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
class Service_Page_HouseReport_Org_CheckEmail{
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
	* 获取树状结构
	* @param array $arrInput
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		try{
			if (empty($arrInput['newemail'])) {
				$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
				$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			}else{
				$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
				$res = $this->objServiceCustomerAccount->getUid($arrInput['newemail']);
				if($res['errorno']){
					return $res;
				}
				//如果已注册uc
				if ($res['data'] > 0) {
					$bizInfo = $this->objServiceCustomerAccount->getBizTypeList($res['data']);
					/* if ($bizInfo['data'][0]===1) {
						//如果已经绑定，则不能够重复绑定
						$this->data = ErrorCode::returnData(2105);
					} else {
						//如果没有绑定，可以修改,在页面提示输入密码
						$this->data = ErrorCode::returnData(2104);
					} */
					$this->data = ErrorCode::returnData(2104);
				}else{//如果没有注册
					$this->data = ErrorCode::returnData(2103);
				}
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}