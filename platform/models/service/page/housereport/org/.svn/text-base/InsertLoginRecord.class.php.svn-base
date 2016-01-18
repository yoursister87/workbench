<?php
class Service_Page_HouseReport_Org_InsertLoginRecord{
	/**
	 *@ var Service_Data_Gcrm_CustomerAccountLoginEvent
	 ***/	
	protected $objServiceDataGcrmCustomerAccountLoginEvent;
	public function __construct(){
		$this->objServiceDataGcrmCustomerAccountLoginEvent = Gj_LayerProxy::getProxy("Service_Data_Gcrm_CustomerAccountLoginEvent");
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE; 
		 $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG; 
	}
	public function execute($arrInput){
		try{
			if(!is_array($arrInput)){
				 $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
				 $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;	 
			}else{
				 $city = HttpNamespace::ip2city(long2ip($arrInput['ip']));
				 if($city['city_id'] == -1 &&$city['district_id'] == -1){
					$arrInput['city'] = '';
				 }else{
					   $arrInput['city'] = $city['city_name']. $city['district_name'];
				 }
				$res = $this->objServiceDataGcrmCustomerAccountLoginEvent->addCustomerLoginLog($arrInput);
				if ($res['errorno']){
					return $res;
			   }
			}
		}catch (Exception $e){
			 $this->data['errorno'] = $e->getCode();
			 $this->data['errormsg'] = $e->getMessage();
		}		
		return $this->data;
	}
}



