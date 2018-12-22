<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Host.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/08/21 11:53:25
 * @brief 域名控制
 *  
 **/
class Hk_Util_Host {
    /**
     * 获取域名
     * @param bool $search 是否检索域名
     * @return string
     */
    public static function getHost($search = false) {
        /*if($search) {
            return 'http://search.zuoye.baidu.com';//已废弃
        }*/
        /*
        //暂停上线pending
        $client = Hk_Util_Client::getVersion();
        if(isset($client['type']) && $client['type'] == 'ios'){
            return 'https://www.zybang.com';
        }*/
        return 'https://www.zybang.com';
    }

    /**
     * push服务内网域名
     */
    public static function getUregisterHost(){
        return 'http://uregister.int.zybang.com';
    }

    public static function getStaticHost(){
         return 'https://yy-s.zuoyebang.cc';
    }

    public static function getReqDomain() {
        $host = $_SERVER["HTTP_HOST"];
        if (false !== strpos($host, ":")) {
            $host  = explode(":", $host)[0];
        }
        if (empty($host)) {
            return false;
        }

        $tmp   = explode(".", $host);
        if (empty($tmp[count($tmp) - 1])) {
            array_pop($tmp);
        }
        $query = implode(".", $tmp);

        # 拼接后缀正则
        $str        = "";
        $topDomains = array("com", "edu", "gov", "int", "mil", "net", "org", "biz", "info", "pro", "name", "museum", "coop", "aero", "xxx", "idv", "mobi", "cc", "me");
        foreach ($topDomains as $v) {
            $str   .= ($str ? "|" : "") . $v;
        }
        $pattens    = sprintf('[^\.]+\.(?:(%s)|\w{2}|((%s)\.\w{2}))$', $str, $str);
        $domain     = preg_match("/" . $pattens . "/ies", $query, $matches) ? $matches[0] : $query; # get domain
        return $domain;
    }
}
