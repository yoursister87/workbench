<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: renyajing <renyajing@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 */ 

class Dao_Xiaoqu_XiaoquPeitaoV2 extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = 'xiaoqu_peitao_v2';
    protected $table_fields = array(
        'id',
        'xiaoqu_id',
        'major_type',
        'type',
        'content',
        'create_time'
    );

    public function getXiaoquPeitaoByXiaoquId($xiaoquId, $majorType = null, $type = null){
        $result = array();
        if (is_numeric($xiaoquId)) {
            $arrConds = array('xiaoqu_id = ' => $xiaoquId);
            if (!empty($majorType)) {
                $arrConds['major_type = '] = $majorType;
            }
            if (!empty($type)) {
                $arrConds['type = '] = $type;
            }
            $result = $this->select($this->table_fields, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getXiaoquPeitaoByXiaoquId " . json_encode($arrConds), ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . "xiaoquId为空或类型错误", ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }
}

