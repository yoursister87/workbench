<?php


/**
 * 新版推送客户端，推送走新版本推送服务端：optimus<br>
 * 支持cuid和uid维度推送，支持广播，只支持url或者app自有协议跳转<br>
 * 由于只支持url和native，所以具体的参数请通过url/native的<b>参数</b>实现
 *
 * @filesource hk/service/PushClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-05-28
 */
class Hk_Service_PushClient {


    const MOD_NORMAL = 1;           # 普通消息

    const MT_URL     = 1;           # url跳转
    const MT_NATIVE  = 2;           # native跳转

    const UT_CUID    = 1;           # cuid推送
    const UT_UID     = 2;           # uid推送

    # 当前已对接appId列表
    private static $accessApps = array(
        Hk_Const_AppId::APP_HOMEWORK,       # 作业帮
        Hk_Const_AppId::APP_PARENT,         # 作业帮家长版
        Hk_Const_AppId::APP_AIRCLASS,       # 一课
        Hk_Const_AppId::APP_YKTEACHER,      # 一课教师端
        Hk_Const_AppId::APP_DYTEACHER,      # 答疑教师端
    );

    /**
     * 判断产品线是否已接入push
     *
     * @param string       $appId
     * @return boolean
     */
    public static function isAppAccessed($appId) {
        return in_array($appId, self::$accessApps) ? true : false;
    }

    /**
     * 递交push数据到push网关<br>
     * 成功会返回push模块给的唯一id，失败返回false<br>
     * $payload字段：<br>
     * <code>
     * array(
     *     "title"    => string,        # 标题，必填
     *     "subtitle" => string,        # 子标题，选填，iOS支持，android设置无效
     *     "content"  => string,        # 内容，必填
     *     "url"      => string,        # 跳转url，选填，支持http以及native
     *     "badge"    => int,           # 推送badge，选填，根据app定义发送值，内容由推送方确定，请勿随意设置
     *     "ext"      => array(         # 特定系统支持字段，选填
     *         "sound"    => string,    # iOS推送声音
     *         "category" => string,    # iOS注册category
     *     ),
     *     "extra"    => array(         # 透传参数，选填，需要端支持才行，否则传递无效
     *         "key"  => "val",
     *         ...
     *     ),
     * );
     * </code>
     *
     * @param string      $appId    产品线Id，需要接入optimus-push才能正常使用
     * @param int         $uType    推送用户类型，MT_UID|MT_CUID
     * @param array       $users    用户列表，UT_UID: uid列表；UT_CUID: cuid列表；参数类型强校验
     * @param int         $mtType   跳转类型，MT_URL: url跳转；MT_NATIVE: native跳转，需要客户端支持
     * @param array       $payload  消息内容结构体
     * @param string      $os       推送用户列表对应os，指定os推送更快
     * @param string      $msgId    打点消息Id，不传默认分配
     * @param string      $taskId   打点任务Id，后台才需要使用
     * @return mixed:string|boolean
     */
    public static function sendPush($appId, $uType, array $users, $mtType, array $payload, $os = "", $msgId = "", $taskId = "") {
        $task     = self::buildTask($appId, $uType, $users, $mtType, $payload, $msgId, $taskId, $os);
        if (false === self::checkParam($task)) {
            return false;
        }

        # 请求push接口，发送push
        $srvName  = "push";
        $method   = "sendpush";
        $ret      = Hk_Service_Rpc::call($srvName, $method, $task);
        if (false === $ret) {
            return false;
        } elseif ($ret["errno"] !== 0) {
            return false;
        }
        return $ret["data"]["reqId"];
    }

    /**
     * push参数校验
     *
     * @param array       $task
     * @return boolean
     */
    private static function checkParam($task) {
        if ($task["taskId"] == "") {
            Bd_Log::addNotice("taskErr", "taskId");
            return false;
        }
        if (!in_array($task["appId"], self::$accessApps)) {
            Bd_Log::addNotice("taskErr", "appId");
            return false;
        }
        # os只允许空，ios和android
        if (isset($task["os"]) && $task["os"] !== "" && ($task["os"] !== "ios" && $task["os"] !== "android")) {
            Bd_Log::addNotice("taskErr", "os");
            return false;
        }
        if ($task["pushMod"] !== self::MOD_NORMAL) {            # 只允许批量消息
            Bd_Log::addNotice("taskErr", "pushMod");
            return false;
        }
        if ($task["uType"] !== self::UT_CUID && $task["uType"] !== self::UT_UID) {
            Bd_Log::addNotice("taskErr", "uType");
            return false;
        }
        if (count($task["userList"]) <= 0) {
            Bd_Log::addNotice("taskErr", "userList");
            return false;
        }

        # payload校验
        $payload = $task["payload"];
        if ($payload["msgId"] == "") {
            Bd_Log::addNotice("payloadErr", "msgId");
            return false;
        }
        if ($payload["title"] == "" || $payload["content"] == "") {
            Bd_Log::addNotice("payloadErr", "content");
            return false;
        }
        if ($payload["mtType"] !== self::MT_URL && $payload["mtType"] !== self::MT_NATIVE) {
            Bd_Log::addNotice("payloadErr", "mtType");
            return false;
        }
        if ($payload["url"] === "") {
            Bd_Log::addNotice("payloadErr", "url");
            return false;
        }
        if (!empty($payload["extra"])) {
            foreach ($payload["extra"] as $k => $v) {
                if (is_array($v)) {                         # 扩展字段val不能是数组
                    Bd_Log::addNotice("payloadErr", "extraPair");
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 生成推送任务参数，$payload字段参加sendPush定义<br>
     * optimus-push模块需要如下数据格式如下所示：<br>
     * <code>
     * array(
     *     "taskId"   => string,
     *     "appId"    => string,
     *     "pushMod"  => int,
     *     "uType"    => int,
     *     "userList" => array,
     *     "payload"  => array(
     *         "msgId"    => string,
     *         "mtType"   => int,
     *         "title"    => string,
     *         "subtitle" => string,
     *         "content"  => string,
     *         "url"      => string,
     *         "sound"    => string,
     *         "category" => string,
     *         "badge"    => int,
     *         "extra"    => array(
     *             "key" => "val",
     *             ....
     *         ),
     *     ),
     * )
     * </code>
     *
     * @param string      $appId
     * @param int         $uType
     * @param array       $users
     * @param int         $mtType
     * @param array       $pl
     * @param string      $msgId
     * @param string      $taskId
     * @param string      $os
     * @return array
     */
    private static function buildTask($appId, $uType, array $users, $mtType, array $pl, $msgId, $taskId, $os) {
        $msgId    = $msgId !== ""  ? strval($msgId) : self::genMsgId();
        $taskId   = $taskId === "" ? $msgId : $taskId;
        foreach ($users as &$user) {            # 对数据进行转换，防止类型错误
            $user = $uType === self::UT_UID ? intval($user) : strval($user);
        }

        $title    = strval($pl["title"]);
        $subTitle = strval($pl["subtitle"]);
        $content  = strval($pl["content"]);
        $url      = strval($pl["url"]);
        $badge    = isset($pl["badge"]) && is_int($pl["badge"])   ? intval($pl["badge"]) : -1;
        $ext      = isset($pl["ext"]) && is_array($pl["ext"])     ? $pl["ext"]   : array();
        $extra    = isset($pl["extra"]) && is_array($pl["extra"]) ? $pl["extra"] : array();
        $payload  = self::buildPayload($msgId, $mtType, $title, $subTitle, $content, $pl["url"], $badge, $ext, $extra);
        $task     = array(
            "taskId"   => $taskId,
            "appId"    => $appId,
            "pushMod"  => self::MOD_NORMAL,
            "uType"    => $uType,
            "userList" => $users,
            "payload"  => $payload,
        );
        if ("" !== $os) {
            $task["os"] = $os;
        }
        return $task;
    }

    /**
     * 拼接推送payload信息，$ext只能设置2个值：<br>
     * <code>
     * array(
     *     "sound"    => string,   # ios推送声音
     *     "category" => string,   # ios推送注册category
     * );
     * </code>
     *
     * @param string      $msgId
     * @param int         $mtType
     * @param string      $title
     * @param string      $subTitle
     * @param string      $content
     * @param string      $url
     * @param array       $ext
     * @param array       $extra
     * @return array
     */
    private static function buildPayload($msgId, $mtType, $title, $subTitle, $content, $url, $badge, array $ext = array(), array $extra = array()) {
        $payload = array(
            "msgId"    => $msgId,           # 消息唯一id，uda打点需求
            "mtType"   => $mtType,          # 跳转类型，对应MT_URL|MT_NATIVE
            "title"    => $title,           # 标题
            "subtitle" => $subTitle,        # 子标题，只有ios生效
            "content"  => $content,         # 内容
            "url"      => $url,             # 跳转地址，支持url和native形式
            "extra"    => (object)$extra,   # 扩展额外字段，需要端解析实现
        );

        if ($badge >= 0) {                  # 只有在badge >= 0时，设置badge才生效
            $payload["badge"] = intval($badge);
        } else {
            $payload["badge"] = -1;
        }
        if (isset($ext["sound"])) {                    # ios系统特有，android设置无效
            $payload["sound"] = strval($ext["sound"]);
        }
        if (isset($ext["category"])) {                 # ios系统特有，android设置无效
            $payload["category"] = strval($ext["category"]);
        }
        return $payload;
    }

    /**
     * 生成唯一打点msgId
     *
     * @return string
     */
    private static function genMsgId() {
        return uniqid("ph");
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
