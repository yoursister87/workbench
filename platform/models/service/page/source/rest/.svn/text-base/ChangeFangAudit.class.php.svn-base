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
class Service_Page_Source_Rest_ChangeFangAudit{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    const AUDIT_PASS = 1;
    const AUDIT_REFUSE = 2;
    protected $arrAuditRow =array( self::AUDIT_PASS =>array('listing_status'=>'5','display_status'=>'3','editor_audit_status'=>'1'  ),
    self::AUDIT_REFUSE =>array('listing_status'=>'2','display_status'=>'1','editor_audit_status'=>'2'  ));
    public function execute($arrParams){
        $arrRet = $this->arrRet;
        try{

            $this->checkParam($arrParams);
            $objDsFang = Gj_LayerProxy::getProxy("Service_Data_Source_FangSubmit");
            $intPuid = $arrParams['puid'];
            $arrRows = json_decode($arrParams['rows'],true);
            if($arrRows ===null){
               $arrRows =array();
            }
            if(isset($arrParams['audit_status'])){
                $arrRows = array_merge($arrRows,$this->arrAuditRow[$arrParams['audit_status']]);
            }
            if(empty($arrRows)){
                $arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            }

            $arrSourceInfo = $objDsFang->updateHouseSourceByPuid($arrRows,$intPuid,null,null,true);
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
        if(!isset($arrParams['rows']) && !isset($arrParams['audit_status'])){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'rows is error');
        }

    }
}
