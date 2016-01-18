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
class Service_Data_Source_UserPostList
{
    protected $data;
    /**
     * @var Dao_Housepremier_UserPostList
     */
    protected $objDaoUserPostList;
    protected $arrFields = array("id","puid","house_id","type","daynum","account_id");

    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDaoUserPostList = Gj_LayerProxy::getProxy('Dao_Housepremier_UserPostList');
    }
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    //{{{getHouseListByAccountId
    /**
     * 获取上架房源列表
     * @param $whereConds
     * @param array $arrFields
     * @param int $page
     * @param int $pageSize
     * @param array $orderArr
     * @return mixed
     */
    public function getHouseListByWhere($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        $arrConds = $this->getWhere($whereConds);
        if($arrConds['errorno']){
            return $arrConds;
        }
        if (count($arrFields)) {
            $this->arrFields = $arrFields;
        }
        try{
            $res = $this->objDaoUserPostList->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoUserPostList->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        } else {
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{getHouseCountByWhere
    /**
     * 获取上架房源数量
     * @param $whereConds
     * @return mixed
     */
    public function getHouseCountByWhere($whereConds){
        $arrConds = $this->getWhere($whereConds);
        if($arrConds['errorno']){
            return $arrConds;
        }
        try{
            $res = $this->objDaoUserPostList->selectByCount($arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoUserPostList->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        } else {
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}

    //{{{insertHouseRecord
    /**
     *上架房源后插入记录
     * @params $arrFields
     * @return
     */
    public function insertHouseRecord($arrFields){
        try{
            $res = $this->objDaoUserPostList->insertHouseRecord($arrFields);
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoUserPostList->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
        }
        return $this->data;
    }

    //{{{getWhere
    /**
     * 组装查询条件
     * @param $whereConds
     * @return array
     */
    protected function getWhere($whereConds){
        $arrConds = array();
        if(!empty($whereConds['account_id']) && intval($whereConds['account_id']) > 0 ){
            $arrConds['account_id ='] = $whereConds['account_id'];
        }
        if(!empty($whereConds['s_post_at'])){
            $arrConds['daynum >='] = $whereConds['s_post_at'];
        }
        if(!empty($whereConds['e_post_at'])){
            $arrConds['daynum <='] = $whereConds['e_post_at'];
        }
        if(!count($arrConds)){
            $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $data;
        }
        return $arrConds;
    }//}}}

}
