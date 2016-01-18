<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_HouseReport_Dataanalysis_EffectAnaDataAjax
{
    //{{{ countTypeList 商业产品类型配置
    const countTypePre = 1; // 精品
    const countTypeBid = 9;// 竞价
    const countTypeAss = 4;// 放心房
    const countTypeSticky = 7; // 自助置顶
    const countTypeZhiNeng = 8;// 智能推广
	protected $countTypeList = array(
		self::countTypePre	=> array(
            'name' => '精品',
            'color' => '#9d4a8e'
            ),
        self::countTypeBid  => array(
            'name' => '竞价',
            'color' => '#22c78b',
            ),
		self::countTypeAss  => array(
            'name' => '放心房',
            'color' => '#5580c8',
            ),
		self::countTypeSticky   => array(
            'name' => '置顶',
            'color' => '#b37334',
            ),
		self::countTypeZhiNeng  => array(
            'name' => '智能推广',
            'color' => '#9cbd4d'	 
            )
	);
    //}}}
    /* {{{ __construct*/
    public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] 	= ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    /* }}} */
    /* {{{ public execute*/
    /**
     * @brief pageService调用入口
     *
     * @param $arrInput array(
     *          'orgId' => string '0' 
     *          'sDate' => string '2015-01-19' 
     *          'eDate' => string '2015-01-26' 
     *          'houseType' => array 
     *          'widgetType' => string 'pv_per_house_widget' 
     *          'accountId' => string '' 
     *      )
     *
     * @returns  数据 
     */
    public function execute($arrInput){
        $dataFromDb = $this->getRawData($arrInput);
        $retArr = $this->genWidgetData($arrInput, $dataFromDb);
        $this->data['data'] = $retArr;
        return $this->data;
    }
    /* }}} */
    /* {{{ protected getRawData*/
    /**
     * @brief 根据查询条件从数据库获取数据
     *
     * @param $arrInput array(
     *          'orgIds' => string '0' 
     *          'sDate' => string '2015-01-19' 
     *          'eDate' => string '2015-01-26' 
     *          'houseType' => array 
     *          'widgetType' => string 'pv_per_house_widget' 
     *          'accountId' => string '' 
     *      )
     *
     * @returns   
     */
    protected function getRawData($arrInput){
        $dataAnaDs = Gj_LayerProxy::getProxy("Service_Data_HouseReport_DataAnalysis");
        $queryConds = array();
        $result = array();
        $queryConds['orgId'] = $this->getInfoFromArr($arrInput, "orgId", -1);
        $queryConds['sDate'] = $this->getInfoFromArr($arrInput, "sDate", "yesterday -7 days");
        $queryConds['eDate'] = $this->getInfoFromArr($arrInput, "eDate", "yesterday -1 days");
        $queryConds['houseType'] = $this->getInfoFromArr($arrInput, "houseType", array(0));
        $queryConds['countType'] = $this->getCountTypeListByWidgetType($this->getInfoFromArr($arrInput, "widgetType", 0));
        if (isset($arrInput['accountId']) && $arrInput['accountId'] > 0) {
            $queryConds['accountId'] = $arrInput['accountId'];
            $ret = $dataAnaDs->getAccountData($queryConds);
        } else {
            $ret = $dataAnaDs->getOrgData($queryConds);
        }
        if (!empty($ret['data'])) {
            foreach ($ret['data'] as $countData) {
                $countType = $this->getInfoFromArr($countData, 'count_type', -1);
                $date = $this->getInfoFromArr($countData, 'report_date', 0);
                $clickPrice = $this->getInfoFromArr($countData, 'click_price', 0);
                $click = $this->getInfoFromArr($countData, 'click_count', 0);
                $houseCount = $this->getInfoFromArr($countData, 'house_count', 0);
                $tmpArr = $this->getInfoFromArr($result, $countType, array('count' => 0));
                $tmpArr['count'] += $click;
                if ($this->getInfoFromArr($arrInput, 'widgetType', '') == 'pv_per_house_widget') {
                    $avgVal = $this->getAvg($click, $houseCount);
                } else {
                    $avgVal = $this->getAvg($clickPrice, $click);
                }
                $tmpArr[$date] = $avgVal;
                $result[$countType] = $tmpArr;
            }
        }
        return $result;
    }
    /* }}} */
    /* {{{ protected genWidgetData*/
    /**
     * @brief 格式化数据按照查询条件拼装展示数组
     *
     * @param $arrInput
     * @param $dataFromDb
     *
     * @returns   
     */
    protected function genWidgetData($arrInput, $dataFromDb){
        $wantTypeList = $this->getCountTypeListByWidgetType($arrInput['widgetType']);
        $ret = array();
        $dateCount = (strtotime($arrInput['eDate']) - strtotime($arrInput['sDate'])) / 86400;
        foreach($wantTypeList as $countType){
            $tmp = $this->countTypeList[$countType];
            $countTypeData = $this->getInfoFromArr($dataFromDb, $countType, array());
            $tmp['count'] = $this->getInfoFromArr($countTypeData, 'count', 0);//$countType);
            $tmp['data'] = array();
            $dateIndex = 0;
            for ($b = strtotime($arrInput['sDate']); $b <=strtotime($arrInput['eDate']); $b += 86400) {
                $tmp['data'][] = array(
                    'F1' => $this->getInfoFromArr($countTypeData, $b, 0),//array_rand($arrInput['houseType'])*$countType),
                    'F2' => ($dateIndex % 2) == 1 && $dateCount > 20 ? date("j", $b) : date("n/j", $b) 
                );
                $dateIndex++;
            }
            $ret[] = $tmp;
        }
        return $ret;
    }
    /*}}}*/
    /* {{{ protected getAvg*/
    /**
     * @brief 计算除法
     *
     * @param $consume 总消耗
     * @param $count 数量
     *
     * @returns   
     */
    protected function getAvg($consume, $count){
        $ret = 0;
        if (empty($consume) || empty($count)) {
            return $ret;
        }
        //得数保留2位
        return round($consume/$count,2);
    }
    /*}}}*/
    /* {{{ protected getCountTypeListByWidgetType*/
    /**
     * @brief 获取图表对应的商品类型列表
     *
     * @param $widgetType
     *
     */
    protected function getCountTypeListByWidgetType($widgetType){
        $typeList = array(
            'pv_per_house_widget' => array_keys($this->countTypeList),
            'price_per_pv_widget' => array(self::countTypeBid, self::countTypeSticky, self::countTypeZhiNeng)
        );
        return isset($typeList[$widgetType]) ? $typeList[$widgetType] : $typeList['pv_per_house_widget'];
    }
    /* }}} */
    /* {{{ protected getInfoFromArr*/
    /**
     * @brief 从数组中获取值
     *
     * @param $arr 来源数据
     * @param $key 取值的下标
     * @param $default 默认值
     *
     */
    protected function getInfoFromArr($arr, $key, $default = 0){
        return !empty($arr) && isset($arr[$key]) ? $arr[$key] : $default;
    }
    /*}}}*/
}
