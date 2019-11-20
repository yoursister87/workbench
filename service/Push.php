<?php

/**
 * @deprecated 从2018-08-01开始，所有push都走新通道
 * 对应客户端：Hk_Service_PushClient
 * @author guobaoshan@zuoyebang.com
 * @date 2017-06-15
 * @brief 基于Message服务和fdc服务封装的(直播课)推送服务，用于取代Zhibo_Service_Push和Hk_Ds_Fudao_SysPush
 * 全部推送最终都走Message服务，不再走LCS服务
 * 支持特性：
 * 1. 支持单uid、单cuid、多cuid、多uid推送
 *    多uid和多cuid推送注意有数量限制
 *    单个uid和cuid推送时，也需要放在数组里
 * 2. 支持推送策略控制
 *    支持直接推送，即直接调用Hk_Service_Message服务推送
 *    支持异步推送，即先发给nmq暂存，然后由fdc模块处理发送（fdc也是调用的Hk_Service_Message服务推送），量大时使用
 *    支持控制推送，先发给nmq暂存，然后由fdc模块处理发送（经过fdc的控制策略），有控制策略需求时使用，如优惠券过期推送
 *    控制推送中，fdc模块支持的控制信息如下：
 *        needRetry[默认0]——如发送条件不满足，是否保存然后重试，如为0会被直接丢弃
 *        reqTime[默认now]——预设推送时间，需配合needRetry使用（需设置为1）
 *        expireTime[默认now+2h]——消息发送有效期，过期后没发送成功会被丢弃
 *        rank[默认0]——消息发送优先级，0~9，值越大优先级别越高
 *        forceSend[默认0]——是否不过策略强制发送，默认需要过策略
 * 3. 支持推送目标选择策略控制
 *    支持多app中随机选择策略
 *    支持多app全部推送策略
 *    支持多app按顺序优先有效推送策略，默认一课优先（直播业务的策略）
 * 4. 支持纯文本信息push、带跳转链接push、IM消息push等，其他类型可以扩展
 *    推送URL时注意，如果是'homework://','airclass://'等协议，需用APP_SCHEMA替换，'app://'协议暂不处理
 * 5. 服务也可以支持直播外的其他业务，不过目标选择策略需注意调整
 *
 **/

class Hk_Service_Push {

    // 单次批量推送数量限制
    const PUSH_UID_LIMIT        = 50;
    const PUSH_CUID_LIMIT       = 50;

    // 推送策略控制
    const PUSH_STRATEGY_SYNC    = 1; // 直接同步发送
    const PUSH_STRATEGY_ASYNC   = 2; // 通过nmq异步发送
    const PUSH_STRATEGY_CONTROL = 3; // 通过fdc模块控制策略异步发送

    // 推送方式
    const PUSH_MODE_UID     = 1; // 支持批量uid推送
    const PUSH_MODE_CUID    = 2; // 支持批量cuid推送
    const PUSH_MODE_ALL     = 3; // cuid、uid推送均支持

    // 推送目标选择策略
    const PRODUCT_SELECT_RANDOM     = 1;    // 随机选一个
    const PRODUCT_SELECT_ALL        = 2;    // 推送所有
    const PRODUCT_SELECT_TOP        = 3;    // 依顺序选择一个有效的，涉及有效性判断

    // url schema替换标识，用product对应的schema名称替换（保留名称，除schema外url中就不能再出现这个名称了）
    const APP_SCHEMA    = "appschema";

    // APP产品url schema映射
    private static $productSchemaMap    = array(
        Hk_Service_Message_Const::NAPI_PRODUCT_NAME => "homework",
        Hk_Service_Message_Const::YIKE_PRODUCT_NAME => "airclass",
        Hk_Service_Message_Const::TEACHER_PRODUCT_NAME => "airteacher",
        Hk_Service_Message_Const::PRACTICE_PRODUCT_NAME => "practice",
    );

    const UIDLOGIN_INFO             = "UIDLOGIN_INFO";
    const DEVICES_CONTENT_KEY       = "DEVICES_CONTENT_KEY";

    private static $objServNmq      = null;
    private static $objRedisMap     = [];

    // push系统使用的redis配置，调用方不需要关注
    private static $pushRedisMap    = array(
        Hk_Service_Message_Const::NAPI_PRODUCT_NAME => array('pid' => 'homework', 'tk' => 'homework', 'app' => 'push'),
        Hk_Service_Message_Const::YIKE_PRODUCT_NAME => array('pid' => 'homework', 'tk' => 'homework', 'app' => 'push'),
        Hk_Service_Message_Const::DAYI_PRODUCT_NAME => array('pid' => 'homework', 'tk' => 'homework', 'app' => 'push'),
        Hk_Service_Message_Const::TEACHER_PRODUCT_NAME => array('pid' => 'homework', 'tk' => 'homework', 'app' => 'push'),
        Hk_Service_Message_Const::PRACTICE_PRODUCT_NAME => array('pid' => 'homework', 'tk' => 'homework', 'app' => 'push'),
    );

    // 支持的推送cmdNo以及对应配置
    public static $cmdConfigMap = array(
        //纯文本推送，应该是跳转到首页
        Hk_Service_Message_Const::SYS_NOTICE        => array(
            'params'    => array(                       //所需的必要参数
                'title'     => ['android_title'],       //调用Hk_Service_Message所需的字段映射
                'content'   => ['ios_alert', 'android_content'],
            ),
            'ext'       => array(                       //调用Hk_Service_Message所需的其他必要字段
                'ruid'      => 0,
                'sysmid'    => 0,
                'noSave'    => 1,
            ),
            'pushMode'  => self::PUSH_MODE_ALL,         // 支持的推送方式
        ),
        //url推送，跳转到url链接
        Hk_Service_Message_Const::URL_PUSH          => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['ios_alert', 'android_content'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_ALL,
        ),
        //IM消息批量cuid推送
        Hk_Service_Message_Const::APP_IM_PUSH_CUIDS => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['msg'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_CUID,
        ),
        //IM消息批量uid推送
        Hk_Service_Message_Const::APP_IM_PUSH_UIDS  => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['msg'],
            ),
            'ext'       => array(
                'uids'      => '',
            ),
            'pushMode'  => self::PUSH_MODE_UID,
        ),
        //一课老师app质检push
        Hk_Service_Message_Const::TEACHER_APP_COURSE_CHECK => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['ios_alert', 'android_content'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_ALL,
        ),
        //一课老师 待上课提醒
        Hk_Service_Message_Const::TEACHER_APP_ON_CLASS => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['ios_alert', 'android_content'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_ALL,
        ),
        //一课老师 讲义上传提醒
        Hk_Service_Message_Const::TEACHER_APP_UP_LECTURE => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['ios_alert', 'android_content'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_ALL,
        ),
        //一课老师 讲义未通过审核提醒
        Hk_Service_Message_Const::TEACHER_APP_LECTURE_NOT_PASS => array(
            'params'    => array(
                'title'     => ['android_title'],
                'content'   => ['ios_alert', 'android_content'],
                'url'       => ['url'],
            ),
            'ext'       => array(
                'cuids'     => '',
            ),
            'pushMode'  => self::PUSH_MODE_ALL,
        ),
    );

    /**
     * @brief 批量uid推送，单个uid当然也可
     * @param $cmdNo int push消息类型
     * @param $msgInput array push所需的数据，一般为title、content等，详情看上面配置
     * @param $uids array push给的uid数组
     * @param $strategy int 推送控制策略，有直推、异步推、控制推等。。。，默认是直推
     * @param $control array strategy为控制推时所需的控制信息
     * @param $products array 推送目标app可选列表
     * @param $selector int 推送目标选择策略，有随机、全部、顺序优先等，默认是一课优先
     *
     **/
    public static function pushByUids($cmdNo, array $msgInput, array $uids,
            $strategy = self::PUSH_STRATEGY_SYNC,
            $control  = array(),
            $products = [Hk_Service_Message_Const::YIKE_PRODUCT_NAME, Hk_Service_Message_Const::NAPI_PRODUCT_NAME, Hk_Service_Message_Const::TEACHER_PRODUCT_NAME],
            $selector = self::PRODUCT_SELECT_TOP) {
        return false;
    }

    /**
     * @brief 批量cuid推送
     * @param $cmdNo int push消息类型
     * @param $msgInput array push所需的数据，一般为title、content等，详情看上面配置
     * @param $cuids array push给的cuid数组
     * @param $strategy int 推送控制策略，有直推、异步推、控制推等。。。，默认是直推
     * @param $control array strategy为控制推时所需的控制信息
     * @param $products array 推送目标app可选列表
     * @param $selector int 推送目标选择策略，有随机、全部、顺序优先等，默认是一课优先
     *
     **/
    public static function pushByCuids($cmdNo, array $msgInput, array $cuids,
            $strategy = self::PUSH_STRATEGY_SYNC,
            $control  = array(),
            $products = [Hk_Service_Message_Const::YIKE_PRODUCT_NAME, Hk_Service_Message_Const::NAPI_PRODUCT_NAME],
            $selector = self::PRODUCT_SELECT_TOP) {
        return false;
    }

    /**
     * @brief 格式化数据之后，启动push
     * @param $cmdNo push消息类型
     * @param $msgInput 格式化后的数据
     * @param $strategy 推送控制策略
     * @param $control 控制信息
     *
     **/
    public static function push($cmdNo, $msgInput, $strategy = self::PUSH_STRATEGY_SYNC, $control = array()) {
        return false;
    }

    public static function pushFdc($arrCmd) {
        return false;
    }

    // 直接调用Hk_Service_Message发送push
    private static function pushSync($cmdNo, $msgInput) {
        return Hk_Service_Message::sendMsg($cmdNo, $msgInput);
    }

    // 通过nmq发送给fdc模块处理push，无控制信息，fdc直接调用message发送
    private static function pushAsync($cmdNo, $msgInput) {
        $arrCmd = array(
            'cmdNo'     => $cmdNo,
            'forceSend' => 1,
            'formated'  => 1,
            'formatMsg' => $msgInput,
        );
        if (self::$objServNmq === null) {
            self::$objServNmq = new Hk_Service_Nmq();
        }
        return self::$objServNmq->talkToQcm(Hk_Const_Command::CMD_FUDAO_SYS_PUSH, $arrCmd);
    }

    // 通过nmq发送给fdc模块处理push，带控制信息，fdc依照自身策略控制发送时机
    private static function pushControl($cmdNo, $msgInput, $control) {
        $arrCmd = array(
            'cmdNo'     => $cmdNo,
            'formated'  => 1,
            'formatMsg' => $msgInput,
        );
        $arrCmd = array_merge($control, $arrCmd);
        if (self::$objServNmq === null) {
            self::$objServNmq = new Hk_Service_Nmq();
        }
        return self::$objServNmq->talkToQcm(Hk_Const_Command::CMD_FUDAO_SYS_PUSH, $arrCmd);
    }

    // 参数检查
    private static function checkParams($cmdNo, $msgInput) {
        $formatInput = array();
        $params = self::$cmdConfigMap[$cmdNo]['params'];
        foreach ($params as $key => $keyMap) {
            if (!isset($msgInput[$key])) {
                Bd_Log::addNotice("pushParamErr", $key);
                return false;
            }
            $value = $msgInput[$key];
            foreach ($keyMap as $key) {
                $formatInput[$key] = $value;
            }
        }
        $extInfo = self::$cmdConfigMap[$cmdNo]['ext'];
        foreach ($extInfo as $key => $value) {
            $formatInput[$key] = $value;
        }
        return $formatInput;
    }

    // url推送时，需要对url进行处理，替换APP_SCHEMA标识
    private static function checkUrl($msgInput, $product) {
        if (!isset($msgInput['url']) || empty($msgInput['url'])) {
            return $msgInput;
        }
        // http和https协议的不需要处理
        if (preg_match('/^https?:\/\//', $msgInput['url'], $out) === 1) {
            return $msgInput;
        }
        // app协议暂不处理
        if (preg_match('/^app:\/\//', $msgInput['url'], $out) === 1) {
            return $msgInput;
        }
        $appSchema = self::$productSchemaMap[$product];
        // 搜索APP_SCHEMA标识并替换
        $url = str_replace(self::APP_SCHEMA, $appSchema, $msgInput['url']);
        if ($url != $msgInput['url']) {
            $msgInput['url'] = $url;
            return $msgInput;
        }
        // 非http/https/app/APP_SCHEMA的也一并替换了
        if (preg_match('/([a-z]+):\/\//', $msgInput['url'], $out) === 1) {
            $length = strlen($out[1]);
            $url = substr_replace($msgInput['url'], $appSchema, 0, $length);
            $msgInput['url'] = $url;
            return $msgInput;
        }
        return $msgInput;
    }

    // 随机选择product
    private static function selectProductRandom($ids, $products) {
        $prodNum    = count($products);
        $idx        = rand(1, $prodNum) - 1;
        $product    = $products[$idx];
        return array(
            $product    => $ids,
        );
    }

    // 选择所有的product
    private static function selectProductAll($ids, $products) {
        $res        = array();
        foreach ($products as $product) {
            $res[$product] = $ids;
        }
        return $res;
    }

    // 按顺序依次选择有效的product
    private static function selectProductTop($type, $ids, $products) {
        $result = array();
        foreach ($ids as $id) {
            $prodNum = count($products);
            $product = "";
            for ($i = 0; $i < $prodNum; $i++) {
                // 最后一个product，不需要判断
                if ($i == $prodNum - 1) {
                    $product = $products[$i];
                    break;
                }
                $now = time();
                // uid判断登录时间
                if ($type == "uid") {
                    $uidLoginInfo = self::getUserLoginInfo($id, $products[$i]);
                    if (empty($uidLoginInfo)){
                        continue;
                    }
                    foreach ($uidLoginInfo as $cuid => $loginTime) {
                        if ($now - $loginTime < 7 * 86400) {
                            $product = $products[$i];
                            break;
                        }
                    }
                }
                // cuid判断绑定时间
                else if ($type == "cuid") {
                    $cuidBindInfo = self::getCuidBindInfo($id, $products[$i]);
                    if (empty($cuidBindInfo)) {
                        continue;
                    }
                    $bindTime = intval($cuidBindInfo['BIND_TIME']);
                    if ($now - $bindTime < 7 * 86400) {
                        $product = $products[$i];
                        break;
                    }
                }
                if (!empty($product)) {
                    break;
                }
            }
            $result[$product][] = $id;
        }
        return $result;
    }

    /**
     * @brief 根据product选择策略，选择最终要发送的product目标
     * @param $type string "uid"或"cuid"，指定ids数组中的id类别
     * @param $ids array uid列表或cuid列表
     * @param $products array 可选的products列表
     * @param $selector eNUM 使用的选择策略（随机、全部、顺序优先）
     *
     **/
    private static function selectProduct($type, $ids, $products, $selector) {
        $splitRes   = array();
        if (empty($products)) {
            return $splitRes;
        }
        $prodNum    = count($products);
        switch ($selector) {
            case self::PRODUCT_SELECT_RANDOM:
                $splitRes = self::selectProductRandom($ids, $products);
                break;
            case self::PRODUCT_SELECT_ALL:
                $splitRes = self::selectProductAll($ids, $products);
                break;
            case self::PRODUCT_SELECT_TOP:
                $splitRes = self::selectProductTop($type, $ids, $products);
                break;
            default:
                break;
        }
        return $splitRes;
    }

    // 从push的redis中读取用户登录信息
    private static function getUserLoginInfo($uid, $product) {
        $objRedisInstance = self::$objRedisMap[$product];
        if (empty($objRedisInstance)) {
            $redisConfig = self::$pushRedisMap[$product];
            $objRedisInstance = Bd_RalRpc::create('Ak_Service_Redis', $redisConfig);
            $objRedisMap[$product] = $objRedisInstance;
        }
        $key = $product.":".$uid.":".self::UIDLOGIN_INFO;
        $res = $objRedisInstance->HGETALL(array('key' => $key));
        if (empty($res) || $res['err_no'] != 0) {
            return false;
        }
        $res = $res['ret'][$key];
        $ret = array();
        foreach ($res as $v) {
            $ret[$v['field']] = $v['value'];
        }
        return $ret;
    }

    // 从push的redis中读取设备的绑定信息
    private static function getCuidBindInfo($cuid, $product) {
        $objRedisInstance = self::$objRedisMap[$product];
        if (empty($objRedisInstance)) {
            $redisConfig = self::$pushRedisMap[$product];
            $objRedisInstance = Bd_RalRpc::create('Ak_Service_Redis', $redisConfig);
            $objRedisMap[$product] = $objRedisInstance;
        }
        $key = $product.":".$cuid.":".self::DEVICES_CONTENT_KEY;
        $res = $objRedisInstance->HGETALL(array('key' => $key));
        if (empty($res) || $res['err_no'] != 0) {
            return false;
        }
        $res = $res['ret'][$key];
        $ret = array();
        foreach ($res as $v) {
            $ret[$v['field']] = $v['value'];
        }
        return $ret;
    }
}