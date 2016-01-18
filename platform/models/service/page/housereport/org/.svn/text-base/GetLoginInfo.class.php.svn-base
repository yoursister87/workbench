<?php                                                                    
/**                                                                                                     
 * @package                                                                   │~                                                                            
 * @subpackage                                                                │~                                                                            
 * @author               $Author:   zhangshijin$                              │~                                                                            
 * @file                 $HeadURL$                                            │~                                                                            
 * @version              $Rev$                                                │~                                                                            
 * @lastChangeBy         $LastChangedBy$                                      │~                                                                            
 * @lastmodified         $LastChangedDate$                                    │~                                                                            
 * @copyright            Copyright (c) 2010, www.ganji.com 
*/
class Service_Page_HouseReport_Org_GetLoginInfo{
	
	protected $data;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount 
	*/
	protected $objServiceDataGcrmHouseManagerAccount;
	/**
	 * @var Service_Data_Gcrm_Company
	*/
	protected $objServiceDataGcrmCompany; 
	/**
	 * @var Service_Data_CompanyShop_BizCompanyInfo
	*/
	protected $objServiceDataCompanyShopBizCompanyInfo;
	public function __construct(){
		$this->objServiceDataGcrmHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
		$this->objServiceDataGcrmCompany = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Company');
		$this->objServiceDataCompanyShopBizCompanyInfo = Gj_LayerProxy::getProxy('Service_Data_CompanyShop_BizCompanyInfo');
		$this->data['data'] = array();
		$this->data['errorno'] =  ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	public function execute($arrInput){
		try{
			$whereConds['account'] = $arrInput['email'];
			$res = $this->objServiceDataGcrmHouseManagerAccount->getOrgInfoByIdOrAccount($whereConds);
			if($res['errorno']){
				return $res;
			}
			$res1 = $this->objServiceDataGcrmCompany->getCompanyInfoById($res['data']['company_id']);
			if($res1['errorno']){
				return $res1;
			}
			$res2 = $this->objServiceDataCompanyShopBizCompanyInfo->getBizCompanyByCompanyId($res1['data']['CompanyId'],$res1['data']['CityId']);
			if($res2['errorno']){
				return $res2['error'];
			}
			$this->data['data']['user'] = $res['data'];
			$this->data['data']['company'] = $res1['data'];
			$this->data['data']['company']['companybriefintroduction'] =$res2['data']['companybriefintroduction'];
			$imgUrl =  UploadConfig::getImageServer() . '/' ;
			if(!$res2['data']['companylogopicurl']){
				$this->data['data']['company']['companylogopicurl'] = ErrorConst::Ganji_Default_Image; 
			}else{
				$this->data['data']['company']['companylogopicurl'] = $imgUrl.$res2['data']['companylogopicurl'];	
			}
		}catch (Exception $e){
		   $this->data['errorno'] = $e->getCode();
		   $this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}
