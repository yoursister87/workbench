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
class Service_Page_RealHouse_GetPrivilegeInfo{
    protected $data;
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    //{{{execute
    /**
     * 获取是否能否评论该条房源
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
        if(!isset($arrInput['puid']) || !isset($arrInput['customer_id'])){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseCommentPrivilege');
            $res = $objService->getHouseCommentPrivilegeInfo($arrInput['puid']);
            if (!isset($res['data'][0]['customer_id']) || $res['data'][0]['customer_id']==$arrInput['customer_id']) {
            	$this->data['data'] = true;
            }else{
            	$this->data['data'] = false;
            }
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
}
