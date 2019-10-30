<?php
/**
 * Created by PhpStorm.
 * User: lihongyun1
 * Date: 14-9-17
 * Time: 下午2:55
 */
class Gj_Exception extends Exception
{

    //errMsg为用户自定义异常信息
    public function __construct($errno, $errMsg = ''){

        parent::__construct($errMsg, $errno);
        //获得 方法，参数，和上一级深度
        $trace = debug_backtrace();
        $strMethod = isset( $trace[1]['function'] )
            ? $trace[1]['function'] : "";
        $arrArgs = isset( $trace[1]['args'] )
            ? json_encode($trace[1]['args']) : "";
        $depth = 1;
        Gj_Log::warning("args:$arrArgs exception_error_msg[$errMsg]", $errno, null,$depth);
    }
}