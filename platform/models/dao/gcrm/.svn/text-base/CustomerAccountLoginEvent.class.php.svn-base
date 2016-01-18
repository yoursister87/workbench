<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */
class Dao_Gcrm_CustomerAccountLoginEvent extends Gj_Base_MysqlDao{
	protected $dbName = 'gcrm';
	protected $dbNameAlias = 'crm';
	protected $tableName = 'customer_account_login_event';
	protected $table_fields = array("EventId","AccountId","Email","IsSuccess","LoginTime","Ip","UserAgent","Message","ClientTimestamp","uuid");
	public function selectGroupbyAccountId($arrFields,$arrConds){
		$strGroupby = ' group by AccountId order by null'; 
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	} 
}
