<?php
/**
 * @category    hk
 * @package     service
 * @version     2013/10/16 19:48:32
 * @copyright   Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 * @update 20150527 jiangyingjie@baidu.com
 **/

/**
 * memcached服务接口类
 */
class Hk_Service_Memcached {
    private static $_arrMemcached;

    public static $arrNmqCache;//for cache 双写
    private $_name;     //name
    private $_switch;   //开关
    private $_prefix;   //前缀
    private $_expire;   //失效时间
    private $_instance; //实例名称
    private $_idc;      //机房
    private $_all_idc;  //多机房
    private $_memNoticeLog;
    private $_memWfLog;
    const LOG_RATIO = 1;


    public function __construct($switch, $prefix, $expire, $name, $instance, $idc, $allidc) {
        $this->_switch   = $switch;
        $this->_prefix   = $prefix;
        $this->_expire   = $expire;
        $this->_name     = $name;
        $this->_instance = $instance;
        $this->_idc      = $idc;
        $this->_all_idc  = $allidc; //array
        $this->_memWfLog = true;
        $randId = mt_rand(0,99);
        if($randId <= self::LOG_RATIO){
            $this->_memNoticeLog = true;
        }else{
            $this->_memWfLog = false;
        }
    }

    public static function getInstance($name) {
        if(isset(self::$_arrMemcached[$name])) {
            return self::$_arrMemcached[$name];
        }

        $conf = Bd_Conf::getConf("/hk/memcached/");
        if(empty($conf)) {
            Bd_Log::warning("Error:[get memcached conf failed], Detail:[name:{$name}]");
            return false;
        }

        $switch   = intval($conf[$name]['switch']);
        $prefix   = strval($conf[$name]['prefix']);
        $expire   = intval($conf[$name]['expire']);
        $instance = strval($conf[$name]['instance']);
        $idc      = strval($conf['idcmap']['idc']);
        $allidc   = array();
        $arrIdc   = explode(",", $conf[$name]['relate_idc']['idc']);
        foreach ($arrIdc as $relateIdc) {
            $relateIdc = trim($relateIdc);
            if("" == $relateIdc) {
                Bd_Log::warning("Error:[get memcached conf failed], Detail:[relate_idc has null idc]");
                return false;
            }
            $allidc[] = strval($relateIdc);
        }
        if(empty($allidc)) {
            Bd_Log::warning("Error:[get memcached conf failed], Detail:[relate_idc empty]");
            return false;
        }
        $memcached = new Hk_Service_Memcached($switch, $prefix, $expire, $name, $instance, $idc, $allidc);
        self::$_arrMemcached[$name] = $memcached;

        return $memcached;
    }

    public function set($key, $value, $expire = 0) {
        if($this->_switch !== 1) {
            return false;
        }

        if($expire === 0) {
            $expire = $this->_expire;
        }

        $formatKey = $this->formatKey($key);
        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $ret = $memcached->set($formatKey, $value, $expire);
        if($ret === false){
            //失败加一次重试
            $ret = $memcached->set($formatKey, $value, $expire);
        }
        if(is_string($value)){
            $len = strlen($value);
        }elseif(is_array($value)){
            $len = strlen(json_encode($value));
        }else{
            $len = 0;
        }
        $arrKeyValue = array(
            'key' => $key,
            'len' => $len,
            'expire' => $expire,
        );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if ($ret === false) {
            Hk_Util_Log::incrKey("mem_{$this->_name}_set_fail", 1);
            $this->printMemcacheLog('set', 'warning', $arrKeyValue);
        } else {
            Hk_Util_Log::incrKey("mem_{$this->_name}_set_succ", 1);
            $this->printMemcacheLog('set', 'notice',$arrKeyValue);
        }

        return $ret;
    }

    public function mset($arrData, $expire = 0) {
        if($this->_switch !== 1) {
            return false;
        }

        if($expire === 0) {
            $expire = $this->_expire;
        }

        $arrFormatData = array();
        foreach($arrData as $key => $value) {
            $formatKey = $this->formatKey($key);
            $arrFormatData[$formatKey] = $value;
        }

        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $ret = $memcached->mset($arrFormatData, $expire);
        if(is_string($value)){
            $len = strlen($value);
        }elseif(is_array($value)){
            $len = strlen(json_encode($value));
        }else{
            $len = 0;
        }
        $arrKeyValue = array(
                'key' => $key,
                'len' => $len,
                'expire' => $expire,
                'num' => count($arrFormatData),
                );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if ($ret === false) {
            Hk_Util_Log::incrKey("mem_{$this->_name}_set_fail", count($arrData));
            $this->printMemcacheLog('mset', 'warning', $arrKeyValue);
        } else {
            Hk_Util_Log::incrKey("mem_{$this->_name}_set_succ", count($arrData));
            $this->printMemcacheLog('mset', 'notice', $arrKeyValue);
        }

        return $ret;
    }

    public function get($key) {
        if ($this->_switch !== 1) {
            return false;
        }

        $formatKey = $this->formatKey($key);
        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $ret = $memcached->get($formatKey);
        if(is_string($ret)){
            $len = strlen($ret);
        }elseif(is_array($ret)){
            $len = strlen(json_encode($ret));
        }else{
            $len = 0;
        }
        $arrKeyValue = array(
                'key' => $key,
                'len' => $len,
                );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if ($ret === false) {
            Bd_Log::addNotice("mc_get_" . $key, "miss");
            Hk_Util_Log::incrKey("mem_{$this->_name}_get_miss", 1);
            $this->printMemcacheLog('get', 'warning', $arrKeyValue);
        } else {
            Hk_Util_Log::incrKey("mem_{$this->_name}_get_hit", 1);
            $this->printMemcacheLog('get', 'notice', $arrKeyValue);
        }
        return $ret;
    }

    public function mget($arrKey) {
        if($this->_switch !== 1) {
            return false;
        }

        $arrFormatKey = array();
        $arrKeyMap = array();
        /**
         * @des 统一处理线上报警 start
         * @author jiahaijun<jiahaijun@zuoyebang.com>
         * @time 2018/04/09
         */
        if(is_array($arrKey) && !empty($arrKey)){
            foreach($arrKey as $key) {
                $formatKey = $this->formatKey($key);
                $arrFormatKey[] = $formatKey;
                $arrKeyMap[$formatKey] = $key;
            }
        }
        /**
         * end
         */

        $arrOutput = array();
        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $arrRet = $memcached->mget($arrFormatKey);
        if(!empty($arrRet)) {
            foreach($arrRet as $formatKey => $value) {
                $key = $arrKeyMap[$formatKey];
                $arrOutput[$key] = $value;
            }
        }
        $misCount = count($arrKey) - count($arrOutput);
        $hitCount = count($arrOutput);
        $arrKeyValue = array(
                'key' => $key,
                'num' => count($arrFormatKey),
                'mis' => $misCount,
                'hit' => $hitCount,
                );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if($misCount > 0){
            $this->printMemcacheLog('mget', 'warning', $arrKeyValue);
        }else{
            $this->printMemcacheLog('mget', 'notice', $arrKeyValue);
        }

        Hk_Util_Log::incrKey("mem_{$this->_name}_get_mis", count($arrKey) - count($arrOutput));
        Hk_Util_Log::incrKey("mem_{$this->_name}_get_hit", count($arrOutput));

        return $arrOutput;
    }

    public function delete($key) {
        if($this->_switch !== 1) {
            return false;
        }

        $formatKey = $this->formatKey($key);
        $arrKeyValue = array(
                'key' => $key,
                );
        foreach($this->_all_idc as $idc) {
            $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $idc);
            $ret = $memcached->delete($formatKey);
            $serverIp = $memcached->getServerByKey($key);
            $arrKeyValue['serverIp'] = $serverIp;
            if($ret === false){
                $this->printMemcacheLog('delete', 'warning', $arrKeyValue);
            }else{
                $this->printMemcacheLog('delete', 'notice', $arrKeyValue);
            }
        }

        return true;
    }

    public function mdelete($arrKey) {
        if($this->_switch !== 1) {
            return false;
        }

        foreach($this->_all_idc as $idc) {
            $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $idc);
            $arrFormatKey = array();
            foreach($arrKey as $key) {
                $arrFormatKey[] = $this->formatKey($key);
            }

            $ret = $memcached->mdelete($arrFormatKey);
        }

        return true;
    }

    public function increment($key, $value) {
        if ($this->_switch !== 1) {
            return false;
        }

        $formatKey = $this->formatKey($key);
        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $ret = $memcached->increment($formatKey, $value);
        $arrKeyValue = array(
                'key' => $key,
                'value' => $ret,
                );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if ($ret === false) {
            Bd_Log::addNotice("mc_incr_" . $key, "fail");
            Hk_Util_Log::incrKey("mem_{$this->_name}_incr_fail", 1);
            $this->printMemcacheLog('incr', 'warning', $arrKeyValue);
        } else {
            Hk_Util_Log::incrKey("mem_{$this->_name}_incr_suc", 1);
            $this->printMemcacheLog('incr', 'notice', $arrKeyValue);
        }
        return $ret;
    }

    public function decrement($key, $value) {
        if ($this->_switch !== 1) {
            return false;
        }

        $formatKey = $this->formatKey($key);
        $memcached = Hk_Service_MemcachedClient::getInstance($this->_instance, $this->_idc);
        $ret = $memcached->decrement($formatKey, $value);
        $arrKeyValue = array(
                'key' => $key,
                'value' => $ret,
                );
        $serverIp = $memcached->getServerByKey($key);
        $arrKeyValue['serverIp'] = $serverIp;
        if ($ret === false) {
            Bd_Log::addNotice("mc_decr_" . $key, "fail");
            Hk_Util_Log::incrKey("mem_{$this->_name}_decr_fail", 1);
            $this->printMemcacheLog('incr', 'warning', $arrKeyValue);
        } else {
            Hk_Util_Log::incrKey("mem_{$this->_name}_decr_suc", 1);
            $this->printMemcacheLog('incr', 'notice', $arrKeyValue);
        }
        return $ret;
    }

    private function formatKey($key) {
        return md5("{$this->_prefix}{$key}");
    }

    private function printMemcacheLog($action, $logLevel, $arrKeyValue){
        if($logLevel == 'notice' && $this->_memNoticeLog == false){
            return true;
        }
        if($logLevel == 'warning' && $this->_memWfLog == false){
            return true;
        }
        Bd_AppEnv::setCurrApp('memcache');
        Bd_Log::addNotice('local_ip', Bd_Ip::getLocalIp());
        Bd_Log::addNotice('instance', $this->_instance);
        Bd_Log::addNotice('name', $this->_name);
        Bd_Log::addNotice('idc', $this->_idc);
        Bd_Log::addNotice('action', $action);
        if(is_array($arrKeyValue)){
            foreach($arrKeyValue as $key => $value){
                Bd_Log::addNotice($key, $value);
            }
        }else{
                Bd_Log::addNotice($key, $arrKeyValue);
        }
        switch($logLevel){
            case 'notice':
                Bd_Log::notice($action." succ");
                break;
            case 'warning':
                Bd_Log::warning($action." fail");
                break;
            default:
                break;
        }
        Bd_AppEnv::setCurrApp();
    }

    //更新set多机房，set本地memcache，然后发nmq命令, 去更新异地机房
    //params: delay 表示是否延迟发送nmq
    public function setMultiIDC($key, $value, $expire = 0, $delay = false) {
        if($expire === 0) {
            $expire = $this->_expire;
        }
        $res = $this->set($key, $value, $expire);
        if($res !== false){
            $arrCommand = array(
                    'command_no' => Hk_Const_Command::CMD_DOUBLE_SET_CACHE,
                    'key'        => $key,
                    'value'      => $value,
                    'expire'     => $expire,
                    'memcached'  => $this->_name,//memcache实例名称
                    'idc'        => $this->_idc,//本地idc机房
                    );
            //判断action是不是picsearch的请求
            if(MAIN_APP == 'search' && Bd_AppEnv::getCurrAction() == 'picsearch' && Bd_AppEnv::getCurrCtl() == 'submit'){
                self::$arrNmqCache[Hk_Const_Command::CMD_DOUBLE_SET_CACHE][] = $arrCommand;
            }else{
                $objNmq = new Hk_Service_Nmq();
                $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_SET_CACHE, $arrCommand);
                if($nmqRes === false){
                    Bd_Log::warning('setMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                }
            }
        }
        return $res;
    }
    //更新mset多机房，mset本地memcache，然后发nmq命令, 去更新异地机房
    public function msetMultiIDC($arrData, $expire = 0) {
        if($expire === 0) {
            $expire = $this->_expire;
        }
        $res = $this->mset($arrData, $expire);
        if($res !== false){
            $arrCommand = array(
                    'command_no' => Hk_Const_Command::CMD_DOUBLE_MSET_CACHE,
                    'data'       => $arrData,
                    'expire'     => $expire,
                    'memcached'  => $this->_name,//memcache实例名称
                    'idc'        => $this->_idc,//本地idc机房
                    );
            $objNmq = new Hk_Service_Nmq();
            $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_MSET_CACHE, $arrCommand);
            if($nmqRes === false){
                Bd_Log::warning('msetMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                }
            }
            return $res;
        }
        //删除多机房指定key，先删除本地机房，然后后nmq命令，去删除异地机房
        public function deleteMultiIDC($key){
            $res = $this->delete($key);
            if($res !== false){
                $arrCommand = array(
                        'command_no' => Hk_Const_Command::CMD_DOUBLE_DELETE_CACHE,
                        'key'        => $key,
                        'memcached'  => $this->_name,
                        'idc'        => $this->_idc,
                        );
                $objNmq = new Hk_Service_Nmq();
                $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_DELETE_CACHE, $arrCommand);
                if($nmqRes === false){
                    Bd_Log::warning('deleteMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                    }
                }
                return $res;
            }
            //mdelete 多机房，先删除本地机房，然后发nmq命令，去删除异地机房
            public function mdeleteMultiIDC($arrKeys){
                $res = $this->mdelete($arrKeys);
                if($res !== false){
                    $arrCommand = array(
                            'command_no' => Hk_Const_Command::CMD_DOUBLE_MDELETE_CACHE,
                            'keys'       => $arrKeys,
                            'memcached'  => $this->_name,
                            'idc'        => $this->_idc,
                            );
                    $objNmq = new Hk_Service_Nmq();
                    $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_MDELETE_CACHE, $arrCommand);
                    if($nmqRes === false){
                        Bd_Log::warning('mdeleteMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                        }
                    }
                    return $res;
                }

                public function incrementMultiIDC($key, $value){
                    $res = $this->increment($key, $value);
                    if($res !== false){
                        $arrCommand = array(
                                'command_no' => Hk_Const_Command::CMD_DOUBLE_INCR_CACHE,
                                'key'        => $key,
                                'value'      => $value,
                                'memcached'  => $this->_name,
                                'idc'        => $this->_idc,
                                );
                        $objNmq = new Hk_Service_Nmq();
                        $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_INCR_CACHE, $arrCommand);
                        if($nmqRes === false){
                            Bd_Log::warning('incrementMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
                            }
                        }
                        return $res;
                    }
                    public function decrementMultiIDC($key, $value){
                        $res = $this->decrement($key, $value);
                        if($res !== false){
                            $arrCommand = array(
                                    'command_no' => Hk_Const_Command::CMD_DOUBLE_DECR_CACHE,
                                    'key'        => $key,
                                    'value'      => $value,
                                    'memcached'  => $this->_name,
                                    'idc'        => $this->_idc,
                                    );
                            $objNmq = new Hk_Service_Nmq();
                            $nmqRes = $objNmq->talkToQcm(Hk_Const_Command::CMD_DOUBLE_DECR_CACHE, $arrCommand);
            if($nmqRes === false){
                Bd_Log::warning('decrementMultiIDC failed while talkToQcm command:{'.json_encode($arrCommand)."}");
            }
        }
        return $res;
    }
}


/**
 * memcache的客户端, 提供基础的增删改查操作等
 */
class Hk_Service_MemcachedClient {

    private static $_arrMemcached;
    private $_mmc;

    public function __construct($arrServers, $ins = null) {
        $this->_mmc = new memcached();
        $this->_mmc->setOptions(array(
            Memcached::OPT_CONNECT_TIMEOUT => 100, //毫秒
            //一致性hash算法
            Memcached::OPT_DISTRIBUTION => Memcached::DISTRIBUTION_CONSISTENT,
            Memcached::OPT_TCP_NODELAY => TRUE,
            //分布式服务分散
            Memcached::OPT_LIBKETAMA_COMPATIBLE => TRUE,
            //异步IO
            Memcached::OPT_NO_BLOCK => TRUE,
            //failed over策略
            Memcached::OPT_RETRY_TIMEOUT => 2,//当一个节点失败并且被remove后，重试链接的时间，秒
            Memcached::OPT_SERVER_FAILURE_LIMIT => 5,
            Memcached::OPT_REMOVE_FAILED_SERVERS => TRUE,
            # 使用压缩
            Memcached::OPT_COMPRESSION => TRUE,
        ));

        $arrFormatServers = array();
        foreach($arrServers as $server) {
            if(empty($server)) {
                continue;
            }

            $arrFormatServers[] = array(
                $server['host'],
                $server['port'],
                $server['weight'],
            );
        }

        $this->_mmc->addServers(
            $arrFormatServers
        );
    }

    public static function getInstance($instance, $idc){
        $ins = "{$instance}_{$idc}";
        if(isset(self::$_arrMemcached[$ins])) {
            return self::$_arrMemcached[$ins];
        }

        $serverConf = Bd_Conf::getConf("/memcachedclient/{$instance}/server", $idc);
        if(empty($serverConf)) {
            Bd_Log::warning("Error:[get memcached conf failed], Detail:[instance:{$instance} idc:{$idc}]");
            return false;
        }

        $memcached = new Hk_Service_MemcachedClient($serverConf, $ins);
        self::$_arrMemcached[$ins] = $memcached;
        return $memcached;
    }

    public function set($key, $value, $expire = 0) {
        return $this->_mmc->set($key, $value, $expire);
    }

    public function mset($arrData, $expire = 0) {
        return $this->_mmc->setMulti($arrData, $expire);
    }

    public function get($key) {
        return $this->_mmc->get($key);
    }

    public function mget($arrKeys) {
        return $this->_mmc->getMulti($arrKeys);
    }

    public function delete($key) {
        return $this->_mmc->delete($key);
    }

    public function mdelete($arrKeys){
        return $this->_mmc->deleteMulti($arrKeys);
    }

    public function flush() {
        return $this->_mmc->flush();
    }

    public function getStats() {
        return $this->_mmc->getStats();
    }

    public function getServerByKey($key) {
        return $this->_mmc->getServerByKey($key);
    }

    public function increment($key, $value) {
        return $this->_mmc->increment($key, $value);
    }

    public function decrement($key, $value) {
        return $this->_mmc->decrement($key, $value);
    }

    //对象销毁时释放memcache连接
    public function __destruct() {
        $this->_mmc->quit();
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
