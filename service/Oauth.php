<?php


/**
 * oauth相关接口封装，提供oauth相关接口以及代码逻辑
 *
 * @filesource hk/service/Ucloud.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-03-12
 */
class Hk_Service_Oauth {


    private static $allowed = array(
        "homework",
    );


    const CALLBACK_DOMAIN = "www.zuoyebang.com";    # 当前公司第三方平台配置的统一的回调url

    public function __construct() {
    }

    /**
     * 调用session的oauth第三方登录模块，生成跳转页面<br>
     * 如果链接错误或者无法拼接出返回链接，将返回false
     *
     * @param string      $oauthType    第三方类型，weixin|qq
     * @param string      $appId        产品线id
     * @param string      $ref          处理完成跳转回的页面
     * @return boolean
     */
    public function authorize($oauthType, $appId, $ref = "") {
        if ("" === $ref) {
            $ref = "www.zybang.com";
        }
        if (empty($appId) || in_array($appId, self::$allowed)) {  # 是否允许使用
            return false;
        }
        $authorizeUrl = sprintf("http://%s/session/oauth/authorize?type=%s&appId=%s&ref=%s", self::CALLBACK_DOMAIN, $oauthType, $appId, urlencode($ref));
        header("Location:{$authorizeUrl}");
        exit();
    }
}

/* vim: set ft=php expandtab ts=4 sw=4 sts=4 tw=0: */