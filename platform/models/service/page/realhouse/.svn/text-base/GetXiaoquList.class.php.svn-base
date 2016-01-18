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
class Service_Page_RealHouse_GetXiaoquList{
    protected $data;
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    //{{{execute
    /**
     * 获取某个账号下发布房源的小区
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
    	if(!isset($arrInput['account_id']) || intval($arrInput['account_id'])< 0){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
    	try {
    		$house_type = 5;
    		$arrFields = array('district_id','street_id','xiaoqu_id','xiaoqu_name');
    		/**
    		 * @var Service_Data_Source_FangByAccount
    		 */
    		$objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
    		$res = $objService->getXiaoQuListByAccount($arrInput['account_id'], $house_type, $arrFields);
    		if($res['errorno'] || empty($res['data'])){
    			return $res;
    		}
    		foreach ($res['data'] as $row){
    			if($arrInput['xiaoqu_type']==3 && $row['street_id']==$arrInput['street_id'] && $row['district_id']==$arrInput['district_id']){
    				$this->data['data'][] = $row;
    			}else if($arrInput['xiaoqu_type']==2 && $row['district_id']==$arrInput['district_id']){
    				$this->data['data'][] = $row;
    			}else if($arrInput['xiaoqu_type']==1){
    				$this->data['data'][] = $row;
    			}
    		}
    	}catch (Exception $e){
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	return $this->data;
    }
}