<?php
/*
 * File Name:GroupAccountHoursDownloadData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
set_time_limit(3600);
class Service_Data_HouseReport_GroupAccountHoursDownloadData extends Service_Data_HouseReport_GroupOutletData
{

    protected $indexFieldName = 'account_id';
    
    protected $fields = array('account_id'=>'account_id',
    	'user_id'=>'user_id',
        "h0" => "h0","h1" => "h1",
        "h2" => "h2","h3" => "h3",
        "h4" => "h4","h5" => "h5",
        "h6" => "h6","h7" => "h7",
        "h8" => "h8" ,"h9" => "h9",
        "h10" => "h10","h11" => "h11",
        "h12" => "h12","h13" => "h13",
        "h14" => "h14","h15" => "h15",
        "h16" => "h16","h17" => "h17",
        "h18" => "h18","h19"=> "h19",
        "h20" => "h20","h21" => "h21",
        "h22" => "h22","h23" => "h23");
    
    protected $productData = array(
        Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT => array(
            'title'=>'账号信息',
            'title_data' => array(
                'account_id'=>'经纪人ID', #登录账号
                'account_name'=>'姓名', 
            	'account_email'=>'邮箱',
            ),
        ),
        Service_Data_HouseReport_GroupData::BUSINESS_SCOPE => array(
            'title'=>'端口类别',
            'title_data'=> array(
                'business_scope_str'=>'端口类型'
            ),
        ),
        Service_Data_HouseReport_GroupData::REFRESH_HOUSE => array(
            'title'=>'分时段刷新',
            'title_data'=>array(
               'h0'=>'0时', 
               'h1'=>'1时', 
               'h2'=>'2时', 
               'h3'=>'3时', 
               'h4'=>'4时', 
               'h5'=>'5时', 
               'h6'=>'6时', 
               'h6'=>'6时', 
               'h7'=>'7时', 
               'h8'=>'8时', 
               'h9'=>'9时', 
               'h10'=>'10时',
               'h11'=>'11时',
               'h12'=>'12时', 
               'h13'=>'13时', 
               'h14'=>'14时', 
               'h15'=>'15时', 
               'h16'=>'16时', 
               'h17'=>'17时', 
               'h18'=>'18时', 
               'h19'=>'19时', 
               'h20'=>'20时', 
               'h21'=>'21时', 
               'h22'=>'22时', 
               'h23'=>'23时', 
            ),
        ), 
    );
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
    }   

/*{{{getAccountData 得到账号信息*/
    /** 
     * 
     */
    protected function getAccountHoursData($params,$data){
        $res = array();
        /* foreach ($params['accountIds'] as $key => $accountId) {
            $res['data'][$accountId][$this->indexFieldName] = $accountId;
            $res['data'][$accountId]['account_name'] = $params['accountName'][$accountId];
        } */
        $newList = array();
        if ($data[Service_Data_HouseReport_GroupData::REFRESH_HOUSE]) {
        	$newList =  $data[Service_Data_HouseReport_GroupData::REFRESH_HOUSE];
        } else {
        	$newList =  $this->getRefreshHouse($params);
        }
        foreach ($newList['data'] as $key => $row) {
        	$accountId = $row['account_id'];
        	$res['data'][$key][$this->indexFieldName] = $accountId;
        	$res['data'][$key]['account_name'] = $params['accountName'][$accountId];
        }
        return $res;
    }
    /*}}}*/
    /*{{{得到排序字段*/
      /** 
     *@codeCoverageIgnore
     */
    protected function getSortCategory(){
        return array(
            Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT,
            Service_Data_HouseReport_GroupData::BUSINESS_SCOPE,
            Service_Data_HouseReport_GroupData::REFRESH_HOUSE
        );
    }
    /*}}}*/

    public function mergeBizScopeField($dataList){
        if (empty($dataList)) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,"没有生效的订单类别");
        }

        $result = array();
        foreach ($dataList as $bizScope => $listData) {
            $list = $listData['data'];
            if (empty($list)) {
                continue;
            }
            foreach ($list as $item) {
                $accountId = $item['account_id'];
                $result['data']['list'][$accountId.'_'.$bizScope] = $item;

            }
        }
        return $result;
    }

    protected function getRefreshHouse($params){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_AccountReport');
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $groupoutletdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletData');
        $groupAccountList = $groupoutletdataService->groupAccountUseBusinessScope($params);
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        $dataService->setVal('city_id',$params['city_id']);
        $dataService->setVal('countType',$params['countType']);
        foreach ($groupAccountList as $bizScope=>$businessInfo) {
            $houseType = HousingVars::$bizToTypes[$bizScope];
            if (empty($houseType)) {
                continue;
            }

            $accountIds = array_keys($businessInfo);
            if (isset($params['houseType'])) {
                $dataService->setVal('houseType',$houseType);
            }

            //$tmpdataList = $dataService->getAccountHoursReport($accountIds);
            $tmpdataList = $dataService->getAccountHoursReportBySum($accountIds);
            unset($accountIds);
            $tmpNewList = array();
            foreach ($tmpdataList['data'] as $row){
            	if($params['user_id'][$row['account_id']]){
            		$row['user_id'] = $params['user_id'][$row['account_id']];
            	}
            	$tmpNewList['data'][] = $row;
            }
            $totalData[$bizScope]  = $tmpNewList;
        }
        /* var_dump($totalData);exit;
        foreach ($totalData as $row){
        	var_dump($row);echo '<hr/>';
        }
        exit; */
        $dataList = $this->mergeBizScopeField($totalData);

        $newList = $groupdataService->changeData($dataList,$this->fields,$this->indexFieldName);
        return $newList;
    }

  /*{{{groupDownLoadData 合并下载数据*/
    /** 
     *
     * @param unknown $tags 
     * @param unknown $params    
     */
    public function groupDownLoadData($tags,$params){
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        //添加下载时间
        $data[Service_Data_HouseReport_GroupData::REFRESH_HOUSE] = $this->getRefreshHouse($params);
        //添加账号信息
        $accountInfoList = $this->getAccountHoursData($params,$data);
        //添加经纪人邮箱
        $accountEmailList = $this->getCrmAccountEmail($params,$data[Service_Data_HouseReport_GroupData::REFRESH_HOUSE]['data']);
        $data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT] = array_merge_recursive($accountInfoList,$accountEmailList);
        //var_dump($data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT]);
        //获得端口类别
        //设置为放心房  用来取用户订单详情
        //$params['countType'] =array(2);
        $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE] = $groupdataService->getBusinessScope($params,$this->indexFieldName,reset($params['countType']));
        $getSortCategory = $this->getSortCategory();
        $res = $groupdataService->mergeData($data,$getSortCategory,$this->productData,$this->indexFieldName);
        return $res;
    }
    /*}}}*/
}
