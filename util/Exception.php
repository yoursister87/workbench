<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @brief 通用异常处理流程
 *
 * @since 1.6 2017-10-11 去除vc和os判断
 * @since 1.5 2016-11-28 增加重登机制
 * @since 1.0 2013-12-26 初始化
 *
 * @filesource hk/util/Exception.php
 * @version 1.6
 * @date    2017-10-11
 */
class Hk_Util_Exception extends Exception {


    const TRACE   = 'trace';
    const DEBUG   = 'debug';
    const NOTICE  = 'notice';
    const WARNING = 'warning';
    const FATAL   = 'fatal';

    protected $errno;
    protected $errstr;
    protected $arg;
    protected $errmsg;        // 错误号对应的错误信息
    protected $errext;        // 附加信息

    /**
     * @param int    $errno      传入的错误号
     * @param string $errext     附加信息
     * @param array  $arg        上下文
     * @param string $level      日志打印级别
     * @return void
     */
    public function __construct($errno, $errext = '', $arg = array(), $level = self::WARNING) {
        $this->errno  = $errno;
        $this->errext = $errext;
        $this->arg    = $arg;
        if (empty($this->arg) || !is_array($this->arg)) {
            $this->arg = array();
        }

        $this->errmsg = Hk_Util_ExceptionCodes::getErrMsg($errno);
        $this->errstr = $this->errmsg . ('' != $errext ? " -- {$errext}" : '');
        if (Hk_Util_ExceptionCodes::USER_NOT_LOGIN === $errno) {            # 对USER_NOT_LOGIN进行统一重新处理
            $this->userNotLoginHandler();
        }

        $stackTrace   = $this->getTrace();
        $class        = @$stackTrace[0]['class'];
        $type         = @$stackTrace[0]['type'];
        $function     = @$stackTrace[0]['function'];
        $file         = $this->file;
        $line         = $this->line;
        if (null != $class) {
            $function = "{$class}{$type}{$function}";
        }
        if (empty($level)) {
            $level    = self::WARNING;
        }
        Bd_Log::$level("{$this->errstr} at [{$function} at {$file}:{$line}]", $this->errno, $this->arg, 1);
        parent::__construct($this->errstr, $errno);
    }

    public function getErrNo() {
        return $this->errno;
    }

    public function getErrStr() {
        return $this->errstr;
    }

    public function getErrMsg() {
        return $this->errmsg;
    }

    public function getErrExt() {
        return $this->errext;
    }

    public function getErrArg() {
        return $this->arg;
    }

    /**
     * 对用户登录异常ERROR_CODE = USER_NOT_LOGIN进行统一进行处理<br>
     * 使用saf获取session当前状态，并进行以下判断<br>
     * 1、session为空，直接返回登录已过期<br>
     * 2、session正常，由于数据异常造成登录状态异常，也会提示用户重新登录<br>
     * 3、session被标记成过期，按照以下两种情况处理（根据vc区别对待）<br>
     *  a、老版本按照未登录处理<br>
     *  b、新版本可以实现session替换（使用将errno替换成USER_SESSION_KICKED实现）<br>
     */
    protected function userNotLoginHandler() {
        $session  = Saf_SmartMain::checkSesssionStatus();
        if (false === $session) {                 # session已经失效，登录已经过期
            Bd_Log::addNotice("notLogin", "sessionExpire");
            $this->errstr = '登录已过期，请重新登录';
            return;
        } elseif (true === $session) {            # session还有效，但被异常踢出（一般redis错误），记录暂时无法处理
            Bd_Log::addNotice("notLogin", "unexpectLogout");
            $this->errstr = '登录状态异常，请稍后重新登录';
            return;
        }

        $kickUid  = $session["kickUid"];
        $kickNo   = $session["kickNo"];
        $kickMsg  = $session["kickMsg"];
        if (5 !== $kickNo) {                      # 只要单点被踢出的用户才能使用恢复session功能
            Bd_Log::addNotice("notLogin", "sessionExpire");
            $this->errstr = $kickMsg;
            return;
        }

        # session单点登出处理
        Bd_Log::addNotice("notLogin", "sessionUniq");

        $this->errno  = Hk_Util_ExceptionCodes::USER_SESSION_KICKED;
        $this->errstr = Hk_Util_ExceptionCodes::$errMsg[$this->errno];
        return;
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
