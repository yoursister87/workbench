<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */

class Dao_Housepremier_HouseCommentPrivilege extends Gj_Base_MysqlDao{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'house_comment_privilege';
    protected $table_fields = array('id', 'puid', 'district_id', 'street_id', 'customer_id', 'agent_Id', 'user_id');

    public function deleteByPuid($arrConds){
        $ret = $this->dbHandler->delete($this->tableName, $arrConds);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
    }
}