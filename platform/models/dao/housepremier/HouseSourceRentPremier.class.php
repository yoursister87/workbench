<?php

/**
 * Dao_Housepremier_HouseSourceRentPremier dao
 * User: lihongyun1
 * Date: 14-10-16
 * Time: 下午1:56
 */
class Dao_Housepremier_HouseSourceRentPremier extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_source_rent_premier';
    protected $table_fields = array('house_id','puid','account_id','city','district_id','district_name','street_id','street_name','title','ip','thumb_img','image_count','type','premier_status','bid_status','listing_status','is_similar','post_at','refresh_at','modified_at','rand_refresh_at','priority','price','person','phone','xiaoqu','xiaoqu_id','xiaoqu_address','pinyin','fang_xing','latlng','area','ceng','ceng_total','chaoxiang','zhuangxiu','pay_type','huxing_shi','huxing_ting','huxing_wei','peizhi','subway','college','bus_station','tag_type','tag_create_at','tab_system','tab_personality','ad_types','ad_status','user_id','cookie_id');
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
    