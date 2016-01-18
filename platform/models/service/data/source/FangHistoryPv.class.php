<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_Source_FangHistoryPv{
    protected $data;
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }

    /**
     * @codeCoverageIgnore
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    //{{{getOneDayByWhere
    /**
     * 获取上架的房源puid
     * @param $user_post_num_id
     * @return array
     */
    public function getOneDayByWhere($whereConds){
        /**
         * @var Service_Data_Source_UserPostList
         */
        $objServiceUserPostList = Gj_LayerProxy::getProxy('Service_Data_Source_UserPostList');
        $res = $objServiceUserPostList->getHouseListByWhere($whereConds, array(), 1, NULL);
        $puid_ids = array();
        $house_ids = array();
        if(!empty($res['data']) && function_exists(array_column)){
            $puid_ids = array_column($res['data'],'puid');
            $house_ids = array_column($res['data'],'house_id');
        } else if (!empty($res['data'])) {
            foreach ($res['data'] as $row){
                $puid_ids[] = $row['puid'];
                $house_ids[] = $row['house_id'];
            }
        }else{
            return $res;
        }
        $this->data['data'] = array(
            'puids'=>array_unique($puid_ids),
            'house_ids'=>array_unique($house_ids)
        );
        return $this->data;
    }//}}}
    //{{{getHousePvByAccountId
    /**
     * 根据account_id获取该用户昨日评论过的房源的昨日点击量、全部点击量
     * @param $account_id
     * @return mixed
     */
    public function getHousePvByAccountId($account_id){
        if(empty($account_id) || !is_numeric($account_id)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        //获取昨日评论房源的house_id、puid
        $whereConds = array(
            'account_id'=>$account_id,
            's_post_at'=>strtotime('yesterday')-1,
            'e_post_at'=>strtotime('today'),
        );
        $resYesterDayArr = $this->getOneDayByWhere($whereConds);
        if($resYesterDayArr['errorno'] || !isset($resYesterDayArr['data']['house_ids']) || !isset($resYesterDayArr['data']['puids'])){
            return $resYesterDayArr;
        }
        //获取昨日评论房源的昨天点击量
        $resYesterdayPv = $this->getYesterdayPvByHouseId($account_id, $resYesterDayArr['data']['house_ids']);
        if($resYesterdayPv['errorno']){
            return $resYesterdayPv;
        }
        //获取昨日评论房源的总点击量
        $resHouseTotalPv = $this->getHouseTotalPvByPuid($resYesterDayArr['data']['puids']);
        if($resHouseTotalPv['errorno']){
            return $resHouseTotalPv;
        }
        $this->data['data'] = array(
            'yesterdayPv' => isset($resYesterdayPv['data'][0]['account_pv']) ? $resYesterdayPv['data'][0]['account_pv'] : 0,
            'houseTotalPv' => isset($resHouseTotalPv['data'][0]['account_history_pv']) ? $resHouseTotalPv['data'][0]['account_history_pv'] : 0,
        );
        return $this->data;
    }//}}}
    //{{{getHousePvByAccountIdByCache
    /**
     * 缓存根据account_id获取该用户昨日评论过的房源的昨日点击量、全部点击量
     * @codeCoverageIgnore
     * @param $account_id
     * @return mixed
     */
    public function getHousePvByAccountIdByCache($account_id){
        $obj = Gj_LayerProxy::getProxy("Service_Data_Source_FangHistoryPv");
        return $obj->getHousePvByAccountId($account_id);
    }//}}}
    //{{{getYesterdayPvByHouseId
    /**
     * 获取昨天上架房源的昨日点击量
     * @param $account_id
     * @param $houseIdArr
     * @return mixed
     */
    public function getYesterdayPvByHouseId($account_id, $houseIdArr){
        /**
         * @var Service_Data_HouseReport_HouseSourceReport
         */
        $objServiceHouseSourceReport = Gj_LayerProxy::getProxy('Service_Data_HouseReport_HouseSourceReport');
        $whereConds = array(
            'account_id'=>$account_id,
            'house_id'=>$houseIdArr,
            'house_type'=>5,
            'ReportDate'=>strtotime('yesterday'),
        	'HouseBiddingMode'=>6,
        );
        try {
            $res = $objServiceHouseSourceReport->getReportByWhere($whereConds);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res['errorno']) {
            Gj_Log::warning($objServiceHouseSourceReport->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }
        return $res;
    }//}}}
    //{{{getHouseTotalPvByPuid
    /**
     * 获取昨日评论过房源的总点击量
     * @param $puid
     * @return mixed
     */
    protected  function getHouseTotalPvByPuid($puid){
        $arrFields = array();
        $arrConds = array();
        if(empty($puid)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        if(is_array($puid)){
        	$puids = implode(',',$puid);
        	$arrConds[] = "puid in ( $puids )";
        	$arrFields = array(
        			"SUM(history_count) AS account_history_pv",
        	);
        }else{
        	$arrConds['puid ='] = $puid;
        	$arrFields = array(
        			"history_count AS account_history_pv",
        	);
        }
        /**
         * @var Dao_Housepremier_HouseSourceList
         */
        $objDaoHouseSourceList = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
        try{
            $res = $objDaoHouseSourceList->selectSumPvByPuids($arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($objDaoHouseSourceList->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }
        $this->data['data'] = $res;
        return $this->data;
    }//}}}
}
