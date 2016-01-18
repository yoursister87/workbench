<?php

/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   kongxiangshuai <kongxiangshuai@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_ImageOuter
{
    protected $data = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function __construct()
    {
        $this->objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseImageOuter");
    }
    /**
     * 新增图片信息
     * @param $arrRow
     * @param $strDbName
     * @param $strTableName
     * @return array
     */
    public function insertImageInfo($arrRow)
    {
        if (!is_array($arrRow)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
        } else {
            try {
                $this->objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseImageOuter");
                $arrRes = $this->objDao->insert($arrRow);
                if($arrRes ===false){
                    throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
                }
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;
    }

    public function delImageInfo($arrRow)
    {
        if (!is_numeric($arrRow) && !is_array($arrRow)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            $this->data['data'] = false;
        } else {
            try {
                $arrRes = $this->objDao->delete($arrRow);
                if ($arrRes === false) {
                    throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE, 'delete failed');
                }
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;
    }

    public function updateImageInfo($arrRow,$whereConds)
    {
        $arrConds = $this->getImageWhere($whereConds);
        try {
            $arrRes = $this->objDao->update($arrRow,$whereConds);
            if ($arrRes === false) {
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE, 'delete failed');
            }
        } catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
    /**
     * 组装查询条件
     * @codeCoverageIgnore
     * @param unknown $whereConds
     * @return multitype:number unknown
     */
    protected function getImageWhere($whereConds)
    {
        $arrConds = array();

        if (isset($whereConds['house_id']) && is_numeric($whereConds['house_id'])) {
            $arrConds['house_id ='] = $whereConds['house_id'];
        }
        if (!empty($whereConds['house_id'])) {
            if (is_array($whereConds['house_id'])) {
                $house_ids = implode(',', $whereConds['house_id']);
                $arrConds[] = "house_id IN ({$house_ids})";
            }
        }

        if (!empty($whereConds['type'])) {
            $arrConds['type ='] = $whereConds['type'];
        }
        if (!empty($whereConds['is_cover'])) {
            $arrConds['is_cover ='] = $whereConds['is_cover'];
        }
        if (!empty($whereConds['category'])) {
            $arrConds['category ='] = $whereConds['category'];
        }
        if(!empty($whereConds['image'])){
            $arrConds['image ='] = $whereConds['image'];
        }
        if(!empty($whereConds['status'])){
            $arrConds['status ='] = $whereConds['status'];
        }
        return $arrConds;
    }

    /**
     * @param $whereConds
     * @param array $arrFields
     * @param int $page
     * @param int $pageSize
     * @param array $orderArr
     * @return array
     */
    public function getImageListByHouseId($whereConds, $arrFields=array('*'))
    {
        if (!is_numeric($whereConds['house_id']) || !is_numeric($whereConds['type'])) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $arrConds = $this->getImageWhere($whereConds);

        try{
            $res = $this->objDao->selectGroupbyConds($arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }

        return $this->data;
    }

}
