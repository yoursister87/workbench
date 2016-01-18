<?php
/**
 * Created by PhpStorm.
 * @author          liuhaipeng1@ganji.com
 * @create          2015-07-14 14:16
 * @file            $HeadURL$
 * @version         $Rev$
 * @lastChangeBy    $LastChangedBy$
 * @lastmodified    $LastChangedDate$
 * @copyright       Copyright (c) 2015, www.ganji.com
 */

class Service_Data_Merchant_Store {
    /**
     * @var Dao_Merchant_Store
     */
    protected $objDaoMerchantStore;
    protected $arrFields = array('id', 'full_name', 'city_id', 'city_name', 'district_id', 'district_name', 'street_id', 'street_name', 'address', 'longitude', 'latitude', 'broker_number', 'premium_broker_number', 'is_cooperate', 'employee_number', 'company_id', 'company_name', 'digital_card', 'audit_id', 'status', 'creator', 'creator_name', 'created_at', 'modifier_id', 'modifier_name', 'modified_at', 'approve_id', 'approve_name', 'approve_at', 'remarks', 'agent_id', 'premium_broker_percentage');

    /**
     * 构造方法
     */
    public function __construct() {
        $this->data['data']     = array();
        $this->data['errorno']  = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDaoMerchantStore = Gj_LayerProxy::getProxy('Dao_Merchant_Store');
    }

    /**
     * 魔术方法
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name, $args) {
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this, $name), $args);
        }
    }

    /**
     * 根据gcrm.customer_account表中的字段MerchantId获取商户门店信息
     * @param $merchantId
     * @param array $arrFields
     * @return array
     */
    public function getMerchantStoreByMerchantId($merchantId, $arrFields = array()) {
        $arrFields = $this->fieldNameRevert($arrFields);
        $arrConds = array('id =' => $merchantId);
        try {
            $res = $this->objDaoMerchantStore->select($arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno']  = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoMerchantStore->getLastSQL());
            $this->data['errorno']  = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res[0];
        }
        return $this->data;
    }

    /**
     * @param $arrFields
     * @return mixed
     */
    private function fieldNameRevert($arrFields) {
        return $arrFields;
    }


}
