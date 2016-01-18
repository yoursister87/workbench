<?php
/**
 * 此类是获取单例的工具类，此类对外公开 getInstance 方法
 * @codeCoverageIgnore
 */
class PlatformSingleton
{
    //存储单例对象的数组
	protected static $instanceList;
    protected static $instanceListProxy;
	protected function __construct() {
	}

	protected function __clone() {
	}
    
	//获取单例方法
	public static function getInstance($className) {	
        //实例化dataservice返回结果
        if(PLATFORM_CODE == 'web'){
            if(isset(self::$instanceListProxy[$className])) {
                $service = self::$instanceListProxy[$className];
            } else {
                $service = Gj_LayerProxy::getProxy($className);
                self::$instanceListProxy[$className] = $service;
            }
            return $service;
        } else {
            if (!isset(self::$instanceList[$className])
                || !(self::$instanceList[$className] instanceof $className)
            ) {
                self::$instanceList[$className] = new $className;
            }
            return self::$instanceList[$className];


        }
	}

	public static function setInstance($className, $obj){
        if(PLATFORM_CODE == 'web'){
            self::$instanceListProxy[$className] = $obj;
        } else {
            self::$instanceList[$className] = $obj;
        }
    }
}
