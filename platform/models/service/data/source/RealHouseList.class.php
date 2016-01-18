<?php
/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   wuyirui <wuyirui@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_RealHouseList{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    protected $arrFields = array('house_id','puid','type','account_id','district_id','district_name','xiaoqu_id','xiaoqu_name');

    protected $realHouseConds = array('`type` = '=>5, 'premier_status in (110,111,112)', 'listing_status in (11,23)');

    /*
     * 获取二手房真房源
     * */
    public function getRealHouseList($cityId, $accountId, $limit = 20){
        $objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
        $arrConds = $this->realHouseConds;
        if(isset($cityId) && $cityId >= 0)
            $arrConds['city = '] = intval($cityId);
        if(!empty($accountId))
            $arrConds['account_id = '] = intval($accountId);
        $appends = ' order by delete_time desc';
        if(!empty($limit))
            $appends .= ' limit ' . $limit;
        try{
            $this->arrRet['data'] = $objDaoSourceList->selectAllList($this->arrFields, $arrConds, $appends);
        }catch(Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
		return $this->arrRet;
    }

    /*
     * 获取成交排行榜
     * */
    public function getAccountList($cityId,$limit = 5) {
        $objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
        $arrFields = array('account_id', 'count(1) as num');
        $arrConds = $this->realHouseConds;
        if(isset($cityId) && $cityId >= 0)
            $arrConds['city = '] = intval($cityId);
        $appends = ' group by account_id order by num desc';
        if(!empty($limit))
            $appends .= ' limit ' . $limit;
        try{
            $this->arrRet['data'] = $objDaoSourceList->selectAllList($arrFields, $arrConds, $appends);
        }catch(Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
		return $this->arrRet;
    }

    //根据account_id获取销售量
    public function getSaleNumByAccountId($accountId) {
        if(empty($accountId))
            return $this->arrRet;
        $objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
        $arrFields = array('account_id', 'count(1) as num');
        $arrConds = $this->realHouseConds;
        $arrConds['account_id ='] = $accountId;
        $appends = ' group by account_id';
        try{
            $this->arrRet['data'] = $objDaoSourceList->selectAllList($arrFields, $arrConds, $appends);
        }catch(Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }
}