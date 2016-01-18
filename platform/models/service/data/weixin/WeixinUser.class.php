<?php
/**
 * @package              
 * @subpackage           
 * @brief                $$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @file                 WeixinUser.class.php$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Weixin_WeixinUser
{
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    protected $model;

    public function __construct(){
        $this->model = Gj_LayerProxy::getProxy("Dao_Weixin_WeixinUser");
    }

    public function getUserNameByOpenid($openid = null){
        try{
            $arrRet = $this->arrRet;
            if($openid == null){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
            $userInfo = $this->model->getUserInfo($openid);
            if(!$userInfo){
                throw new Gj_Exception(ErrorConst::E_DATA_NOT_EXIST_CODE, ErrorConst::E_DATA_NOT_EXIST_MSG);
            }
            $arrRet['data'] = $userInfo['nickname'];
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;

    }
}