<?php
/**
 * @package              
 * @subpackage           
 * @brief            存储用户刷新的房源和时间点
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Redis_Refresh_UserRefreshBizScope{
    /**
     * @var RedisClient
     */
    private $objRedis;
    /**
     * @var string key前缀
     */
    protected $strPrefix    = "z-user-refresh-bizscope-";
    /**
     * @var string key后缀
     */
    protected $strSuffix    = "";
    /**
     * @var int 过期倒计时
     */
    protected $intExpireTime = 259200;
    /**
     * @var string 真正的key
     */
    protected $strKey = "";

    /**
     * @param $intUserId
     * @param $intBizScope
     * @param int $intDateOffset
     */
    public function __construct($arrParams){
        $objRedisUtil = new Gj_Util_RedisClient(RedisConfig::$HousePremierRefreshRedisPool);
        $this->objRedis = $objRedisUtil->getMasterRedis();
        $time = mktime(0, 0, 0, date('m')  , date('d') + $arrParams['date_offset'], date('Y'));
        $date = date('Ymd', $time);
        $this->strKey = $this->strPrefix.$arrParams['account_id'].'-'.$arrParams['biz_scope'].'-'.$date;

    }


    /**
     * 获取member
     * @param $intRefreshTime
     * @param $intHouseId
     * @param $intType
     * @return string
     */
    protected  function getMember($intRefreshTime,$intHouseId,$intType){
        return $intRefreshTime.'_'.$intHouseId.'_'.$intType;

    }

    /**
     * 获取指定天的刷新总数
     * @return mixed
     */
    public function getRefreshTotalNum(){
        return $this->objRedis->zCard($this->strKey);
    }

    /**
     * 添加一个刷新
     * @param $intRefreshTime
     * @param $intHouseId
     * @param $intType
     * @throws Gj_Exception
     */
    public function addRefresh($intRefreshTime,$intHouseId,$intType){
        $ret =  $this->objRedis->zAdd($this->strKey,$intRefreshTime,$this->getMember($intRefreshTime,$intHouseId,$intType));
        if($ret ==false){
            throw new Gj_Exception("redis zadd failed");
        }
        $this->objRedis->expire($this->strKey,$this->intExpireTime);
        return true;
    }

    /**
     * 移除一个刷新
     * @param $intRefreshTime
     * @param $intHouseId
     * @param $intType
     * @return bool
     * @throws Gj_Exception
     */
    public function removeRefresh($intRefreshTime,$intHouseId,$intType){

        $ret =  $this->objRedis->zRem($this->strKey,$this->getMember($intRefreshTime,$intHouseId,$intType));
        if($ret ===false){
            throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"redis zadd failed");
        }
        return true;
    }

    /**
     * 获取已经完成的刷新数
     * @throws Gj_Exception
     */
    public function getDoneRefreshNum(){

        $ret =  $this->objRedis->zCount($this->strKey,0,time());
        if($ret ===false){
            throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"redis zcount failed");
        }
        return $ret;
    }

}