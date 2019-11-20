<?php


/**
 * 封装后的小流量逻辑
 *
 * @see Hk_Ds_MisStrategy_Strategy
 *
 * @since 1.2 2018-10-31 参数统一通过saf获取，saf如果无法获取则通过参数传递
 * @since 1.1 2018-10-26 更简洁调用，策略参数可以 通过cgi获取（saf支持）
 * @since 1.0 2017-06-01 初始化
 *
 * @filesource hk/service/SwStrategy.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.1
 * @date    2018-10-26
 */
class Hk_Service_SwStrategy extends Hk_Ds_MisStrategy_Const {

    const SW_ON  = 1;
    const SW_OFF = 0;

    /**
     * 通过透传参数和上下文生成小流量开关参数<br>
     * 如果有透传参数，直接使用透传参数，如果无，则使用saf获取的上下文参数
     *
     * @return array
     */
    public static function buildSwInput($ctx = array()) {
        $reqParam = Saf_SmartMain::getCgi()["request_param"];
        $userInfo = Saf_SmartMain::getUserInfo();

        $p = array(         # 初始化参数
            "ip"       => Hk_Util_Ip::getClientIp(),
            "uid"      => intval($userInfo["uid"]),
            "phone"    => strval($userInfo["phone"]),
            "city"     => strval($reqParam["city"]),
            "province" => strval($reqParam["province"]),
            "vc"       => intval($reqParam["vc"]),
            "vcname"   => strval($reqParam["vcname"]),
            "grade"    => intval($reqParam["grade"]),
            "os"       => strval($reqParam["os"]),
            "cuid"     => strval($reqParam["cuid"]),
            "channel"  => strval($reqParam["channel"]),
            "device"   => strval($reqParam["device"]),
        );
        foreach ($p as $name => $val) {         # 使用传递上下文覆盖自动获取的参数
            if (isset($ctx[$name])) {
                $p[$name] = $ctx[$name];
            }
        }
        $swInput = array(
            "ip"     => $p["ip"],
            "uid"    => $p["uid"],
            "cuid"   => $p["cuid"],
            "grade"  => $p["grade"],
            "model"  => $p["device"],
            "channel"   => $p["channel"],
            "os_type"   => $p["os"],
            "telephone" => $p["phone"],
            "version"   => empty($p["vc"]) && empty($p["vcname"]) ? [] : ["vc" => $p["vc"], "vcname" => $p["vcname"]],
            "location"  => empty($p["province"]) && empty($p["city"]) ? [] : ["province" => $p["province"], "city" => $p["city"]],
        );
        return $swInput;
    }

    /**
     * 生成小流量通用参数，老版本，不建议再使用
     *
     * @deprecated
     *
     * @param array        $params
     * @return array
     */
    public static function buildSwitchParam($params = array()) {
        if (!isset($params["location"])) {
            $city     = isset($params["city"])     ? $params["city"]     : "";
            $province = isset($params["province"]) ? $params["province"] : "";
            $location = empty($province) && empty($city) ? [] : ["province" => $province, "city" => $city];
        } else {
            $location = $params["location"];
        }

        $ip      = Hk_Util_Ip::getClientIp();
        $vc      = isset($params["vc"])     ? $params["vc"]     : 0;
        $vcname  = isset($params["vcname"]) ? $params["vcname"] : "";
        $version = empty($vc) && empty($vcname) ? [] : ["vc" => $vc, "vcname" => $vcname];
        if (empty($params["os"])) {
            $os  = isset($params["appType"]) ? $params["appType"] : "";
        } else {
            $os  = $params["os"];
        }
        $swInput = array(
            "ip"        => $ip,
            "uid"       => isset($params["uid"])   ? intval($params["uid"]) : 0,
            "cuid"      => isset($params["cuid"])  ? strval($params["cuid"])  : "",
            "grade"     => isset($params["grade"]) ? intval($params["grade"]) : 255,
            "telephone" => isset($params["phone"]) ? strval($params["phone"]) : "",
            "channel"   => isset($params["channel"]) ? strval($params["channel"]) : "",
            "os_type"   => $os,
            "version"   => $version,
            "location"  => $location,
        );
        Bd_Log::addNotice("swInput", @json_encode($swInput));
        return $swInput;
    }

    /**
     * 获取指定类型的开关状态，返回数据结构：<br>
     * <code>
     * array(
     *     "mark" => array(
     *         "sw"    => 0/1,         # 开关状态
     *         "fSync" => 0/1,         # 是否强制同步服务端开关
     *     ),
     *     ...
     * )
     * </code>
     *
     * @see Hk_Ds_MisStrategy_Strategy
     *
     * @param array       $params
     * @param int         $type
     * @param array       $marks
     * @return array
     */
    public static function getSwitches($params, $type, array $marks = array()) {
        return Hk_Ds_MisStrategy_Strategy::getSwitches($params, $type, $marks);
    }
}

/* vim: set expandtab ts=4 sw=4 sts=4 tw=100: */
