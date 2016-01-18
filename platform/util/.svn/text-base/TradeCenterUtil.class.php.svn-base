<?php
/**
 * Created by PhpStorm.
 * User: zhuyaohui
 * Date: 2014/10/27
 * Time: 17:24
 */

class Util_TradeCenterUtil extends Gj_Base_OutLib2Util
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct(){
        $arrMap = $this->getObjMap();
        if(!is_array($arrMap)){
            throw new Gj_Exception(null,"getObjMap is failed");
        }
        if(!class_exists($arrMap['class_name'])){
            Gj\Gj_Autoloader::addClassMap(array($arrMap['class_name']=>$arrMap['file_path']));
        }
        //由于构造函数完成类的加载，而之后只使用TC_Client.class.php的静态方法，所以会出现新建Util_TradeCenterUtil对象（加载所需的类）而没使用该对象的情况。
        //未写单元测试
    }

    /**
     * @codeCoverageIgnore
     */
    public function getObjMap(){
        return array('class_name' => "TC_Client",'file_path' =>CODE_BASE2."/interface/tc2/TC_Client.class.php");
    }
}