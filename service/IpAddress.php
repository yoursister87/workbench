<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file IpAddress.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/04/13 21:07:18
 * @brief 地区查询服务
 * @note 地理数据预先导入（需定期更新）
 *  
 **/

class Hk_Service_IpAddressDao extends Hk_Common_BaseDao {
    public function __construct() {
        $this->_dbName = '/fudao/zyb_fudao';
        $this->_db     = Hk_Service_Db::getDB($this->_dbName);
        $this->_table  = "tblIpAddress";
        $this->arrFieldsMap = array(
            'ipstart'    => 'ipstart',
            'ipstop'     => 'ipstop',
            'country'    => 'country',
            'province'   => 'province',
            'city'       => 'city',
            'county'     => 'county',
            'createTime' => 'create_time',
        );

        $this->arrTypesMap = array(
            'createTime' => Hk_Service_Db::TYPE_INT,    
        );
    }
}

class Hk_Service_IpAddress {
    /**
     * 根据uip获取用户所在地理位置
     *
     * @param  string $ip ip地址
     * @return mix 地理位置
     */
    public static function getAddressByIp($ip = CLIENT_IP) {
        if(empty($ip)) {
            Bd_Log::warning("Error:[param error], Detail:[ip:$ip]");
            return false;
        }
        
        $objMemcached = Hk_Service_Memcached::getInstance("common");
        $cacheKey = 'IpAddress_' . $ip;
        $strValue = $objMemcached->get($cacheKey);
        if(!empty($strValue)) {
            $arrValue = json_decode($strValue, true);
            Hk_Util_Log::setLog('IpAddress', $strValue);
            Hk_Util_Log::setLog('IpAddressCached', 1);
            return $arrValue;
        }

        $objDaoIpAddress = new Hk_Service_IpAddressDao();
        $binIp = bin2hex($ip);
        $sql = "select max(ipstart) as maxIp from tblIpAddress where ipstart <= unhex('$binIp')";
        $ret = $objDaoIpAddress->query($sql);
        if(empty($ret)) {
            return false;
        }

        $maxIp = strval($ret[0]['maxIp']);
        $binMaxIp = bin2hex($maxIp);
        $sql = "select country,province,city,county from tblIpAddress where ipstart = unhex('$binMaxIp')";
        $ret = $objDaoIpAddress->query($sql);
        if(empty($ret)) {
            return false;
        }
        
        $arrOutput = array(
            'country'  => strval($ret[0]['country']),    
            'province' => strval($ret[0]['province']),    
            'city'     => strval($ret[0]['city']),    
            'county'   => strval($ret[0]['county']),    
        );

        $strValue = json_encode($arrOutput);
        $objMemcached->set($cacheKey, $strValue, 864000);
        
        Hk_Util_Log::setLog('IpAddress', $strValue);
        Hk_Util_Log::setLog('IpAddressCached', 0);

        return $arrOutput;
    }
}
