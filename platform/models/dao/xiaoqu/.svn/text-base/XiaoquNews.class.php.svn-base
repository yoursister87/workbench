<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class Dao_Xiaoqu_XiaoquNews extends Gj_Base_MysqlDao{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = 'xiaoqu_news';
    protected $table_fields = array(
        'id',
        'xiaoqu_id',
        'account_id',
        'user_id',
        'content',
        'status',
        'ip',
        'reason',
        'domain',
        'ctime',
        'audit_time'
    );

    /**addXiaoquNews($data){{{*/
    /**
     * @$data['xiaoquId'] : 小区动态所属小区id
     * @$data['accountId'] : 发布者的account_id
     * @data['userId'] : 发布者的user_id即会员中心唯一id
     * @data['content'] : 小区动态内容
     * $data['ip'] : ip
     * $data['domain'] : 城市domain
     */
    public function addXiaoquNews($data){
        $result = false;
        if (empty($data) || !is_array($data)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        $info = array(
            'xiaoqu_id' => $data['xiaoquId'],
            'account_id' => $data['accountId'],
            'user_id' => $data['userId'],
            'content' => $data['content'],
            'status' => 1,
            'ip' => $data['ip'],
            'reason' => '',
            'domain' => $data['domain'],
            'ctime' => $now,
            'audit_time' => $now
        );
        $result = $this->insert($info);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "addXiaoquNews . $data", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**getXiaoquNewsByXiaoquId($xiaoquId){{{
     * @$xiaoquId
     */
    public function getXiaoquNewsByXiaoquId($xiaoquId, $status = 3, $limit = 10){
        if (false === $this->validatorParameters($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        //查询字段
        $queryFields = array('id', 'xiaoqu_id', 'account_id', 'user_id', 'content', 'ctime');
        //where条件
        $arrConds = array('status =' => $status, 'xiaoqu_id = ' => $xiaoquId);
        //附加选项
        $options = array(' order by id desc', 'limit ' . $limit);
        $result = $this->dbHandler->select($this->tableName, $queryFields, $arrConds, null, $options);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getXiaoquNewsByXiaoquId . xiaoquId : $xiaoquId , status : $status", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**getXiaoquNewsByAccountId{{{
     */
    public function getXiaoquNewsByAccountId($accountId, $status = null, $limit = 10){
        $result = array();
        if (false === $this->validatorParameters($accountId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        //查询条件
        $arrConds = array('account_id = ' => $accountId);
        if (!empty($status)) {
            $arrConds['status = '] = $status;
        } else {
            $arrConds['status != '] = 5;
        }
        //查询字段
        $queryFields = array('id', 'xiaoqu_id', 'account_id', 'user_id', 'content', 'reason', 'status', 'ctime');
        //附加选项
        $options = array(' order by id desc', 'limit ' . $limit);
        $result = $this->dbHandler->select($this->tableName, $queryFields, $arrConds, null, $options);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**updateXiaoquNewsStatusById{{{
     */
    public function updateXiaoquNewsStatusById($id, $status, $reason = ''){
        $result = false;
        if (false === $this->validatorParameters($id) || false === $this->validatorParameters($status)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        $info = array(
            'status' => $status,
            'reason' => $reason,
            'audit_time' => $now
        );
        $arrConds = array('id = ' => $id);
        $result = $this->update($info, $arrConds);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "updateXiaoquNewsStatusById id : $id, status : $status", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**getXiaoquNewsByStatus{{{
     */
    public function getXiaoquNewsByStatus($status = 3, $limit = 10){
        if (false === $this->validatorParameters($status) || false === $this->validatorParameters($limit)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        //查询条件
        $arrConds = array('status = ' => $status);
        //查询字段
        $queryFields = array('id', 'xiaoqu_id', 'account_id', 'user_id', 'content', 'domain', 'ip', 'ctime');
        //附加选项
        $options = array('limit ' . $limit);
        $result = $this->dbHandler->select($this->tableName, $queryFields, $arrConds, null, $options);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getXiaoquNewsByStatus status : $status", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**validatorParameters{{{
     */
    public function validatorParameters($item){
        if (empty($item) || !is_numeric($item)) {
            return false;
        } 
        return true;
    }//}}}
}


