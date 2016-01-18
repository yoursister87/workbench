<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Dao_Xiaoqu_HouseSellAvgPrice extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "house_sell_avg_price";
    protected $table_fields = array(
        'id',
        'city',    //city_code
        'district_id',    //区域script_index
        'street_id',    //街道script_index
        'xiaoqu_id',   
        'primary_price',    //初始均价
        'modify_number',    //修正后帖子数(如果帖子数不足20 则该值为该小区所在街道的房源数)
        'relay_number',    //帖子数目
        'step_type',    //0:city 1:district 2:street 3:xiaoqu
        'record_time',    //统计的日期 统计为当天的0点 数据为前一天
        'avg_price',    //计算后的均价
        'soufang_price',    //搜房相关均价
        'soufang_number',    //搜房相关房源数
        'ljdc_price',    //链家相关均价
        'ljdc_number',    //链家相关房源数
        'avg_price_change'    //月均价环比
    );
    /*getPriceList{{{*/
    /**
     * @params $paramsArr = array(
                    'cityCode' => 0,
                    'districtId' => 0,
                    'streetId' => 52,
                    'xiaoquIds' => array(),
                  )
     * @param $dateBEArr = array('begin' => xxxxx, 'end' => 'xxxxxx'); 
     * @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
     * @return array() 如果没有数据返回array()
     */
    public function getPriceList($paramsArr, $dateBEArr, $type){
        $itemList = array();
        $fields = array('id', 'district_id', 'street_id', 'xiaoqu_id', 'avg_price_change', 'avg_price', 'record_time');
        $conArrays = array('city =' => $paramsArr['cityCode']);
        if (1 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
        } elseif (2 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
            $conArrays['street_id ='] = $paramsArr['streetId'];
        } elseif (3 === (int)$type) {
            $xiaoquIds = implode(',', $paramsArr['xiaoquIds']);
            $conArrays[] = 'xiaoqu_id in (' . $xiaoquIds . ')';
        }
        $conArrays['step_type = '] = $type;
        $conArrays['record_time >='] = $dateBEArr['begin'];
        $conArrays['record_time <= '] = $dateBEArr['end'];
        $itemList = $this->select($fields, $conArrays);
        if (FALSE === $itemList) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getPriceList : " . $paramsArr, ErrorConst::E_SQL_FAILED_CODE);
        }
        return $itemList;
    }//}}}
    /*{{{getPriceListByMultiArea*/
    /**
     * @params $paramsArr = array(
                    'cityCode' => 0,
                    'districtIds' => array(),
                  )
     * @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
     * @return array() 如果没有数据返回array()
     */ 
    public function getPriceListByMultiArea($paramsArr, $type) {
        $itemList = array();
        $fields = array('id', 'district_id', 'street_id', 'xiaoqu_id', 'avg_price_change', 'avg_price', 'record_time');
        $conArrays = array('city =' => $paramsArr['cityCode']);
        $conArrays['step_type = '] = $type;

        if (1 === (int)$type) {
            $districtIds = implode(',', $paramsArr['districtId']);
            $conArrays[] = 'district_id in (' . $districtIds . ')';
        } elseif (2 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
            $streetIds = implode(',', $paramsArr['streetId']);
            $conArrays[] = 'street_id in (' .$streetIds . ')';
        } elseif (3 === (int)$type) {
            $xiaoquIds = implode(',', $paramsArr['xiaoquIds']);
            $conArrays[] = 'xiaoqu_id in (' . $xiaoquIds . ')';
        }
 
        $endTime = $this->getSearchTime();
        $startTime = $this->getSearchTime(strtotime('-1 month'));
        $conArrays[] = 'record_time in (' . $startTime . ',' . $endTime . ')';
        $itemList = $this->select($fields, $conArrays);
        $itemList = $this->formatPriceTrendByMultiArea($itemList);
        if (FALSE === $itemList) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getPriceList : " . $paramsArr, ErrorConst::E_SQL_FAILED_CODE);
        }
        return $itemList;
 
    }
    /**/
    /**getXiaoquPriceRankList{{{*/
    /**
     * @param $xiaoquInfo = array(
                'cityCode' => 0,
                'districtId' => district_script_index,
                'streetId' => street_script_index
              );
     * @return array()
     */
    public function getXiaoquPriceRankList($xiaoquInfo){
        $fields = array('xiaoqu_id', 'avg_price', 'avg_price_change', '(avg_price*avg_price_change)/(avg_price_change + 1) AS num');
        $conArrays = $this->getConArrays($xiaoquInfo);
        $appends = array('ORDER BY num DESC ');
        $rankList = $this->getListInfo($fields, $conArrays, $appends);
        return $rankList;
    }//}}}
    /**getXiaoquPriceRankList{{{*/
    /**
     * @param $xiaoquInfo = array(
                'cityCode' => 0,
                'districtId' => district_script_index,
                'streetId' => street_script_index
              );
     * @return array()
     */
    public function getXiaoquPriceOrderList($xiaoquInfo){
        $orderList = array();
        $fields = array('xiaoqu_id', 'avg_price', 'avg_price_change');
        $conArrays = $this->getConArrays($xiaoquInfo);
        $appends = array('ORDER BY avg_price DESC ');
        $orderList = $this->getListInfo($fields, $conArrays, $appends);
        return $orderList;
    }//}}}
    /**getListInfo{{{*/
    protected function getListInfo($fields, $conArrays, $appends){
        $itemList = array();
        $i = 1;
        while (empty($itemList) && $i <= 2) {
            $itemList = $this->dbHandler->select($this->tableName, $fields, $conArrays, null, $appends);
            if ($itemList === FALSE) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . $this->dbHandler->getLastSql(), ErrorConst::E_SQL_FAILED_CODE);
            }
            $conArrays['record_time ='] = $this->getSearchTime($conArrays['record_time =']);
            ++$i;
        }
        return $itemList;
    }//}}}
    /*{{{formatPriceTrendByMultiArea*/
    protected function formatPriceTrendByMultiArea($itemList){
        $count = count($itemList);
        $postList = Array();
        if(!is_array($itemList) || count($itemList) == 0){
            return $postList;
        }

        for($i=0; $i<$count; $i++){
            $key = 0;
            for($j=$i+1; $j<$count; $j++){
                if($itemList[$i]['district_id'] == $itemList[$j]['district_id'] && $itemList[$i]['street_id'] == $itemList[$j]['street_id'] &&$itemList[$i]['xiaoqu_id'] == $itemList[$j]['xiaoqu_id']){ 
                    $k = 1;
                    $avg_price_change = 0.00;
                    if($itemList[$i]['record_time'] > $itemList[$j]['record_time']){
                        if(!empty($itemList[$j]['avg_price'])){
                            $avg_price_change = round($itemList[$i]['avg_price']/$itemList[$j]['avg_price'] - 1 , 2);
                        } 
                        $itemList[$i]['avg_price_change'] = $avg_price_change;
                        $postList[] = $itemList[$i];
                    } else {
                        if(!empty($itemList[$i]['avg_price'])){
                            $avg_price_change = round($itemList[$j]['avg_price']/$itemList[$i]['avg_price'] - 1, 2);
                        }
                        $itemList[$j]['avg_price_change'] = $avg_price_change;
                        $postList[] = $itemList[$j];
                    }
                }
            }
            if($k == 0){
                $postList[] = $itemList[$i];
            }
        }
        return $postList;
    }
    /*}}}*/
    /**getConArrays{{{*/
    protected function getConArrays($xiaoquInfo){
        if (empty($xiaoquInfo) || !is_array($xiaoquInfo)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $conArrays['step_type ='] = 3;
        $conArrays['city='] = $xiaoquInfo['cityCode'];
        $conArrays['district_id ='] = $xiaoquInfo['districtId'];
        $conArrays['street_id ='] = $xiaoquInfo['streetId'];
        $conArrays['record_time ='] = $this->getSearchTime();
        return $conArrays;
    }//}}}
    /**getSearchTime{{{*/
    protected function getSearchTime($searchTime = 0){
        if ($searchTime > 0) {
            //重新获取时间
            $day = date('d', $searchTime);
            if ($day >= 15) {
                $searchTime = strtotime(date('Y-m-01', $searchTime));
            } else {
                $searchTime = strtotime(date('Y-m-15', strtotime(date('Y-m-d', $searchTime) . ' -1 month')));
            }
        } else {
            //获取当前日期最近的1号或者15号的数据
            $currentTime = $this->getTime();
            $date15 = strtotime(date('Y-m-15', $currentTime));
            if ($currentTime >= $date15) {
                $searchTime = $date15;
            } else {
                $searchTime = strtotime(date('Y-m-01', $currentTime));
            }
        }
        return $searchTime;
    }//}}}
    /**getTime{{{*/
    /**
     * @codeCoverageIgnore
     */
    protected function getTime(){
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        return $now;
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
}

