<?php
/*
 * File Name:CheckData.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Data_HouseReport_CheckData
{

    CONST PAGE_ERROR_NUM = 2001;
    CONST DATE_ERROR_NUM = 2002;
    CONST HOUSE_TYPE_ERROR_NUM = 2003;
    CONST FIELDS_ERROR_NUM = 2004;
    CONST ORDER_ERROR_NUM = 2005;
    CONST COUNT_TYPE_ERROR_NUM = 2006;
    CONST PRODUCT_TAGS_ERROR_NUM = 2007;
    CONST PRODUCT_DATA_ERROR_NUM = 2008;
    CONST ORG_DATA_ERROR_NUM = 2009;

    CONST OUTLET_DATA_ERROR_NUM = 2010;

  /*{{{ 设置分页*/
    public function setPage($config){
        $res = array();
        if (is_array($config) && !empty($config)) {
            foreach ($config as $key=>$item) {
                if (in_array($key,array('currentPage','pageSize')) && is_numeric($item)) {
                    $res[$key] = $item;
                } else {
                    throw new Gj_Exception(self::PAGE_ERROR_NUM,"分页配置错误");
                }
            }
        }
        return $res;
    }
    /*}}}*/
    /*{{{ 设置日期*/
	 /** 
     *@codeCoverageIgnore
     **/
    public function setDate($config,$oneMonth = true){
        $arr = array();
        $util = new Util_HouseReportUtil();
        //验证是否是合法的 2014-09-25 这样的日期
        if (!$util->checkDate($config['eDate']) || !$util->checkDate($config['sDate'])) {
            throw new Gj_Exception(self::DATE_ERROR_NUM,"日期设置错误");
        }
        if((strtotime($config['eDate']) < strtotime($config['sDate']))) {
            throw new Gj_Exception(self::DATE_ERROR_NUM,"开始日期不能大于结束日期");
        }
        if ((!$util->assertIsValidDatePeriod($config['sDate'],$config['eDate']))  && $oneMonth === true){
            throw new Gj_Exception(self::DATE_ERROR_NUM,"不能查询跨月的数据");
        }

        return array('sDate'=>$config['sDate'],'eDate'=>$config['eDate']);
    }
    /*}}}*/
	/*{{{设置房源类型*/
    public function setHouseType($config){
        if (is_array($config) && !empty($config)){
            return $config;
        } else {
            throw new Gj_Exception(self::HOUSE_TYPE_ERROR_NUM,"房源类型设置有误");
        }
    }
    /*}}}*/
    /*{{{ 设置countType*/
    public function setCountType($config){
        if (is_array($config) && !empty($config)){
            return $config;
        } else {
            throw new Gj_Exception(self::COUNT_TYPE_ERROR_NUM,"类型设置有误");
        }
    }
    /*}}}*/
    /*{{{ 设置取字段*/
    public function setFields($config){
        if (is_array($config) && !empty($config)){
            return $config;
        } else {
            throw new Gj_Exception(self::FIELDS_ERROR_NUM,"字段设置有误");
        }
    }
    /*}}}*/
    /*{{{ 设置排序*/
    public function setOrder($config){
        if (isset($config['orderField']) && isset($config['order'])) {
            return array('orderField'=>$config['orderField'],'order'=>$config['order']);
        } else {
            throw new Gj_Exception(self::ORDER_ERROR_NUM,"设置排序有误");
        }
    }
    /*}}}*/
    /*{{{ 设置取商品类型*/
    public function setProduct($config){
        if (is_array($config) && !empty($config)){
            return $config;
        } else {
            throw new Gj_Exception(self::PRODUCT_TAGS_ERROR_NUM,"商品类型设置有误");
        }
    }
    /*}}}*/

    /*{{{ 设置取商品类型*/
    public function setBusinessScope($config){
        if (is_array($config) && !empty($config)){
            foreach($config as $key=>$item) {
                if (!is_numeric($item)){
                    unset($config[$key]);
                }
            }
            return $config;
        } else {
            throw new Gj_Exception(self::PRODUCT_TAGS_ERROR_NUM,"端口类型设置有误");
        }
    }
    /*}}}*/

    /*{{{ 设置取商品类型*/
    public function setEverydayDownloadDate($config){
        $maxDate = 7;
        $util = new Util_HouseReportUtil();
        //验证是否是合法的 2014-09-25 这样的日期
        if (!$util->checkDate($config['eDate']) || !$util->checkDate($config['sDate'])) {
            throw new Gj_Exception(self::DATE_ERROR_NUM,"日期设置错误");
        }
        if(strtotime($config['eDate']) < strtotime($config['sDate'])) {
            throw new Gj_Exception(self::DATE_ERROR_NUM,"开始日期不能大于结束日期");
        }
        $spaceDay = (strtotime($config['eDate']) - strtotime($config['sDate']))/3600/24;
        if ($spaceDay > $maxDate) {
            throw new Gj_Exception(self::DATE_ERROR_NUM,"每日详情报表不能下载超过7天数据");
        }

        return array('sDate'=>$config['sDate'],'eDate'=>$config['eDate']);
    }
    /*}}}*/
}
