<?php
/**
 * @package
 * @subpackage
 * @brief                $wap客源项目数据源$
 * @file                 WapProjectKeyuan.class.php
 * @author               wanyang:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-25
 * @lastmodified         下午6:45
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Wap_WapProjectKeyuan extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_wap_project_keyuan';
    /*
     * phone是unique
     */
    protected $table_fields = array('id', 'conditions', 'type', 'major_category', 'create_time', 'sales_name', 'phone');

    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = 'house_wap';
        parent::__construct();
    }

    /**
     * @brief 查询客源需求数据
     * @param $openid
     * @param $fields
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function selectKeyuanData($phone, $fields, $limit = 10, $page = 1){
        if (is_array($fields) && $phone !== null) {
            if (!$arrFields = $this->objMysqlUtil->checkSelectFields($fields, $this->table_fields)) {
                throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, " field is error");
            }
            $conds = array('phone =' => $phone);
            $ret = $this->selectByPage($fields, $conds, $page, $limit, array('create_time' => 'DESC'), null);
        } else {
            throw new Gj_Exception( ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }
        return $ret;
    }

    /**
     * @brief 插入客源数据
     * @param $data
     * @return bool
     */
    public function insertKeyuanData($data){
        if ($this->insert($this->formatDataForInsert($data))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 更新用户的需求数据
     * @param $data
     * @return bool
     */
    public function updateKeyuanData($data){

        //一期需求中目前不需要更新数据
        return true;
    }

    /**
     * @brief 格式化即将入库的数据
     * @param $data
     */
    protected function formatDataForInsert($data){
        $fdata = array();
        $fdata['conditions'] = serialize($data['conditions']);
        $fdata['major_category'] = empty($data['major_category'])?1:$data['major_category'];
        $fdata['type'] = empty($data['type'])?1:$data['type'];
        $fdata['sales_name'] = $data['sales_name'];
        $fdata['phone'] = $data['phone'];
        $fdata['create_time'] = time();
        return $fdata;
    }
}