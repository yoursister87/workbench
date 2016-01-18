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
class Service_Page_RealHouse_GetCommentHouseList{
	protected $data;
    //{{{execute
    /**
     * 获取100%真房源已经评论房源列表
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
            $res = $objService->getCommentHouseList($arrInput);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
}