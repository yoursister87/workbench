<?php
class Service_Page_HouseReport_Org_SendVerifyCode{
	/**
	* @var Service_Page_HouseReport_Org_SendVerifyCode
	 */
	protected $objServicePageHouseReportOrgSendVerifyCode;
	public function __construct(){
		$this->objServicePageHouseReportOrgSendVerifyCode = Gj_LayerProxy::getProxy('Gj_Fang_Api_Platform_PhoneCode');
		$this->data['data']['errorno'] =  ErrorConst::SUCCESS_CODE;
		$this->data['data']['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		try{
			$res = $this->objServicePageHouseReportOrgSendVerifyCode->sendVerifyCode($arrInput['phone']);
			if ($res['errorno']){
				return $res;
			}
		}catch(Exception $e){
			$this->data['data']['errorno'] = $e->getCode();
			$this->data['data']['errormsg'] = $e->getMessage();
		}
		$this->data['data']['msg'] = $res['data']['msg'];
		return $this->data;

	}
}
