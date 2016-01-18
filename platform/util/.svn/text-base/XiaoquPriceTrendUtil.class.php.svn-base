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
 class Util_XiaoquPriceTrendUtil
{
    /**generateMonthTimePeriod生成月份时间段{{{*/
    /**
     * @params string $dateEnd   介绍年月
     * @return array 生成以结束时间向前推算的连续12个月为维度的空数据
     */
    protected function generateMonthTimePeriod($dateEnd){
        $period = array();
        $i = 0;
        while ($i <12) {
            $dynamicMonth = date('Y-m', strtotime($dateEnd . " - " . $i . 'month'));
            $period[$dynamicMonth] = array();
            ++$i;
        }
        return $period;
    }//}}}
    public function patchEmptyData($priceList, $dateList){
        if (empty($priceList) || !is_array($priceList)) {
            return array();
        }
        foreach ($dateList as $month => $item) {
            if (!isset($priceList[$month])) {
                $i = 1;
                $flag = true;
                while ($flag && $i < 12) {
                    $preMonth = date('Y-m', strtotime($month . ' -' . $i . ' month'));
                    if (isset($priceList[$preMonth])) {
                        $flag = false;
                        $dateList[$month] = $priceList[$preMonth];
                        continue;
                    }
                    $postMonth = date('Y-m', strtotime($month . ' +' . $i . ' month'));
                    if (isset($priceList[$postMonth])) {
                        $flag = false;
                        $dateList[$month] = $priceList[$postMonth];
                    }
                    ++ $i;
                }
            } else {
                $dateList[$month] = $priceList[$month];
            }
        }
        return $dateList;
    }
    protected function filerPriceListByMonth($priceList){
        if (empty($priceList) || !is_array($priceList)) {
            return array();
        }
        $retPriceList = array();
        $nowKey = date('Y-m');
        $nowDay = date('d');
        foreach ($priceList as $key => $price) {
            $dateKey = date('Y-m', $price['record_time']);
            $realDateKey = date('Y-m', strtotime($dateKey . ' -1 month'));
            $day = date('d', $price['record_time']);
            if (15 == $day) {
                if ($dateKey == $nowKey && $nowDay >= 15){
                    $retPriceList[$dateKey] = $price; 
                } else {
                    continue;
                }
            } else {
                $retPriceList[$realDateKey] = $price;
            }
        }
        asort($retPriceList);
        return $retPriceList;
    }
    /**dataPacketByXiaoquId根据小区id分组打包数据{{{*/
    /**
     * @param $itemlist array()
     * @param $majorType  类别 1 3 5
     * @return array()
     */
    protected function dataPacketByXiaoquId($itemList, $majorType){
        $priceListGroup = array();
        if (empty($itemList) || !is_array($itemList)) {
            return $priceListGroup;
        }
        foreach ($itemList as $item) {
            if (in_array($item['xiaoqu_id'], array(-1, -999))) {
                $xiaoquId = -999;
            } else {
                $xiaoquId = $item['xiaoqu_id'];
            }
            if ( in_array($majorType, array(1, 3)) && isset($item['huxing']) && $item['huxing'] > 0) {
                $priceListGroup[$xiaoquId][$item['huxing']][$item['id']] = $item;
            } else {
                $priceListGroup[$xiaoquId][$item['id']] = $item;
            }
        }
        return $priceListGroup;
    }//}}}
    /**subPriceList根据时间截取列表{{{*/
    /**
     * @param $priceList array()
     * @param $dateBegin int 时间戳
     * @param $dateEnd int 时间戳
     */
    protected function subPriceList($priceList, $dateBegin, $dateEnd){
        $subList = array();
        if (!empty($priceList) && is_array($priceList)) {
            foreach ($priceList as $dateKey => $item) {
                if ($dateBegin <= $dateKey && $dateEnd >= $dateKey) {
                    $subList[$dateKey] = $item;
                }
            }
        }
        return $subList;
    }//}}}
    /**formataPriceListDetail{{{*/
    protected function formataPriceListDetail($priceList, $dateList, $dateBegin, $dateEnd){
        $priceList = $this->filerPriceListByMonth($priceList);
        $priceList = $this->patchEmptyData($priceList, $dateList);
        $priceList = $this->subPriceList($priceList, $dateBegin, $dateEnd);
        return $priceList;
    }//}}}
    /**formataPriceList{{{*/
    public function formataPriceList($itemList, $dateBegin, $dateEnd, $majorType){
        $newPriceList = array();
        //按照xiaoquId分组
        $priceList = $this->dataPacketByXiaoquId($itemList, $majorType);
        if (!empty($priceList) && is_array($priceList)) {
            $dateList = $this->generateMonthTimePeriod($dateEnd);
            foreach ($priceList as $xiaoquId => $list) {
                if ($majorType == 1 || $majorType == 3) {
                    foreach($list as $huxing => $price) {
                        $newPriceList[$xiaoquId][$huxing] = $this->formataPriceListDetail($price, $dateList, $dateBegin, $dateEnd);
                    }
                } else {
                    $newPriceList[$xiaoquId] = $this->formataPriceListDetail($list, $dateList, $dateBegin, $dateEnd);

                }
            }
        }
        return $newPriceList;
    }//}}}
    /**
     * @codeCoverageIgnore
     */
    public function __call($func, $args){
        if(method_exists($this, $func)){
            switch (count($args)){
                case 0:
                    return $this->$func();
                    break;
                case 1:
                    return $this->$func($args[0]);
                    break;
                case 2:
                    return $this->$func($args[0], $args[1]);
                    break;
                case 3:
                    return $this->$func($args[0], $args[1], $args[2]);
                    break;
                case 4:
                    return $this->$func($args[0], $args[1], $args[2], $args[3]);
                    break;
                default:
                    return $this->$func($args);
                    break;
            }

        }
    }
}
