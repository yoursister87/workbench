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
class Service_Page_HouseReport_Org_CheckAgentPwd{
	protected $data;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		if (empty($arrInput['newemail']) || empty($arrInput['passwd'])) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		/**
		 * @var Service_Data_Gcrm_CustomerAccount
		 */
		$objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
		//验证新的用户密码、密码是否正确
		$res = $objServiceCustomerAccount->login($arrInput['newemail'], $arrInput['passwd']);
		return $res;
	}
}