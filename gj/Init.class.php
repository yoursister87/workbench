<?php
/**
 * @package              
 * @subpackage           
 * @brief                GODP全局初始化类。
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 **/
require_once dirname(__FILE__) . "/../../code_base2/config/config.inc.php";
require_once dirname(__FILE__) . "/autoloader.class.php";
class Gj_Init
{
    static private $isInit = false;

    /* {{{ init */
    /**
     * @brief 
     *
     * @param $app_name
     *
     * @returns   
     */
    public static function init($app_name = null) {
        if (true === self::$isInit) {
            return false;
        }

        self::$isInit = true;

        // 初始化基础环境
        self::initBasicEnv();

        // 初始化App环境
        self::initAppEnv($app_name);

        // 初始化Ap框架
        //self::initAp();

		// 初始化日志库
        //self::initLog($app_name);

        // 执行产品线的auto_prepend
        self::doProductPrepend();

        return true;
        //return Ap_Application::app();
    }//}}}
    /* {{{ initBasicEnv */
    /**
     * @brief 初始化基础环境,由于赶集目前代码结构有很多不合适各业务线独立的地方，
     *          所以此次下面环境的初始化，有些地方虽然不合理也只能这样了。
     *
     * @returns   
     */
    private static function initBasicEnv() {
        // 页面启动时间(us)，PHP5.4可用$_SERVER['REQUEST_TIME']
        define('REQUEST_TIME_US', intval(microtime(true)*1000000));

        // ODP预定义路径
        define('ODP_ROOT_PATH', dirname(__FILE__) . "/../");
        define('ODP_CODE_BASE2', ODP_ROOT_PATH . "/../../");
        // CONF_PATH是文件系统路径，不能传给Gj_Conf
        define('CONF_PATH', ODP_ROOT_PATH.'/conf');
        define('ODP_APP_PATH', ODP_ROOT_PATH); //由于代码都写完了，所以目前ODP_APP_PATH和ROOT_PATH是一个
        define('ODP_LIB_PATH', ODP_ROOT_PATH . "/gj");
        /*
        define('LOG_PATH', ODP_ROOT_PATH.'/log');
        define('DATA_PATH', ODP_ROOT_PATH.'/data');
        define('BIN_PATH', ODP_ROOT_PATH.'/php/bin');
        define('TPL_PATH', ODP_ROOT_PATH.'/template');
        define('WEB_ROOT', ODP_ROOT_PATH.'/webroot');
        define('PHP_EXEC', BIN_PATH.'/php');
         */

        return true;
    }//}}}
    /* {{{ getAppName */
    /**
     * @brief 
     *
     * @returns   
     */
    private static function getAppName(){
        $app_name = null;
        // cgi
        if (PHP_SAPI != 'cli') {
            // /xxx/index.php
            //$script = explode('/', $_SERVER['SCRIPT_NAME']);
            //某些重写规则会导致"/xxx/index.php/"这样的SCRIPT_NAME
            $script = explode('/', rtrim($_SERVER['SCRIPT_NAME'], '/'));

            // ODP app
            if (count($script) == 3 && $script[2] == 'index.php') {
                $app_name = $script[1];
            }
        } else { // cli
            $file = $_SERVER['argv'][0];
            if ($file{0} != '/') {
                $cwd = getcwd();
                $full_path = realpath($file);
            } else {
                $full_path = $file;
            }

            if (strpos($full_path, ODP_APP_PATH.'/') === 0) {
                $s = substr($full_path, strlen(ODP_APP_PATH)+1);
                if (($pos = strpos($s, '/')) > 0) {
                    $app_name = substr($s, 0, $pos);
                }
            }
        }

        return $app_name;
    }//}}}
    /* {{{ initAppEnv */
    /**
     * @brief 初始化APP环境
     *
     * @param $app_name
     *
     * @returns   
     */
    private static function initAppEnv($app_name) {
        // 检测当前App
        if($app_name != null || ($app_name = self::getAppName()) != null) {
            define('IS_ODP', true);
            define('MAIN_APP', $app_name);
        } else {
            define('IS_ODP', false);
            define('MAIN_APP', 'unknown-app');
        }

        // 设置当前App
        require ODP_LIB_PATH.'/env/AppEnv.class.php';
        Gj_Env_AppEnv::setCurrApp(MAIN_APP);

        return true;
    }//}}}
    /* {{{ initAp */
    /**
     * @brief 初始化Ap
     *
     * @returns   
     */
    private static function initAp()
    {
        // 读取App的ap框架配置
        require_once ODP_LIB_PATH.'/Conf.php';
        $ap_conf = Gj_Conf::getAppConf('ap');

        // 设置代码目录，其他使用默认或配置值
        $ap_conf['directory'] = Bd_AppEnv::getEnv('code');

        // 生成ap实例
        $app = new Ap_Application(array('ap' => $ap_conf));
        return true;
    }//}}}
    /* {{{ doProductPrepend */
    /**
     * @brief 执行产品线的auto_prepend
     *
     * @returns   
     */
    private static function doProductPrepend() {
        if (file_exists(ODP_APP_PATH."/auto_prepend.php")) {
            include_once ODP_APP_PATH."/auto_prepend.php";
        }
    }//}}}
    /* {{{ initLog */
    /**
     * @brief 
     *
     * @returns   
     */
	private static function initLog()
	{
        // 初始化日志库，仅为兼容老代码
        define('CLIENT_IP', Bd_Ip::getClientIp());
		//获取userip
        define('USER_IP', Bd_Ip::getUserIp());
		//获取上一个经过的服务器
		define('FRONTEND_IP', Bd_Ip::getFrontendIp());

		Bd_Omp::initOmpLog();
		define("MODULE", APP);

		//获取LogId
		/*
		if(!defined('LOG_ID'))
		{
			Bd_Log::genLogID();
		}
		//获取Product
        if(getenv('HTTP_X_BD_PRODUCT'))
		{
			define('PRODUCT', trim(getenv('HTTP_X_BD_PRODUCT')));
		}
		else
		{
			define('PRODUCT', 'ORP');
		}
		//获取subsys
        if(getenv('HTTP_X_BD_SUBSYS'))
		{
			define('SUBSYS', trim(getenv('HTTP_X_BD_SUBSYS')));
		}
		else
		{
			define('SUBSYS', 'ORP');
		}
		define("MODULE", APP);
		*/
	}//}}}
}
