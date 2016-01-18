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
class Service_Page_HouseReport_Org_SetAgentInfo{
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
		if (!is_numeric($arrInput['AccountId']) || !is_numeric($arrInput['UserId'])) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		try{
			//获取数据库数据
			$arrFields = array('AccountId','UserId','AccountName','ICNo','ICImage','BusinessCardImage','Picture');
			$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
			$res = $this->objServiceCustomerAccount->getAccountInfoById($arrInput['AccountId'],$arrFields);
			if($res['errorno']){
				return $res;
			}
			$arrRows = array();
            $arrRows['ICNo'] = '';
            $arrRows['Picture'] = '';
            $arrRows['ICImage'] = '';
            $arrRows['BusinessCardImage'] = '';

			if(!empty($arrInput['AccountName']) && $res['data'][0]['AccountName']!=$arrInput['AccountName']){
				$arrRows['AccountName'] = $arrInput['AccountName'];
			}
            if(!empty($arrInput['ICNo']) && $arrInput['ICNo']!=$res['data'][0]['ICNo']){
                $arrRows['ICNo'] = $arrInput['ICNo'];
            }
            if(!empty($arrInput['ICImage']) && $arrInput['ICImage']!=$res['data'][0]['ICImage']){
                $arrRows['ICImage'] = $arrInput['ICImage'];
            }
            if(!empty($arrInput['BusinessCardImage']) && $arrInput['BusinessCardImage']!=$res['data'][0]['BusinessCardImage']){
                $arrRows['BusinessCardImage'] = $arrInput['BusinessCardImage'];
            }
            if(!empty($arrInput['Picture']) && $arrInput['Picture']!=$res['data'][0]['Picture']){
                $arrRows['Picture'] = $arrInput['Picture'];
            }
            if(!empty($arrInput['CompanyProof']) && $arrInput['CompanyProof']!=$res['data'][0]['CompanyProof']){
                $arrRows['CompanyProof'] = $arrInput['CompanyProof'];
            }

			$arrRows['AccountId'] = $arrInput['AccountId'];
			$arrRows['CreatorId'] = $arrInput['CreatorId'];
			$arrRows['CreatorName'] = $arrInput['CreatorName'];
			$arrRows['Key'] = $arrInput['AccountId'] . '_' .  time();
			$updataProfile = $this->objServiceCustomerAccount->UpdateProfile($arrRows);
			if($updataProfile['errorno']){
				return $updataProfile;
			}
			$this->data = $updataProfile;
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}