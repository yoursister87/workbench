<?php

/**
 * @file CommonClassMap.php
 * @brief 维护lib类的所有强依赖类信息列表，方便genClassMock获取
 * @author Lu Xuechao (luxuechao@ganji.com)
 * @version 1.0
 * @date 2014-07-16
 */
class Conf_CommonClassMap 
{
    /* ##########################################################*/
    /**
     * @brief array 保存要mock的lib类的所有接口信息,手工维护
     * 前面必须加上 sv: sf: mv: mf: 如果不加默认为mf:
     */
    /* ##########################################################*/
    protected static $classMap = array(
        'dependClassCommon' => array("mf:c1"), 
  
    );

    /* ##########################################################*/
    /**
     * @brief getMapInfo 根据；类名获取接口信息
     *
     * @param $className 类名
     *
     * @return 返回类对应的所有函数名
     */
    /* ##########################################################*/
    public static function getMapInfo($className)
    {
        return self::$classMap[$className];
    }
}
