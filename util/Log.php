<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Log.php
 * @author chuzhenjiang(com@baidu.com)
 * @date 2013/12/06 12:36:38
 * @brief 日志
 *
 **/
class Hk_Util_Log extends Bd_Log
{
    public static $timer = null;
    public static $loginfo = array();


    public static function start($key)
    {
        if(null == self::$timer)
        {
            self::$timer = new Bd_TimerGroup(Bd_Timer::PRECISION_US);
        }
        self::$timer->start($key);
    }


    public static function stop($key)
    {
        if(null == self::$timer)
        {
            return;
        }
        self::$timer->stop($key);
    }


    public static function timeTotal($key = null)
    {
        if(null == self::$timer)
        {
            return;
        }
        return self::$timer->getTotalTime($key, Bd_Timer::PRECISION_MS);
    }


    public static function setLog($key, $value)
    {
        self::$loginfo[$key] = $value;
    }

    
    public static function incrKey($key, $step = 1)
    {
        self::$loginfo[$key] = intval(self::$loginfo[$key]) + $step;
    }

    
    public static function decrKey($key, $step = 1)
    {
        self::$loginfo[$key] = intval(self::$loginfo[$key]) - $step;
    }
    
    
    public static function printLog()
    {
        $time = self::timeTotal();
        Bd_Log::addNotice('time', $time);
        foreach(self::$loginfo as $key => $val)
        {
            Bd_Log::addNotice($key, $val);
        }
    }

    /**
     * 单独打印一条日志用于数据收集
     * 并发情况下，日志长度超过block size有可能出现日志串行的问题
     * 单独打印日志可以有效降低日志串行的风险
     *
     * @param $key
     * @param $value
     * @return   
     **/
    public static function printStatLog($key, $value)
    {
        $log = parent::getInstance();

        // 保存addNotice信息
        $tmpArr = $log->addNotice;
        $log->addNotice = array();

        // 打印日志
        Bd_Log::addNotice($key, $value);
        Bd_Log::notice("", 0);

        // 恢复addNotice信息
        $log->addNotice = $tmpArr;
    }

    /*
     * @param $str
     * @return NULL
     * @desc 脚本多次调用Hk_Service_Message::callRpc进行消息推送时，每次addNotice的rpc key都不同，会导致addNotice数组巨大
     * 此方法用于打印后清理addNotice数组
     */
    public static function printLogClearNotice($str){
        $log = parent::getInstance();
        Bd_Log::notice($str, 0);
        $log->addNotice = array();
    }

}


/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
