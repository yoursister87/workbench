<?php
class Dao_Xapian_Sell 
{
    const XAPIAN_ID = 205;
    protected static $majorCategoryId = 21;
    //{{{ xapianFieldArr
    protected static $xapianFieldArr = array(
        /* str */'f' => 
        array(
            'top_info' => array(1, 1), 
            'username' => array(0, 0),
            'city' => array(1, 0), 
            'district_id' => array(2, 0), 
            'district_name' => array(1, 1), 
            'street_id' => array(1, 0), 
            'street_name' => array(1, 1), 
            'title' => array(1, 1), 
            'ip' => array(0, 0),
            'thumb_img' => array(0, 0), 
            'person' => array(0, 0), 
            'phone' => array(1, 1), 
            'major_category' => array(2, 0), 
            'agent' => array(1, 0), 
            'post_type' => array(1, 0), 
            'cookie_id' => array(0, 0),
            'xiaoqu' => array(1, 1), 
            'xiaoqu_id' => array(2, 0), 
            'xiaoqu_address' => array(1, 1), 
            'pinyin' => array(0, 0), 
            'fang_xing' => array(1, 0), 
            'latlng' => array(1, 1), 
            'ceng_total' => array(0, 0),
            'chaoxiang' => array(1, 0),
            'huxing_shi' => array(1, 0), 
            'huxing_ting' => array(1, 0), 
            'huxing_wei' => array(1, 0), 
            'user_code' => array(1, 0),
            'account_id' => array(1, 0),
            'tag_type' => array(1, 2),
            'tag_create_at' => array(0, 0),
            'strval1' => array(0, 0), 
            'strval2' => array(0, 0), 
            'platform' => array(1, 2),
            'company_id' => array(1, 0),
            'bus_line' => array(1, 1),
            'subway_line' => array(1, 1),
            'collage_line' => array(1, 1),
            'land_tenure' => array(0, 0),
            'house_property' => array(1, 0),
            'tab_personality' => array(0, 0),
        ), 
        /* int */'n' => 
        array(
            'puid' => array(0, 0),
            'id' => array(0, 0),
            'listing_status' => array(1, 0),
            'post_at' => array(1, 0),
            'is_premier' => array(1, 0),
            'user_id' => array(0, 0),
            'image_count' => array(1, 0),
            'refresh_at' => array(0, 0),
            'show_time' => array(1, 0),
            'price' => array(1, 0),
            'display_status' => array(0, 0),
            'area' => array(1, 0),
            'ceng' => array(1, 0),
            'niandai' => array(1, 0),
            'zhuangxiu' => array(1, 0),
            'premier_status' => array(1, 0),
            'intval1' => array(0, 0),
            'intval2' => array(0, 0),
            'downpayment' => array(1, 0),
            'tab_system' => array(0, 0),
            'loan_require' => array(0, 0),
            'bid_structure' => array(0, 0),
            'elevator' => array(0, 0),
            'area_inside' => array(0, 0),
        ),
    );//}}}
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
        'id',
        'title',
        'refresh_at',
        'post_at',
        'district_id',
        'street_id',
        'street_name',
        'district_name',
        'post_type',
        'xiaoqu_address',
        'image_count',
        'latlng',
        'huxing_shi',
        'area',
        'price',
        'agent',
        'xiaoqu',
        'pinyin',
        'thumb_img',
        'huxing_ting',
        'huxing_wei',
        'ceng',
        'ceng_total',
        'niandai',
        'chaoxiang',
        'zhuangxiu',
        'top_info',
        'puid',
        'tag_type',
        'user_id',
        'account_id',
        'is_premier',
        'company_id',
        'show_time',
        'downpayment',
        'loan_require',
        'land_tenure',
        'house_property',
        'tab_system',
        'premier_status',
        'listing_status',
    );//}}}
    protected static $defaultOrderFieldArr = array('listing_status' => 'desc', 'post_at' => 'desc');

    /* {{{preSearch*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function preSearch($queryConfigArr){
        // create builder
        $searchUtil = new Util_HouseXapian();
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
            
        // send query
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil',self::$majorCategoryId);
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
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil',self::$majorCategoryId);
        return $searchHandle->getResult($searchId);
    }//}}}
    /* {{{getFilterList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @codeCoverageIgnore
     * @returns   
     */
    public function getFilterList($queryConfigArr){
        $searchUtil = new Util_HouseXapian();
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
        $builder->setEqualFilter('self_major_category', $queryConfigArr['queryFilter']['major_category_script_index']);
        $filterList = $builder->getFilterList();
        $filterList['textFilterData'] = $builder->getTextFilter();
        return $filterList;
    }//}}}
    /* {{{getBuilder*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @codeCoverageIgnore
     *
     * @returns   
     */
    public function getBuilder($queryConfigArr){
        $searchUtil = new Util_HouseXapian();
        return $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
    }//}}}
}
