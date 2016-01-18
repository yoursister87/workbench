<?php
/*
 * File Name:AccountAjax.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
class Service_Page_HouseReport_Report_AccountAjax
{
   
    protected $sortIndexName = 'reportDate';

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }

    /*{{{groupAccountData 组织结构数据*/
    /** 
     * 
     * @param unknown $params    
     *  数据格式
     *  array(
     *       'data'=>array(
     *          'title'=>'日期',
     *          'title_list'=> array(
     *              orgId=>array('name'=>'name','href'=>'href')
     *              orgId=>array('name'=>'name','href'=>'href')
     *          ),
     *          'count'=>条数
     *       )
     *      'accountIds' => array()
     *       
     */
    protected function groupAccountData($params){
        $res = array();
        $timeArr = array();
        $date = $params['date'];
        $stime = strtotime($date['sDate']);
        $etime = strtotime($date['eDate']);
        if ($stime === false || $etime === false) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"账号详情时间有误");
        }
        $res['data']['title'] = '日期';
        for ($i = $stime;$i <= $etime;$i += 3600*24) {
            $timeArr[] = date('Y-m-d',$i);
        }
        foreach ($timeArr as $time) {
            $res['data']['title_list'][$time] = array('name'=>$time);
            //建立排序的索引   因为数据库里面查询出的是时间戳 这里就需要转换
            $res[$this->sortIndexName][] = strtotime($time);
        }

        return $res;
    }
    /*}}}*/
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this,$name),$args);
        }
    }

    /**
	 * {{{execute
	 */
    public function execute($arrInput){
        $groupDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupAccountData');
        $checkData = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
        $params  = array();
        try{
            if (isset($arrInput['date'])){
                //设置时间
                $params['date'] = $checkData->setDate($arrInput['date']);
            }

            if (isset($arrInput['houseType'])) {
                //设置房源
                $params['houseType'] = $checkData->setHouseType($arrInput['houseType']);;
            }
            //设置端口类别
            $params['businessScope']  = $checkData->setBusinessScope($arrInput['businessScope']);
            $params['houseType'] = $pageDataSerivce->setHouseType($params);

            $params['product']  = $arrInput['product'];
            $params['companyId'] = $arrInput['companyId'];
            $params['customerId'] = $arrInput['customerId'];
            //当前页
            $params['page'] = $arrInput['page'];
            $params['userId'] = $arrInput['userId'];
            $params['userLevel'] = $arrInput['userLevel'];

            //设置关键字搜索
            $params['keyword'] = addslashes(trim($arrInput['kword']));
            $params['stype'] = $arrInput['stype'];
            //数据类型
            $params['dtype'] = $arrInput['dtype'];
            $params['accountId'] = $arrInput['accountId'];

        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }


        if (!$this->data['errorno']) {
            $tags = array();
            //组合商品类型 和数据类型
            $productGroup = array_merge($params['product'],$params['dtype']);
            foreach ($productGroup as $product) {
                if (isset(Service_Data_HouseReport_GroupData::$productStr2Indx[$product])) {
                    $tags[] = Service_Data_HouseReport_GroupData::$productStr2Indx[$product];    
                }
            }

            try{
                $accountData = $this->groupAccountData($params);
                $res = $groupService->groupAjaxData($tags,$params);
                //数据匹配
                $res = $groupDataService->matchData($accountData,$res,$this->sortIndexName);
                $this->data['data']['dataList'] = $res;
                $this->data['data']['titleList'] = $accountData['data'];
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;

    }	
    /*}}}*/
}
