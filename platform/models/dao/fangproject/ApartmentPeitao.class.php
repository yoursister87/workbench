<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: liuzhen1 <liuzhen1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 * @codeCoverageIgnore
 */
class Dao_Fangproject_ApartmentPeitao extends Gj_Base_MysqlDao
{
    protected $dbNameAlias = 'fang';
    protected $dbName = 'fang_project';
    protected $tableName = 'apartment_peitao';
    protected $table_fields = array('id','apartment_id','status','type','content');

    /** {{{ 重写基类的select方法
     * @param array $arrFields
     * @param array $arrConds
     * @return array
     */
    public function select($arrFields, $arrConds)
    {
        if (!$arrFields = $this->objMysqlUtil->checkSelectFields($arrFields,$this->table_fields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        //需要按照类别排序
        $appends = array('ORDER BY type ASC');
        $ret = $this->dbHandler->select($this->tableName, $arrFields, $arrConds, null, $appends);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        Gj_Log::debug($this->getLastSQL());
        return $ret;
    }//}}}
}
