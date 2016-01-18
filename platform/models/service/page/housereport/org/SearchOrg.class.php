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
class Service_Page_HouseReport_Org_SearchOrg{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	/**
	 * @var Service_Data_Gcrm_CompanyStoresAddress
	 */
	protected $objServiceCompanyStoresAddress;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	/**
	 * @var Service_Data_Gcrm_CustomerAccountLoginEvent
	 */
	protected $objServiceCustomerAccountLoginEvent;
	/**
	 * @ Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount
	 */
	protected $objSearchCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
	}
	protected function conditionSearchOne(&$data){
		$timedate = time();
		$fields = array("AccountId","BussinessScope");
		$accountIds = $data['AccountId'];
		$CustomerId =  $data['CustomerId'];
		$arrConds  = array("AccountId IN ( $accountIds )","CustomerId IN ( $CustomerId)","InDurationBeginTime <= $timedate","InDurationBeginTime >0","InDuration = 1","CountType IN (1)");
		$objBusinessInfo = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
		$businessInfo = $objBusinessInfo->getAccountBusinessInfolist($fields,$arrConds);
		$data['BussinessScope'] =  HousingVars::$bizTxt[$businessInfo[0]['BussinessScope']];
	}
	/** 
	 *@codeCoverageIgnore
	 **/
	protected function searchBusinessInfo($data){
		if(empty($data['data']))
			return array();
		foreach($data['data'] as &$value){
			if($value['PremierExpire'] =='生效中'){
					$this->conditionSearchOne($value);				
			}else{
				$data['data'][0]['BussinessScope'] = '无';
			}
		}
		return $data;
	}
	//{{{execute
	/**
	* 搜索
	* @param array $arrInput
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		if (empty($arrInput['sTime'])){
			$arrInput['sTime'] = strtotime('today');
		}else{
			$arrInput['sTime'] = strtotime($arrInput['sTime']);
		}
		if (empty($arrInput['eTime'])) {
			$arrInput['eTime'] = strtotime('tomorrow')-1;
		}else{
			$arrInput['eTime'] = strtotime($arrInput['eTime'])+24*3600-1;
		}
		if(!is_numeric($arrInput['search_type']) || $arrInput['sTime']==0 || $arrInput['eTime']==0){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		if (empty($arrInput['page'])){
			$arrInput['page'] = 1;
		}
		if (empty($arrInput['pageSize'])){
			$arrInput['pageSize'] = 15;
		}
		if ($arrInput['search_type']==1) {
			$res = $this->SearchOrg($arrInput);
		}else{
			//父节点路径
			$arrInput['isShowParent'] = true;
			$arrInput['isShowEamil'] = true;
			$objSearchCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount');
			$list= $objSearchCustomerAccount->SearchAgent($arrInput);
			$data = $this->searchBusinessInfo($list);
			//为分页
			$res['errorno'] = $data['errorno'];
			$res['errormsg'] = $data['errormsg'];
			$res['data']['data'] = $data['data'];
		}
		$this->data = $res;
		return $this->data;
	}//}}}
	//{{{ SearchOrg
	/**
	 * 获取搜索门店列表
	 * @param unknown $arrInput			搜索条件
	 * @return unknown|string
	 */
	protected function SearchOrg($arrInput){
		$data = array();
		try{
			$whereConds = array(
					'title' =>$arrInput['search_keyword'],
			);
			$res = $this->objServiceHouseManagerAccount->getChildTreeByOrgId($arrInput['id'], 4, $whereConds, $arrInput['page'], $arrInput['pageSize']);
			$pageConfig = array(
					'total_rows' =>isset($res['data']['count']) ? $res['data']['count'] : 0,
					'list_rows' => $arrInput['pageSize'],
					'now_page' =>$arrInput['page'],
					'method' =>'ajax',
					'func_name'=>'pagination',
					'parameter' =>'pagination',
			);
			$pageUtil = new Util_HouseReport_Page($pageConfig);
			$pageStr = $pageUtil->show();
			$data['data']['pageStr'] = $pageStr;
			if($res['errorno']){
				return $res;
			}
			$data ['errorno'] = $res['errorno'];
			$data ['errormsg'] = $res['errormsg'];
			foreach ($res['data']['list'] as $key=>$row){
				//获取请求经纪人的url
				$urlParams = array(
						'a'=>'getAgentList',
						'customer_id'=>$row['customer_id']
				);
				$row['url'] = Util_CommonUrl::createUrl($urlParams);
				//父节点路径
				$parentNode = $this->objServiceHouseManagerAccount->getTreeByOrgId($row['id'], false);
				if (is_array($parentNode['data'])) {
					$row['title'] = $parentNode['data'][2]['activeList']['title']."=>".$parentNode['data'][3]['activeList']['title']."=>".$parentNode['data'][4]['activeList']['title'];;
				}
				//经纪人的数量
				$whereConds = array(
						'customer_id'=>$row['customer_id'],
						'sTime'=>$arrInput['sTime'],
						'eTime'=>$arrInput['eTime'],
				);
				$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
				$orgTotal = $this->objServiceCustomerAccount->getAccountCountByCustomerId($whereConds);
				$row['num'] = empty($orgTotal["data"]) ? 0 : $orgTotal["data"];
				$this->objServiceCustomerAccountLoginEvent = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccountLoginEvent');
				$loginTotal = $this->objServiceCustomerAccountLoginEvent->getCustomerLoginCount($row['id'], $arrInput['sTime'], $arrInput['eTime']);
				$row['loginNum'] = empty($loginTotal["data"]) ? 0 : $loginTotal["data"];
				//获取门店地址
				$arrFileds = array('id','district_id','district_name','street_id','street_name','address');
				$this->objServiceCompanyStoresAddress = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CompanyStoresAddress');
				$addressInfo = $this->objServiceCompanyStoresAddress->getStoreInfoByUserId(NULL, $row['customer_id'], NULL, NULL, $arrFileds);
				$row['address_id'] = $addressInfo['data']['id'];
				$row['district_id'] = $addressInfo['data']['district_id'];
				$row['street_id'] = $addressInfo['data']['street_id'];
				$row['address'] = $addressInfo['data']['address'];
				$data['data']['data'][$key] = $row;
			}
		}catch (Exception $e){
			$data['errorno'] = $e->getCode();
			$data['errormsg'] = $e->getMessage();
		}
		return $data;
	}//}}}
	
	
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}
	}
}
