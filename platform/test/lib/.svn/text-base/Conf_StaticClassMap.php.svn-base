<?php

/**
 * @file StaticClassMap.php
 * @brief 维护lib类的所有静态函数信息列表，方便genStaticMock获取
 * @author Lu Xuechao (luxuechao@ganji.com)
 * @version 1.0
 * @date 2014-07-16
 */
class Conf_StaticClassMap {
    /* ##########################################################*/
    /**
     * @brief array 保存要mock的lib类的所有静态接口信息,手工维护
     */
    /* ##########################################################*/
    protected static $map_arr = array(
        'dependClass' => array('d1',"d2"),
        'HousingListHelper' => array('getPriceRange'),
        'DBMysqlNamespace' => array('getOne', 'createDBHandle2'),
        'BangNamespace' => array('getUserCooperationYear'),
        "BusinessDistrictNamespace" => array('getBusinessDistrictIdByUrl'),
		"UserPhoneNamespace"  =>array("sendCodeByPhone"),
		 "UserAuthInterface"   => array('authPhone'),
		"HttpNamespace"  => array ('ip2city'),
        'HouseBrokerListInfoInterface' => array('GetBrokerList'),
        'MsPostListApi' => array('getList'),
        'SpamDefenceNamespace' => array('checkSpamKeyword'),
    );

    /* ##########################################################*/
    /**
     * @brief getMapInfo 根据；类名获取静态接口信息
     *
     * @param $className 类名
     *
     * @return 返回类对应的所有静态函数名
     */
    /* ##########################################################*/
    public static function getMapInfo($className) {
        return self::$map_arr[$className];
    }

}

