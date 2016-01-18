<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Dao_Xapian_Gongyu
{
    const XAPIAN_ID = 1014;
    protected static $majorCategoryId = 20;
    protected static $xapianFieldArr = array(
        'f' => array(
            "city" => array(1, 0),
            "district_id" => array(2, 0),
            "district_name" => array(1, 1),
            "street_id" => array(1, 0),
            "street_name" => array(1, 1),
            "title" => array(1, 1),
            "description" => array(1, 1),
            "image" => array(0, 0),
            "huxing_image" => array(0, 0),
            "tel" => array(0, 0),
            "apartment_id" => array(1, 0),
            "apartment_name" => array(1, 1),
            "address" => array(1, 1),
            "latlng" => array(1, 1),
            "chaoxiang" => array(1, 0),
            "huxing_shi" => array(1, 0),
            "huxing_ting" => array(1, 0),
            "huxing_wei" => array(1, 0),
            "tab_system" => array(1, 2),
            "tab_personality" => array(0, 0),
            "status" => array(1, 0),
            "model_name" => array(0, 0),
            "company_id" => array(1, 0),
            'bus_line' => array(1, 1),
            'subway_line' => array(1, 1),
            'college_line' => array(1, 1),
        ),
        'n' => array(
            "puid" => array(0, 0),
            "id" => array(0, 0),
            "image_count" => array(0, 0),
            "price" => array(1, 0),
            "area" => array(0, 0),
            "ceng" => array(1, 0),
            "ceng_total" => array(0, 0),
            "post_at" => array(1, 0),
            "modified_at" => array(1, 0),
            "weight" => array(1, 0),
            "company_id" => array(1, 0),
        ),       
    );
    protected static $defaultQueryFieldArr = array(
        "puid",
        "id",
        "city",
        "district_id",
        "district_name",
        "street_id",
        "street_name",
        "title",
        "description",
        "image",
        "huxing_image",
        "image_count",
        "price",
        "tel",
        "apartment_id",
        "apartment_name",
        "address",
        "latlng",
        "area",
        "ceng",
        "ceng_total",
        "chaoxiang",
        "huxing_shi",
        "huxing_ting",
        "huxing_wei",
        "tab_system",
        "tab_personality",
        "status",
        "post_at",
        "modified_at",
        "weight",
        "model_name",
        "company_id",
        'subway_line',
        'bus_line',
        'college_line',
    );
    protected static $defaultOrderFieldArr = array(
        'weight' => desc,    
    );
    public function preSearch($queryConfigArr){
        $searchUtil = new Util_HouseXapian();
        $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfigArr, self::$xapianFieldArr, self::$defaultQueryFieldArr, self::$defaultOrderFieldArr);
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil', self::$majorCategoryId);
        return $searchHandle->query($builder->getQueryString());
    }
    public function getSearchResult($searchId){
        $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil', self::$majorCategoryId);
        return $searchHandle->getResult($searchId);

    }
    //status
}
