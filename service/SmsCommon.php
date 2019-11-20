<?php
/***************************************************************************
 *
 * Copyright (c) 2016 Zuoyebang.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file SmsCommon.php
 * @author liujinghui(liujinghui@zuoyebang.com)
 * @date 2016/04/19
 * @brief 短信验证码公共调用接口（对短信服务进行phplib封装，如不愿意直接ral调用可使用此类）
 **/
class Hk_Service_SmsCommon {


    const SMS_ACTSCTRL_ERR  = 22000;
    const SMS_TPLID_ERR     = 22001;
    const SMS_SEND_FAILED   = 22002;
    const SMS_SEND_PARAMERR = 22003;

    const TOKEN_VALID_TIME  = 300;  # 短信验证码时效
    const TOKEN_RETRY_TIMES = 10;   # 短信验证码最大验证次数

    private static $errNo  = 0;

    /**
     * 发送短信验证码
     *
     * @param  int    $phone         手机号
     * @param  int    $randToken     随机验证码
     * @param  int    $availableTime 有效时间（百度暂无）
     * @param  string $service       短信服务商
     * @param  array  $extra         扩展字段 [appId,pkgName]
     * @return bool   true/false
     */
    public static function sendRandToken($phone, $randToken, $availableTime = 5, $service = '' ,$extra = []){
        $arrHeader = array(
            'cookie'   => $_COOKIE,
            'pathinfo' => '/sms/api/sendrandtoken',
        );
        $arrParams = array(
            'phone'     => $phone,
            'randtoken' => $randToken,
            'extra'     => [],
        );
        if (!empty($service)) {
            $arrParams['service'] = strval($service);
        }
        if(!empty($extra)){
            $arrParams['extra'] = $extra;
        }


        $ret = ral('zyb-sms', 'POST', $arrParams, 123, $arrHeader);
        if (false === $ret) {
            $errNo  = ral_get_errno();
            $errMsg = ral_get_error();
            $status = ral_get_protocol_code();
            self::$errNo = self::SMS_SEND_FAILED;
            Bd_Log::warning("Error[sms_service ral connect error] Detail[errno:$errNo errmsg:$errMsg protocol_status:$status params:".json_encode($arrParams)."]");
        }
        Bd_Log::addNotice("SMS_MODEL_NAME", MAIN_APP);

        $ret = @json_decode($ret, true);
        if (isset($ret['errNo']) && $ret['errNo'] != 0) {
            if (intval($ret['errNo']) === 1) {
                self::$errNo = self::SMS_SEND_PARAMERR;
                Bd_Log::warning("Error[param error] Detail[phone:$phone randToken:$randToken params:".json_encode($arrParams)."]");
                return false;
            } elseif (intval($ret['errNo']) === 22000) {
                self::$errNo = self::SMS_ACTSCTRL_ERR;
                Bd_Log::warning("Error[checkActsCtrl error] Detail[phone:$phone randToken:$randToken params:".json_encode($arrParams)."]");
                return false;
            }
        }
        if (isset($ret['data']['sendResult']) && $ret['data']['sendResult'] == 'success') {
            return true;
        }
        Bd_Log::warning("Error[smsSend error] Detail[phone:$phone randToken:$randToken params:".json_encode($arrParams)."]");
        self::$errNo = self::SMS_SEND_FAILED;
        return false;
    }

    /**
     * 按模板ID发送短信-通用接口
     *
     * @param  string $phone        手机号，多个手机号则用半角逗号分隔
     * @param  array  $datas        内容数据
     * @param  string $tempId       模板Id
     * @param  int    $skipCtrl     是否忽略频率控制
     * @param  string $service      短信服务商（不指定则系统随机指定服务商发送）
     * @param  int    $isMarketing  是否营销短信（如指定服务商为百度或容联，请务必填上是否营销短信参数！！！！！否则统计和发送都会异常！！！）
     * @return bool true/false
     */
    public static function sendSmsByTemplateId($phone, $datas = array(), $tempId = 0, $skipCtrl = 0, $service = '', $isMarketing = 0){
        if (empty($phone)) {        // 参数检查
            self::$errNo = self::SMS_SEND_PARAMERR;
            Bd_Log::warning("Error[param error] Detail[phone:$phone tempId:$tempId]");
            return false;
        }

        $arrHeader = array(
            'cookie'   => $_COOKIE,
            'pathinfo' => '/sms/api/sendbytemplateid',
        );
        $arrParams = array(
            'phone'      => $phone,
            'templateid' => $tempId,
        );

        if (!empty($datas) && is_array($datas)) {
            $arrParams['params'] = json_encode($datas);
        }
        if ($skipCtrl === 1 || $skipCtrl === true) {             // 为了兼容老的使用方式，此处加了bool兼容
            $arrParams['skipctrl'] = 1;
        }
        if (!empty($service)) {
            $arrParams['service'] = strval($service);
        }
        if (intval($isMarketing) === 1 && ($service == 'yun' || $service == 'ronglian')) {
            $arrParams['ismarketing'] = 1;
        }

        $ret = ral('zyb-sms', 'POST', $arrParams, 123, $arrHeader);
        if (false === $ret) {
            $errNo  = ral_get_errno();
            $errMsg = ral_get_error();
            $status = ral_get_protocol_code();
            self::$errNo = self::SMS_SEND_FAILED;
            Bd_Log::warning("Error[sms_service ral connect error] Detail[errno:$errNo errmsg:$errMsg protocol_status:$status params:".json_encode($arrParams)."]");
        }
        $ret = json_decode($ret, true);
        if (isset($ret['errNo']) && $ret['errNo'] != 0) {
            if (intval($ret['errNo']) === 1) {
                self::$errNo = self::SMS_SEND_PARAMERR;
                Bd_Log::warning("Error[param error] Detail[phone:$phone tempId:$tempId params:".json_encode($arrParams)."]");
                return false;
            } elseif (intval($ret['errNo']) === 22000) {
                self::$errNo = self::SMS_ACTSCTRL_ERR;
                Bd_Log::warning("Error[checkActsCtrl error] Detail[phone:$phone tempId:$tempId params:".json_encode($arrParams)."]");
                return false;
            } elseif (intval($ret['errNo']) === 22001) {
                self::$errNo = self::SMS_TPLID_ERR;
                Bd_Log::warning("Error[tempId error] Detail[phone:$phone tempId:$tempId params:".json_encode($arrParams)."]");
                return false;
            }
        }
        if (isset($ret['data']['sendResult']) && $ret['data']['sendResult'] == 'success') {
            return true;
        }
        Bd_Log::warning("Error[smsSend error] Detail[phone:$phone tempId:$tempId params:".json_encode($arrParams)."]");
        self::$errNo = self::SMS_SEND_FAILED;
        return false;
    }

    /**
     * 发送短信验证码
     * 设置redis信息，用于短信验证码的验证
     *
     * @param  int    $phone 登录标识
     * @param  string $tokenType  验证码类型
     * @param  string $service  服务商类型
     * @param  string $os    app类型
     * @param  string $vc    app版本
     * @return bool   true/false
     */
    public static function sendSmsVerifyCode($phone, $tokenType, $service = 'yun') {
        //短信随机验证码300s内有效
        $conf = Bd_Conf::getConf("/hk/redis/session");
        $objCache = new Hk_Service_Redis($conf['service']);
        $cacheKey = $conf['keys'][$tokenType]. $phone;
        $cacheVal = $objCache->get($cacheKey);
        $arrValue = json_decode($cacheVal, true);
        $intCreateTime = isset($arrValue['createTime']) ? intval($arrValue['createTime']) : 0;
        $intNowTime = time();
        $intIsChecked = isset($arrValue['isChecked']) ? intval($arrValue['isChecked']) : 0;
        if(($intNowTime - $intCreateTime) >= self::TOKEN_VALID_TIME || !isset($arrValue['randToken']) || $intIsChecked === 1) {
            $arrValue = [
                'randToken'  => rand(100000,999999),
                'createTime' => time(),
                'checkTimes' => 0,
                'isChecked'  => 0,
            ];
            $ret = $objCache->setex($cacheKey, json_encode($arrValue), 3600);//验证码redis key一小时有效，但可验证时间只有300s既5分钟
            Hk_Util_Log::setLog('sendSmsVerifyCode', json_encode($arrValue));
            if(false === $ret) {
                Bd_Log::warning("Error:[redis setex error], Detail:[key:$cacheKey]");
                return false;
            }
        }

        //发送短信验证码
        $randToken = $arrValue['randToken'];

        //新短信服务
        $arrHeader = array(
            'cookie'   => $_COOKIE,
            'pathinfo' => '/sms/api/sendrandtoken',
        );
        $arrParams = array(
            'phone'     => $phone,
            'randtoken' => $randToken,
            'service'   => $service,
        );
        $ret = ral('zyb-sms', 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errNo           = ral_get_errno();
            $errMsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error[new_sms_service connect error] Detail[errno:$errNo errmsg:$errMsg protocol_status:$protocol_status params:".json_encode($arrParams)."]");
        }
        $ret = json_decode($ret, true);
        if(!isset($ret['errNo']) || $ret['errNo'] != 0 || !isset($ret['data']['sendResult']) || $ret['data']['sendResult'] != 'success'){
            $ret = false;
        }
        if(false === $ret) {
            Bd_Log::warning("Error:[token message send error], Detail:[phone:$phone randToken:$randToken]");
            return false;
        }

        return true;
    }

    /**
     * 校验短信验证码
     *
     * @param   int     $phone      登录标识
     * @param   string  $tokenType  验证码类型
     * @param   int     $randToken  验证码
     * @return  bool    true/false  通过/失败
     */
    public static function checkSmsVerifyCode($phone, $tokenType, $randToken) {

        $conf = Bd_Conf::getConf("/hk/redis/session");
        $objCache = new Hk_Service_Redis($conf['service']);
        $cacheKey = $conf['keys'][$tokenType]. $phone;
        $cacheVal = $objCache->get($cacheKey);
        Hk_Util_Log::setLog('checkRandToken_cacheKey', $cacheKey);

        //服务端无法读出验证码-超过1小时才进行校验或redis出现问题
        $arrValue = json_decode($cacheVal, true);
        if(!is_array($arrValue) || !isset($arrValue['randToken'])){
            Hk_Util_Log::setLog('checkRandToken_message', 'unvalid');
            Bd_Log::warning("Error:[token unvalid], Detail:[phone:$phone userInputRandToken:$randToken]");
            return false;
        }

        $intIsChecked = isset($arrValue['isChecked']) ? intval($arrValue['isChecked']) : 0;
        //服务端读出验证码，但判断该验证码已经被校验过，不能再次校验
        if($intIsChecked === 1) {
            Hk_Util_Log::setLog('checkRandToken_message', 'hadChecked');
            Bd_Log::warning("randToken had checked {$phone}, {$randToken}, {$arrValue[randToken]}");
            return false;
        }

        $intCreateTime = isset($arrValue['createTime']) ? intval($arrValue['createTime']) : 0;
        if((time() - $intCreateTime) > self::TOKEN_VALID_TIME) {
            Hk_Util_Log::setLog('checkRandToken_message', 'timeout');
            Bd_Log::warning("randToken had checked {$phone}, {$randToken}, {$arrValue[randToken]}");
            return false;
        }

        //短信验证码连续试错超过10次认定为作弊
        if($arrValue['checkTimes'] >= self::TOKEN_RETRY_TIMES) {
            Hk_Util_Log::setLog('checkRandToken_message', 'antispam');
            Bd_Log::warning("randToken had checked {$phone}, {$randToken}, {$arrValue[randToken]},{$arrValue[checkTimes]}");
            return false;
        }

        //校验短信验证码
        if($arrValue['randToken'] != $randToken) {
            $arrValue['checkTimes']++;
            $ret = $objCache->setex($cacheKey, json_encode($arrValue), 3600);//验证码redis key一小时有效，但可验证时间只有300s既5分钟
            Hk_Util_Log::setLog('checkRandToken_message', 'failed');
            return false;
        }

        $arrValue['isChecked'] = 1;

        $ret = $objCache->setex($cacheKey, json_encode($arrValue), 3600);//验证码redis key一小时有效，但可验证时间只有300s既5分钟
        Hk_Util_Log::setLog('checkRandToken_message', 'success');
        return true;
    }

    public static function getErrno() {
        return self::$errNo;
    }
}