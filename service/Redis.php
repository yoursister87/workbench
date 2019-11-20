<?php
/***************************************************************************
 *
 * Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file Redis.php
 * @author wangdong03(com@baidu.com)
 * @date 2014-3-10
 * @version 1.0
 **/
 class Hk_Service_Redis{

    private $ralrpc;
    private $conf;
    private $flag;

    /*
     * $arrConf = array(
     *  'pid' => 'iknow',
     *  'tk'  => 'iknow',
     *  'app' => 'napi',
     *  'instance' => 'ranks',
     *  'flag' => 1,
     * );
     */
    public function __construct($arrConf){
        $this->conf = $arrConf;
        if($this->conf == NULL)
        {
            $this->conf = $arrConf = array(
                'pid' => 'homework',
                'tk'  => 'homework',
                'app' => 'napi',
                'flag' => 1,
            );
        }
        $this->ralrpc = Bd_RalRpc::create ( 'Ak_Service_Redis', array (
                'pid'       => $this->conf['pid'],
                'tk'        => $this->conf['tk'],
                'app'       => $this->conf['app'],
                'instance'  => $this->conf['instance'],
        ) );
        $this->flag = $this->conf['flag'] === 0 ? false:true;
    }

    public function set($key,$value){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->SET(array(
            'key'   => $key,
            'value' => $value,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when set key : $key , value: $value , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_set_".$newKey);
        return true;
    }

    public function get($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->GET(array(
            'key'   => $key
        ));
        if (!isset($ret['err_no'])) {
            return false;
        }
        if (0 != $ret['err_no']) {
            Bd_Log::warning("redis error when get key : $key , redis return : " . json_encode($ret));

            return false;
        }
        $newKey = $this->_handleKey($key);
        if ($ret['ret'][$key] === null)
        {
            Hk_Util_Log::incrKey("redis_getfail_".$newKey);
        }
        else
        {
            Hk_Util_Log::incrKey("redis_getok_".$newKey);
        }
        return $ret['ret'][$key];
    }

    /**
     * del
     *
     * @param  string $key
     * @param  int double
     * @return bool true/false
     */
    public function del($key, $double = 0){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }

        //同步内网和云缓存问题
    /*
        if($double === 0) {
            $arrCommand = array(
                'command_no' => Hk_Const_Command::CMD_DOUBLE_DEL_CACHE,
                'key'        => $key,
            );
            $idc = ral_get_idc();
            if($idc == 'yun') {
                $ret = Hk_Service_CommandProxy::talkToInternal(Hk_Const_Command::CMD_DOUBLE_DEL_CACHE, $arrCommand);
            }else {
                $ret = Hk_Service_CommandProxy::talkToExternal(Hk_Const_Command::CMD_DOUBLE_DEL_CACHE, $arrCommand);
            }
        }*/

        $ret = $this->ralrpc->DEL(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when del key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_del_".$newKey);
        return true;
    }

    public function setex($key,$value,$seconds = 21600){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
            return true;
        }
        $seconds = intval($seconds);
        $ret = $this->ralrpc->SETEX(array(
            'key'       => $key,
            'value'     => $value,
            'seconds'   => $seconds,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when setex key : $key , value: $value , seconds: $seconds, redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_setex_".$newKey);
        return true;
    }

    public function mget($keys){
        if( false == $this->flag ){
            return false;
        }
        $req = array();
        foreach($keys as $key){
            $req[] = array('key' => $key);
        }
        if(empty($req)){
            return array();
        }
        $ret = $this->ralrpc->GET(array(
            'reqs'      => $req,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when mget key : ".json_encode($keys).", redis return : ".json_encode($ret));
            return false;
        }
        Hk_Util_Log::incrKey("redis_mget_".$keys[0]);
        return $ret['ret'];
    }

    /**
     * mset
     *
     * @param  mix $arrValue array('a' => 'b', 'b' => 'c')
     * @return bool true/false
     */
    public function mset($arrValue){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $req = array();
        foreach($arrValue as $key => $value){
            $req[] = array('key' => $key, 'value' => $value);
        }
        if(empty($req)){
            return true;
        }
        $ret = $this->ralrpc->SET(array(
            'reqs'      => $req,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when mset arrValue : ".json_encode($arrValue));
            
Bd_Log::warning("-----maxranje-----".var_export($ret, true));
            return false;
        }
        Hk_Util_Log::incrKey("redis_mset_".$keys[0]);
        return true;
    }

    public function ttl($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->TTL(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when ttl key : $key , redis return : ".json_encode($ret));
            return false;
        }
        Hk_Util_Log::incrKey("redis_ttl_".$key);
        return $ret['ret'][$key];
    }

    public function incr($key){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->INCR(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when incr key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_incr_".$newKey);
        return $ret['ret'][$key];
    }

    public function incrby($key, $step = 1){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->INCRBY(array(
            'key'   => $key,
            'step'  => intval($step),
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when incrby key : $key , step : $step , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_incrby_".$newKey);
        return $ret['ret'][$key];
    }

    public function decr($key){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->DECR(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when decr key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_decr_".$newKey);
        return $ret['ret'][$key];
    }

    public function hset($key,$field,$value){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HSET(array(
            'key'   => $key,
            'field' => $field,
            'value' => $value
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hset key : $key ,field : $field , value : $value redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hset_".$newKey);
        return true;
    }

    public function hlen($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HLEN(array(
            'key'   => $key,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hlen key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hlen_".$newKey);
        return $ret['ret'][$key];
    }

    public function hget($key,$field){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HGET(array(
            'key'   => $key,
            'field' => $field
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hget key : $key ,field : $field  redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hget_".$newKey);
        return $ret['ret'][$key];
    }

    public function hmget($key,$fields){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HMGET(array(
                        'key'   => $key,
                        'field' => $fields
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hget key : $key, redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hmget_".$newKey);
        return $ret['ret'][$key];
    }

    public function hgetall($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HGETALL(array(
                        'key'   => $key,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hgetall key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hgetall_".$newKey);
        return $ret['ret'][$key];
    }

    public function hdel($key,$field){
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        if (! is_array($field))
        {
            $field = array($field);
        }
        $ret = $this->ralrpc->HDEL(array(
            'key'   => $key,
            'field' => $field,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hset key : $key ,field : $field, redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hdel_".$newKey);
        return intval($ret['ret'][$key]);
    }

    public function hexists($key,$field){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HEXISTS(array(
            'key'   => $key,
            'field' => $field,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no'] ){
            Bd_Log::warning("redis error when hexists key : $key , field : $field redis return err_no: ".$ret['err_no'].": ".json_encode($ret));
            return false;
        }
        if( 0 == $ret['ret'][$key] ){
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hexists_".$newKey);
        return true;
    }

    public function exists($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->EXISTS(array(
            'key'   => $key,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when exists key : $key, redis return : ".json_encode($ret));
            return false;
        }
        if($ret['ret'][$key] == 0){
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_exists_".$newKey);
        return true;
    }

    public function expire($key , $seconds){
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->EXPIRE(array(
            'key'       => $key,
            'seconds'   => $seconds,
        ));
        if (!isset($ret['err_no'])) {
            return false;
        }
        if( 0 != $ret['err_no']){
            Bd_Log::warning("redis error when expire key : $key , seconds : $seconds redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_expire_".$newKey);
        return true;
    }

    public function zrevrank($key ,$member){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZREVRANK(array(
            'key'       => $key,
            'member'    => $member,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zrevrank key : $key , member : $member redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zrevrank_".$newKey);
        return $ret['ret'][$key];
    }

    public function zincrby($key,$step,$member){
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->ZINCRBY(array(
            'key'       => $key,
            'step'      => $step,
            'member'    => $member,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zincrby key : $key , member : $member ,step $step redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zincrby_".$newKey);
        return true;
    }

    //min其实是最大值，有坑，小心
    public function zrevrange($key,$start,$stop,$min = false){
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->ZREVRANGEBYSCOREWITHSCORES(array(
            'key'   => $key,
            'min'   => (string)(!empty($min) ? ($min) : '+INF'),
            'max'   => '-INF',
            'count' => $stop,
            'offset'=> $start,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zrevrange key : $key , start : $start ,stop : $stop redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zrevrange_".$newKey);
        return $ret['ret'][$key];

    }

    public function zrange($key, $start, $stop){
        if(false == $this->flag){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
            return true;
        }
        $ret = $this->ralrpc->ZRANGE(array(
            'key'   => $key,
            'start' => $start,
            'stop'  => $stop,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zrange key : $key , start : $start ,stop : $stop redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zrange_".$newKey);
        return $ret['ret'][$key];
    }

    /**
     * 上面的函数名字写错了，只能另起名字了
     * @param  string  $key
     * @param  integer $start 起始下标 0开始
     * @param  integer $stop  结束下标
     * @return false|array
     */
    public function zrevrangewithscore($key,$start,$stop){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZREVRANGEWITHSCORES(array(
            'key'   => $key,
            'start' => $start,
            'stop'  => $stop,
        ));
        if( !isset($ret['err_no']) || 0 !== $ret['err_no']){
            Bd_Log::warning("redis error when zrevrangewithscore key : $key , start : $start ,stop : $stop redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zrevrangewithscore_".$newKey);
        return $ret['ret'][$key];

    }

    public function zcount($key,$max = false, $min = false){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZCOUNT(array(
                        'key'   => $key,
                        'min'   => (string)(!empty($min) ? ($min) : '-INF'),
                        'max'   => (string)(!empty($max) ? ($max) : '+INF'),
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zcount key : $key   redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zcount_".$newKey);
        return $ret['ret'][$key];

    }


    public function zadd($key,$num,$member){
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->ZADD(array(
            'key'       => $key,
            'members'   => array(
                                array('score' => $num , 'member'=> $member ),
                           )
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zadd key : $key , member : $member ,num :$num redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zadd_".$newKey);
        return true;
    }

    public function zscore( $key ,$member ){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZSCORE(array(
            'key'       => $key,
            'member'    => $member
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zscore key : $key , member : $member redis return : ".json_encode($ret));
            return 0;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zscore_".$newKey);
        return intval($ret['ret'][$key]);
    }

    /**
     * zcard
     *
     * @param  int  $key  key
     * @return bool true/false
     */
    public function zcard($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZCARD(array(
            'key' => $key,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zcard key : $key redis return : ".json_encode($ret));
            return 0;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zcard_".$newKey);
        return intval($ret['ret'][$key]);
    }

    public function zrem( $key, $member){

        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->ZREM(array(
            'key'       => $key,
            'member'    => $member
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zrem key : $key , member : $member redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zrem_".$newKey);
        return intval($ret['ret'][$key]);
    }

    public function zremrangebyscore($key, $min, $max)
    {
        if( false == $this->flag ){
                    return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                    return true;
            }
        $ret = $this->ralrpc->ZREMRANGEBYSCORE(array(
                        'key' => $key,
                        'min' => $min,
                        'max' => $max,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
                    Bd_Log::warning("redis error when zremrangebyscore key : $key ,   redis return : ".json_encode($ret));
                    return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zremrangebyscore_".$newKey);
        return intval($ret['ret'][$key]);
    }

    /**
     * 注意：待删除的集合由小到大排列
     *
     * @param $key
     * @param $start
     * @param $stop
     * @return bool|int
     */
    public function zremrangebyrank($key,$start,$stop)
    {
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->ZREMRANGEBYRANK(array(
            'key' => $key,
            'start' => $start,
            'stop' => $stop,
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when zremrangebyrank key : $key ,   redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_zremrangebyrank_".$newKey);
        return intval($ret['ret'][$key]);
    }

    public function __call($method, $args) {
        if( false == $this->flag ){
            return false;
        }
            if(Hk_Util_Tools::isTestRequest()){
                return true;
            }

        if ($this->ralrpc) {
        Bd_Log::addNotice("redis_call_".$method, 1);
            return call_user_func_array ( array($this->ralrpc, $method), $args );
        } else {
            return array("err_no"=>20140612,"err_msg"=>"redisproxy:redisrpc init failed");
        }
    }

    private function _handleKey($key)
    {
        $arr = explode('_', $key);
        $newKey = '';
        foreach ($arr as $a)
        {
            if (is_numeric($a) || strlen($a) >= 30)
            {
                $a = "N";
            }
            $newKey .= $a."_";
        }
        return $newKey;
    }

    public function lpush($key,$value){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->LPUSH(array(
            'key'   => $key,
            'value' => $value,
        ));
        if (!isset($ret['err_no'])) {
            return false;
        }
        if(0 != $ret['err_no']){
            Bd_Log::warning("redis error when lpush key : $key , value: $value , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_lpush_".$newKey);
        return true;
    }

    public function rpush($key,$value){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->RPUSH(array(
            'key'   => $key,
            'value' => $value,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when rpush key : $key , value: $value , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_rpush_".$newKey);
        return true;
    }

    public function rpop($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->RPOP(array(
            'key' => $key
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when rpop key : $key, redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_rpop_".$newKey);
        return $ret['ret'][$key];
    }

    public function llen($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->LLEN(array(
            'key' => $key
        ));
        if (!isset($ret['err_no'])) {
            return false;
        }
        if (0 != $ret['err_no']) {
            Bd_Log::warning("redis error when llen key : $key, redis return : " . json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_llen_".$newKey);
        return $ret['ret'][$key];
    }

    public function lrange($key, $start, $stop){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->LRANGE(array(
            'key'   => $key,
            "start" => $start,
            "stop" => $stop
        ));
        if (!isset($ret['err_no'])) {
            return false;
        }
        if (0 != $ret['err_no']) {
            Bd_Log::warning("redis error when lrange key : $key, start : $start, end : $stop, redis return : " . json_encode($ret));

            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hlen_".$newKey);
        return $ret['ret'][$key];
    }

    public function hincrby($key, $field, $step = 1) {
        if (false == $this->flag) {
            return false;
        }

        $ret = $this->ralrpc->HINCRBY(array(
            'key'   => $key,
            'field' => $field,
            'step'  => intval($step),
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hincrby key : $key ,field : $field, redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hincrby_".$newKey);
        return intval($ret['ret'][$key]);
    }

    /**
     * getset，原子操作，一般用户加锁的时候用
     */
    public function getset($key,$value){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
            return true;
        }
        $ret = $this->ralrpc->GETSET(array(
            'key'   => $key,
            'value' => $value,
        ));
        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when getset key : $key , value: $value , redis return : ".json_encode($ret));
            return false;
        }

        $newKey = $this->_handleKey($key);
        if ($ret['ret'][$key] === null)
        {
            Hk_Util_Log::incrKey("redis_getfail_".$newKey);
        }
        else
        {
            Hk_Util_Log::incrKey("redis_getok_".$newKey);
        }
        return $ret['ret'][$key];
    }

    /**
     * setnx，如果不存在设置，否则不进行操作
     */
    public function setnx($key,$value){
        if( false == $this->flag ){
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
            return true;
        }
        $ret = $this->ralrpc->SETNX(array(
            'key'   => $key,
            'value' => $value,
        ));

        if(!isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when setnx key : $key , value: $value , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_setnx_".$newKey);
        return $ret['ret'][$key] ? true : false;
    }

    /**
     * 获取键类型
     */
    public function type($key){
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->TYPE(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when type key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_type_".$newKey);
        return $ret['ret'][$key];
    }

    public function hkeys($key)
    {
        if( false == $this->flag ){
            return false;
        }
        $ret = $this->ralrpc->HKEYS(array(
            'key'   => $key
        ));
        if( !isset($ret['err_no']) || 0 != $ret['err_no']){
            Bd_Log::warning("redis error when hkeys key : $key , redis return : ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hkeys_".$newKey);
        return $ret['ret'][$key];
    }

    /**
     * 批量向hash存储数据，$values为$field => $value映射
     *
     * @param string       $key
     * @param array        $values
     * @return boolean
     */
    public function hmset($key, array $values) {
        if (false === $this->flag) {
            return false;
        }

        $reqs = array();
        foreach ($values as $field => $value) {
            $reqs[] = array("key" => $key, "field" => $field, "value" => $value);
        }
        $ret  = $this->ralrpc->HMSET(array(
            "reqs" => $reqs,
        ));
        if (!isset($ret['err_no']) || 0 != $ret['err_no']) {
            Bd_Log::warning("redis error when hmset key: $key, redis return: ".json_encode($ret));
            return false;
        }
        $newKey = $this->_handleKey($key);
        Hk_Util_Log::incrKey("redis_hmset" . $newKey);
        return "OK" === $ret['ret'][$key] ? true : false;
    }
}
