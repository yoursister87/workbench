<?php

/**
 * @deprecated
 * @file SysMessage.php
 * @author guobaoshan@zuoyebang.com
 * @date 2017-06-13
 * @brief 基于Hk_Service_Message封装的发送系统消息的服务
 * 可以指定不同的产品app，具体的product在Hk_Service_Message_Const中定义
 * 使用Hk_Service_Message_Const::CHARGE，而不是SYS_NOTICE，可以避免消息存储到db的过程
 * 1. 发送纯文本消息：sendTextSysMessage
 * 2. 发送带跳转链接的消息：sendUrlSysMessage
 *
 **/

class Hk_Service_SysMessage
{
    // 跳转链接格式化pattern
    const SYSMSG_REG    = '/{urlType=\"(\d+)\" urlTitle=\"(.+)\" urlUrl=\"(.+)\"}/';

    /**
     * @brief 发送纯文本系统消息
     * @param $uid 发送给的用户id，只支持单uid发送，批量uid发送走运营后台
     * @param $title 消息标题
     * @param $content 消息内容
     * @param $product 发送到的app产品
     *
     **/
    public static function sendTextSysMessage($uid, $title, $content, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        if ($uid <= 0 || empty($title) || empty($content)) {
            Bd_Log::warning("SendTextSysMessage failed! Detail[param error!]");
            return false;
        }
        $arrMsg = array(
            'ruid'      => $uid,
            'title'     => $title,
            'content'   => $content,
            'product'   => $product,
        );
        $ret = Hk_Service_Message::sendMsg(Hk_Service_Message_Const::CHARGE, $arrMsg);
        if ($ret === false) {
            Bd_Log::warning("SendTextSysMessage failed! Detail[Hk_Service_Message error!]");
            return false;
        }
        return true;
    }

    /**
     * @brief 发送带http/https跳转链接的系统消息
     * @param $uid 发送给的用户id，只支持单uid发送，批量uid发送走运营后台
     * @param $title 消息标题
     * @param $content 消息内容
     * @param $urlTitle 跳转button文案
     * @param $urlLink 跳转链接url
     * @param $product 发送到的app产品
     *
     **/
    public static function sendUrlSysMessage($uid, $title, $content, $urlTitle, $urlLink, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        if ($uid <= 0 || empty($title) || empty($content) || empty($urlTitle) || empty($urlLink)) {
            Bd_Log::warning("SendUrlSysMessage failed! Detail[param error!]");
            return false;
        }
        $urlContent = '{urlType="1" urlTitle="'.$urlTitle.'" urlUrl="'.$urlLink.'"}';
        $content    = $content.$urlContent;
        $arrMsg = array(
            'ruid'      => $uid,
            'title'     => $title,
            'content'   => $content,
            'product'   => $product,
        );
        $ret = Hk_Service_Message::sendMsg(Hk_Service_Message_Const::CHARGE, $arrMsg);
        if ($ret === false) {
            Bd_Log::warning("SendUrlSysMessage failed! Detail[Hk_Service_Message error!]");
            return false;
        }
        return true;
    }
}

?>
