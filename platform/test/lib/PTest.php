<?php 

/**
* @file PTest.php
* @brief 主要用于对现有类之间的强依赖进行mock
* @author Lu Xuechao (luxuechao@ganji.com)
* @version 1.0
* @date 2014-07-16
 */


/*
 * 包含主要的配置文件
 * Conf_StaticClassMap.php：依赖于类的静态函数的集合
 * Conf_CommonClassMap.php：强依赖于类本身的集合
 */
require_once dirname(__FILE__)."/Conf_StaticClassMap.php";
require_once dirname(__FILE__)."/Conf_CommonClassMap.php";

//PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');
/* ##########################################################*/
/**
 * @brief mock接口
 */
/* ##########################################################*/

class Testcase_PTest extends PHPUnit_Framework_TestCase 
{
    public static $fn2FakeObj = array();

    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setBackupGlobals(false);
    }

    /* ##########################################################*/
    /**
        * @brief genObjectMock Returns a mock object for the specified class.
        *
        * @param $originalClassName 
        * @param $methods
        * @param $arguments
        * @param $mockClassName
        * @param $callOriginalConstructor
        * @param $callOriginalClone
        * @param $callAutoload
        *
        * @return PHPUnit_Framework_MockObject_MockObject
     */
    /* ##########################################################*/
    public function genObjectMock($originalClassName, $methods, array $arguments = array(), $mockClassName = '', $callOriginalConstructor = FALSE, $callOriginalClone = TRUE, $callAutoload = TRUE) 
    {
		return $this->getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor, $callOriginalClone, $callAutoload);
	}


    /* ##########################################################*/
    /**
        * @brief genAllObjectMock 根据$conf生成所有mock
        *
        * @param $conf mock的配置，是一个array，格式如下
        *       array(
        *           'className1' => array(
        *               'fn1' => array(
        *                   'return' => ... 返回值(必须有)
        *                   'params' => ...  接口检查参数(可选)
        *               ),
        *               'mul1:fn2' => ...   mul表示接口被多次调用，1表示第一次
        *               'mul2:fn2' => ...   这里的接口顺序必须与真实调用顺序一致
        *           )
        *           'className2' => ...
        *       )
        *
        * @return 成功返回mock对象arr，失败返回false
     */
    /* ##########################################################*/
    public function genAllObjectMock($conf)
    {
        if(!is_array($conf)) {
            return false;
        }
        $fakeObjArr = array();
        foreach($conf as $class => $funcArr) {
            $fnArr = array_keys($funcArr);
            foreach($fnArr as &$fn) {
                if(strpos($fn, ':')) {
                    $fn = explode(':', $fn);
                    $fn = $fn[1];
                }
            }
            $fnArr = array_unique($fnArr);
            $fakeObj = $this->getMock($class, $fnArr,array(),'',false);
            $i = 0;
            foreach($funcArr as $fn => $mockInfo) {
                $this->deployMock($fakeObj, $fn, $mockInfo, $i);
                $i++;
            }
            $fakeObjArr[$class] = $fakeObj;
        }
        return $fakeObjArr;
    }
 
    /* ##########################################################*/
    /**
        * @brief genEasyObjectMock  快速生成并返回一个mock对象
        *
        * @param $className     类名  只支持单个方法
        * @param $method        方法名
        * @param $retVal        返回值
        * @param $withVal       预期接收值
        *
        * @return NULL
     */
    /* ##########################################################*/
    public function genEasyObjectMock($className, $method, $retVal, $withVal = false)
    {
        if(!is_array($method)) {
            $method = array($method);
        }

        $fakeObj = $this->genObjectMock($className, $method);

        if($withVal !== false) {
            foreach($method as $methodVal) {
		        if(!is_array($retVal) || !isset($retVal[$methodVal])) {
			        $ret=$retVal; 
                } else {
			        $ret=$retVal[$methodVal];
                }

                $fakeObj->expects($this->any())
                        ->method($methodVal)
                        ->with($this->equalTo($withVal))
                        ->will($this->returnValue($ret));
            }
        } else {
            foreach($method as $methodVal) {
                if(!is_array($retVal) || !isset($retVal[$methodVal])) {
                    $ret=$retVal;
                } else {
                    $ret=$retVal[$methodVal];
                }

                $fakeObj->expects($this->any())
                        ->method($methodVal)
                        ->will($this->returnValue($ret));
            }
        }
        return $fakeObj;
    }


    /* ##########################################################*/
    /**
        * @brief genStaticMock 生成类static成员的对应mock对象
        *
        * @param $className     类名
        * @param $args_arr       方法名，必须是array
        *
        * @return mock对象
     */
    /* ##########################################################*/
    public function genStaticMock($className, $func_arr = array())
    {
        $all_func_arr = Conf_StaticClassMap::getMapInfo($className);

        foreach($func_arr as $funcName) {
            $this->assertContains($funcName, $all_func_arr, 
                "[$className] not contain [$funcName], please check Conf_StaticClassMap");
        }

        foreach($all_func_arr as &$funcName) {
            $funcName = "sf:".$funcName;
        }

        $fakeObj = $this->getMock("_bd_StaticMockClass_".$className, $func_arr);

        if(!class_exists($className, false)) {
            $this->evalClass($className, $all_func_arr);
        }

        $obj = new $className;
        $obj->setMock($fakeObj);

        return $fakeObj;
    }


    /* ##########################################################*/
    /**
        * @brief genEasyStaticMock  快速生成并返回一个static mock对象
        *
        * @param $className     类名  只支持单个方法
        * @param $method        方法名
        * @param $retVal        返回值
        * @param $withVal       预期接收值
        *
        * @return NULL
     */
    /* ##########################################################*/
    public function genEasyStaticMock($className, $method, $retVal, $withVal = false)
    {
            if(!is_array($method)) {
              $method = array($method);
            }

            $fakeObj = $this->genStaticMock($className, $method);

            if($withVal !== false) {
                    foreach($method as $methodVal) {
                            $fakeObj->expects($this->any())
                                    ->method($methodVal)
                                    ->with($this->equalTo($withVal))
                                    ->will($this->returnValue($retVal));
                    }
            } else {
                    foreach($method as $methodVal) {
                            $fakeObj->expects($this->any())
                                    ->method($methodVal)
                                    ->will($this->returnValue($retVal));
                    }
            }

            return $fakeObj;
    }

    /* ##########################################################*/
    /**
        * @brief genClassMock 用于解决类似于 new Bd_Db_DBMan 这样的问题
        *
        * @param $className 类名
        * @param $member_arr  成员列表，静态成员 前面必须加上 sv: sf: mv: mf:
        *                   如果不加默认为mf:, mv不需要设置也可以使用
        *
        * @return 返回mock的对象
     */
    /* ##########################################################*/
    public function genClassMock($className, $member_arr = array()) 
    {
        if(empty($member_arr)) {
            //$classMap = ucfirst(PTestInit::getPath('PRODUCT'))."_ClassMap";
            //if(class_exists($classMap)) {
                //$classMapObj = new $classMap;
                $member_arr = Conf_CommonClassMap::getMapInfo($className);
                if(empty($member_arr)){
                    throw new Exception($className." did not config in Conf_CommonClassMap.php");
                }
            //}
        }

        $class_arr = explode(':', $className);
        $baseClassName = "";
        if(count($class_arr) == 2) {
            $className = $class_arr[0];
            $baseClassName = $class_arr[1];
        }
        if(!class_exists($className, false)) {
            $this->evalClass($className, $member_arr, $baseClassName);
        }

        $func_arr = array();
        foreach($member_arr as $memberName) {
            if($pos = strpos($memberName, "sf:") !== false) {
                $func_arr[] = substr($memberName, $pos+2);
            } else if($pos = strpos($memberName, "mf:") !== false) {
                $func_arr[] = substr($memberName, $pos+2);
            } else if($pos = strpos($memberName, "mv:") === false && 
                strpos($memberName, "sv:") === false) {
                    $func_arr[] = $memberName;  
            }   
        }

        $fakeObj = $this->getMock("_bd_ClaassMockClass_".$className, $func_arr);
        $obj = new $className;
        $obj->setMock($fakeObj);

        return $fakeObj;
    }

    /* ##########################################################*/
    /**
        * @brief genFunctionMock 全局函数的mock对象
        *
        * @param $functionName
        *
        * @return mock对象
        */
    /* ##########################################################*/
    public function genFunctionMock($functionName) {
        $fakeObj = $this->getMock("_bd_FunctionMock".$functionName, array($functionName));

        self::$fn2FakeObj[$functionName] = $fakeObj;
        $this->evalFunction($functionName);

        return $fakeObj;
    }

    private function evalFunction($functionName) {
        if(!function_exists($functionName)) {
            $functionDef = "
                function $functionName() {
                    \$fakeObj = Testcase_PTest::\$fn2FakeObj[__FUNCTION__];
                    \$args_arr = func_get_args();
                    return call_user_func_array(array(\$fakeObj, __FUNCTION__), \$args_arr);
                }";

        eval($functionDef);
        }
    }

    private function evalClass($className, $member_arr = array(), $baseClassName = "") {
        $baseClassStr = ( ($baseClassName !== "") ? ('extends '.$baseClassName) : "" );
        $class_head = 
            "class $className $baseClassStr {
                private static \$fakeObj = NULL;
                private static \$_bd_memVar = array();

                public function setMock(\$fakeObj) {
                    self::\$fakeObj = \$fakeObj;
                }";
        $class_func = "";
        foreach($member_arr as $memberName) {
            if($pos = strpos($memberName, "sv:") !== false) {
                $memberName = substr($memberName, $pos+2);
                $class_func = $class_func."
                    public static \$$memberName = NULL;
                ";
            } else if($pos = strpos($memberName, "mv:") !== false) {
                $memberName = substr($memberName, $pos+2);
                $class_func = $class_func."
                    private \$$memberName = NULL;
                ";
            } else if($pos = strpos($memberName, "sf:") !== false) {
                $memberName = substr($memberName, $pos+2);
                $class_func = $class_func."
                    public static function $memberName() {
                        \$args = func_get_args();
                        return call_user_func_array(array(self::\$fakeObj, __FUNCTION__), \$args);
                    }";
            } else if($pos = strpos($memberName, "mf:") !== false) {
                $memberName = substr($memberName, $pos+2);
                $class_func = $class_func."
                    public function $memberName() {
                        \$args = func_get_args();
                        return call_user_func_array(array(self::\$fakeObj, __FUNCTION__), \$args);
                    }";
            } else {
                $class_func = $class_func."
                    public function $memberName() {
                        \$args = func_get_args();
                        return call_user_func_array(array(self::\$fakeObj, __FUNCTION__), \$args);
                    }";
            }
        }

        $class_func = $class_func."
            public function __get(\$name) {
                return self::\$_bd_memVar[\$name];
            }
            public static function setMemVar(\$name, \$var) {
                self::\$_bd_memVar[\$name] = \$var;
            }";

        $class_str = $class_head."\n".$class_func."}";
        eval($class_str); 
    }

    /**
     * @author: chenhao02
     * @param string $className
     * @param string $methods
     * @param string $newClassPre 要mock类的前缀
     * @return mock出来的class的一个实例
     * <pre>
     * $methods:
     * array(
             array(
     *         'name'=>'fun1',  // 表示要重写的方法
     *         'retVal'=>'retval', //表示重写方法返回的值
     *         'isFun'=>'false'; //false，表示直接返回值。
     *       ),
     *       array(
     *         'name'=>'fun2',
     *         'retVal'=>'funmethod',
     *         'isFun'=>'true';  //true表示将retVal当作可执行代码进行执行
     *       ),
     * );
     * 将一个class类进行mock，其原理是在内存复制一份和原class一样的内容，
     * 然后按照methods提供的数组，改掉对应的方法的运行过程。
     * 
     * </pre>
     */
    public static function mockClass ($className, $methods, $newClassPre = null)
    {
        if (empty($className) || empty($methods) ) {
            throw new Exception(
            sprintf("please check the argments:%s,%d,%s!", $className, 
            count($methods)), - 1);
        }
        if( empty($newClassPre))
        {
           $newClassPre = "mock_".rand(0, 10000)."_"; 
        }
        $newClassName = $newClassPre . $className;
        if (! class_exists($className)) {
            throw new Exception(sprintf("class %s not exist!", $className));
        }
        if (! class_exists($newClassName)) {
            $reflectClass = new ReflectionClass($className);
            $fileName = $reflectClass->getFileName();
            $startLine = $reflectClass->getStartLine();
            $endLine = $reflectClass->getEndLine();
            $methodsArray = array();
            foreach ($methods as $method) {
                $reflectionMethod = $reflectClass->getMethod($method['name']);
                $methodStartLine = $reflectionMethod->getStartLine();
                $methodEndLine = $reflectionMethod->getEndLine();
                $methodModify = "";
                $methodModifyArray = Reflection::getModifierNames(
                $reflectionMethod->getModifiers());
                foreach ($methodModifyArray as $value) {
                    $methodModify .= ($value . " ");
                }
                $methodArgmentsArray = $reflectionMethod->getParameters();
                $methodArgments = "";
                foreach ($methodArgmentsArray as $argmentPara) {
                    $methodArgments .= ("$".$argmentPara->getName().",");
                }
                if(!empty($methodArgments))
                    $methodArgments = substr($methodArgments,0,strlen($methodArgments)-1);
                $methodsArray[] = array($methodStartLine, $methodEndLine, 
                $method['name'], $method['retVal'], $methodModify,$methodArgments,$method['isFun']);
            }
            foreach ($methodsArray as $value) {
                $methodStartLines[] = $value[0];
            }
            array_multisort($methodStartLines, SORT_ASC, $methodsArray);
            $currenIndex = 0;
            list ($curMethodStartLine, $curMethodEndLine, $curMethodName, $curMethodReturnValue, $curModify,$curMethodArgs,$isFun) = $methodsArray[$currenIndex];
            $fileLines = file($fileName);
            $src = "";
            for ($i = $startLine - 1; $i < $endLine; $i ++) {
                if ($i == ($curMethodStartLine - 1)) {
                    $curFunContent = $curMethodReturnValue;
                    if(TRUE == $isFun)
                    {
                        $curFunContent = ($curMethodReturnValue."(".$curMethodArgs.")");
                    }
                    $src .= ("    ".$curModify." function " . $curMethodName .
                     "(".$curMethodArgs.")\n {\n return " . $curFunContent . "; \n}\n");
                    $i = ($curMethodEndLine - 1);
                    $currenIndex ++;
                    if ($currenIndex < count($methods)) {
                        list ($curMethodStartLine, $curMethodEndLine, $curMethodName, $curMethodReturnValue) = $methods[$currenIndex];
                    }
                    continue;
                }
                $src .= $fileLines[$i] . "\n";
            }
            $src = preg_replace('/class\s+' . $className . '/', 
            "class $newClassName", $src);
            eval($src);
        }
        return new $newClassName();
    }

   
    private function deployMock(&$fakeObj, $fn, $mockInfo, $i) 
    {
        if(strpos($fn, 'mul') !== false && strpos($fn, ':') !== false) {
            $fna = explode(':', $fn);
            $this->deployFuncMock($fakeObj, $fna[1], $mockInfo, $i);
        } else {
            $this->deployFuncMock($fakeObj, $fn, $mockInfo, false);
        }
    }
    

    private function deployFuncMock(&$fakeObj, $fn, $mockInfo, $n)
    {
        if($n !== false) {
            $n = intval($n);
            $mockStr = "\$fakeObj->expects(\$this->at($n))";
        } else {
            $mockStr = "\$fakeObj->expects(\$this->any())";
        }
        $mockStr .= "->method('$fn')";
        if(isset($mockInfo['params'])) {
            $mockStr .= "->with(";
            $infCheck = array();
            $withVal = $mockInfo['params'];

            for($i=0, $l=count($withVal); $i<$l; $i++) {
                $infCheck[$i] = $this->equalTo($withVal[$i]);
                $mockStr .= "\$infCheck[$i],";
            }
            $mockStr = trim($mockStr, ",");
            $mockStr .= ")";
        }
        $mockStr .= "->will(\$this->returnValue(\$mockInfo['return']));";
        eval($mockStr);
    }

    public function setGlobals($name, $var) 
    {
        $Globals[$name] = $var;
    }

    public function setConst($name, $var)
    {
        if(!defined($name))
        {
            define($name, $var);
        }
    }
}
require_once dirname(__FILE__) . "/../../AutoLoad.class.php";
$loader= new AutoLoad('platform');
$loader->start();
Gj_Conf::setPath(dirname(__FILE__) ."/../ut/conf/");
Gj_LayerProxy::init(Gj_Conf::getConf("layerproxy"));