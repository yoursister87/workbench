<?php

/**
 * Dao_Housepremier_HouseSourceSellPremier dao
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-10-16
 * Time: 下午1:56
 * @codeCoverageIgnore
 */
class Dao_Housepremier_HouseSourceSellPremier extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_source_sell_premier';
    protected $table_fields = array('count(1) as num','house_id','puid','account_id','city','district_id','district_name','street_id','street_name','title','ip','thumb_img','image_count','type','premier_status','bid_status','listing_status','is_similar','post_at','refresh_at','modified_at','rand_refresh_at','priority','price','minprice_guide','maxprice_guide','price_bought','downpayments_require','downpayments_calculate','person','phone','xiaoqu','xiaoqu_id','xiaoqu_address','pinyin','fang_xing','house_property','fiveyears','only_house','land_tenure','bid_structure','elevator','latlng','area','area_inside','ceng','ceng_total','chaoxiang','niandai','zhuangxiu','huxing_shi','huxing_ting','huxing_wei','subway','bus_station','tag_type','tag_create_at','tab_system','tab_personality','loan_require','monthly_payments','ad_types','ad_status','user_id','cookie_id');
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
    