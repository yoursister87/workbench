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
class Dao_Gcrm_Customer  extends Gj_Base_MysqlDao{
	protected $dbName = 'merchant';
	protected $dbNameAlias = 'crm';
	protected $tableName = 'merchant_store_group';
    protected $table_fields = array("id","full_name","city_id","city_name","district_id","district_name","street_id","street_name","broker_number","company_id","company_name","store_id","store_name","status","crea    tor","creator_name","created_at","remarks","agent_id", "id as CustomerId", "full_name as FullName", "company_id as CompanyId", "company_name as CompanyName");
	public function selectCustomerByPage($arrFields, $arrConds, $page, $pageSize, $orderArr){
		return $this->selectByPage($arrFields, $arrConds, $page, $pageSize, $orderArr);
	}
	public function selectCustomerByCount($arrConds,$appends = null){
		return	$this->selectByCount($arrConds,$appends);
	}
}
