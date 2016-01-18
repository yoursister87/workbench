<?php

/**
 * Dao_Housepremier_HouseSourceShortrentPremier dao
 * User: lihongyun1
 * Date: 14-10-16
 * Time: 下午1:56
 */
class Dao_Housepremier_HouseSourceShortrentPremier extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_source_shortrent_premier';
    protected $table_fields = array('house_id','puid','account_id','city','district_id','district_name','street_id','street_name','title','ip','thumb_img','image_count','type','premier_status','bid_status','listing_status','is_similar','rand_refresh_at','priority','post_at','refresh_at','modified_at','price','person','phone','address','latlng','area','rent_type','rent_date','fang_xing','tag_type','tag_create_at','ad_types','ad_status','user_id','cookie_id');
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
    