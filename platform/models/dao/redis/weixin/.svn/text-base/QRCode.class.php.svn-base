<?php
/**
 * @package
 * @subpackage
 * @brief                $微信二维码生产接口$
 * @file                 WeixinSubscribe.class.php
 * @author               $Author:    cuijianwen <cuijianwen@ganji.com>$
 * @lastChangeBy         14-11-28
 * @lastmodified         上午10:29
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Redis_Weixin_QRCode
{
    protected $objRedis;
    protected $strPrefix    = "WeixinQRcode_"; //前缀
    protected $strSuffix    = ""; //后缀
    protected $key = "QRSceneId";
    protected $sceneId ;
    protected $arrRet = array(
        "errno" => 0,
        "errmsg"    => '成功',
        "data"      => array(),
    );

    public function __construct(){
        file_put_contents("/tmp/wxlog.txt." . date("Ymd") , json_encode(array( "ready to get new redis obj...\n", RedisConfig::$HouseWapRedisPool ) ) ."\n\n" , FILE_APPEND );
        $utilRedis = new Gj_Util_Redis( RedisConfig::$HouseWapRedisPool );
        $this->objRedis = $utilRedis->getMasterRedis('Master');
        if($this->objRedis->socket ){
            file_put_contents("/tmp/wxlog.txt." . date("Ymd") , "获取redis 对象成功.  RedisConfig::\$HouseWapRedisPool "  . "\n" , FILE_APPEND );
        }else{
            file_put_contents("/tmp/wxlog.txt." . date("Ymd") , "获取redis 对象失败.  RedisConfig::\$HouseWapRedisPool " . "\n" , FILE_APPEND );
        }
        $this->key = $this->strPrefix . $this->key . $this->strSuffix . date("Ymd");
        //默认给sceneId赋值
        $sceneId = $this->getCurrentKey();
        //如果取不到重试一次,避免redis出现问题
        if(empty($sceneId)){
            $sceneId = $this->getCurrentKey();
        }
        if(!empty($sceneId)){
            $this->sceneId = $sceneId;
        }else{
            $this->objRedis->set($this->key,10001) && $this->sceneId = 10001;
        }
        file_put_contents("/tmp/wxlog.txt." . date("Ymd") , "Dao_Redis_Weixin_QRCode SceneId value equal :" . $this->sceneId . "\n\n", FILE_APPEND );
    }

    protected function getCacheKey ( $sceneId = '' ){
        $key = $this->strPrefix. "scene_" . $sceneId . $this->strSuffix;
        file_put_contents("/tmp/wxlog.txt." . date("Ymd") , json_encode(array( "getCacheKey:" . $sceneId , $key ) ) . "\n\n", FILE_APPEND );
        return $key;
    }

    public function getCurrentKey(){
        return $this->objRedis->get($this->key);
    }
    public function getQRSceneId (){
//incr max value: Signed 64-bit integer , -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807
        if( $this->sceneId < 999999999 && $this->sceneId > 10000){
            $this->sceneId = $this->objRedis->incr($this->key);
            //key的名称每天0:00:00会发生改变， value从1开始, 保留30天.
            $this->objRedis->expire( $this->key , 86400 * 30 );
        }else{
//永久二维码 最多10000个，预留。
            $this->sceneId = 10001;
        }

        return $this->sceneId;
    }

    public  function insertQRContent( $event_type = '' , $params_str = '' ){
        $cache_key = $this->getCacheKey( $this->sceneId );

        $cache_data = array(
            "event_type"    =>  $event_type,
            "params_str"    =>  $params_str,
        );

//return $cache_data;

        try{
            $set_status = $this->objRedis->set( $cache_key , json_encode( $cache_data ) );
            $expire_status = $this->objRedis->expire( $cache_key , 1800 );
        } catch ( Exception $e ){

        }

        if( $set_status && $expire_status ){
            return true;
        }
        return false;
    }

    public function getQRContentBySceneId ( $sceneId = '' ){
        $cache_key = $this->getCacheKey( $sceneId );
        $cache_content = $this->objRedis->get( $cache_key   );

        file_put_contents("/tmp/wxlog.txt." . date("Ymd") , "getQRContentBySceneId cachekey:" . $cache_key . "\n\n", FILE_APPEND );
        file_put_contents("/tmp/wxlog.txt." . date("Ymd") , "getQRContentBySceneId cachecontent:" . $cache_content . "\n\n", FILE_APPEND );
        return $cache_content;
//return json_decode($cache_content , true );
    }
}
