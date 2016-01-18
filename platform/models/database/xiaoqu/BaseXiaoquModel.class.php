<?php

require_once GANJI_CONF . '/DBConfig.class.php';
require_once CODE_BASE2 . '/util/db/DBMysqlNamespace.class.php';

class BaseXiaoquModel
{
	
	//主库句柄
	protected $dbMaster = FALSE;

	//从库句柄
	protected $dbSlave = FALSE;
	
	//数据库名
    protected $dbName = 'xiaoqu';
    
    //获取主库句柄函数
	protected function getMasterDbHandle() {
		$retry = 0;
		do {
			$this->dbMaster = DBMysqlNamespace::createDBHandle2(DBConfig::$SERVER_MS_MASTER, $this->dbName);
			$retry++;
		} while ($this->dbMaster === FALSE && $retry < 3);
		if ($this->dbMaster === FALSE) {
			throw new Exception(ErrorConst::E_DB_CONNECT_MSG, ErrorConst::E_DB_CONNECT_CODE);
		}
		return $this->dbMaster;
	}
	
	//获取从库句柄函数
	protected function getSlaveDbHandle() {
		$retry = 0;
		do {
			$this->dbSlave = DBMysqlNamespace::createDBHandle2(DBConfig::$SERVER_MS_SLAVE, $this->dbName);
			$retry++;
		} while ($this->dbSlave === FALSE && $retry < 3);
		
		if ($this->dbSlave === FALSE) {
			throw new Exception(ErrorConst::E_DB_CONNECT_MSG, ErrorConst::E_DB_CONNECT_CODE);
		}
		return $this->dbSlave;
	}
	
    protected function parseFields($fields) {
        $fieldNum = count($fields);
        if ($fieldNum > 0) {
            return implode(',', $fields);
        } else {
            return '*';
        }
    }
}
