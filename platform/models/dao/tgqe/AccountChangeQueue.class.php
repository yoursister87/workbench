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
class Dao_Tgqe_AccountChangeQueue extends Gj_Base_MysqlDao{
	protected $dbName = 'tgqe';
	protected $dbNameAlias = 'tgqe';
	protected $tableName = 'account_change';
	protected $table_fields = array("account_id","user_id","action","value","create_at","author");
}
