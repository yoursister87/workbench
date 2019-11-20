<?php


/**
 * rpc请求封装，使用json打包调用后端指定service服务
 *
 * @filesource hk/service/Rpc.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-09-01
 */
class Hk_Service_Rpc {


    private static $rpcConfig = NULL;

    /**
     * 获取指定service对应的配置
     *
     * @param string         $serviceName
     */
    private static function getRpcConfig($serviceName) {
        if (NULL === self::$rpcConfig) {
            $srvConf = Bd_Conf::getConf("/hk/rpc");
            if (empty($srvConf)) {
                return false;
            }
            self::$rpcConfig = $srvConf["rpcService"];
        }
        return isset(self::$rpcConfig[$serviceName]) ? self::$rpcConfig[$serviceName] : false;
    }

    /**
     * 调用rpc service<br>
     * 已经记录调用日志，请不要在前端再次记录rpc相关调用日志
     *
     * @since 2018-09-01 增加请求rpc耗时
     *
     * @param string         $serviceName   后端服务名称
     * @param string         $method        后端服务方法
     * @param array          $input         服务参数列表
     * @param array          $addition      向后端通过httpHeader透传的数据
     * @return mixed:boolean|array
     */
    public static function call($serviceName, $method, array $input = array(), array $addition = array()) {
        $arg  = array(
            "service" => $serviceName,
            "method"  => $method,
            "input"   => @json_encode($input),
        );
        $conf = self::getRpcConfig($serviceName);
        if (false === $conf) {
            Bd_Log::warning("rpc service call failed, {$serviceName} config not exit");
            return false;
        }
        $ralName   = $conf["ralname"];             # service的ral集群名
        $basePath  = $conf["basepath"];            # service的固定uri
        $requestId = self::getRequestId();         # 生成此次请求的唯一requestId，通过get方式提交
        $uriPath   = sprintf("%s/%s", $basePath, $method);
        $header    = self::getAddHeader($addition);
        $header["querystring"] = sprintf("requestId=%s", $requestId);       # 设置GET参数
        ral_set_pathinfo($uriPath);
        ral_set_log(RAL_LOG_LOGID,  Bd_Log::genLogID());
        ral_set_log(RAL_LOG_MODULE, defined("MAIN_APP") ? MAIN_APP : 'unknown');

        $timerKey = "rpc_{$serviceName}_{$method}";
        Hk_Util_Log::start($timerKey);
        $resp     = ral($ralName, 'POST', $input, rand(), $header);
        Hk_Util_Log::stop($timerKey);
        if (false === $resp) {                  # 请求失败
            $arg["errno"]    = ral_get_errno();
            $arg["errmsg"]   = ral_get_error();
            $arg["httpcode"] = ral_get_protocol_code();
            Bd_Log::warning("rpc call failed", $arg["errno"], $arg, 1);
            return false;
        }
        if (!is_array($resp)) {
            $output = $resp;
            $resp   = json_decode($resp, true);
        } else {
            $output = json_encode($resp);
        }
        $arg["output"] = strlen($output) <= 100 ? $output : substr($output, 0, 100) . "...";
        Bd_Log::addNotice($requestId, $arg);        # 请求成功只添加一个requestKey
        return $resp;
    }

    /**
     * 向请求的rpc服务端通过httpHeader透传相关参数
     *
     * @param array    $header
     */
    private static function getAddHeader(array $header = array()) {
        $header["Content-Type"] = "application/json";
        return $header;
    }

    /**
     * 生成唯一的requestId，格式：logid_time
     */
    private static function getRequestId() {
        return sprintf("%s_%d", uniqid("rpc"), time());
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
