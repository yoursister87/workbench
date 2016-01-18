<?php
class Service_Page_HouseReport_Org_VerifyCode{
	/**
	 * @var  Gj_Fang_Api_PhoneCode
	**/
	protected $objGjFangApiPhoneCode;
	public function __construct(){
		$this->objGjFangApiPhoneCode =  Gj_LayerProxy::getProxy('Gj_Fang_Api_Platform_PhoneCode');
		$this->data['data']['errorno'] =  ErrorConst::SUCCESS_CODE;
		$this->data['data']['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		try{
			$res = $this->objGjFangApiPhoneCode->verifyPhone($arrInput['phone'],$arrInput['code']);
			if ($res['errorno']){
				$this->data['data']=  $res;
				return $this->data;
			}
		}catch(Exception $e){
			 $this->data['data']['errorno'] = $e->getCode();
			 $this->data['data']['errormsg'] = $e->getMessage();
		}
		$this->data['data']['msg'] = $res['data']['msg'];
		$this->data['data']['codeVerify'] = $res['data']['codeVerify'];
		return $this->data;
	}
}
