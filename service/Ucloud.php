<?php


/**
 * 调用Ucloud Rpc以及回调nmq封装业务逻辑<br>
 * 请注意：平台维护，在使用此类方法之前先和平台沟通，请勿随意修改此文件<br>
 * 有权限控制，请调用前先联系平台，tangyang@zuoyebang.com
 *
 * @since 1.5 2018-06-28 新增账号状态选项以及操作
 * @since 1.2 2016-07-12
 *
 * @filesource hk/service/Ucloud.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.5
 * @date    2018-06-28
 */
class Hk_Service_Ucloud extends Hk_Const_ActionNo {


    const ACC_STATUS_OK   = 0;              # 账号正常
    const ACC_STATUS_LOCK = 1;              # 锁定用户账号

    private $serviceName = "ucloud";

    /**
     * 根据用户ucloud中ext字段accountStatus解析用户状态
     *
     * @param string       $accStatus
     * @return int
     */
    public static function getAccountStatus($accStatus) {
        if ("" === $accStatus) {
            return self::ACC_STATUS_OK;
        }
        list($status, $deadline) = explode("_", $accStatus);
        $status    = intval($status);
        $deadline  = intval($deadline);
        if (0 === $status) {                                # 账号状态正常
            return self::ACC_STATUS_OK;
        }
        if (0 === $deadline || time() <= $deadline) {       # 状态永久|还未过期
            return $status;
        }
        return self::ACC_STATUS_OK;
    }

    /**
     * 根据用户唯一标示获取用户的uid<br>
     * 只能在后台功能使用，禁止线上功能使用此函数
     *
     * @param string     $val
     * @param string     $type
     * @return mixed:int|boolean
     */
    public function getUserUid($val, $type = "phone") {
        $input  = array(
            "type" => $type,
            "val"  => strval($val),
        );
        $method = "getUserUid";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return $ret["data"];
    }

    /**
     * 根据用户uid获取unionId
     *
     * @param int        $uid
     * @param string     $oauthType
     * @return mixed:string|boolean
     */
    public function getUserOuid($uid, $oauthType) {
        $input  = array(
            "uid"       => $uid,
            "oauthType" => $oauthType,
        );
        $method = "getUserOuid";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return $ret["data"];
    }

    /**
     * 添加appUcloud用户
     *
     * @param int        $uid
     * @param int        $sex
     * @param int        $grade
     * @param string     $phone
     * @param string     $uname
     * @param string     $avatar
     * @param array      $ext
     * @return boolean
     */
    public function addAppUser($uid, $sex, $grade, $phone, $uname, $avatar, array $ext) {
        $input  = array(
            "uid"    => $uid,
            "sex"    => $sex,
            "grade"  => $grade,
            "phone"  => strval($phone),
            "uname"  => strval($uname),
            "avatar" => $avatar,
            "ext"    => $ext,
        );
        $method = "addAppUser";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 更新用户数据，禁止更新wealth, experience, ext字段
     *
     * @param int        $uid
     * @param array      $data
     * @return boolean
     */
    public function updateAppUser($uid, array $data) {
        if (empty($data)) {
            return false;
        }
        $input  = array(
            "uid"  => $uid,
            "data" => $data,
        );
        $method = "updateAppUser";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 设置用户账号状态，$deadline代表设置截止时间<br>
     * $deadline = 0: 永久操作
     *
     * @param int        $uid
     * @param int        $status
     * @param int        $deadline
     * @return boolean
     */
    public function setAccountStatus($uid, $status, $deadline) {
        $allowed = array(
            self::ACC_STATUS_OK,
            self::ACC_STATUS_LOCK,
        );
        if (!in_array($status, $allowed)) {
            return false;
        }
        $ext     = array(
            "accountStatus" => sprintf("%d_%d", $status, $deadline),
        );
        return $this->updateAppUserExt($uid, $ext);
    }

    /**
     * 更新用户ext字段，此函数只允许用户更新直接更新的字段，并在白名单的字段列表<br>
     * 2016-12-03：新增特定的ext字段的请求延迟更新，防止后端调用量过大
     *
     * @param int        $uid
     * @param array      $ext
     * @return boolean
     */
    public function updateAppUserExt($uid, array $ext) {
        if (empty($ext)) {
            return false;
        }

        $delayed  = false;
        $delayExt = array(              # 要延迟更新的字段
            array(
                "loginAddExpTime" => 1,
            ),
            array(
                "lbsId"           => 1,
                "lbsUploadTime"   => 1,
            ),
        );
        foreach ($delayExt as $fields) {
            $pcount      = count($fields);
            if ($pcount >= count($ext) && $pcount >= count(array_merge($fields, $ext))) {   # 主要比较逻辑：比较参数个数是否一致，以及合并后的参数个数是否有变化
                $delayed = true;
                break;
            }
        }

        $method   = "updateAppUserExt";
        $input    = array(
            "uid" => $uid,
            "ext" => $ext,
        );
        if (false === $delayed) {
            $ret  = $this->callRpc($method, $input);
            return false === $ret || $ret["errno"] > 0 ? false : true;
        } else {        # 延迟更新数据渠道
            return $this->delayUpdateChannel($method, $input);
        }
    }

    /**
     * 延迟通道更新用户ext数据，适用于更新ext字段失败或者有延迟需求
     *
     * @param int         $uid
     * @param array       $ext
     * @return boolean
     */
    public function updateAppUserExtDelay($uid, array $ext) {
        if (empty($ext)) {
            return false;
        }

        $method = "updateAppUserExt";
        $input  = array(
            "uid" => $uid,
            "ext" => $ext,
        );
        return $this->delayUpdateChannel($method, $input);
    }

    /**
     * 更新用户ext数字增/减字段，如果有此需求请使用此函数
     *
     * @param int        $uid
     * @param array      $ext
     * @return boolean
     */
    public function updateAppUserNumExt($uid, array $ext) {
        if (empty($ext)) {
            return false;
        }
        $input  = array(
            "uid" => $uid,
            "ext" => $ext,
        );
        $method = "updateAppUserNumExt";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 更新用户财富数据<br>
     * $exp/$wealth为负，代表消费/扣除
     *
     * @param int        $uid
     * @param int        $exp
     * @param int        $wealth
     * @param int        $actionNo
     * @return boolean
     */
    public function updateUserWealth($uid, $exp, $wealth, $actionNo) {
        $input  = array(
            "uid"        => $uid,
            "experience" => $exp,
            "wealth"     => $wealth,
            "actionNo"   => $actionNo,
        );
        $method = "updateUserWealth";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * @breif 生成一个作业帮学号<br>
     * 跟用户省份相关，这里只提供序列号生成服务，不会判断学生是否已经有了
     * 序号，请在具体业务中判断
     *
     * @param int   $uid | 用户uid
     * @return string
     */
    public function getStuSequence($uid) {
        $input  = array(
            "uid" => $uid,
        );
        $method = "getStuSequence";
        $ret    = $this->callRpc($method, $input);
        return false === $ret || $ret["errno"] != 0 ? false : $ret;
    }

    /**
     * 延迟更新通道请求，用于需要延迟更新的ucloud请求
     *
     * @param string       $method
     * @param array        $input
     * @return boolean
     */
    private function delayUpdateChannel($method, $input) {
        $commandNo  = Hk_Const_Command::CMD_UCLOUD_UPDELAY;
        $actionBody = array(
            "method" => $method,
            "input"  => $input,
        );

        $nmqService = new Hk_Service_Nmq();
        $ret        = $nmqService->talkToQcm($commandNo, $actionBody, 'ucloud');
        if (false === $ret) {
            Bd_Log::addNotice("rpcCall", "talkToQcmFailed");
            return false;
        }
        return true;
    }

    private function callRpc($method, $input) {
        return Hk_Service_Rpc::call($this->serviceName, $method, $input);
    }

    /**
     * 动作调用callback, 在相关action执行时调用<br>
     * 将任务相关commandNo以及必要参数通过nmq传递给后方异步调用<br>
     *
     * 2016-11-18 增加callback调用失败情况日志
     *
     * @param int          $commandNo
     * @param array        $body
     * @return boolean
     */
    public static function actionCallback($commandNo, array $body) {
        $mark       = "ucCallback_" . $commandNo;
        $actionBody = self::actionBodyValid($body);
        if (false === $actionBody) {
            Bd_Log::addNotice($mark, "invalidBody");
            return false;
        }

        $nmqService = new Hk_Service_Nmq();
        $ret        = $nmqService->talkToQcm($commandNo, $actionBody, 'ucloud');
        if (false === $ret) {
            Bd_Log::addNotice($mark, "talkToQcmFailed");
            $errArg = array(
                "action" => "ucloud",
                "cmdNo"  => $commandNo,
                "body"   => json_encode($actionBody),
            );

            # TODO 如果发送到nmq失败，降级调用rpc进行同步处理
            $ucloudSrv = new Hk_Service_Ucloud();
            $ret       = $ucloudSrv->callback($commandNo, $actionBody);
            return $ret;
        }
        return true;
    }

    /**
     * 动作加分降级回调接口，只有在nmq调用失败时才会使用此接口，并且不对外
     *
     * @param int          $commandNo
     * @param array        $actionBody
     * @return boolean
     */
    private function callback($commandNo, $actionBody) {
        $input  = array(
            "commandNo"  => $commandNo,
            "actionBody" => $actionBody,
        );
        $method = "actionCallback";
        $ret    = $this->callRpc($method, $input);
        if (false === $ret || $ret["errno"] > 0) {
            return false;
        }
        return true;
    }

    /**
     * 对action的body数据进行校验和转换<br>
     * 其中：time, vc, osType会自动获取，不需要传递<br>
     *
     * @param array       $body
     * @return mixed
     */
    private static function actionBodyValid(array $body) {
        $uid  = isset($body["uid"]) ? intval($body["uid"]) : 0;
        if (0 === $uid) {          # uid为空，直接返回false
            return false;
        }

        $exp    = isset($body["exp"])    ? intval($body["exp"])    : 0;
        $wealth = isset($body["wealth"]) ? intval($body["wealth"]) : 0;
        $time   = time();

		unset($body['uid']);
		if (isset($body['exp'])) {
			unset($body['exp']);
		}
		if (isset($body['wealth'])) {
			unset($body['wealth']);
		}
        $ext    = $body;            # 扩展字段为body剩下的字段
        $appVersion  = Hk_Util_Client::getVersion();
        $appTerminal = Hk_Util_Client::getTerminal();

        $vc     = intval($appVersion["versionCode"]);
        $osType = strval($appVersion["type"]);
        $cuid   = strval($appTerminal["cuid"]);
        return array(
            "uid"    => $uid,
            "cuid"   => $cuid,
            "vc"     => $vc,
            "osType" => $osType,
            "exp"    => $exp,
            "wealth" => $wealth,
            "ext"    => $ext,
            "time"   => $time,
        );
    }
}

/* vim: set ft=php expandtab ts=4 sw=4 sts=4 tw=0: */
