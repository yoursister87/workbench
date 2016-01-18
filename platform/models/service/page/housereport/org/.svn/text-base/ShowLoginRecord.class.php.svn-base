<?php
class Service_Page_HouseReport_Org_ShowLoginRecord{
	/**
	 *@var Service_Data_Gcrm_CustomerAccountLoginEvent 
	 * */
	const PAGE_SIZE = 20;
	protected $objServiceDataGcrmCustomerAccountLoginEvent;
	public function __construct(){
		$this->objServiceDataGcrmCustomerAccountLoginEvent = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccountLoginEvent');
		 $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		  $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		try{
			$res =  $this->objServiceDataGcrmCustomerAccountLoginEvent->getCustomerLoginCount($arrInput['account_id'],$arrInput['date']['sDate'],$arrInput['date']['edate']);
			if ( $res['errorno']){
				 return $res;
			}
			$ret['dataList'] = $this->objServiceDataGcrmCustomerAccountLoginEvent->getCustomerLoginList($arrInput['account_id'],$arrInput['date']['sDate'],$arrInput['date']['edate'],$arrInput['arrFields'],$arrInput['page'],20);	
			if ( $res['errorno']){
				return $ret;
			}
		 $arrMerge = array();
        foreach ($ret['dataList']['data'] as $value){
			$value ['loging_time'] =  date('Y-m-d H:i:s',$value['loging_time']);
			$value['ip'] = long2ip($value['ip']);
			$value['city']	= $value['city'];
			$arrMerge[] = $value;  
        }   
		$ret1['data']['dataList']['data']=  $arrMerge;
		$ret1['data']['dataList']['count'] = $res;
		}catch(Exception $e){
			 $this->data['errorno'] = $e->getCode();
			 $this->data['errormsg'] = $e->getMessage();
		}		
	return $ret1;
	}
}
