<?php


/**
 * 系统通知消息客户端，系统通知需指定模板
 *
 * @filesource hk/service/MsgClient.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-07-07
 */
class Hk_Service_MsgClient {


    const MTYPE_LIKE    = 3;
    const MTYPE_COMMENT = 4;

    private $appId;
    private static $accessApps = array(     # 已对接appId
        Hk_Const_AppId::APP_HOMEWORK,
    );

    public function __construct($appId) {
        if (!in_array($appId, self::$accessApps)) {
            Bd_Log::warning("{$appId} access denied");
            return false;
        }
        $this->appId = $appId;
    }

    /**
     * 发送系统通知，如果不指定用户将发送给所有用户<br>
     * 模板申请请联系平台
     *
     * @param int         $tplId
     * @param array       $contentCtx
     * @param array       $urlCtx
     * @param array       $uids
     * @return boolean
     */
    public function sendNotice($tplId, array $uids, array $contentCtx = array(), array $urlCtx = array()) {
        $input  = array(
            "appId"  => $this->appId,
            "uids"   => $uids,
            "tplId"  => intval($tplId),
            "contentCtx" => $contentCtx,
            "urlCtx"     => $urlCtx,
        );
        $method = "sendNotice";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 发送点赞和评论通知消息，即MTYPE_LIKE|MTYPE_COMMENT<br>
     * payload字段随类型不同进行校验，字段如下：<br>
     * <code>
     * array(<br>
     *     # 点赞通知<br>
     *     MTYPE_LIKE => array(<br>
     *         "url"   => "string",        # 必填<br>
     *         "refer" => array(           # 选填，二选一<br>
     *             "image"   => "string",<br>
     *             "content" => "string",<br>
     *         ),<br>
     *     ),<br>
     *     # 评论通知<br>
     *     MTYPE_COMMENT => array(<br>
     *         "url"   => "string",        # 必填<br>
     *         "reply" => "string",        # 必填<br>
     *         "refer" => array(           # 选填，二选一<br>
     *             "image"   => "string",<br>
     *             "content" => "string",<br>
     *         ),<br>
     *     ),<br>
     * );<br>
     * </code>
     *
     * @param int         $suid
     * @param array       $uids
     * @param int         $mType
     * @param array       $payload
     * @param boolean     $isPush
     * @return boolean
     */
    public function sendMsg($suid, array $uids, $mType, array $payload, $isPush = false) {
        $input  = array(
            "appId"   => $this->appId,
            "suid"    => $suid,
            "uids"    => $uids,
            "mType"   => $mType,
            "payload" => $payload,
            "isPush"  => $isPush,
        );
        $method = "sendMsg";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 获取指定名称徽标列表<br>
     * 如果指定徽标和用户相关，必须传递用户uid
     *
     * @param array       $names
     * @param int         $uid
     * @return mixed:array|boolean
     */
    public function getBadges(array $names, $uid = 0) {
        $input  = array(
            "appId" => $this->appId,
            "names" => $names,
            "uid"   => $uid,
        );
        $method = "getBadges";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return $ret["data"];
    }

    /**
     * 覆盖设置徽标值
     *
     * @param string      $name
     * @param int         $value
     * @param int         $uid
     * @return boolean
     */
    public function setBadge($name, $value, $uid = 0) {
        $input  = array(
            "appId" => $this->appId,
            "name"  => $name,
            "value" => $value,
            "uid"   => $uid,
        );
        $method = "setBadge";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 递增/递减设置徽标值
     *
     * @param string      $name
     * @param int         $value
     * @param int         $uid
     * @return boolean
     */
    public function incrBadge($name, $value, $uid = 0) {
        $input  = array(
            "appId" => $this->appId,
            "name"  => $name,
            "value" => $value,
            "uid"   => $uid,
        );
        $method = "incrBadge";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return $ret["data"];
    }

    /**
     * 清空徽标以及对应的值
     *
     * @param array       $names
     * @param int         $uid
     * @return boolean
     */
    public function clearBadges(array $names, $uid) {
        $input  = array(
            "appId" => $this->appId,
            "names" => $names,
            "uid"   => $uid,
        );
        $method = "clearBadges";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 获取指定用户指定产品的消息开关设置
     *
     * @param int    $uid
     * @param string $product   产品线标识
     * @return mixed
     */
    public function getUserSwitch($uid, $product) {
        $input  = array(
            'uid'     => $uid,
            'product' => $product,
        );
        $method = 'getUserSwitch';
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret['errno'] > 0) {
            return false;
        }
        return $ret['data'];
    }

    /**
     * 设置用户消息开关
     *
     * @param int    $uid
     * @param string $type      开关标识
     * @param int    $close     开关状态
     * @param string $product   产品线标识
     * @return bool
     */
    public function setUserSwitch($uid, $type, $close, $product) {
        $input  = array(
            'uid'     => $uid,
            'type'    => $type,
            'close'   => $close,
            'product' => $product,
        );
        $method = 'setUserSwitch';
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret['errno'] > 0) {
            return false;
        }
        return true;
    }

    private function callRpc($method, $input) {
        $srvName = "sysmessage";
        return Hk_Service_Rpc::call($srvName, $method, $input);
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
