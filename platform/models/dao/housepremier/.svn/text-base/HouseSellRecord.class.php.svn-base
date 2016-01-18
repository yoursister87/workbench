<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 * @codeCoverageIgnore
 */

class Dao_Housepremier_HouseSellRecord extends Gj_Base_MysqlDao{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'house_sell_record';
    protected $table_fields = array("id","xiaoqu_name","price","price_unit","brokeage","brokeage_unit","deliver_at","post_at","account_id","puid","type","sellerid","sellername","sellerphone");

    public function selectSellInfoBypuid($arrFields, $arrConds){
        $ret = $this->dbHandler->select($this->tableName, $arrFields, $arrConds);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
    }

    public function insertSellInfo($arrFields){
        $mustContainFields = array("puid","type","account_id","sellername","sellerphone","sellerid", "price", "price_unit");
        $arrRows = $this->objMysqlUtil->checkWriteRow($arrFields, $this->table_fields);
        if (!$arrRows || !$this->checkMustFields($arrRows, $mustContainFields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        $arrRows['deliver_at'] = time();
        $ret = $this->dbHandler->insert($this->tableName, $arrRows, null, $arrRows);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
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
}