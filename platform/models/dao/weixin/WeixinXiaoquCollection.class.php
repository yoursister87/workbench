<?php

class Dao_Weixin_WeixinXiaoquCollection extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_xiaoqu_collection';
    protected $table_fields = array('id', 'openid', 'xiaoqu_id', 'name', 'pinyin', 'thumb_img', 'url', 'city_name', 'district_name', 'street_name', 'avg_price', 'finish_at', 'create_time');

    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = 'house_weixin';
        parent::__construct();
    }

    /**
     * @brief 根据openid取多条收藏数据
     * @param string $orderby
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function selectCollections($openid, $fields, $limit = 10, $page = 1)
    {
        if (is_array($fields) && $openid !== null) {
            if (!$arrFields = $this->objMysqlUtil->checkSelectFields($fields, $this->table_fields)) {
                throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, " field is error");
            }

            $conds = " `openid` = '{$openid}' ";

            $ret = $this->selectByPage($fields, $conds, $page, $limit, array('create_time' => 'DESC'), null);
			//var_dump($this->getLastSQL());exit;
        } else {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        return $ret;
    }

    /**
     * @brief 插入一条收藏数据
     * @param $data
     */
    public function insertOneCollection($data)
    {
        $fields = array('id');
        $conds = array( 'openid =' => $data['openid'], 'xiaoqu_id=' => $data['xiaoqu_id']);
        if($this->selectByPage($fields, $conds, 1, 1, array(), null)){
            throw new Gj_Exception( ErrorConst::E_DATA_DUPLICATE_CODE, ErrorConst::E_DATA_DUPLICATE_MSG);
        }
        if ($this->insert($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 删除该openid下的所有收藏数据
     * @param $openid
     * @return array
     */
    public function deleteAllCollectionsByOpenid($openid)
    {
        //delete($table, $conds = NULL, $options = NULL, $appends = NULL)
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }

        $sql = " `openid` = '{$openid}' ";
        if (!$this->dbHandler->delete($this->tableName, $sql, null, null)) {
            return false;
        }
        return true;
    }
	 /**
     * @brief 删除该openid下制定的一个小区ID信息或者是一个自增ID
     * @param $openid
     * @return array
     */
    public function deleteOneCollectionsByOpenidAndXiaoquID($openid,$xiaoqu_id)
    {
        //delete($table, $conds = NULL, $options = NULL, $appends = NULL)
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }

        $sql = " `openid` = '{$openid}' and  `xiaoqu_id` = '{$xiaoqu_id}' limit 1";
        if (!$this->dbHandler->delete($this->tableName, $sql, null, null)) {
            return false;
        }
        return true;
    }
	/**
	 * 获取单个用户的收藏总数 
	 * @params $openid   
	 * @return array()
	*/
	public function getXiaoquCollectionsCountNum($openid)
	{
		if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }
		$arrConds_where = " `openid` = '{$openid}'";
        $ret = $this->selectByCount($arrConds_where);
		//echo $this->getLastSQL();
		if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,$this->getLastSQL());
        }
		return $ret;
	}
}
