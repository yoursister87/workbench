<?php
/**
 * 帖子描述表dao
 * @author lihongyun1
 *
 */
class Dao_Housepremier_HouseSourceDescription extends Gj_Base_MysqlDao{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName;
    protected $tablePrefix = 'house_premier_description.house_source_description_';
	protected $table_fields = array('puid','description','ext','create_time');
    protected $dbHandler;
	
	public function __construct(){		
		parent::__construct('house_premier',true);
	}
	
	public function insertDesc($arrRow,$intPuid){
        if (!is_numeric($intPuid)) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'puid is wrong');
            return false;
        }
		
		$this->setTableName($intPuid);
        $arrRow['create_time'] = time();

        $arrOndup = array('create_time' => $arrRow['create_time'],'description' =>$arrRow['description']);
        if (!$arrRows = $this->objMysqlUtil->checkWriteRow($arrRow,$this->table_fields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        $ret = $this->dbHandler->insert($this->tableName,$arrRows,null,$arrOndup);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
	}
	public function selectDesc($arrFields,$arrConds,$intPuid){
        if (!is_numeric($intPuid)) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'puid is wrong');
            return false;
        }
		
		$this->setTableName($intPuid);
		return $this->select($arrFields,$arrConds);
	}
	
	public function updateDesc($arrRow,$arrConds,$intPuid){
		if (!is_numeric($intPuid)) {
			throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'puid is wrong');
            return false;
		}
		$this->setTableName($intPuid);
		return $this->update($arrRow,$arrConds);
	}	
	
	private  function setTableName($intPuid){
		$table_sign = $intPuid % 100;
		$table_sign = sprintf("%02d", $table_sign);
		$this->tableName = $this->tablePrefix.$table_sign;
	}
	
}