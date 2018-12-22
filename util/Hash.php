<?php

/**
 * @file   Hash.php
 * @author 梁爽(liangshuang01@zybang.com)
 * @date   2016-04-06 16:21
 * @brief
 *
 **/
class Hk_Util_Hash
{
    /**
     * 将传入字符转换成数字,hash算法
     * @param $key
     * @return int
     */
    public static function hashInt($key) {
        $len = strlen($key);
        $h   = 0;

        if (is_string($key) && $len > 0) {
            $h = ord($key[0]);

            for ($i = 1; $i < $len; $i++) {
                $c = ord($key[$i]);
                $h = (($h << 5) - $h + $c) & 0xFFFFFFFF;
            }
        }

        return $h;
    }
}