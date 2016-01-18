<?php
class Dao_Housepremier_HouseSpecial extends Gj_Base_MysqlDao{

protected $dbName = 'house_premier';
protected $dbNameAlias = 'premier';
protected $tableName = 'house_special';
protected $table_fields = array(
		"special_id",
		"user_name",
		"user_phone",
		"company_name",
		"company_city",
		"company_street",
		"manager_name",
		"manager_phone",
		"house_city",
		"house_district",
		"house_street",
		"house_xiaoqu",
		"house_category",
		"house_subcategory",
		"house_bisness",
		"house_price",
		"order_date",
		"house_link",
		"create_time"
	);
	public function selectLimitData($arrFields,$arrConds){
		 $limit = 'limit 4';	
		 return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$limit);
	}

}
