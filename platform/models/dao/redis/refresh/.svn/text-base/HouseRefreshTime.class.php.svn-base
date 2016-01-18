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
class Dao_Redis_Refresh_UserRefreshTime{
    /**
     * @var RedisClient
     */
    private $redis;
    /**
     * @var string key前缀
     */
    protected $strPrefix    = "s-refresh-";
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
     * @param $intHouseId
     * @param $intHouseType
     * @param int $intDateOffset
     */
    public function __construct($intUserId,$intHouseId,$intHouseType,$intDateOffset = 0){

        $this->redis = new RedisClient(RedisConfig::$HousePremierRefreshRedisPool);
        $time = mktime(0, 0, 0, date('m')  , date('d') + $intDateOffset, date('Y'));
        $date = date('Ymd', $time);
        $this->strKey = $this->strPrefix.$intUserId.'-'.$intHouseId.'-'.$intHouseType.'-'.$date;
    }



}