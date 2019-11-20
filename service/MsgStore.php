<?php
/***************************************************************************
 *
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

/**
 * @file MsgStore.php
 * 文档见  http://wiki.baidu.com/pages/viewpage.action?pageId=90769421
 * @author zhangyoulin01(com@baidu.com)
 *         @date 2015-5-11
 * @version 1.0
 *         
 */
class Hk_Service_MsgStore {
    private $_redis;
    private $_curMsgId;
    
    const MSG_TYPE_UNIQ_ID_KEY = "NAPP_GLOBAL_MSG_UNIQ_ID";  //消息自增id的redis key
    const MSG_USER_SWITCH_INFO = "NAPP_USER_SWITCH_INFO";   //是否接收消息 开关存储的key
    const DEVICE_OFFLINE_MESSAGE_EXPIRE_TIME = 86400;       //离线消息保存时间
    const DEFAULT_RETURN_RN_VALUE = 20;                     //消息每页显示数目

    const MSG_STORAGE_EXPIRE_TIME = 5184000;                //用户2个月未登陆将失效所有消息
    
    static $msg_key = array(
        'question_sort' => array('key' => 'NAPP_MSG_USER_LIST_KEY_ID', 'limit' => 20),     //问答消息的有序集存储的key, 和最大消息数
        'question_hash' => array('key' => 'NAPP_MSG_USER_CONTENT_LIST', 'limit' => 20),    //问答消息的的hash存储的key, 和最大消息数
        'system' => array('key' => 'NAPP_SYSMSG_USER_CONTENT_LIST', 'limit' => 10),        //系统消息的存储的key, 和最大消息数
        'article' =>  array('key' => 'NAPP_ARTICLE_USER_CONTENT_LIST', 'limit' => 20),      //圈子消息
        'chat' => array('key' => 'NAPP_CHAT_USER_CONTENT_LIST', 'limit' => 20),            //聊天消息
        'switch' => array('key' => 'NAPP_USER_SWITCH_INFO', 'limit' => 20),                //是否接收消息的开关
        'search' => array('key' => 'MSG_SEARCH_RESULT_INFO', 'limit' => 5),               //检索消息
        'offline' => array('key' => 'NDEVICE_USER_OFFLINE_MESSAGE', 'limit' => 20),        //离线消息
    );
    
    /**
     * 构造函数
     */
    public function __construct() {
        static $_redis = null;
        if(empty($_redis)){
			/*
            $conf = array(
                'pid'   => 'iknow', 
                'tk'    => 'iknow', 
                'app'   => 'app_msg',
                'flag' => 1,
            );*/
			$conf = Bd_Conf::getConf('hk/redis/msg');
			
            $_redis = new Hk_Service_Redis($conf['service']);
        }
        $this->_redis=$_redis;
        if(empty($this->_redis)){
          Bd_Log::warning("init_redis_err:  input param".var_export($conf,true));
        }
        $this->_curMsgId = 0;
    }
    
    /**
     * 刷新用户消息失效时间
     *
     * @param int $uid
    **/
    public function refreshMsgExpireTime($uid)
    {
       if(!$uid) return;

       $types = array(
            'question_sort',
            'question_hash',
            'system',
            'article',
            'chat',
            'search',
        );

        foreach($types as $type) {
            $redisKey = $this->_getRedisKey($uid, $type);

            $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
        }
    }

    /**
     * 设置用户的消息开关
     *
     * @param unknown $uid
     * @param unknown $type
     * @param number $close
     * @return boolean
     */
    public function setUserSwitch($uid, $type, $close = 1)
    {
        $redisKey = $this->_getRedisKey($uid, 'switch');
        $ret = $this->_redis->hset($redisKey, $type, intval($close));
        return $ret;
    }
    
    /**
     * 获取用户的消息开关
     *
     * @param unknown $uid
     * @return array
     */
    public function getUserSwitch($uid) {
        $redisKey = $this->_getRedisKey($uid, 'switch');
        $ret = $this->_redis->hgetall($redisKey);
        if ($ret)
        {
            foreach($ret as $switch) {
                $switchRes[$switch['field']] = intval($switch['value']);
            }
        }
        return $switchRes;
    }
    
    
    /**
     * 获取msg id
     * 
     * @return number
     */
    public function _getUniqMsgId() {
        //云穿透到内网获取id
       	$incrKey = self::MSG_TYPE_UNIQ_ID_KEY;
        $this->_curMsgId = $this->_redis->incr($incrKey);
	Bd_Log::addNotice('NAPP_GLOBAL_MSG_UNIQ_ID', $this->_curMsgId);
	/*
        $idc = Bd_Conf::getConf('idc/cur');
        if($idc == 'yun') {
            $ret = Hk_Service_Yun::idalloc(self::MSG_TYPE_UNIQ_ID_KEY);
            $this->_curMsgId = intval($ret);
	    Bd_Log::addNotice('idalloc_NAPP_GLOBAL_MSG_UNIQ_ID_2', $ret);
        }else {
            $incrKey = self::MSG_TYPE_UNIQ_ID_KEY;
            $this->_curMsgId = $this->_redis->incr($incrKey);
	    Bd_Log::addNotice('NAPP_GLOBAL_MSG_UNIQ_ID', $this->_curMsgId);
        }*/

        return $this->_curMsgId;
    }
    
    
    public function getCurUserMaxMsgId() {
        return $this->_curMsgId;
    }
    
    /**
     * 发送消息,
     * 
     * @param int $uid
     * @param string $type  为消息类型，有如下这些：system,article,chat,search,offline,question
     * @param array $data  为消息数组，格式自定义
     */
    public function setMsg($uid, $type, $data)
    {
        if (! $uid || ! $data)
        {
            return false;
        }
        $redisKey = $this->_getRedisKey($uid, $type);
        $data['create_time']  = intval(microtime(true) * 1000);//chat 是这样
        $intUnread = isset($data['unread_count']) ? intval($data['unread_count']) : 1;//默认是未读状态
        $data['unread_count'] = $intUnread;
        $data['mid'] = $this->_getUniqMsgId();

        Bd_Log::addNotice("save_msg_no", $data["type"]);

        switch ($type)
        {
            case 'system':
            case 'article':
                $ret = $this->_redis->zadd($redisKey, $data['create_time'], json_encode($data));
                if ($ret)
                {
                    $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
                }
                break;
            case 'chat':
                //跟据fid计算unread数目，并删除旧消息，
                $paramArr = array(
                    'rn' => -1,
                    'pn' => 0,
                );
                $arrList = $this->getMsgList($uid, $type, $paramArr);
                foreach ($arrList as $chatdata)
                {
                    $msgInfo = json_decode($chatdata, true);
                    if($msgInfo['fid'] == $data['fid']) {
                        $arrRemMember[] = $chatdata;
                        $intUnread += intval($msgInfo['unread_count']);
                    }
                }
                if ($arrRemMember)
                {
                    $this->_redis->zrem($redisKey, $arrRemMember);
                }
                $data['unread_count'] = $intUnread;
                $ret = $this->_redis->zadd($redisKey, $data['create_time'], json_encode($data));
                if ($ret)
                {
                    $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
                }
                break;
            case 'search':
                $data['create_time'] = time();
                $field = $data['field'];
                unset($data['unread_count']);
                $data['unread'] = 1;
                $data = json_encode($data);
                $ret = $this->_redis->hset($redisKey, $field, $data);
                if ($ret)
                {
                    $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
                }
                break;
            case 'offline':
                $field = $data['field'];
                $data = json_encode($data);
                $ret = $this->_redis->hset($redisKey, $field, $data);
                if ($ret)
                {
                    $this->_redis->expire($redisKey , self::DEVICE_OFFLINE_MESSAGE_EXPIRE_TIME);
                }
                break;
            case 'question':
                $ret = $this->_mergeQuestionMsg($uid, $data);
                break;
            default:
                break;
        }
        $this->_deleteMsgExpire($uid, $type);
        if (! $ret)
        {
            Bd_Log::warning ( "MsgStore error:" .$uid."_".$type."_".json_encode($data));
        }
        return $ret;
    }
    
    /**
     * 把全部消息标记位已读. question 和 search 是把一个问题的消息标记已读，需要field
     * 
     * @param unknown $uid
     * @param unknown $type
     */
    public function changeMsgRead($uid, $type, $field = false)
    {
        if (!$uid)
        {
            return false;
        }
        $redisKey = $this->_getRedisKey($uid, $type);
        switch ($type)
        {
            case 'system':
            case 'article':
            case 'chat':
                if ($field)
                {
                    $data = $this->_redis->zrevrange($redisKey, 0, 1, $field);
                    if(empty($data)) {
                        return true;
                    }

                    $data = array_pop($data); 
                    $this->_redis->zrem($redisKey, $data['member']);
                    $msgInfo = json_decode($data['member'], true);
                    $msgInfo['unread_count'] = 0;
                    if ($data['score'])
                    {
                        $ret = $this->_redis->zadd($redisKey, $data['score'], json_encode($msgInfo));
                    }
                }
                else 
                {
                    $paramArr = array(
                        'rn' => -1,
                        'pn' => 0,
                    );
                    $arrList = $this->getMsgList($uid, $type, $paramArr);
                    foreach ($arrList as $data)
                    {
                        $this->_redis->zrem($redisKey, $data);
                        $msgInfo = json_decode($data, true);
                        $msgInfo['unread_count'] = 0;
                        $ret = $this->_redis->zadd($redisKey, $msgInfo['create_time'], json_encode($msgInfo));
                    }
                }
                break;
            case 'search':
                $data = $this->_redis->hget($redisKey, $field);
                $data = json_decode($data, true);
                $data['unread'] = 0;
                $ret = $this->_redis->hset($redisKey, $field, json_encode($data));
            case 'question':
                $redisKey = $this->_getRedisKey($uid, 'question_hash');
                $data = $this->_redis->hget($redisKey, $field);
                $data = json_decode($data, true);
                $data['unread_count'] = 0;
                $ret = $this->_redis->hset($redisKey, $field, json_encode($data));
                break;
            case 'offline':
                break;
            default:
                break;
        }
        return $ret;
    }
    
    /**
     * 用户清空所有消息，
     * 
     * @param unknown $uid
     * @param unknown $type
     */
    public function deleteMsgAll($uid, $type)
    {
        if ($type == 'question')
        {
            $redisKey = $this->_getRedisKey($uid, 'question_sort');
            $ret = $this->_redis->del($redisKey);
            if ($ret)
            {
                $redisKey = $this->_getRedisKey($uid, 'question_hash');
                $ret = $this->_redis->del($redisKey);
            }
        }
        else
        {
            $redisKey = $this->_getRedisKey($uid, $type);
            $ret = $this->_redis->del($redisKey);
        }
        return $ret;
    }
    
    
    /**
     * 用户删除一条消息
     * 
     * @param unknown $uid
     * @param unknown $type
     * @param unknown $field  field 或者 score
     */
    public function deleteMsgByKey($uid, $type, $field)
    {
        $redisKey = $this->_getRedisKey($uid, $type);
        if (!$redisKey || !$field)
        {
            return false;
        }
        switch ($type)
        {
            case 'system':
            case 'article':
            case 'chat':
                $ret = $this->_redis->zremrangebyscore($redisKey, $field, $field);
                break;
            case 'search':
                $ret = $this->_redis->hdel($redisKey, $field);
                break;
            case 'offline':
                //离线消息只能清空全部
                break;
            case 'question':
                $redisKey = $this->_getRedisKey($uid, 'question_sort');
                $arrList = $this->_redis->zrevrange($redisKey, 0, -1);
                foreach($arrList as $data)
                {
                    if($field == $data['member'])
                    {
                        $member = $data['member'];
                        break;
                    }
                }
                $this->_redis->zrem($redisKey, array($member));
                $redisKey = $this->_getRedisKey($uid, 'question_hash');
                $ret = $this->_redis->hdel($redisKey, $field);
                break;
            default:
                break;
        }
        return $ret;
    }
    
    /**
     * 删除消息有3种操作：用户清空所有消息，用户删除某条消息，系统自动删除过量的消息
     * 这个是自动删除过量消息
     * 
     * @param unknown $uid
     * @param unknown $type
     * @param string $keys
     */
    private function _deleteMsgExpire($uid, $type)
    {
        //todo: 改成10分之1个概率执行
        if (! $uid  )
        {
            return false;
        }
        $redisKey = $this->_getRedisKey($uid, $type);
        $limit = $this->_getLimit($uid, $type);
        switch ($type)
        {
            case 'system':
            case 'article':
            case 'chat':
                $dataArr =  $this->_redis->zrevrange($redisKey, 0, -1);
                $count = count($dataArr);
                if($count > $limit) {
                    $remReq = array_slice($dataArr, $limit, $count - $limit);
                    foreach ($remReq as $r)
                    {
                        $member[] = $r['member'];
                    }
                    $this->_redis->zrem( $redisKey, $member);
                }
                break;
            case 'search':
                $dataArr = $this->_redis->hgetall($redisKey);
                $count = count($dataArr);
                if($count > $limit) {
                    foreach($dataArr as $oneMsg){
                        $arr = json_decode($oneMsg['value'],true);
                        $createTime = intval($arr['create_time']);
                        $tmpArr[$createTime] = $oneMsg;
                    }
                    //按创建时间倒序
                    krsort($tmpArr);
                    
                    //最多保留最近$limit条
                    $delArr = array_slice($tmpArr, $limit);
                    if(!empty($delArr)){
                        foreach($delArr as $oneMsg){
                            $this->_redis->hdel($redisKey, $oneMsg['field']);
                        }
                    }
                }
                break;
            case 'offline':
                ;
                break;
            case 'question':
                $this->_deleteExpireQuestionMsg($uid, $type);
                break;
            default:
                break;
        }
    }
    
    /**
     * 获取消息列表
     * 
     * @param unknown $uid
     * @param unknown $type
     * @param array $paramArr
     */
    public function getMsgList($uid, $type, $paramArr = array())
    {
        if (! $uid )
        {
            return false;
        }
        $redisKey = $this->_getRedisKey($uid, $type);
        $rn = $paramArr['rn'];
        $pn = $paramArr['pn'];
        $time = $paramArr['time'];
        $start = !empty($pn) ? $pn : 0;
        $stop = !empty($rn) ? $rn : self::DEFAULT_RETURN_RN_VALUE;
        $ret = array();
        switch ($type)
        {
            case 'system':
            case 'article':
            case 'chat':
                $data = $this->_redis->zrevrange($redisKey, $start, $stop, $time);
                if ($data)
                {
                    foreach ($data as $d)
                    {
                        $ret[] = $d['member'];
                    }
                }
                break;
            case 'search':
                return $this->_getSearchMsg($redisKey, $start, $stop);//Service_Page_News_V2_AskAnswer 里调用
                break;
            case 'offline':
                $ret = $this->_redis->hgetall($redisKey);
                $this->_redis->del($redisKey);
                break;
            case 'question':
                $redisKey = $this->_getRedisKey($uid, 'question_sort');
                $ret = $this->_redis->zrevrange($redisKey, $start, $stop, $time);
                if (! empty($ret))
                {
                    $fields = array();
                    foreach($ret as $keyArr) {
                        $fields[] = $keyArr[ 'member' ];
                    }
                    $redisKey    = $this->_getRedisKey($uid, 'question_hash');
                    $ret = $this->_redis->hmget($redisKey, $fields);
                }
                
                break;
            default:
                break;
        }
        
        return  $ret;
    }
    
    /**
     * 获取消息数量
     * 
     * @param unknown $uid
     * @param unknown $type
     * @param string $onlyUnread
     */
    public function getMsgCount($uid, $type, $onlyUnread = true, $paramArr = array())
    {
        if (! $uid )
        {
            return false;
        }
        $redisKey = $this->_getRedisKey($uid, $type);
        $time = intval(microtime(true) * 1000);
        $start = 0;
        $stop = -1;
        $total = 0;
        switch ($type)
        {
            case 'system':
            case 'article':
            case 'chat':
                if ($onlyUnread)
                {
                    $dataArr = $this->_redis->zrevrange($redisKey, $start, $stop);
                    if(!empty($dataArr)) {
                        foreach($dataArr as $info) {
                            $msg = json_decode($info['member'],true);
                            $total += intval($msg['unread_count']);
                        }
                    }
                }
                else
                {
                    $total = $this->_redis->zcount($redisKey, 0, $time);
                }
                break;
            case 'search':
                $dataArr = $this->_redis->hgetall($redisKey);
                if($dataArr){
                    if ($onlyUnread)
                    {
                        foreach($dataArr as $v){
                            $tmp = json_decode($v['value'], true);
                            $total += $tmp['unread'];
                        }
                    }
                    else 
                    {
                        $total = count($dataArr);
                    }
                }
                break;
            case 'offline':
                ;
                break;
            case 'question':
                $total = $this->_getQuestionMsgCount($uid, $onlyUnread, $paramArr);
                break;
            default:
                break;
        }
        
        return $total;
    }
    
    /**
     * 获取问题消息数量
     * 
     * @param unknown $uid
     * @param unknown $onlyUnread
     * @param unknown $paramArr
     * @return boolean
     */
    private function _getQuestionMsgCount($uid, $onlyUnread, $paramArr)
    {
        $total = 0;
        if ($onlyUnread)
        {
            $data = $this->getMsgList($uid, 'question');
            if ($data)
            {
                foreach ($data as $d)
                {
                    $d = json_decode($d, true);
                    $total = $total + $d['unread_count'];
                }
            }
        }
        else
        {
            $redisKey = $this->_getRedisKey($uid, 'question_sort');
            $total = $this->_redis->zcount($redisKey, $paramArr['max'], $paramArr['min']);
        }
        return $total;
    }
    
    
    /**
     * 获取检索消息
     * 
     * @param unknown $redisKey
     * @return array
     */
    private function _getSearchMsg($redisKey, $pn, $rn)
    {
        $ret = $this->_redis->hgetall($redisKey);
        if(empty($ret)){
            return array();
        }
        
        $tmpArr = array();
        foreach($ret as $oneMsg){
            $arr = json_decode($oneMsg['value'],true);
            $createTime = intVal($arr['create_time']);
            $tmpArr[$createTime] = $oneMsg;
        }
        
        //按创建时间倒序
        krsort($tmpArr);
        
        
        $resArr = array_slice($tmpArr, $pn, $rn);
        return array_values($resArr);
    }
    
    /**
     * 如果是问题的消息，同一个问题的消息要合并
     * 
     */
    private function _mergeQuestionMsg($uid, $data)
    {
        //更新hash表
        $microtime = intval(microtime (true) * 1000) / 1000;
        $redisKey = $this->_getRedisKey($uid, 'question_hash');
        $hashField = $data['field'];
        $ret = $this->_redis->hget($redisKey, $hashField);
        $ret = json_decode($ret, true);
        $ret = empty($ret) ? array() : $ret;
        
        $ret[ 'unread_count' ]++; 
        $ret[ 'mid' ]          = $this->_getUniqMsgId(); 
        $ret[ 'create_time' ]  = $microtime;
        
        $data = array_merge($data, $ret); //跟以前有同一个key的消息合并
        
        $ret1 = $this->_redis->hset($redisKey, $hashField, json_encode($data));
        if ($ret1)
        {
            $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
        } else {
            return false;
        }
        
        //更新有序集
        $redisKey = $this->_getRedisKey($uid, 'question_sort');
        $ret2 = $this->_redis->zadd($redisKey, $data['create_time'], $hashField);
        if ($ret2)
        {
            $this->_redis->expire($redisKey, self::MSG_STORAGE_EXPIRE_TIME);
        }

        return $ret2;
    }
    
    private function _deleteExpireQuestionMsg($uid, $type)
    {
        if($type == 'question'){
            $typeSort = 'question_sort';
            $typeHash = 'question_hash';
            $redisKeySort = $this->_getRedisKey($uid, $typeSort);
            $redisKeyHash = $this->_getRedisKey($uid, $typeHash);
        } else{
            $redisKeySort = $this->_getRedisKey($uid, $type);
            $redisKeyHash = $this->_getRedisKey($uid, $type);
        }
        $limit = $this->_getLimit($uid, 'question_sort');
        $limit = 20;
        
        Bd_Log::addNotice('DELETE_QUESTION_MSG_KEY', $redisKeySort);
        $dataArr = $this->_redis->zrevrange($redisKeySort, 0, -1);
        if(empty($dataArr)) {
            $intRealCount = intval($this->_redis->zcard($redisKeySort));
            Bd_Log::addNotice('DELETE_QUESTION_MSG_ERROR', $intRealCount);
            Hk_Util_Log::start('hs_special_redis_delete');
            if($intRealCount > 20){
                $zrembyscoreResult = intval($this->_redis->zremrangebyscore($redisKeySort, 0, (time()-(86400*3))));
                Bd_Log::addNotice('DELETE_QUESTION_MSG_BY_SCORE', $zrembyscoreResult);
                if($zrembyscoreResult <= 0){
                    Hk_Util_Log::stop('hs_special_redis_delete');
                    return false;
                }
                $dataArr = $this->_redis->zrevrange($redisKeySort, 0, -1);
                if(count($dataArr) > 20){
                    $remReq = array_slice($dataArr, 20);
                    $fields = array();
                    foreach($remReq as $keyArr) {
                        $fields[] = $keyArr['member'];
                    }
                    $zremResult = $this->_redis->zrem( $redisKeySort, $fields);
                    Bd_Log::addNotice('DELETE_QUESTION_MSG_SORT_MORE_THAN_20_DEL', $zremResult);
                    $dataArr = array_slice($dataArr, 0, 20);
                }
                $fields = array();
                foreach($dataArr as $keyArr) {
                    $fields[] = $keyArr['member'];
                }
                $newHashSetArr = $this->_redis->hmget($redisKeyHash, $fields);
                Bd_Log::addNotice('DELETE_QUESTION_MSG_HASH_GET_COUNT', count($newHashSetArr));
                $delResult = $this->_redis->del($redisKeyHash);
                Bd_Log::addNotice('DELETE_QUESTION_MSG_HASH_KEY_DEL', intval($delResult));
                $setSuccess = 0;
                foreach ($newHashSetArr as $value) {
                    $arr = json_decode($value, ture);
                    $field = $arr['field'];
                    if(!empty($field) && !empty($value)){
                        $setResult = $this->_redis->hset($redisKeyHash, $field, $value);
                        if($setResult === true){
                            $setSuccess++;
                        }
                    }
                }
                Bd_Log::addNotice('DELETE_QUESTION_MSG_HASH_SET_COUNT', intval($setSuccess));
            }
            Hk_Util_Log::stop('hs_special_redis_delete');
            return false;
        }

        $count = count($dataArr);
        Bd_Log::addNotice('DELETE_QUESTION_MSG_COUNT', 'count-'.$count.'-limit-'.$limit);
        if($count > $limit) {
            $remReq = array_slice($dataArr, $limit);
            $fields = array();
            foreach($remReq as $keyArr) {
                $fields[] = $keyArr['member'];
            }
            $zremResult = $this->_redis->zrem( $redisKeySort, $fields);
            $hdelResult = $this->_redis->hdel( $redisKeyHash, $fields);
            Bd_Log::addNotice('DELETE_QUESTION_MSG_DELETE', 'will-'.count($fields).'-zrem-'.intval($zremResult).'-hdel-'.intval($hdelResult));
        }
    }
    
    /**
     * 获取消息存储在redis的key
     * 
     * @param unknown $uid
     * @param unknown $type
     * @return string
     */
    private function _getRedisKey($uid, $type)
    {
        if (!$uid)
        {
            return false;
        }
        return $uid . ":" . self::$msg_key[$type]['key'];
    }
    
    /**
     * 获取消息存储的最大数据
     *
     * @param unknown $uid
     * @param unknown $type
     * @return string
     */
    private function _getLimit($uid, $type)
    {
        return self::$msg_key[$type]['limit'];
    }

    /**
     * 云穿透获取id
     *
     * @return int id
     */
    private function getIdFromHttp() {
        $url = "http://10.16.192.20/napi/api/getidalloc";
        $postData = array(
            'name' => self::MSG_TYPE_UNIQ_ID_KEY,
        );      
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_NOSIGNAL, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
        $ret = curl_exec($curl);
        if(empty($ret)) {
            $errno = curl_errno($curl);
            Bd_Log::warning("Error:[yun proxy connect error] curl $url error:".$errno);
            curl_close($curl);
            return false;
        }       
        curl_close($curl);
        $arrResult = json_decode($ret, true);
        if($arrResult['errNo'] != 0){
            Bd_Log::warning("Error:[yun proxy errNo not 0]");
            return false;
        }       
        return $arrResult['data'];
    }
}
