<?php

class Dao_Xiaoqu_Xinloupan extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xinloupan";
    protected $table_fields = array(
            'id',
            'xiaoqu_id',
            'city',
            'start_sale',
            'check_in',
            'homes_num',
            'pre_sale_permit',
            'intro',
            'sales_office_address',
            'sales_office_tel',
            'status',
            'price',
            'activity',
            'hot_huxing',
            'loupan_label',
            'huxing_price'
    );

    /** {{{ getXinloupanIdsByCityArr 根据城市数组获取新盘IdsList
     *
     */
    public function getXinloupanIdsByCityArr($cityArr, $limit = 6){
        $idList = array();
        if (!empty($cityArr) && is_array($cityArr)) {
            $cityStr =  "'" . implode("','",$cityArr) . "'";
            $cityStr = "city in ({$cityStr})";
            $arrConds = array($cityStr , 'status = ' => 0);
            $options = array(' order by id desc', 'limit ' . $limit);
            $idList = $this->dbHandler->select($this->tableName, array('id'), $arrConds, null, $options);
            if (FALSE === $idList) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . " getXinloupanIdsByCityArr . $idList", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $idList;
    } //}}}
    /** {{{ getXinloupanDataByCityArr 根据城市数组获取新盘数据
     *
     */
    public function getXinloupanDataByCityArr($cityArr, $start = 0, $limit = 10){
        $list = array();
        if (!empty($cityArr) && is_array($cityArr)) {
            $cityStr =  "'" . implode("','",$cityArr) . "'";
            $cityStr = "city in ({$cityStr})";
            $arrConds = array($cityStr , 'status = ' => 0);
            $options = array(' order by id desc', 'limit '. $start . ', ' . $limit);
            $list = $this->dbHandler->select($this->tableName, $this->table_fields, $arrConds, null, $options);
            if (FALSE === $list) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . " getXinloupanDataByCityArr . $list ", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $list;
    } //}}}
    /** {{{ getXinloupanCountByCityArr 根据城市数组获取新楼盘的数量
     *
     */
    public function getXinloupanCountByCityArr($cityArr){
        $count = array();
        if (!empty($cityArr) && is_array($cityArr)) {
            $cityStr =  "'" . implode("','",$cityArr) . "'";
            $cityStr = "city in ({$cityStr})";
            $arrConds = array($cityStr , 'status = ' => 0);
            $count = $this->dbHandler->select($this->tableName, array('count(1) as total'), $arrConds);
            if (FALSE === $count) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . " getXinloupanCountByCityArr  . $count ", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $count[0]['total'];
    } //}}}
    /** {{{ getXinloupanInfoByXiaoquId 根据xiaoquId获取新楼盘信息
     *
     */
    public function getXinloupanInfoByXiaoquId($xiaoquId){
        $loupanInfo = array();
        if (!empty($xiaoquId) && is_numeric($xiaoquId)) {
            $arrConds = array('xiaoqu_id = ' => $xiaoquId, 'status = ' => 0);
            $loupanInfo = $this->dbHandler->select($this->tableName, $this->table_fields, $arrConds);
            if (FALSE === $loupanInfo) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getXinloupanInfoByXiaoquId . $xiaoquId", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $loupanInfo[0];
         
    } //}}} 
}
