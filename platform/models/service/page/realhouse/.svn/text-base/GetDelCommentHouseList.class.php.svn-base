<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_RealHouse_GetDelCommentHouseList{
    protected $data;
    //{{{execute
    /**
     * 获取100%真房源失效房源列表
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
        if(!isset($arrInput['user_id']) || !is_numeric($arrInput['user_id']) || !isset($arrInput['owner_account_id']) || !is_numeric($arrInput['owner_account_id'])){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealQuery');
            $res = $objService->getDelCommentHouseList($arrInput);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
}