<?php
class Dao_Xapian_Jingjia
{
    const XAPIAN_ID = 1001;
    //{{{xapianFieldArr
    protected static $xapianFieldArr = array(
        'f' => array(
            'city'=>array(1, 0),
            'major_category'=>array(1, 0),
            'district_id'=>array(1, 0),
            'street_id'=>array(1, 0),
            'source_district_id'=>array(1, 0),
            'source_street_id'=>array(1, 0),
            'source_xiaoqu_id'=>array(1, 0),
            'account_id'=>array(1, 0),
            'company_id'=>array(1, 0),
            'source_title'=>array(1, 1),
            'source_xiaoqu'=>array(1, 1),
            'source_xiaoqu_address'=>array(1, 0),
            'is_alive'=>array(1, 0),
            'ad_date'=>array(1, 0),
            'source_district_name'=>array(0, 0),
            'source_street_name'=>array(0, 0),
            'source_pinyin'=>array(0, 0),
            'source_thumb_img'=>array(0, 0),
            'source_house_type'=>array(1, 0),
            'source_fang_xing'=>array(1, 0),
            'source_tag_type'=>array(1, 0),
            'source_house_property'=>array(1, 0),
            'strval1'=>array(1, 0),
            'strval2'=>array(1, 0),
        ),
        'n' => array(
            'puid'=>array(0, 0),
            'source_id'=>array(0, 0),
            'priority'=>array(1, 0),
            'source_priority'=>array(1, 0),
            'source_price'=>array(1, 0),
            'source_area'=>array(1, 0),
            'source_niandai'=>array(1, 0),
            'source_huxing_shi'=>array(1, 0),
            'source_huxing_ting'=>array(0, 0),
            'source_huxing_wei'=>array(1, 0),
            'source_chaoxiang'=>array(1, 0),
            'source_ceng'=>array(1, 0),
            'source_ceng_total'=>array(1, 0),
            'source_image_count'=>array(1, 0),
            'intval1'=>array(1, 0),
            'intval2'=>array(1, 0),
        ),
    );//}}}
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
        'id',
        'source_id',
        'source_district_id',
        'source_district_name',
        'source_street_id',
        'source_street_name',
        'source_pinyin',
        'source_price',
        'source_niandai',
        'source_xiaoqu_id',
        'company_id',
        'source_huxing_shi',
        'source_huxing_ting',
        'source_chaoxiang',
        'source_ceng',
        'source_ceng_total',
        'source_thumb_img',
        'source_house_type',
        'source_image_count',
        'source_area',
        'source_title',
        'source_xiaoqu',
        'source_xiaoqu_address',
        'source_tag_type',
        'source_huxing_wei',
        'source_house_property',
        'puid',
        'thumb_img',
        'intval1',
        'intval2',
        'account_id',
        'is_alive',
        'source_tab_system',
    );//}}}
    protected static $defaultOrderFieldArr = array('priority' => 'desc');

    /* {{{preSearch*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function preSearch($queryFilterArr){
        // create builder
        $searchUtil = new Util_HouseXapian();
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryFilterArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
            
        // send query
        $searchHandle = PlatformSingleton::getInstance('Util_XapianSearchHandleUtil');
        return $searchHandle->query($builder->getQueryString());
    }//}}}
    /* {{{getSearchResult*/
    /**
     * @brief 
     *
     * @param $searchId
     *
     * @returns   
     */
    public function getSearchResult($searchId){
        $searchHandle = PlatformSingleton::getInstance('Util_XapianSearchHandleUtil');
        return $searchHandle->getResult($searchId);
    }//}}}
}
