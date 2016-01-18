<?php

/**
 * 房源帖子ds接口.
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_PremierQuery
{
    protected $objDaoSource;
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function __construct()
    {

    }

    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    /**
     * 通过puid_index中的表名去映射dao
     * @param string $strTableName puid数据表名
     * @param string $strDbName puid 数据库名
     * @throws Gj_Exception
     * @return Dao_Fang_HouseSourceRent
     */
    public function getObjDaoByType($intType)
    {
        $arrHouseTypeMapDao = array(
            1 => 'Dao_Housepremier_HouseSourceRentPremier',
            3 => 'Dao_Housepremier_HouseSourceSharePremier',
            5 => 'Dao_Housepremier_HouseSourceSellPremier',
            6 => 'Dao_Housepremier_HouseSourceStorerentPremier',
            7 => 'Dao_Housepremier_HouseSourceStoretradePremier',
            8 => 'Dao_Housepremier_HouseSourceOfficerentPremier',
            9 => 'Dao_Housepremier_HouseSourceOfficetradePremier',
            10 => 'Dao_Housepremier_HouseSourceShortrentPremier',
            11 => 'Dao_Housepremier_HouseSourcePlantPremier',
            12 => 'Dao_Housepremier_HouseSourceLoupanPremier',
            11001 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11003 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11011 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11013 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11021 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11023 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11031 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11033 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11041 => 'Dao_Housepremier_HouseSourcePlantPremier',
            11043 => 'Dao_Housepremier_HouseSourcePlantPremier',
        );
        if(!isset($arrHouseTypeMapDao[$intType])){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,"dao name is error");
        }
        $objDao = Gj_LayerProxy::getProxy($arrHouseTypeMapDao[$intType]);

        if(!is_object($objDao)){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,"error obj Dao");

        }
        return  $objDao;


    }


    /**
     * 通过house_id 和type 获取信息
     * @param int $intHouseId
     * @param int $intType
     * @param array $arrFileds
     * @return array|bool
     */
    public function getRowByHouseId($intHouseId,$intType,$arrFileds=array()){
        $arrRet = $this->arrRet;
        try{

            $objDao = $this->getObjDaoByType($intType);
            if (!$objDao){
                return false;
            }
            if (empty($arrFileds)){
                $arrFileds =  array('*');
            }elseif(!in_array('puid',$arrFileds)){
                $arrFileds[] = 'puid';
            }
            $conds = array('house_id =' => $intHouseId);
            //为了检查是否利用新的接口加入limit 1
            $appends = 'limit 1';
            $ret = $objDao->select($arrFileds,$conds,null,$appends);
            if (empty($ret)){
                $ret = $objDao->selectByMaster($arrFileds,$conds,null,$appends);
            }
            //主从都找不到。默认house_id有错误
            if(empty($ret)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'get house info empty');
            }
            //检查是否同步查描述字段
            if (in_array('*',$arrFileds) || in_array('description',$arrFileds)){
                $objDaoDesc = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceDescription");
                $ret_desc = $objDaoDesc->selectDesc(array('description'),array('puid='=>$ret[0]['puid']),$ret[0]['puid']);
                if ($ret_desc){
                    $ret[0]['description'] = $ret_desc[0]['description'];
                }
            }
            $arrRet['data'] = $ret[0];
        }catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }
    //{{{getTuiguangHouseByAccountId
    /**
     * 获取推广中的房源
     * @param $whereConds
     * @param array $arrFields
     * @param int $page
     * @param int $pageSize
     * @param array $orderArr
     * @return mixed
     */
    public function getTuiguangHouseByAccountId($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        if (!count($arrFields)) {
            $arrFields = array('house_id','type','puid');
        }
        $arrConds = $this->getHouseWhere($whereConds);
        if(count($arrConds) <=0){
            $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->arrRet['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->arrRet;
        }
        try{
            /**
             *@var Dao_Housepremier_HouseSourceList
             */
            $objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
            $res = $objDao->selectByPage($arrFields, $arrConds, $page, $pageSize, $orderArr);
        } catch(Exception $e) {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($objDao->getLastSQL());
            $this->arrRet['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->arrRet['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->arrRet['data'] = $res;
        }
        return $this->arrRet;
    }//}}}
    //{{{getHouseWhere
    /**
     * 组装条件
     * @param $whereConds
     * @return array
     */
    protected function getHouseWhere($whereConds){
        $arrConds = array();
        if(!empty($whereConds['account_id'])){
            $arrConds['account_id ='] = $whereConds['account_id'];
        }
        if(!empty($whereConds['listing_status'])){
        	$arrConds['listing_status ='] = $whereConds['listing_status'];
        }
        if (is_array($whereConds['premier_status'])){
            $premier_status = implode(',',$whereConds['premier_status']);
            $arrConds[] = "premier_status IN ({$premier_status})";
        } else if(!empty($whereConds['premier_status'])){
            $arrConds['premier_status ='] = "{$whereConds['premier_status']}";
        }
        if (is_array($whereConds['puid'])){
        	$puid = implode(',',$whereConds['puid']);
        	$arrConds[] = "puid IN ({$puid})";
        } else if(!empty($whereConds['puid'])){
        	$arrConds['puid ='] = "{$whereConds['puid']}";
        }
        if(!empty($whereConds['s_post_at'])){
            $arrConds['post_at >='] = $whereConds['s_post_at'];
        }
        if(!empty($whereConds['e_post_at'])){
            $arrConds['post_at <='] = $whereConds['e_post_at'];
        }
        if(!empty($whereConds['conds'])){
        	$arrConds = array(
        			'account_id =' =>$whereConds['account_id'],
        			$whereConds['conds']
        	);
        }
        /**
         * @author 刘海鹏 <liuhaipeng1@ganji.com>
         * @create 2015-07-14
         */
        if (! empty($whereConds['type'])) {
            if (is_array($whereConds['type'])) {
                $type = implode(',', $whereConds['type']);
                $arrConds[] = "type IN ({$type})";
            } else {
                $arrConds['type ='] = $whereConds['type'];
            }
        }
        return $arrConds;
    }//}}}

}