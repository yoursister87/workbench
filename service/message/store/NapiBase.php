<?php
/**
 * Base.php
 *
 * 消息存储基类
 *
 * @author yanruitao<yanruitao@zuoyebang.com>
 * @version 1.0
 * @package Zybang
 */

abstract class Hk_Service_Message_Store_NapiBase
{
    /**
     * 保存的redis实例
     */
    public $objRedis = null;

    /**
     * redis中的key
     */
    public $key = "";

    /**
     * 消息保存的最大长度
     */
    public $limit = 0;

    /**
     * 消息默认保存时间
     * 保存2个月
     */
    const MSG_EXPIRE_TIME = 5184000;

    public function __construct($objRedis)
    {
        $this->objRedis = $objRedis;
    }

    /**
     * 获取用户redis中的key
     *
     * @param $key
     * @return mixed
     */
    public function getCacheKey($uid, $type = null)
    {
        if (!$uid) {
            return false;
        }
        return "{$uid}:" . $this->key;
    }

    /**
     * 刷新消息过期时间
     *
     * @access public
     * @param uid int 用户uid
     * @param return bool
     */
    public function refreshExpireTime($uid)
    {
        $cacheKey = $this->getCacheKey($uid);
        if ($cacheKey) {
            return $this->objRedis->expire($cacheKey, self::MSG_EXPIRE_TIME);
        }
        return false;
    }

    /**
     * 获取消息数量的限制
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * 保存消息
     *
     * @access public
     * @param $uid int
     * @param $data array
     */
    abstract public function saveMsg($uid, $data);

    /**
     * 删除过期消息
     *
     * @access protected
     * @param $udi int
     * @return bool
     */
    abstract public function delExpireMsg($uid);

    /**
     * 删除指定消息
     *
     * @param $uid int
     * @Param $key string
     * @return bool
     */
    abstract public function delMsg($uid, $key);

    /**
     * 获取用户某种消息类型的数量
     *
     * @access public
     * @param $uid int
     * @param $onlyUnread bool
     */
    abstract public function getMsgCount($uid, $onlyUnread = true);

    /**
     * 获取用户某种消息类型消息列表
     *
     * @param $uid int
     * @param $param array
     */
    abstract public function getMsgList($uid, $param = []);

    /**
     * 标记消息为已读
     *
     * @param $uid int
     * @param $field mixed
     */
    abstract public function changeMsgRead($uid, $field);
}
