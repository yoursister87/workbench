<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   renyajing$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Service_Data_Broker_List
{
    //{{{getSourceList
    public function getSourceList($queryConfigArr){
        try {
            if (!is_array($queryConfigArr) || empty($queryConfigArr)
                || empty($queryConfigArr['queryFilter']) || !is_array($queryConfigArr['queryFilter'])
            ) {
                $data = array(
                    'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
                    'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
                );
            } else {
                $obj = Gj_LayerProxy::getProxy('Dao_Xapian_Broker');
                $searchResult = $obj->getSourceList($queryConfigArr);
                $data = array(
                    'errorno'  => ErrorConst::SUCCESS_CODE,
                    'errormsg' => ErrorConst::SUCCESS_MSG,
                    'data' => array('data' => $searchResult),
                );
            }
        } catch(Exception $e){
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
        return $data;
    }//}}}
}
