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
 * @codeCoverageIgnore
 */
class Dao_Housepremier_UserPostList extends Gj_Base_MysqlDao{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'user_post_list';
    protected $table_fields = array("id","puid","house_id","type","daynum","account_id");

    //添加房源上架记录
    public function insertHouseRecord($arrFields){
        $mustContainFields = array('puid','house_id', 'type','account_id');
        $arrRows = $this->objMysqlUtil->checkWriteRow($arrFields, $this->table_fields);
        if (!$arrRows || !$this->checkMustFields($arrRows, $mustContainFields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        $arrRows['daynum'] = time();
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