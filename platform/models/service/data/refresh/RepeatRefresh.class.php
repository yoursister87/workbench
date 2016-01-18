<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * @codeCoverageIgnore 没有逻辑
 */
class Service_Data_Refresh_RepeatRefresh{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function setRepeatRefresh($repeatInfo){
        $arrRet = $this->arrRet;
        try{
            if(empty($repeatInfo['puid']) || $repeatInfo['puid'] <= 0){
                if(empty($repeatInfo['house_id']) || empty($repeatInfo['type'])){
                    throw new Gj_Exception( ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG."setRepeatRefresh:house_id or house_type is empty");
                }
                $arrFields = array('puid','account_id');
                $arrConds = array('house_id ='=>$repeatInfo['house_id'],'type ='=>$repeatInfo['type']);  
                $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
                $ret = $objDao->select($arrFields, $arrConds);
                if(!empty($ret[0]['puid'])){
                    $repeatInfo['puid'] = $ret[0]['puid'];
                } else {
                    $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
                    $ret = $objDao->select($arrFields, $arrConds);
                    if(!empty($ret[0]['puid'])){
                        $repeatInfo['puid'] = $ret[0]['puid'];
                    }/*else{
                        $sql = $objDao->getLastSQL();
                        throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, 'puid select返回：'. var_export($ret, true).'sql:'.$sql);
                    }*/
                }

                if(!empty($ret[0]['account_id'])){
                    $repeatInfo['account_id'] = $ret[0]['account_id'];
                }
            }
            $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_RepeatRefresh');
            $ret  =  $objDao->setRepeat($repeatInfo);
            if($ret > 0){
                $arrRet['data'] = 'puid:'.$repeatInfo['puid'].' insert ok';
            } elseif($ret == 0){
                $arrRet['data'] = 'puid:'.$repeatInfo['puid'].' update ok';
            } else {
                throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, 'setRepeatRefresh:puid:'.$repeatInfo['puid'].' sql update or insert fail');
            }

        } catch(Exception $e){
             $arrRet['errorno'] = $e->getCode();
             $arrRet['errormsg'] = $e->getMessage();

        }
        return $arrRet;
    }

   
}
