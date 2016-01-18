<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: liuzhen1 <liuzhen1@ganji.com>$
 * @author               $Author: zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Data_Apartment_Peitao
{
    protected $successData = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    
    /** {{{ 根据公寓id获取公寓配套
     * @param int $apartmentId 公寓id
     * @param array $arrFields 要获取的列
     * @param int $status      状态1为有效记录-1为无效记录0为全部
     * @return array 
     */
    public function getApartmentPeitaoByApartmentId($apartmentId, $arrFields=array(), $status=1)
    {
        try {
            $apartmentId = intval($apartmentId);
            if (empty($arrFields) || !is_array($arrFields)) {
                $arrFields = array('*');
            }
            $status = intval($status);
            $arrConds = array('apartment_id=' => $apartmentId);
            if (!empty($status)) {
                $arrConds = array_merge($arrConds, array('status=' => $status));
            }
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_ApartmentPeitao');
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
}
