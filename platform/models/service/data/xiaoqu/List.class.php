<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: renyajing $ 
 * @file         $HeadURL: http://svn.corp.ganji.com:8888/svn/ganji_dev/code_base2/app/housing/platform/models/service/data/xiaoqu/List.class.php $ 
 * @version      $Rev: 367532 $ 
 * @lastChangeBy $LastChangedBy: renyajing$ 
 * @lastmodified $LastChangedDate: 2015-03-16 17:02:35 +0800 (Mon, 16 Mar 2015) $ 
 * @copyright Copyright (c) 2014, www.ganji.com
 */ 

class Service_Data_Xiaoqu_List
{
    //注意和HousingVars里XIAOQU_ORDER_IN_XIPIAN保持统一
    protected $searchOrder = array(
        'XIAOQUBAO_SELL_DESC'   => 1,
        'SELL_NUM_DESC'         => 2,
        'RENT_NUM_DESC'         => 3,
        'AVG_PRICE_SELL_DESC'   => 4,
        'AVG_PRICE_SELL_ASC'    => 5,
        'SELL_NUM_ASC'          => 6,
        'RENT_NUM_ASC'          => 7,
        'ZUFANG_NUM_DESC'         =>8,
        'ZUFANG_NUM_ASC'          =>9,
        'AVG_PRICE_CHANGE_DESC' =>10,
        'AVG_PRICE_CHANGE_ASC' =>11,
    );    
    protected $textFilter = array();
    public function getXiaoquList($queryFilter, $pageParam, $queryFields = array()){
        /*$data = array(
            'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
        );
        if ($listParam['city_code'] === null || $listParam['domain'] === null){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':city_code or domain', ErrorConst::E_PARAM_INVALID_CODE);   
        }
        if(empty($listParam['major_category_id'])){
            $listParam['major_category_id'] = HousingVars::SELL_ID;
        }*/
        $queryConfig = $this->getQueryConfig($queryFilter, $pageParam, $queryFields);
        $data = $this->queryXiaoquList($queryConfig);
        return $data;
    }
    protected function getQueryConfig($queryFilter, $pageParam, $queryFields = array()){
        if ('HOT' == $queryFilter['type']) {
            $orderFields = $this->getHotXiaoquOrderFields();
        } else {
            $orderFields = $this->getOrderFields($queryFilter);
        }
        $offsetLimit = $this->getOffsetLimit($pageParam);
        $queryFilter['offset_limit'] = $offsetLimit;
        $queryFilter['avg_price'] = $this->getAvgPriceFilter($queryFilter);
        $this->setTextFilter($queryFilter);
        if(in_array($this->orderKey, array($this->searchOrder['ZUFANG_NUM_DESC'], $this->searchOrder['ZUFANG_NUM_ASC']))){
            $queryFilter['zufang_num'] =  array(0, 99999999);       //zufang_num asc 时，必须这样才起作用
        }
        $queryFilter['textFilter'] = $this->textFilter;
        $queryConfig = array(
            'queryFilter' => $queryFilter,
            'orderField'  => $orderFields,
            'queryField'  => $queryFields
        );
        return $queryConfig;
    }
    protected function setTextFilter($queryFilter){
        $this->setPinyinFilter($queryFilter);
        if ('SUBWAY' == $queryFilter['type']) {
            $this->setSubwayQueryFilter($queryFilter);
        } elseif ('NEARBY' == $queryFilter['type']) {
            $this->setNearByQueryFilter($queryFilter);
        }
    }
    protected function getOffsetLimit($pageParam){
        if ($pageParam['page'] < 1) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':page', ErrorConst::E_PARAM_INVALID_CODE);
        }
        if ($pageParam['pageSize'] < 1) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':pageSize', ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $offsetLimit = array(($pageParam['page'] - 1) * $pageParam['pageSize'], $pageParam['pageSize']);
    }
    protected function getHotXiaoquOrderFields(){
        return  array(
                'popularity' => 'desc',
                'avg_price_change' => 'desc',
                );
    }
    protected function getOrderFields($listParam){
        if($listParam['major_category_id'] == HousingVars::SELL_ID){
            $this->orderKey = $listParam['order'] ?  $listParam['order'] : $this->searchOrder['XIAOQUBAO_SELL_DESC'];
        } elseif($listParam['major_category_id'] == HousingVars::RENT_ID){
            $this->orderKey = $listParam['order'] ?  $listParam['order'] : $this->searchOrder['ZUFANG_NUM_DESC'];
        }
        if (isset(HousingVars::$XIAOQU_ORDER_IN_XIPIAN[ $this->orderKey ])) {
            $order_info = HousingVars::$XIAOQU_ORDER_IN_XIPIAN[ $this->orderKey ];
            if(!is_array($order_info[0])){
                $order_info = array($order_info);                  
            }
             foreach ((array)$order_info as $key => $val) {
                $orderFields[$val[0]] = $val[1];
            }            
        }
        return $orderFields;
    }
    protected function getAvgPriceFilter($listParam){
        $avgPrice = array();
        //价格区间处理
        if (is_null($listParam['price']) && !is_null($listParam['price_b']) && !is_null($listParam['price_e'])) {
            $p1 = $listParam['price_b'] * 10000;
            $p2 = $listParam['price_e'] * 10000;
        } else if (!is_null($listParam['price'])) {
            list( $p1, $p2 ) = HousingVars::getSellAveragePriceRange($listParam['domain'], $listParam['price']);
        } else {
            //当排序规则为按均价由低到高
            //为了去掉小区均价为0的
            if ( isset(HousingVars::$XIAOQU_ORDER_PRICE_RANGE[ $this->orderKey ] )) {
                list( $p1, $p2 ) = HousingVars::$XIAOQU_ORDER_PRICE_RANGE[ $this->orderKey ];
            }
        }
        if ( isset($p1) && isset($p2) ) {
            $avgPrice = array($p1, $p2);
        }
        return $avgPrice;
    }
    protected function setPinyinFilter($listParam){
        if ( is_numeric($listParam['pinyin_id'])) {
            $pinyin = XiaoquPageConfig::$pinyinType4Xipian[ $listParam['pinyin_id'] ];
            $this->textFilter[0]['pinyin_prefix'] = $pinyin;
        }
    }
    protected function setNearByQueryFilter($listParam){
        //坐标查询处理
        if (!empty($listParam['lat']) && !empty($listParam['lng'])) {
            $radius = $listParam['radius'] > 0 ? $listParam['radius'] : 3000;
            //$client = new BusSubwayCollegeNamespce();
            $client = $this->getSubwayObject();
            self::$LATLNG_RANGE = $client->getLatLngRange2($listParam['lat'], $listParam['lng'], $radius);
            $this->textFilter[0]['latlng'] = self::$LATLNG_RANGE;
        }   
    }
    protected function getSubwayObject(){
        return new Dao_Location_BusSubwayCollege();
    }
    protected function setSubwayQueryFilter($listParam){
        if (!in_array($listParam['domain'], HousingVars::$DOMAIN_IN_SUBWAY_CITY)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.": no subway in this city", ErrorConst::E_PARAM_INVALID_CODE);            
        }
        //$client = new BusSubwayCollegeNamespce();
        $client = $this->getSubwayObject();
        $LINE = $client->getSubwayByCityId($listParam['city_code']);
        if (!is_null($listParam['subway_line'])) {
            $lineInfo = $client->getSubwayStationByLineId($listParam['city_code'], $listParam['subway_line']);
            if ($lineInfo != null) {
                $STATIONS = $lineInfo['stations'];
            }
            if (!is_null($listParam['station'])) {
                $STATION = $client->getSubwayStationInfoByLineIdAndStationNumber($listParam['city_code'], $listParam['subway_line'], $listParam['station']);
                if ($STATION) {
                    //由于是画方算法，乘以一个系数
                    $distance = !is_null($listParam['walk']) ? HousingVars::$WALK_TIME_VALUES [$listParam['walk']] * 0.8 : 3000 * 0.8;
                    $LATLNG_RANGE = $client->getLatLngRange2($STATION ['lat'], $STATION ['lng'], $distance);
                }
            }
        }       
        //地铁线路
        if ($listParam['subway_line'] === null) {
            $this->textFilter[0]['subway_line'] = 'all';
        } else {
            $this->textFilter[0]['subway_line'] = $listParam['subway_line'];
        }
        //地铁站点
        if (isset($LATLNG_RANGE)) {
            unset($this->textFilter[0]['subway_line']);
            $this->textFilter[0]['latlng'] = $LATLNG_RANGE;
        } else {
            if (!is_null($listParam['walk'])) {
                if (!is_null($listParam['subway_line'])) {
                    $filterName = 'subwaydistance_' . $listParam['subway_line'];
                } else {
                    $filterName = 'subwaydistance';
                }
                $this->textFilter[0][$filterName] = HousingVars::$WALK_TIME_VALUES [$listParam['walk']];
            }
        }
    }
    public function queryXiaoquList($queryConfigArr){
        try {
            if (!is_array($queryConfigArr) || empty($queryConfigArr)
                || empty($queryConfigArr['queryFilter']) || !is_array($queryConfigArr['queryFilter'])
            ) {
                $data = array(
                    'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
                    'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
                );
            } else {
                $obj = Gj_LayerProxy::getProxy('Dao_Xapian_Xiaoqu');
                $searchResult = $obj->getXiaoquList($queryConfigArr);
                $data = array(
                    'errorno'  => ErrorConst::SUCCESS_CODE,
                    'errormsg' => ErrorConst::SUCCESS_MSG,
                    'data' => array(
                        'items' => $searchResult[0],
                        'total' => $searchResult[1]
                    ),
                );
            }
        } catch(Exception $e){
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
        return $data;
    }
}
