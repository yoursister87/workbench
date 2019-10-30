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
class Gj_Log
{
    const LOG_LEVEL_FATAL   = 0x01;
    const LOG_LEVEL_WARNING = 0x02;
    const LOG_LEVEL_NOTICE  = 0x04;
    const LOG_LEVEL_TRACE   = 0x08;
    const LOG_LEVEL_DEBUG   = 0x10;

    public static $arrLogLevels = array(
        self::LOG_LEVEL_FATAL   => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE  => 'NOTICE',
        self::LOG_LEVEL_TRACE    => 'TRACE',
        self::LOG_LEVEL_DEBUG   => 'DEBUG',
    );

    protected $intLevel;
    protected $strLogFile;
    protected $bolAutoRotate;
    protected $addNotice = array();
    private static $arrInstance = array();

    public static $current_instance;

    private static $strLogPath  = null;
    private static $lastLogs=array();
    private static $lastLogSize=0;
    private static $bolIsOnline = false;
    private function __construct($arrLogConfig)
    {
        $this->intLevel         = $arrLogConfig['level'];
        $this->bolAutoRotate    = $arrLogConfig['auto_rotate'];
        $this->strLogFile       = $arrLogConfig['log_file'];
    }



    private static $category = null;
    private static function getCategory($category){
        if(empty($category)){
            $real_category = 'fang.' . \Gj\Gj_Autoloader::getAppName();
        }else{
            $real_category = 'fang.' . \Gj\Gj_Autoloader::getAppName() . '.'.$category;
        }
        return $real_category;
    }


    /**
     *获取指定App的log对象，默认为当前App
     * @return Gj_Log
     * */
    public static function getInstance($app = null)
    {
        if(empty($app))
        {
            $app = \Gj\Gj_Autoloader::getAppName();
        }
        if(empty(self::$arrInstance[$app]))
        {
            $g_log_conf = Gj_Conf::getConf('log');
            $app_log_conf = Gj_Conf::getAppConf('log');

            // app配置优先
            if(is_array($app_log_conf))
            {
                $g_log_conf = array_merge($g_log_conf, $app_log_conf);
            }

            // 生成路径
            $logPath = self::getLogPath();
            if(!is_dir($logPath."/$app"))
            {
                @mkdir($logPath."/$app");
            }
            $log_file = $logPath."/$app/$app.log";

            $log_conf = array(
                'level'         => intval($g_log_conf['level']),
                'auto_rotate'   => ($g_log_conf['auto_rotate'] == '1'),
                'log_file'      => $log_file,
            );
            self::$arrInstance[$app] = new Gj_Log($log_conf);
        }

        return self::$arrInstance[$app];
    }
    /**
     * @brief 日志打印的根目录
     *
     * @return string
     * @retval
     **/
    public static function getLogPath(){

        if(self::$strLogPath == null){
            $ret = Gj_Conf::getConf('log','log_path');
            if(false !== $ret){
                self::$strLogPath = $ret;
            }else{
                self::$strLogPath = './';
            }
        }
        return self::$strLogPath;


    }

    /**
     * @brief 是否送交猫眼，利用logger类
     *
     * @return  public static function
     * @retval
     * @see
     * @note
     * @author luhaixia
     * @date 2012/08/09 20:25:18
     **/
    public static function isOnline(){
        if(self::$bolIsOnline == null){
            return $ret = Gj_Conf::getConf('/log/is_online');
        }
        return self::$bolIsOnline;
    }
    public static function debug($str, $errno = 0, $arrArgs = null, $depth = 0,$strCategory = '')
    {
        $ret = self::getInstance()->writeLog(self::LOG_LEVEL_DEBUG, $str, $errno, $arrArgs, $depth + 1,$strCategory);
        if(self::isOnline() == 1){
            Logger::logDebug($str,self::getCategory($strCategory));

        }
    }

    public static function trace($str, $errno = 0, $arrArgs = null, $depth = 0,$strCategory = '')
    {
        $ret = self::getInstance()->writeLog(self::LOG_LEVEL_TRACE, $str, $errno, $arrArgs, $depth + 1,$strCategory);
        if(self::isOnline() == 1){
            Logger::logInfo($str,self::getCategory($strCategory));

        }
    }

    public static function notice($str, $errno = 0, $arrArgs = null, $depth = 0,$strCategory = '')
    {
        $ret = self::getInstance()->writeLog(self::LOG_LEVEL_NOTICE, $str, $errno, $arrArgs, $depth + 1,$strCategory);
    }

    public static function warning($str, $errno = 0, $arrArgs = null, $depth = 0,$strCategory = '')
    {
        $ret = self::getInstance()->writeLog(self::LOG_LEVEL_WARNING, $str, $errno, $arrArgs, $depth + 1,$strCategory);
        if(self::isOnline() == 1){
            Logger::logWarn($str,self::getCategory($strCategory));

        }
    }

    public static function fatal($str, $errno = 0, $arrArgs = null, $depth = 0,$strCategory = '')
    {
        $ret = self::getInstance()->writeLog(self::LOG_LEVEL_FATAL, $str, $errno, $arrArgs, $depth + 1,$strCategory);
        if(self::isOnline() == 1){
            Logger::logFatal($str,self::getCategory($strCategory));

        }
    }

    // 生成logid
    public static function genLogID()
    {
        if(defined('LOG_ID')){
            return LOG_ID;
        }

        $arr = gettimeofday();
        $logId = ((($arr['sec']*100000 + $arr['usec']/10) & 0x7FFFFFFF) | 0x80000000);
        define('LOG_ID', $logId);

        return LOG_ID;
    }


    private function writeLog($intLevel, $str, $errno = 0, $arrArgs = null, $depth = 0, $filename_suffix = '')
    {
        if( $intLevel > $this->intLevel || !isset(self::$arrLogLevels[$intLevel]) )
        {
            return;
        }
        if(!is_dir(self::getLogPath())){
            return;
        }
        //log file name
        $strLogFile = $this->strLogFile;
        if( ($intLevel & self::LOG_LEVEL_WARNING) || ($intLevel & self::LOG_LEVEL_FATAL) )
        {
            $strLogFile .= '.wf';
        }
        if(!empty($filename_suffix)){

            $strLogFile .= '.'.$filename_suffix;
        }

        //assign data required
        $this->current_log_level = self::$arrLogLevels[$intLevel];

        //build array for use as strargs
        if (is_array($arrArgs) && count($arrArgs) > 0) { //only arr args
            $this->current_args = $arrArgs;
        } else { //empty
            $this->current_args = array();
        }

        $this->current_err_no = $errno;
        $this->current_err_msg = $str;
        $trace = debug_backtrace();
        $depth2 = $depth + 1;
        if( $depth >= count($trace) )
        {
            $depth = count($trace) - 1;
            $depth2 = $depth;
        }
        $this->current_file = isset( $trace[$depth]['file'] )
            ? $trace[$depth]['file'] : "" ;
        $this->current_line = isset( $trace[$depth]['line'] )
            ? $trace[$depth]['line'] : "";
        $this->current_function = isset( $trace[$depth2]['function'] )
            ? $trace[$depth2]['function'] : "";
        $this->current_class = isset( $trace[$depth2]['class'] )
            ? $trace[$depth2]['class'] : "" ;
        $this->current_function_param = isset( $trace[$depth2]['args'] )
            ? $trace[$depth2]['args'] : "";

        self::$current_instance = $this;
        $str = $this->parseFormat();
        if($this->bolAutoRotate)
        {
            $strLogFile .= '.'.date('Y_m_d');
        }

        if(self::$lastLogSize > 0)
        {
            self::$lastLogs[] = $str;
            if(count(self::$lastLogs) > self::$lastLogSize)
            {
                array_shift(self::$lastLogs);
            }
        }
        return file_put_contents($strLogFile, $str, FILE_APPEND);
    }

    /**
     * 获取最近的日志
     * @return array
     */
    public static function getLastLogs(){
        return self::$lastLogs;
    }

    /**
     *日志格式 '%L: %t [%f:%N] errno[%E] logId[%l] %S %M';
     */
    public  function parseFormat(){
        $time = strftime('%y-%m-%d %H:%M:%S');
        $logId = self::genLogID();
        $args = $this->get_str_args();
        $strLogLine = "$this->current_log_level: $time [$this->current_file:$this->current_line] errno[$this->current_err_no] logId[$logId] func[$this->current_class::$this->current_function] $args $this->current_err_msg {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']} \n";
        return $strLogLine;

    }

    public function get_str_args() {
        $strArgs = '';
        foreach($this->current_args as $k=>$v){
            if(!is_array($v)){
                $v = json_encode($v);
            }
            $strArgs .= ' '.$k.'['.$v.']';
        }
        return $strArgs;
    }

    /**
     * 记录日志信息<主要用于记录和跟踪业务,补充数据库以外的信息>
     * @param string $msg 要写入日志的信息
     * @param string $fileName 要写入的文件名
     * @return bool
     * @author liuty <liutongyi@58ganji.com>
     * @date 2016-8-29
    **/
    public static function info($msg,$fileName=null){
        if(!empty($msg)){
            $logPath = self::getLogPath() .'/'. \Gj\Gj_Autoloader::getAppName();
            if(!is_dir($logPath)){
                @mkdir($logPath,true);
            }
            $log_file = $logPath .'/'.($fileName ? $fileName : 'gj_log_info').date('Y_m_d').".log";
            $msg = date("Y-m-d H:i:s")."\t".$msg.PHP_EOL;
            return file_put_contents($log_file, $msg, FILE_APPEND);
        }
        return false;
    }//end function
    
}
