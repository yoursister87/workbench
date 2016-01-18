<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_FangByAccount{

    /**
     * @var Dao_Housepremier_HouseSourceList
     */
    private $objDaoHouseList;
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * @codeCoverageIgnore
     */
    public function __construct(){
        $this->objDaoHouseList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
    }

    /**
     * @brief 通过account_id，获取对应房源类型的数量
     * @param $intAccountId
     * @param null $arrHouseType
     */
    public function getCountHouseTypeByAccount($intAccountId){
        try {
            $arrFields = array('type','count(1) as num');
            $arrConds = array('account_id=' =>$intAccountId ,'listing_status=' =>Dao_Housepremier_HouseSourceList::STATUS_OK ,'premier_status in (2,102)');
            $arrHouseTypeNum = $this->objDaoHouseList->selectGroupbyHouseType($arrFields,$arrConds);
            if($arrHouseTypeNum ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseList->getLastSQL());
            }
            $this->arrRet['data'] = $arrHouseTypeNum;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;

    }

    /**
     * @brief 通过account_id，获取指定类型的真房源数量（具体类型由传入的$InarrConds决定）
     * @param $intAccountId
     * @param null $arrHouseType
     */
    public function getRealHouseCountByAccount($intAccountId, $InarrConds = null){
        try {
            $arrFields = array('type', 'count(1) as num');
            $arrConds = array('account_id=' => $intAccountId);
            if(count($InarrConds)) {
                $arrConds = array_merge($arrConds, $InarrConds);
            }
            $RealHouseInfo = $this->objDaoHouseList->selectGroupbyHouseType($arrFields,$arrConds);
            if($RealHouseInfo ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseList->getLastSQL());
            }
            $this->arrRet['data'] = $RealHouseInfo;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }





    /**
     * @brief 通过account_id ,获取小区列表
     * @param $intAccountid
     * @return array
     */
    public function getXiaoQuListByAccount($intAccountid,$intType=null,$arrFields=array()){
        try {
            $fields = array('xiaoqu_id','xiaoqu_name');
        	if (count($arrFields)) {
        		$fields = $arrFields;
        	}
            $conds['account_id='] = $intAccountid;
            //$conds['listing_status='] = Dao_Housepremier_HouseSourceList::STATUS_OK;
            $conds['xiaoqu_id!='] = 0;
            if($intType != null){
                $conds['type='] =$intType;
            }
            $arrXiaoquList = $this->objDaoHouseList->selectGroupbyXiaoquId($fields,$conds);
            if($arrXiaoquList ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseList->getLastSQL());
            }
            $xiaoquOption = array();
            if (!empty($arrXiaoquList) && count($arrFields)==0){
                foreach ( $arrXiaoquList as $val ) {
                    if ( $val['xiaoqu_name'] && $val['xiaoqu_id'] ) {
                        $xiaoquOption[$val['xiaoqu_id']] = $val['xiaoqu_name'];
                    }
                }
                $this->arrRet['data'] = $xiaoquOption;
            }else{
            	$this->arrRet['data'] = $arrXiaoquList;
            }
            
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }

/*
    //根据公司id获取已发布房源用户自定义编号（内部编号）
    public function getUserCodeByNothing($fields,$conds){
        try{
            //$conds['listing_status='] = Dao_Housepremier_HouseSourceList::STATUS_OK;
            //这里account_id为空，先试试看能不能取出来
            $arrUserCodeList = $this->objDaoHouseList->selectAllInfo($fields,$conds);
            if($arrUserCodeList ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseList->getLastSQL());
            }
            $this->arrRet['data'] = $arrUserCodeList;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }
*/
    /**
     * @brief 通过$arrConds获取已发布的房源列表
     * @param $arrConds
     */
    public function getPostListByConds($arrConds, $arrFields = null){
        try {
            if(count($arrFields)) {
                $fields = $arrFields;
            } else {
                $fields = array('id', 'house_id', 'puid', 'type', 'account_id', 'user_code', 'city', 'title', 'history_count', 'yesterday_count', 'district_id', 'district_name', 'street_id', 'street_name', 'xiaoqu_id', 'xiaoqu_name', 'pinyin', 'post_at', 'premier_status', 'bid_status', 'listing_status', 'is_similar', 'audit_time', 'auditor_id', 'auditor_name', 'modified_time', 'delete_time', 'reason', 'tag_type', 'tag_create_at', 'cpc_quality_auto', 'cpc_quality_manual', 'cpc_ranking_manual', 'biz_type', 'ad_types', 'ad_status');
            }
            $HouseList = $this->objDaoHouseList->selectAllInfo($fields,$arrConds);
            if($HouseList ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseList->getLastSQL());
            }
            $this->arrRet['data'] = $HouseList;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }

    /**
     * @brief 通过$account_id获取该用户
     * @param $account_id 账户ID
     * @param $date,获取时间 'Y-m-d'
     * @return counts
     */
    public function getPostCountByAccountId($account_id,$date){
        if(empty($account_id) || empty($date) ||  !is_numeric($account_id)){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
        }
        $arrFields = array("type,xiaoqu_name");
        $arrConds['account_id='] = $account_id;
        $arrConds['post_at>='] = strtotime($date);
        $arrConds['post_at<'] = strtotime($date)+86400;
        $arrConds['listing_status='] = 1;

        try{
            $result = $this->objDaoHouseList->selectAllInfo($arrFields,$arrConds);
            $types = array_keys(HousingVars::$scriptIndex2MajorCategoryId);

            if(is_array($types)){
                $total_num = 0;
                $detail = array();
                foreach($types as $type){
                    if(!empty($result) && is_array($result)) {
                        $type_num = 0;
                        $xiaoqu = array();
                        foreach ($result as $item) {
                            if (isset($item['type']) && $type == $item['type'] ){
                                $type_num++;
                                $xiaoqu[] =  $item['xiaoqu_name'];
                                $total_num ++;
                            }
                        }
                        $detail[$type]['num'] = $type_num;
                        $xiaoqu = array_unique($xiaoqu);//小区去重
                        $detail[$type]['xiaoquNames'] = count($xiaoqu)>5 ? array_slice($xiaoqu,0,5) : $xiaoqu;//最多取5个小区
                    }
                }
                $this->arrRet['data']['total'] = $total_num;
                $this->arrRet['data']['detail'] = $detail;
            }
        }catch (Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage().'sql:'.$this->objDaoHouseList->getLastSQL()
            );
        }
     return $this->arrRet;

    }
}
