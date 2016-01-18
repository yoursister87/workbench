<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong1$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_Account_AccountInfo
{
    /* {{{__construct*/
    /**
     * @brief 构造函数
     *
     */
    public function __construct(){
        $this->customerAccountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $this->customerService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    /* }}} */
    /* {{{ __call*/
    /**
     * @brief 匿名函数
     *
     * @param $name 函数名
     * @param $args 参数
     *
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    /* }}} */
    /* {{{ public execute*/
    /**
     * @brief page service 入口
     *
     * @param $arrInput array(
     *      'user_id' => 会员中心id,
     *      'account_id' => 端口后台id,
     *  );
     *
     * @returns   array()
     */
    public function execute($arrInput){
        $customerAccountRes = array();
        $accountInfo = array();
        $customerAccountFields = array(/*{{{ customer_account fields*/
                "AccountId",
                "UserId",
                "AccountName",
                "CellPhone",
                "HasLicence",
                "Picture",
                "IM",
                "PersonalIntroduction",
                "DistrictId",
                "AreaId",
                "Status",
                "CreatedTime",
                "UserType",
                "CityId",
                "BussinessScope",
                "ServicePlanId",
                "RecentTag",
                "PremierTag",
                "CreditScore",
                "IsFree",
                "IsCpc",
                "OwnerType",
                "GroupId",
                "MerchantType",
                "CompanyIdOfZhenFangYuan"
        );/*}}}*/
        $customerFields = array(/*{{{ customer_fields*/
            "id",
            "full_name",
            "company_name",
            "company_id",
        );/*}}}*/
        if (isset($arrInput['account_id']) && is_numeric($arrInput['account_id'])) {
            $customerAccountRes = $this->customerAccountService->getAccountInfoById($arrInput['account_id'], $customerAccountFields);
        } else if (isset($arrInput['user_id']) && is_numeric($arrInput['user_id'])) {
            $customerAccountRes = $this->customerAccountService->getAccountInfoByUserId($arrInput['user_id'], $customerAccountFields);
        } 
        if (!empty($customerAccountRes) && $customerAccountRes['errorno'] == 0 && is_array($customerAccountRes['data'][0])) {
            $accountInfo = $customerAccountRes['data'][0];
            $accountInfo['CreatedTime'] = strtotime($accountInfo['CreatedTime']);
            $customerRes = $this->customerService->getCustomerInfoByCustomerId($accountInfo['GroupId'], $customerFields);
            if ($customerRes['errorno'] == 0 && is_array($customerRes['data'])) {
                $accountInfo['CustomerId'] = $customerRes['data']['id'];
                $accountInfo['CustomerName'] = $customerRes['data']['full_name'];
                $accountInfo['CompanyId'] = $customerRes['data']['company_id'];
                $accountInfo['CompanyName'] = $customerRes['data']['company_name'];
            }
        } else {
            $this->data['errorno'] = ErrorConst::E_DATA_NOT_EXIST_CODE;
            $this->data['errormsg'] = "account_not_exist\taccount_id_" . json_encode($arrInput);
        }
        $this->data['data'] = $accountInfo;
        return $this->data;
    }
    /* }}} */
}
