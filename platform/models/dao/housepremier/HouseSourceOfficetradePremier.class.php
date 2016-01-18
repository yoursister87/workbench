<?php

/**
 * Dao_Housepremier_HouseSourceOfficetradePremier dao
 * User: lihongyun1
 * Date: 14-10-16
 * Time: 下午1:56
 */
class Dao_Housepremier_HouseSourceOfficetradePremier extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_source_officetrade_premier';
    protected $table_fields = array('house_id','puid','account_id','city','district_id','street_id','district_name','street_name','title','ip','thumb_img','image_count','type','premier_status','bid_status','listing_status','is_similar','post_at','refresh_at','modified_at','rand_refresh_at','priority','price','person','phone','address','latlng','area','house_type','shopping','house_name','peizhi','building_type','ceng','ceng_total','zhuangxiu','property_charge','electric_charge','car_port_charge','loupan_id','ad_types','ad_status','user_id','cookie_id');
    public function getTableInfo($name){
        switch ($name){
            case "dbName":
                return $this->dbName;
                break;
            case "tableName":
                return $this->tableName;
            case "tableFields":
                return $this->table_fields;
                break;
        }
    }
}
    