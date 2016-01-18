<?php

/**
 * 推广帖子的写操作的ds接口.
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_PremierSubmit
{


    /**
     * @var Gj_Base_MysqlDao
     */
    protected $objDaoDetail;

    protected $objSourcePuid;
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    const BIZ_TYPE_JINGPIN = 1;
    const BIZ_TYPE_ASSURE =2 ;
    //code_base2/app/adtype/include/AdTypeVars.class.php
    protected  static $arrBizAdType = array(
        self::BIZ_TYPE_JINGPIN =>0x200000,
        self::BIZ_TYPE_ASSURE => 0x400000 );


    public function __construct()
    {
        $this->objSourcePuid = new Util_Source_PostUid();
    }

    /**
     * 新增帖子接口,添加数据源操作premier数据库接口
     * @param $arrFields 插入数据库
     * @return array
     */
    protected  function addDbSource($arrFields){
        $arrRet = $this->arrRet;
        //必要参数检查
        if (!isset($arrFields['type'],$arrFields['puid'])){
            $arrRet['errno'] = ErrorConst::E_PARAM_INVALID_CODE;
            Gj_Log::warning("param is error",ErrorConst::E_PARAM_INVALID_CODE);
            return $arrRet;
        }
        unset($arrFields['house_id']);

        //开启事务
        $this->objDaoDetail->startTransaction();
        try {
            //1,插入详情表
            $intHouseId = $this->objDaoDetail->insert($arrFields);
            if(!is_numeric($intHouseId)){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"insert premire source detail failed,sql:".$this->objDaoDetail->getLastSQL());
            }
            $arrFields['house_id'] = $intHouseId;
            //2,插入描述分表
            if(!isset($arrFields['description'])){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"fields not has keys description ,fields:".json_encode($arrFields));
            }
            $objDaoDescription =  Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceDescription");
            //描述字段收集

            $arrDescFields = array('description' =>$arrFields['description'],'puid' =>$arrFields['puid']);

            $objDaoDescription->insertDesc($arrDescFields,$arrFields['puid']);
            //3,插入source_list
            $objDaoSourceList =  Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
            $objDaoSourceList->insert($arrFields);

            //提交事务
            $this->objDaoDetail->commit();
            $arrRet['data']= array('house_id' =>$intHouseId);

        } catch (Exception $e) {
            //回滚操作
            $this->objDaoDetail->rollback();
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }


    /**
     * 添加推广贴房源入口
     * @param $arrFields
     * @param string $intOperationType
     * @param string $strOperationMsg
     * @return array
     */
    public function addSource($arrFields,$intOperationType = Service_Data_Source_PremierSourceOperation::OP_TYPE_USER_ADD,$strOperationMsg=''){
        $arrRet = $this->arrRet;
        try{
            $arrFields = $this->patchFields($arrFields);
            //取得帖子的库表信息
            $objDsPremierQuery = Gj_LayerProxy::getProxy("Service_Data_Source_PremierQuery");
            $this->objDaoDetail  = $objDsPremierQuery->getObjDaoByType($arrFields['type']);
            //如果是归档帖恢复，去查puid是否在puid表存在，建议90天后不去查puid，2014-11-28
            $arrPuidInfo = null;
            if($intOperationType == Service_Data_Source_PremierSourceOperation::OP_TYPE_USER_ADD_RECOVER_EXPIRE_HOUSE){
                $arrPuidInfo =  $this->objSourcePuid->lookUpIndex($arrFields['puid']);
            }
            //将puid和帖子id写入post_index表
            if($arrPuidInfo === null){
                $bolRet = $this->objSourcePuid->insertIndex($arrFields['puid'], $this->objDaoDetail->getTableInfo('dbName'),$this->objDaoDetail->getTableInfo('tableName'),0);
                if($bolRet == false){
                    throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'insert puid failed');
                }
            }
            //将帖子信息写入数据库
            $arrDbRet = $this->addDbSource($arrFields);
            if($arrDbRet['errorno']!=ErrorConst::SUCCESS_CODE){
                //删除puid 索引
                $this->objSourcePuid->deleteIndex($arrFields['puid']);
                return $arrDbRet;
            }

            $intHouseId = $arrDbRet['data']['house_id'];
            $arrDbRet['data']['puid'] = $arrFields['puid'];

            //更新puid表中的house_id
            $bolRet = $this->objSourcePuid->updateIndex($arrFields['puid'], $intHouseId);
            if($bolRet == false){
                throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'update puid failed');
            }

            //写入日志操作
            $objDsDetailOperation = Gj_LayerProxy::getProxy("Service_Data_Source_PremierSourceOperation");
            $objDsDetailOperation->addSourceOperation($intHouseId,$arrFields['type'],$arrFields['account_id'],$intOperationType,$strOperationMsg,$arrFields['city']);
            return $arrDbRet;
        }catch (Exception $e) {

            Gj_Log::warning("throw Exception ".$e->getMessage(),$e->getCode());
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;

    }

    /**
     * 补充和检查字段
     * @param array $arrFields
     * @return array
     * @throws Gj_Exception
     */
    protected function patchFields($arrFields){
        //生成puid
        if(isset($arrFields['puid']) && is_numeric($arrFields['puid']) && $arrFields['puid']>0 ){
            $intPuid = $arrFields['puid'];
        }else{
            $intPuid = $this->objSourcePuid->generateId();
        }
        $arrFields['puid'] = $intPuid;
        if($arrFields['puid'] ==false){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'generateid puid failed');

        }

        //生成user_id，cookie_id
        if(!isset($arrFields['account_id'])){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'account_id is empty');
        }
        $objGcrm  = Gj_LayerProxy::getProxy("Service_Data_Gcrm_CustomerAccount");
        $arrGcrmRet= $objGcrm->getAccountInfoById($arrFields['account_id'],array('UserId'));
        if($arrGcrmRet['errorno'] != ErrorConst::SUCCESS_CODE){
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE,'get user_id failed');
        }
        $arrFields['user_id'] = $arrGcrmRet['data'][0]['UserId'];
        $arrFields['cookie_id'] = $_COOKIE['ganji_uuid'];

        //过滤掉Null
        foreach($arrFields as $k =>$v){
            if($v === null){
                unset($arrFields[$k]);
            }
        }
        //对biz_type 和ad_type进行转换同步
        $arrFields = $this->patchFieldsByAd($arrFields);
        return $arrFields;
    }

    protected  function patchFieldsByAd($arrFields){
        if(isset($arrFields['biz_type'])&& in_array($arrFields['biz_type'],array(1,2))){
            $adTypeStatus = array();
            $objAdtype = new Util_Source_AdType();
            $adTypeStatus['ad_types'] = $objAdtype->fmtAd(array(self::$arrBizAdType[$arrFields['biz_type']]=> true));
            $adTypeStatus['ad_status'] = $objAdtype->fmtAd(array(self::$arrBizAdType[$arrFields['biz_type']]=> true));
        }else{
            return $arrFields;
        }
        if ($adTypeStatus) {
            unset($arrFields['ad_types']);
            unset($arrFields['ad_status']);
            foreach ($adTypeStatus as $strField => $dos) {
                $adSqlRem = '';
                $adSqlAdd = '';
                foreach ($dos as $bin => $do) {
                    if ($do) {
                        $adSqlAdd .= '|'.$bin;
                    } else {
                        $adSqlRem .= '&~'.$bin;
                    }
                }
                $arrFields[] = sprintf(' `%s` = ((`%s`%s)%s) ', $strField, $strField, $adSqlRem, $adSqlAdd);
            }
        }

        return $arrFields;

    }

    public function updateAccountPostsBizType($accountId, $curBizType, $newBizType, $type='')
    {
        $arrRet = $this->arrRet;
        if(empty($accountId) || empty($type)){
            $arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $arrRet['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $arrRet;
        }
        if ('' != $type) {
            $typeClause = "type in ({$type})";
        } else {
            $typeClause = '';
        }
        $arrChangeRow = $this->patchFieldsByAd(array('biz_type' => $newBizType));
        try {
            $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
            $arrConds = array('account_id =' => $accountId, 'biz_type =' => $curBizType);
            if($typeClause){
                $arrConds[] = $typeClause;
            }
            $arrRes = $objDao->update($arrChangeRow, $arrConds);
            if ($arrRes === false) {
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE, 'update failed');
            }
        } catch (Exception $e) {
            $arrRet = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        $types = explode(',', $type);
        unset($arrChangeRow['biz_type']);
        foreach ($types as $t) {
            try {
                $objDSPremierQuery = Gj_LayerProxy::getProxy('Service_Data_Source_PremierQuery');
                $objDao = $objDSPremierQuery->getObjDaoByType((int)$t);
                $conds = array('account_id =' => $accountId, 'type =' => (int)$t);
                $arrRes = $objDao->update($arrChangeRow, $conds);
                if($arrRes ===false){
                    throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
                }
            } catch(Exception $e) {
                $arrRet = array(
                    'errorno' => $e->getCode(),
                    'errormsg' => $e->getMessage(),
                );
            }
        }//}}}
        return $arrRet;
    }//}}}
}