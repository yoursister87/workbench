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
class Service_Page_HouseReport_Org_GetAgentList{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_Customer
	 */
	protected $objServiceHouseCustomer;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		
	}
	/***
	 *主要作用是在组织架构经纪人级别列表中加入经纪人购买的端口类型
	 * */
	protected function mergePort($whereConds,$accountData){
		if(empty($whereConds) && empty($accountData)){
			return array();
		}
		$accountLists = array_column($accountData['data'],'AccountId');
		$accountIds = implode(',',$accountLists);
		$fields = array("AccountId","BussinessScope");
		$conds = $whereConds['customer_id'];
		$timedate = time();
		$arrConds  = array("AccountId IN ( $accountIds )","CustomerId IN ( $conds )","InDurationBeginTime <= $timedate","InDurationBeginTime > 0","CountType IN (1)","InDuration = 1");
		$objBusinessInfo = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
		$businessInfo = $objBusinessInfo->getAccountBusinessInfolist($fields,$arrConds);
		foreach($businessInfo as &$value){
			$value['BussinessScope'] = HousingVars::$bizTxt[$value['BussinessScope']];
		}
		foreach($accountData['data'] as &$value){
			foreach($businessInfo  as $value1){
				if($value['AccountId'] == $value1['AccountId']){
					$value['BussinessScope'][] =  $value1['BussinessScope'];	
				}
			}
		}
		foreach($accountData['data'] as &$value){
			if(empty($value['BussinessScope'])){
				$value['BussinessScope'] = '无';
				continue;
			}
			//if(count($value['BussinessScope']) != 1 && count($value['BussinessScope']) != 0){
			if(is_array($value['BussinessScope'])){
				$value['BussinessScope'] = implode('/',$value['BussinessScope']);	
			}/*else{
				$value['BussinessScope'] = $value['BussinessScope'][0];	
			}*/
		}
		return $accountData;
	}
	/** 
	 *@codeCoverageIgnore
	 **/
	public function returnObj($pageConfig){
		$obj = new Util_HouseReport_Page($pageConfig);
		return $obj->show();
	}
	public function execute($arrInput){
		$arrFields = array("AccountId","CustomerId","AccountName","CreatedTime","UserId","CellPhone","Email","Status","OwnerType","PremierExpire");
		if(!is_numeric($arrInput['customer_id'])){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		try {
			if (empty($arrInput['page'])){
				$arrInput['page'] = 1;
			}
			if (empty($arrInput['pageSize'])){
				$arrInput['pageSize'] = 20;
			}
			$whereConds = array('customer_id'=>$arrInput['customer_id']);
			$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
			$customerAccountCount = $this->objServiceCustomerAccount->getAccountCountByCustomerId($whereConds);
			$pageConfig = array(
					'total_rows' =>isset($customerAccountCount['data']) ? $customerAccountCount['data'] : 0,
					'list_rows' => $arrInput['pageSize'],
					'now_page' =>$arrInput['page'],
					'method' =>'ajax',
					'func_name'=>'pagination',
					'parameter' =>"'agent'",
			);
			$pageStr = $this->returnObj($pageConfig);
			$orderArr = array('PremierExpire'=>'DESC','CreatedTime'=>'DESC');
			$list = $this->objServiceCustomerAccount->getAccountListByCustomerId($whereConds, $arrFields ,$arrInput['page'], $arrInput['pageSize'], $orderArr);
			$res = $this->mergePort($whereConds,$list);
			if($res['errorno']){
				return $res;
			}
			$arrFields = array("CustomerId","FullName","CompanyId");
			/* foreach ($res['data'] as $key=>$row){
				if($row['PremierExpire']>time()){
					$row['PremierExpire'] = "生效中";
				}else{
					$row['PremierExpire'] = "已过期";
				}
				$row['key'] = md5($row['UserId'].'_'.$row['Email']);
				$this->data['data']['data'][$key] = $row;
				$customerInfo = $this->objServiceHouseCustomer->getCustomerInfoByCustomerId($row['CustomerId'], $arrFields);
				$this->data['data']['data'][$key]['FullName'] = empty($customerInfo['data']["FullName"]) ? '' : $customerInfo['data']["FullName"];
			} */
			$this->data['data']['pageStr'] = $pageStr;
			$this->data['data']['totalNum'] = $customerAccountCount['data'];
			//$this->data['data']['data'] = $this->getCrmEmail($res['data']);
			$objSearchCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount');
			$resToEmail = $objSearchCustomerAccount->getCrmEmail($res['data']);
			$this->data['data']['data'] = $resToEmail['data'];
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}
	}
}
