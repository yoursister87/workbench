<?php
/**
 * @category    library
 * @package     napi
 * @author      com<jiangyingjie@baidu.com>
 * @version     2014-12-1 17:40:55
 * @copyright   Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 **/

/**
 * 用于NMQ命令去重
 *
 * 注意：该服务主要依赖一个redis标记命令的doing和done状态。代码中对redis读写失败不做异常处理
 * 是因为该服务并非严格需要保持状态的一致性，仅应对小概率NMQ命令重复情况，大部分情况下，没有
 * 该服务命令也能正常处理。但若抛异常，在redis服务故障的情况下，提交会立刻瘫痪。从系统角度看
 * 我们不希望一个没那么重要且非必须的服务出问题时会直接拖垮提交。因此，这里redis交互异常时打
 * 日志但不抛异常重试命令。
 */
class Hk_Util_TransIdMutex {

    //命令处理状态标志位
    const STATUS_ALL_OK              = 0;   //命令正常处理成功
    const STATUS_OK_TRANSID_DUP      = 1;   //命令已处理，重复命令丢弃
    const STATUS_ERROR_TRANSID_DOING = 2;   //命令处理中，需稍后重试
    const STATUS_ERROR_PROGRAM       = 3;   //命令处理失败（业务逻辑错误），需立即重试

    //去重配置
    const KEY_PATTERN       = "module_%s_transid_%d_clientIp_%s_stage_%s";
    const STATUS_DOING      = "doing";
    const STATUS_DONE       = "done";
    const EXPIRE_TIME_DOING = 120;
    const EXPIRE_TIME_DONE  = 10800;

    public  $flag;          //状态码
    private $_objCache;   //去重cache连接
    private $_doing_key;    //标记命令正在处理的key名
    private $_done_key;     //标记命令已经处理完毕的key名

    public function __construct($arrParams) {
        $transId    = intval($arrParams['transId']);
        $moduleName = $arrParams['moduleName'];
        $clientIp = Bd_Ip::getClientIp();
        if ($transId===0 ) {
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, '', array(
                'transId' => $transId,
            ));
        }
		//命令去重的redis分机房配置
        /*
        $strConfPath = "hk/redis/cache";
        $arrConf = Bd_Conf::getConf($strConfPath);
        if (empty($arrConf)) {
            Bd_Log::warning("Error[Conf] Abstract[getConfFail] Detail[get conf:{$strConfPath} failed]");
        }
        $this->_redis_conn = new Hk_Service_Redis($arrConf);
        */
        $this->_objCache = Hk_Service_Memcached::getInstance('nmq');

        $this->flag        = self::STATUS_ALL_OK;
        $this->_doing_key  = sprintf(self::KEY_PATTERN, $moduleName, $transId, $clientIp, self::STATUS_DOING);
        $this->_done_key   = sprintf(self::KEY_PATTERN, $moduleName, $transId, $clientIp, self::STATUS_DONE);

        $status = $this->_getStatus();
        switch ($status) {
            case self::STATUS_DOING:
                $this->flag = self::STATUS_ERROR_TRANSID_DOING;
                break;
            case self::STATUS_DONE:
                $this->flag = self::STATUS_OK_TRANSID_DUP;
                break;
            default:
                $this->_setStatus($this->_doing_key, self::STATUS_DOING, self::EXPIRE_TIME_DOING);
                $this->flag = self::STATUS_ALL_OK;
                break;
        }
        Bd_Log::addNotice("NmqMutexStatus", $status);
    }

    private function _getStatus() {
        $ret = $this->_objCache->get($this->_done_key);
        if(!empty($ret)){
            return self::STATUS_DONE;
        }

        $ret = $this->_objCache->get($this->_doing_key);
        if(!empty($ret)){
            return self::STATUS_DOING;
        }

        return "ok";
    }

    private function _setStatus($key, $value, $expired) {
        $ret = $this->_objCache->set($key, $value, $expired);
        if(!$ret) {
            Bd_Log::warning("Error:[set memcached failed], Detail:[key:$key, value: $value]");
        }
        return $ret;
    }

    private function _delStatus($key) {
        $ret = $this->_objCache->delete($key);
        if(!$ret) {
            Bd_Log::warning("Error:[del memcached failed], Detail:[key:$key]");
        }
        return $ret;
    }

    public function unlock() {
        Bd_Log::addNotice("NmqMutexFlag", $this->flag);
        switch($this->flag) {
            case self::STATUS_ALL_OK:
                $this->_setStatus($this->_done_key, self::STATUS_DONE, self::EXPIRE_TIME_DONE);
                break;
            case self::STATUS_ERROR_PROGRAM:
                $this->_delStatus($this->_doing_key);
                header("HTTP/1.1 500 Internal Error");
                break;
            case self::STATUS_ERROR_TRANSID_DOING:
                usleep(1000000);
                header("HTTP/1.1 500 Internal Error");
                break;
            case self::STATUS_OK_TRANSID_DUP:
                break;
            default:
                break;
        }
    }

    public function setError() {
        $this->flag = self::STATUS_ERROR_PROGRAM;
    }
}
