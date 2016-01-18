<?php
/**
 * @package              
 * @subpackage           
 * @brief                $微信订阅请求队列$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @file                 SubscribeRequestQueue.php$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Redis_Weixin_SubscribeRequestQueue
{
    protected $objRedis;
    protected $requestQueueKey;
    protected $prefix = 'weixin_subscribe_request_queue';
    public function __construct(){
        $utilRedis = new Gj_Util_Redis( RedisConfig::$HouseWapRedisPool );
        $this->objRedis = $utilRedis->getMasterRedis('Master');
        $this->requestQueueKey = $this->prefix;
    }

    /**
     * @param string $data
     * @param bool $distinct 在插入data之前检查是否已存在cache的值。
     * @return bool
     */
    public function pushSubscribeRequest($data = '', $distinct = false, $cacheData = ''){
        if(!$data){
            return false;
        }
        if($distinct && $cacheData){
            $this->objRedis->lRemove($this->requestQueueKey, $cacheData, 1);
        }
        if($this->objRedis->lPush($this->requestQueueKey, $data)){
            return true;
        }
        return false;

    }

    public function popSubscribeRequest(){
        return $this->objRedis->lPop($this->requestQueueKey);
    }

    public function getSizeOfSubcribeRequest(){
        return $this->objRedis->lSize($this->requestQueueKey);
    }
}