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
class Service_Page_Source_Rest_AddFang{
    private $objDs;
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function execute($arrParams){
        $arrRet = $this->arrRet;
        try{

            $objDsFang = Gj_LayerProxy::getProxy("Service_Data_Source_FangSubmit");
            $arrRows = json_decode($arrParams['rows'],true);

            $arrSourceInfo = $objDsFang->addHouseSourceApi($arrRows);
            if($arrSourceInfo['errorno'] != ErrorConst::SUCCESS_CODE){
                Gj_Log::warning("add new  failed:msg.".$arrSourceInfo['errormsg'],$arrSourceInfo['errorno']);
            }

            $arrRet = $arrSourceInfo;


        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();

        }
        return $arrRet;

    }
}