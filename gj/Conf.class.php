<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * 此方法用来解析框架内的配置文件
 * 目前配置文件采用.ini格式
 */
class Gj_Conf
{
    protected static $confPath = null;
    /* {{{ getConf */
    /**
     * @brief 
     *
     * @param $confFileName
     * @param $confName
     *
     * @returns  array|false 
     */
    public static function getConf($confFileName, $confName = null){
        $confFile = self::getPath().$confFileName.".ini";
        $conf = false;
        if (is_file($confFile)) {
            $conf = parse_ini_file($confFile, true);
        }
        if (is_array($conf)
            && is_string($confName)
            && isset($conf[$confName])
        ) {
            return $conf[$confName];
        }
        return $conf;
    }//}}}
    /* {{{ patch528 */
    /**
     * @brief 兼容php5.2.8, 由于低版本php不支持array["key"]这样的写法，修改成array.key = xxx
     *
     * @param $conf
     *
     * @returns   
     */
    public static function patch528($conf) {
        $tmpConf = array();
        foreach ($conf as $confKey => $value) {
            $arr = explode(".", $confKey);
            if (is_array($arr) && 2 === count($arr)) {
                $tmpConf[ $arr[0] ][ $arr[1] ] = $value;
            } else {
                $tmpConf[$confKey] = $value;
            }
        }
        return $tmpConf;
    }//}}}
    /* {{{ getAppConf */
    /**
     * @brief 获得某个app的配置文件
     *
     * @param $confFile string 配置文件名字
     * @param $confName string 配置项内容
     *
     * @returns  array 
     */
    public static function getAppConf($confFile, $confName = null){
        return self::getConf(\Gj\Gj_Autoloader::getAppName() . "/{$confFile}", $confName);
    }//}}}
    /* {{{ getPath */
    /**
     * @brief 
     *
     * @returns   
     */
    protected static function getPath(){
        if(self::$confPath ===null){
            return  dirname(__FILE__) . "/../conf/";
        }
        return self::$confPath;
    }//}}}
    /* {{{ setPath */
    /**
     * @brief 
     *
     * @param $filePath
     *
     * @returns   
     */
    public static function setPath($filePath){
        if(is_dir($filePath)){
            self::$confPath =$filePath;
        }
    }//}}}
}
