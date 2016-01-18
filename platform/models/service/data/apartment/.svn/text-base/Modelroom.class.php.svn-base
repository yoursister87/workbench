<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: zhangrong3 <zhangrong3@ganji.com>$
 * @author               $Author: zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Data_Apartment_Modelroom
{
    /* {{{getModelroomByApartmentId*/
    /**
     * @brief 
     *
     * @param $apartmentId
     * @param $num
     *
     * @returns   
     */
    public function getModelroomByApartmentId($apartmentId, $num, $pageNo=1){
        try {
            if (!is_numeric($apartmentId) || $apartmentId <= 0) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            if (!is_numeric($num) || $num <= 0) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            $apartmentModel = Gj_LayerProxy::getProxy('Service_Data_Apartment_Info'); 
            $res = $apartmentModel->getApartmentInfoById($apartmentId);
            if (ErrorConst::SUCCESS_CODE != $res['errorno'] || empty($res['data'])) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            $model = $this->getModel($res['data']['city']);
            $res = $model->getModelroomByApartmentId($apartmentId, $num, $pageNo);
            return array(
                'data' => $res,
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
            );
        } catch (Exception $e) {
            return array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
    }//}}}
    /*{{{getModelroomBySqlParams*/
    /**
     * @param string $domain
     * @param string $fields
     * @param array  $filter
     * @param string $appends
     * @return array
     */
    public function getModelroomBySqlParams($domain, $fields = null, $conds = null, $appends = null){
        try {
            if (empty($domain)) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            $model = $this->getModel($domain);
            if (empty($fields)){
                $fields = "*";
            }
            $data = $model->getModelroomBySqlParams($fields, $conds, null, $appends);
            return array(
                'data' => $data,
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
            );
        }  catch (Exception $e) {
            return array(
                'errorno' => $e->getCode(),
                'msg' => $e->getMessage()
            );
        }
    }
    /*}}}*/
    /* {{{getModelroomByParams*/
    /**
     * @brief 
     *
     * @param $params
     * @param $num
     *
     * @returns   
     */
    public function getModelroomByParams($params, $num, $pageNo=1){
        try {
            if (!is_numeric($num) || $num <= 0) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            $model = $this->getModel($params['queryFilter']['city_domain']);
            // params 序列化
            $params = $this->serializeParams($params);
            if (false === $params) {
                $res = array();
            } else {
                $res = $model->getModelroomByParams($params, $num, $pageNo);
            }
            return array(
                'data' => $res,
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
            );
        } catch (Exception $e) {
            return array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
    }//}}}
    /* {{{getRecommendModelroom*/
    /**
     * @brief 
     *
     * @param $puid
     * @param $num
     *
     * @returns   
     */
    public function getRecommendModelroom($puid, $num){
        try {
            $obj = Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery');
            $info = $obj->getHouseSourceByPuidInfo($puid);
            $apartmentId = $info['data']['apartment_id'];
            $res= $this->getModelroomByApartmentId($apartmentId, $num+1);
            // 多取出一个，如果其中包含当前这个，则去掉, 否则去掉最后一个
            if (!empty($res['data']) && is_array($res['data'])) {
                $unset = false;
                foreach ($res['data'] as $index => $modelroom) {
                    if ($puid == $modelroom['puid']) {
                        unset($res['data']['index']);
                        $unset = true;
                        break;
                    }
                }
                if (false == $unset) {
                    array_pop($res['data']);
                }
            }
            return array(
                'data' => $res['data'],
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
            );
        } catch (Exception $e) {
            return array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
    }//}}}
    /* {{{getModel*/
    /**
     * @brief 
     *
     * @param $cityDomain
     *
     * @returns   
     */
    protected function getModel($cityDomain){
        $cityInfo = GeoNamespace::getCityByDomain($cityDomain);
        return Gj_LayerProxy::getProxy('Dao_Fang_HouseSourceModelroom', $cityInfo['database']);
    }//}}}
    /* {{{serializeParams*/
    /**
     * @brief 
     *
     * @param $params
     *
     * @returns   
     */
    protected function serializeParams($params){
        $retParams = array();
        if (isset($params['queryFilter']['fang_xing']) && 4 != $params['queryFilter']['fang_xing']) {
            return false;
        }
        if (isset($params['queryFilter']['zhuangxiu']) && 5 == $params['queryFilter']['zhuangxiu']) {
            return false;
        }
        if (!empty($params['queryFilter']['company_id'])) {
            return false;
        }
        $validParams = array('district_id' => null, 'street_id' => null, 'price' => 'between', 'ceng' => 'between', 'chaoxiang' => null, 'tab_system' => 'in', 'huxing_shi' => null, 'huxing_ting' => null, 'huxing_wei' => null);
        $rentModel = Gj_LayerProxy::getProxy('Dao_Xapian_Zufang');
        $filter = $rentModel->getFilterList($params);
        foreach ($filter as $key => $value) {
            if (!array_key_exists($key, $validParams)) 
                continue;
            if (is_array($value['value']) && 'between' == $validParams[$key]) {
                $retParams[] = "{$key} between {$value['value'][0]} and {$value['value'][1]}";
            } else if (is_array($value['value']) && 'in' == $validParams[$key]) {
                $inSet = join(',', $value['value']);
                $retParams[] = "{$key} in ({$inSet})";
            } else {
                $retParams[] = "{$key}={$value['value']}";
            }
        }
        // 设置一个恒成立的条件，为了防止没有筛选条件的时候不能返回结果
        $retParams[1] = 1;
        return $retParams;
    }
    //}}}
}

