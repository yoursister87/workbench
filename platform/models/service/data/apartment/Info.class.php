<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: liuzhen1 <liuzhen1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Data_Apartment_Info
{
    protected $successData = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    
    /** {{{ 根据城市和拼音获取公寓信息 
     * @param  string $city      城市domain
     * @param  string $pinyin    小区拼音
     * @param  array  $arrFields 要获取的列
     * @return array            小区信息数组
     */
    /*
    public function getApartmentInfoByCityPinyin($city, $pinyin, $arrFields=array())
    {
        try {
            if (empty($arrFields) || !is_array($arrFields)) {
                $arrFields = array('*');
            }
            $arrConds = array('city=' => $city, 'pinyin=' => $pinyin);
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_Apartment');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res[0];
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } */
    //}}}
    
    /** {{{ 根据公寓id获取公寓信息
     * @param  int   $id        公寓id
     * @param  array $arrFields 要查询的列
     * @return array
     */
    public function getApartmentInfoById($id, $arrFields=array())
    {
        try {
            $id = intval($id);
            if (empty($arrFields) || !is_array($arrFields)) {
                $arrFields = array('*');
            }
            $arrConds = array('id=' => $id);
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_Apartment');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res[0];
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
 
    /** {{{ 根据公司ID获取相同(不同)公司的公寓列表
     * @param  int   $companyId     公司id
     * @param  bool  $isSameCompany 是否为相同公司
     * @param  array $arrFields     要查询的列
     * @return array
     */
    public function getApartmentListByCompanyId($companyId, $isSameCompany=true, $arrFields=array())
    {    
        try {
            $companyId = intval($companyId);
            if (empty($arrFields) || !is_array($arrFields)) {
                $arrFields = array('*');
            }
            if ($isSameCompany == true) {
                $arrConds = array('company_id=' => $companyId);
            } else {
                $arrConds = array('company_id!=' => $companyId);
            }
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_Apartment');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res;
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    
    /** {{{ 根据locationId和投放类型获取公寓信息
     * @param  int    $locationId 位置id
     * @param  int    $type       投放类型1:街道2:区域3:城市
     * @return array
     */
    public function getApartmentByAdLocation($locationId, $type=1, $arrFields=array())
    {
        try {
            $locationId = intval($locationId);
            $type = intval($type);
            if (empty($arrFields) || !is_array($arrFields)) {
                $arrFields = array('apartment_id');
            }
            $arrConds = array('location_id=' => $locationId, 'type=' => $type);
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_ApartmentAdLocation');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res[0];
        } catch (Exception $e) {    
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}

}
