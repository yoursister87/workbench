<?php
/**
 * Created by PhpStorm.
 * User: shaohuan@zuoyebang.com
 * Time: 2017/7/26 9:40
 */

class Hk_Service_Message_Switch_Teacher extends Hk_Service_Message_Switch_Base
{
    const SWITCH_COURSE_CHECK                = 'courseCheck';        // 质检
    const SWITCH_ON_CLASS                    = 'onClass';            // 待上课
    const SWITCH_UPLOAD_LECTURE              = 'uploadLecture';      // 上传讲义
    const SWITCH_LECTURE_NOT_PASS            = 'lectureNotPass';     // 讲义审核不通过

    // 一课老师app目前支持的开关配置
    public static $SwitchSet    = array(
        self::SWITCH_COURSE_CHECK,
        self::SWITCH_ON_CLASS,
        self::SWITCH_UPLOAD_LECTURE,
        self::SWITCH_LECTURE_NOT_PASS,
    );

    // Message对应的控制开关
    public static $CmdSwitchMap = array(
        Hk_Service_Message_Const::TEACHER_APP_COURSE_CHECK         => self::SWITCH_COURSE_CHECK,
        Hk_Service_Message_Const::TEACHER_APP_ON_CLASS             => self::SWITCH_ON_CLASS,
        Hk_Service_Message_Const::TEACHER_APP_UP_LECTURE           => self::SWITCH_UPLOAD_LECTURE,
        Hk_Service_Message_Const::TEACHER_APP_LECTURE_NOT_PASS     => self::SWITCH_LECTURE_NOT_PASS,
    );

    /**
     * redis实例存储
     */
    private $objRedis = null;

    /**
     * 用户开关信息存储的键名
     */
    public $key = "TEACHER_USER_SWITCH_INFO";

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
     * @param null $cmdNo
     * @return array
     */
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
     * @param $key
     * @return string
     */
    public function getCacheKey($key)
    {
        return "{$key}:" . $this->key;
    }

    /**
     * 设置用户的消息开关
     * @param $uid
     * @param $type 消息类型
     * @param int $close 可能为某些其他信息（遗留问题保留全局系统消息的删除信息）
     * @return bool
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
