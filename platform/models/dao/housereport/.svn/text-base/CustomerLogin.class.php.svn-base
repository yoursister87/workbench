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
class Dao_Housereport_CustomerLogin extends Gj_Base_MysqlDao{
	protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    protected $tableName = 'customer_login';
    protected $table_fields = array("id","account_id","account","company_id","loging_time","ip","is_success","city");
	public function selectLastLog($arrFields, $arrConds){
		$orderArr = 'ORDER BY loging_time DESC Limit 1,1 ';
		$ret = $this->dbHandler->select($this->tableName,$arrFields, $arrConds,null,$orderArr);
		return  $ret;
	}
}

