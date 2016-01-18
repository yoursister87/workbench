<?php

/**
 * 房源帖子ds接口.
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_FangQuery
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

    /**
     * 通过puid_index中的表名去映射dao
     * @param string $strTableName puid数据表名
     * @param string $strDbName puid 数据库名
     * @throws Gj_Exception
     * @return Dao_Fang_HouseSourceRent
     */
    public function getObjDaoByTableName($strTableName,$strDbName)
    {
        $arrTable2Dao = array(
            'house_source_rent' => 'Dao_Fang_HouseSourceRent',
            'house_source_sell' => 'Dao_Fang_HouseSourceSell',
            'house_source_share' => 'Dao_Fang_HouseSourceShare',
            'house_source_shortrent' => 'Dao_Fang_HouseSourceShortrent',
            'house_source_wantrent' => 'Dao_Fang_HouseSourceWantrent',
            'house_source_wantbuy' => 'Dao_Fang_HouseSourceWantbuy',
            'house_source_storerent' => 'Dao_Fang_HouseSourceStorerent',
            'house_source_storetrade' => 'Dao_Fang_HouseSourceStoretrade',
            'house_source_officerent' => 'Dao_Fang_HouseSourceOfficerent',
            'house_source_officetrade' => 'Dao_Fang_HouseSourceOfficetrade',
            'house_source_plant' => 'Dao_Fang_HouseSourcePlant',
            'house_source_loupan' => 'Dao_Fang_HouseSourceLoupan',
            'house_source_officerent_premier'=>'Dao_Housepremier_HouseSourceOfficerentPremier',
            'house_source_officetrade_premier'=>'Dao_Housepremier_HouseSourceOfficetradePremier',
            'house_source_rent_premier'=>'Dao_Housepremier_HouseSourceRentPremier',
            'house_source_sell_premier'=>'Dao_Housepremier_HouseSourceSellPremier',
            'house_source_share_premier'=>'Dao_Housepremier_HouseSourceSharePremier',
            'house_source_storerent_premier'=>'Dao_Housepremier_HouseSourceStorerentPremier',
            'house_source_storetrade_premier'=>'Dao_Housepremier_HouseSourceStoretradePremier',
            'house_source_plant_premier'=>'Dao_Housepremier_HouseSourcePlantPremier',
            'house_source_loupan_premier'=>'Dao_Housepremier_HouseSourceLoupanPremier',
            'house_source_shortrent_premier'=>'Dao_Housepremier_HouseSourceShortrentPremier',
            'house_source_modelroom' => 'Dao_Fang_HouseSourceModelroom',
        );
        if(!isset($arrTable2Dao[$strTableName])){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,"dao name is error");
        }
        if($strDbName =='house_premier'){
            $objDao = Gj_LayerProxy::getProxy($arrTable2Dao[$strTableName]);
        }else{
            $objDao = Gj_LayerProxy::getProxy($arrTable2Dao[$strTableName],$strDbName);
        }

        if(!is_object($objDao)){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,"error obj Dao");

        }
        return  $objDao;


    }
    /**
     * 获取房源信息通过puid 的信息
     * @param $intPuid
     * @param $strDbName
     * @param $strTableName
     * @param array $arrFields
     * @param bool $bolIsMaster
     * @return array
     */
    public function getHouseSourceByPuidInfo($intPuid,$strDbName=null,$strTableName=null,$arrFields=array(),$bolIsMaster=false)
    {
        $arrRet = $this->arrRet;
        try {
            if($strDbName===null ||$strTableName===null){
                $objPostUid = new Util_Source_PostUid();
                $res = $objPostUid->lookUpIndex($intPuid);
                if(empty($res)){
                    throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, "puid is wrong");
                }
                $strDbName = $res['db_name'];
                $strTableName = $res['table_name'];
            }
            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('puid =' => $intPuid);
            if(empty($arrFields)||!is_array($arrFields)){
                $arrFields = array('*');
            }
            $arrRes = array();
            if($bolIsMaster === false){
                $arrRes = $objDao->select($arrFields,$arrConds);
            }
            //从库没有取到就要从主库或者直接取主库
            if(empty($arrRes)){
                $arrRes = $objDao->selectByMaster($arrFields,$arrConds);
            }
            if(empty($arrRes)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'get db data is empty');
            }

            $arrRet['data'] = $arrRes[0];
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }

    ///////////////////
    /**
     * 获取房源信息通过
     * @param $arrConds
     * @param $strDbName
     * @param $strTableName
     * @param array $arrFields
     * @param bool $bolIsMaster
     * @return array
     */
    public function getHouseSourceByConds($arrConds,$strDbName=null,$strTableName=null,$arrFields=array(),$bolIsMaster=false, $currentPage = null, $pageSize = null, $orderArr = array(), $appends = null)
    {
        $arrRet = $this->arrRet;
        try {
            if($strDbName===null ||$strTableName===null){
                $objPostUid = new Util_Source_PostUid();
                $res = $objPostUid->lookUpIndex($arrConds['puid =']);
                if(empty($res)){
                    throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, "puid is wrong");
                }
                $strDbName = $res['db_name'];
                $strTableName = $res['table_name'];
            }
            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            if(empty($arrFields)||!is_array($arrFields)){
                $arrFields = array('*');
            }
            $arrRes = array();
            if($bolIsMaster === false){
                $arrRes = $objDao->selectByPage($arrFields, $arrConds, $currentPage, $pageSize, $orderArr, $appends);
            }
            //从库没有取到就要从主库或者直接取主库
            if(empty($arrRes)){
                $arrRes = $objDao->selectByMaster($arrFields,$arrConds);
            }
            if(empty($arrRes)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'get db data is empty');
            }
            $arrRet['data'] = $arrRes;
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }
    ////////////////////

    /**
     * 获取房源通过post_id,以及数据相关资料获得信息
     * @param $intPostId
     * @param $intCityCode
     * @param $strDbName
     * @param $strTableName
     * @return array
     */
    public function getHouseSourceByPostId($intPostId,$strDbName,$strTableName,$arrFields= array()){

        $arrRet = $this->arrRet;
        if(empty($strDbName) || empty($strTableName)){
            throw  new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"db or table is empty");
        }
        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('id =' => $intPostId);
            if(empty($arrFields)||!is_array($arrFields)){
                $arrFields = array('*');
            }
            $arrRes = $objDao->select($arrFields,$arrConds);
            //从库没有取到就要从主库取
            if(empty($arrRes)){
                $arrRes = $objDao->selectByMaster($arrFields,$arrConds);
            }
            if(empty($arrRes)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'get db data is empty');
            }
            $arrRet['data'] = $arrRes[0];
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }

    /**
     * 获取待审核房源贴，需要查版本库
     * @param $intPuid
     * @return array
     */
    public function getHouseSourceAuditByPuid($intPuid,$strFieldCategory){
        $arrRet = $this->arrRet;

        try {
            $curSourceContent = $this->getHouseSourceRest($intPuid,$strFieldCategory);
            if($curSourceContent['errorno'] != ErrorConst::SUCCESS_CODE || empty($curSourceContent['data'])){
                return $curSourceContent;
            }

            $objEditHistory = Gj_LayerProxy::getProxy("Dao_Ganjimisc_PostEditHistory");
            $arrConds = array('puid =' => $intPuid);
            $arrFields =array('data');
            $arrEditHistory = $objEditHistory->select($arrFields,$arrConds);
            if(!empty($arrEditHistory)){

                $arrPostHistory = unserialize($arrEditHistory[0]['data']);
                $arrPostHistory = $arrPostHistory?$arrPostHistory['post']:array();
                $arrRet['data'] = array_merge($curSourceContent['data'],$arrPostHistory);
            }else{
                return $curSourceContent;
            }
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }

    public function getHouseSourceRest($intPuid,$strFieldCategory){
        $arrRet = $this->arrRet;
        try {

            $objPostUid = new Util_Source_PostUid();
            $res = $objPostUid->lookUpIndex($intPuid);
            if(empty($res)){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, "puid is wrong");
            }
            $strDbName = $res['db_name'];
            $strTableName = $res['table_name'];
            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $objFormatRest = Gj_LayerProxy::getProxy("Service_Data_Source_FangFieldsRestFormat",$objDao);
            $arrFields = $objFormatRest->getFieldsByCategory($strFieldCategory);
            $arrConds = array('puid =' => $intPuid);
            if(empty($arrFields)||!is_array($arrFields)){
                $arrFields = array('*');
            }
            $arrRes = $objDao->select($arrFields,$arrConds);
            //从库没有取到就要从主库或者直接取主库
            if(empty($arrRes)){
                $arrRes = $objDao->selectByMaster($arrFields,$arrConds);
            }
            if(empty($arrRes)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'get db data is empty');
            }

            $arrRet['data'] = $objFormatRest->formatRetFileds($arrRes[0]);
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }

}
