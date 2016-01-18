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
 */ 

class Dao_Xiaoqu_XiaoquDealHistory extends Gj_Base_MysqlDao
{
    protected $dbNameAlias = 'fang';
    protected $dbName = 'xiaoqu';
    protected $xiaoqu = 'xiaoqu';
    protected $tableName = 'xiaoqu_deal_history';

    //获取成交历史总数
    public function getXiaoquDealHistoryTotalCount($xiaoquId)
    {
        $xiaoquId = intval($xiaoquId);
        if ($xiaoquId == 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        //fields
        $fields = array('COUNT(1) total');
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);    
        $result = $this->dbHandler->select($this->tableName, $fields, $conds);
        $totalCount = intval($result[0]['total']);
        return $totalCount;
    }
    //获取成交历史记录
    public function getXiaoquDealHistoryData($xiaoquId, $fields, $offset, $limit)
    {
        $xiaoquId = intval($xiaoquId);
        $offset = intval($offset);
        $limit = intval($limit);
        //判断参数是否合法
        if ($xiaoquId == 0 || $limit == 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG."xiaoquId和limit不能等于0", ErrorConst::E_PARAM_INVALID_CODE);
        }
        //fields
        if (!is_array($fields)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        } elseif (is_array($fields) && count($fields) > 0) {
            $fields = array('*');
        }
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);
        //appends
        $appends = array("ORDER BY deal_date DESC", "LIMIT $offset,$limit");
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
        return $result;
    }

    //每个月的成交均价
    public function getDealHistoryAvgPriceByMonth($xiaoquId, $recentMonth)
    {
        if (empty($xiaoquId) || empty($recentMonth)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $dateArr = $this->getStartEndDateByMonth($recentMonth);
        $startDate = $dateArr['start_date'];
        $endDate = $dateArr['end_date'];
        //fields
        $fields = array('SUBSTR(deal_date, 1, 7) mon', 'ROUND(AVG(deal_price)) avg_price');
        //conds
        $conds = array('deal_date>=' => $startDate, 'deal_date<=' => $endDate, 'xiaoqu_id=' => $xiaoquId);
        //appends
        $appends = array('GROUP BY mon', 'ORDER BY mon ASC');
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
        return $result;
    }

    //每个月的成交套数
    public function getDealHistoryCountByMonth($xiaoquId, $recentMonth)
    {
        if (empty($xiaoquId) || empty($recentMonth)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        } 
        $dateArr = $this->getStartEndDateByMonth($recentMonth);
        $startDate = $dateArr['start_date'];
        $endDate = $dateArr['end_date'];
        //fields
        $fields = array('SUBSTR(deal_date, 1, 7) mon', 'COUNT(1) num');
        //conds
        $conds = array('deal_date>=' => $startDate, 'deal_date<=' => $endDate, 'xiaoqu_id=' => $xiaoquId);
        //appends
        $appends = array('GROUP BY mon', 'ORDER BY mon ASC');
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends); 
        return $result;
    }

    //某段时间之内每个户型的成交数量
    public function getDealHistoryCountByHuxing($xiaoquId, $recentMonth)
    { 
        if (empty($xiaoquId) || empty($recentMonth)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        } 
        $dateArr = $this->getStartEndDateByMonth($recentMonth);
        $startDate = $dateArr['start_date'];
        $endDate = $dateArr['end_date'];
        //fields
        $fields = array('SUBSTR(huxing, 1, 1) huxing', 'COUNT(1) num');
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId, 'deal_date>=' => $startDate, 'deal_date<=' => $endDate);
        //appends
        $appends = array('GROUP BY huxing', 'HAVING huxing>0', 'ORDER BY huxing ASC');
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
        return $result;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getStartEndDateByMonth($recentMonth)
    {
		$currentDay = date('d');
        if ($currentDay >= 15) {
            //近几个月的开始时间
			$startDate = date('Y-m-01', strtotime(date('Y-m-15')) - 86400*30*($recentMonth-1));
            //结束时间
			$endDate = date('Y-m-d');
        } else {  
            //近几个月的开始时间
			$startDate = date('Y-m-01', strtotime(date('Y-m-15')) - 86400*30*($recentMonth)); 
            //结束时间，上个月的最后一天
			$endDate = date('Y-m-d', strtotime(date('Y-m-01')) - 3600);
        }
        
        return array('start_date'=>$startDate, 'end_date'=>$endDate);
	}
}
