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
class Service_Page_HouseReport_Org_GetAgentInfo{
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	
	}
	public function execute($arrInput){
		if (!is_numeric($arrInput['account_id'])) {
            $this->data['data'] = array();
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		}else{
			try{

                if(!$this->judgePrivilege($arrInput['account_id'])){
                    $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                    $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                    return $this->data;
                }
				$arrFields = array('AccountId','UserId','AccountName','ICNo','ICImage','BusinessCardImage','Picture');
				$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
				$res = $this->objServiceCustomerAccount->getAccountInfoById($arrInput['account_id'],$arrFields);

				if($res['errorno']){
					return $res;
				}

				/**
				 * @var Util_HouseReport_FormatImage
				 */
				$utilFormatImage = new Util_HouseReport_FormatImage();
				$imageConfig = array('width' => 76,'height' => 106,'quality' => 6, 'version' => 0,'cut' => false);
				$res['data'][0]['Picture'] = $utilFormatImage->formatImageUrl($res['data'][0]['Picture'], $imageConfig);
                $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
                $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
				/* $utilFormatImage = new Util_HouseReport_FormatImage();
				$res['data'][0]['Picture'] = $utilFormatImage->formatImageUrl($res['data'][0]['Picture']);
				$res['data'][0]['ICImage'] = $utilFormatImage->formatImageUrl($res['data'][0]['ICImage']);
				$res['data'][0]['BusinessCardImage'] = $utilFormatImage->formatImageUrl($res['data'][0]['BusinessCardImage']); */
				$this->data['data'] = $res['data'][0];

			}catch (Exception $e){
				$this->data['errorno'] = $e->getCode();
				$this->data['errormsg'] = $e->getMessage();
			}
		}
		return $this->data;
	}


    public function judgePrivilege($accountId){
        $customerObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $managerObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $cusInfo = $customerObj->getAccountInfoById($accountId,array('CustomerId'));
        $customerId = $cusInfo['data'][0]['CustomerId'] ;
        $customerArr = $managerObj->getChildTreeByOrgId($_SESSION['house_report']['user']['id'],4,array(),1,null);
        $allCustomerIds = array();
        if(!empty($customerArr['data']['list'])){
            foreach($customerArr['data']['list'] as $row){
                array_push($allCustomerIds,$row['customer_id']);
            }
        }
        if(!in_array($customerId,$allCustomerIds)){
                return false;
        }
        return true;
    }
}