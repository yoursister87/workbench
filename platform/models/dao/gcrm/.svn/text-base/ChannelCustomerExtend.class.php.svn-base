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
class Dao_Gcrm_ChannelCustomerExtend  extends Gj_Base_MysqlDao{
	protected $dbName = 'gcrm';
	protected $dbNameAlias = 'crm';
	protected $tableName = 'channel_customer_extend';
	protected $table_fields = array("id","customer_id","product_line","source","status");
	public function selectInfoByCustomerId($arrConds){
		return $this->select($this->table_fields, $arrConds);
	}
}