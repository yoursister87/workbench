<?php
/**
 * 房源帖子ds接口.
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_FangSubmit{
    const CATEGORY_SCRIPT_INDEX = 2;
    protected $objDaoSource;
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function __construct()
    {
        $this->objSourcePuid = new Util_Source_PostUid();

    }

    /**
     * @param $strTableName
     * @param $strDbName
     * @return Gj_Base_MysqlDao
     */
    protected  function getObjDaoByTableName($strTableName,$strDbName){
        $objDSFangQuery = Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery');
        $objDao = $objDSFangQuery->getObjDaoByTableName($strTableName,$strDbName);
        return $objDao;
    }

    /**
     * @param $arrFields
     * @param $strTableName
     * @param $strDbName
     * @return array
     */
    public function addHouseSource($arrFields,$strTableName,$strDbName){
        $arrRet = $this->arrRet;

        try {

            $objDao = $this->getObjDaoByTableName($strTableName,$strDbName);
            $intPostId = $objDao->insert($arrFields);
            if($intPostId === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
            }
            $arrRet['data']['post_id'] = $intPostId;

        }
        catch(Exception $e) {
            $arrRet = array(
                    'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                    'errormsg' => $e->getMessage(),
                );
            }
        return $arrRet;
    }



    /**
     * 更新房源信息，通过puid
     * @param $arrChangeRow
     * @param $intPuid
     * @param $strTableName
     * @param $strDbName
     * @return array
     */
    public function updateHouseSourceByPuid($arrChangeRow,$intPuid,$strTableName=null,$strDbName=null,$isAudit = false){
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
            $objDSFangQuery = Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery');
            $objDao = $objDSFangQuery->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('puid =' =>$intPuid);
            if($isAudit!== false){
                $arrConds['ad_types='] = 0;
            }
            $arrRes = $objDao->update($arrChangeRow,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
            }

        } catch(Exception $e) {
            $arrRet = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }
    //{{{updateHouseSourceListByPuid
    /**
     * 更新房源信息，通过puid
     * @param $arrChangeRow
     * @param $intPuid
     * @return array
     */
    public function updateHouseSourceListByPuid($arrChangeRow,$intPuid){
        $arrRet = $this->arrRet;
        try {
            $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
            $arrConds = array('puid =' =>$intPuid);
            $arrRes = $objDao->update($arrChangeRow,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
            }

        } catch(Exception $e) {
            $arrRet = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }//}}}

    public function updateHouseSourceByPostId($arrChangeRow,$intPostId,$strTableName,$strDbName){
        $arrRet = $this->arrRet;
        try {
            $objDSFangQuery = Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery');
            $objDao = $objDSFangQuery->getObjDaoByTableName($strTableName,$strDbName);
            $arrConds = array('id =' =>$intPostId);
            $arrRes = $objDao->update($arrChangeRow,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
            }

        } catch(Exception $e) {
            $arrRet = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }
    public function addHouseSourceApi($arrFields){
        $objGeo = new Gj_Util_Geo();
        $arrCityInfo = $objGeo->getCityByCityCode($arrFields['city']);
        $strDbName = $arrCityInfo['database'];
        $objCategory = new Gj_Util_Category();
        $arrMajorCategory = $objCategory->getMajorCategoryByScriptIndex(Service_Data_Source_FangSubmit::CATEGORY_SCRIPT_INDEX,$arrFields['major_category']);
        $strTableName = $arrMajorCategory['table'];
        if(empty($arrFields['puid'])){
            $intPuid = $this->objSourcePuid->generateId();
            $bolRet = $this->objSourcePuid->insertIndex($intPuid, $strDbName,$strTableName,0);
            if($bolRet == false){
                throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'insert puid failed');
            }
            $arrFields['puid'] = $intPuid;

        }
        $arrDbRet = $this->addHouseSource($arrFields,$strTableName,$strDbName);
        if($arrDbRet['errorno']!=ErrorConst::SUCCESS_CODE){
            //删除puid 索引
            $this->objSourcePuid->deleteIndex($arrFields['puid']);
            return $arrDbRet;
        }
        $intHouseId = $arrDbRet['data']['post_id'];
        $arrDbRet['data']['puid'] = $arrFields['puid'];
        //更新puid表中的house_id
        $bolRet = $this->objSourcePuid->updateIndex($arrFields['puid'], $intHouseId);
        if($bolRet == false){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'update puid failed');
        }

        return $arrDbRet;
    }

}