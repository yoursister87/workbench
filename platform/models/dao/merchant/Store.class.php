<?php
/**
 * Created by PhpStorm.
 * @author          liuhaipeng1@ganji.com
 * @create          2015-07-14 13:58
 * @file            $HeadURL$
 * @version         $Rev$
 * @lastChangeBy    $LastChangedBy$
 * @lastmodified    $LastChangedDate$
 * @copyright       Copyright (c) 2015, www.ganji.com
 */

class Dao_Merchant_Store extends Gj_Base_MysqlDao {
    protected $dbName = 'merchant';
    protected $dbNameAlias = 'crm';
    protected $tableName = 'merchant_store';
    protected $dbHandler;
    protected $table_fields = array('id', 'full_name', 'city_id', 'city_name', 'district_id', 'district_name', 'street_id', 'street_name', 'address', 'longitude', 'latitude', 'broker_number', 'premium_broker_number', 'is_cooperate', 'employee_number', 'company_id', 'company_name', 'digital_card', 'audit_id', 'status', 'creator', 'creator_name', 'created_at', 'modifier_id', 'modifier_name', 'modified_at', 'approve_id', 'approve_name', 'approve_at', 'remarks', 'agent_id', 'premium_broker_percentage');


}
