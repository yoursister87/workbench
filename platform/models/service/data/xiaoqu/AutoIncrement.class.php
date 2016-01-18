<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class Service_Data_Xiaoqu_AutoIncrement
{
    public function addXiaoquAutoIncrement($xiaoquInfo){
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquAutoIncrement');
            $ret = $model->addXiaoquAutoIncrement($xiaoquInfo);
            $data = array(
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $ret,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }
}
