<?php
/*
 +---------------------------------------------------------------------+
 | redis lock                                                          |
 +---------------------------------------------------------------------+
 | 对一个键进行加锁，获取键的X锁（排它锁）                             |
 | 处理完之后释放锁                                                    |
 | @file: RedisLock.php                                                |
 | @date: 2016-9-20                                                    |
 | @exple:                                                             |
 |      $lock = new Hk_Service_RedisLock($objRedsi);                   |
 |      $expireAt = $lock->getLock("your_key");                        |
 |      //do your things ......                                        |
 |      $lock->releaseLock("your_key", $expireAt);                     |
 +---------------------------------------------------------------------+
 | Author: yanruitao@zuoyebang.com                                     |
 +---------------------------------------------------------------------+
 */

class Hk_Service_RedisLock
{
    //锁的前缀
    const EXCLUSIZE_LOCK_PREFIX = "eXclusive_Lock_";

    public $objRedis = null;
    public $lockGap = 1;    //加锁时间s
    public $timeOut = 2;    //超时时间s

    public $usleepGap = 10; //循环获取锁的时候间隔时间 ms

    /**
     * @desc 设置redis实例
     *
     * @param obj object | redis实例
     */
    public function __construct($obj, $usleepGap = null)
    {
        $this->objRedis = $obj;
        if ($usleepGap) {
            $this->usleepGap = intval($usleepGap);
        }
    }

    /**
     * @desc 获取锁键名
     */
    public function getLockCacheKey($key)
    {
        return self::EXCLUSIZE_LOCK_PREFIX . "{$key}";
    }

    /**
     * @desc 获取锁
     *
     * @param key string | 要上锁的键名
     * @param lockGap int | 上锁时间
     * @param timeOut int | 获取锁超时时间，为0表示不限制
     * @return mixed | 获取失败返回false，成功返回锁的截止时间
     */
    public function getLock($key, $lockGap = NULL, $timeOut = NULL)
    {
        $startTime = time();
        $lockGap = $lockGap ? $lockGap : $this->lockGap;
        $timeOut = (NULL !== $timeOut) ? intval($timeOut) : $this->timeOut;
        $lockCacheKey = $this->getLockCacheKey($key);
        $expireAt = $startTime + $lockGap;
        $isGet = (bool)$this->objRedis->setnx($lockCacheKey, $expireAt);
        if ($isGet) {
            return $expireAt;
        }

        while (1) {
            $time = time();
            if ($timeOut !== 0 && $time > ($startTime + $timeOut)) {     //获取锁超时了，直接退出
                Bd_Log::notice("RedisLock get failed, time out : key[{$key}], lockGap[{$lockGap}], timeOut[{$timeOut}]");
                return false;
            }
            usleep($this->usleepGap);
            $oldExpire = $this->objRedis->get($lockCacheKey);
            if ($oldExpire >= $time) {
                continue;
            }
            $newExpire = $time + $lockGap;
            $expireAt = $this->objRedis->getset($lockCacheKey, $newExpire);
            if ($oldExpire != $expireAt) {
                continue;
            }
            $isGet = $newExpire;
            break;
        }
        return $isGet;
    }

    /**
     * @desc 释放锁
     *
     * @param key string | 加锁的字段
     * @param expireAt int | 加锁的截止时间
     *
     * @return bool | 是否释放成功
     */
    public function releaseLock($key, $expireAt)
    {
        if (!$expireAt) {   //建的过期时间为空，表示可能获取排它锁失败，直接返回，不作处理
            return true;
        }
        $lockCacheKey = $this->getLockCacheKey($key);
        if ($expireAt >= time()) {
            return $this->objRedis->del($lockCacheKey);
        }
        return true;
    }
}
