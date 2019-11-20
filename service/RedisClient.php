<?php


/**
 * redis-client客户端<br>
 * 使用redis.so，只支持redis标准协议<br>
 * 支持函数列表参见（注意：由于扩展限制，不支持3.0以上的redis新函数）：https://github.com/phpredis/phpredis
 *
 * @see https://github.com/phpredis/phpredis
 *
 * @filesource hk/service/RedisClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-05-24
 */
class Hk_Service_RedisClient {


    private static $redisInst = array();

    private function __construct() {}

    /**
     * 获取redis集群对应的实例
     *
     * @param string       $name
     * @return mixed:Hk_Service_RedisClient|boolean
     */
    public static function getInstance($name) {
        if (isset(self::$redisInst[$name])) {
            return self::$redisInst[$name];
        }

        $conf = Bd_Conf::getConf("/hk/redisclient/services");
        if (!isset($conf[$name]) || empty($conf[$name])) {       # 未配置
            Bd_Log::warning("get redis cluster failed, cluster: {$name}");
            return false;
        }

        $cluster  = $conf[$name];
        $instance = $cluster['instance'];
        $prefix   = $cluster['prefix'];
        $ctimeout = $cluster['ctimeout'];
        $conn     = new RedisInstance($instance, $prefix, $ctimeout);

        self::$redisInst[$name] = $conn;
        return $conn;
    }
}

/**
 * redis客户端封装，封装redis.so，连接实例通过phpbns获取
 *
 * @filesource hk/service/RedisClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 */
class RedisInstance {


    const ERR_OK   = 0;
    const ERR_INST = -1;        # 获取实例失败
    const ERR_CONN = -2;        # 连接错误
    const ERR_EXEC = -3;        # 命令执行失败

    private $host = "";
    private $port = 0;
    private $ctimeout;

    private $service;
    private $inst;
    private $prefix;

    private $conn = NULL;
    private static $timer = array();

    public function __construct($inst, $prefix, $ctimeout) {
        $this->service = "redis";
        $this->inst    = $inst;

        $client = Bd_Bns_Client::getInstance();
        self::start("call_bns");
        $server = $client->getServiceInstance($this->service, $inst);            # 使用bns的random策略，随机挑选实例
        self::stop("call_bns");
        if (false === $server) {
            $this->log("redis instance get failed", self::ERR_INST, array(), Bd_Log::LOG_LEVEL_WARNING);
            return;
        }

        $this->host     = $server["host"];
        $this->port     = $server["port"];
        $this->prefix   = $prefix;
        $this->ctimeout = $ctimeout;
        $this->init();
    }

    /**
     * 初始化redis连接，记录连接时间和日志
     */
    private function init() {
        $interval = 100;                                  # 默认超时重试间隔时间，单位：ms
        $ctimeout = floatval($this->ctimeout / 1000);     # 连接超时，需要转换时间单位，s

        $conn     = new Redis();
        $timer    = "connect";
        try {
            self::start($timer);
            $ret  = $conn->connect($this->host, $this->port, $this->ctimeout, NULL, $interval);
            self::stop($timer);
        } catch (RedisException $e) {
            $this->log($e->getMessage(), $e->getCode(), array(), Bd_Log::LOG_LEVEL_WARNING);
            return;
        }

        if (false === $ret) {
            $this->log("conn failed", self::ERR_CONN, array(), Bd_Log::LOG_LEVEL_WARNING);
            return;
        }
        if (!empty($this->prefix)) {
            $conn->setOption(Redis::OPT_PREFIX, "{$this->prefix}:");
        }
        $this->log("connect ok", self::ERR_OK, array(), Bd_Log::LOG_LEVEL_DEBUG);
        $this->conn = $conn;
    }

    /**
     * 执行redis命令，获取命令结果<br>
     * 执行日志将输出在/path/to/log/redis中<br>
     * 失败返回NULL
     *
     * @param string       $func
     * @param array        $input
     * @return mixed:ret|NULL
     */
    public function __call($func, $input) {
        $args = array(
            "func" => $func,
        );
        if (NULL === $this->conn) {                         # 客户端初始化失败
            $this->log("not connect", self::ERR_EXEC, $args, Bd_Log::LOG_LEVEL_WARNING);
            return NULL;
        } elseif (!method_exists($this->conn, $func)) {     # 方法不存在
            $this->log("function not exist", self::ERR_EXEC, $args, Bd_Log::LOG_LEVEL_WARNING);
            return NULL;
        }

        $timer   = "read";              # 记录redis交互时间
        try {
            self::start($timer);
            $ret = call_user_func_array(array($this->conn, $func), $input);
            self::stop($timer);
        } catch (RedisException $e) {
            $this->log($e->getMessage(), $e->getCode(), $args, Bd_Log::LOG_LEVEL_WARNING);
            return NULL;
        }
        $this->log("ok", self::ERR_OK, $args);
        return $ret;
    }

    /**
     * 计时器封装，启动计时器<br>
     * 先重置计时器然后启动
     *
     * @param string      $name
     * @return boolean
     */
    private static function start($name) {
        if (!isset(self::$timer[$name])) {
            self::$timer[$name] = new Bd_Timer(false, Bd_Timer::PRECISION_US);
        }
        self::$timer[$name]->reset();
        return self::$timer[$name]->start();
    }

    /**
     * 关闭并重置计时器，返回计时器时间，单位：ms
     *
     * @param string      $name
     * @return float
     */
    private static function stop($name) {
        if (!isset(self::$timer[$name])) {
            return 0;
        }
        return self::$timer[$name]->stop();
    }

    /**
     * 记录redis实例客户端访问日志
     *
     * @param string      $msg
     * @param int         $errno
     * @param array       $arg
     * @param const       $level
     */
    private function log($msg = "", $errno = 0, array $arg = array(), $level = Bd_Log::LOG_LEVEL_NOTICE) {
        $logArg = array(              # 通用日志参数
            "caller"    => $this->service,
            "service"   => $this->inst,
            "client_ip" => Bd_Env::getUserIp(),
            "remote_ip" => empty($this->host) ? "" : "{$this->host}:{$this->port}",
            "err_info"  => $msg,
        );
        foreach (self::$timer as $name => $timer) {         # 记录相关耗时
            $logArg[$name] = floatval(number_format($timer->getTotalTime() / 1000, 3, ".", ""));
        }

        $logApp = "redis";
        $logArg = array_merge($logArg, $arg);
        Bd_AppEnv::setCurrApp($logApp);
        if (Bd_Log::LOG_LEVEL_DEBUG === $level) {
            Bd_Log::debug("", $errno, $logArg);
        } elseif (Bd_Log::LOG_LEVEL_WARNING === $level) {
            Bd_Log::warning("", $errno, $logArg);
        } elseif (Bd_Log::LOG_LEVEL_FATAL === $level) {
            Bd_Log::fatal("", $errno, $logArg);
        } else {
            Bd_Log::notice("", $errno, $logArg);
        }
        Bd_AppEnv::setCurrApp();
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */