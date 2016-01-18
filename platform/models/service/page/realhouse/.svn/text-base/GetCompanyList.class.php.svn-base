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
class Service_Page_RealHouse_GetCompanyList{
    protected $data;
	/**
     * 默认构造方法
     * @codeCoverageIgnore
     */
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }//}}}
    //{{{__call
    /**
     * @codeCoverageIgnore
     * @param $name
     * @param $args
     * @return mixed
     */
     public function __call($name,$args){
     if (Gj_LayerProxy::$is_ut === true) {
     return  call_user_func_array(array($this,$name),$args);
    }
    }//}}}
    //{{{execute
    /**
     * 获取某个城市下面开通100%真房源的公司列表
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
        if(!isset($arrInput['CityId']) && intval($arrInput['CityId'])< 0 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            //获取城市下面开通100%真房源的公司
            $resCom = $this->getRealHouseCompanyByCityId($arrInput['CityId']);
            if($resCom['errorno']){
                return $resCom;
            }
            //获取公司logo
            $companyList = $this->getCompanyInfoByCompanyId($resCom, $arrInput['CityId']);
            $this->data['data'] = $companyList;
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}

    //{{{getRealHouseCompanyByCityId
    /**
     * 根据city_id获取该城市下开通100%真房源的账号
     * @param $city_id
     * @return mixed
     */
    protected function getRealHouseCompanyByCityId($city_id){
    	try{
	        /**
	         * @var Service_Data_Gcrm_Company
	         */
	        $objServiceCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Company');
	        $resCom = $objServiceCustomer->getRealHouseCompanyByCityId($city_id);
	        $this->data = $resCom;
        }catch (Exception $e){
        	$this->data['errorno'] = $e->getCode();
        	$this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getCompanyInfoByCompanyId
    /**
     * 获取公司logo
     * @param $resCustomer
     * @return array
     */
    protected function getCompanyInfoByCompanyId($resCustomer,$city_id){
    	try{
	        /**
	         * @var Service_Data_CompanyShop_BizCompanyInfo
	         */
	        $objServiceBizCompanyInfo = Gj_LayerProxy::getProxy('Service_Data_CompanyShop_BizCompanyInfo');
	        $resCompanyInfo = $objServiceBizCompanyInfo->getAllBizCompanyList($city_id,HousingVars::SELL_ID);
	        $companyList = array();
	        if (!empty($resCustomer['data']) && !$resCompanyInfo['errorno']){
	            foreach($resCustomer['data'] as $key=>$row){
	                if(isset($row['CompanyId']) && isset($resCompanyInfo['data'][$row['CompanyId']])){
	                    $row['logo'] = $resCompanyInfo['data'][$row['CompanyId']]['companylogopicurl'];
	                    $row['company_name'] = $resCompanyInfo['data'][$row['CompanyId']]['companyfullname'];
	                    $row['short_name'] = $resCompanyInfo['data'][$row['CompanyId']]['companyname'];
	                }
	                $companyList[] = $row;
	            }
	        }
        }catch (Exception $e){
        	$this->data['errorno'] = $e->getCode();
        	$this->data['errormsg'] = $e->getMessage();
        }
        return $companyList;
    }//}}}
}
