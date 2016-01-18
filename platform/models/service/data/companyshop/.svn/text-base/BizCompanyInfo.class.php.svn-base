<?php
/*
 * File Name:CompanyHouseInfo.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Data_CompanyShop_BizCompanyInfo
{
    protected $objUtil = null;
    protected $result = null;

    public function __construct(){
        //$this->objUtil = Gj_LayerProxy::getProxy('Util_MsCrmAdPostApp')->getObj();

        $obj = new Util_MsCrmAdPostApp();
        $this->objUtil = $obj->getObj();

        $this->result['data'] = array();
        $this->result['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->result['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    public function __set($name,$val) {
        $this->$name = $val;
    }


    /* {{{getAllBizCompanyList 获取名企公司列表*/
    /**
     * @brief
     * @params cityId  城市id
     * @params majorCategoryId null 为不限
     * @params districtId  区域id  0 为不限
     * @params streetId  街道id  0 为不限
     * @returns
     */
    public function getAllBizCompanyList($cityId,$majorCategoryId = null,$districtId = 0,$streetId = 0){
        if (!is_numeric($cityId)  ||!is_numeric($districtId) ||!is_numeric($streetId)) {
            $this->result['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->result['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->result;
        }
        $params = array(
            'c' => $cityId,
            'ca' => '7', // fang
            'pt' => '3', // page type, 3 - list #类别列表页
            'mac' => $majorCategoryId,
            //'d' =>  $districtId, 
            //'s' =>  $streetId,
        );
        //删除类别搜索条件
        if ($majorCategoryId === null) {
            unset($params['mac']);
        }
        $tfkey = 'housing_ppzq'; 
        //返回结果详情 参考 http://wiki.corp.ganji.com/%E9%A6%96%E9%A1%B5/%E8%BF%90%E8%90%A5%E5%B9%B3%E5%8F%B0/CRM%E5%94%AE%E4%B8%AD%E5%B0%8F%E7%BB%84/open-api/advert-render
        $ret = $this->objUtil->getCrmAdPost(array('housing_ppzq'), $params);
        $companyList = array();
        if(!empty($ret[$tfkey]) && is_array($ret[$tfkey])){
            foreach ($ret[$tfkey] as $item) {
            //广信息填入
            $companyId = $item['ExtInfo']['companyid'];
            //取公司信息
            $companyList[$companyId] = $item['ExtInfo'];
            }
        }
        /**
         * 结果返回
         * adid
         * companyid 
         * companyfullname 公司全名
         * companyname 公司简称
         * companylogopicurl 公司logo
         * companybriefintroduction  公司简介
         * logopictype
         * 
         */
        $this->result['data'] = $companyList;
        return $this->result;
    }
    /*}}}*/
    
    /* {{{getBizCompanyByCompanyId 获取名企公司信息*/
    /**
     * @brief
     * @parmas companyId 公司Id
     * @params cityId  城市id
     * @params majorCategoryId
     * @params districtId  区域id  0 为不限
     * @params streetId  街道id  0 为不限
     * @returns
     */
    public function getBizCompanyByCompanyId($companyId,$cityId,$majorCategoryId = null,$districtId = 0,$streetId = 0){
        if (!is_numeric($companyId)) {
            $this->result['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->result['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->result;
        }
        $ret = $this->getAllBizCompanyList($cityId,$majorCategoryId,$districtId,$streetId);
        if ($ret['errorno'] != ErrorConst::SUCCESS_CODE){
            return $ret;
        }
        if (isset($ret['data'][$companyId])) {
            $ret['data'] = $ret['data'][$companyId];
            return $ret;
        } else {
            $this->result['data'] = array();
            return $this->result;
        }
    }
    /*}}}*/
}
