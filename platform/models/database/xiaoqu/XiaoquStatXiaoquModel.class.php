<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   xiaowenjie$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class XiaoquStatXiaoquModel extends BaseXiaoquModel
{
    protected $_tableName = 'xiaoqu_stat';
    protected $_tableFileds = array(
            'xiaoqu_id',
            'transport_cnt',   //交通数目
            'school_cnt',    //教育数目
            'dining_cnt',    //餐饮数目
            'hospital_cnt',    //医院数目
            'bank_cnt',    //银行数目
            'shop_cnt',    //超市数目
            'avg_price',    //均价
            'avg_price_change',    //均价环比浮动百分比
            'popularity',    //热门度
            'words_icon',   //文字标签
            'rent_cnt',    //租房帖子数目
            'sell_cnt',    //二手房帖子数目
            'share_cnt',    //合租房贴子数目
            'xiaoqu_bao_cnt',    //小区宝数目
            'modified_time',
            'stat_info'
    );
    public function getXiaoquStatInfoById($id, $fileds = array()){
        if (is_numeric($id) && is_array($fileds)) {
            $filedsStr = $this->parseFields($fileds);
            $sql = 'select ' . $filedsStr . ' from ' . $this->_tableName . ' where xiaoqu_id =' . $id;
            $dbHandle = $this->getSlaveDbHandle();
            return $dbHandle->getAll($sql);
        }
        return false;
    }

    /**
     * [getXiaoquStatInfoByXiaoquId 根据小区ids数组返回要查询的字段]
     * @param  array  $xiaoquIds [小区id数组]
     * @param  array  $fields    [要查询的字段]
     * @return array
     */
    public function getXiaoquStatInfoByXiaoquId($xiaoquIds, $fields=array()) {
        if (empty($xiaoquIds) || !is_array($fields)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $queryFields = $this->parseFields($fields);
        $xiaoquIds = implode(',', $xiaoquIds);
        $sql = "SELECT {$queryFields} FROM {$this->_tableName} WHERE xiaoqu_id IN ($xiaoquIds)";
        $dbSlave = $this->getSlaveDbHandle();
        $result = $dbSlave->getAll($sql);
        if ($result === FALSE) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }
} 


