<?php
class Dao_Gcrm_CustomerAccount  extends Gj_Base_MysqlDao
{
    const CACHE_KEY_PREFIX = 'platform-account-info_';
    const CACHE_TIME = 86400;
    protected $dbName = 'gcrm';
    protected $dbNameAlias = 'crm';
    protected $tableName = 'customer_account';
    protected $dbHandler;
    protected $table_fields = array("UserType","AccountId","BussinessScope","ServicePlanId","CustomerId","CityId","DistrictId","AreaId","Email","AccountName","AliasName","ICNo","ICImage","BusinessCardImage","Gender","OfficePhone","CellPhone","IM","Fax","DepositAmount","AwardAmount","Balance","IsCPC","LastestRecharge","PremierExpire","RestExpire","RegistrationDate","PersonalIntroduction","HasLicence","Picture","Status","PremierUnfreezeTime","LoginTime","ModifierId","CreatedTime","CreatorName","CreatorId","ModifierName","ModifiedTime","ShopName","ShopNotice","ShopService","AuditAt","AuditId","AuditName","AuditResult","ModifiedAt","SaleId","SaleName","SaleGroup","SaleGroupArea","ServiceId","ServiceName","ServiceGroup","ServiceGroupArea","Discount","CreditScore","UserId","RecentTag","PremierTag","LastVisitUserId","LastVisitDate","PremierAwardFreezedTime","PremierAwardFreezedBalance","AwardFreezedTime","AwardFreezedBalance","IsFree","OwnerType","IsInvalid","PremierCurrBeginAt","PremierCurrEndAt","RestCurrBeginAt","RestCurrEndAt","InSeaTime","InSeaType","GroupId", "GroupId as CustomerId","IsCpc","MerchantType","CompanyIdOfZhenFangYuan");

    /* {{{getAccountInfoByAccountId*/
    /**
     * @brief 
     *
     * @param $accountIdArr
     * @param $fieldArr
     *
     * @returns   
     */
    public function getAccountInfoByAccountId($accountIdArr, $fieldArr=array('*')){
        if (!is_array($accountIdArr) || empty($accountIdArr)
            || !is_array($fieldArr) || empty($fieldArr)
        ) {
            throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
        }
        // from cache
        try {
            $dataFromCache = $this->getAccountInfoByAccountIdFromCache($accountIdArr);
            $accountIdFromCacheArr = array_keys($dataFromCache);
            $accountIdFromDbArr = array_diff($accountIdArr, $accountIdFromCacheArr);
            // get from db
            $dataFromDb = array();
            if (!empty($accountIdFromDbArr)) {
                $accountIdStr = implode(',', array_unique($accountIdFromDbArr));
                $data = $this->select($fieldArr, array("accountid in ({$accountIdStr})"));
                if (is_array($data) && !empty($data)) {
                    foreach ($data as $value) {
                        $dataFromDb[$value['AccountId']] = $value;
                    }
                }
                // save cache
                $this->saveAccountInfoToCache($dataFromDb);
            }
            return $dataFromDb + $dataFromCache;
        } catch (Exception $e) {
            return false;
        }
    }//}}}
    /* {{{getAccountInfoByAccountIdFromCache*/
    /**
     * @brief 
     *
     * @param $accountIdArr
     *
     * @returns   
     */
    protected function getAccountInfoByAccountIdFromCache($accountIdArr){
        if (!is_array($accountIdArr) || empty($accountIdArr)) {
            throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
        }
        $qkeys = array();
        foreach ($accountIdArr as $id) {
            $qkeys[] = self::CACHE_KEY_PREFIX . $id;
        }
        $cacheClient = Gj_Cache_CacheClient::getInstance('Memcache');
        $data = $cacheClient->readMulti($qkeys);
        if (false != $data && !empty($data)) {
            $cacheData = array();
            foreach ($data as $key => $value) {
                if (empty($value)) continue;
                $keyArr = explode('_', $key);
                $cacheData[$keyArr[1]] = $value;
            }
            return $cacheData;
        } else {
            return array();
        }
    }//}}}
    /* {{{saveAccountInfoToCache*/
    /**
     * @brief 
     *
     * @returns   
     */
    protected function saveAccountInfoToCache($data){
        if (!is_array($data) || empty($data)) {
            return false;
        }
        $cacheData = array();
        foreach ($data as $key => $value) {
            if (empty($value)) continue;
            $cacheKey = self::CACHE_KEY_PREFIX . $key;
            $cacheData[$cacheKey] = $value;
        }
        $cacheClient = Gj_Cache_CacheClient::getInstance('Memcache');
        return $cacheClient->writeMulti($cacheData, self::CACHE_TIME);
    }//}}}
	public function selectAllInfo($arrFields, $arrConds){
		$ret = $this->dbHandler->select($this->tableName,$arrFields, $arrConds,null,null);
		return  $ret;
	} 
}
