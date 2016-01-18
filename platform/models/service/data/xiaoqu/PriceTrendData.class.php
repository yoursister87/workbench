<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: liuduanyang <liuduanyang@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 */ 

class Service_Data_Xiaoqu_PriceTrendData
{
    /*{{{getDistrictSellPriceTrend 根据小区id获取小区走势图url*/
    public function getXiaoquRealImagePath($xiaoquId) {
        try {
            if(empty($xiaoquId)){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPriceTrendData');
            $items = $model->getXiaoquTrendInfo($xiaoquId);
            if(!empty($items[0]['url'])){
                $data  = $items[0]['url'];
            }
        } catch(Exception $e) {
            $data = '';
        }
        return $data;
    }
    /*}}}*/
    
    /*{{{getXiaoquPriceTrendData 根据小区id获取小区走势数据*/
    public function getXiaoquPriceTrendData($xiaoquId) {
        $data = array();
        try {
            if(empty($xiaoquId)){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }   
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPriceTrendData');
            $items = $model->getXiaoquTrendInfo($xiaoquId);
            if (!empty($items[0])) {
                $data = $items[0];
                $data['data'] = @json_decode($data['data'], true);
                $data['data'] = is_array($data['data']) ? $data['data'] : array();
                //获取最新月份的均价
                $data['cur'] = end($data['data']);
            }
        } catch(Exception $e) {
            $data = array();
        }   
        return $data;
    }   
    /*}}}*/

    
}
