<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class Dao_Xiaoqu_XiaoquStat extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xiaoqu_stat";
    protected $table_fields = array(
        'id',
        'xiaoqu_id',
        'transport_cnt',    //交通数目
        'school_cnt',       //教育数目
        'dining_cnt',       //餐饮数目
        'hospital_cnt',     //医院数目
        'bank_cnt',         //银行数目
        'shop_cnt',         //超市数目
        'avg_price',        //均价
        'avg_price_change', //均价环比浮动百分比
        'popularity',       //热门度
        'words_icon',       //文字标签
        'rent_cnt',         //租房帖子数目
        'sell_cnt',         //二手房帖子数目
        'share_cnt',        //合租房贴子数目
        'xiaoqu_bao_cnt',   //小区宝数目
        'modified_time',    //修改时间
        'stat_info'
    );

    /**getXiaoquStatInfoByXiaoquId 根据小区ids数组返回要查询的字段{{{*/
    /**
     * @param  array  $xiaoquIds [小区id数组]
     * @param  array  $fields    [要查询的字段]
     * @return array
     */
    public function getXiaoquStatInfoByXiaoquId($xiaoquIds, $fileds = array()) {
        if (empty($xiaoquIds) || !is_array($xiaoquIds) || !is_array($fileds)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (empty($fileds)) {
            $fileds = $this->table_fields;
        }
        $xiaoquIds = implode(',', $xiaoquIds);
        $arrConds = array('xiaoqu_id in (' . $xiaoquIds . ' )');
        $result = $this->select($fileds, $arrConds);
        if ($result === FALSE) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
}
