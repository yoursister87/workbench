<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Adapt.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/09/09 14:21:53
 * @brief wise适配服务
 *  
 **/

class Hk_Service_Adapt {
	/**
	 * wise适配服务
     *
	 * @return mix 适配信息
	 */
    public static function getAdaptInfo() {
        $arrHeader = array(
            'provider'             => 'zuoyebang',    
        );
        $arrParams = array(
            'HTTP_CLIENTIP'        => $_SERVER['HTTP_CLIENTIP'],
            'HTTP_USER_AGENT'      => $_SERVER['HTTP_USER_AGENT'],
            'HTTP_COOKIE'          => $_SERVER['HTTP_COOKIE'],
            'HTTP_ACCEPT'          => $_SERVER['HTTP_ACCEPT'],
            'HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
            'HTTP_ACCEPT_ENCODING' => $_SERVER['HTTP_ACCEPT_ENCODING'],
            'HTTP_ACCEPT_CHARSET'  => $_SERVER['HTTP_ACCEPT_CHARSET'],
            'HTTP_CONNECTION'      => $_SERVER['HTTP_CONNECTION'],
            'provider'             => 'zuoyebang',
        );

        $ret = ral('wise', 'getAdaptInfo', $arrParams, 123, $arrHeader);
        if(empty($ret)) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service wise connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        return $ret;
    }
}
