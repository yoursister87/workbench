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

class Dao_Xapian_Zufang 
{
    const XAPIAN_ID = 201;
    protected static $majorCategoryId = 20;
    //{{{ xapianFieldArr
    protected static $xapianFieldArr = array(
        /* str */'f' => 
        array(
            'top_info' => array(1, 1),
            'is_premier' => array(0, 0),
            'city' => array(1, 0),
            'district_id' => array(1, 0),
            'district_name' => array(1, 1),
            'street_id' => array(1, 0),
            'street_name' => array(1, 1),
            'title' => array(1, 1),
            'description' => array(0, 0),
            'thumb_img' => array(0, 0),
            'person' => array(0, 0),
            'phone' => array(1, 1),
            'major_category' => array(2, 0),
            'agent' => array(1, 0),
            'post_type' => array(1, 0),
            'xiaoqu' => array(1, 1),
            'xiaoqu_id' => array(1, 0),
            'xiaoqu_address' => array(1, 1),
            'pinyin' => array(0, 0),
            'fang_xing' => array(1, 0),
            'latlng' => array(1,1),
            'ceng_total' => array(0, 0),
            'chaoxiang' => array(1, 0),
            'huxing_shi' => array(1, 0),
            'huxing_ting' => array(1, 0),
            'huxing_wei' => array(1, 0),
            'peizhi' => array(0, 0),
            'jichu' => array(0, 0),
            'pay_type' => array(0, 0),
            'share_type' => array(1, 0),
            'share_mode' => array(1, 0),
            'house_type' => array(1, 0),
            'rent_sex_request' => array(1, 0),
            'strval1' => array(1, 1),
            'strval2' => array(1, 0),
            'ad_types' => array(0, 0),
            'ad_status' => array(0, 0),
            'bus_line' => array(1, 1),
            'subway_line' => array(1, 1),
            'college_line' => array(1, 1),
            'tab_system' => array(1, 2),
            'auth_status' => array(1, 0),
            'company_id' => array(1, 0),//端口贴过来的置顶帖会有此字段
        ), 
        /* int */'n' => 
        array(
            'puid' => array(0, 0),
            'id' => array(0, 0),
            'listing_status' => array(1, 0),
            'post_at' => array(1, 0),
            'user_id' => array(0, 0),
            'image_count' => array(1, 0),
            'refresh_at' => array(0, 0),
            'show_time' => array(0, 0),
            'price' => array(1, 0),
            'area' => array(1, 0),
            'ceng' => array(1, 0),
            'niandai' => array(1, 0),
            'zhuangxiu' => array(1, 0),
            'intval1' => array(1, 0),
            'intval2' => array(1, 0),
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
                'district_name',
                'street_name',
                'listing_status',
                'post_type',
                'xiaoqu_address',
                'image_count',
                'latlng',
                'huxing_shi',
                'huxing_ting',
                'huxing_wei',
                'area',
                'price',
                'agent',
                'xiaoqu',
                'pinyin',
                'top_info',
                'puid',
                'chaoxiang',
                'ceng',
                'ceng_total',
                'niandai',
                'major_category',
                'thumb_img',
                'phone',
                'person',
                'user_id',
                'ad_types',
                'ad_status',
                'listing_status',
                'tab_system',
                'auth_status',
                'house_type',
                'zhuangxiu',
                'user_word',
                'editor_word',
                'company_id',
            );//}}}
    protected static $defaultOrderFieldArr = array('listing_status' => 'desc', 'intval1' => 'desc');

    /* {{{preSearch*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     *
     * @codeCoverageIgnore
     */
    public function preSearch($queryConfigArr){
        // create builder
        
        $searchUtil = new Util_HouseXapian();
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
            
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
    /* {{{getBuilder*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     *
     * @codeCoverageIgnore
     */
    public function getBuilder($queryConfigArr){
        $searchUtil = new Util_HouseXapian();
        return $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
    }//}}}

}
