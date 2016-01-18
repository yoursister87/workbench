<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: renyajing <renyajing@ganji.com>$ 
 * @author       $Author: liuzhen1 <liuzhen1@ganji.com>$ 
 * @author       $Author: zhenyangze <zhenyangze@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 */ 

class Service_Data_Xiaoqu_Price
{
    /* getRentPriceTrend 小区租房均价走势 {{{ */
    /** 
     * @params $paramsArr = array(
         'cityCode' => 0,
         'districtId' => 0,
         'streetId' => 52,
         'xiaoquIds' => array(),
'category'  => 1 || 3
)
* @param $dateBEArr = array('begin' => xxxxx, 'end' => 'xxxxxx', 'monthNum' => 'monthNum'); 
* @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
    * @return array() 如果没有数据返回array()
     */
    public function getRentPriceTrend($paramsArr, $dateBEArr, $type) {
        try {
            $priceList = array();
            $newDate = $dateBEArr;
            if (($patchNum = 12 - $dateBEArr['monthNum']) > 0) {
                $newDate['begin'] = date('Y-m', strtotime($dateBEArr['begin'] . ' -' . $patchNum . ' month'));
            }
            $newDate = $this->validatorDate($newDate);
            if (true === $this->validateParams($paramsArr, $type) && true === $this->validateMajorCategory($paramsArr['category'])) {
                $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPrice');
                $items = $model->getPriceList($paramsArr, $newDate, $type);
                $utilObj = new Util_XiaoquPriceTrendUtil();
                $priceList = $utilObj->formataPriceList($items, $dateBEArr['begin'], $dateBEArr['end'], $paramsArr['category']);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$priceList),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}} 
    /*{{{getRentPriceTrendByMultiArea 根据区域id获取租房均价及增幅*/
    /**
     * @params $paramsArr = array(
     *      'cityCode' => 0,
     *      'districtId' => array(0, 1, 2)
     * )
     * @params $type = 1
     * @return array()
     */
    public function getRentPriceTrendByMultiArea($paramsArr, $type) {

        try {
            if (true === $this->validateParams($paramsArr, $type)) {
                $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPrice');
                $items = $model->getPriceListByMultiArea($paramsArr, $type);
            } 
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items' => $items),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }
    /*}}}*/
    /*{{{getSellXiaoquCount 根据城市domain获取二手房均价区间内的小区数量*/
    /**
     * @params $city: 城市domain
     *
     * @return array()
     */
    public function getSellXiaoquCount($city, $isRandom=false, $districtId = -1, $streetId = -1){
        try{
            if(empty($city)){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "city不能为空", ErrorConst::E_PARAM_INVALID_CODE);
            }      
            //左开右闭
            $priceTypeValues = HousingVars::getAveragePriceRange($city, HousingVars::SELL);
            if(empty($priceTypeValues)){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "请检查是否传入的是city domain", ErrorConst::E_PARAM_INVALID_CODE);
            }
            $result = $priceTypeValues;
            foreach($result as $key =>$val){
                $result[$key] =0; 
            }
            $xiaoquIds = array();
            $maxSize = 2000;
            $cnt =0;
            $cache = Gj_Cache_CacheClient::getInstance('Memcache');
            $cacheKey = 'fang_xiaoqu_SellXiaoquCount_' . $city . '_' . $isRandom . '_' . $districtId . '_' . $streetId;
            $result = $cache->read($cacheKey);
            if (false == $result){
                $result = array();
                $model_stat = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
                list($xiaoquIds, $cntTotal) = $this->getXiaoquInfo($city, $isRandom, $districtId, $streetId, $maxSize);
                foreach($xiaoquIds as $key => $item){
                    $xiaoquIds[$key] = intval($item['id']);
                } 
                $cnt = count($xiaoquIds);
                $step = 100;
                for($i=0; $i<$cnt; $i+=$step){
                    $ids =array();
                    for($j=1; $j<=$step&&($i+$j)<=$cnt; $j++) {
                        $ids[] = $xiaoquIds[$i+$j-1 ];
                    }
                    $avg_prices= $model_stat->getXiaoquStatInfoByXiaoquId($ids, array('avg_price'));
                    foreach($avg_prices as $key =>$item){
                        $avg_prices[$key] = intval($item['avg_price']);
                    }                
                    foreach($avg_prices as $price){
                        //判决归属于那个价格区间
                        foreach($priceTypeValues as $key => $val){
                            if($price>$val[1]) { 
                                continue;
                            } else if($price>$val[0]){
                                $result[$key]++; 
                                break;
                            } 
                        }
                    }
                } 
                //如果统计方法，则对结果进行同比例放大
                if($isRandom && $maxSize<$cntTotal){
                    foreach($result as $key => $val){
                        $result[$key]= intval(($result[$key]/$maxSize)* $cntTotal);
                    }
                }
                $cache->write($cacheKey, $result);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('result' => $result),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } 
    /*}}}*/
    /*{{{getDistrictSellPriceTrend 根据区域id获取二手房均价及增幅*/
    /**
     * @params $paramsArr = array(
     *      'cityCode' => 0,
     *      'districtId' => array(0, 1, 2)
     * )
     * @params $type = 1
     * @return array()
     */
    public function getSellPriceTrendByMultiArea($paramsArr, $type) {
        try {
            if (true === $this->validateParams($paramsArr, $type)) {
                $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_HouseSellAvgPrice');
                $items = $model->getPriceListByMultiArea($paramsArr, $type);
            } 
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items' => $items),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }
    /*}}}*/
    /* getSellPriceTrend 小区二手房均价走势 {{{ */
    /** 
     * @params $paramsArr = array(
         'cityCode' => 0,
         'districtId' => 0,
         'streetId' => 52,
         'category' => 5,
         'xiaoquIds' => array(),
)
* @param $dateBEArr = array('begin' => xxxxx, 'end' => 'xxxxxx', 'monthNum' => 'monthNum'); 
* @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
    * @return array() 如果没有数据返回array()
     */
    public function getSellPriceTrend($paramsArr, $dateBEArr, $type) {
        try {
            $priceList = array();
            $newDate = $dateBEArr;
            if (($patchNum = 12 - $dateBEArr['monthNum']) > 0) {
                $newDate['begin'] = date('Y-m', strtotime($dateBEArr['begin'] . ' -' . $patchNum . ' month'));
            }
            $newDate = $this->validatorDate($newDate);
            if (true === $this->validateParams($paramsArr, $type) && true === $this->validateMajorCategory($paramsArr['category'])) {
                $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_HouseSellAvgPrice');
                $items = $model->getPriceList($paramsArr, $newDate, $type);
                $utilObj = new Util_XiaoquPriceTrendUtil();
                $priceList = $utilObj->formataPriceList($items, $dateBEArr['begin'], $dateBEArr['end'], $paramsArr['category']);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$priceList),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}} 
    /**getXiaoquSellPriceRank 小区二手房均价增幅降幅{{{*/
    /**
     * @params $xiaoquInfo = array(
         'cityCode' => 0,
         'districtId' => 0,
         'streetId' => 3
     )
     * @params $limit 取排行的前后几名
     * @return array()
     */
    public function getXiaoquSellPriceRank($xiaoquInfo, $limit = 5){
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_HouseSellAvgPrice');
            $rateOrder = $model->getXiaoquPriceRankList($xiaoquInfo);
            $priceOrder = $model->getXiaoquPriceOrderList($xiaoquInfo);
            list($data['rateTop'], $data['rateLast']) = $this->sliceArray($rateOrder, $limit);
            list($data['priceTop'], $data['priceLast']) = $this->sliceArray($priceOrder, $limit);
            $data = $this->formataAvgChange($data);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $data,
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**sliceArray{{{*/
    protected function sliceArray($itemList, $limit = 5){
        $topLimit = $lastLimit = array();
        if (is_array($itemList) && !empty($itemList)) {
            $topLimit = array_slice($itemList, 0, $limit);
            $lastLimit = array_slice($itemList, -$limit, $limit);
        }
        return array($topLimit, $lastLimit);
    }//}}}
    /**formataAvgChange{{{*/
    protected function formataAvgChange($infoList){
        if (!is_array($infoList) || empty($infoList)) {
            return array();
        }
        foreach ($infoList as $key => &$list) {
            foreach ($list as &$item) {
                $item['avg_price_change'] = $item['avg_price_change'] * 100;
                if (isset($item['num'])) {
                    unset($item['num']);
                }
            }
        }
        return $infoList;
    }//}}}
    /*validateParams{{{*/
    /**
     * @params $paramsArr = array(
         'cityCode' => 0,
         'districtId' => 0,
         'streetId' => 52,
         'xiaoquIds' => array();
    )
        * @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
        * @return array() 如果没有数据返回array()
     */
    protected function validateParams($paramsArr, $type) {
        $stepType = array(0, 1, 2, 3);
        if (!in_array($type, $stepType)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "查询均价类型错误或为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (empty($paramsArr) || !is_array($paramsArr)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "查询均价条件为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (!isset($paramsArr['cityCode'])) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "city_code不能为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (1 === (int)$type && !isset($paramsArr['districtId'])) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "查询区域均价districtId为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (2 === (int)$type && (!isset($paramsArr['districtId']) ||  !isset($paramsArr['streetId']))) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "查询街道均价districtId或streetId为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (3 === (int)$type && (empty($paramsArr['xiaoquIds']) || !is_array($paramsArr['xiaoquIds']))) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "查询小区均价xiaoquId为空", ErrorConst::E_PARAM_INVALID_CODE);
        }
        return true;
    }//}}}
    //{{{校验查询时间段是否合法
    protected function validatorDate($dateBEArr){
        //list($dateBegin, $dateEnd) = $dateBEArr;
        $dateBegin = $dateBEArr['begin'];
        $dateEnd = $dateBEArr['end'];
        $recordTimeBegin = strtotime(date("Y-m-1",strtotime($dateBegin." +1 month")));
        $recordTimeEnd   = strtotime(date("Y-m-1",strtotime($dateEnd." +1 month")));
        if($recordTimeBegin >= $recordTimeEnd){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":结束时间不能小于开始时间", ErrorConst::E_PARAM_INVALID_CODE);
        }
        return array('begin' => $recordTimeBegin, 'end' => $recordTimeEnd);
    }//}}}
    //{{{校验要查询的category
    protected function validateMajorCategory($category){
        $categoryArr = array(1, 3, 5);
        if (empty($category) || !in_array($category, $categoryArr)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":category为必选项", ErrorConst::E_PARAM_INVALID_CODE);
        }
        return true;
    }//}}}
    /**just for test{{{*/
    /**
     * @brief 
     * @param $func
     * @param $args
     * @codeCoverageIgnore
     */
    public function __call($func, $args) {
        if (Gj_LayerProxy::$is_ut === true) {
            switch (count($args)) {
            case 1:
                return $this->$func($args[0]);
            case 2:
                return $this->$func($args[0], $args[1]);
            case 3:
                return $this->$func($args[0], $args[1], $args[2]);
            default :
                return $this->$func();
            }
        }
    }//}}}
    /**getXiaoquInfo
     * @brief
     * @param $city
     * @param $isRandom
     * @param $district
     * @param $street
     * @return array
     * @codeCoverageIgnore
     */
    protected function getXiaoquInfo($city, $isRandom, $district, $street, $maxSize){
        $xiaoquId = array();
        $cntTotal = null;
        $model_xiaoqu = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
        if($isRandom){
            //采取统计的方式获取数据,并不严格保证绝对精确,对于类似北京(2w)，上海(4w)，成都(2w)之类的城市可以采取如此方式
            $ret = $model_xiaoqu->getXiaoquInfoByCityByRandom($city, array('id'), $maxSize);
            $xiaoquIds = $ret['result'];
            $cntTotal = intval($ret['count']);
        } else {
            if ($district < 0 && $street < 0){
                $xiaoquIds = $model_xiaoqu->getXiaoquInfoByCity($city, array('id'));
            } else if ($district >= 0 && $street < 0){
                $xiaoquIds = $model_xiaoqu->getXiaoquInfoByCityDistrict($city, $district, array('id'));
            } else {
                $xiaoquIds = $model_xiaoqu->getXiaoquInfoByCityDistrictStreet($city, $district, $street, array('id'));
            }
        }
        return array($xiaoquIds, $cntTotal);
    }//}}}
}
