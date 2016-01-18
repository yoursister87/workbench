<?php
/**
 * @package              
 * @subpackage           
 * @brief                $permanent QRcode count requirement$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @file                 permanentQRcodeCount.class.php$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Redis_Weixin_PermanentQRcodeCount
{
    protected $objRedis;
    protected $prefix = 'weixin_subscribe_request_queue';
    public function __construct(){
        $utilRedis = new Gj_Util_Redis( RedisConfig::$HouseWapRedisPool );
        $this->objRedis = $utilRedis->getMasterRedis('Master');
    }


    /**
     * @brief the value of key incr one
     * @param $key
     */
    public function incrValueByKey($key){
        $this->objRedis->incr($key);
    }
    /**
     * @brief get the value of the key from redis handle
     * @param $name
     */
    public function getValueByKey($key){
        return $this->objRedis->get($key);
    }

    /**
     * @brief set the value on the key to redis server
     * @param $key
     * @param $name
     * @param $expire
     */
    public function setValueByKey($key, $value){
        $this->objRedis->set($key, $value);
    }

    /**
     * @brief push data into a queue by key
     * @param $key
     * @param $value
     */
    public function pushValueToQueueByKey($key, $value){
        $this->objRedis->lPush($key, $value);
    }
    /**
     * @brief get the length of a queue
     * @param $key
     */
    public function getLengthOfQueueBykey($key){
        return $this->objRedis->lSize($key);
    }

    /**
     * @brief pop element by start end and key
     * @param $key
     * @param int $start
     * @param $end
     */
    public function popElementOfQueueByKey($key, $start = 0, $end){
        return $this->objRedis->lRange($key, $start, $end);
    }
}