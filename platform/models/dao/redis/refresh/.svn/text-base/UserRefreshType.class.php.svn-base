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
class Dao_Redis_Refresh_UserRefreshType{
    /**
     * @var RedisClient
     */
    private $objRedis;
    /**
     * @var string key前缀
     */
    protected $strPrefix    = "z-user-refresh-type-";
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

    protected $strSearchDate;

    /**
     * @param $intUserId
     * @param $intBizScope
     * @param int $intDateOffset
     */
    public function __construct($intDateOffset){
        $objRedisUtil = new Gj_Util_RedisClient(RedisConfig::$HousePremierRefreshRedisPool);
        $this->objRedis = $objRedisUtil->getMasterRedis();
        $time = mktime(0, 0, 0, date('m')  , date('d') + $intDateOffset, date('Y'));
        $date = date('Ymd', $time);
        $this->strSearchDate = $date;


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
    public function getRefreshTotalNum($intAccountId,$arrType){
        if(!is_array($arrType)){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"house type  error");
        }
        $intRet = 0;
        foreach($arrType as $intHouseType){
            $this->strKey = $this->strPrefix.$intAccountId.'-'.$intHouseType.'-'.$this->strSearchDate;
            $intRes =  $this->objRedis->zCard($this->strKey);
            if($intRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"redis zcount failed");
            }
            $intRet += $intRes;
        }
        return $intRet;
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
     * @param $intAccountId
     * @param $arrType
     * @return int
     * @throws Gj_Exception
     */
    public function getDoneRefreshNum($intAccountId,$arrType){
        if(!is_array($arrType)){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"house type  error");
        }
        $intRet = 0;
        foreach($arrType as $intHouseType){
            $this->strKey = $this->strPrefix.$intAccountId.'-'.$intHouseType.'-'.$this->strSearchDate;
            $intRes =  $this->objRedis->zCount($this->strKey,0,time());
            if($intRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"redis zcount failed");
            }
            $intRet += $intRes;
        }
        return $intRet;
    }

    public function getAllRefreshByType($intAccountId,$arrType){
        if(!is_array($arrType)){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,"house type  error");
        }
        $arrRet = array();
        foreach($arrType as $intHouseType){
            $this->strKey = $this->strPrefix.$intAccountId.'-'.$intHouseType.'-'.$this->strSearchDate;
            $arrRes =  $this->objRedis->zRange($this->strKey,0,-1);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,"redis zcount failed");
            }
            $arrRet =  array_merge($arrRet,$arrRes);
        }
        return $arrRet;
    }

}