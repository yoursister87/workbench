<?php                                                                    
/**                                                                           │~                                                                            
 * @package                                                                   │~                                                                            
 * @subpackage                                                                │~                                                                            
 * @author               $Author:   zhangshijin$                              │~                                                                            
 * @file                 $HeadURL$                                            │~                                                                            
 * @version              $Rev$                                                │~                                                                            
 * @lastChangeBy         $LastChangedBy$                                      │~                                                                            
 * @lastmodified         $LastChangedDate$                                    │~                                                                            
 * @copyright            Copyright (c) 2010, www.ganji.com                    │~                                                                            
*/ 
class Service_Page_HouseReport_Org_ModifyPhone{
	/**
	 *@var Service_Data_Gcrm_HouseManagerAccount
	 **/
	protected $objServiceDataGcrmHouseManagerAccount;
	public function __construct(){
		$this->objServiceDataGcrmHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;  
	}
	public function execute($arrInput){
		try{
			$arrCord = array ('phone' =>$arrInput['phone']);
			$res = $this->objServiceDataGcrmHouseManagerAccount->updateOrgById($arrInput['id'],$arrCord);
			if($res['errorno']){
				return $res;
			  }
		}catch(Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}
