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
class Service_Page_HouseReport_Org_AddOrgByCustomer{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_Customer
	 */
	protected $objServiceHouseCustomer;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	public function __construct(){
		$this->data['data'] = 1;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	//{{{execute
	/**
	 * 获取树状结构
	 * @param array $arrInput
	 * @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	 */
	 public function execute($arrInput){
	 	$arrFields = array("CustomerId","FullName","CompanyId");
	 	try{
	 		$arrInput['customer_id'] = explode(',', $arrInput['customer_id']);
		 	foreach ($arrInput['customer_id'] as $customer_id){
		 		if (!is_numeric($customer_id)) {
		 			continue;
		 		}
		 		$this->objServiceHouseCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
		 		$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
		 		$customerInfo = $this->objServiceHouseCustomer->getCustomerInfoByCustomerId($customer_id, $arrFields);
		 		if (!isset($customerInfo['data']['FullName'])) {
		 			continue;
		 		}
		 		$arrRows=array(
		 				'pid' =>$arrInput['pid'],
		 				'company_id' =>$arrInput['company_id'],
		 				'customer_id' =>$customer_id,
		 				'level' =>$arrInput['level'],
		 				'create_time' =>time(),
		 				'title' =>$customerInfo['data']['FullName'],
		 		);
		 		$res = $this->objServiceHouseManagerAccount->addOrg($arrRows);
		 	}
	 	}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
	 	return $this->data;
	 }
}