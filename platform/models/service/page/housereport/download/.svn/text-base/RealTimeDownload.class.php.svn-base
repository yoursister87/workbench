<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
set_time_limit(3600);
class Service_Page_HouseReport_Download_RealTimeDownload{
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
	/**
	 * {{{execute
	 */
	public function execute($arrInput){
		error_reporting('E_ALL');
		ini_set('error_display', true);
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
				$groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_RealTimeSumDownloadData');
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
				$arrInput['businessScope'] = array(1,3,4,8);
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
				$accountData = $groupService->groupOutletData($params);
				$params['city_id'] = $accountData['city_id'];
				$params['accountIds'] = $accountData[$this->sortIndexName];
				$params['accountName'] = $accountData['accountName'];
				$params['phone'] = $accountData['cellPhone'];
				$params['customerId'] = $accountData['customerId'];
				$params['ownerType'] = $accountData['ownerType'];
				$params['user_id'] = $accountData['user_id'];
				$params['status'] = $accountData['status'];
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
}
