<?php
/**
 * 对message RPC、查询调用的封装
 *
 * @deprecated
 *
 * @fileName Msg.php
 * @author  yanruitao<yanruitao@zuoyebang.com>
 * @version 1.0
 */

class Hk_Service_Message
{

    const SERVICE_NAME = "message";     # 调用的RPC服务

    /**
     * 调用rpc service
     *
     * @param string         $method
     * @param array          $input
     */
    private static function callRpc($method, $input) {
        return Hk_Service_Rpc::call(self::SERVICE_NAME, $method, $input);
    }

    /**
     * 业务发送push
     *
     * example:
     * Hk_Service_Message::sendMsg(Hk_Service_Message_Const::SYS_NOTICE, [
     *  "ruid" => 2100052512,
     *  "sysmid" => 368
     * ]);
     *
     * @param $cmdNo int 业务push的命令序号
     * @param $datas array 业务push命令对应的参数
     */
    public static function sendMsg($cmdNo, $datas)
    {
        //格式化参数
        $cmdNo = intval($cmdNo);

        // 校验参数
        // 判断命令号是否支持
        if (!isset(Hk_Service_Message_Const::$supportCmdNo[$cmdNo])) {
            Bd_Log::warning("Message send msg cmdNo not support. Abstract[cmdNo[{$cmdNo}]] Detail[datas[". var_export($datas, true) ."]]");
            return false;
        }
        // 判断datas是否是数组
        if (!is_array($datas) || empty($datas)) {
            Bd_Log::warning("Message send msg datas should be array and can't empty. Abstract[cmdNo[{$cmdNo}]] Detail[datas[". var_export($datas, true) ."]]");
            return false;
        }

        $input = array_merge([
            "cmdNo" => $cmdNo,
        ], $datas);
        $method = "sendmsg";
        $ret = self::callRpc($method, $input);
        if (false === $ret || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 删除消息
     * key为空全删
     *
     * example:
     * Hk_Service_Message::delMsg("system", 2100052512, "1479375053400");       # 删除用户2100052512 create_time为1479375053400的系统消息
     * Hk_Service_Message::delMsg("system", 2100052512);        # 删除用户2100052512的所有系统消息
     *
     * @param $storeType string 存储类型
     * @param $uid int 用户uid
     * @Param $key mixed
     * @param $product string app产品，默认为主app(napi)
     */
    public static function delMsg($storeType, $uid, $key = "", $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        //参数格式化
        $storeType = strval($storeType);
        $uid = intval($uid);
        $key = strval($key);

        // 校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message del msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] key[{$key}]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message del msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] key[{$key}]]");
            return false;
        }

        $input =[
            "product" => $product,
            "storeType" => $storeType,
            "uid" => $uid,
            "key" => $key
        ];
        $method = "delmsg";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 批量删除消息
     *
     * example:
     * Hk_Service_Message::delMsgBatch("system", 2100052512, [
     *  1479710587482,
     *  1479709956738
     * ]);
     *
     * @param $storeType string 存储类型
     * @param $uid int 用户uid
     * @param $keys array 要删除记录对应的信息列表
     * @param $product string app产品
     * @return bool
     */
    public static function delMsgBatch($storeType, $uid, $keys, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        //参数格式化
        $storeType = strval($storeType);
        $uid = intval($uid);

        // 判断参数是否为有效的数组
        if (empty($keys) || !is_array($keys)) {
            Bd_Log::warning("Message batch del msg param(keys) error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        // 校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message batch del msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message batch del msg param[uid] error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        $input =[
            "product" => $product,
            "storeType" => $storeType,
            "uid" => $uid,
            "keys" => json_encode($keys)
        ];
        $method = "delmsgbatch";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 标记消息为已读
     * key为空全部删除
     *
     * example:
     * Hk_Service_Message::markMsg("system", 2100052512, 1479524001689);        # 标记用户2100052512 create_time为1479524001689的系统消息为已读
     * Hk_Service_Message::markMsg("system, 2100052512);        # 标记用户2100052512的所有系统消息为已读
     *
     * @param $storeType string 存储类型
     * @param $uid int 用户uid
     * @param $key mixed
     */
    public static function markMsg($storeType, $uid, $key = "", $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        //参数格式化
        $storeType = strval($storeType);
        $uid = intval($uid);
        $key = strval($key);

        // 校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message mark msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] key[{$key}]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message mark msg param[uid] error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] key[{$key}]]");
            return false;
        }

        $input =[
            "product" => $product,
            "storeType" => $storeType,
            "uid" => $uid,
            "key" => $key
        ];
        $method = "markmsg";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 批量标记为已读
     *
     * example:
     * Hk_Service_Message::markMsgBatch("system", 2100052512, [
     *  1479710587482,
     *  1479709956738
     * ]);
     *
     * @param $storeType string 存储类型
     * @param $uid int 用户uid
     * @param $keys array 要标记记录对应的信息列表
     * @return bool
     */
    public static function markMsgBatch($storeType, $uid, $keys, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        //参数格式化
        $storeType = strval($storeType);
        $uid = intval($uid);

        // 判断参数是否为有效的数组
        if (empty($keys) || !is_array($keys)) {
            Bd_Log::warning("Message batch mark msg param(keys) error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        // 校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message batch mark msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message batch mark msg param[uid] error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] keys[". var_export($keys, true) ."]]");
            return false;
        }

        $input = [
            "product" => $product,
            "storeType" => $storeType,
            "uid" => $uid,
            "keys" => json_encode($keys)
        ];
        $method = "markmsgbatch";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 标记用户问题类型消息已读
     * 这里的key拼接跟存储时候的一致` "0_{问题qid}_{回答者uid}" `
     *
     * @param $uid int 用户uid
     * @param $qid int 帖子id
     * @param $fid int 回答者uid
     * @return bool
     */
    public static function markMsgQuestion($uid, $qid, $fid)
    {
        // 格式化参数
        $uid = intval($uid);
        $qid = intval($qid);
        $fid = intval($fid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message mark question msg param[uid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}] fid[{$fid}]]");
            return false;
        }

        // 校验qid是否合法
        if ($qid <= 0) {
            Bd_Log::warning("Message mark question msg param[qid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}] fid[{$fid}]]");
            return false;
        }

        // 校验fid是否合法
        if ($fid <= 0) {
            Bd_Log::warning("Message mark question msg param[fid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}] fid[{$fid}]]");
            return false;
        }

        $key = "0_{$qid}_{$fid}";   // TODO question类型消息键的拼接可以放在phplib中统一起来

        $ret = self::markMsg("question", $uid, $key);

        return $ret;
    }

    /**
     * 根据qid标记消息为已读
     * 存储类型只有question类型在用
     *
     * example:
     * Hk_Service_Message::markMsgByQid(2100052512, 218877454);     # 根据qid标记用户消息已读，需要消息中包含qid字段
     *
     * @param $uid int 用户uid
     * @param $qid int 消息id
     * @return bool
     */
    public static function markMsgByQid($uid, $qid)
    {
        // 格式化参数
        $uid = intval($uid);
        $qid = intval($qid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message mark question msg by qid param[uid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }

        // 校验qid是否合法
        if ($qid <= 0) {
            Bd_Log::warning("Message mark question msg by qid param[qid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }

        $input =[
            "product" => Hk_Service_Message_Const::NAPI_PRODUCT_NAME,
            "storeType" => "question",
            "uid" => $uid,
            "qid" => $qid
        ];
        $method = "markmsgbyqid";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 根据qid删除消息
     * 存储类型只有question在用
     *
     * example:
     * Hk_Service_Message::delMsgByQid(2100052512, 218877454);      # 根据qid删除用户消息，需要消息中包含qid字段
     *
     * @param $uid int 用户uid
     * @param $qid int
     * @return bool
     */
    public static function delMsgByQid($uid, $qid)
    {
        // 格式化参数
        $uid = intval($uid);
        $qid = intval($qid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message delete question msg by qid param[uid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }

        // 校验qid是否合法
        if ($qid <= 0) {
            Bd_Log::warning("Message delete question msg by qid param[qid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }
        $input =[
            "product" => Hk_Service_Message_Const::NAPI_PRODUCT_NAME,
            "storeType" => "question",
            "uid" => $uid,
            "qid" => $qid
        ];
        $method = "delmsgbyqid";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 根据fid删除聊天消息
     *
     * example:
     * Hk_Service_Message::delMsgByFid(2100052512, 2100000446);     # 根据fid删除消息，需要消息中包含fid字段
     *
     * @param $uid int 用户uid
     * @param $fid int
     * @return bool
     */
    public static function delMsgByFid($uid, $fid)
    {
        // 格式化参数
        $uid = intval($uid);
        $fid = intval($fid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message del msg by fid param[uid] error. Abstract[uid[{$uid}]] Detail[fid[{$fid}]]");
            return false;
        }

        // 校验fid是否合法
        if ($fid <= 0) {
            Bd_Log::warning("Message del msg by fid param[fid] error. Abstract[uid[{$uid}]] Detail[fid[{$fid}]]");
            return false;
        }

        $input =[
            "product" => Hk_Service_Message_Const::NAPI_PRODUCT_NAME,
            "storeType" => "chat",
            "uid" => $uid,
            "fid" => $fid
        ];
        $method = "delmsgbyfid";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 根据消息所属ask、answer类型删除消息
     *
     * example:
     * Hk_Service_Message::delMsgByQuestionType(2100052512, 0);     #删除ask类型消息
     * Hk_Service_Message::delMsgByQuestionType(2100052512, 1);     #删除answer类型消息
     *
     * @param $uid int 用户uid
     * @param $type int 0——ask，1——answer
     * @param bool
     */
    public static function delMsgByQuestionType($uid, $type)
    {
        // 格式化参数
        $uid = intval($uid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message del question msg by type param[uid] error. Abstract[uid[{$uid}]] Detail[type[{$type}]]");
            return false;
        }

        // 校验type是否合法
        if (!in_array($type, [0, 1])) {
            Bd_Log::warning("Message del question msg by type param[type] error. Abstract[uid[{$uid}]] Detail[type[{$type}]]");
            return false;
        }
        $input =[
            "product" => Hk_Service_Message_Const::NAPI_PRODUCT_NAME,
            "storeType" => "question",
            "uid" => $uid,
            "type" => $type
        ];
        $method = "delmsgbyquestiontype";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 根据fid标记聊天消息
     *
     * example:
     * Hk_Service_Message::markMsgByFid(2100052512, 2100000446);    # 根据fid标记消息为已读，消息中必须包含fid字段
     *
     * @param $uid int 用户uid
     * @param $fid int
     * @return bool
     */
    public static function markMsgByFid($uid, $fid)
    {
        // 格式化参数
        $uid = intval($uid);
        $fid = intval($fid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message mark msg by fid param[uid] error. Abstract[uid[{$uid}]] Detail[fid[{$fid}]]");
            return false;
        }

        // 校验fid是否合法
        if ($fid <= 0) {
            Bd_Log::warning("Message mark msg by fid param[fid] error. Abstract[uid[{$uid}]] Detail[fid[{$fid}]]");
            return false;
        }

        $input =[
            "product" => Hk_Service_Message_Const::NAPI_PRODUCT_NAME,
            "storeType" => "chat",
            "uid" => $uid,
            "fid" => $fid
        ];
        $method = "markmsgbyfid";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 根据qid获取问题未读消息数量
     * 目前只有question存储类型
     *
     * example:
     * Hk_Service_Message::getUnreadCountByQid(2100052512, 218887325);      # 根据qid获取消息未读数
     *
     * @param $uid int 用户uid
     * @param qid int
     * @return int
     */
    public static function getUnreadCountByQid($uid, $qid)
    {
        // 格式化参数
        $uid = intval($uid);
        $qid = intval($qid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Messagel get unread count by qid param[uid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }

        // 校验qid是否合法
        if ($qid <= 0) {
            Bd_Log::warning("Message get unread count by qid param[qid] error. Abstract[uid[{$uid}]] Detail[qid[{$qid}]]");
            return false;
        }

        $ret = 0;
        $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME;
        $storeType = "question";
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["store"][$storeType];
        $obj = self::getInstance($cls, [self::getNapiRedis()]);
        if (!$obj) {
            return $ret;
        }

        $lists = $obj->getMsgList($uid);
        foreach ($lists as $item)
        {
            $item = json_decode($item, true);
            if($qid == $item['qid'])
            {
                $ret = $item['unread_count'];       // TODO 一个用户一个qid只能有一条信息?
                break;
            }
        }
        return $ret;

    }

    /**
     * 获取用户系统消息列表
     *
     * example:
     * Hk_Service_Message::getUserMsg("system", 2100052512, false, 5, 100); # 获取用户系统消息从第5个到100个数据
     *
     * @param $storeType string 存储类型
     * @param $uid int 用户uid
     * @param $min int|false zset排好序，开始的值 | false的时候该条件不参与
     * @param $count int|false 要获取的记录条数 | false的时候不计数
     * @param $offset int|false 获取偏移量 | false的时候没有偏移量
     * @return array
     */
    public static function getUserMsg($storeType, $uid, $min = false, $count = false, $offset = false, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        // 格式化参数
        $storeType = strval($storeType);
        $uid = intval($uid);

        //校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message get user msg store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] min[{$min}] count[{$count}] offset[{$offset}]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message get user msg param[uid] error. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}] min[{$min}] count[{$count}] offset[{$offset}]]");
            return false;
        }

        $ret = [];
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["store"][$storeType];
        $obj = self::getInstance($cls, [self::getNapiRedis()]);
        if (!$obj) {
            return $ret;
        }

        $param["count"] = $count;
        $param["offset"] = $offset;
        $param["min"] = $min;
        $ret = $obj->getMsgList($uid, $param);
        return $ret;
    }

    /**
     * 获取用户聊天信息列表
     *
     * @param $uid int 用户uid
     * @param $min int|false zset排好序，开始的值 | false的时候该条件不参与
     * @param $count int|false 要获取的记录条数 | false的时候不计数
     * @param $offset int|false 获取偏移量 | false的时候没有偏移量
     * @return array
     */
    public static function getUserChatMsg($uid, $min = false, $count = false, $offset = false)
    {
        // 格式化参数
        $uid = intval($uid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message get user chat msg param[uid] error. Abstract[uid[{$uid}]] Detail[min[{$min}] count[{$count}] offset[{$offset}]]");
            return false;
        }

        $resp = self::getUserMsg("chat", $uid, $min, $count, $offset);

        $ret = [
            "list" =>[]
        ];

        foreach ($resp as $json) {
            $ret["list"][] = json_decode($json, true);
        }

        return $ret;
    }

    /**
     * 获取question类型消息未读数量
     *
     * @param $uid int 用户uid
     * @return array
     */
    public static function getQuestionUnread($uid)
    {
        // 格式化参数
        $uid = intval($uid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message get question unread param[uid] error. Abstract[uid[{$uid}]] Detail[]");
            return false;
        }

        $ret = [
            "ask" => 0,
            "answer" => 0,
            "invite"  => 0,
        ];

        $items = self::getUserMsg("question", $uid);
        foreach ($items as $item) {
            $record = json_decode($item, true);
            $qType = self::getQuestionType($record["type"]);
            if (0 == $qType) {
                $ret["ask"] += $record["unread_count"];
            } elseif (1 == $qType) {
                $ret["answer"] += $record["unread_count"];
            } elseif (2 == $qType) {
                $ret["invite"] += $record["unread_count"];
            }
        }

        return $ret;
    }

    /**
     * 根据业务类型获取是提问||回答||邀请
     *
     * @param $msgNo int
     * @return int
     */
    public static function getQuestionType($msgNo)
    {
        $msgNo = intval($msgNo);
        $ret = 3;
		switch ($msgNo) {
            case Hk_Service_Message_Const::DEFINE_NEWREPLY:
            case Hk_Service_Message_Const::INVITE_EVALUATE:
                $ret = 0;
                break;
            case Hk_Service_Message_Const::DEFINE_REPLYASK:
            case Hk_Service_Message_Const::DEFINE_EVALUATE_GOOD:
            case Hk_Service_Message_Const::THANKS:
                $ret = 1;
                break;
            case Hk_Service_Message_Const::DEFINE_INVITE:
                $ret = 2;
            default:
                break;
        }
        return $ret;
    }

    /**
     * 获取用户消息未读数量
     *
     * - 获取用户系统消息未读数量 getUserUnread("system", $uid)
     * - 获取用户文章消息未读数量 getUserUnread("article", $uid)
     * - 获取用户聊天未读消息数量 getUserUnread("chat", $uid)
     *
     * @param $storeType string 存储消息类型
     * @param $uid int 用户uid
     * @return int
     */
    public static function getUserUnread($storeType, $uid, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        // 格式化参数
        $storeType = strval($storeType);
        $uid = intval($uid);

        //校验存储类型是否支持
        if (!self::isStoreTypeSupport($product, $storeType)) {
            Bd_Log::warning("Message get user unread store type not support. Abstract[storeType[{$storeType}]] Detail[uid[{$uid}]]");
            return false;
        }

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message get user unread param[uid] error. Abstract[uid[{$uid}]] Detail[storeType[{$storeType}]]");
            return false;
        }

        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["store"][$storeType];
        $obj = self::getInstance($cls, [self::getNapiRedis()]);
        $ret = $obj->getMsgCount($uid, true);
        return $ret;
    }

    /**
     * 获取用户提问回答消息列表
     *
     * @param $intUid int
     * @param $intBaseTime int
     * @param $intType 0——提问，1——回答
     * @param $intLimit
     * @param $intOffset
     */
    public static function getUserAskAnswerMsg($intUid, $intBaseTime = 0, $intType = 0, $intLimit = 20, $intOffset = 0)  {
        $arrOutput = array(
            'list'            => array(),
            'total'            => 0,
            'askTotal'        => 0,
            'answerTotal'    => 0,
            'inviteTotal'    => 0,
        );

        $cls = Hk_Service_Message_Const::$projectClsMaps[Hk_Service_Message_Const::NAPI_PRODUCT_NAME]["store"]["question"];
        $obj = self::getInstance($cls, [self::getNapiRedis()]);
        $arrContentList = $obj->getMsgList($intUid);
        $arrQuestionMsg = array();
        //问题聚合
        foreach($arrContentList as $strOneMsg)
        {
            $arrOneMsg = json_decode($strOneMsg, true);
            if($intBaseTime > 0 && $intBaseTime < $arrOneMsg['create_time'])
            {
                continue;
            }
            if(isset($arrQuestionMsg[$arrOneMsg['qid']]))
            {
                $arrQuestionMsg[$arrOneMsg['qid']]['unread_count'] += $arrOneMsg['unread_count'];
                continue;
            }
            $arrQuestionMsg[$arrOneMsg['qid']] = $arrOneMsg;
        }

        if(empty($arrQuestionMsg))
        {
            return $arrOutput;
        }
        $intCount = 0;
        $intNum = 0;
        $arrList = array();
        $intTotal = 0;
        $intAskTotal = 0;
        $intAnswerTotal = 0;
        $intInviteTotal = 0;
        foreach($arrQuestionMsg as $arrOne)
        {
            $intMsgType = self::getQuestionType($arrOne['type']);
            $intTotal += $arrOne['unread_count'];
            if($intMsgType == 0)
            {
                $intAskTotal += $arrOne['unread_count'];
            }
            elseif($intMsgType == 1)
            {
                $intAnswerTotal += $arrOne['unread_count'];
            }
            elseif($intType == 2)
            {
                $intInviteTotal += $arrOne['unread_count'];
            }
            if($intType != $intMsgType)
            {
                continue;
            }
            if($intNum ++ < $intOffset)
            {
                continue;
            }
            if(++ $intCount <= $intLimit)
            {
                $arrList[] = $arrOne;
            }
        }

        $arrOutput['list'] = $arrList;
        $arrOutput['total'] = $intTotal;
        $arrOutput['askTotal'] = $intAskTotal;
        $arrOutput['answerTotal'] = $intAnswerTotal;
        $arrOutput['inviteTotal'] = $intInviteTotal;
        return $arrOutput;
    }

    /**
     * 获取全局未读系统消息数量
     *
     */
    public static function getGlobalSysMsgInfoUnread($input, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        $msgList = self::getGlobalSysMsg($input, true, $product);
        return count($msgList);
    }

    /**
     * 删除全局系统消息
     * 关于图谱相关信息 @see Hk_Service_Message_Switch_Napi
     *
     * @param $uid int 用户uid
     * @param $os string 操作系统类型
     * @param $key mixed 删除全部或者指定消息，为空表示清空用户全量全局系统消息
     * @return bool
     */
    public static function delGlobalSysMsg($uid, $os, $key = "", $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        $uid = intval($uid);
        $os = strval($os);
        $key = strval($key);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message del global sys msg param[uid] error. Abstract[uid[{$uid}]] Detail[os[{$os}] key[{$key}]]");
            return false;
        }

        // 校验操作系统os
        if (!in_array($os, ["ios", "android"])) {
            Bd_Log::warning("Message del global sys msg param[os] error. Abstract[uid[{$uid}]] Detail[os[{$os}] key[{$key}]]");
            return false;
        }

        $input =[
            "product" => $product,
            "uid" => $uid,
            "os" => $os,
            "key" => $key
        ];
        $method = "delglobalsysmsg";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 标记全局系统消息为已读
     * 其实只是更新最新拉取的时间戳，下次拉取的时候会根据这个时间戳确定是否显示未读
     *
     * @param $uid int 用户uid
     * @return bool
     */
    public static function markGlobalSysMsg($uid, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        $uid = intval($uid);

        // 校验uid是否合法
        if ($uid <= 0) {
            Bd_Log::warning("Message mark global sys msg param[uid] error. Abstract[uid[{$uid}]] Detail[]");
            return false;
        }
        $input =[
            "product" => $product,
            "uid" => $uid,
        ];
        $method = "markglobalsysmsg";
        $ret = self::callRpc($method, $input);
        if (!isset($ret["errNo"]) || 0 != $ret["errNo"]) {
            return false;
        }
        return true;
    }

    /**
     * 获取全局系统消息
     * 关于图谱相关信息 @see Hk_Service_Message_Switch_Napi
     *
     * @param $input int 输入的过滤信息
     * @param $countOnly bool 是否只是用于统计
     * @param array 根据条件过滤之后的全局系统消息列表
     */
    public static function getGlobalSysMsg($input, $countOnly = false, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        $result = [];

        // 校验参数
        if(
            empty($input['uid']) ||
            empty($input['grade']) ||
            empty($input['os']) ||
            empty($input['vc']) ||
            empty($input['version'])
        ){
            return $result;
        }

        // 格式化地区名称
        if (!empty($input["provName"])) {
            $patterns[] = "/维吾尔自治区/";
            $patterns[] = "/壮族自治区/";
            $patterns[] = "/回族自治区/";
            $patterns[] = "/自治区/";
            $patterns[] = "/省/";
            $patterns[] = "/市/";
            $input["provName"] = preg_replace($patterns, '', $input["provName"]);
        }

        $prefixKey = Hk_Service_Message_Store_NapiSystem::GLOBAL_SYSMSG_PREFIX.$input["os"];

        //获取用户上次拉取全局消息的时间戳
        $field = "last_query";
        $deltm_field = "deltm";
        $delmap_field = "delmap";
        $last_query_time = 0;

		// 获取用户上次拉取全局消息的时间戳
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["switch"];
        $objSwitch = self::getInstance($cls);
        $switches = $objSwitch->getSwitch($input["uid"]);
        if(!empty($switches[$field])){
            $last_query_time = $switches[$field];
        }

		//拉取全量的全局系统消息，并根据用户信息过滤
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["store"]["system"];
        $objStore = self::getInstance($cls, [self::getNapiRedis()]);
        $msgArr = $objStore->getMsgList($prefixKey);
        $msgList = [];
        foreach($msgArr as $msg){
            $msgList[] = @json_decode($msg, true);
        }
        //生成用户当前的删除图谱
        $delmap = self::buildGlobalSysMsgDelMap(array_reverse($msgList), intval($switches[$deltm_field]), intval($switches[$delmap_field]));
        $val = 1;
        foreach($msgList as $msg){
            //根据删除图谱判断消息是否被用户删除了
            if(($val & $delmap) != 0){
                $val = ($val << 1);
                continue;
            }
            $val = ($val << 1);
            //根据上次拉取时间确定此消息是否被读过
            if($msg['create_time'] < $last_query_time){
                if($countOnly){
                    continue;
                }
                $msg['unread_count'] = 0;
            }
            //如果有time输入参数，代表需要取time之前的消息，之后的过滤掉
            if(!empty($input['time'])){
                if($msg['create_time'] >= $input['time']){
                    continue;
                }
            }
            if(!self::filterRegion($input, $msg)){
                continue;
            }
            if(!self::filterGrade($input, $msg)){
                continue;
            }
            if(!self::filterOsversion($input, $msg)){
                continue;
            }
            $result[] = $msg;
        }
        if(!$countOnly){
            //更新用户拉取全局系统消息的时间戳
            self::markGlobalSysMsg($input["uid"], $product);
        }
        return $result;
    }

    /**
     * 获取全局系统消息的时候过滤地区信息
     *
     * @param $arrInput array 输入的参数信息
     * @param $msg array 一条全局系统信息
     * @return bool
     */
    private static function filterRegion($arrInput, $msg) {

        // 省份过滤
        if (empty($msg["provName"])) {
            return true;
        }
        if ($msg["provName"] != $arrInput["provName"]){
            return false;
        }

        // 城市过滤出
        if (empty($msg["cityName"])) {
            return true;
        }
        if ($msg["cityName"] != $arrInput["cityName"]) {
            return false;
        }

        //地区过滤
        if (empty($msg["areaName"])) {
            return true;
        }
        if ($msg["areaName"] != $arrInput["areaName"]) {
            return false;
        }
        return true;
    }

    /**
     * 过滤年级信息
     *
     * @param $arrInput array 输入参数信息
     * @param $msg array 一条全局系统消息
     * @return bool
     */
    private static function filterGrade($arrInput, $msg) {
        if ($msg["grade"] == 0) {
            return true;
        }
        if ($msg["grade"] == $arrInput["grade"]) {
            return true;
        }

		// 年级信息转换为文凭信息
        $gradeDiploma = [
			1 => 10,     //小学
			10 => 10,    //小学
			11 => 10,    //一年级
			12 => 10,    //二年级
			13 => 10,    //三年级
			14 => 10,    //四年级
			15 => 10,    //五年级
			16 => 10,    //六年级
			20 => 20,    //初中
			2 => 20,     //初一
			3 => 20,     //初二
			4 => 20,     //初三
			30 => 30,    //高中
			5 => 30,     //高一
			6 => 30,     //高二
			7 => 30,     //高三
        ];
        if ($msg["grade"] == $gradeDiploma['grade']) {
            return true;
        }
        return false;
    }

    /**
     * 过滤操作系统信息
     *
     * @param $arrInput array 输入参数信息
     * @param $msg array 一条全局系统消息
     * @return bool
     */
    private static function filterOsversion($arrInput, $msg) {
        if ($msg["os"] != $arrInput["os"]) {
            return false;
        }
        if ($msg["version"] === "") {
            return true;
        }
        $versionList = explode(",", $msg["version"]);
        if (in_array($arrInput["version"], $versionList)){
            return true;
        }
        return false;
    }

    /**
     * 生成用户删除图谱
     * 关于图谱相关信息 @see Hk_Service_Message_Switch_Napi
     *
     * @param $msgList array 全局系统消息列表，跟过滤时候的列表顺序是相反的
     * @param $deltm int 删除的时间信息
     * @param $delmap int 列表位图信息
     * @return int 新的列表位图信息
     */
    public static function buildGlobalSysMsgDelMap($msgList, $deltm, $delmap) {
        if(empty($msgList) || empty($deltm)) {
            return 0;
        }
        $old_delmap = 0;
        //上次是全量删除
        if($deltm % 10 == 0){
            $last_del_time = intval($deltm / 10);
            //遍历消息列表生成新的删除图谱
            foreach($msgList as $msg){
                if(intval($msg['create_time']) <= $last_del_time){
                    $old_delmap = ($old_delmap << 1) + 1;
                }else{
                    $old_delmap = ($old_delmap << 1);
                }
            }
        }
        //上次是单个删除，取出上次的删除图谱
        else{
            $tmp_delmap = intval($delmap);
            $last_del_time = intval($deltm / 10);
            $new_count = 0;
            foreach($msgList as $msg){
                if(intval($msg['create_time']) > $last_del_time){
                   $new_count += 1;
                }
            }
            $old_delmap = ($tmp_delmap << $new_count) & ((1 << count($msgList)) - 1);
        }
        return $old_delmap;
    }

    /**
     * 获取指定产品（指定命令）对应的开关type（开关标识）集合
     *
     * @param $product string 产品名称
     * @param $cmdNo int 定义的Message命令号
     *
     **/
    public static function getProductSwitchType($product, $cmdNo = null)
    {
        $ret = [];
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["switch"];
        if ($cls === null || empty($cls)) {
            return $ret;
        }
        $obj = Hk_Service_Message::getInstance($cls);
        if (!$obj) {
            Bd_Log::warning("Message switch not support. ABstract[initialize clss[{$cls}] failed].");
            return $ret;
        }

        $ret = $obj->getSwitchType($cmdNo);

        return $ret;
    }

    /**
     * 获取指定产品指定用户的消息开关
     *
     * @param $product string 产品名称
     * @param $uid int 用户uid
     * @return array
     */
    public static function getUserSwitch($uid, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
		$ret = [];
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["switch"];
        if ($cls === null || empty($cls)) {
            return $ret;
        }
        $obj = Hk_Service_Message::getInstance($cls);
        if (!$obj) {
            Bd_Log::warning("Message switch not support. ABstract[initialize clss[{$cls}] failed]. Detail[uid[{$uid}]]");
			return $ret;
        }

        $ret = $obj->getSwitch($uid);

		return $ret;
    }

    /**
     * 设置指定产品指定用户的消息开关
     *
     * @param $uid int 用户uid
     * @param $type string 开关标识
     * @param $close int 开、关
     *
     */
    public static function setUserSwitch($uid, $type, $close = 1, $product = Hk_Service_Message_Const::NAPI_PRODUCT_NAME)
    {
        $ret = false;
        $cls = Hk_Service_Message_Const::$projectClsMaps[$product]["switch"];
        if ($cls === null || empty($cls)) {
            return $ret;
        }
        $obj = Hk_Service_Message::getInstance($cls);
        if (!$obj) {
            Bd_Log::warning("Message switch not support. ABstract[initialize clss[{$cls}] failed]. Detail[uid[{$uid}]]");
            return $ret;
        }
        $ret = $obj->setSwitch($uid, $type, $close);
        return $ret;
    }

    /*****************************************单例模式 start*********************************************/

    /**
     * 单例模式获取类实例
     * 实例化的时候如果出现错误会记录调用地方的错误信息，这里临时重设了php系统错误处理函数，在某些不跑一场的错误中用来抛出异常
     *
     * @param $cls string
     * @param $args array
     */
    public static function getInstance($cls, $args = [])
    {
        static $instances = [];
        if (!isset($instances[$cls])) {
            if (!class_exists($cls)) {
                $instances[$cls] = false;
                Bd_Log::warning("Message getInstance of class failed. Abstract[class [{$cls}] not exists]");
            } else {
                // 实例化类出现问题的时候，记录详细的调用出信息
                set_error_handler("Hk_Service_Message::instanceException"); # 实例化错误可能不抛出Exception而爆出Error，我们临时手动处理
                try {
                    $reflectionCls = new ReflectionClass($cls);
                    $instances[$cls] = $reflectionCls->newInstanceArgs($args);
                } catch (Exception $e) {
                    $logs = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 1);
                    $firstLog = array_pop($logs);
                    $firstLog["class"] = empty($firstLog["class"]) ? "NOCLASS" : $firstLog["class"];
                    $firstLog["type"] = empty($firstLog["type"]) ? "NOTYPE" : $firstLog["type"];
                    $firstLog["function"] = empty($firstLog["function"]) ? "NOFUNC" : $firstLog["function"];
                    Bd_Log::warning("Modal message get instance failed. Abstract[cls[{$cls}] args[". json_encode($args) ."]] Detail[file[{$firstLog['file']}] line[{$firstLog['line']}] func[{$firstLog['class']}{$firstLog['type']}{$firstLog['function']}]]");
                }
                restore_error_handler();    # 恢复之前的错误处理函数，不对影响系统其他地方
            }
        }
        return $instances[$cls];
    }

    /**
     * 实例化的时候获取错误debugbacktrace信息，准确定位错误信息
     *
     * @param $errNo int 错误号
     * @param $errStr string 错误信息
     * @param $errFile string 错误文件位置
     * @param $errLine int 错误行
     * @throw Exception
     */
    public static function instanceException($errNo, $errStr, $errFile, $errLine)
    {
        throw new Exception("类反射实例化对象错误");
    }

    /**
     * 获取product 为napi的redis实例
     * product napi中的所有redis统一使用这个
     */
    public static function getNapiRedis()
    {
        static $objRedis = null;
        if (!isset($objRedis)) {
            $conf = Bd_Conf::getConf('hk/redis/msg');
            $objRedis = new Hk_Service_Redis($conf['service']);
            if (empty($objRedis)) {
                $objRedis = false;
                Bd_Log::warning("Msg init redis failed. Abstract[]. Detail[".json_encode($conf["service"])."]");
            }
        }
        return $objRedis;
    }
    /*****************************************单例模式 end*********************************************/

    /***************************************** 校验参数 start *****************************************/
    /**
     * 判断某个产品的某个存储类型是否支持
     *
     * example:
     * static::isStoreTypeSupport(Hk_Service_Message_Const::NAPI_PRODUCT_NAME, "question");"
     *
     * @param $product string 产品名称
     * @param $storeType string 存储类型
     * @return bool
     */
    private static function isStoreTypeSupport($product, $storeType)
    {
        if (isset(Hk_Service_Message_Const::$projectClsMaps[$product]["store"][$storeType])) {
            return true;
        }
        return false;
    }
    /***************************************** 校验参数 end *****************************************/
}
