<?php
class Dao_Xapian_Shangpu 
{
    const XAPIAN_ID = 1008;
    protected static $majorCategoryId = 26;
    //{{{ xapianFieldArr
    protected static $xapianFieldArr = array(
        /* str */'f' => 
        array(
            'top_info' => array(1, 0), 
            'city' => array(1, 0), 
            'district_id' => array(1, 0), 
            'district_name' => array(1, 0), 
            'street_id' => array(1, 0),
            'company_id' => array(1, 0), 
            'street_name' => array(1, 0), 
            'title' => array(1, 0), 
            'description' => array(0, 0), 
            'thumb_img' => array(0, 0), 
            'person' => array(0, 0), 
            'phone' => array(1, 0), 
            'major_category' => array(2, 0), 
            'agent' => array(1, 0), 
            'post_type' => array(1, 0), 
            'latlng' => array(1, 0), 
            'xiaoqu' => array(1, 0), 
            'xiaoqu_id' => array(1, 0), 
            'xiaoqu_address' => array(1, 0), 
            'pinyin' => array(0, 0), 
            'fang_xing' => array(1, 0), 
            'huxing_shi' => array(1, 0), 
            'huxing_ting' => array(0, 0), 
            'huxing_wei' => array(0, 0), 
            'peizhi' => array(0, 0), 
            'jichu' => array(0, 0), 
            'deal_type' => array(1, 0), 
            'address' => array(1, 0), 
            'house_type' => array(1, 0), 
            'price_type' => array(0, 0), 
            'shopping' => array(1, 0), 
            'trade' => array(1, 0), 
            'house_name' => array(1, 0), 
            'rent_type' => array(1, 0), 
            'loupan_name' => array(0, 0), 
            'strval1' => array(1, 0), 
            'strval2' => array(1, 0), 
        ), 
        /* int */'n' => 
        array(
            'puid' => array(0, 0),
            'post_id' => array(0, 0),
            'listing_status' => array(1, 0),
            'post_at' => array(1, 0),
            'user_id' => array(0, 0),
            'image_count' => array(1, 0),
            'refresh_at' => array(0, 0),
            'show_time' => array(0, 0),
            'price' => array(1, 0),
            'area' => array(1, 0),
            'house_type_for_addword' => array(1, 0),
            'rent_date' => array(1, 0),
            'store_rent_type' => array(1, 0),
            'store_stat' => array(1, 0),
            'price_month' => array(1, 0),
            'price_day' => array(1, 0),
            'intval1' => array(1, 0),
            'intval2' => array(1, 0),
        ),
    );//}}}
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
        'puid', 
        'post_id', 
        'account_id', 
        'title', 
        'listing_status', 
        'refresh_at', 
        'post_at', 
        'district_id', 
        'street_id',
        'company_id', 
        'district_name', 
        'street_name', 
        'area', 
        'price', 
        'agent', 
        'image_count', 
        'post_type', 
        'top_info', 
        'thumb_img', 
        'user_id', 
        'store_rent_type', 
        'store_stat', 
        'loupan_name', 
        'house_type', 
        'address', 
        'house_name', 
        'price_type', 
        'major_category',
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
     *
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
    /* {{{getFilterList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function getBuilder($queryConfigArr){
        $searchUtil = new Util_HouseXapian();
        return $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
    }//}}}
}
