<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_RealHouse_GetShelvedRealHouseList
{
    protected $data;

    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    /**
     * @codeCoverageIgnore
     * @param $name
     * @param $args
     * @return mixed
     */
    //{{{__call
    public function __call($name, $args)
    {
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this, $name), $args);
        }
    }//}}}

    //{{{execute
    /**
     * 获取上架过的房源列表
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput)
    {
        //获取处于上架状态的房源数量
        if(isset($arrInput['filter_puid'])){
            $ret = $this->getHouseInfoByPuid($arrInput['puidList'], $arrInput['arrFields']);
            return $ret;
        }elseif (isset($arrInput['arrChangeRow'])) {
            $ret = $this->updateRealHouseAccumulativeCountByAccount($arrInput['arrChangeRow'], $arrInput['account_id'], $arrInput['bussiness_scope']);
            return $ret;
        } elseif (isset($arrInput['listing_status'])) {
            $shelvingCount = $this->getShelvedCountInfo($arrInput);
            return $shelvingCount;
        } elseif(isset($arrInput['update_user_post_list'])){
            $ret = $this->updateUserPostList($arrInput['puidList']);
            return $ret;
        }else {
            //获取本月上架过的的房源列表
            $realHousePuidList = $this->getShelvedRealHouseList($arrInput['account_id']);
            return $realHousePuidList;
        }
    }//}}}

    /* {{{ GetShelvedRealHouseList */
    /**
     * @brief 获得本月上架过的房源puid列表
     * @params Accountid
     * @returns
     */
    protected function getShelvedRealHouseList($account_id)
    {
        $arrConds = array('account_id' => $account_id, 's_post_at' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'e_post_at' => time());
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_UserPostList');
            $res = $objService->getHouseListByWhere($arrConds, null, null, null);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        //去除重复房源
        $ret = array();
        if (count($res['data'])) {
            foreach ($res['data'] as $value) {
                if (!in_array($value['puid'], $ret)) {
                    $ret[] = $value['puid'];
                }
            }
        }
        $this->data['data'] = $ret;
        return $this->data;
    }
    /* }}} *


    //110未上架  111展示中  112上架未展示
    /* {{{ getShelvedCountInfo */
    /**
     * @brief 获取上架中的房源数量
     * @params Accountid
     * @returns
     */
    protected function getShelvedCountInfo($arrInput)
    {
        $arrConds = array('listing_status=' => 1, 'premier_status in (111,112)');////Dao_Housepremier_HouseSourceList::STATUS_OK = 1;
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
            $res = $objService->getRealHouseCountByAccount($arrInput['account_id'], $arrConds);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        $this->data['data'] = 0;
        if (count($res['data'])) {
            foreach ($res['data'] as $value) {
                if ($value['type'] == 5) {
                    $this->data['data'] = $value['num'];
                }
            }
        }
        return $this->data;
    }
    /* }}} */

    /* {{{ getShowCountInfo */
    /**
     * @brief 获取展示中的房源数量
     * @params Accountid
     * @returns
     */
    protected function getShowCountInfo($account_id)
    {
        $arrConds = array('listing_status=' => 1, 'premier_status in (111)');////Dao_Housepremier_HouseSourceList::STATUS_OK = 1;
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
            $ShowInfo = $objService->getRealHouseCountByAccount($account_id, $arrConds);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        if (count($ShowInfo['data'])) {
            foreach ($ShowInfo['data'] as $value) {
                if ($value['type'] == 5)
                    return $value['num'];
            }
        }
        return 0;
    }
    /* }}} */

    /* {{{ getPostCountInfo */
    /**
     * @brief 获取已发布的真房源数量
     * @params Accountid
     * @returns
     */
    protected function getPostCountInfo($account_id)
    {
        $arrConds = array('premier_status in (110,111,112)');//Dao_Housepremier_HouseSourceList::STATUS_OK = 1;
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
            $postCountInfo = $objService->getRealHouseCountByAccount($account_id, $arrConds);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        if (count($postCountInfo)) {
            foreach ($postCountInfo['data'] as $value) {
                if ($value['type'] == 5)
                    return $value['num'];
            }
        }
        return 0;
    }
    /* }}} */

    /* {{{ getPostCountInfoByDay */
    /**
     * @brief 获取今日发布的真房源数量
     * @params Accountid
     * @returns
     */
    protected function getPostCountInfoByDay($account_id)
    {
        $arrConds = array('premier_status in (110,111,112)', 'post_at >' => strtotime(date('Y-m-d')), 'post_at <' => strtotime(date('Y-m-d', strtotime('+1 day'))));//Dao_Housepremier_HouseSourceList::STATUS_OK = 1;
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
            $postCountInfoByDay = $objService->getRealHouseCountByAccount($account_id, $arrConds);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        if (count($postCountInfoByDay['data'])) {
            foreach ($postCountInfoByDay['data'] as $value) {
                if ($value['type'] == 5)
                    return $value['num'];
            }
        }
        return 0;
    }
    /* }}} */

    /* {{{ getRealHouseAccumulativeCount */
    /**
     * @brief 获取本月累计上架的不同的真房源数量
     * @params Accountid
     * @returns
     */
    protected function getRealHouseAccumulativeCount($account_id)
    {
        //上架的真实房源数据每月只存一条，每次新上架房源只更新此数据；表中的时间为每月第一天0时
        $arrConds = array(
            'account_id' => $account_id,
            'bussiness_scope' => 4,
            'daynum' => mktime(0, 0, 0, date('m'), 1, date('Y'))
        );
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_UserRefreshNum');
            $postCount = $objService->getRefreshInfoByAccount($arrConds);
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        if (count($postCount['data'])) {
            if ($postCount['data']) {
                return $postCount['data']['num'];
            }
        }
        return 0;
    }
    /* }}} */

    //{{{updateRealHouseAccumulativeCountByAccount
    /**
     * @brief 根据account_id更新累计上架的不同的真房源数量
     * @param $arrInput
     * @return mixed
     */
    protected function updateRealHouseAccumulativeCountByAccount($arrChangeRow, $intAccountId, $bussiness_scope)
    {
        //先查询数量
        $ret = $this->getRealHouseAccumulativeCount($intAccountId);
        $objService = Gj_LayerProxy::getProxy('Service_Data_Source_UserRefreshNum');
        if ($ret) {
            //如果查询到记录，执行更新操作
            try {
                $res = $objService->updateNumByAccount($arrChangeRow, $intAccountId, $bussiness_scope);
                $this->data = $res;
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
                return $this->data;
            }
        } else {
            //没有查询到记录，执行插入操作
            try {
                $res = $objService->insertNumCountInfo(array('account_id' => $intAccountId, 'num' => $arrChangeRow['num'], 'bussiness_scope' => $bussiness_scope));
                $this->data = $res;
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
                return $this->data;
            }
        }
        return $this->data;
    }//}}}

    //{{{updateUserPostList
    /**
     * @brief 上架房源后更新user_post_list表
     * @param $arrInput
     * @return mixed
     */
    protected function updateUserPostList($puidList)
    {
        //现根据puidlist获取帖子信息
        $houseList = $this->getHouseInfoByPuid($puidList);
        foreach($houseList as $value) {
            try {
                $objService = Gj_LayerProxy::getProxy('Service_Data_Source_UserPostList');
                $res = $objService->insertHouseRecord($value[0]);
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
                return $this->data;
            }
        }
        return $this->data;
    }//}}}

    //{{{getHouseInfoBypUID
    /**
     * @brief 根据puid获取帖子信息
     * @params $arrPuidList
     * @return array
     */
    protected function getHouseInfoByPuid($arrPuidList, $arrFields = null)
    {
        $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
        if(!count($arrFields)) {
            $arrFields = array('puid', 'house_id', 'type', 'account_id');
        }
        $houseInfoList = array();
        foreach ($arrPuidList as $puid) {
            try {
                $ret = $objService->getPostListByConds(array('puid = ' => $puid), $arrFields);
                if (count($ret['data'])) {
                    $houseInfoList[] = $ret['data'];
                }
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
                return $this->data;
            }
        }
        return $houseInfoList;
    }


}