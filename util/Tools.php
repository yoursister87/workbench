<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Tools.php
 * @author chuzhenjiang@baidu.com
 * @date 2013-12-18
 * @brief 通用工具
 *
 **/

class Hk_Util_Tools
{
    const TEST_TAG = 'rdqa';
    public static $nmqskip = null;
    public static function isTestRequest(){
	    //内网调试
	    if(Hk_Util_Ip::isInnerIp())
	    {
		    if(trim($_COOKIE['APP_DEBUG']) == strval(date('Y').(date('n') - 1))
				    || trim($_GET['skip']) == self::TEST_TAG || trim($_POST['skip']) == self::TEST_TAG || trim(self::$nmqskip == self::TEST_TAG))
		    {
			Bd_Log::addNotice("rdqa_test_request", 1);
			return true;
		    }
	    }
	    return false;
    }
    /**
     * 获取Spam的附加信息(提交检查用)
     */
    public static function getSpamRequest()
    {
        $result = Saf_SmartMain::getCgi();
        $result['user']   = Saf_SmartMain::getUserInfo();
        $result['cookie'] = $_COOKIE;
        $result['client'] = array (
            'terminal'    => Hk_Util_Client::getTerminal(),
            'from_id'     => Hk_Util_Client::getFromId(),
            'submit_from' => Hk_Util_Client::getSubmitFrom(),
            );
        return $result;
    }


    public static function openSign($qid, $title, $content)
    {
        $qid = $qid == NULL?'':$qid;
        $title = $title == NULL? '':$title;
        $content = $content == NULL? '' :$content;
        $openConf = Bd_Conf::getConf('/cms/napi/open/submit');
        $security_key = $openConf['security_key'];
        $appkey = $openConf['appkey'];
        $str = "$security_key&$appkey&$qid&$title&$content";
        return md5($str);
    }

    /**
     * 校验手机号是否合法
     *
     * @param string        $phone
     * @return boolean
     */
    public static function checkPhoneAvalilable($phone) {
        if (!is_numeric($phone) || $phone < 10000000000 || $phone > 20000000000) {
            return false;
        }

        $phonePre    = substr($phone, 0, 3);
        $phonePreSet = array(        // 以下为分运营商手机号开头规则集合，后续请及时更新，本次更新时间2016.03.21
            // 中国移动
            array('134' => 1, '135' => 1, '136' => 1, '137' => 1, '138' => 1, '139' => 1, '150' => 1, '151' => 1, '152' => 1,
                  '157' => 1, '158' => 1, '159' => 1, '178' => 1, '182' => 1, '183' => 1, '184' => 1, '187' => 1, '188' => 1,
                  '147' => 1, '198' => 1,
            ),
            // 中国联通
            array('130' => 1, '131' => 1, '132' => 1, '155' => 1, '156' => 1, '176' => 1, '185' => 1, '186' => 1, '145' => 1, '166' => 1),
            // 中国电信
            array('133' => 1, '153' => 1, '173' => 1, '177' => 1, '180' => 1, '181' => 1, '189' => 1, '199' => 1),
            // 虚拟运营商
            array('170' => 1, '171' => 1),
            // 直播课主讲账号
            array('116' => 1, '111' => 1),
        );
        foreach ($phonePreSet as $value) {
            if (isset($value[$phonePre])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 校验手机号码是否符合规范
     * @param  int    $phone 手机号码
     * @return bool   true/false
     */
    public static function checkPhoneFormat($phone) {
        if( (0 >= $phone) || (10000000000 >= $phone) || (20000000000 <= $phone) ) {
            return false;
        }
        $phonePrefix = intval(substr($phone, 0, 3));
        //以下为分运营商手机号开头规则，后续请及时更新，本次更新时间2016.03.21
        $arrPhonePrefixs = array(
            'CMCC'    => array(134=>1, 135=>1, 136=>1, 137=>1, 138=>1, 139=>1, 150=>1, 151=>1, 152=>1,157=>1, 158=>1, 159=>1, 178=>1, 182=>1, 183=>1, 184=>1, 187=>1, 188=>1, 147=>1),
            'UNICOM'  => array(130=>1, 131=>1, 132=>1, 155=>1, 156=>1, 176=>1, 185=>1, 186=>1, 145=>1),
            'TELECOM' => array(133=>1, 153=>1, 173=>1, 177=>1, 180=>1, 181=>1, 189=>1),
            'VNO'     => array(170=>1, 171=>1),
        );
        foreach ($arrPhonePrefixs as $ISP => $prefixs) {
            if (isset($prefixs[$phonePrefix])) {
                return true;
            }
        }
        return false;
    }

    /**
     * 检测访问来源移动端
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'ipad',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );

            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * 检测邮箱的合法性
     */
    static public function checkEmailValid($email='')
    {
        if(empty($email)){
            return false;
        }
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(! $email){
            return false;
        }

        return true;
    }



    static public function getbin2hexString($str='')
    {
        if(empty($str)){
            return $str;
        }

        $str =  "unhex('".bin2hex($str)."')";

        return $str;
    }


}
