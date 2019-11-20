<?php
/***************************************************************************
 *
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file ActCtrl.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/09/02 20:55:50
 * @brief 频率控制服务
 **/
class Hk_Service_ActsCtrl {

    //本地缓存
    private static $cacheData = array();
    private static function setLocalCache($key, $data) {
        if(count(self::$cacheData) < 100) {
            self::$cacheData[$key] = $data;
        }
    }
    //频率控制支持的维度
    const KEY_UID   = 'uid';
    const KEY_CUID  = 'cuid';
    const KEY_UIP   = 'uip';
    const KEY_PHONE = 'phone';
    const KEY_URI   = 'uri';

    //频率控制服务注册号
    const COMMAND_MESSAGE               = 1;  // 短信频率控制
    const COMMAND_PICSEARCH             = 2;  // 图片检索频率控制
    const COMMAND_CRM                   = 3;  // 后台CRM
    const COMMAND_QUESTION              = 4;  // question模块接口访问频率
    const COMMAND_FUDAOCHAT             = 5;  // 辅导主讲端聊天频率控制
    const COMMAND_PLAYERLOG             = 6;  // 直播课播放器的行为日志
    const COMMAND_SCHOOLSEARCH          = 7;  // 学校搜索频率控制
    const COMMAND_API_PICSEARCH         = 8;  // 图片检索频率控制
    const COMMAND_FUDAOCHATSUBMIT       = 9;  // 直播课聊天发送频率控制
    const COMMAND_FUDAOCHATPULL         = 10; // 直播课拉取消息频率控制
    const COMMAND_ENZUOWENEVAL          = 11; // 手写英语作文批改服务
    const COMMAND_KUNPENG_APPLY_FRIEND  = 12; // zyb添加好友频率限制(鲲鹏项目)
    const COMMAND_KUNPENG_ACCEPT_FRIEND = 13; // zyb接受好友频率限制(鲲鹏项目)
    const COMMAND_KUNPENG_MSG_TASK      = 14; // zyb群发消息频率限制(鲲鹏项目)
    const COMMAND_IM_MEMBER_ADD         = 15; // IM用户加群频率控制
    const COMMAND_CODESEARCH            = 16; // 扫码频率控制
    const COMMAND_CODEANSWER            = 17; // 扫码查看答案频率控制
    const COMMAND_IM_SEND_MSG           = 18; // 发送消息频率控制
    const COMMAND_KUNPENG_GROUP_CREATE  = 19; // 建群频率限制(鲲鹏项目)
    const COMMAND_VOTE_QPS              = 20; // 投票QPS
    const COMMAND_KUNPENG_MSG_TRANSPOND = 21; // zyb转发消息频率限制(鲲鹏项目)
    const COMMAND_SESSION_PIC_ANTISPAM  = 22; // session图片验证码控制频率
    const COMMAND_FLIPPED_ORDER         = 23; // 浣熊下单控制频率

    //频率控制策略
    static public $arrStrategy = array(
        //短信发送频率控制策略
        self::COMMAND_MESSAGE => array(
            //单个手机号一天最多5次
            self::KEY_PHONE => array(
                60    => 1,
                86400 => 5,
            ),
        ),
        //图片检索频率控制策略
        //单个cuid一天最多80次
        self::COMMAND_PICSEARCH => array(
            self::KEY_CUID => array(
                #60    => 8,
                #600   => 20,
                #3600  => 50,
                86400 => 300,
            ),
        ),
        //后台CRM
        self::COMMAND_CRM   => array(
            self::KEY_PHONE => array(
                86400 => 5,
            ),
        ),
        self::COMMAND_QUESTION => array(
            self::KEY_URI => array(
                3600 => 20000,//1小时2w
                60 => 1000,//1分钟
            ),
        ),
        //辅导主讲端聊天频率控制
        self::COMMAND_FUDAOCHAT => array(
            //老师每秒最多接收3条消息
            self::KEY_UID => array(
                1 => 1,
            ),
        ),
        //直播课播放器的行为日志
        self::COMMAND_PLAYERLOG => array(
            self::KEY_URI => array(
                1 => 20,    // 1秒钟20条数据写入
            )
        ),
        //直播课聊天频率控制
        self::COMMAND_FUDAOCHATSUBMIT => array(
            self::KEY_URI => array(
                1 => 1000,    // 控制QPS 1000
            )
        ),
        //直播课拉取聊天消息
        self::COMMAND_FUDAOCHATPULL => array(
            self::KEY_URI => array(
                1 => 100,    // 控制QPS 100
            )
        ),
        //学校搜索频率控制
        self::COMMAND_SCHOOLSEARCH => array(
            self::KEY_CUID => array(
                600   => 40,
                86400 => 40,
            ),
        ),
        //图片检索频率控制策略
        //单个cuid一天最多80次
        self::COMMAND_API_PICSEARCH => array(
            self::KEY_CUID => array(
                #60    => 8,
                600   => 30,
                #3600  => 50,
                86400 => 200,
            ),
        ),
        self::COMMAND_ENZUOWENEVAL => array(
            self::KEY_UID => array(
                86400 =>  30,
            ),
            self::KEY_URI => array(
                1 => 5,
            ),
        ),
        // zyb添加用户好友频率限制
        self::COMMAND_KUNPENG_APPLY_FRIEND => [
            self::KEY_UID   => [
                86400   => 20,
                60      => 1,
            ],
            self::KEY_PHONE => [
                3600    => 1,
            ],
        ],
        // zyb接受用户好友频率限制
        self::COMMAND_KUNPENG_ACCEPT_FRIEND => [
            self::KEY_UID => [
                86400 => 15,
                60    => 1,
            ],
        ],
        // zyb群发消息频率限制
        self::COMMAND_KUNPENG_MSG_TASK => [
            self::KEY_UID => [
                604800 => 2,
                7200   => 1,
            ],
        ],
        // zyb转发消息频率限制
        self::COMMAND_KUNPENG_MSG_TRANSPOND => [
            self::KEY_UID => [
                86400 => 5,
                1800  => 1,
            ],
        ],
        // 建群频率限制
        self::COMMAND_KUNPENG_GROUP_CREATE => [
            self::KEY_UID => [
                86400 => 10,
                1800   => 1,
            ],
        ],
        //IM用户加群频率限制
        self::COMMAND_IM_MEMBER_ADD => [
            self::KEY_URI => [
                10800 => 3,
            ],
        ],
        //扫码搜答案频率控制
        self::COMMAND_CODESEARCH    => [
            self::KEY_CUID => [
                86400   => 100,
            ],
        ],
        self::COMMAND_CODEANSWER    => [
            self::KEY_CUID => [
                86400   => 30,
            ],
        ],
        //IM发送消息频率限制
        self::COMMAND_IM_SEND_MSG => [
            self::KEY_UID => [
                60 => 10,
                3  => 1,
            ],
        ],
        //投票系统QPS
        self::COMMAND_VOTE_QPS    => [
            self::KEY_URI => [
                1  => 100,
            ]
        ],
        # session图片验证码，86400
        self::COMMAND_SESSION_PIC_ANTISPAM => array(
            self::KEY_PHONE => array(
                86400 => 1,
            ),
        ),
        self::COMMAND_FLIPPED_ORDER => array(
            self::KEY_UID => array(
                3600 => 100,    // 1小时100
                60   => 20,     // 1分钟20
            ),
        ),
    );

    /**
     * 频率控制
     *
     * @param  int    $commandNo 频率控制服务注册号 eg: Hk_Service_ActsCtrl::COMMAND_MESSAGE
     * @param  string $actKey    频率控制支持的维度 eg: Hk_Service_ActsCtrl::KEY_PHONE
     * @param  string $actValue  维度对应的值       eg: 18600575306
     * @return bool true/false
     */
    static public function checkActsCtrl($commandNo, $actKey, $actValue) {
        if($commandNo <= 0 || strlen($actKey) <= 0 || empty($actValue)){
            Bd_Log::warning("Error:[params error], Detail:[commandNo:$commandNo actKey:$actKey actValue:$actValue");
            return false;
        }

        if(!isset(self::$arrStrategy[$commandNo]) || empty(self::$arrStrategy[$commandNo])) {
            Bd_Log::warning("Error:[actsctrl command invalid], Detail:[commandNo:$commandNo]");
            return false;
        }

        $arrCommandInfo = self::$arrStrategy[$commandNo];
        if(!isset($arrCommandInfo[$actKey]) || empty($arrCommandInfo[$actKey])) {
            Bd_Log::warning("Error:[actsctrl key invalid], Detail:[actKey:$actKey]");
            return false;
        }

        $redisConf = Bd_Conf::getConf("/hk/redis/actsctrl");
        $objRedis = new Hk_Service_Redis($redisConf['service']);
        $arrKeyInfo = $arrCommandInfo[$actKey];
        foreach($arrKeyInfo as $period => $threshold) {
            if($period <= 0) {
                Bd_Log::warning("Error:[actsctrl period invalid], Detail:[period:$period]");
                return false;
            }

            $curTime = time();
            $timeKey = intval($curTime / $period);
            $redisKey = $redisConf['keys']['actsctrl']. $commandNo. '_'. $actKey. '_'. $period. '_'. $actValue. '_'. $timeKey;
            $value = $objRedis->incr($redisKey);
            $objRedis->expire($redisKey, $period);
            self::setLocalCache($redisKey, $value);//写本地缓存
            if($value > $threshold) {
                Bd_Log::warning("Error:[actsctrl strategy hit], Detail:[command:$commandNo actKey:$actKey actValue:$actValue period:$period threshold:$threshold rediskey:$redisKey value:$value]");
                return false;
            }
        }
        return true;
    }

    /**
     * 频率控制升级版，通过时不直接返回true，还返回各维度的当前统计值
     *
     * @param  int    $commandNo 频率控制服务注册号 eg: Hk_Service_ActsCtrl::COMMAND_MESSAGE
     * @param  string $actKey    频率控制支持的维度 eg: Hk_Service_ActsCtrl::KEY_PHONE
     * @param  string $actValue  维度对应的值       eg: 18600575306
     * @return mix array/false
     */
    static public function checkActsCtrlNew($commandNo, $actKey, $actValue) {
        if($commandNo <= 0 || strlen($actKey) <= 0 || empty($actValue)){
            Bd_Log::warning("Error:[params error], Detail:[commandNo:$commandNo actKey:$actKey actValue:$actValue");
            return false;
        }

        if(!isset(self::$arrStrategy[$commandNo]) || empty(self::$arrStrategy[$commandNo])) {
            Bd_Log::warning("Error:[actsctrl command invalid], Detail:[commandNo:$commandNo]");
            return false;
        }

        $arrCommandInfo = self::$arrStrategy[$commandNo];
        if(!isset($arrCommandInfo[$actKey]) || empty($arrCommandInfo[$actKey])) {
            Bd_Log::warning("Error:[actsctrl key invalid], Detail:[actKey:$actKey]");
            return false;
        }

        $curActs = array();
        $redisConf = Bd_Conf::getConf("/hk/redis/actsctrl");
        $objRedis = new Hk_Service_Redis($redisConf['service']);
        $arrKeyInfo = $arrCommandInfo[$actKey];
        foreach($arrKeyInfo as $period => $threshold) {
            if($period <= 0) {
                Bd_Log::warning("Error:[actsctrl period invalid], Detail:[period:$period]");
                return false;
            }

            $curTime = time();
            $timeKey = intval($curTime / $period);
            $redisKey = $redisConf['keys']['actsctrl']. $commandNo. '_'. $actKey. '_'. $period. '_'. $actValue. '_'. $timeKey;
            $value = $objRedis->incr($redisKey);
            $objRedis->expire($redisKey, $period);
            self::setLocalCache($redisKey, $value);//写本地缓存
            if($value > $threshold) {
                Bd_Log::warning("Error:[actsctrl strategy hit], Detail:[command:$commandNo actKey:$actKey actValue:$actValue period:$period threshold:$threshold rediskey:$redisKey value:$value]");
                return false;
            }
            $curActs[$period] = $value;
        }
        return $curActs;
    }

    /**
     * 频率控制-----临时方案，解决上方控制不够严谨的问题
     *
     * @param  int    $commandNo 频率控制服务注册号 eg: Hk_Service_ActsCtrl::COMMAND_MESSAGE
     * @param  string $actKey    频率控制支持的维度 eg: Hk_Service_ActsCtrl::KEY_PHONE
     * @param  string $actValue  维度对应的值       eg: 18600575306
     * @return bool true/false
     */
    static public function checkSmsActsCtrl($commandNo, $actKey, $actValue) {
        if($commandNo <= 0 || strlen($actKey) <= 0 || empty($actValue)){
            Bd_Log::warning("Error:[params error], Detail:[commandNo:$commandNo actKey:$actKey actValue:$actValue");
            return false;
        }

        $date = date("Ymd");
        $curTime = time();

        $redisConf = Bd_Conf::getConf("/hk/redis/actsctrl");
        $objRedis = new Hk_Service_Redis($redisConf['service']);

        $redisKey = $redisConf['keys']['actsctrl'] . 'new_sms_date_' . $date . '_' . $actValue;
        $value = $objRedis->incr($redisKey);
        $objRedis->expire($redisKey, (86400+3600));
        self::setLocalCache($redisKey, $value);
        if($value > 5){
            Bd_Log::warning("Error:[smsactsctrl strategy hit], Detail:[daily actCtrl actValue:$actValue period:86400 threshold:5 rediskey:$redisKey value:$value]");
            return false;
        }

        $period = 60;//只能发一次的过期时间段设置
        $redisKey = $redisConf['keys']['actsctrl'] . 'new_sms_period_' . $period . '_' . $actValue;
        $value = $objRedis->get($redisKey);
        if(!empty($value)){
            if(($curTime - $value) <= $period){
                Bd_Log::warning("Error:[smsactsctrl strategy hit], Detail:[period actCtrl actValue:$actValue period:$period threshold:1 rediskey:$redisKey lastSend:$value thisSend:$curTime]");
                return false;
            }
        }
        $objRedis->setex($redisKey, $curTime, ($period*2));
        return true;
    }

    /**
     * 获取频次数值get
     *
     * @param  int    $commandNo 频率控制服务注册号 eg: Hk_Service_ActsCtrl::COMMAND_MESSAGE
     * @param  string $actKey    频率控制支持的维度 eg: Hk_Service_ActsCtrl::KEY_PHONE
     * @param  string $actValue  维度对应的值       eg: 18600575306
     * @param  int    $getPerion 时间周期的值       eg: 86400（一天），60（一分钟）
     * @return bool true/false
     */
    static public function getActsCtrlNum($commandNo, $actKey, $actValue, $getPeriod = 86400) {
        if($commandNo <= 0 || strlen($actKey) <= 0 || empty($actValue)){
            Bd_Log::warning("Error:[gactsctrlnum params error], Detail:[commandNo:$commandNo actKey:$actKey actValue:$actValue");
            return false;
        }

        if(!isset(self::$arrStrategy[$commandNo]) || empty(self::$arrStrategy[$commandNo])) {
            Bd_Log::warning("Error:[gactsctrlnum command invalid], Detail:[commandNo:$commandNo]");
            return false;
        }

        $arrCommandInfo = self::$arrStrategy[$commandNo];
        if(!isset($arrCommandInfo[$actKey]) || empty($arrCommandInfo[$actKey])) {
            Bd_Log::warning("Error:[gactsctrlnum key invalid], Detail:[actKey:$actKey]");
            return false;
        }

        $redisConf = Bd_Conf::getConf("/hk/redis/actsctrl");
        $objRedis = new Hk_Service_Redis($redisConf['service']);
        $arrKeyInfo = $arrCommandInfo[$actKey];
        foreach($arrKeyInfo as $period => $threshold) {
            if($period <= 0) {
                Bd_Log::warning("Error:[gactsctrlnum period invalid], Detail:[period:$period]");
                return false;
            }
            if($period != $getPeriod){
                continue;
            }

            $curTime = time();
            $timeKey = intval($curTime / $period);
            $redisKey = $redisConf['keys']['actsctrl']. $commandNo. '_'. $actKey. '_'. $period. '_'. $actValue. '_'. $timeKey;
            if(isset(self::$cacheData[$redisKey])){
                return self::$cacheData[$redisKey];
            }else{
                $value = $objRedis->get($redisKey);
                self::setLocalCache($redisKey, $value);
            }
            return $value;
        }
        Bd_Log::warning("Error:[getactsctrlnum getperiod invalid], Detail:[getperiod:$getPeriod]");
        return false;
    }
}
