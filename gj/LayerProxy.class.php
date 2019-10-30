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
 */
final class Gj_LayerProxy
{
	private $caller = null;
	private static $conf =	array();

	public static $is_ut = false;
	public static $ut_proxy_arr = array();

	/* {{{ __construct */
	/**
	 * @brief 
	 *
	 * @param $proxy
	 *
	 * @returns   
	 */
	private function __construct(IProxy $proxy){
		$this->caller = $proxy;
	}//}}}
	/* {{{ init */
	/**
	 * @brief 
	 *
	 * @param $conf
	 *
	 * @returns   
	 */
	public static function init($conf){
		self::$conf = $conf;
	}//}}}
	/* {{{ getProxy */
	/**
	 * @brief 
	 *
	 * @param $mod
	 * @param $param
	 *
	 * @returns   
	 */
	public static function getProxy($mod, $param=null){
		if (self::$is_ut 
			&& isset(self::$ut_proxy_arr[$mod])
		) {
			return self::$ut_proxy_arr[$mod];  
		}
		$proxy = new Gj_Layerproxy_PHPProxy($mod);
		$proxy->init(self::$conf, $param);
		$api = new Gj_LayerProxy($proxy);
		return $api;
	}//}}}
	/* {{{ registerProxy */
	/**
		* @brief 
		*
		* @param $key
		* @param $obj
		*
		* @returns   
	 */
	public static function registerProxy($key, $obj) {
		if (!$key) {
			return false;
		}
		self::$ut_proxy_arr[$key] = $obj;

		return true;
	}//}}}
	/* {{{ clearProxy */
	/**
	 * @brief 
	 *
	 * @returns   
	 */
	public static function clearProxy() {
		self::$ut_proxy_arr = array();	
	}//}}}
	/* {{{ __call */
	/**
	 * @brief 
	 *
	 * @param $name
	 * @param $args
	 *
	 * @returns   
	 */
	public function __call($name,$args){
		try {
			$ret = $this->caller->call($name,$args);
			return $ret;
		}catch(Exception $ex) {
			throw $ex;
		}
	}//}}}
}
/* vim: set ts=4 sw=4 sts=4 tw=100 noet: */
