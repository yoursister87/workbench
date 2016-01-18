<?php
/*
 * File Name:HouseManageAccountGcrmModel.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Dao_Gcrm_HouseManagerAccount extends Gj_Base_MysqlDao
{

    protected $dbName = 'gcrm';
    protected $dbNameAlias = 'crm';
    protected $tableName = 'house_manager_account';
    protected $table_fields = array("id","pid","company_id","customer_id","level","create_time","del_time","status","account","password","passwd","title","name","phone");
    public function deleteById($arrConds){
    	$ret = $this->dbHandler->delete($this->tableName, $arrConds);
    	if($ret === false){
    		throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
    	}
    	return $ret;
    }
}
