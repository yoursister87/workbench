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
class Service_Page_Source_Rest_ChangeFang{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    public function execute($arrParams){
        $arrRet = $this->arrRet;
        try{
            $objDsFang = Gj_LayerProxy::getProxy("Service_Data_Source_FangSubmit");
            $intPuid = $arrParams['puid'];
            $arrRows = json_decode($arrParams['rows'],true);
            $arrSourceInfo = $objDsFang->updateHouseSourceByPuid($arrRows,$intPuid,null,null,false);
            if($arrSourceInfo['errorno'] != ErrorConst::SUCCESS_CODE){
                Gj_Log::warning("get puid :".$intPuid." failed:msg.".$arrSourceInfo['errormsg'],$arrSourceInfo['errorno']);
            }

            $arrRet = $arrSourceInfo;


        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();

        }
        return $arrRet;

    }
    protected function checkParam($arrParams){
        if(!isset($arrParams['puid'])){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'puid is error');
        }
        if(!isset($arrParams['rows']) ){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'rows is error');
        }

    }
}
