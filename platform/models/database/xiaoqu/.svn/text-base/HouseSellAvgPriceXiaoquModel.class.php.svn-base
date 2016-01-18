<?php

class HouseSellAvgPriceXiaoquModel extends BaseXiaoquModel {
    
    protected $table = 'house_sell_avg_price';

    //house_sell_avg_price表是每个月的1号或者15号更新
    protected function getSearchTime($searchTime=0) {
    	if ($searchTime > 0) {
    		//重新获取时间
    		$day = date('d', $searchTime);
    		if ($day >= 15) {
    			$searchTime = strtotime(date('Y-m-01', $searchTime));
    		} else {
    			$searchTime = strtotime(date('Y-m-15', strtotime('-1 month')));
    		}
    	} else {
	    	//获取当前日期最近的1号或者15号的数据
	    	$currentTime = time();
	    	$date15 = strtotime(date('Y-m-15', $currentTime));
	    	if ($currentTime > $date15) {
	    		$searchTime = $date15;
	    	} else {
	    		$searchTime = strtotime(date('Y-m-01', $currentTime));
	    	}
	    }
    	return $searchTime;
    }

    //价格涨幅排行榜
    public function getSellPriceRankList($cityCode, $districtScriptIndex, $streetScriptIndex) {
    	$cityCode = intval($cityCode);
    	$districtScriptIndex = intval($districtScriptIndex);
    	$streetScriptIndex = intval($streetScriptIndex);
    	//house_sell_avg_price表是每个月的1号或者15号更新
    	$searchTime = $this->getSearchTime();
    	$sql = "SELECT xiaoqu_id,avg_price,avg_price_change,(avg_price*avg_price_change)/(avg_price_change + 1) AS num FROM house_sell_avg_price WHERE step_type=3 AND record_time={$searchTime} AND city={$cityCode} AND district_id={$districtScriptIndex} AND street_id={$streetScriptIndex} ORDER BY num DESC";
    	$dbSlave = $this->getSlaveDbHandle();
    	$rateTop = $dbSlave->getAll($sql);
    	if (is_array($rateTop) && count($rateTop) > 0) {
    		//house_sell_avg_price表是每个月的1号或者15号更新
    		$searchTime = $this->getSearchTime($searchTime);
    		$sql = "SELECT xiaoqu_id,avg_price,avg_price_change,(avg_price*avg_price_change)/(avg_price_change + 1) AS num FROM house_sell_avg_price WHERE step_type=3 AND record_time={$searchTime} AND city={$cityCode} AND district_id={$districtScriptIndex} AND street_id={$streetScriptIndex} ORDER BY num DESC";
    		$rateTop = $dbSlave->getAll($sql);
    	}
    	if ($rateTop === FALSE) {
    		throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
    	}
    	foreach ($rateTop as $key => &$value) {
    		unset($value['num']);
    	}
    	//价格排行
    	$sql = "SELECT xiaoqu_id,avg_price,avg_price_change FROM house_sell_avg_price WHERE step_type=3 AND record_time={$searchTime} AND city={$cityCode} AND district_id={$districtScriptIndex} AND street_id={$streetScriptIndex} ORDER BY avg_price DESC";
    	$priceTop = $dbSlave->getAll($sql);
    	if ($priceTop === FALSE) {
    		throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
    	}
    	$data = array(
    		'rate_top' => $rateTop,
    		'price_top' => $priceTop,
    	);
    	return $data;
    }


    //获取区域价格数据
    public function getDistrictPriceTrend($geoParam, $dateBEArr) {        
        return $this->getSellPriceTrend($geoParam, $dateBEArr, 1);
    }   

    //获取商圈价格数据
    public function getStreetPriceTrend($geoParam, $dateBEArr) {
        return $this->getSellPriceTrend($geoParam, $dateBEArr, 2);
    }

    //获取小区价格数据                         
    public function getXiaoquSellPriceTrend($geoParam, $dateBEArr) {
        return $this->getSellPriceTrend($geoParam, $dateBEArr, 3); 
    }
    
     /**
      * @brief 获取指定起始月份的二手房均价
      * @param array $geoParam = array(
                        'city_code' =>0,
                        'districtScriptIndex'=>0,
                        'streetScriptIndex' =>0,
                        'xiaoquIdArr' => array(),
                    )
      * @param array $dateBEArr = array('2014-1', '2014-5')
      * @param int $step_type
      * @return array 如果没有数据返回array()
      {{{  
    */
    public function getSellPriceTrend($geoParam, $dateBEArr, $step_type){
        $city_code = $geoParam['city_code'];
        $districtScriptIndex = $geoParam['districtScriptIndex'];
        $streetScriptIndex   = $geoParam['streetScriptIndex'];
        $xiaoquIdArr = $geoParam['xiaoquIdArr'];

        list($dateBegin, $dateEnd) = $dateBEArr;
        $record_timeBegin = strtotime(date("Y-m-1",strtotime($dateBegin." +1 month")));
        $record_timeEnd   = strtotime(date("Y-m-1",strtotime($dateEnd." +1 month")));
        if($record_timeBegin >= $record_timeEnd){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":结束时间不能小于开始时间", ErrorConst::E_PARAM_INVALID_CODE);
        }
        $sql = "SELECT city,district_id,street_id,xiaoqu_id,avg_price,avg_price_change,record_time FROM house_sell_avg_price WHERE ";
        if($step_type < 3 ){
            if($city_code === null){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":city_code必填", ErrorConst::E_PARAM_INVALID_CODE);
            }
             if($streetScriptIndex != null && $districtScriptIndex == null){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":streetScriptIndex不为空的情况下，districtScriptIndex也不能为空", ErrorConst::E_PARAM_INVALID_CODE);
            }

            $sql.= " city={$city_code}";
            if($districtScriptIndex != null){
                $sql.= " AND district_id={$districtScriptIndex}";
            }
            if($streetScriptIndex != null){
                $sql.= " AND street_id={$streetScriptIndex} ";
            }
            $sql.= " AND step_type ={$step_type} ";
            $sql.= " AND record_time>={$record_timeBegin} AND  record_time<={$record_timeEnd} ";
        } elseif($step_type == 3){ //where顺序不同是为了使用sql索引
            if(empty($xiaoquIdArr)){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.":xiaoquId必填", ErrorConst::E_PARAM_INVALID_CODE);                
            }

            $xiaoquIdStr = implode(',', $xiaoquIdArr);
            $sql.= " xiaoqu_id IN ({$xiaoquIdStr}) ";
            $sql.= " AND record_time>={$record_timeBegin} AND  record_time<={$record_timeEnd} ";
            $sql.= " AND step_type ={$step_type} ";
        }
        $dbSlave    = $this->getSlaveDbHandle();
        $dbRowArr   = $dbSlave->getAll($sql);   
        $retArr     = $this->formatPerMonthRst($dbRowArr, $dateBegin, $dateEnd, $step_type);
        $newRetArr  = $this->supplyEmptyMonthData($retArr, $dateBegin, $dateEnd);
        return $newRetArr;
    } //}}}

    /**
     * @brief 生成月份时间段 {{{
     * @param string $dateBegin 开始年月
     * @param string $dateEnd 结束年月
     * @return array 如果没有数据返回array()
     */
    protected function genMonthArr($dateBegin, $dateEnd){
        //生成月份时间段
        $ret = array();
        $i = 0;
        while ( strtotime($dateBegin." +".$i." month") <= strtotime($dateEnd)){
            $dynamicMonth = date('Y-m', strtotime($dateBegin." +".$i." month"));
            $ret[$dynamicMonth] = array();//查询年月下默认赋值为array();
            $i++;
        }
        return $ret;
    }
    //}}}

    /** 
     * 将数据库原始数据格式化成指定月份对应的数据   {{{
     * @param array $dbRowArr
     * @param string $dateBegin 开始年月
     * @param string $dateEnd 结束年月
     * @param int $step_type 数据类别区分，3表示是小区数据
     */
    protected function formatPerMonthRst($dbRowArr, $dateBegin, $dateEnd, $step_type){
        $ret = $this->genMonthArr($dateBegin, $dateEnd);
        //此循环中非当月的15号的数据要作废不用，
        foreach((array) $dbRowArr as $k => $v){
            $month = '';
            if($step_type == 3){//当查询小区时需要有个小区维度
                $key = $v['xiaoqu_id'];
            } else {
                $key = $v['city'];
            } 
            if(empty($retArr[$key])){ //赋值用于占位的月份时间段数组
                $retArr[$key] = $ret; 
            }
            //通用规则：1号的数据则是上个月的均价
            if(date('d', $v['record_time']) == 1){ //$record_time为1号的数据则是上个月的均价
                $month = date('Y-m', strtotime(date('Y-m-d',$v['record_time']) . " -1 month"));              
            } 
            //特殊规则： 如果结束时间（$dateEnd）是当前月份,优先用record_time为15号的数据，没有则用1号数据
            $curMonth =date('Y-m', time()) ;            
            if(date('Y-m', strtotime($dateEnd)) == $curMonth){ //如果结束时间存在当前月份
                if( date('Y-m-d', $v['record_time']) == date('Y-m-15', time()) //当前月15号的数据，其他月份的15号数据作废不用
                    ||                     
                    (date('Y-m-d', $v['record_time']) == date('Y-m-1', time()) && empty($retArr[$key][$curMonth])) //当前月1号的数据 
                           
                  )
                { 
                    $month = $curMonth;
                }
            }

            if($month != '') {
                $retArr[$key][$month] = $v;
            }
                   
        }
        return $retArr;
    }
    //}}}

    /** 
     * @brief 没有数据的月份按照某一规则补充数据   {{{
     * @param array $dbRowArr
     * @param string $dateEnd 结束年月
     * @param int $step_type 数据类别区分，3表示是小区数据
     */
    protected function supplyEmptyMonthData($retArr, $dateBegin, $dateEnd){
        $newRetArr = array();
        //没有数据的月份补数据.规则：如4月份没有数据，按照3、5、2、6、1、7的方式获取
        foreach ((array)$retArr as $k => $v) { //$k是上边的$key
            foreach ((array)$v as $kk => $vv) { //$kk是月份
                if(!empty($vv)){ 
                     $newRetArr[$k][$kk] = $vv;
                } else {//当前月没有数据时,需要补数据
                    $i = 0;
                    $flag = true;
                    while ($flag) {//获取最近月份的数据
                        $pre_Month = date("Y-m", strtotime($kk." -".$i." month"));
                        $post_Month = date("Y-m", strtotime($kk." +".$i." month"));
                        if(strtotime($kk." -".$i." month") < strtotime($dateBegin) && strtotime($kk." +".$i." month") > strtotime($dateEnd)){
                            $flag = false;
                        }
                        $supplyMonth = !empty($v[$pre_Month]) ? $pre_Month : $post_Month;
                        if(!empty($v[$supplyMonth])){ //获取到要补充的数据，则停止循环
                            $newRetArr[$k][$kk] = $v[$supplyMonth];                            
                            $flag = false;
                        }
                        $i++;
                    }   
                }//结束if 
            }
        }  //结束循环 
        return $newRetArr;
    }
    //}}}

}
