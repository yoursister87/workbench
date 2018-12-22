<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Redirect.php
 * @author luhaixia(com@baidu.com)
 * @date 2015/03/09 15:27:51
 * @brief 
 *  
 **/

class Hk_Util_Redirect{
	/**
	 * 重定向
     *
	 * @param  string  $strUrl url
	 * @param  int $intStatusCode httpcode
	 * @param  bool $bolExit
	 * @return bool true/false
	 */
    static public function redirect($strUrl = '', $intStatusCode = 302, $bolExit = true){
        if(empty($strUrl)) {
            $zuoyeHost = Hk_Util_Host::getHost();
            $strUrl = $zuoyeHost . '/';
        }

        //服务器返回状态码
        switch($intStatusCode){
        case 200:
            echo "<meta http-equiv='Pragma' content='no-cache'>".
                "<meta http-equiv='Refresh' content='0;URL=".$strUrl."'>";
            break;
        case 301:
            header('HTTP/1.1 301 Moved Permanently');
        case 302:
            header('Location: '.$strUrl);
            break;
        case 404:
            header('HTTP/1.1 404 Not Found');
            break;
        default:
            header('Location: '.$strUrl);
            break;
        }
        if($bolExit){
            //是否直接退出
            exit(0);
        }
        return true;
    }
}
