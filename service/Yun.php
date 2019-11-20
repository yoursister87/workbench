<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file Yun.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/09/22 11:39:55
 * @brief 通过云穿透的服务
 *  
 **/

class Hk_Service_Yun {

    /**
     * 检索穿透到内网
     *
     * @param  string $strWords 检索query
     * @return mix 搜索结果
     */
    /*
    public static function rcs($strWords, $sid, $isSmall, $limit, $offset) {
        $r = rand(0, 100);
        if($r > 100){
            $service = 'yun-http';
            $pathinfo = '/search/api/searchproxy';
        }else{
            $service = 'rcs-http';
            $pathinfo = '/semerge/query';
        }
        if(empty($strWords)) {
            Bd_Log::warning("Error:[param error], Detail:[strWords:$strWords]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => $pathinfo,
        );
        $arrParams = array(
            'strWords' => $strWords,
            'sid'      => $sid,
            'isSmall'  => $isSmall,
            'limit'    => $limit, 
            'offset'   => $offset,
            'logid'    => Bd_Log::genLogID(),
        );
        $ret = ral($service, 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service $service connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        $arrResult = json_decode($ret, true);
        $errno     = intval($arrResult['errNo']);
        $errmsg    = $arrResult['errMsg'];
        if(intval($errno) !== 0) {
            Bd_Log::warning("Error:[service $service rcs process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return $arrResult['data'];
    }*/
    /**
     * 从检索系统中查找相似的问题
     * @param strWords string 检索字符串
     * @param sid int 检索sid
     * @param isSmall int 是否小流量标识
     * @param weight int 固定15
     * @param noMap bool 是否出结构化 0 出， 1不出
     * @param limit int
     * @param imageWidth int 默认值0
     * @parsm imageHeight int 默认值0
     * @return array 检索结果
     **/
    public static function rcsnew($strWords, $sid, $isSmall, $limit, $offset, $weight = 10, $mapType = 0, $imageWidth = 0, $imageHeight = 0) {
        $service = 'rcsnew-http';
        $pathinfo = '/seproxy/query';
        if(empty($strWords)) {
            Bd_Log::warning("Error:[param error], Detail:[strWords:$strWords]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => $pathinfo,
        );
        $arrTerminal = Hk_Util_Client::getTerminal();
        $arrParams = array(
            'strWords' => $strWords,
            'sid'      => $sid,
            'isSmall'  => $isSmall,
            'limit'    => $limit,
            'offset'   => $offset,
            'logid'    => Bd_Log::genLogID(),
            "ip"       => Hk_Util_Ip::getClientIp(),
            "cuid"     => $arrTerminal['terminal'],
            "imageWidth"  => $imageWidth,
            "imageHeight" => $imageHeight,
        );
        if ($mapType != 0) {
            $arrParams['mapType'] = $mapType;
        }
        if ($weight != 10) {
            $arrParams['weight'] = $weight;
        }
        $ret = ral($service, 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service $service connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        $arrResult = json_decode($ret, true);
        $errno     = intval($arrResult['errNo']);
        $errmsg    = $arrResult['errMsg'];
        if(intval($errno) !== 0) {
            Bd_Log::warning("Error:[service $service rcs process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return $arrResult['data'];
    }

    /**
     * redis配置的id仍穿透到内网分配
     *
     * @param  string $name 
     * @return int id
     */
    public static function idalloc($name) {
        if(empty($name)) {
            Bd_Log::warning("Error:[param error], Detail:[name:$name]");
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                return true;
        }

        $arrHeader = array(
            'pathinfo' => '/napi/api/getidalloc',
        );
        $arrParams = array(
            'name' => $name,
        );
        $ret = ral('yun-http', 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service yun-http connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        $arrResult = json_decode($ret, true);
        $errno     = intval($arrResult['errNo']);
        $errmsg    = $arrResult['errMsg'];
        if(intval($errno) !== 0) {
            Bd_Log::warning("Error:[service yun-http-idalloc process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return $arrResult['data'];
    }
}
