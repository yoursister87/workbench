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
 * 需要定义APP_ROOT来标示应用模块代码位置
 */
namespace Gj;
class Gj_Autoloader
{
    private static $arrMap = array();
	private static $appName = null;
    private static $layerPath = array(
        'Service' => 'models/service',
        'Dao' => 'models/dao',
        'Config' => 'config',
        'Util' => 'util',
        'Controller' => 'controllers',
        'Gj' => '../gj',
        'Action'=> 'actions',
        'Api'=>'../gj/fang/api',

    );
	public static function setAppName($appName){
		self::$appName = $appName;
	}
    public static function getAppName(){
        return self::$appName;
    }
    /* {{{ addClassMap
     * 添加类映射表
     *
     * @note: 文件若是相对路径，则自动加上ODP根目录，即ROOT_PATH宏
     * */
    public static function addClassMap($arrMap) {
        if (is_array($arrMap)) {
            self::$arrMap = array_merge(self::$arrMap, $arrMap);
            self::start();
        }
    }//}}}
    /* {{{ start */
    /**
     * @brief 
     *
     * @returns   
     */
    public static function start(){
        self::$arrMap = array_merge(self::$arrMap, self::getMap());
        spl_autoload_register(array('\Gj\Gj_Autoloader', 'autoload'));
    }//}}}
    /* {{{ reset */
    /**
     * @brief 
     *
     * @returns   
     */
    public static function reset() {
        spl_autoload_unregister(array('\Gj\Gj_Autoloader', 'autoload'));
        self::$arrMap = array();
		self::$appName = null;
    }//}}}
    /* {{{ autoload */
    /**
     * @brief 
     *
     * @param $name
     *
     * @returns   
     */
    public static function autoload($name){
		if (class_exists($name) === true) {
			return true;
		}
        if (isset(self::$arrMap[$name])) {
            $file = self::$arrMap[$name];
            if ($file[0] == '/') {
                require_once $file;
            } else {
                require_once $codeBaseDir ."/$file";
            }
        } else {

            //走namespace
            if(strpos($name,"\\") !== false){
                $arrName = explode("\\",$name);
                $name = array_pop($arrName);
            }
            //走新规则，把路径名也作为类的一部分
            $classPath = explode('_', $name);
            $fileBaseName = array_pop($classPath);
            $strLayer = array_shift($classPath);
            $path = strtolower(implode('/', $classPath));
            $layerPath = self::$layerPath[$strLayer];
            if(!empty($layerPath)){
                $filePath = dirname(__FILE__) . "/../". self::$appName . "/$layerPath/{$path}/{$fileBaseName}.class.php";
            }
            if (null !== $filePath && file_exists(realpath($filePath))) {
                include_once $filePath;
            } else {
            }
        }
    }//}}}
    /* {{{ getMap */
    /**
     * @brief 框架内部使用的对外依赖
     *
     * @returns   
     */
    private static function getMap(){
        $codeBaseDir = dirname(__FILE__) . "/../../code_base2";
        $ganjiConfDir = $codeBaseDir . "/../ganji_conf/";
        define('TG_HOUSE', $codeBaseDir."/../fang/tuiguang/house/");
        define('FANG_V3', $codeBaseDir."/../fang/tuiguang/fang_v3/");
		return array(
            "MemcacheConfig" => $ganjiConfDir . "/MemcacheConfig.class.php",
            "MemCacheAdapter" => $codeBaseDir . "/util/cache/adapter/MemCacheAdapter.class.php",
            "CacheNamespace" => $codeBaseDir . "/util/cache/CacheNamespace.class.php",
            "Logger" => $codeBaseDir . "/util/log/Logger.class.php",
            "DBConfig"      => $codeBaseDir . "/../ganji_conf/DBConfig.class.php",
            "DBTransaction"      => $codeBaseDir . "/util/db/DBTransaction.class.php",
            "DBMysqlNamespace" => $codeBaseDir . "/util/db/DBMysqlNamespace.class.php",
             "GeoNamespace" => $codeBaseDir . "/app/geo/GeoNamespace.class.php",
             "RedisClient" => $codeBaseDir . "/util/redis/RedisClient.class.php",
            "RedisConfig" => $ganjiConfDir . "/RedisConfig.class.php",
            "TgUser" => TG_HOUSE . "/model/user/TgUser.class.php",
            "Ajk" => $codeBaseDir . "/app/house_premier/ajk/Ajk.class.php",
            "HousingVars" => $codeBaseDir . "/app/post/adapter/housing/include/HousingVars.class.php",
            "MsCrmAdPostApp" => $codeBaseDir . "/../msapi/core/app/post/MsCrmAdPostApp.class.php",
            "HousingPremierHelperPageConfig" => $codeBaseDir ."/../fang/tuiguang/fang_v3/apps/housing/premier/config/HousingPremierHelperPageConfig.class.php",
            "ChangeHouseSourceInfo" => TG_HOUSE . "/model/ChangeHouseSourceInfo.class.php",
            "WapHousingUrlNamespace" => $codeBaseDir . "/../fang/wap/app/housing/common/include/WapHousingUrlNamespace.class.php",
            "CategoryManager" => FANG_V3 . "/common/category/CategoryManager.class.php",
            "HousingPremierMasterPost" => FANG_V3 . "apps/housing/premier/model/HousingPremierMasterPost.class.php"
        );
    }//}}}
}
