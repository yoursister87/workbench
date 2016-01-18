<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */
class Service_Data_Source_HouseCommentPrivilege{
    private $objHouseCommentPrivilege;
    protected $data;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objHouseCommentPrivilege = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseCommentPrivilege");
    }

    /**
     * 根据puid取帖子评论的特权信息。
     * $params $puid or array($puid1, $puid2, ......)
     */
    public function getHouseCommentPrivilegeInfo($puidList, $arrFields = array(), $customerId = null){
        if(is_array($puidList)){
            $puids = implode(',',$puidList);
            $arrConds[] = "puid in ( $puids )";
        }else{
            $arrConds['puid ='] = $puidList;
        }
        if($customerId){
            $arrConds['customer_id ='] = $customerId;
        }
        if(empty($arrFields)) {
            $arrFields = array('id', 'puid', 'district_id', 'street_id', 'customer_id', 'agent_Id', 'user_id');
        }
        try{
            $privilegeInfo = $this->objHouseCommentPrivilege->select($arrFields, $arrConds);
            if($privilegeInfo === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objHouseCommentPrivilege->getLastSQL());
            }
            $this->data['data'] = $privilegeInfo;
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }

    /**
     * 根据puid为帖子设置评论特权信息。
     * $params   $puidList
     */
    public function addHouseCommentPrivilegeInfo($arrFields){
        try{
            $res = $this->objHouseCommentPrivilege->insert($arrFields);
            if($res === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE, ErrorConst::E_DB_FAILED_MSG."sql :".$this->objHouseCommentPrivilege->getLastSQL());
            }
            $this->data['data'] = $res;
        }catch(Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }

    /**
     * 根据puid为帖子删除评论特权信息。
     * $params $array(puid1,puid2)或者$puid
     */
    public function deleteHouseCommentPrivilegeInfo($puidList){
        if(is_array($puidList)){
            $puids = implode(',',$puidList);
            $arrConds[] = "puid in ( $puids )";
        }else{
            $arrConds['puid ='] = $puidList;
        }
        try{
            $res = $this->objHouseCommentPrivilege->deleteByPuid($arrConds);
            if($res === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objHouseCommentPrivilege->getLastSQL());
            }
            $this->data['data'] = $res;
        }catch(Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
}