<?php
/**
 *
 * 一练app用户开关存储类
 *
 * 用户全局系统消息读取删除相关信息也存储在这里，有3个键
 * - last_query，最后拉取全局系统消息的时间
 * - deltm，保存的是上次删除的时候最新一条全局系统消息的创建时间
 * - delmap，删除图谱信息
 *
 * 这里的稍微难理解点，采用了位图的知识，delmap存储的是一个整数，只有将它转换为二进制的时候才会有意义
 * 每一位代表用户一条系统消息的删除状态（1删除，0未删除）
 * 在构建delmap的时候将原有的消息列表顺序反过来，这样做处理原始列表的时候就比较方便了
 *
 * @author zhaoxin@zuoyebang.com
 * @version 1.0
 * @package Zybang
 */

class Hk_Service_Message_Switch_Practice extends Hk_Service_Message_Switch_Base
{
    const PRACTICE_SWITCH_PUSH    = "practicePush";

    // 一课app目前支持的开关配置
    public static $SwitchSet    = array(
        self::PRACTICE_SWITCH_PUSH,
    );

    // Message对应的控制开关
    public static $CmdSwitchMap = array(
        Hk_Service_Message_Const::APP_IM_PUSH_CUIDS => self::PRACTICE_SWITCH_PUSH,
        Hk_Service_Message_Const::APP_IM_PUSH_UIDS  => self::PRACTICE_SWITCH_PUSH,
    );

    /**
     * redis实例存储
     */
    private $objRedis = null;

    /**
     * 用户开关信息存储的键名
     */
    public $key = "PRACTICE_USER_SWITCH_INFO";

    /**
     * 用户存储开关信息的数量限制
     */
    public $limit = 20;

    public function __construct($objRedis = null)
    {
        $this->objRedis = $objRedis;
    }

    private function getRedisObject()
    {
        if (empty($this->objRedis)) {
            $conf = Bd_Conf::getConf('hk/redis/msg');
            $this->objRedis = new Hk_Service_Redis($conf['service']);
            if (empty($this->objRedis)) {
                Bd_Log::warning("Msg init redis failed. Detail[".json_encode($conf['service'])."]");
                return false;
            }
        }
        return true;
    }

    /**
     * 获取Napi产品开关字符串标识集合
     *
     **/
    public function getSwitchType($cmdNo = null)
    {
        $types = [];
        if ($cmdNo === null) {
            return self::$SwitchSet;
        }
        if (isset(self::$CmdSwitchMap[$cmdNo])) {
            $types[] = self::$CmdSwitchMap[$cmdNo];
        }
        return $types;
    }

    /**
     * 获取拼接之后的键值
     */
    public function getCacheKey($key)
    {
        return "{$key}:" . $this->key;
    }

    /**
     * 设置用户的消息开关
     *
     * @access public
     * @param $uid int
     * @param $type string 消息类型
     * @param $close mixed 可能为某些其他信息（遗留问题保留全局系统消息的删除信息）
     */
    public function setSwitch($uid, $type, $close = 1)
    {
        if (!$this->getRedisObject()) {
            return false;
        }
        $cacheKey = $this->getCacheKey($uid);
        $len = $this->objRedis->hlen($cacheKey);
        if ($len > $this->limit) {
            Bd_Log::warning("msg switch error: {$cacheKey} len over than limit " . $this->limit);
        }
        return $this->objRedis->hset($cacheKey, $type, intval($close));
    }

    /**
     * 获取用户的消息开关
     *
     * @access public
     * @param $uid int
     * @return array 用户的开关信息
     */
    public function getSwitch($uid)
    {
        $switches = [];
        if (!$this->getRedisObject()) {
            return $switches;
        }
        $cacheKey = $this->getCacheKey($uid);
        $ret = $this->objRedis->hgetall($cacheKey);
        if ($ret) {
            foreach ($ret as $item) {
                $switches[$item["field"]] = intval($item["value"]);
            }
        }
        return $switches;
    }
}
