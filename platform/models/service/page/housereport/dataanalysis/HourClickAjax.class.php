<?php
/**
 * File Name:HourClickAjax.class.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
*/
class Service_Page_HouseReport_Dataanalysis_HourClickAjax
{

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }

    private $hourList = array('h0','h1','h2','h3','h4','h5','h6','h7','h8','h9','h10','h11','h12','h13','h14','h15','h16','h17','h18','h19','h20','h21','h22','h23');

    private $lineConfig = array(
        'market'=>array(
            'name'=>'市场需求占比',
            'color'=>'#dc4a32',
            'count'=>-1   #没有总数字段返回 count=-1
        ),
        'refresh'=>array(
            'name'=>'刷新占比',
            'color'=>'#1eb0ae',
            'count'=>-1  #没有总数字段返回 count=-1
        ),
        'click'=>array(
            'name'=>'点击占比',
            'color'=>'#9cbd4d',
            'count'=>-1  #没有总数字段返回 count=-1
        ),
    );
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this,$name),$args);
        }
    }

    protected function sumHourData($data){
        $sum = 0;
        foreach ($this->hourList as $hour) {
            if (isset($data[$hour])) {
                $sum += $data[$hour];
            }
        }
        $data['sum'] = $sum;
        return $data;
    }

    protected function getCustomerList($pid){
        $accountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        //得到pid下面的门店
        $orgInfo = $accountService->getChildTreeByOrgId( $pid,4,array(),1,null);
        if ($orgInfo['errorno']) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'该结构下面没有门店');
        }
        $data = $orgInfo['data']['list'];
        $customerList = array();
        foreach ($data as $item) {
            $customerList[] = $item['customer_id'];
        }
        return $customerList;
    }

    protected function getDefaultData($defaultHour = 0,$otherDefault = array()){
        $data = array();
        foreach ($this->hourList as $hours) {
            $data[0][$hours] = $defaultHour;
        }

        if (is_array($otherDefault) && !empty($otherDefault)) {
            foreach ($otherDefault as $key=>$val) {
                $data[0][$key] = $val;
            }
        }

        return $data;
    }
    /*{{{formartData 格式化数据
     *  输入数据
     *  array(28) {
     *     'h0' =>
     *     string(1) "0"
     *     'h1' =>
     *    'reportdate'=>unixtime
     *
     *  返回形式
     *   哪条线：{
     *       [
     *         name:'jinjia'
     *         data:[
     *           {F1：纵坐标，F2: 横坐标},
     *           {F1：纵坐标，F2: 横坐标},
     *           {F1：纵坐标，F2: 横坐标}
     *           ]
     *       ]
     *   },
     */
    protected function formartData( $data ,$params = null){
        $list = array();
        $result = array();
        $res = array();

        foreach ($this->hourList as $hours) {
            //纵坐标
            $list['F1'] = (!empty($data['sum'])?(round($data[$hours] / $data['sum'],4)):'0') * 100;
           // $list['F1'] .= '%';
            $list['F2'] = str_replace('h','',$hours);
            $result['data'][] = $list;
        }
        if (isset($params) && is_array($params)) {
            //图表需要的字段和值
            foreach ($params as $key=>$val) {
                $result[$key] = $val;
            }
        }
        $res[] = $result;
        return $res;
    }
    /*}}}*/


    public function execute($arrInput){
        $params['houseType'] = $arrInput['houseType'];
        $params['pid'] = $arrInput['pid'];
        $params['cityId'] = $arrInput['cityId'];
        $params['date'] = $arrInput['sdate'];
        $params['accountId'] = $arrInput['accountId'];
        $params['level'] = $arrInput['level'];
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_HouseAccountHours');

        try {

            $cityData = $dataService->hoursClickByCity( $params['cityId'],$params['houseType'],1, $params['date']);
            //如果是人的级别
            if (!empty($params['accountId']) && $params['accountId']!=-1){
                //个人结构刷新分时点击
                $refreshData = $dataService->hoursClickByAccountId($params['accountId'], $params['houseType'], 1,1,$params['date']);

                //个人结构点击分时点击
                $clickData = $dataService->hoursClickByAccountId($params['accountId'], $params['houseType'], 2,1,$params['date']);
            } else {
                $customerList = $this->getCustomerList($params['pid']);
                //组织结构刷新分时点击
                if (!empty($customerList)) {
                    $refreshData = $dataService->hoursClickByCustomerId($customerList, $params['houseType'], 1,1,$params['date']);
                } else {
                    $refreshData = $this->getDefaultData(0,array('report_date'=>$params['date']));
                }
                //组织结构点击分时点击
                if (!empty($customerList)) {
                    $clickData = $dataService->hoursClickByCustomerId($customerList, $params['houseType'], 2,1,$params['date']);
                } else {
                    $clickData = $this->getDefaultData(0,array('report_date'=>$params['date']));
                }
            }

            if ($cityData['errorno'] && $refreshData['errorno'] && $clickData['errorno']) {
                throw new Gj_Exception('2011','分时段返回数据有误');
            }
            //对数据求和
            $cityData['data'][0] = $this->sumHourData($cityData['data'][0]);
            $refreshDataInfo['data'][0] = $this->sumHourData($refreshData['data'][0]);
            $clickDataInfo['data'][0] = $this->sumHourData($clickData['data'][0]);
            //得到求和后的数据
            $cityData = $cityData['data'][0] ;
            $refreshData = $refreshDataInfo['data'][0];
            $clickData = $clickDataInfo['data'][0];
            //并且设置属性
            $cityData = $this->formartData($cityData,$this->lineConfig['market']);
            $refreshData = $this->formartData($refreshData,$this->lineConfig['refresh']);
            $clickData = $this->formartData($clickData,$this->lineConfig['click']);
            $totalData = array();
            $totalData = array_merge($refreshData,$clickData,$cityData);
            $this->data['data'] = $totalData;
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
       return $this->data;
    }
}
