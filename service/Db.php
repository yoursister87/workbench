<?php
/**
 * @category    library
 * @package     hk
 * @author      com<jiangyingjie@baidu.com>
 * @version     2014-12-1 17:31:55
 * @copyright   Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 *
 */
class Hk_Service_Db {

    /**
     * 连接的数据库名
     * @var string
     */
	private $_name;

    /**
     * 数据库连接实例
     * @var resource
     */
	private $_db;

    /**
     * mysql日志文件
     * @var string
     */
    private $_logFile = NULL;

    private $_autoRotate;

    const TYPE_INT  = 1;    //整形，做intval处理
    const TYPE_STR  = 2;    //字符串类型，mysql结果集字段默认为int类型
    const TYPE_JSON = 3;    //Json类型，做json_decode处理（非必要请尽量少用）

	private function __construct($name, $db, $logFile=NULL, $autoRotate=true) {
		$this->_name = $name;
		$this->_db   = $db;
		if ($this->_logFile === NULL) {
		  $this->_logFile = $logFile;
        }
        $this->_autoRotate = $autoRotate;
	}

	/**
	 * 通过数据库名称获取DB的连接
     *
	 * @param  string  $name 配置的数据库名称
	 * @param  boolean $new  是否每次都获取新的连接，true是，false否，默认false
	 * @param  string  $logFile mysql读写日志文件名
	 * @return self Hk_Service_Db类型的数据库连接对象
	 */
	static public function getDB($name, $new=false, $logFile=NULL, $autoRotate=true) {
	$db = Bd_Db_ConnMgr::getConn($name, NULL, $new);
        if (empty($db)) {
            Bd_Log::warning("Error:[get DB connection failed], Detail:[DB name:'{$name}', pls check configuration]");
            return false;
        }
        if (!$db->isConnected) {
            unset($db);
            $db = Bd_Db_ConnMgr::getConn($name, NULL, true);
        }
		$objDb = new Hk_Service_Db($name, $db, $logFile, $autoRotate);
		return $objDb;
	}

	/**
	 * 使用魔术方法调用Bd_DB类的其它方法，相当于直接操作Bd_DB类, 调用不存在的方法或者使用空Bd_DB对象会触发fatal error
     *
     * @link http://docs.babel.baidu.com/doc/7e062776-df30-46bf-b11b-9d9491ffd501 Bd_DB手册
	 * @param  string $method 调用的函数名
	 * @param  array  $args   传递的参数数组
	 * @return mixed 成功则为对应函数调用结果，失败为false，部分函数返回NULL
	 */
	public function __call($method, $args) {
        //校验对象及调用方法
		if (empty($this->_db) || !($this->_db instanceof Bd_DB)) {
			trigger_error("Call to a member funciton {$method}() on a non-object", E_USER_ERROR);
		}

		if (!method_exists($this->_db, $method)) {
			trigger_error("Call to undefined method {$method}()", E_USER_ERROR);
		}
		//执行对象的对应方法
        $sqlBeginTime = microtime(true);
		$ret = call_user_func_array(array($this->_db, $method), $args);
        $sqlEndTime = microtime(true);
        $sqlExeTime = ($sqlEndTime - $sqlBeginTime) * 1000;

        //当函数返回值为false或者NULL的时候，区分情况输出日志
		switch ($method) {
            case 'insert':
            case 'update':
            case 'delete':
			case 'query':
			case 'select':
			case 'selectCount':
                if ($this->_logFile) {
                    if ($this->_autoRotate) {
                        $file = LOG_PATH.'/'.$this->_logFile.'.'.date('YmdH');
                        $fileSlow = LOG_PATH.'/'.$this->_logFile.'.slow.'.date('YmdH');
                    } else {
                        $file = LOG_PATH.'/'.$this->_logFile;
                        $fileSlow = LOG_PATH.'/'.$this->_logFile.'.slow';
                    }
                    if (!is_dir(dirname($file))) {
                        mkdir(dirname($file).'/', 0775, TRUE);
                    }
                    if (!is_dir(dirname($fileSlow))) {
                        mkdir(dirname($fileSlow).'/', 0775, TRUE);
                    }
			        $retJson = json_encode($ret);
			        $retJson = substr($retJson, 0, 1000);
                    $uri = $_SERVER['REQUEST_URI'];
                    if (empty($uri)) {
                        $uri = realpath($_SERVER['SCRIPT_FILENAME']);
                    }
			        $sql = date('Y-m-d H:i:s')." URI[$uri] LOGID[{$_SERVER['HTTP_X_BD_LOGID']}] SQL[{$this->_db->lastSQL}] RET[$retJson] TIME[{$sqlExeTime}]\n";
                    file_put_contents($file, $sql, FILE_APPEND|LOCK_EX);
                    //slow log
                    if ($sqlExeTime > 1000) {
                        $arrTrace = debug_backtrace();
                        $i = 0;
                        $logTrace = "";
                        foreach($arrTrace as $row) {
                            $logTrace .= "#$i " . $row['function'] . ' called at ' . $row['file'].':'.$row['line'] ."\n";
                            $i++;
                        }
                        $logInfo = date('Y-m-d H:i:s')." URI[$uri] LOGID[{$_SERVER['HTTP_X_BD_LOGID']}] "
                                    . "SQL[{$this->_db->lastSQL}] TIME[{$sqlExeTime}] TRACE: \n$logTrace";
                        file_put_contents($fileSlow, $logInfo, FILE_APPEND|LOCK_EX);
                    }
                }
				if ($ret === false) {
					Bd_Log::warning("Error:[{$method} db failed], Detal:[db:'{$this->_name}', errno:'{$this->_db->errno}', ".
					        "error:'{$this->_db->error}', lastSQL:'{$this->_db->lastSQL}', lastCost:'{$this->_db->lastCost}', ".
					        "isConnected:'".var_export($this->_db->isConnected,true)."']");
				}
				break;
			default:
				break;
		}
		return $ret;
	}

	/**
	 * 使用sql模板方式进行查询，追加打印日志，注意多数场合查询完需要手动close
     *
	 * @param  string $sqlTpl    待执行的sql语句模板
	 * @param  array  $arrParams sql语句的模板变量和对应的值
	 * @return array 查询结果,失败则为false
	 */
	public function queryUseTpl($sqlTpl, $arrParams) {
		$tpl = new Bd_DB_SQLTemplate($this->_db);
		$tpl->prepare($sqlTpl);
		$sql = $this->_tpl->bindParam($arrParams, NULL, true);
		if (empty($sql)) {
			Bd_Log::warning("Error:[build sql failed], Detail:[sqlTpl:'{$sqlTpl}', params:'".json_encode($arrParams)."']");
			return false;
		}
		return $this->_db->query($sql);
	}

	/**
	 * 对传入的SQL限制条件数组或字符串，转换成Bd_DB可以识别的方式
     *
	 * @param  mixed  $arrConds 字符串或者数组格式的SQL限制条件, 格式示例：
	 * <code>"field = value"<code/>或者
     * <code>
	 * array(
	 *     'field' => 'value',
	 *     'field' => array(value, '=|>|<|...'),
	 * )
     * </code>
	 * @param  array  $arrMap 程序中使用的字段名和数据库列名的映射数组
	 * @return mixed 返回转换后的SQL限制条件（字符串或者数组）,不符合条件的返回NULL，转换失败会触发fatal error
	 */
	public static function getConds($arrConds, $arrMap = null){
        //参数检查, 对字符串类型直接返回，对于非数组类型返回NULL
        if (is_string($arrConds)) {
        	return $arrConds;
        }
        if (!is_array($arrConds)) {
        	return NULL;
        }

        //对数组类型的进行重新拼装
        $arrCondsRes = array();
        foreach($arrConds as $key => $value){
            if ($arrMap !== NULL && isset($arrMap[$key])) {
                $key = $arrMap[$key];
            }
            if (is_array($value)) {
            	switch (count($value)) {
            		case 2:
            			$arrCondsRes["$key ".$value[1]] = $value[0];
            			break;
            		case 4:
            			$arrCondsRes["$key ".$value[1]] = $value[0];
                    	$arrCondsRes["$key ".$value[3]] = $value[2];
            			break;
            		default:
            			trigger_error("Unsupport SQL conds format, arrConds:'".var_export($arrConds,true)."' in ".__FILE__, E_USER_ERROR);
            			break;
            	}
            } elseif (is_numeric($key) && is_string($value)) {
                $arrCondsRes[] = $value;
            }
            else {
                $arrCondsRes["$key ="] = $value;
            }
        }
        return $arrCondsRes;
    }

    /**
     * 将字段数组和数据库表的列名做映射转换
     * @param  mixed   $arrFields     程序中使用的字段数组，格式为array(field1, field2, ...)，
     *                                若该参数为字符串则直接返回
     * @param  array   $arrFieldsMap  字段和数据库表列名的映射数组
     * @param  boolean $useAs         是否转换成"列名 as 程序字段名"的形式，默认为false不转
     * @return array 转换之后的数组
     */
    public static function mapField($arrFields, $arrFieldsMap, $useAs=false) {
        //参数的校验
        if (empty($arrFields) || !is_array($arrFields) || empty($arrFieldsMap)) {
            return $arrFields;
        }

        //将fields中的名称转换成映射表中对应的名称，如果使用as，则写成 column as field 的形式
        $ret = array();
        foreach($arrFields as $field){
            if (isset($arrFieldsMap[$field])){
                $ret[] = ($useAs != false) ? ($arrFieldsMap[$field]." as ".$field) : $arrFieldsMap[$field];
            } else{
                $ret[] = $field;
            }
        }
        return $ret;
    }

    /**
     * 将键值数组中的键值用数据库表的列名做映射转换
     * @param  mixed  $arrRow       程序中使用的键值数组，格式为array('key' => value, ....)，
     *                              若该参数为字符串则直接返回
     * @param  array  $arrFieldsMap 字段和数据库表列名的映射数组
     * @return array 转换之后的数组
     */
    public static function mapRow($arrRow, $arrFieldsMap) {
    	//参数的校验
    	if (empty($arrRow) || !is_array($arrRow) || empty($arrFieldsMap)) {
            return $arrRow;
        }

        //将rows中的名称转换成映射表中对应的名称
        $ret = array();
        foreach($arrRow as $field => $value){
            if (isset($arrFieldsMap[$field]) && $field != $arrFieldsMap[$field]){
                $ret[$arrFieldsMap[$field]] = $value;
                unset($arrRow[$field]);
            }
            else{
                $ret[$field] = $value;
            }
        }
        return $ret;
    }

    /**
     * 根据返回结果的字段类型进行类型转换，mysql返回的所有值均为字符串
     * @param  array  $arrRet      mysql返回的单行结果数组
     * @param  array  $arrTypesMap dao中定义的返回结果类型映射表
     * @return array 字段类型映射之后的结果数组
     */
    public static function mapFieldType($arrRet, $arrTypesMap) {
        if (empty($arrRet) || !is_array($arrRet) || empty($arrTypesMap)) {
            return $arrRet;
        }

        //将结果中的字段类型进行转换
        foreach ($arrTypesMap as $field => $type) {
            if (isset($arrRet[$field])) {
                switch ($type) {
                    case self::TYPE_INT:
                        $arrRet[$field] = intval($arrRet[$field]);
                        break;
                    case self::TYPE_STR:
                        $arrRet[$field] = strval($arrRet[$field]);
                        break;
                    case self::TYPE_JSON:
                        if (!empty($arrRet[$field]) && is_string($arrRet[$field])) {
                            $arrRet[$field] = json_decode($arrRet[$field], true);
                            if ($arrRet[$field] === NULL || !is_array($arrRet[$field])) {
                                Bd_Log::warning("Error:[json_decode failed], Detail:[field:'{$field}', type:'{$type}', value:'{$arrRet[$field]}']");
                                $arrRet[$field] = array();
                            }
                        } else {
                            $arrRet[$field] = array();
                        }
                        break;
                    default:
                        BD_Log::warning("Error:[Undefined field type], Detail:[field:'{$field }', type:'{$type}']");
                        break;
                }
            }
        }
        return $arrRet;
    }

    /**
     * 对插入或者更新的字段进行类型检查和转换，类型映射中没有的默认为字符串
     * @param  array  $arrFields   待插入或者更新的字段数组
     * @param  array  $arrTypesMap dao中定义的返回结果类型映射表
     * @return array  成功返回类型转换后的数组，未转换或输入错误不做处理
     */
    public static function checkFieldType($arrFields, $arrTypesMap) {
        if (empty($arrFields) || !is_array($arrFields) || empty($arrTypesMap)) {
            return $arrFields;
        }

        foreach ($arrTypesMap as $field => $type) {
            if (isset($arrFields[$field])) {
                switch ($type) {
                    case self::TYPE_INT:
                        $arrFields[$field] = intval($arrFields[$field]);
                        break;
                    case self::TYPE_JSON:
                        if (is_array($arrFields[$field])) {
                            $arrFields[$field] = json_encode($arrFields[$field]);
                            if ($arrFields[$field] === false) {
                                Bd_Log::warning("Error:[json_decode failed], Detail:[field:'{$field}', type:'{$type}', value:'{$arrRet[$field]}']");
                                $arrFields[$field] = '';
                            }
                        }
                        if (!is_string($arrFields[$field])) {
                            Bd_Log::warning("Error:[field type error], Detail:[field:'{$field}' should be json string]");
                        }
                        break;
                    default:
                        break;
                }
            }
        }

        return $arrFields;
    }

	/**
	 * TODO: Bd_DB 兼容wikidb的各种使用方法，比如select，insert之类的函数
	 * 可以抽出通用流程和日志输出，列在此处。
	 */
}
