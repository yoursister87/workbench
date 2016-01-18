<?php
/*
 * File Name:BalanceOperationUtil.class.php
 * Author:lukang
 * 
 * mail:lukang@ganji.com
 * description:订单处理
 */
class Util_HouseReport_BalanceOperationUtil
{
    /*
     *@codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    protected  function getBizTime($balanceList,$allowList){
        if (empty($balanceList)) {
            return array();
        }
        $nowTime = time();
        $tmpList = array();
        //循环精品订单
        foreach ($balanceList as $item) {
            if (in_array($item['Extension'],$allowList) &&
                $item['BeginAt'] > $nowTime && $item['Status'] == 1
            ) {
                $tmpList[$item['Extension']]['BeginAt'][] = $item['BeginAt'];
                $tmpList[$item['Extension']]['EndAt'][] = $item['EndAt'];
            }
        }
        return $tmpList;
    }


    /**
     *@codeCoverageIgnore
     */
    protected function getBalanceList($options){
        $gcrmObj = $this->getGcrmObj();
        //$tcObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_TradeCenterInterface');
        //$data = $tcObj->GetBalanceList($option);
        $data = $gcrmObj->getBalanceList($options);
        return $data;
        //return $tcObj->GetBalanceList($option);
    }


    /**
     * @codeCoverageIgnore
     */
    protected function getBalanceAssureList($options){
        $gcrmObj = $this->getGcrmObj();
        $options['ProductCode'] = HousingVars::PD_ASSURE_HOUSE;
        $data = $gcrmObj->getBalanceList($options);
        return $data;
    }
    /**
     * @codeCoverageIgnore
     */
    public function getGcrmObj($interface = 'BizService'){
        return new Gcrm($interface);
    }

    protected function seriesOrder($startTime,$endTime){
        array_multisort($startTime,SORT_ASC,$endTime,SORT_ASC);

        //全部过期返回空
        if (empty($endTime)) {
            return array();
        }

        if (count($startTime)>1) {//存在多条未过期数据
            //连续订单数
            $series = 0;
            foreach ($startTime as $k=>$val) {
                //如果是连续订单
                if (isset($startTime[$k+1]) && ($endTime[$k] +1) == $startTime[$k+1]) {

                    if (empty($sTime[$series+1])) {
                        $series++;
                        //取第一个最小时间
                        $sTime[$series] = $startTime[$k];
                        $eTime[$series] = $endTime[$k];
                    }

                    if ($eTime[$series] <= $endTime[$k+1]) {
                        $eTime[$series] = $endTime[$k+1];
                    }
                    continue;

                }

                if ($startTime[$k] <  $eTime[$series]){
                    if ($endTime[$k] >=  $eTime[$series]) {
                        $eTime[$series] = $endTime[$k];
                    }
                    continue;
                }


                $series++;
                $eTime[$series] = $endTime[$k];
                $sTime[$series] = $startTime[$k];

                //普通订单
                if (end($eTime) < $startTime[$k])  {
                    $sTime[count($sTime)+1] = $startTime[$k];
                    $eTime[count($eTime)+1] = $endTime[$k]; 
                }
            }
        } else {
            $sTime[0] = reset($startTime);
            $eTime[0] = reset($endTime); 
        }
        return array('beginAt'=>$sTime,'endAt'=>$eTime);
    }

    /*
     *  {{{getListHaveshallList 得到一个经纪人待生效的订单列表
     *  @params  array option
     *  'UserId'=>$accountInfo['UserId'],
     *      'CityId'=>12,
     *       'CategoryType'=>7,
     *      'Extension'=>3,
     *      'ProductCode'=>'pd_post_num'  #精品的productcode
     *  @params array businessScope 传入的类型必须确保当前没有生效的
     *
     * return  array(
     *        bizScope=>
     *       array(
     *           1=>array(beginAt=>array)
     *           2=>array(endAt=>array)
     *       )
     *  )
    */

    public function getListHaveshallList($option,$businessScope){
	    $params = array(
            'UserId'=>$option['UserId'],
            'CityId'=>$option['CityId'],
            'ProductCode'=>$option['ProductCode'],
            'CategoryType'=>7,
        );

        if (!is_array($businessScope) || empty($businessScope)) {
           return array();
        }

        $assureList = array();
        $premierList = array();
        $balanceAssureServiceData = array();

        foreach ($businessScope as $bizNum=>$bizInfo){
            //存在放心房   counttype=2
            if (isset($bizInfo['2'])) {
                $assureList[] = $bizNum;
            }
            if (isset($bizInfo['1'])) {
                $premierList[] = $bizNum;
            }
        }
        //调用精品的接口
        $balanceServiceData = $this->getBalanceList($params);
        //存在放心房端口
        if (!empty($assureList)) {
            $balanceAssureServiceData = $this->getBalanceAssureList($params);
        }

        $premierTimeList = array();

        $premierTimeList = $this->getBizTime($balanceServiceData,$premierList);
        $assureTimeList = $this->getBizTime($balanceAssureServiceData,$assureList);

        $result = array();

        foreach ($premierTimeList as $extension=>$time) {
            $result[$extension][1] = $this->seriesOrder($time['BeginAt'],$time['EndAt']);
        }
        foreach ($assureTimeList as $extension=>$time) {
            $result[$extension][2] = $this->seriesOrder($time['BeginAt'],$time['EndAt']);
        }

        return $result;
    }
}
