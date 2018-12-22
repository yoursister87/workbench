<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Util.php
 * @author chuzhenjiang@baidu.com
 * @date 2013-12-18
 * @brief 通用工具
 *
 **/

class Hk_Util_String
{
    
    public static function get_unicode_char($str)
    {
        $out = "";
        for($i=0,$len=strlen($str);$i<$len;$i++)
        {
            $x = decbin(ord(substr($str, $i, 1)));
            if($i == 0)
            {
                $s = strlen($str)+1;
                $out .= substr($x, $s, 8-$s);
            }
            else
            {
                $out .= substr($x, 2, 6);
            }
        }
        return bindec($out);
    }

    public static function utf8_to_gbk($name)
    {
        $tostr = "";
        for($i=0, $len=strlen($name); $i<$len; $i++)
        {
            $curbin = ord(substr($name, $i, 1));
            if($curbin < 0x80) // 小于10000000，字节第一位为0，即为ASCII码，属于单字节符号，范围：小于2^7 = 128，
            {
                $tostr .= substr($name, $i, 1);
            }
            elseif($curbin < bindec("11000000")) // 单字节，第一位为1，范围：[128,  256)
            {
                $str = substr($name, $i, 1);
                $tostr .= "&#" . ord($str) . ";";
            }
            elseif($curbin < bindec("11100000")) // 双字节，
            {
                $str = substr($name, $i, 2);
                $gstr = iconv("UTF-8", "GBK", $str);
                if(!$gstr)
                {
                    $tostr .= "&#" . self::get_unicode_char($str) . ";";
                }
                else
                {
                    $tostr .= $gstr;
                }
                $i += 1;
            }
            elseif($curbin < bindec("11110000")) // 三字节
            {
                $str = substr($name, $i, 3);
                $gstr = iconv("UTF-8", "GBK", $str);
                if(!$gstr)
                {
                    $tostr .= "&#" . self::get_unicode_char($str) . ";";
                }
                else
                {
                    $tostr .= $gstr;
                }
                $i += 2;
            }
            elseif($curbin < bindec("11111000")) // 四字节
            {
                $str = substr($name, $i, 4);
                $tostr .= "&#" . self::get_unicode_char($str) . ";";
                $i += 3;
            }
            elseif($curbin < bindec("11111100")) // 五字节
            {
                $str = substr($name, $i, 5);
                $tostr .= "&#" . self::get_unicode_char($str) . ";";
                $i += 4;
            }
            else // 六字节
            {
                $str = substr($name, $i, 6);
                $tostr .= "&#" . self::get_unicode_char($str) . ";";
                $i += 5;
            }
        }
        return $tostr;
    }


    public static function iconvRec($src, $dest, &$arr, $force_no_detect = 0)
    {
        foreach($arr as $key => &$value)
        {
            if ( is_array($value))
            {
                self::iconvRec($src, $dest, $value, $force_no_detect);
            }
            else if (is_string($value))
            {
                if (1 == $force_no_detect || 0 == strncasecmp(mb_detect_encoding($value,"$dest,$src"), $src,
                strlen($src)))
                {
                    if ( in_array(strtolower($src), array('utf8', 'utf-8')) &&
                    in_array(strtolower($dest), array('cp936','gbk'))) {
                        $value = self::utf8_to_gbk($value);
                    } else {
                        $value = iconv($src,$dest,$value);
                    }
                }
            }
        }
        return $arr;
    }
    
    public static function uni2utf8( $c )
    {
        if ($c < 0x80)
        {
            $utf8char = chr($c);
        }
        else if ($c < 0x800)
        {
            $utf8char = chr(0xC0 | $c >> 0x06).chr(0x80 | $c & 0x3F);
        }
        else if ($c < 0x10000)
        {
            $utf8char = chr(0xE0 | $c >> 0x0C).chr(0x80 | $c >> 0x06 & 0x3F).chr(0x80 | $c & 0x3F);
        }
        else
        {
            $utf8char = "&#{$c};";
        }   
        return $utf8char;
    }   

    public static function gbk_to_utf8($str)
    {
        $partten = '/&#(\d+?);/';
        $str = iconv('gbk', 'utf8//IGNORE', $str);

        $out = preg_replace_callback(
            $partten,
            create_function(
                '$matches',
                'return Hk_Util_String::uni2utf8($matches[1]);'
            ),
            $str
        );
        return $out;
    }
    
    
    /**
     * 多字节文本分割like str_split
     * @param string $string
     * @param int $split_lenth
     * @return array
     */
    public static function mbStrSplit($string,$split_lenth){
       $result=array();
       $left=mb_strlen($string,"utf-8");
       $cut_start=0;
       while($left>0){
           $_subStr=mb_substr($string, $cut_start,$split_lenth,"utf-8");
           $_len=mb_strlen($_subStr,"utf-8");
           if($_len>0){
               $result[]=trim($_subStr);
           }
           $left-=$_len;
           $cut_start+=$_len;
      }
      return $result;
    }
}
