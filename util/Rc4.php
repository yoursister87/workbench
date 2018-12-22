<?php

/* * *************************************************************************
 *
 * Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 *
 * ************************************************************************ */

/**
 * @file Rc4.php
 * @author changwei01@baidu.com
 * @date 2015-4-13
 * @brief rc4加密
 * */
class Hk_Util_Rc4 {

    static private $key;

    static public function rc4Encode($data) {
        $key = self::getKey();
        $ret = self::rc4($key, $data);
        return base64_encode($ret);
    }

    public static function rc4Decode($data) {
        $key = self::getKey();
        return self::rc4($key, base64_decode($data));
    }

    static public function rc4ArrayEncode($arr) {
        $ret = array();
        $key = self::getKey();
        if ($arr) {
            foreach ($arr as $v) {
                $ret[] = base64_encode(self::rc4($key, $v));
            }
        }
        return $ret;
    }

    /**
     * 获取加密key，同token有关系
     *
     * 2017-09-25 优化逻辑
     *
     * @return string
     */
    static private function getKey() {
        if (self::$key) {
            $strRc4Key = self::$key;
        } else {
            $appid   = $_REQUEST['appid'];
            $appid   = empty($appid) ? $_REQUEST['appId'] : $appid;
            $orgcuid = $_REQUEST['cuid'];                   # 原始cuid

            # 由于新增各种app，cuid会带产品线appId_cuid格式
            if (!empty($appid) && $appid != 'homework') {
                $cuid = $appid . '_'. $orgcuid;
            } else {
                $cuid = $orgcuid;
            }

            $strKey1 = "@#AIjd83#@6B";
            $strKey2 = $_REQUEST['vc'];
            $conf    = Bd_Conf::getConf('/hk/antispam/common');
            $antiSpamDs = Hk_Ds_User_AntiSpamRegister::getInstance();
            $appVersion = Hk_Util_Client::getVersion();
            if ($appVersion['type'] == 'ios') {               # 2017-09-25 直接去掉vc判断，并兼容以前的逻辑
                $devInfo   = $antiSpamDs->getInfoByDeviceId($cuid);

                $randomKey = $devInfo['randomKey'];
                $str       = 'K$L@aPb$O^Ic%U*Y`T=f+R~d954e1215aef11a512c1585a0fcd5648ff189f1e?Q0"9{8<7@6#5(4%3&2+1';
                $key       = md5($str. $orgcuid . $randomKey);
                $randomKey = Hk_Util_Rc4::rc4($key, $randomKey);
                $devInfo   = array (
                    'randomKey' => base64_encode($randomKey),
                );
            } else {
                $devInfo   = $antiSpamDs->getInfoByDeviceId($cuid);
            }
            $strPart1   = md5($strKey1);
            $strPart2   = md5($strKey2);
            $strPart3   = self::swapString(md5('[' . $devInfo['randomKey'] . ']@'), 15);
            $strPart123 = self::swapString(($strPart1 . $strPart2 . $strPart3), 3);
            $strPart4   = md5($strPart123);
            $strRc4Key  = self::swapString(($strPart123 . $strPart4), 60);
            self::$key  = $strRc4Key;
        }
        return $strRc4Key;
    }

    static private function swapString($string, $num) {
        $intLength = strlen($string) - 1;
        for ($i = 0; $i < $num; $i++) {
            $tmp = $string[$i];
            $string[$i] = $string[$intLength - $i];
            $string[$intLength - $i] = $tmp;
        }
        return $string;
    }

	/**
	 * rc4
     *
	 * @param  string $pwd 密钥
	 * @param  string $data 明文
	 * @return bool true/false
	 */
    static public function rc4($pwd, $data) {

        // 兼容旧代码, 当密钥小于128bit时openssl会默认用0补齐
        if(strlen($pwd) < 16) {
            return mcrypt_encrypt(MCRYPT_ARCFOUR, $pwd, $data, MCRYPT_MODE_STREAM, '');
        }

        return openssl_encrypt($data, 'rc4', $pwd, OPENSSL_RAW_DATA);

        /*
        $key[] = "";
        $box[] = "";
        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }

        return $cipher;*/
    }
}
