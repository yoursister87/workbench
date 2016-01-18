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
 * @codeCoverageIgnore 无逻辑，暂时不需要单测
 */
class Service_Page_Source_Rest_QueryFang{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * @param $arrParam
     * @return array
     */
    public function execute($arrParams){
        $arrRet = $this->arrRet;
        try{
            $this->checkParam($arrParams);
            $objDsFang = Gj_LayerProxy::getProxy("Service_Data_Source_FangQuery");
            $intPuid = $arrParams['puid'];
            $arrFields = isset($arrParams['fields'])?$arrParams['fields']:"";
            if(!is_numeric($intPuid)){
                Gj_Log::warning(" puid :".$intPuid." is wrong");
            }
            $arrSourceInfo = $objDsFang->getHouseSourceRest($intPuid,$arrFields);
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


    }
}