<?php
/*
 * File Name:OrgTree.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Page_HouseReport_Report_Default
{


    protected $levelList = array(
        Service_Data_HouseReport_ReportService::COMPANY_LEVEL=>'全部区域',
        Service_Data_HouseReport_ReportService::AREA_LEVEL=>'全部板块',
        Service_Data_HouseReport_ReportService::PLATE_LEVEL=>'全部门店',
        Service_Data_HouseReport_ReportService::OUTLET_LEVEL=>'全部经纪人',
    );  

    public function __construct(){
        $this->accountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $this->customerService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $this->util = new Util_CommonUrl();
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }

    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    private function getBackOutletUrl($orgId){
        $urlParams['c'] = 'report';
        $urlParams['pid'] = $orgId;
        $urlParams['level'] = 4;
        return  $this->util->createUrl($urlParams,null);
    }

    private function fomartDate(&$unixtime){
        $unixtime = date('Y-m-d',$unixtime);
    }
    //验证相应的id是不是这个公司的
    private  function validPower($params){
        $conds['company_id'] = $params['company_id'];
        $conds['id'] = $params['id'];
        $ret = $this->accountService->getOrgInfoByIdOrAccount($conds);
        if (empty($ret['data'])) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'此结构不存在');
        }
    }
    //{{{得到将要生效的列表
    /*
     *  return 
     *   [businessScope] =>
     *      array(2) {
     *       'BeginAt' =>
     *       array(1) {
     *       [0] => int(1441036800)
     *   }
     *   'EndAt' =>
     *     array(1) {
     *    [0] => int(1441900799)
     *    }
     * }
     */
    private function getShellBalanceList($accountInfo,$shellBusiness){
        $balanceUtil = Gj_LayerProxy::getProxy('Util_HouseReport_BalanceOperationUtil');
        $cityInfo = GeoNamespace::getCityByCityCode($accountInfo['CityId']);
        $option=array(
            'UserId'=>$accountInfo['UserId'],
            'CityId'=>$cityInfo['id'],
            'ProductCode'=>HousingVars::PD_PREMIER
        );
        $result = $balanceUtil->getListHaveshallList($option,$shellBusiness);
        foreach($result as $bizScope=>&$business) {
            //递归把日期格式为指定的日期
            array_walk_recursive($business, array($this,'fomartDate'));
            $business['bizText'] = HousingVars::$bizTxt[$bizScope];
        }

        return $result;
    }
    /*}}}*/
    /*{{{获得用户的端口类型列表*/
    private function userBusinessScopeList($accountInfo){
        $businessService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
        $ret = $businessService->getBusinessInfoByAccountIds($accountInfo['AccountId']);
        if ($ret['errorno']) {
            throw new Gj_Exception($ret['errorno'],$ret['errormsg']);
        }

        $dataList = $ret['data'];

        $now = strtotime('today');
        //待生效订单
        $shellDuration = array();
        //失效订单
        $lostBusiness = array();
        //生效订单
        $durationBusiness = array();
        if (empty($dataList))
            return array();

        $businessList = array();
        foreach ($dataList as &$item) {
            //体验端口屏蔽
            if ($item['BussinessScope'] == 10){
                continue;
            }

            //生效中的订单
            if ($item['InDuration'] == 1) {
                $item['startDate'] = date('Y-m-d',$item['InDurationBeginTime']);
                $item['endDate'] = date('Y-m-d',$item['InDurationEndTime']);
                $durationBusiness[$item['BussinessScope']][$item['CountType']] = $item;
                $durationBusiness[$item['BussinessScope']]['bizText'] = HousingVars::$bizTxt[$item['BussinessScope']];
            }
            //失效的订单
            if ($item['InDuration'] == 0 && $item['NextDurationBeginTime']<$now) {
                $item['startDate'] = date('Y-m-d',$item['MinBeginTime']);
                $item['endDate'] = date('Y-m-d',$item['MaxEndTime']);
                $lostBusiness[$item['BussinessScope']][$item['CountType']] = $item;
                $lostBusiness[$item['BussinessScope']]['bizText'] = HousingVars::$bizTxt[$item['BussinessScope']];
            }
            //待生效订单
            if ($item['NextDurationBeginTime'] > $now) {
                $shellDuration[$item['BussinessScope']][$item['CountType']] = true;
            }
        }

        return array(
            //生效中的
            'duration'=>$durationBusiness,
            //已失效的
            'lost'=>$lostBusiness,
            //待生效的
            'shell'=>$shellDuration
        );
    }
    /*}}}*/
    private function getOrgTree($params){
        $father = $this->accountService->getTreeByOrgId($params['id']);
        if ($father['errorno'])
            throw new Gj_Exception($father['errorno'],$father['errormsg']);
        foreach ($father['data'] as $level=>$item) {
            if ($level <= $params['userLevel']) {
                $activeList = $father['data'][$level]['activeList'];
                $father['data'][$level] = array();
                $father['data'][$level]['activeList'] = $activeList;
            }
        }

        return $father['data'];
    }

    protected function inDurationBizInput($params){
        $obj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
        //公司级别账号不用 companyid 去做查询的原因 是会把未分配的门店查询进去
        $customerIds = $pageDataSerivce->getAllOutlet($params['id']);
        //获得某个公司生效中的精品端口类型
        $ret = $obj->getOpenBizByCompanyId($params['company_id'],$customerIds,1,true);

        if ($ret['errorno']){
            throw new Gj_Exception($father['errorno'],$father['errormsg']);
        }
        $data = $ret['data'];
        $result = array();
        foreach ($data as $item) {
            $biz = HousingVars::$bizTxt[$item['BusinessScope']];
            if ($biz) {
                $result[$item['BusinessScope']] = $biz;
            }
        }
        $result[0] = '全部';
        ksort($result);

        return $result;
    }
    protected function getNavUrl($orgTree,$params){
        $pid = HttpNamespace::getGET('pid',$params['accountOrgId']);
        $arr = array('?c=report'=>'使用情况统计');
        ksort($orgTree);
        if (isset($pid)) {
            foreach ($orgTree as $level => $info) {

                if ($level == Service_Data_HouseReport_ReportService::AREA_LEVEL) {
                    if ($params['userLevel'] <= Service_Data_HouseReport_ReportService::AREA_LEVEL) {
                        $arr["?c=report&pid={$info['activeList']['id']}&level=".Service_Data_HouseReport_ReportService::AREA_LEVEL] =
                            array('content'=>$info['activeList']['title'],'show'=>true);
                    } else {
                        $arr[] = array('content'=>$info['activeList']['title'],'show'=>false);
                    }
                }

                if ($level == Service_Data_HouseReport_ReportService::PLATE_LEVEL) {
                    if ($params['userLevel'] <= Service_Data_HouseReport_ReportService::PLATE_LEVEL) {
                        $arr["?c=report&pid={$info['activeList']['id']}&level=".Service_Data_HouseReport_ReportService::PLATE_LEVEL] =
                            array('content'=>$info['activeList']['title'],'show'=>true);
                    } else {
                        $arr[] = array('content'=>$info['activeList']['title'],'show'=>false);
                    }
                }

                if ($level == Service_Data_HouseReport_ReportService::OUTLET_LEVEL) {
                    if ($params['userLevel'] <= Service_Data_HouseReport_ReportService::PLATE_LEVEL) {
                        $arr["?c=report&pid={$info['activeList']['id']}&level=".Service_Data_HouseReport_ReportService::OUTLET_LEVEL] =
                            array('content'=>$info['activeList']['title'],'show'=>true);
                    } else {
                        $arr[] = array('content'=>$info['activeList']['title'],'show'=>false);
                    }
                }
            }
        } else {
            array_push($arr,$this->levelList[$params['realLevel']]);
        }
        return $arr;
    }


    protected function getChildTree($result,$params){
        $resultData = array();
        if ($result['errorno']) {
            $data['errorno'] = Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM;
            $data['errormsg'] = '此结构下面没有数据';
        } else {
            if (empty($params['id'])) {
                $listParams['level'] = $params['userLevel'] + 1;
            } else {
                $listParams['pid'] = $params['id'];
                $listParams['level'] = $params['slevel'];
            }
            $listParams['company_id'] = $params['company_id'];
            $res = $this->accountService->getOrgInfoListByPid($listParams,
                array("id","pid","company_id","customer_id","level",'title'), 1, null);
            if ($res['errorno']) {
                $data['errorno'] = Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM;
                $data['errormsg'] = '此结构下面没有数据';
            } else {
                $data['data'] =  $result;
                $data['data'][$listParams['level']] =  $res['data'];
            }
        }
        return $data;
    }
    //{{{ getOrgSelect  获得select框中的url结构关系
    protected function getOrgSelect($father,$params){
        $res = $this->getChildTree($father,$params);
        $urlParams['c'] = 'report';
        if (!empty($res['data'])) {
            foreach ($res['data'] as $key=>&$levelItem) {
                foreach ($levelItem as &$item){
                    $urlParams['pid'] = $item['id'];
                    if ($params['realLevel'] == 4 || !empty($params['selectOption'])) {
                    	$urlParams['selectOption'] = 1;
                    }
                    $urlParams['level'] = $item['level'];
                    $item['url'] = $this->util->createUrl($urlParams,null);
                    //$item['url'] = "&level={$item['level']}&pid={$item['id']}";
                }
            }
            $data = $res['data'];
        } else {
            throw new Gj_Exception($res['errorno'],$res['errormsg']);
        }
        return $data;
    }
    //}}}
    //{{{getUserPage 获得账号信息
    protected function getUserPage($params){
        $accountInfo = array();
        $accountId = $params['accountId'];
        if (!empty($accountId) && is_numeric($accountId)) {
            $accountInfo = $this->customerService->getAccountInfoById($accountId);
            if ($accountInfo['errorno']) {
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"账号信息错误");
            }
            //取得一个经纪人信息
            $accountInfo = reset($accountInfo['data']);
            $where['customer_id'] = $accountInfo['CustomerId'];
            $orgInfo = $this->accountService->getOrgInfoListByPid($where);
            //此账号不属于此公司
            if (empty($orgInfo['data'])) {
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'此结构不存在');
            }
            //获得这个门店的house_manger_account  id
            $accountInfo['orgId'] = $orgInfo['data'][0]['id'];
            $businessList = $this->userBusinessScopeList($accountInfo);
            $shellList = $this->getShellBalanceList($accountInfo,$businessList['shell']);
            //回到门店url
            $accountInfo['backoutletUrl'] = $this->getBackOutletUrl($accountInfo['orgId']);
            //生效订单
            $accountInfo['durationOrder'] = $businessList['duration'];
            //失效订单
            $accountInfo['lostOrder'] = $businessList['lost'];
            //将要生效订单
            $accountInfo['shellOrder'] = $shellList;
            $accountInfo['accountid_show'] =  "<a target='_blank' href='http://{$params['domain']}.ganji.com/fang_{$accountId}/'>{$accountId}</a>";
            $imageServer = UploadConfig::getImageServer();
            if (empty($accountInfo['Picture'])) {
                $accountInfo['show_picture'] = 'http://sta.ganjistatic1.com/src/image/v5/house/user_default.png';
            } else {
                $accountInfo['show_picture'] = $imageServer.'/'.$accountInfo['Picture'];
            }
            if ($balanceRes['errorno']) {
                throw new Gj_Exception($balanceRes['errorno'],$balanceRes['errormsg']);
            }
        }

        return $accountInfo;
    }
    //}}}

    public function execute($arrInput){
        $params['id'] =  HttpNamespace::getGET('pid',null);
        $params['selectOption'] = $arrInput['selectOption'];
        $params['realLevel'] = $arrInput['level'];
        //获得子等级
        if (!isset($params['id'])) {
            $params['id'] = $arrInput['userId'];
            //初始化为用户当前的level
            $params['level'] = $arrInput['userLevel'];
        } else {
            $params['level'] = (int)$arrInput['level'];
        }
        $params['slevel'] = $params['level'] + 1;
        $params['userLevel'] = (int)$arrInput['userLevel'];
        $params['company_id'] = $arrInput['companyId'];
        $params['accountId'] = $arrInput['accountId'];
        $params['domain'] = $arrInput['domain'];


        try{
            $this->validPower($params);
            //var_dump($params);
            //如果有账号信息的到账号信息
            $accountInfo = $this->getUserPage($params);

            if (isset($accountInfo['orgId'])) {
                //把org_id放入
                $params['accountOrgId'] = $accountInfo['orgId'];
                $params['id'] = $accountInfo['orgId'];
                //手动指定下一级level
                $params['slevel'] = 5;
            }


            //得到组织数
            $fatherTree = $this->getOrgTree($params);
            //得到select下拉框数据
            $selectInfo = $this->getOrgSelect($fatherTree,$params);
            //得到导航的url array数据
            $navUrl = $this->getNavUrl($fatherTree,$params);
            //bizInput 框中的生效的产品类型
            //全部经纪人级别 才有 bizInput框
            $bizInput = array();
            if ($params['realLevel'] == 4){
                $bizInput = $this->inDurationBizInput($params);
            }

            $this->data['data'] = array(
                'selectInfo' =>$selectInfo,
                'navUrl'     =>$navUrl,
                'accountInfo'=>$accountInfo,
                'bizInput'   =>$bizInput,
            );
        }catch(Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
}
