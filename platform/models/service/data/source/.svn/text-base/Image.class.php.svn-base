<?php

/**
 * 房源帖子ds接口.
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_Image
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
     * @brief 通过puid_index中的表名去映射dao
     * @param string $strTableName puid数据表名
     * @param $strDbName
     * @throws Exception
     * @return Dao_Fang_HouseImageRent
     */
    public function getObjDaoByTableName($strTableName,$strDbName)
    {
        $arrTable2Dao = array(
            'house_source_rent' => 'Dao_Fang_HouseImageRent',
            'house_source_sell' => 'Dao_Fang_HouseImageSell',
            'house_source_share' => 'Dao_Fang_HouseImageShare',
            'house_source_shortrent' => 'Dao_Fang_HouseImageShortrent',
            'house_source_wantrent' => 'Dao_Fang_HouseImageWantrent',
            'house_source_wantbuy' => 'Dao_Fang_HouseImageWantbuy',
            'house_source_storerent' => 'Dao_Fang_HouseImageStorerent',
            'house_source_storetrade' => 'Dao_Fang_HouseImageStoretrade',
            'house_source_officerent' => 'Dao_Fang_HouseImageOfficerent',
            'house_source_officetrade' => 'Dao_Fang_HouseImageOfficetrade',
            'house_source_plant' => 'Dao_Fang_HouseImagePlant',
            'house_source_loupan' => 'Dao_Fang_HouseImageLoupan',
            'house_source_modelroom' => 'Dao_Fang_HouseImageModelroom',
        );
        //@codeCoverageIgnoreStart
        if(!isset($arrTable2Dao[$strTableName])){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'get dao failed');
        }
        $objDao = Gj_LayerProxy::getProxy($arrTable2Dao[$strTableName],$strDbName);

        if(!is_object($objDao)){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'get obj dao failed');
        }
        //@codeCoverageIgnoreEnd
        return  $objDao;


    }

    /**
     * 获取房源信息通过puid 的信息
     * @param $intPostId
     * @param $strDbName
     * @param $strTableName
     * @param array $arrFileds
     * @internal param $intPuid
     * @return array|bool
     */
    public function getImageListByPostId($intPostId,$strDbName,$strTableName,$arrFileds=array())
    {

        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('post_id =' => $intPostId);
            if(empty($arrFileds) || !is_array($arrFileds)){
                $arrFileds = array('*');
            }

            $arrRes = $objDao->selectOrderByInd($arrFileds,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'select failed');
            }
            $this->arrRet['data'] = $arrRes;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;

    }
    /* {{{getImageListByPuid*/
    /**
     * @brief 
     *
     * @param $intPuid
     * @param $strDbName
     * @param $strTableName
     * @param $arrFileds
     *
     * @returns   
     */
    public function getImageListByPuid($intPuid, $strDbName=null, $strTableName=null, $arrFileds=array())
    {
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
            $arrConds = array('post_id =' => $res['post_id']);
            if(empty($arrFileds) || !is_array($arrFileds)){
                $arrFileds = array('*');
            }

            $arrRes = $objDao->selectOrderByInd($arrFileds,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'select failed');
            }
            $this->arrRet['data'] = $arrRes;
        } catch (Exception $e) {
            $this->arrRet = array(
                    'errorno' => $e->getCode(),
                    'errormsg' => $e->getMessage(),
                    );
        }
        return $this->arrRet;
    }//}}}
    /**
     * 获取房源通过post_id,以及数据相关资料获得信息
     * @param $intImageId
     * @param $strDbName
     * @param $strTableName
     * @internal param $intPostId
     * @return array
     */
    public function getImageInfoByImageId($intImageId,$strDbName,$strTableName){
        $arrRet = $this->arrRet;
        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('id =' => $intImageId);
            $arrFileds = array('*');

            $arrRes = $objDao->select($arrFileds,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'select failed');
            }
            $arrRet['data'] = $arrRes;

        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }

    /**
     * 新增图片信息
     * @param $arrRow
     * @param $strDbName
     * @param $strTableName
     * @return array
     */
    public function insertImageInfo($arrRow,$strDbName,$strTableName){
        $arrRet = $this->arrRet;

        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrRes = $objDao->insert($arrRow);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
            }
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;


    }

    public function updateImageInfoByPostId($arrChangeRow,$strDbName,$strTableName,$intPostId){
        $arrRet = $this->arrRet;

        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('post_id=' =>$intPostId);
            $arrRes = $objDao->update($arrChangeRow,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
            }
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }

    public function deleteImageByImageIdPuid($intImageId,$intPuid){
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
            $arrConds = array('id=' =>$intImageId);
            $arrRes = $objDao->delete($arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'delete image failed');
            }
            //更新帖子信息
            $objDSFangSubmit = Gj_LayerProxy::getProxy('Service_Data_Source_FangSubmit');
            $arrChangeRow= array('image_count=image_count-1');
            $arrRes = $objDSFangSubmit->updateHouseSourceByPuid($arrChangeRow,$intPuid,$strTableName,$strDbName);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update source failed');
            }

        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }

    public function getImageByPuid($intPuid){
        $arrRet = $this->arrRet;

        try {
            $objPostUid = new Util_Source_PostUid();
            $res = $objPostUid->lookUpIndex($intPuid);
            if(empty($res)){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, "puid is wrong");
            }
            $strDbName = $res['db_name'];
            $strTableName = $res['table_name'];
            $intPostId = $res['post_id'];
            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('post_id=' =>$intPostId);
            $arrFields = array('*');

            $arrRes = $objDao->select($arrFields,$arrConds);
            $arrRet['data'] = $arrRes;


        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }
}
