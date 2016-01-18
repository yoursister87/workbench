<?php

/**
 * HouseSourceSell model
 * User: lihongyun1
 * Date: 14-8-14
 * Time: 下午1:56
 */
class Dao_Fang_HouseSourceSell extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'fang';
    protected $dbHandler;
    protected $tableName = 'house_source_sell';
    protected $table_fields = array('id','puid','user_id','username','password','city','district_id','district_name','street_id','street_name','title','description','ip','thumb_img','image_count','post_at','refresh_at','show_time','price','person','phone','major_category','agent','listing_status','display_status','editor_audit_status','show_before_audit','show_before_audit_reason','post_type','cookie_id','xiaoqu_id','xiaoqu','xiaoqu_address','pinyin','fang_xing','latlng','area','ceng','ceng_total','chaoxiang','niandai','zhuangxiu','huxing_shi','huxing_ting','huxing_wei','subway','college','bus_station','user_code','source_type','source_desc','top_info','ad_types','ad_status','elevator','bid_structure','house_property','land_tenure');
    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = $dbName;
        parent::__construct();
    }

}
    