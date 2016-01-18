<?php
class Dao_Xapian_Shangputg 
{
    const XAPIAN_ID = 1002;
    protected static $majorCategoryId = 26;
    //{{{xapianFieldArr
    protected static $xapianFieldArr = array(
        /* str */'f' => array(
            'major_category' => array(1, 0),
            'account_id' => array(0, 0),
            'city' => array(1, 0),
            'district_id' => array(1, 0),
            'district_name' => array(1, 0),
            'street_id' => array(1, 0),
            'street_name' => array(1, 0),
            'title' => array(1, 0),
            'description' => array(0, 0),
            'thumb_img' => array(0, 0),
            'type' => array(1, 0),
            'person' => array(0, 0),
            'phone' => array(1, 0),
            'latlng' => array(1, 0),
            'ceng' => array(1, 0),
            'peizhi' => array(0, 0),
            'pay_type' => array(1, 0),
            'address' => array(1, 0),
            'house_type' => array(1, 0),
            'shopping' => array(1, 0),
            'property_charge' => array(0, 0),
            'electric_charge' => array(0, 0),
            'house_name' => array(1, 0),
            'ceng_total' => array(0, 0),
            'price_type' => array(0, 0),
            'lease_term_unit' => array(1, 0),
            'huxing_shi' => array(1, 0),
            'trade' => array(1, 0),
            'property_charge_included' => array(1, 0),
            'loupan_name' => array(0, 0),
            'company_id' => array(1, 0),
            'strval1' => array(1, 0),
            'strval2' => array(1, 0),
        ),
        /* int */'n' => array(
            'puid' => array(0, 0),
            'house_id' => array(0, 0),
            'listing_status' => array(1, 0),
            'premier_status' => array(1, 0),
            'refresh_at' => array(1, 0),
            'image_count' => array(1, 0),
            'bid_status' => array(1, 0),
            'post_at' => array(1, 0),
            'modified_at' => array(0, 0),
            'priority' => array(1, 0),
            'price' => array(1, 0),
            'area' => array(1, 0),
            'zhuangxiu' => array(1, 0),
            'house_type_for_addword' => array(0, 0),
            'building_type' => array(0, 0),
            'car_port_charge' => array(0, 0),
            'price_month' => array(1, 0),
            'price_day' => array(1, 0),
            'lease_term' => array(1, 0),
            'open_area' => array(1, 0),
            'assignment_charge' => array(1, 0),
            'store_rent_type' => array(1, 0),
            'store_stat' => array(1, 0),
            'intval1' => array(1, 0),
            'intval2' => array(1, 0),
        ),
    );//}}}
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
        'house_id', 
        'title', 
        'refresh_at', 
        'post_at', 
        'district_id', 
        'street_id', 
        'company_id',
        'image_count', 
        'area', 
        'price', 
        'district_name', 
        'street_name', 
        'puid', 
        'account_id', 
        'loupan_name', 
        'store_stat', 
        'store_rent_type', 
        'house_type', 
        'address', 
        'price_type', 
        'shopping', 
        'chaoxiang', 
        'ceng', 
        'ceng_total', 
        'thumb_img', 
        'huxing_shi', 
        'intval2', 
        'major_category',
    );//}}}
    protected static $defaultOrderFieldArr = array('intval2' => 'desc', 'refresh_at' => 'desc');

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
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil',self::$majorCategoryId); 
        print_r($builder->getQueryString());
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
}
