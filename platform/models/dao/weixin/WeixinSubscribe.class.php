<?php

/**
 * @package
 * @subpackage
 * @brief                $微信订阅数据$
 * @file                 WeixinSubscribe.class.php
 * @author               $Author:    wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         下午5:55
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Weixin_WeixinSubscribe extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_subscribe';
    protected $table_fields = array('id', 'openid', 'conditions', 'subType', 'major_category', 'create_time');

    protected static $RENT_SHARE = array(1, 3);
    protected static $SUBSCRIBE_MAX = array(
        1 => 1,
        3 => 1,
        5 => 5,
    );
    protected static $ALL_CONDS_FIELDS = array('openid');
    protected static $BASIC_CONDS_FIELDS = array('openid', 'major_category');
    protected static $ADVANCEED_CONDS_FIELDS = array('openid', 'major_category', 'subType');

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
     * @brief 获取订阅条件
     * @param $openid $openid = null, $majorcategory = null,
     * $subType = null, $fields = array('conditions','major_category', 'subType')
     * @return array
     */
    public function getSubscribeConditionByOpenid($openid = null, $major_category = null,
                                                  $subType = null, $fields = array('conditions', 'major_category', 'subType', 'id'))
    {
        $pagesize = 10;
        $page = 1;
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $conds = $this->prepareQueryConds(
            array(
                'openid' => $openid,
                'major_category' => $major_category,
                'subType' => $subType,
            ),
            self::$ADVANCEED_CONDS_FIELDS
        );
        $ret = $this->selectByPage($fields, $conds, $page, $pagesize, array(), null);
        if ($ret) {
            foreach ($ret as $key => $value) {
                $ret[$key]['conditions'] = unserialize($value['conditions']);
            }
                return $ret;
        }
        return false;
    }

    /**
     * @brief 根据sid获取条件
     * @param null $subscribeId
     * @param array $fields
     * @return bool
     * @throws Gj_Exception
     */

    public function getSubscribeConditionBySubscribeId($subscribeId = null, $fields = array('conditions', 'major_category', 'subType', 'id'))
    {
        if ($subscribeId == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $page = 1;
        $pagesize = 1;
        $conds = array(' id = ' => intval($subscribeId));
        $ret = $this->selectByPage($fields, $conds, $page, $pagesize, array(), null);
        if ($ret[0]) {
            $ret[0]['conditions'] = unserialize($ret[0]['conditions']);
            return $ret[0];
        }
        return false;
    }

    /**
     * @brief 添加单条订阅条件
     * @param $data
     * @return bool
     */
    public function insertSubscribeCondition($data)
    {
        if (in_array($data['major_category'], array(1,3)) ) {
            $overwrite_if_validate_fields = array('major_category' => $data['major_category']);
            if ($this->isSubscribeAlreadyExits($data['openid'], $overwrite_if_validate_fields)) {
                return $this->updateSubscribeCondition($data);
            }
        } else {
            if (!$this->NotDuplicateCheck($data, self::$SUBSCRIBE_MAX[$data['major_category']]) || 
                        !$this->isUnderAllowSize($data, self::$SUBSCRIBE_MAX[$data['major_category']])) {
                throw new Gj_Exception(ErrorConst::E_OPERATION_OVERFLOW_CODE, ErrorConst::E_OPERATION_OVERFLOW_MSG);
            }
        }
        $data['conditions'] = serialize($data['conditions']);
        $data['major_category'] = empty($data['major_category']) ? 1 : $data['major_category'];
        $data['create_time'] = time();
        if ($id = $this->insert($data)) {
            return $id;
        } else {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
    }

    /**
     * @brief 根据openid取消/删除订阅
     * @param $openid , major_category
     * @return bool
     */
    public function deleteSubscribeCondition($openid, $extra_validate_fields = array())
    {
        if (!$this->isSubscribeAlreadyExits($openid)) {
            throw new Gj_Exception(ErrorConst::E_DATA_NOT_EXIST_CODE, ErrorConst::E_DATA_NOT_EXIST_MSG);
        }
        $data = array_merge(array('openid' => $openid), $extra_validate_fields);
        $conds = $this->prepareQueryConds($data, self::$BASIC_CONDS_FIELDS);
        if (!$this->dbHandler->delete($this->tableName, $conds, null, null)) {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
        return true;
    }


    /*
     * 根据订阅的id删除
     */
    public function deleteSubscribeBySubscribeId($subscribeId = null, $openid = null)
    {
        if ($subscribeId == null || $openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $conds = array(' id = ' => intval($subscribeId), 'openid = ' => $openid);
        if (!$this->dbHandler->delete($this->tableName, $conds, null, null)) {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
        return true;
    }

    /**
     * @brief 根据openid修改FANG1, FANG3订阅
     * @param $openid
     * @return bool
     */
    public function updateSubscribeCondition($data)
    {
        if ($data['openid'] == null || $data['conditions'] == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        if (!$this->isSubscribeAlreadyExits($data['openid'])) {
            throw new Gj_Exception(ErrorConst::E_DATA_NOT_EXIST_CODE, ErrorConst::E_DATA_NOT_EXIST_MSG);
        }
        $conds = $this->prepareQueryConds($data, self::$BASIC_CONDS_FIELDS);
        $data['conditions'] = serialize($data['conditions']);
        $data['create_time'] = time();

        if ($this->update($data, $conds)) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
    }

    /**
     * @brief 根据sid更新订阅
     * @param null $subscribeId
     * @return bool
     * @throws Gj_Exception
     */
    public function updateSubscribeByScribeId($data = array())
    {
        if ($data['id'] == null || $data['openid'] == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $conds = $this->prepareQueryConds($data, array('id', 'openid'));
        $data['conditions'] = serialize($data['conditions']);
        $data['create_time'] = time();
        if ($this->update($data, $conds)) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
    }

    /**
     * @brief 检查openid数据是否存在
     * @param $openid
     * @return bool
     */
    public function isSubscribeAlreadyExits($openid, $extra_validate_fields = array())
    {
        $fields = array('id');
        $data = array_merge(array('openid' => $openid), $extra_validate_fields);
        $conds = $this->prepareQueryConds($data, self::$BASIC_CONDS_FIELDS);
        if (!$this->selectByPage($fields, $conds, 1, 1, array(), null)) {
            return false;
        }
        return true;
    }

    /**
     * @brief 数据重复检查
     * @param $data
     */
    public function NotDuplicateCheck($data = array(), $limit = 1)
    {
        if ($data['openid'] == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $fields = array('conditions');
        $conds = $this->prepareQueryConds($data, self::$BASIC_CONDS_FIELDS);
        if ($arr = $this->selectByPage($fields, $conds, 1, $limit, array(), null)) {
            foreach ($arr as $key => $value) {
                $current = unserialize($value['conditions']);
                if($current['domain'] != $data['conditions']['domain']){
                    throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, $current['domain']);
                }
                if ($current == $data['conditions']) {
                    throw new Gj_Exception(ErrorConst::E_DATA_DUPLICATE_CODE, ErrorConst::E_DATA_DUPLICATE_MSG);
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 检查是否超过限制5条
     */
    public function isUnderAllowSize($data = array(), $limit = 1)
    {
        if ($data['openid'] == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        $conds = $this->prepareQueryConds($data, self::$BASIC_CONDS_FIELDS);
        if ($count = $this->selectByCount($conds, null)) {
            return $count < $limit;
        }
        return true;
    }

    public function prepareQueryConds($data, $requiredFields = array())
    {
        $conds = array();
        if (in_array('id', $requiredFields) && $data['id']) {
            $conds[] = " `id` = '{$data['id']}' ";
        }
        if (in_array('openid', $requiredFields) && $data['openid']) {
            $conds[] = " `openid` = '{$data['openid']}' ";
        }
        if (in_array('subType', $requiredFields) && $data['subType']) {
            $conds[] = " `subType` = '{$data['subType']}' ";
        }
        if (in_array('major_category', $requiredFields) && $data['major_category']) {
            if (in_array($data['major_category'], self::$RENT_SHARE)) {
                $conds[] = " `major_category` in (1,3) ";
            } else {
                $conds[] = " `major_category` = '{$data['major_category']}' ";
            }
        }
        return implode(" and ", $conds);
    }
}
