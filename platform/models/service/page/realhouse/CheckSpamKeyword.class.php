<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_RealHouse_CheckSpamKeyword{
    protected $data;
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    //{{{execute
    /**
     * 防垃圾
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
        if(!isset($arrInput['keyword']) || !is_string($arrInput['keyword'])){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $objService = Gj_LayerProxy::getProxy('Service_Data_Gcc_CallOuttaskExport');
            $res = $objService->checkSpamKeyword($arrInput['keyword']);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
}
