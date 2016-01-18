<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_Housereport_PatchRule
{
    public function __construct(){
        $this->objDao = Gj_LayerProxy::getProxy('Dao_Housereport_PatchRule');
        $this->data = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
            'data' => array()
        );
    }
    public function getRuleList($arrFields=array(), $arrConds=array(), $page=1, $pageSize=10){
        $arrFields = array(
                "RuleId", 
                "RuleName", 
                "RuleStatus", 
                "StartTime", 
                "EndTime", 
                "CompanyId", 
                "CustomerId", 
                "CityCode", 
                "DistrictIndex", 
                "FreeType", 
                "PatchRule", 
                "HouseCount", 
                "CountType", 
                "HouseTypeStr", 
                "ModifiedTime", 
            );
        $arrConds = array(
            'RuleStatus=' => 1
        );
        try{
            $ret = $this->objDao->selectByPage($arrFields, $arrConds, $page, $pageSize);
            $this->data['data'] = $ret;
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
}
