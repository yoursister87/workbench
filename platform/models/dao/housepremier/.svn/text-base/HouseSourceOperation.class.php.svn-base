<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */
class Dao_Housepremier_HouseSourceOperation extends Gj_Base_MysqlDao {
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_source_operation';
    protected $table_fields = array('HouseId','Type','OperationType','CityId','Status','Message','CreatorId','CreatorName','CreatedTime','ModifierId','ModifierName','ModifiedTime','UserAgent',);
	public function selectGroupbyCreatorId($arrFields,$arrConds){
		$strGroupby = ' group by CreatorId order by null'; 
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	} 
}
