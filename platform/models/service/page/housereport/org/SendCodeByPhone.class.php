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
class Service_Page_HouseReport_Org_SendCodeByPhone{
	public function __construct(){
		$this->data['data']['errorno'] =  ErrorConst::SUCCESS_CODE;
		$this->data['data']['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		if (preg_match ( "/^1\d{10}$/", $arrInput['phone']) == false) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		try{
			/**
			 * @var Gj_Fang_Api_Platform_PhoneCode
			 */
			$objPhoneCode = Gj_LayerProxy::getProxy('Gj_Fang_Api_Platform_PhoneCode');
			$res = $objPhoneCode->sendCodeByPhone($arrInput['phone']);
		}catch(Exception $e){
			$this->data['data']['errorno'] = $e->getCode();
			$this->data['data']['errormsg'] = $e->getMessage();
		}
		$this->data = $res;
		return $this->data;

	}
}
