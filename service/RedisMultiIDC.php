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
class Hk_Service_RedisMultiIDC{

    /*
     * $arrConf = array(
     *  'pid' => 'iknow',
     *  'tk'  => 'iknow',
     *  'app' => 'napi',
     *  'instance' => 'ranks',
     *  'flag' => 1,
     * );
     */
    private $objRedis;
    private $arrConf;
    public function __construct($arrConf = NULL){
        $this->arrConf  = $arrConf;
        $this->objRedis = new Hk_Service_Redis($arrConf);
    }

    public function __call($funName, $arguments){  
        $res = true;
        if(method_exists($this->objRedis, $funName)){
            $res =  call_user_func_array(array($this->objRedis, $funName), $arguments); 
        }else{
            Bd_Log::warning("Hk_Service_RedisMultiIDC call $funName with params ".json_encode($arguments, true)." failed");
            return false;
        }
        if($res !== false){
            //如果是更新接口，则发异步nmq
            switch($funName){
                case 'set':
                case 'setex':
                case 'del':
                case 'lpush':
                case 'zrevrangewithscore':
                case 'zrevrank':
                case 'zadd':
                case 'zcount':
                case 'zrem':
                    $arrCommand = array(
                            'command_no' => Hk_Const_Command::CMD_DOUBLE_SET_REDIS,
                            'funName'    => $funName,
                            'args'       => $arguments,
                            'redisName'  => $this->arrConf,//redis实例名称
                            'idc'        => ral_get_idc(),//本地idc机房
                            );
                    $objNmq = new Hk_Service_Nmq();
                    $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_SET_REDIS, $arrCommand);
                    if($nmqRes === false){
                        Bd_Log::warning('setMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                    }
                    break;

                default:
                break;
            }
        }
        return $res;
    }
}
