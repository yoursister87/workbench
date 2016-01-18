<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Xapian_Zufangtg 
{
    const XAPIAN_ID = 202;
    protected static $majorCategoryId = 20;
    //{{{xapianFieldArr
    protected static $xapianFieldArr = array(
        /* str */'f' => array(
            'major_category' => array(2, 0),
            'account_id' => array(1, 0),
            'city' => array(1, 0),
            'district_id' => array(2, 0),
            'district_name' => array(1, 0),
            'street_id' => array(1, 0),
            'street_name' => array(1, 0),
            'title' => array(1, 1),
            'description' => array(0, 1),
            'thumb_img' => array(0, 0),
            'person' => array(0, 0),
            'phone' => array(1, 1),
            'xiaoqu' => array(1, 1),
            'xiaoqu_id' => array(2, 0),
            'xiaoqu_address' => array(1, 1),
            'pinyin' => array(0, 0),
            'fang_xing' => array(1, 0),
            'latlng' => array(1,1),
            'ceng_total' => array(0, 0),
            'chaoxiang' => array(1, 0),
            'pay_type' => array(0, 0),
            'peizhi' => array(0, 0),
            'share_mode' => array(1, 0),
            'house_type' => array(1, 0),
            'rent_sex_request' => array(1, 0),
            'tag_type' => array(1, 0),
            'tag_create_at' => array(0, 0),
            'huxing_shi' => array(1, 0),
            'huxing_ting' => array(1, 0),
            'huxing_wei' => array(1, 0),
            'strval1' => array(0, 0),
            'strval2' => array(1, 0),
            'agent' => array(0, 0),
            'ad_types' => array(0, 0),
            'ad_status' => array(0, 0),
            'post_type' => array(0, 0),
            'bus_line' => array(1, 1),
            'subway_line' => array(1, 1),
            'college_line' => array(1, 1),
            'company_id' => array(1, 0),
            'tab_system' => array(1, 2),

        ),
        /* int */'n' => array(
            'puid' => array(0, 0),
            'house_id' => array(0, 0),
            'premier_status' => array(1, 0),
            'refresh_at' => array(1, 0),
            'priority' => array(1, 0),
            'image_count' => array(1, 0),
            'bid_status' => array(0, 0),
            'listing_status' => array(0, 0),
            'post_at' => array(1, 0),
            'modified_at' => array(0, 0),
            'price' => array(1, 0),
            'intval1' => array(1, 0),
            'intval2' => array(1, 0),
            'area' => array(1, 0),
            'ceng' => array(1, 0),
            'zhuangxiu' => array(1, 0),
        ),
    );//}}}
    //{{{defaultQueryFieldArr
    protected static $defaultQueryFieldArr = array(
                'house_id',
                'district_id',
                'district_name',
                'street_id',
                'street_name',
                'xiaoqu',
                'xiaoqu_address',
                'latlng',
                'pinyin',
                'title',
                'price',
                'image_count',
                'huxing_shi',
                'huxing_ting',
                'huxing_wei',
                'thumb_img',
                'area',
                'tag_type',
                'refresh_at',
                'post_at',
                'puid',
                'major_category',
                'account_id',
                'company_id',
                'premier_status',
                'intval1',
                'intval2',
                'tab_system',
                'chaoxiang',
                'ceng',
                'ceng_total',
                'bus_line',
                'subway_line',
                'college_line',
                'house_type',
                'listing_status',
                'user_id',
                'phone',
                'zhuangxiu',
                'peizhi',
                'fang_xing',
                'person',
    );//}}}
    protected static $defaultOrderFieldArr = array('intval2'=>'desc', 'refresh_at' => 'desc');

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
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil',self::$majorCategoryId, self::XAPIAN_ID);
        //$searchHandle = PlatformSingleton::getInstance('Util_XapianSearchHandleUtil');
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
        //$searchHandle = PlatformSingleton::getInstance('Util_XapianSearchHandleUtil');
        return $searchHandle->getResult($searchId);
    }//}}}
}
