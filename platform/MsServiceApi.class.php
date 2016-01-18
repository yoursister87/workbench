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
 * @codeCoverageIgnore
 */
require_once dirname(__FILE__) . "/AutoLoad.class.php";

class MsServiceApi {

	protected static $instanceList;
	protected static $instanceListProxy;


	protected function __construct() {
	}

	protected function __clone() {
	}

    public static function call() {
        $args = func_get_args();

        if (count($args) > 0) {
            $loader = new AutoLoad('platform');
            $loader->start();
            Gj_LayerProxy::init(Gj_Conf::getConf("layerproxy"));
            //获取类名和方法名
            $classMethod = array_shift($args);
            $classMethodArr = explode('.', $classMethod);
            //类名
            $className = $classMethodArr[0];
            //方法名
            $methodName = $classMethodArr[1];

            //实例化dataservice返回结果
            if(PLATFORM_CODE == 'web'){
                if(isset(self::$instanceListProxy[$className])) {
                    $service = self::$instanceListProxy[$className];
                } else {
                    $service = Gj_LayerProxy::getProxy($className);
                    self::$instanceListProxy[$className] = $service;
                } 
            } else {
                $service = self::$instanceList[$className];
                if (!($service instanceof $className)) {
                    $service = new $className;
                    self::$instanceList[$className] = $service;
                }
            }
            $data = call_user_func_array(array($service, $methodName), $args);
            $loader->stop();

        } else {
            $data = array(
                'errorno'  => 1002,
                'errormsg' => __METHOD__.'参数不能为空',
                'data' => '',
            );
        }

        return $data;
    } 

	public static function callByProxy() {
		$args = func_get_args();

		if (count($args) > 0) {
            $loader = new AutoLoad('platform');
            $loader->start();
            Gj_LayerProxy::init(Gj_Conf::getConf("layerproxy"));
			//获取类名和方法名
			$classMethod = array_shift($args);
			$classMethodArr = explode('.', $classMethod);
			//类名
			$className = $classMethodArr[0];
			//方法名
			$methodName = $classMethodArr[1];

			//实例化dataservice返回结果
            if(isset(self::$instanceListProxy[$className])) {
                $service = self::$instanceListProxy[$className];
            } else { 
                $service = Gj_LayerProxy::getProxy($className);
				self::$instanceListProxy[$className] = $service;
			} 
			$data = call_user_func_array(array($service, $methodName), $args);
            $loader->stop();
			
		} else {
            $data = array(
                'errorno'  => 1002,
                'errormsg' => __METHOD__.'参数不能为空',
                'data' => '',
            );
		}

        return $data;
	} 
}
