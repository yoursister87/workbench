<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Dao_Xiaoqu_XiaoquLock extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = 'xiaoqu_lock';
    protected $table_fields = array(
        'id',
        'xiaoqu_id',
        'table',    //待锁定表 1:xiaoqu_xiaoqu 2:xiaoqu_peitao
        'field',    //待锁定字段 如district_id
        'status',   //当前记录是否失效 0:失效 1:正常
        'add_time',
        'add_user',
        'add_reason',
        'last_modify_time'
    );
    
    /**getXiaoquLockInfoByXiaoquId{{{*/
    /**
     * @param  int $xiaoquId   小区id
     * @param  int $tableType   待锁定表 1:xiaoqu_xiaoqu 2:xiaoqu_peitao
     * @param  string $filed  待锁定字段,如district_id
     * @return array()
     */
    public function getXiaoquLockInfoByXiaoquId($xiaoquId, $tableType = 1, $filed = null) {
        $result = array();
        if (!empty($xiaoquId) && is_numeric($xiaoquId)) {
            $arrConds = array('`xiaoqu_id` = ' => $xiaoquId, '`table` = ' => $tableType);
            if (!empty($filed)) {
                $arrConds['`field` = '] = $filed;
            }
            $arrConds['`status` = '] = 1;
            $result = $this->select('*', $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getXiaoquLockInfoByXiaoquId " . json_encode($arrConds), ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }
}
