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
class Service_Page_Source_Rest_BatchQueryFang{
    const TYPE_GET_ONE = 1;
    const TYPE_GET_BATCH = 2;
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
            $objDsFang = Gj_LayerProxy::getProxy("Service_Data_Source_FangQuery");
            $arrPuid = explode(",",$arrParams['puid']);
            if(count($arrPuid) >50 || count($arrPuid)<1){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"puid总数超出限制");
            }
            $arrFields = isset($arrParams['fields'])?explode(",",$arrParams['fields']):null;
            foreach($arrPuid as $intPuid){
                if(!is_numeric($intPuid)){
                    Gj_Log::warning(" puid :".$intPuid." is wrong");
                    continue;
                }
                $arrSourceInfo = $objDsFang->getHouseSourceRest($intPuid,$arrFields);
                if($arrSourceInfo['errorno'] != ErrorConst::SUCCESS_CODE){
                    Gj_Log::warning("get puid :".$intPuid." failed:msg.".$arrSourceInfo['errormsg'],$arrSourceInfo['errorno']);
                    continue;
                }
                $arrRet['data'][$intPuid] = $arrSourceInfo['data'];
            }


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