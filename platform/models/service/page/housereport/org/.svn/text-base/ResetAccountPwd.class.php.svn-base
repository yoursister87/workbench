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
class Service_Page_HouseReport_Org_ResetAccountPwd{
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
	 * 重置密码
	 * @param array $arrInput				参数数组
	 * @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	 */
	 public function execute($arrInput){
	 	if(!is_numeric($arrInput['UserId'])){
	 		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
	 		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
	 	}else{
	 		try{
	 			$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
	 			$res = $this->objServiceCustomerAccount->setPassword($arrInput['UserId'],$arrInput['passwd']);
	 			if($res['errorno']){
	 				return $res;
	 			}
	 			$logInfo = 'LogOrgId=' . $arrInput['id'] . "changeInfo:user_id=". $arrInput['UserId'] . "changePassword=" . $arrInput['passwd'];
	 			Gj_Log::trace($logInfo);
	 			$this->data = $res;
	 		}catch (Exception $e){
				$this->data['errorno'] = $e->getCode();
		    	$this->data['errormsg'] = $e->getMessage();
			}
		}
		return $this->data;
	 }
}
