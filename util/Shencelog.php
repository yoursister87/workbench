<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Shencelog.php
 * @author sunxiancan(com@baidu.com)
 * @date 2016-03-02
 * @brief 神策服务器端打点日志
 *          新写的文件
 *
 **/

class Hk_Util_Shencelog extends Bd_Log
{
    //文件路径
    private static $logFile = '';

    //文件前缀
    const PRE_FILE = 'shence';

    //事件的归属
    private static $distinctId = '';

    //事件
    private static $event = '';

    //属性
    private static $properties = array(
        '$manufacturer' => '',   //设备型号
        '$os' => '',             //设备系统
        '$os_version' =>'',      //设备版本
        '$app_version' => '',    //app版本
    //    '$wifi' => '',           //是否wifi
        '$ip' => '',             //客户端ip
        'c' => '',              //设备
        'grade' => '',          //年级
        'subject' => '',        //学科
        'search_id' => '',      //检索id
    );

    //请求参数
    private static $_requestParam = array();

    /**
     * @对外暴漏的打点接口
     * @params int|string $distinctId 唯一id，比如uid
     * @params string $event 事件 唯一确定这次打点的归属
     * @params array $properties 事件的属性  附加的属性
     */
    public static function dot($distinctId, $event, array $properties) {
        //获取请求参数
        $arrRequestParam = Saf_SmartMain::getCgi();
        self::$_requestParam = !is_null($arrRequestParam['request_param']) ? $arrRequestParam['request_param'] : array();
        //log绝对地址
        self::getFile();
        //全局参数
        self::$distinctId = $distinctId;
        self::$event = $event;
        //写入文件
        $ret = self::writeShence($properties); 
        if(false === $ret) {
            Bd_Log::warning("神策测试数据插入失败,distinct_id:{$distinceId}, event:{$event},
            properties:" . json_encode($properties) );
        }
        return $ret;
    }
    
    /**
     * @获取模块对应的log绝对地址
     */
    private static function getFile() {
        if(!self::$logFile) {
            $prefix = self::getLogPrefix(); 
            $path = self::getLogPath();
            self::$logFile = "{$path}/{$prefix}/" . self::PRE_FILE . ".log.new";
        }
        return self::$logFile;
    }

    /**
     * @基础的数据获取
     */
    private static function standardProperty() {
        self::$properties['$ip'] = CLIENT_IP;
        //设备信息
        $versionInfo = Hk_Util_Client::getVersion();
        self::$properties['$os'] = $versionInfo['type'];
        //版本
        self::$properties['$app_version'] = self::$_requestParam['vcname'] ?
        self::$_requestParam['vcname'] : '';
    }

    /**
     * @神策数据写入
     * @params array $appendProperty 基础信息之外的属性信息
     */
    private static function writeShence(array $appendProperty) {
        //基本属性写入
        self::standardProperty();
        $properties = array_merge(self::$properties, $appendProperty);
        $contents = array(
            'distinct_id' => strval(self::$distinctId), 
            'time' => time(),
            'type' => 'track',
            'event' => self::$event,
            'properties' => $properties,
        );     
        return file_put_contents(self::$logFile , json_encode($contents, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    }
}


/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
