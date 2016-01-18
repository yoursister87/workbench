<?php

/**
 * @package
 * @subpackage
 * @brief                $Wap客源项目需求对外接口$
 * @file                 WapProjectKeyuan.class.php
 * @author               wanyang:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-25
 * @lastmodified         下午6:54
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Wap_WapProjectKeyuan
{
    protected $keyuanDaoObj;
    protected $type = array(
        1 => 'domain,district,street,price,huxing_shi',
    );

    protected static $allowedMajorCategory = array('1');
    protected static $allowedType = array('1');
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function  __construct(){
        $this->keyuanDaoObj = Gj_LayerProxy::getProxy('Dao_Wap_WapProjectKeyuan');
    }

    /**
     * @brief 根据subtype准备入库的conditions字段
     * @param $data
     * @return bool
     */
    protected function getReadyPatternByType($data)
    {
        try {
            $type = empty($data['type']) ? 1 : $data['type'];
            $condsPattern = explode(",", $this->type[$type]);
            foreach ($condsPattern as $key => $value) {
                $data['conditions'][$value] = $data[$value];
            }
            $data['major_category'] = in_array($data['major_category'], self::$allowedMajorCategory)?$data['major_category']:1;
            $data['type'] = in_array($data['type'], self::$allowedType)?$data['type']:1;
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @brief 参数为空检查，否则抛出异常
     * @param $params
     * @return bool
     * @throws Gj_Exception
     */
    protected function checkParams($params){
        foreach($params as $key => $value){
            if($value === null){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
        }
        return true;
    }
    /**
     * @brief 根据条件获取客源数据
     * @param $params
     * @return array
     */
    public function getKeyuanData($params)
    {
        try {
            $arrRet = $this->arrRet;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 更新用户的客源数据
     * @param $params
     * @return array
     */
    public function updateKeyuanData($params)
    {
        try {
            $arrRet = $this->arrRet;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 新增客源需求数据
     * @param $params
     * @return array
     */
    public function addKeyuanData($params)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams($params);
            $fdata = $this->getReadyPatternByType($params);
            if(!$this->validateInsertAbilityByTime($fdata['phone'])){
                throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
            }
            $this->keyuanDaoObj->insertKeyuanData($fdata);
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 根据同一用户的需求更新时间间隔要求给出能否继续发需求的判断，暂定1h
     * @param $phone
     * @param $time seconds
     * @return bool
     */
    protected function validateInsertAbilityByTime($phone, $time=3600){
        $this->checkParams(array($phone, $time));
        $data = $this->keyuanDaoObj->selectKeyuanData($phone, array('create_time'), 1, 1);
        if( $data[0]['create_time']  && (time() - $data[0]['create_time'] < $time) ){
            return false;
        }
        return true;
    }
}
