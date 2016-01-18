<?php
/*
 * File Name:AccountAjax.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class	Service_Page_HouseReport_Report_OutletRealDataTimeAjax{
	protected $data;
	protected $sortIndexName = 'accountIds';
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;	
	}	
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}   
	} 
	protected function groupOutletData($params){
		$pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
		$page = intval($params['page']) <= 0?1:intval($params['page']);		
		if (isset($params['stype']) && is_numeric($params['stype'])&& !empty($params['keyword'])){
			$searchObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount');    
			$whereConds['search_keyword'] = $params['keyword'];
			$whereConds['search_type'] = $params['stype'];
			$whereConds['id'] = $params['userId'];
			//只搜索付费账号
			$whereConds['induration'] = true;
			$dataServiceRet = $searchObj->SearchAgent($whereConds);
			$dataServiceRetCount['data'] = count($dataServiceRet['data']);				
		}else{
			$accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
			//当前存在的pid是门店id
			$id = HttpNamespace::getGET('pid',null);
			$orgId = !empty($id)?$id:$params['userId'];
			//查到所有的该公司的所以门店id
			$customerIds = $pageDataSerivce->getAllOutlet($orgId);
            if (empty($customerIds)) {
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'该端口类别下面没有数据');
            }
			$whereConds = array();
			$whereConds['customerId'] = $customerIds;
			$whereConds['businessScope'] = $params['businessScope'];
			$whereConds['effective'] = true;
			$dataServiceRet = $accountObj->getAccountListByCompanyId($params['companyId'],$whereConds,$page,Service_Data_HouseReport_GroupData::PAGE_SIZE);
			$dataServiceRetCount = $accountObj->getAccountListByCompanyIdCount($params['companyId'],$whereConds);	
		}
		if (empty($dataServiceRet['data'])) {
			throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'该端口类别下面没有数据');
		}	
		$accountList = $dataServiceRet['data'];
		$tmpList = array();
		foreach ($accountList as $item) {
			//得到所有的accountId
			$tmpList[$item['AccountId']] = $item['AccountId'];
		}
		$accountIdList = array_keys($tmpList);
		$accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
		$accountIdList = $accountObj->getAccountInfoById($accountIdList,array('AccountId','AccountName','CustomerId'));
		$tmpList1 = array();
		//把名字查询其中
		foreach ($accountIdList['data'] as $item) {
			$tmpList1[$item['AccountId']] = array('accountId'=>$item['AccountId'],'accountName'=>$item['AccountName'],'custoemrId'=>$item['CustomerId']);
		}

		$utilUrl = new Util_CommonUrl();
		$accountData['data']['list'] = $tmpList1; 
		//得到总页数
		$res['data']['count'] = $dataServiceRetCount['data'];
		$url['c'] = 'report';

		$res['data']['title'] = '姓名';

		if (!$accountData['errorno']) {
			foreach ($accountData['data']['list'] as $item) {
				$url['account_id'] = $item['accountId'];
				//$url['customer_id'] = $item['custoemrId'];
				$url['level'] = 9;
				$href = $utilUrl->createUrl($url, null);
				$res['data']['title_list'][$item['accountId']] = array('name'=>$item['accountName']/*,'href'=>$href*/);
				//添加排序索引
				$res[$this->sortIndexName][] = $item['accountId'];
			}
		} else {
			throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"组织结构数据有误");
		}
		return $res;
	}
	public function execute($arrInput){
		$pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');	
		$checkData = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
		$groupDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
		$groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletData');
		$params =  array();
		try{
			$params =  $pageDataSerivce->getCommonParams($arrInput);
			//精品或者竞价
			if (isset($arrInput['countType'])) {
				//设置房源
				$params['countType']  = $checkData->setCountType($arrInput['countType']);;
			}
			//设置端口类别
			$params['businessScope']  = $checkData->setBusinessScope($arrInput['businessScope']);
			$params['houseType'] = $pageDataSerivce->setHouseType($params);	
		}catch(Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if(0 == $this->data['errorno']){
			$tags = array();
			$productGroup = array_merge($params['product'],$params['dtype']);	
			foreach ($productGroup as $product) {
				if (isset(Service_Data_HouseReport_GroupData::$productStr2Indx[$product])) {
					$tags[] = Service_Data_HouseReport_GroupData::$productStr2Indx[$product];
				}
			}
			try{
				$accountData = $this->groupOutletData($params);
				$params['accountIds'] = $accountData[$this->sortIndexName];
				$res = $groupService->groupAjaxData($tags,$params);//主要的文件在这个类中
				$this->data['data']['dataList'] = $res;
				$titleList = $groupDataService->matchTitleData($accountData,$res);
				//$this->data['data']['titleList'] = $accountData['data'];
				$this->data['data']['titleList'] = $titleList['data'];
				//var_dump($this->data['data']);exit;
				//分页页码
				$this->data['data']['page'] = $pageDataSerivce->getPageStr($params['page'], $accountData['data']['count'], $params['level']);
			}catch(Exception $e){
				$this->data['errorno'] = $e->getCode();
				$this->data['errormsg'] = $e->getMessage();
			}
		}
			return $this->data;
	}
} 
