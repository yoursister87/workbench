<?php


/**
 * 本地缓存yac服务，使用yac.so实现<br>
 * 使用共享内存模式，key空间最大64M，value空间最大512M，需要严格限制使用量<br>
 * 共享内存模式只适用于对过期时间不敏感的数据，严禁长期存储，造成数据无法及时更新<br>
 * 请注意：线上未开启cli模式，所以在命令行情况下操作无任何效果<br>
 * 其他未尽事宜请参见以下文档
 *
 * @see https://github.com/laruence/yac
 *
 * @since 1.0 2018-10-15 初始化
 *
 * @filesource hk/service/YacClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-10-15
 */
class Hk_Service_YacClient {


    private static $inst = array();

    private function __construct() {}

    /**
     * 获取实例
     *
     * @param string      $name
     * @return mixed:object|boolean
     */
    public static function getInstance($name = "") {
        if (false === extension_loaded('yac')) {                      # 未装载yac模块，禁止使用
            return false;
        }
        if ("" === $name) {
            $name = defined("MAIN_APP") ? MAIN_APP : "unknown";       # 使用odp环境变量作为前缀，防止冲突
        }
        if (isset(self::$inst[$name])) {
            return self::$inst[$name];
        }

        $prefix = "{$name}:";
        self::$inst[$name] = new YacClient($prefix);
        return self::$inst[$name];
    }
}

/**
 * Yac客户端
 *
 * @filesource hk/service/YacClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-10-15
 */
class YacClient {


    private $ttl = 60;          # 默认ttl
    private $yac;

    public function __construct($prefix = "") {
        $this->yac = new Yac($prefix);
    }

    /**
     * 单key设置缓存
     *
     * @param string      $key
     * @param mixed       $value
     * @param int         $ttl
     * @return boolean
     */
    public function set($key, $value, $ttl = 0) {
        $ttl = 0 === $ttl ? $this->ttl : $ttl;
        return $this->yac->set($key, $value, $ttl);
    }

    /**
     * 批量设置缓存，使用array($key => $val)方式设置
     *
     * @param array       $kvs
     * @param int         $ttl
     * @return boolean
     */
    public function mset(array $kvs, $ttl = 0) {
        if (empty($kvs)) {
            return false;
        }
        $ttl = 0 === $ttl ? $this->ttl : $ttl;
        return $this->yac->set($kvs, $ttl);
    }

    /**
     * 获取指定key|keys对应值，支持string|array
     *
     * @param mixed       $keys
     * @return mixed|boolean
     */
    public function get($keys) {
        return $this->yac->get($keys);
    }

    /**
     * 删除|批量删除指定key数据，$delay = 0代表立刻删除
     *
     * @param mixed       $keys
     * @param int         $delay
     * @return boolean
     */
    public function delete($keys, $delay = 0) {
        return $this->yac->delete($keys, $delay);
    }

    /**
     * 清空整个客户端缓存
     *
     * @return boolean
     */
    public function flush() {
        return $this->yac->flush();
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */