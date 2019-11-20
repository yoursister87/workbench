<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file CUGMap.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/04/13 21:07:18
 * @brief 用户-年级信息查询
 * @note 用户-年级信息由检索统计数据生成，1个月更新一次（定时任务importCUG.php）
 *  
 **/

class Hk_Service_CUGMap {

    //缓存
    private static $cacheData = array();
    private static function setLocalCache($key, $data) {
        if(count(self::$cacheData) < 100) {
            self::$cacheData[$key] = $data;
        }
    }
    /**
     * 根据uid获取用户对应的年级
     *
     * @param  int $uid
     * @return mix 年级
     */
    public static function getCUGMapByUid($uid) {
        if(intval($uid) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[uid:$uid]");
            return false;
        }
        if(isset(self::$cacheData[$uid])){
            return self::$cacheData[$uid];
        }
        $arrOutput = array();
        $objMemcached = Hk_Service_Memcached::getInstance("zhiboke");
        $cacheKey = 'CUGMap_' . $uid;
        $strValue = $objMemcached->get($cacheKey);
        if(!empty($strValue)) {
            $arrValue = json_decode($strValue, true);
            Hk_Util_Log::setLog('CUGMapUid', $strValue);
            Hk_Util_Log::setLog('CUGMapUidCached', 1);
            self::setLocalCache($uid, $arrValue);
            return $arrValue;
        }

        $objUcloud = new Hk_Ds_User_Ucloud();
        $userInfo = $objUcloud->getUserInfo($uid);
        if(empty($userInfo)) {
            self::setLocalCache($uid, $arrOutput);
            return $arrOutput;
        }

        $grade = intval($userInfo['grade']);
        if($grade === 255) {
            self::setLocalCache($uid, $arrOutput);
            return $arrOutput;
        }

        $arrOutput = array(
            'gradeId'  => $grade,  
        );

        $strValue = json_encode($arrOutput);
        $objMemcached->set($cacheKey, $strValue, 2592000);
        
        Hk_Util_Log::setLog('CUGMapUid', $strValue);
        Hk_Util_Log::setLog('CUGMapUidCached', 0);

        self::setLocalCache($uid, $arrOutput);
        return $arrOutput;
    }

    /**
     * 根据cuid获取用户对应的年级
     *
     * @param  string $cuid
     * @return mix 年级
     */
    public static function getCUGMapByCuid($cuid) {
        $arrOutput = array();
        if(strlen(trim($cuid)) <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[cuid:$cuid]");
            return false;
        }
        if(isset(self::$cacheData[$cuid])){
            return self::$cacheData[$cuid];
        }
        
        
        $objMemcached = Hk_Service_Memcached::getInstance("zhiboke");
        $cacheKey = 'CUGMap_' . $cuid;
        $strValue = $objMemcached->get($cacheKey);
        if(!empty($strValue)) {
            $arrValue = json_decode($strValue, true);
            Hk_Util_Log::setLog('CUGMapCuid', $strValue);
            Hk_Util_Log::setLog('CUGMapCuidCached', 1);
            self::setLocalCache($cuid, $arrValue);
            return $arrValue;
        }

        Hk_Util_Log::setLog('CUGMapCuidCached', 0);

        self::setLocalCache($cuid, $arrOutput);
        return $arrOutput;
    }
}
