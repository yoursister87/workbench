<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_HouseReport_Org_AgentToCustomer{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	//{{{execute
	/**
	* 经纪人转门店
	* @param array $arrInput
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		if (!is_string($arrInput['AccountIds'])) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		}else{
			try{
				$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
				$res = $this->objServiceCustomerAccount->UpdateCustomer($arrInput);
				if ($res['errorno']) {
					return $res;
				}
				$accountidArr = explode(',', $arrInput['AccountIds']);
				foreach ($accountidArr as $accountId){
					$this->addQueueAccountChange($accountId);
				}
				$this->data = $res;
			}catch (Exception $e){
				$this->data['errorno'] = $e->getCode();
				$this->data['errormsg'] = $e->getMessage();
			}
		}
		return $this->data;
	}
	//{{{addQueueAccountChange
	/**
	 * 转门店成功，插入到队列，修改account_bussiness_info
	 * @param unknown $accountId	
	 */
	 protected function addQueueAccountChange($accountId){
		 $arrFileds = array(
		 		'account_id' => $accountId,
		 		'action' => -1,
		 		'value' => -1,
		 		'create_at' => time(),
		 		'author' => 'EMP',
		 );
		 $accountChange = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountChange');
		 $res = $accountChange->addAccountChange($arrFileds);
		 return $res;
	 }//}}}
}