<?php

/**
 * @package
 * @subpackage
 * @brief                $微信认证数据相关$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @file                 WeixinAuth.class.php$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Weixin_WeixinAuth extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_auth';
    protected $table_fields = array('id', 'access_token', 'expire_at', 'request_times');

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
     * @brief 获取access_token
     */
    public function getAccessToken()
    {
        $page = 1;
        $limit = 1;
        $ret = $this->selectByPage($this->table_fields, null, $page, $limit, null, null);
        if ($ret[0]) {
            return $ret[0];
        } else {
            return false;
        }
    }

    /**
     * @brief 更新或插入token
     * @param $data
     */
    public function addAccessToken($data)
    {
        if (!$data['access_token'] || !$data['expire_at']) {
            return false;
        }
        if ($old_access_token_data = $this->getAccessToken()) {
            $time_interval = $old_access_token_data['expire_at'] - strtotime(date("Ymd"));
            if ( $time_interval >= 0 && $time_interval <= 300){
                $data['request_times'] = 1;
            }else{
                $data['request_times'] = $old_access_token_data['request_times'] + 1;
            }
            if ($this->update($data, array('id =' => $old_access_token_data['id']))) {
                return true;
            }
        } else {
            $data['request_times'] = 1;
            if ($this->insert($data)) {
                return true;
            };
        }
        return false;
    }

}
