<?php
class Dao_Xapian_Loupan
{
    const XAPIAN_ID = 212;
    protected static $majorCategoryId = 1347;
    protected static $defaultOrderFieldArr = array('intval2' => 'desc', 'listing_status' => 'desc', 'post_at' => 'desc');
    protected static $xapianFieldArr = array(
        'f' => array(
            'major_category' => array(2, 0),
            'top_info' => array(1, 1), 
            'city' => array(1, 0), 
            'district_id' => array(2, 0), 
            'district_name' => array(1, 1), 
            'street_id' => array(1, 0), 
            'street_name' => array(1, 1),
            'company_id' => array(1, 0), 
            'title' => array(1, 1), 
            'description' => array(0, 0),
            'thumb_img' => array(0, 0), 
            'person' => array(0, 0), 
            'phone' => array(1, 1), 
            'agent' => array(1, 0),
            'post_type' => array(1, 0),
            'xiaoqu' => array(1, 1),
            'xiaoqu_id' => array(1, 0),
            'xiaoqu_address' => array(1, 1),
            'pinyin' => array(0, 0), 
            'fangxing' => array(1, 0), 
            'latlng' => array(1, 1), 
            'ceng' => array(1, 0),
            'ceng' => array(0, 0),
            'chaoxiang' => array(1, 0),
            'huxing_shi' => array(1, 0), 
            'huxing_ting' => array(0, 0), 
            'huxing_wei' => array(0, 0), 
            'strval1' => array(1, 0),
            'strval2' => array(1, 0),
            'platform' => array(1, 2),
        ),
        'n' => array(
            'puid' => array(0, 0),
            'id' => array(0, 0),
            'listing_status' => array(1, 0),
            'user_id' => array(0, 0),
            'image_count' => array(1, 0),
            'post_at' => array(1, 0),
            'refresh_at' => array(0, 0),
            'show_time' => array(1, 0),
            'price' => array(1, 0),
            'area' => array(1, 0),
            'niandai' => array(1, 0),
            'zhuangxiu' => array(1, 0),
            'intval1' => array(1, 0),
            'intval2' => array(1, 0),
        ),
    );
    protected static $defaultQueryFieldArr = array(
        'id',
        'title',
        'refresh_at',
        'post_at',
        'district_id',
        'street_id',
        'company_id',
        'district_name',
        'street_name',
        'listing_status',
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
        'chaoxiang',
        'zhuangxiu',
        'top_info',
        'puid',
        'user_id',
        'account_id',
        'intval2',
        'show_time',
    );
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
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil', self::$majorCategoryId);
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
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil', self::$majorCategoryId);
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
?>
