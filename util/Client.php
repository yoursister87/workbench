<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Client.php
 * @author chuzhenjiang(com@baidu.com)
 * @date 2013/12/09 11:34:46
 * @brief 客户端版本信息
 *
 **/

class Hk_Util_Client
{
    const APP_TOKEN         = 'token';  //request中标识版本信息的字段 两位版本
    const APP_VERSION       = 'vc';     //request中标识具体版本的字段 精确版本
    const APP_CUID          = 'cuid';   //request中的设备id
    const TOKEN_LEN         = 34;         //版本信息长度

    const ANDROID_VERSION   = 1;        //安卓版本
    const IOS_VERSION       = 2;        //iOS版本

    ///app版本token映射表,后期需要维护,分配使用后，请在对应行尾加上版本注释
    //格式：android的1_uToLopFNsjcxpLIPu8DG3xRSjGJvSpWV,ios的2_uToLopFNsjcxpLIPu8DG3xRSjGJvSpWV
    public static $arrVersionMap = array(
        'uToLopFNsjcxpLIPu8DG3xRSjGJvSpWV' => 1, //1.0版本
        'kFO7DjqCPBnoP71FP4xwXRxd4mtiG7b2' => 2, //1.1版本
        'gUf6IEsL6TCRuVoIYkQ2OhH3YiOpkDjO' => 3, //1.2版本
        'SKLpivofoeowHfPFg8msm65oR72g8m4f' => 4, //1.3版本
        'ut7plkKwBSWNs8XsCkWRjsGKrPiYJQxt' => 5, //1.4版本
        '5fUvKXoy6VJMhBP2UiVJcRKLvnPkG72K' => 6, //1.5版本
        'rFTHVxPpLsTLTU6h46pUyJiX1fXG8wh4' => 7, //2.0版本
        'KETfIc3Ic1WvPsHdkqYgqO4Tu4DgIQ5F' => 8, //2.1版本
        'OjfmeoDYUYLQcfBRNW7Fkxm1QCfsdI7N' => 9, //2.2版本
        'GohwxPP5tXlMYXHPm3Y4SISRrEbbf3SI' => 10, //2.3版本
        'XgkYIxe4mvdigoYS687j5YhgBY8twPRg' => 11, //2.4版本
        'xbQppnVJniUipy8ODlQ5jDXNId7dvv3f' => 12, //andriod2.5版本  ios3.0版本
        'gHFsq1FP8dQVl6K6WOLQnkEh6k5q1Non' => 13, //andriod3.0版本  ios3.1版本
        'KMBcYiVtXrMk7nFjGLORePo1gUXebjqO' => 14, //android3.1版本  ios3.2版本
        'ODLbJtc6eHUfhWPMc6rLtVGJsHlnEpOr' => 15, //android3.2版本  ios3.3版本
        'WOiJ36I2PwEVoJjgBrrI5edtvrlBe8ge' => 16, //android3.3版本
        '1TwtjlQ5gFvgg6eMLVehg3kV855JfJqs' => 17, //android3.4版本
        'XIEY7cf2UpNRmPxXPEMMtLpc5EuTQJdD' => 18, //android3.5版本&PGC版本
        'GcIBlGF2KRCM4wPyC6VeUgdumcc2hojG' => 19, //android4.0版本
        'oXfsOlQC5dtUCQtnyLqlSkdx2uJdJBws' => 20, //android4.1版本
        'v3JT7VWnCqY8PiBiv7GVLyLj7H5XsXWH' => 21, //android4.2版本
        'qP658d14KPoEuMrooHRmmBYVyTdWPbQU' => 22, //android4.3版本
        'TOfJXuw2tgYwC1qHQpDY4m5i7TmHpLhw' => 23,
        'FUsKwHP3eOjGgi71pGOmnYefKEYTj3bK' => 24,
        'KiMhweWh1DshP1tDcEPI3PTJlBuBjwVc' => 25,
        'rDhLDi3Ow2xh1mCqvXOgnFk1P3E5ErmM' => 26,
        'Idw7bLseJJQbxI82G6xPMOQCFPFMYnqQ' => 27,
        'qin3xeYWvRN6iDMUJfCqRYMVUuwJcBj7' => 28,
        'pfmxQ3TBYyd1MxJ1IBLiJdlSx2wudeoe' => 29,
        'XPXQH3c5HRPtFHkSwi3sCCURmT25QfxM' => 30, //7.2
        'TCQ5OXCfb7WGoVIbTgFUXXTTmmPPQF4k' => 31,
        'Hww44uxVejEG2iigjn1eFSuGvYP7RkEr' => 32,
        '17Godnxc2nBw54gvjNPtEm52ur2pYFiI' => 33,
        'w4tUkikF424m4GHMElTYGwiPiMK2ygIT' => 34,
        'FQqIjNe6Gj5lEWPPlNPybvoRDe8UXS6n' => 35,
        'wJU1IJI823GiriUxEQ8eHfqFeprfBRd6' => 36,
        'rsPFJLCjPk6xo5NW7FLxiTsgbGSXH6ys' => 37,
        'bbr7MBdMiqViPeOKvwMCewxDDOv4DMEF' => 38,
        'oX6JNqYBeNUb7vykqfcIms1Qd3ckrQJD' => 39,
        'YXOYhWmwSd1KoNSY5thC34oFffpVbrCX' => 40,
    );


    /**
     *
     * 获取版本信息
     */
    public static function getVersion()
    {
        $arrOutput = array(
            'version'       => 0,
            'versionCode'	=> 0,
            'os'            => 0,
            'source'        => 0,
            'token'         => '',
            'type'			=> '',
        );
        if(isset($_REQUEST[self::APP_TOKEN]) && strlen($_REQUEST[self::APP_TOKEN]) == self::TOKEN_LEN)
        {
            list($intOS, $strToken) = explode('_', $_REQUEST[self::APP_TOKEN]);
            $arrOutput['version'] = intval(self::$arrVersionMap[$strToken]);
            $arrOutput['os'] = $intOS == self::IOS_VERSION
                ? self::IOS_VERSION : self::ANDROID_VERSION;
            $arrOutput['source'] = 1000 * ($arrOutput['os'] + 1) + $arrOutput['version'];
            $arrOutput['token'] = strval($strToken);
            $arrOutput['type'] = $intOS == self::IOS_VERSION
                ? 'ios' : 'android';
            $arrOutput['msgVersion'] = $arrOutput['version'] - 20; //4.2做为新消息的起始版本 客户端注册时用.
        }
        if(isset($_REQUEST[self::APP_VERSION]))
        {
            $arrOutput['versionCode'] = intval($_REQUEST[self::APP_VERSION]);
        }
        return $arrOutput;
    }


    /**
     *
     * 获取设备号
     */
    public static function getTerminal()
    {
        $arrOutput = array(
            'terminal'  => '',
            'imei'      => '',
        );
        if(isset($_REQUEST[self::APP_CUID]) && strlen($_REQUEST[self::APP_CUID]) > 0)
        {
            $arrOutput['terminal'] = strval($_REQUEST[self::APP_CUID]);
            $arrOutput['imei'] = strval($_REQUEST[self::APP_CUID]);
        }elseif(isset($_COOKIE['TERMINAL']) && strlen($_COOKIE['TERMINAL']) > 0){
			 if(false !== strpos($_COOKIE['TERMINAL'], 'iPad_')){
				$termal = substr($_COOKIE['TERMINAL'], 5);
			 }elseif(false !== strpos($_COOKIE['TERMINAL'], 'iPhone_')){
				$termal = substr($_COOKIE['TERMINAL'], 7);
			 }elseif(false !== strpos($_COOKIE['TERMINAL'], '_')){
				$termal = substr($_COOKIE['TERMINAL'], strpos($_COOKIE['TERMINAL'], '_')+1);
			 }else{
				$termal = $_COOKIE['TERMINAL'];
		     }
			 $arrOutput['terminal'] = $termal;
		}elseif(isset($_COOKIE['cuid']) && strlen($_COOKIE['cuid']) > 0){
			 $arrOutput['terminal'] = $_COOKIE['cuid'];
		}
        return $arrOutput;
    }

    /**
     *
     * 获取来源
     */
    public static function getFromId()
    {
        $arrVersion = self::getVersion();
        return intval($arrVersion['source']);
    }

    /**
     *
     * 获取app类型
     */
    public static function getSubmitFrom($strExt = '')
    {
        $arrVersion = self::getVersion();
        return strval($arrVersion['type']).$strExt;
    }

    /**
     *
     * 按照source获取app类型
     */
    public static function getSubmitFromBySource($intSource, $strExt = '')
    {
        $strType = 'android';
        if($intSource >= 3000 && $intSource <= 4000)
        {
             $strType = 'ios';   
        }
        return $strType.$strExt;
    }
}


