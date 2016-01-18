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
class Service_Data_Refresh_PremierRefreshQuery{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    public function getRefreshNumByDay($intAccountId,$intBizScope,$intDateOffset = 0){
        $arrRet = $this->arrRet;
        try{

            $objDao = Gj_LayerProxy::getProxy("Dao_Redis_Refresh_UserRefreshBizScope",array('account_id'=>$intAccountId,'biz_scope'=>$intBizScope,'date_offset' =>$intDateOffset));
            $arrRet['data']['release'] = $objDao->getDoneRefreshNum();
            $arrRet['data']['total'] = $objDao->getRefreshTotalNum();
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }
    public function getRefreshTotalNumByType($intAccountId,$arrHouseType,$intDateOffset = 0){
        $arrRet = $this->arrRet;
        //处理房11的问题
        foreach($arrHouseType as $key =>$intHouseType){
            if(strlen($intHouseType)>4){
                $arrHouseType[$key] = substr($intHouseType,0,2);
            }
        }
        $arrHouseType = array_unique($arrHouseType);
        try{
            $objDao = Gj_LayerProxy::getProxy("Dao_Redis_Refresh_UserRefreshType",$intDateOffset);
            $arrRet['data']['total'] = $objDao->getRefreshTotalNum($intAccountId,$arrHouseType);
        }catch(Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;

    }

    public function getRefreshNumByType($intAccountId,$arrHouseType,$intDateOffset = 0){
        $arrRet = $this->arrRet;

        //处理房11的问题
        foreach($arrHouseType as $key =>$intHouseType){
            if(strlen($intHouseType)>4){
                $arrHouseType[$key] = substr($intHouseType,0,2);
            }
        }
        $arrHouseType = array_unique($arrHouseType);
        try{
            $objDao = Gj_LayerProxy::getProxy("Dao_Redis_Refresh_UserRefreshType",$intDateOffset);
            $arrRet['data']['release'] = $objDao->getDoneRefreshNum($intAccountId,$arrHouseType);
            $arrRet['data']['total'] = $objDao->getRefreshTotalNum($intAccountId,$arrHouseType);
        }catch(Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;

    }

    public function getAllRefreshNumType($intAccountId,$arrHouseType,$intDateOffset = 0){
        $arrRet = $this->arrRet;

        //处理房11的问题
        foreach($arrHouseType as $key =>$intHouseType){
            if(strlen($intHouseType)>4){
                $arrHouseType[$key] = substr($intHouseType,0,2);
            }
        }
        $arrHouseType = array_unique($arrHouseType);
        try{
            $objDao = Gj_LayerProxy::getProxy("Dao_Redis_Refresh_UserRefreshType",$intDateOffset);
            $arrRet['data'] = $objDao->getAllRefreshByType($intAccountId,$arrHouseType);
        }catch(Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;

    }

}