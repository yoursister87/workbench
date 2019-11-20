<?php


/**
 * @file PlatPay.php
 * @author guobaoshan@zuoyebang.com
 * @date 2017-04-07
 * @brief 平台通用支付商品和订单查询接口服务
 **/
class Hk_Service_PlatPay {

    /**
     * 支付统一开关，配置使用平台小流量开关控制平台，使用开关小流量平台标示：paySwitch<br>
     * $input入参列表：<br>
     * <code>
     * array(<br>
     *     "ip"       => string,<br>
     *     "cuid"     => string,<br>
     *     "uid"      => int,<br>
     *     "province" => string,<br>
     *     "city"     => string,<br>
     *     "os"       => string,<br>
     *     "vc"       => int,<br>
     *     "vcname"   => string,<br>
     *     "phone"    => string,<br>
     *     "grade"    => int,<br>
     *     "channel"  => string,<br>
     * );
     * </code>
     * 使用黑名单机制，命中小流量代表关，未命中小流量代表开<br>
     * 开：true<br>
     * 关：false
     *
     * @param array        $input
     * @return boolean
     */
    public static function paySwitch($input) {
        $params = Hk_Service_SwStrategy::buildSwInput($input);
        if ("android" === $params["os"]) {      # android永远返回true
            Bd_Log::addNotice("swInput", @json_encode($params));
            Bd_Log::addNotice("paySwitch", "on");
            return true;
        }
        if (empty($params["location"])) {       # 如果无定位，不开第三方支付
            Bd_Log::addNotice("swInput", @json_encode($params));
            Bd_Log::addNotice("paySwitch", "off");
            return false;
        }

        $type = Hk_Service_SwStrategy::STR_TYPE_TEST;
        $mark = "paySwitch";
        $ret  = Hk_Service_SwStrategy::getSwitches($params, $type, array($mark));
        if (1 === $ret[$mark]["sw"]) {
            Bd_Log::addNotice("paySwitch", "off");
            return false;
        }
        Bd_Log::addNotice("paySwitch", "on");
        return true;
    }
}
