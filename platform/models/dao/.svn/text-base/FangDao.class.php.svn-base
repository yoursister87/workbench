<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
* @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_FangDao
{

    //主库句柄
    protected $dbMaster = FALSE;

    //从库句柄
    protected $dbSlave = FALSE;

    //数据库别名&库名
    protected $dbNameAlias  = null;
    protected $dbName       = null;

    public function __construct(){
        $this->setDbConf();
    }

    //获取主库句柄函数
    protected function getMasterDbHandle() {
        if ( false === ($this->dbMaster instanceof DBHandle) ) {
            $this->dbMaster = DBMysqlNamespace::createDBHandle2($this->dbMasterConf, $this->dbName);
        }
        if ($this->dbMaster === FALSE) {
            throw new Exception(ErrorConst::E_DB_CONNECT_MSG, ErrorConst::E_DB_CONNECT_CODE);
        }
        return $this->dbMaster;
    }

    //获取从库句柄函数
    protected function getSlaveDbHandle() {
        if ( false === ($this->dbSlave instanceof DBHandle) ) {
            $this->dbSlave = DBMysqlNamespace::createDBHandle2($this->dbSlaveConf, $this->dbName);
        }

        if ($this->dbSlave === FALSE) {
            throw new Exception(ErrorConst::E_DB_CONNECT_MSG, ErrorConst::E_DB_CONNECT_CODE);
        }
        return $this->dbSlave;
    }

    protected function parseFields($fields) {
        if (is_array($fields) && count($fields) > 0) {
            return implode(',', $fields);
        } else {
            return '*';
        }
    }
    public function setSlaveDbHandle($db){
        $this->dbSlave = $db;
    }
    public function setMasterDbHandle($db){
        $this->dbMaster = $db;
    }
    public function setTableName($tableName){
        $this->tableName = $tableName;
    }
    /* {{{ __get */
    /**
     * @brief 
     *
     * @param $name
     *
     * @returns   
     * @codeCoverageIgnore
     */
    public function __get($name){
        return $this->$name;
    }//}}}
    /* {{{ setDbConf */
    /**
     * @brief 
     *
     * @param $database
     *
     * @returns   
     */
    protected  function setDbConf(){
        switch ($this->dbNameAlias) {
        case 'house_premier_arch':
            $this->dbMasterConf = DBConfig::$SERVER_HOUSING_PIGEONHOLE_MASTER;
            $this->dbSlaveConf = DBConfig::$SERVER_HOUSING_PIGEONHOLE_SLAVE;
            break;
        case 'xiaoqu':
            $this->dbMasterConf = DBConfig::$SERVER_FANG_MASTER;
            $this->dbSlaveConf = DBConfig::$SERVER_FANG_SLAVE;
            break;
        case 'crm':
            $this->dbMasterConf = DBConfig::$SERVER_CRM_MASTER;
            $this->dbSlaveConf = DBConfig::$SERVER_CRM_SLAVE;
            break;
        case 'house_report':
            $this->dbMasterConf = DBConfig::$SERVER_REPORT_MASTER;
            $this->dbSlaveConf =  DBConfig::$SERVER_REPORT_SLAVE;
            break;
        default:
            $this->dbMasterConf = DBConfig::$SERVER_HOUSING_PREMIER_MASTER;
            $this->dbSlaveConf = DBConfig::$SERVER_HOUSING_PREMIER_SLAVE;
            break;
        }
    }//}}}
}
