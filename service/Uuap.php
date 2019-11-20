<?php


/**
 * 作业帮内部uuap相关封装<br>
 * 采用CAS协议封装phpcas，如果用户未登录，登录流程如下：<br>
 * 1、使用isAuthenticated判断用户是否登录，未登录将强制执行登录流程，获取用内网唯一uname<br>
 * 2、必要参数执行login，生成ZYBUUAP<br>
 * 3、登出流程
 *
 * @filesource hk/service/Uuap.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2017-11-21
 */
class Hk_Service_Uuap {


    const RC4_KEY_ZYBUUAP = '@#AI~jdWJD83#@6_E';       # 登录标识rc4加解密密钥
    const UUAP_EXPIRES    = 86400;                     # 登录UUAP过期时间指定1天

    private static $host  = "";
    private static $redis = NULL;

    private static function getRedisInst() {
        if (NULL === self::$redis) {
            $conf = Bd_Conf::getConf("/hk/redis/session");
            self::$redis = new Hk_Service_Redis($conf['service']);
        }
        return self::$redis;
    }

    /**
     * 设置cas回调host，如果不设定将从HTTP_HOST参数获取，可能不正确
     *
     * @param string      $host
     */
    public static function setHost($host) {
        self::$host = $host;
    }

    /**
     * 判断用户是否登录，并执行登录流程<br>
     * 如果已登录，将返回用户唯一的uname<br>
     * 如果未登录，将强制跳转到cas服务器认证后返回<br>
     * 登录成功，获取到用户内网唯一uname后，将生成session，生成zybuuap<br>
     * 函数将返回用户session用户信息
     *
     * @return mixed:array|boolean
     */
    public static function login() {
        $redUrl = self::getRedirectUrl();               # 设置回调地址
        Bd_PhpCas::setServiceUrl($redUrl);

        $uname  = Bd_PhpCas::isAuthenticated();

        if (false === $uname) {
            $uname  = Bd_PhpCas::login();
        }
        $loginFrom  = MAIN_APP;
        $email      = sprintf("%s@zuoyebang.com", $uname);
        $ret        = self::buildSession($uname, $email, $loginFrom);
        if (false === $ret) {
            return false;
        }
        $zybuuap    = $ret["zybuuap"];
        $userInfo   = $ret["userInfo"];

        Bd_Log::addNotice("zybuuap", $zybuuap);

        # setcookie操作
        $domain     = self::getDomain();
        $expire     = time() + self::UUAP_EXPIRES;
        setcookie('ZYBUUAP', $zybuuap, $expire, '/', $domain, false, true);
        return $userInfo;
    }

    /**
     * 退出登录，同时会退出cas登录
     *
     * 2018-10-09 支持跳转回调
     *
     * @param string      $redUrl
     * @return boolean
     */
    public static function logout($redUrl = "") {
        if (isset($_COOKIE["ZYBUUAP"])) {
            $zybuuap = $_COOKIE["ZYBUUAP"];
            self::delSession($zybuuap);

            $domain  = self::getDomain();
            $expire  = time() - 3600;
            setcookie('ZYBUUAP', "", $expire, '/', $domain, false, true);
            Bd_PhpCas::logout($redUrl);                        # cas退出登录
        }
        return true;
    }

    /**
     * 拼接默认跳转地址
     *
     * @return array
     */
    private static function getRedirectUrl() {
        if ("" === self::$host) {
            $host = $_SERVER["HTTP_HOST"];
        } else {
            $host = self::$host;
        }
        $protocal = isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && "https" === $_SERVER["HTTP_X_FORWARDED_PROTO"] ? "https" : "http";

        # 需要将ticket参数去掉，否则将无限重定向
        $reqUri   = explode('?', $_SERVER['REQUEST_URI'], 2);
        $uri      = strval($reqUri[0]);
        $query    = strval($reqUri[1]);
        if (!empty($query)) {
            $query  = preg_replace("/&ticket(=[^&]*)?|^ticket(=[^&]*)?&?/",'', $query);
        }
        return "" === $query ? sprintf("%s://%s%s", $protocal, $host, $uri) : sprintf("%s://%s%s?%s", $protocal, $host, $uri, $query);
    }

    /**
     * 获取当前setcookie请求的域名
     *
     * @return string
     */
    private static function getDomain() {
        $domain     = $_SERVER["HTTP_HOST"];
        if (false !== strpos($domain, ":")) {
            $domain = explode(":", $domain)[0];
        }
        return $domain;
    }

    /**
     * 创建内网uuap对应的session会话，成功返回zybuuap以及userInfo<br>
     * 会话结构：<br>
     * <code>
     * array(<br>
     *     "uname"       => string,     # 内网唯一标示<br>
     *     "email"       => string,     # 内网邮箱<br>
     *     "loginFrom"   => string,     # 登录来源<br>
     *     "lastLogTime" => timestamp,  # 登录时间<br>
     * );
     * </code>
     *
     * @param string      $uname
     * @param string      $email
     * @param string      $loginFrom
     * @return mixed:array|boolean
     */
    private static function buildSession($uname, $email, $loginFrom) {
        $zybuuap  = self::genZybuuap($uname, $email);
        $content  = array(
            "uname" => $uname,
            "email" => $email,
            "loginFrom"   => $loginFrom,
            "lastLogTime" => time(),
        );
        $redis    = self::getRedisInst();
        $ret      = $redis->setex($zybuuap, @json_encode($content), self::UUAP_EXPIRES);
        if (false === $ret) {
            Bd_Log::addNotice('buildUuap', 'failed');
            return false;
        }
        Bd_Log::addNotice('buildUuap', 'success');
        return array(
            "userInfo" => $content,
            "zybuuap"  => $zybuuap,
        );
    }

    /**
     * 删除用户session会话，直接删除zybuuap
     *
     * @param string      $zybuuap
     * @return boolean
     */
    private static function delSession($zybuuap) {
        $redis = self::getRedisInst();
        $ret   = $redis->del($zybuuap);
        if (false === $ret) {
            Bd_Log::addNotice('delUuap', 'failed');
            return false;
        }
        Bd_Log::addNotice('delUuap', 'success');
        return true;
    }

    /**
     * 生成zybuuap
     *
     * @param string      $uname
     * @param string      $email
     * @return string
     */
    private static function genZybuuap($uname, $email) {
        $rand    = rand(10000000, 99999999);
        $zybuuap = pack('a16vvVVV', $email, 0x029B, $uname, $rand & 0xFFFFFFFF, $rand >> 32, time());
        for ($i = 0; $i < 16; $i++) {           // padding 48 byte
            $zybuuap .= chr(48 + rand(0, 74));
        }

        $key     = md5(self::RC4_KEY_ZYBUUAP);
        $zybuuap = Hk_Util_Rc4::rc4($key, $zybuuap);
        $zybuuap = base64_encode($zybuuap);

        // URL-safe Base64
        $zybuuap = str_replace('+', '-', $zybuuap);
        $zybuuap = str_replace('/', '_', $zybuuap);
        return "UUAP_" . $zybuuap;
    }
}

/* vim: set ft=php expandtab ts=4 sw=4 sts=4 tw=0: */
