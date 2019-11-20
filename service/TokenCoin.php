<?php

/**
 * @file TokenCoin.php
 * @author guobaoshan@zuoyebang.com
 * @date 2017-04-21
 * @brief 用户代币查询/花费/退款RPC服务
 * 
 *
 **/

class Hk_Service_TokenCoin {
    
    // 提供代币服务的service
    const COIN_SERVICE  = "charge";

    // 代币rpc请求的token secret
    const COIN_SECRET   = "zybang-coin-2017";

    /**
     * @brief 查询用户代币余额
     * @param $uid
     * @return int 
     *
     **/
    public static function getUserTokenCoin($uid) {
        if ($uid <= 0) {
            Bd_Log::warning("Error:[param error], Detail[uid:$uid]");
            return false;
        }
        $arrHeader  = array(
            'pathinfo'  => '/napi/pay/tokencoin',
        );
        $arrParams  = array(
            'act'   => 'query',
            'uid'   => $uid,
        );
        $ret    = self::talkCoinRpc($arrHeader, $arrParams);
        if (false === $ret || -1 === $ret) {
            return false;
        }
        return intval($ret['coin']);
    }

    /**
     * @brief 订单退款，退还用户代币
     * @param $source 业务
     * @param $uid
     * @param $coin 退还代币值
     * @param $ext 需记录的扩展数据，orderId和refundId必须
     * @return bool | -1
     *
     **/
    public static function incrUserTokenCoin($source, $uid, $coin, array $ext) {
        if (empty($source) || $uid <= 0 || $coin <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source uid:$uid coin:$coin]");
            return false;
        }
        if (!isset($ext['orderId']) || $ext['orderId'] <= 0) {
            Bd_Log::warning("Error:[param error], Detail[ext:".json_encode($ext)."]");
            return false;
        }
        if (!isset($ext['refundId']) || $ext['refundId'] <= 0) {
            Bd_Log::warning("Error:[param error], Detail[ext:".json_encode($ext)."]");
            return false;
        }
        $token      = self::getToken($source, $uid);
        $arrHeader  = array(
            'pathinfo'  => '/napi/pay/tokencoin',
        );
        $arrParams  = array(
            'act'       => 'refund',
            'uid'       => $uid,
            'source'    => $source,
            'coin'      => $coin,
            '_token'    => $token,
            'ext'       => json_encode($ext),
        );
        $ret    = self::talkCoinRpc($arrHeader, $arrParams);
        if (false === $ret) {
            return false;
        } else if (-1 === $ret) {
            return -1;
        }
        return true;
    }

    /**
     * @brief 订单支付，消费用户代币
     * @param $source 业务
     * @param $uid
     * @param $coin 消费代币值
     * @param $ext 需记录的扩展数据，orderId必须
     * @return bool | -1
     *
     **/
    public static function decrUserTokenCoin($source, $uid, $coin, array $ext) {
        if (empty($source) || $uid <= 0 || $coin <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source uid:$uid coin:$coin]");
            return false;
        }
        if (!isset($ext['orderId']) || $ext['orderId'] <= 0) {
            Bd_Log::warning("Error:[param error], Detail[ext:".json_encode($ext)."]");
            return false;
        }
        $token      = self::getToken($source, $uid);
        $arrHeader  = array(
            'pathinfo'  => '/napi/pay/tokencoin',
        );
        $arrParams  = array(
            'act'       => 'buy',
            'uid'       => $uid,
            'source'    => $source,
            'coin'      => $coin,
            '_token'    => $token,
            'ext'       => json_encode($ext),
        );
        $ret    = self::talkCoinRpc($arrHeader, $arrParams);
        if (false === $ret) {
            return false;
        } else if (-1 === $ret) {
            return -1;
        }
        return true;
    }

    // 生成token
    public static function getToken($source, $uid) {
        return md5($source."_".$uid."_".self::COIN_SECRET);
    }

    /**
     * ral调用
     * @return 
     * -1: 调用服务异常，结果未知，需要重试
     * false: 操作结果失败
     * array: 操作结果成功
     *
     **/
    private static function talkCoinRpc($arrHeader, $arrParams) {
        $ret    = ral(self::COIN_SERVICE, 'POST', $arrParams, 123, $arrHeader);
        if (false === $ret) {
            // ral调用失败，返回-1，代表服务异常，需要重试
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service ".self::COIN_SERVICE." connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return -1;
        }

        $ret    = json_decode($ret, true);
        $errno  = intval($ret['errNo']);
        $errmsg = strval($ret['errstr']);
        if ($errno > 0) {
            Bd_Log::warning("Error:[service ".self::COIN_SERVICE." process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return $ret['data'];
    }
}

?>
