<?php
/**
 * Created by PhpStorm.
 * User: zhuyaohui
 * Date: 2014/12/27
 * Time: 11:52
 */

class Dao_Housepremier_UserRefreshNum extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'user_refresh_num';
    protected $table_fields = array('id','account_id','daynum','num', 'bussiness_scope');
    /**
     * 帖子状态
     *
     */
    const STATUS_OK = 1;
    const STATUS_DELETE = 11;
    const STATUS_PERMANENT_DELETE = 12;//永久删除
    const STATUS_MANUAL_LOCK = 15;
    const STATUS_COMPLAIN_LOCK = 16;
    const STATUS_APPROVAL_LOCK = 17; //待审核

    //获得累计上架真房源数量
    public function getRealHouseCountByAccount($arrFields, $arrConds){
        return $this->dbHandler->select($this->tableName,$arrFields,$arrConds);
    }

    //添加累计上架真房源数量
    public function insertCountInfo($arrFields){
        $mustContainFields = array('account_id','num', 'bussiness_scope');
        $arrRows = $this->objMysqlUtil->checkWriteRow($arrFields, $this->table_fields);
        if (!$arrRows || !$this->checkMustFields($arrRows, $mustContainFields)) {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," field is error");
        }
        $arrRows['daynum'] = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $ret = $this->dbHandler->insert($this->tableName, $arrRows, null, $arrRows);
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE," error:".$this->dbHandler->error()." sql :".$this->getLastSQL());
        }
        return $ret;
    }

    /**
     * @brief
     *
     * @param $arrayFields，$mustFields
     *
     * @returns
     */
    protected function checkMustFields($arrayFields, $mustFields){
        if (!is_array($arrayFields) || !is_array($mustFields)) {
            return false;
        }
        $intersectRet = array_intersect($mustFields, array_keys($arrayFields));
        return $intersectRet == $mustFields;
    }
}
