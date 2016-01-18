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
class Service_Data_Source_HouseModifyRecord{
    protected $data;
    protected $arrFields = array("id","puid","fieldname","oldvalue","newvalue","user_id","ip","post_at");
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    //{{{__call
    /**
     * @param $name
     * @param $args
     * @return mixed
     * @codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }//}}}
     //{{{getPriceRangeByPuid
    /**
     * 获取一个帖子的历史最高价和最低价  
     * 
     * @param mixed $puid 
     * @access public
     * @return void
     */
    public function getPriceRangeByPuid($puid){
        $range= array("MAX"=>0, "MIN"=>0);
        if(empty($puid)|| !is_numeric($puid)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $range;
        }
        $arrFields = array("MAX(newvalue) as MAX","MIN(oldvalue) as MIN", "MAX(oldvalue) as MAX_M","MIN(newvalue) as MIN_M");
        $whereConds= array("puid"=> $puid);
        $ret= $this->getModifyRecordListByWhere($whereConds, $arrFields);
        if(empty($ret['data'][0]['MAX'])|| empty($ret['data'][0]['MIN'])||empty($ret['data'][0]['MAX_M'])|| empty($ret['data'][0]['MIN_M'])){
            $range['MAX']= 0;
            $range['MIN']= 0;
        }else {
            $range['MAX']= $ret['data'][0]['MAX']>$ret['data'][0]['MAX_M']? $ret['data'][0]['MAX']: $ret['data'][0]['MAX_M'];
            $range['MIN']= $ret['data'][0]['MIN']<$ret['data'][0]['MIN_M']? $ret['data'][0]['MIN']: $ret['data'][0]['MIN_M'];
        }
        return $range;
    }
    //}}}
    //{{{getModifyRecordListByWhere
    /**
     * 获取修改记录
     * @param $whereConds
     * @param array $arrFields
     * @param int $page
     * @param int $pageSize
     * @param array $orderArr
     * @return mixed
     */
    public function getModifyRecordListByWhere($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        if (count($arrFields)) {
            $this->arrFields = $arrFields;
        }
        $arrConds = $this->getWhere($whereConds);
        if(count($arrConds) <=0 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            /**
             * @var Dao_Housepremier_HouseModifyRecord
             */
            $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseModifyRecord');
            $res = $objDao->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($objDao->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{getWhere
    /**
     * 组装查询条件
     * @param $whereConds
     * @return array
     */
    protected function getWhere($whereConds){
        $arrConds = array();
        if(!empty($whereConds['puid'])){
            $arrConds['puid ='] = $whereConds['puid'];
        }
        if(!empty($whereConds['fieldname'])){
            $arrConds['fieldname ='] = $whereConds['fieldname'];
        }
        if(!empty($whereConds['user_id'])){
            $arrConds['user_id ='] = $whereConds['user_id'];
        }
        return $arrConds;
    }//}}}

    //{{{insertSellInfo
    /**
     * 插入真房源价格修改记录
     * @param $arrFields
     * @return array
     */
    public function insertPriceModifyInfo($arrFields)
    {
        try{
            $objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseModifyRecord');
            $res = $objDao->insert($arrFields);
            if($res ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
            }
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
}
