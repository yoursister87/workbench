<?php
/*
 * File Name:HouseUserBizInfo.class.php
 * Author:lukang
 * mail:lukang@ganji.com
*/ 
class Dao_Housepremier_AccountBusinessInfo extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'account_bussiness_info';

    protected $table_fields = array("Id","AccountId","UserId","CustomerId","CompanyId","CountType","BussinessScope","InDuration","MaxPremierCount","MaxFreeRefreshCount","MaxChargeRefreshCount","InDurationBeginTime","InDurationEndTime","NextDurationBeginTime","MinBeginTime","MaxEndTime","ModifiedTime","CityCode","LoginName","Email","GiftRefresh");
    /**
     * @brief
     *
     * @param $arrayFields
     *
     * @returns
     * @codeCoverageIgnore
     * 没有逻辑分支
     */
    public function insertBussinessInfoOnDuplicateKeyUpdate($arrayFields){
        $mustContainFields = array("AccountId","CountType","BussinessScope");
        $arrRows = $this->objMysqlUtil->checkWriteRow($arrayFields, $this->table_fields);
        if (!$arrRows || !$this->checkMustFields($arrRows, $mustContainFields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        $ret = $this->dbHandler->insert($this->tableName, $arrRows, null, $arrRows);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
    }
    public function deleteAccountInfo($cond){
        $ret = false;
        if (!empty($cond)){
            $ret = $this->dbHandler->delete($this->tableName, $cond, null, null);
        }
        return $ret;
    }
    /**
     * @brief
     *
     * @param $arrayFields，$mustFields
     *
     * @returns
     */
    protected function checkMustFields($arrayFields, $mustFields){
        if (!is_array($arrayFields) || !is_array($mustFields)) {
            return false;
        }
        $intersectRet = array_intersect($mustFields, array_keys($arrayFields));
        return $intersectRet == $mustFields;
    }

    /**
     * @brief
     *
     * @codeCoverageIgnore
     * 
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
}
