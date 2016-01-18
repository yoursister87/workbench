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
 * @copyright Copyright (c) 2015, www.ganji.com 
 */ 

class Dao_Xiaoqu_XiaoquPriceTrendData extends Gj_Base_MysqlDao
{
    //该表只有wap端，app端用 
    //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xiaoqu_price_trend_data";
    //{{{ getXiaoquRealImagePath 获取小区走势图信息
    public function getXiaoquTrendInfo($xiaoquId) {
        $xiaoquId = intval($xiaoquId);
        if(empty($xiaoquId)){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        //fields 
        $fields = array('url','data');
        //conds
        $conds = array('xiaoqu_id= ' =>$xiaoquId);
        //appends 
        $appends = array(' LIMIT 1');
        
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
		return $result;
    }//}}}
}
