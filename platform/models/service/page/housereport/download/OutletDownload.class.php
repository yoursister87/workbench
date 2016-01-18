<?php
/*
 * File Name:AccountDownload.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
set_time_limit(3600);
class Service_Page_HouseReport_Download_OutletDownload
{
   
     protected $sortIndexName = 'accountIds';

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }

    /*
     *@codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    private function getTodayDownladIndex($params){
        $sTime = strtotime($params['date']['sDate']);
        $eTime = strtotime($params['date']['eDate']);
        $accountIdList = $params['accountIds'];
        $index = array();
        foreach ($accountIdList as $accountId){
            for ($i=$sTime;$i<=$eTime;$i+=86400) {
                $day = date('Y-m-d',$i);
                $index[] = "{$day}_{$accountId}";
            }
        }
        return $index;
    }
    //获得相应这个等级下面的所有门店
    private function getAllOutlet($orgId){
        $houseAccount =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $customerIds = array();
        $tmpData = $houseAccount->getChildTreeByOrgId($orgId,4,array(),null,null);
        foreach($tmpData['data']['list'] as $item) {

            $customerIds[] = $item['customer_id'];
        }
        return $customerIds;
    }
    //{{{得到汇总表格下载需要的数据
    protected function getDownLoadData($downData){
        //第一标题
        $firstTitle = array();
        //第二标题
        $assistantTitle = array();
        $sheetName = '日均表格';
        //下载的数据
        foreach ($downData as $dataList) {
            $firstTitle[0][] = array(
                'name'=>$dataList['title'],
                'width'=>count($dataList['title_data']),
            );
            $assistantTitle = array_merge($assistantTitle,$dataList['title_data']);
        }
        $data = array();
        $res = array();
        $tmpData = reset($downData);
        foreach ($tmpData['total_data'] as $index=>$item) {
            $data[] = $item;
        }
        //副标题放入数组的第一个值
        array_unshift($data,$assistantTitle);
        $res[$sheetName]['title'] = $firstTitle;
        $res[$sheetName]['data'] = $data;
        return $res;
    }
    //}}}
    protected function createCsv($data,$filename = 'report'){
        $houseExcel = new Util_HouseExcel();
        $tmpData = reset($data);
        $houseExcel->createCsv($tmpData,$filename);
    }
    //{{{multi_array_sort
    /**
     * 对多维数组进行排序
     * @param $multi_array 数组
     * @param $sort_key需要传入的键名
     * @param $sort排序类型
     */
    protected function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC) {
    	if (is_array($multi_array)) {
    		foreach ($multi_array as $row_array) {
    			if (is_array($row_array)) {
    				$key_array[] = $row_array[$sort_key];
    			} else {
    				return FALSE;
    			}
    		}
    	} else {
    		return FALSE;
    	}
    	array_multisort($key_array, $sort, $multi_array);
    	return $multi_array;
    }//}}}
    
    /*{{{groupAccountData 组织结构数据*/
    /** 
     * 
     * @param unknown $params    
     *  数据格式
     *  array(
     *       'data'=>array(
     *          'title'=>'日期',
     *          'title_list'=> array(
     *              orgId=>array('name'=>'name','href'=>'href')
     *              orgId=>array('name'=>'name','href'=>'href')
     *          ),
     *          'count'=>条数
     *       )
     *      'accountIds' => array()
     *       
     */
    protected function groupOutletData($params){
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
        $dataServiceRet = array();
        //如果有搜索
        if (isset($params['stype']) && is_numeric($params['stype'])
           && !empty($params['keyword'])
        ){
            $searchObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount');      
            $whereConds['search_keyword'] = $params['keyword'];
            $whereConds['search_type'] = $params['stype'];
            $whereConds['id'] = $params['userId'];
            //只搜索付费账号
            $whereConds['induration'] = true;
            $dataServiceRet = $searchObj->SearchAgent($whereConds);
        } else {
            $accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
            //当前存在的pid是门店id
            $id = HttpNamespace::getGET('pid',null);
            $orgId = !empty($id)?$id:$params['userId'];
            //查到所有的该公司的所以门店id
            $customerIds = $pageDataSerivce->getAllOutlet($orgId);
            if(empty($customerIds)){
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'没有符合条件的数据 !');
            }
           //是否是过期的订单
           if ($params['islost']) {
               $whereConds['customerId'] = $customerIds;
               $whereConds['businessScope'] = $params['businessScope'];
               //取精品订单 后一段时间作为过期端口的开始时间  查找有效和无效的端口
               $whereConds['effective'] = false;

               //一般端口的过期时间在 一天的 23:59:59秒
               $whereConds['maxTime']['<=maxEndTime'] = strtotime($params['date']['eDate']) + 3600 * 24 -1;
               $whereConds['maxTime']['>=maxEndTime'] = strtotime($params['date']['sDate']);
               //以半年为一个时间范围
              // $whereConds['maxTime']['>=minBeginTime'] = strtotime($params['date']['eDate']) - 3600 * 24 * 178;
               $dataServiceRet = $accountObj->getAccountListByCompanyId($params['companyId'],$whereConds,1,null);
           } else {
               $whereConds = array();
               $whereConds['customerId'] = $customerIds;
               $whereConds['businessScope'] = $params['businessScope'];
               $whereConds['effective'] = true;
               $whereConds['inTime']['<=minBeginTime'] = strtotime($params['date']['eDate']);
               $dataServiceRet = $accountObj->getAccountListByCompanyId($params['companyId'],$whereConds,1,null);

               //$dataServiceRetCount = $accountObj->getAccountListByCompanyIdCount($params['companyId'],$whereConds);
           }
        }
        if (empty($dataServiceRet['data'])) {
            if ($params['islost']===true){
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'没有过期数据');
            } else {
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'该端口类别下面没有数据');
            }
        }
        $accountList = $dataServiceRet['data'];
        $tmpList = array();
        foreach ($accountList as $item) {
            //得到所有的accountId
            $tmpList[$item['AccountId']] = $item['AccountId'];
        }
        $accountIdList = array_keys($tmpList);
        $accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $accountIdList = $accountObj->getAccountInfoById($accountIdList,array('AccountId','UserId','AccountName','CityId','CellPhone','CustomerId','OwnerType','Status'));
        $tmpList1 = array();
        $cityList = array();
        //把名字查询其中
        foreach ($accountIdList['data'] as $item) {
            $tmpList1[$item['AccountId']] = array(
            	'user_id'=>$item['UserId'],
            	'accountId'=>$item['AccountId'],
            	'accountName'=>$item['AccountName'],
            	'cellPhone'=>$item['CellPhone'],
                'customerId'=>$item['CustomerId'],
                'ownerType'=>$item['OwnerType'],
                'status'=>$item['Status'],
            );
            $cityList[] = $item['CityId'];
        }
		$cityArr = array_count_values($cityList);
		$sum = -1;
		$city_id = 0;
		foreach ($cityArr as $key=>$val){
			if ($sum <= $val) {
				$city_id = $key;
				$sum = $val;
			}
		}
        $accountData['data']['list'] = $tmpList1; 
        //得到总页数       
        //$res['totalCount'] = $dataServiceRetCount['data'];
        if (!$accountData['errorno']) {
            foreach ($accountData['data']['list'] as $item) {
                //添加排序索引
                $res[$this->sortIndexName][$item['accountId']] = $item['accountId'];
                $res['user_id'][$item['accountId']] = $item['user_id'];
                $res['accountName'][$item['accountId']] = $item['accountName'];
                $res['cellPhone'][$item['accountId']] = $item['cellPhone'];
                $res['customerId'][$item['accountId']] = $item['customerId'];
                $res['ownerType'][$item['accountId']] = $item['ownerType'];
                $res['status'][$item['accountId']] = $item['status'];
            }
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"组织结构数据有误");
        }
        $res['city_id'] = $city_id;
        return $res;
    }
    /*}}}*/
    
    /**
	 * {{{execute
	 */
    public function execute($arrInput){
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
        $downloadType = $arrInput['downloadType'];
        $arrInput['islost'] = false;
        $date = $arrInput['date']['sDate'].'至'.$arrInput['date']['eDate'];
        $params  = array();
        $params = $pageDataSerivce->getCommonParams($arrInput);
        $params['downloadFilename'] = $date."汇总数据";

        switch ($downloadType) {
            case '1':
                //汇总数据
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletSumDownloadData');
                break;
            case '2':
                //平均数据
                $params['downloadFilename'] = $date."日均数据";
                $params['isavg'] = true;
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletAvgDownloadData');
                break;
            case '3':
                $params['downloadFilename'] = $date."每日详情报表";
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletTodayDownloadData');
                $this->sortIndexName = 'date_AccountId';
                //分时段刷新数据
                break;
            case '4':
            case '5':
                if ($downloadType == 4){
                    //精品分时段刷新
                    $arrInput['countType'] = array(1);
                    $params['downloadFilename'] = $date."精品分时段刷新";
                }
                //放心房分时段刷新
                if ($downloadType == 5){
                    $arrInput['countType'] = array(2);
                    $params['downloadFilename'] = $date."放心房分时段刷新";
                }
                
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupAccountHoursDownloadData');
                break;
            case '6':
                //过期的经纪人报表
                $arrInput['islost'] = true;
                $params['downloadFilename'] = $date."过期的经纪人报表";
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletSumDownloadData');
                break;
            default:
                $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletSumDownloadData');
                break;
        }

        $checkData = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
        $groupDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');

        try{
            if (isset($arrInput['date'])){
                //设置时间
                $params['date'] = $checkData->setDate($arrInput['date']);
                if ($downloadType == 3){
                    $params['date'] = $checkData->setEverydayDownloadDate($arrInput['date']);
                }
            }

            if (isset($arrInput['houseType'])) {
                //设置房源
                $params['houseType'] = $checkData->setHouseType($arrInput['houseType']);;
            }

            //精品或者竞价
            if (isset($arrInput['countType'])) {
                //设置房源
                $params['countType']  = $checkData->setCountType($arrInput['countType']);;

            }
            if(empty($arrInput['businessScope'])){
            	$arrInput['businessScope'] = array(0,1,2,3,4,5,6,7,8,9);
            }
            //设置端口类别
            $params['businessScope']  = $checkData->setBusinessScope($arrInput['businessScope']);
            $params['houseType'] = $pageDataSerivce->setHouseType($params);

            $params['islost']  = $arrInput['islost'];

        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if (!$this->data['errorno']) {
            $tags = array();
            //组合商品类型 和数据类型
            $productGroup = array_merge($params['product'],$params['dtype']);
            foreach ($productGroup as $product) {
                if (isset(Service_Data_HouseReport_GroupData::$productStr2Indx[$product])) {
                    $tags[] = Service_Data_HouseReport_GroupData::$productStr2Indx[$product];    
                }
            }
            try{
                $accountData = $this->groupOutletData($params);
                $params['city_id'] = $accountData['city_id'];
                $params['accountIds'] = $accountData[$this->sortIndexName];
                $params['accountName'] = $accountData['accountName'];
                $params['phone'] = $accountData['cellPhone'];
                $params['customerId'] = $accountData['customerId'];
                $params['ownerType'] = $accountData['ownerType'];
                $params['user_id'] = $accountData['user_id'];
                $params['status'] = $accountData['status'];
                if ($this->sortIndexName == 'date_AccountId') {
                    $accountData['date_AccountId'] = $this->getTodayDownladIndex($params);
                    $params['detaAccountId'] = $accountData['date_AccountId'];
                }
                $res = $groupService->groupDownLoadData($tags,$params);
            	$res = $groupDataService->matchTableData($accountData,$res,$this->sortIndexName);
            	$res[1]['total_data'] = $this->multi_array_sort($res[1]['total_data'],2);
            	$downData = $this->getDownLoadData($res);
                $this->createCsv($downData,$params['downloadFilename']);
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;
    }	
    /*}}}*/
}
