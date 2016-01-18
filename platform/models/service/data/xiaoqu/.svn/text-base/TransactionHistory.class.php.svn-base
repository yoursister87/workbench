<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: liuzhen1 <liuzhen1@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com
 * @codeCoverageIgnore
 */ 

class Service_Data_Xiaoqu_TransactionHistory
{
    /* getXiaoquDealHistoryTrendByMonth 获取小区成交历史趋势 {{{ */
    /**
     * 获取小区成交历史趋势
     * @codeCoverageIgnore
     */
    public function getXiaoquDealHistoryTrendByMonth($xiaoquId, $recentMonth)
    {
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquDealHistory');
            $dealPriceArr = $model->getDealHistoryAvgPriceByMonth($xiaoquId, $recentMonth);
            $newDealPriceArr = array();
            foreach ($dealPriceArr as $row) {
                $newDealPriceArr[$row['mon']] = $row;
            }
            $dealNumArr = $model->getDealHistoryCountByMonth($xiaoquId, $recentMonth);
            $newDealNumArr = array();
            foreach ($dealNumArr as $row) {
                $newDealNumArr[$row['mon']] = $row;
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('deal_price'=>$newDealPriceArr, 'deal_num'=>$newDealNumArr),
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}    
    /* getXiaoquDealHistoryCountByHuxing 获取小区每个户型的成交数量 {{{ */
    /**
     * 获取小区每个户型的成交数量
     * @codeCoverageIgnore
     */
    public function getXiaoquDealHistoryCountByHuxing($xiaoquId, $recentMonth)
    {
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquDealHistory'); 
            $result = $model->getDealHistoryCountByHuxing($xiaoquId, $recentMonth);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /* getXiaoquDealHistory 获取小区成交历史数据 {{{ */
    /**
     * 获取小区成交历史数据 
     * @param  int     $xiaoquId 小区id
     * @param  array   $fields   要查询的字段
     * @param  integer $offset   偏移量
     * @param  integer $limit    限制的条数
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getXiaoquDealHistory($xiaoquId, $fields=array(), $offset=0, $limit=20)
    {
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquDealHistory');
            $totalCount = $model->getXiaoquDealHistoryTotalCount($xiaoquId);
            $items = $model->getXiaoquDealHistoryData($xiaoquId, $fields, $offset, $limit);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$items, 'total'=>$totalCount),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
}
