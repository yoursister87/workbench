<?php
/***************************************************************************
 *
 * Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file Level.php
 * @author wangdong03(com@baidu.com)
 * @date 2014-2-27
 * @version 1.0
 **/
class Hk_Util_Level {

    /**
     * 用户等级map
     */
    private static $levelMap = array(
        1  => ["min" => 0,  "max" => 50],
        2  => ["min" => 50, "max" => 100],
        3  => ["min" => 100, "max" => 200],
        4  => ["min" => 200, "max" => 350],
        5  => ["min" => 350, "max" => 550],
        6  => ["min" => 550, "max" => 800],
        7  => ["min" => 800, "max" => 1100],
        8  => ["min" => 1100, "max" => 1600],
        9  => ["min" => 1600, "max" => 2300],
        10 => ["min" => 2300, "max" => 3300],
        11 => ["min" => 3300, "max" => 5000],
        12 => ["min" => 5000, "max" => 6900],
        13 => ["min" => 6900, "max" => 10000],
        14 => ["min" => 10000, "max" => 15000],
        15 => ["min" => 15000, "max" => 22000],
        16 => ["min" => 22000, "max" => 32000],
        17 => ["min" => 32000, "max" => 47000],
        18 => ["min" => 47000, "max" => 69000],
        19 => ["min" => 69000, "max" => 100000],
        20 => ["min" => 100000, "max" => 0],
    );

    /**
     * 获取用户等级<br>
     * $good字段被废弃
     *
     * @param int         $exp
     * @param int         $good  被采纳数
     */
    public static function getUserLevel($exp, $good = 0) {
        foreach (self::$levelMap as $levelId => $map) {
            $min = $map["min"];
            $max = $map["max"];
            if ($exp >= $min && $exp < $max) {      # 位于等级区间
                return $levelId;
            } elseif ($exp >= $min && $max === 0) { # 超过最大等级配置
                return $levelId;
            }
        }
        return 1;       # 等级为负值，显示1
    }

    /**
     * 获取用户等级，升级相关数据，可用于进度条展示等<br>
     * $good字段被废弃
     *
     * @param int         $exp   经验
     * @param int         $good  被采纳数
     */
    public static function getLevelData($exp, $good = 0) {
        $userLevelId = self::getUserLevel($exp);
        $levelMap    = self::$levelMap[$userLevelId];
        $levelData   = array(
            'level'                => $userLevelId,
            'startLevelExperience' => $levelMap["min"],     // 升级经验值起点
            'endLevelExperience'   => $levelMap["max"],     // 升级经验值终点
        );
        return $levelData;
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */