<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * @codeCoverageIgnore
 */
class Dao_Housepremier_HouseRealComment extends Gj_Base_MysqlDao{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'house_real_comment';
    protected $table_fields = array("comment_id","house_id","house_type","puid","title","content","puid","post_at","modified_at","ip","user_id","user_name","user_phone","owner_user_id","stat");
    public function deleteById($arrConds){
        $ret = $this->dbHandler->delete($this->tableName, $arrConds);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
    }
	public function selectGroupbyPuid($arrFields,$arrConds){
		$strGroupby = ' group by puid order by null'; 
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	} 
}
