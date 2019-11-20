<?php
/***************************************************************************
 *
 * Copyright (c) 2015 zybang.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file   Pay.php
 * @author jiangyingjie(jiangyingjie@zybang.com)
 * @date   2015/12/25 16:28:25
 * @brief  支付服务
 *
 **/
class Hk_Service_Pay
{
    /**
     * 分配订单id统一入口
     *
     * @brief 业务线与支付服务交互订单号无需加密，业务线与客户端交互订单号需加密（Hk_Service_Pay::encode）。
     *
     * @return int
     **/
    public static function getOrderId() {
        $objIdAlloc = new Hk_Service_IdAlloc(Hk_Service_IdAlloc::NAME_ORDER);
        $orderId = $objIdAlloc->getIdAlloc();
        if(false === $orderId) {
            Bd_Log::warning("Error:[idalloc error], Detail:[name:order_id]");
            return false;
        }

        return $orderId;
    }

    /**
     * 获取退款请求id
     * @return int
     */
    public static function getRefundRequestNo() {
        $objIdAlloc = new Hk_Service_IdAlloc(Hk_Service_IdAlloc::NAME_REFUND_REQUEST_NO);
        $requestNo = $objIdAlloc->getIdAlloc();
        if(false === $requestNo) {
            Bd_Log::warning("Error:[idalloc error], Detail:[name:refund_request_no]");
            return false;
        }
        return $requestNo;
    }

    /**
     * 获取预支付参数
     *
     * @param array $arrParams 请求参数
     * @return array $ret
     * @example
     * <pre>
     * $arrParams = array(
     *     'payChannel'  => 1, //（必须）1支付宝，2微信支付，3QQ钱包，4微信扫码支付，5支付宝pc支付，6微信公众号支付，7微信触屏h5支付，8苹果内购，9学币支付，如果要获取多个支付渠道用逗号分隔
     *     'productName' => '这是一个商品', //（必须）商品名称
     *     'price'       => 300, //（必须）商品价格，大于0，单位‘分’
     *     'callbackUrl' => '/course/api/paynotify', //（必须）支付成功回调链接
     *     'source'      => 'abc', //（必须）业务线名称（接入时约定）
     *     'secret'      => 'bcd', //（必须）业务线密钥（接入时约定）
     *     'productId'   => 123, //（非必须）商品id
     *     'uid'         => 2109888, //(非必须)，如登录状态无需提供，否则需要提供uid，如payChannel=6，用户在维系公众号内未登录作业帮账号
     *     'openId'      => 'abcde', //仅在payChannel=6时必须，表示微信号的id
     *     'returnUrl'   => 'http://www.zybang.com', //仅在payChannel=5时必须，表示网页支付成功跳转的页面
     *     'transp'      => array( //（非必须）透传信息，回调时此参数json_encode后原样回传
     *         'packid' => 123,
     *         'userid' => 456,
     *     ),
     *     'app'         => 'airclass', //(非必须)，如果不传递，默认是homework(主app)
     *     'cuid'        => 'C656AA0226AF2E2984721E5A9752BDF3|310833520097568',//(非必须)
     * )
     *
     * ！！注意！！：
     * 1. 对于payChannel参数，其中2微信支付、4微信扫码支付、6微信公众号支付、7微信触屏h5支付，微信的支付方式一次请求只能携带一个。如需多个，请分开多次请求此接口；
     * 2. app参数只对端支付有效，主要用来区分主app和一课app。对于扫码，网页等支付此参数无效，由支付系统内部设置；
     * 3. transp参数，主要用来回调使用，用户支付（或后续退款）成功之后，此参数会携带到业务线回调中（回调是transp为json字符串，需要业务方进行json_decode）
     *
     * $ret = array(
     *     'orderId' => 123, //支付订单号
     *     'wxpay'   => '',  //微信支付参数
     *     'alipay'  => '',  //支付宝支付参数
     *     'qqpay'   => '',  //qq钱包支付参数
     *     'pcwxpay' => '',  //微信扫码支付二维码图片地址
     *     'pcalipay' => '', //支付宝网页支付链接
     *     'jsapipay' => '', //微信公众号支付参数
     *     'h5wxpay'  => '', //微信h5支付网页链接
     *     'wxminiapppay' => '', //微信小程序支付网页链接
     *     'zybcoinpay' => '', //学币支付参数
     * )
     * </pre>
     */
    public static function getPrePayInfo($arrParams) {
        $payChannel  = strval($arrParams['payChannel']);
        $productId   = intval($arrParams['productId']);
        $productName = strval($arrParams['productName']);
        $price       = intval($arrParams['price']);
        $callbackUrl = strval($arrParams['callbackUrl']);
        $source      = strval($arrParams['source']);
        $secret      = strval($arrParams['secret']);
        $returnUrl   = strval($arrParams['returnUrl']);
        $uid         = intval($arrParams['uid']);
        $cuid        = trim(strval($arrParams['cuid']));
        $openId      = strval($arrParams['openId']);
        $transp      = is_array($arrParams['transp']) ? $arrParams['transp'] : array();
        $app         = strval($arrParams['app']);
        $appType     = strval($arrParams['appType']);
        $os          = isset($arrParams['os']) ? strval($arrParams['os']) : '';
        if ($app == '') {
            $app = 'homework';
        }
        if(strlen($payChannel) <= 0 || $price <= 0 || strlen($callbackUrl) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0 || !in_array($app, array('homework', 'airclass', 'practice', 'universe', 'fudao'))) {
            Bd_Log::warning("Error:[param error], Detail[payChannel:$payChannel price:$price callbackUrl:$callbackUrl source:$source secret:$secret app:$app]");
            return false;
        }
        $orderId = self::getOrderId();
        if (false === $orderId) {
            Bd_Log::warning("Error:[db error], alloc orderid fail");
            return false;
        }
        $token = self::getToken($orderId, $source, $secret);
        $arrHeader = array(
            'pathinfo' => '/pay/submit/getprepayinfo',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'payChannel'  => $payChannel,
            'orderId'     => $orderId,
            'productId'   => $productId,
            'productName' => $productName,
            'price'       => $price,
            'callbackUrl' => $callbackUrl,
            'source'      => $source,
            'app'         => $app,
            'returnUrl'   => $returnUrl,
            'uid'         => $uid,
            'cuid'        => $cuid,
            'openId'      => $openId,
            'transp'      => json_encode($transp),
            'userIp'      => CLIENT_IP,
            'token'       => $token,
            'appType'     => $appType,
            'os'          => $os,
        );
        $ret = self::requestPay($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 请求退款
     * @param array $arrParams
     * @return array $ret
     * @example
     * <pre>
     * $arrParams = array(
     *     'orderId'      => 123, //(必须)支付订单号
     *     'requestNo'    => 112, //(必须)请求号，用户支付系统去重
     *     'refundPrice'  => 1000, //(非必须)应退金额
     *     'refundFee'    => 2000, //(必须)实退金额
     *     'callbackUrl'  => '/course/api/refundnotify', //(非必须)支付系统退款成功之后，回调业务线地址
     *     'source'       => 'abc', //（必须）业务线名称（接入时约定）
     *     'secret'       => 'bcd', //（必须）业务线密钥（接入时约定）
     *     'refundTransp' => array( //（非必须）透传信息，回调时此参数json_encode后原样回传
     *         'orderId' => 23,
     *         'uid'     => 123,
     *     )
     * );
     * </pre>
     */
    public static function requestRefund($arrParams) {
        $orderId      = intval($arrParams['orderId']);
        $requestNo    = intval($arrParams['requestNo']);
        $refundPrice  = intval($arrParams['refundPrice']);
        $refundFee    = intval($arrParams['refundFee']);
        $callbackUrl  = strval($arrParams['callbackUrl']);
        $source       = strval($arrParams['source']);
        $secret       = strval($arrParams['secret']);
        $refundTransp = isset($arrParams['refundTransp']) ? $arrParams['refundTransp'] : array();
        $courseType   = isset($arrParams['courseType']) ? $arrParams['courseType'] : 0;
        $suggestAmount  = isset($arrParams['suggestAmount']) ? $arrParams['suggestAmount'] : 0;
        $priceAmount    = isset($arrParams['priceAmount']) ? $arrParams['priceAmount'] : 0;
        $discount       = isset($arrParams['discount']) ? $arrParams['discount'] : 0;
        $refundReason   = isset($arrParams['refundReason']) ? strval($arrParams['refundReason']) : "";
        //TODO: requestNo约束加入requestNo不为0判断
        if ($orderId <= 0 || $refundFee <= 0 || strlen($source) == 0 || strlen($secret) == 0 || !is_array($refundTransp)) {
            Bd_Log::warning("Error:[param error], Detail[orderId:$orderId refundFee:$refundFee source:$source secret:$secret refundTransp:$refundTransp]");
            return false;
        }
        $token = self::getToken($orderId, $source, $secret);
        $arrHeader = array(
            'pathinfo' => '/pay/submit/refund',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'orderId'     => $orderId,
            'requestNo'   => $requestNo,
            'refundPrice' => $refundPrice,
            'refundFee'   => $refundFee,
            'callbackUrl' => $callbackUrl,
            'source'      => $source,
            'refundTransp'=> $refundTransp,
            'token'       => strval($token),
            'courseType'  => $courseType,
            'suggestAmount'=> $suggestAmount,
            'priceAmount'  => $priceAmount,
            'discount'     => $discount,
            'refundReason' => $refundReason,
        );
        $ret = self::requestPay($arrHeader, $arrParams);
        return $ret;
    }

     /*
     * 通过订单id获取订单信息
     *
     * @param  int     $orderId      订单id
     * @return mix
     */
    public static function getOrderInfoByOrderId($orderId) {
        if(intval($orderId) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[orderId:$orderId]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/api/getorderinfo',
        );
        $arrParams = array(
            'orderid'     => intval($orderId),
        );

        $ret = self::requestPay($arrHeader, $arrParams);

        return $ret;
    }

    /**
     * 发起退款
     *
     * @param  int     $orderId       订单号
     * @param  int     $refundPrice   应退金额
     * @param  int     $refundFee     实退金额
     * @param  string  $callbackUrl   回调url
     * @param  string  $source        业务线名称（接入时约定）
     * @param  string  $secret        业务线密钥（接入时约定）
     *
     * @return true/false
     */
    public static function refund($orderId, $refundPrice, $refundFee, $callbackUrl, $source, $secret) {
        if(strlen($orderId) <= 0 || strlen($callbackUrl) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[orderId:$orderId callbackUrl:$callbackUrl source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($orderId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/submit/refund',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'orderId'     => strval($orderId),
            'refundPrice' => intval($refundPrice),
            'refundFee'   => intval($refundFee),
            'callbackUrl' => strval($callbackUrl),
            'source'      => strval($source),
            'token'       => strval($token),
        );

        $ret = self::requestPay($arrHeader, $arrParams);

        return $ret;
    }

    /**
     * 检查订单是否已支付
     *
     * @param  string  $orderId 订单号
     * @param  string  $source  业务线名称（接入时约定）
     * @param  string  $secret  业务线密钥（接入时约定）
     *
     * @return true/false
     */
    public static function checkPay($orderId, $source, $secret) {
        if(strlen($orderId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[orderId:$orderId source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($orderId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/submit/checkpay',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'orderId' => strval($orderId),
            'source'  => strval($source),
            'token'   => strval($token),
        );

        $ret = self::requestPay($arrHeader, $arrParams);
        if(isset($ret['result']) && intval($ret['result']) === 1) {
            return true;
        }

        return false;
    }

    /**
     * 检查订单是否已退款
     *
     * @param  string  $orderId 订单号
     * @param  string  $source  业务线名称（接入时约定）
     * @param  string  $secret  业务线密钥（接入时约定）
     *
     * @return true/false
     */
    public static function checkRefund($orderId, $source, $secret) {
        if(strlen($orderId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[orderId:$orderId source:$secret token:$secret]");
            return false;
        }

        $token = self::getToken($orderId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/submit/checkrefund',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'orderId' => strval($orderId),
            'source'  => strval($source),
            'token'   => strval($token),
        );

        $ret = self::requestPay($arrHeader, $arrParams);
        if(isset($ret['result']) && intval($ret['result']) === 1) {
            return true;
        }

        return false;
    }

    /**
     * 对账批量获取订单数据
     *
     * @param  int     $startTime 开始时间
     * @param  int     $stopTime  结束时间
     * @param  string  $source    业务线名称（接入时约定）
     * @param  string  $secret    业务线密钥（接入时约定）
     *
     * @return true/false
     */
    public static function duizhang($startTime, $stopTime, $source, $secret) {
        if(intval($startTime) <= 0 || intval($stopTime) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[startTime:$startTime stopTime:$stopTime source:$secret token:$secret]");
            return false;
        }

        $token = self::getToken($startTime, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/api/duizhang',
            'cookie'   => $_COOKIE,
        );
        $arrParams = array(
            'startTime' => intval($startTime),
            'stopTime'  => intval($stopTime),
            'source'    => strval($source),
            'token'     => strval($token),
        );

        $ret = self::requestPay($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 订单号加密
     *
     * @param  int     $orderId    订单号
     *
     * @return string
     */
    public static function encode($orderId) {
        $orderId = Hk_Util_IdCrypt::encodeCid($orderId);
        return strval($orderId);
    }

    /**
     * 订单号解密
     *
     * @param  string     $orderId    订单号
     *
     * @return int
     */
    public static function decode($orderId) {
        $orderId = Hk_Util_IdCrypt::decodeCid($orderId);
        return intval($orderId);
    }

    /**
     * 获取token（业务线和支付系统交互凭证）
     *
     * @param  string     $orderId    订单号
     * @param  string     $source     产品线名称（接入时约定）
     * @param  string     $secret     产品线密钥（接入时约定）
     *
     * @return string
     */
    public static function getToken($orderId, $source, $secret) {
        return md5($orderId . '_' . $source . '_' . $secret);
    }

    /**
     * 价格归一化
     * @param int $price，单位（分）
     * @param int $accuracy，精度，1精确到角，2精确到元
     *
     * @return 处理后的价格，单位（分）
     */
    public static function priceNorm($price, $accuracy=1) {
        $i = pow(10, $accuracy);
        return intval($price/$i)*$i;
    }

    /**
     * 根据渠道获取量纲和金额
     * @param int $price，单位（分）
     * @param int $payChannel，支付渠道
     * @return array 处理后的类型和价格（unit：0分，1学币；amount：数值）
     */
    public static function getAmountAndUnitByPriceChannel($price, $payChannel) {
        if ($payChannel == 9) {//代币支付
            return array(
                'unit' => 1,
                'amount' => intval($price/10),
            );
        } else {
            return array(
                'unit' => 0,
                'amount' => $price,
            );
        }
    }

    /**
     * 网页授权获取openId
     * @param $code
     * @return mixed
     */
    public static function getOpenId() {
        $WxMiniApp = intval($_GET['WxMiniApp']);
        if($WxMiniApp){//为小程序支付
            $appid = 'wx5bc380911c8ded32';
            $secret= '1d1bac321afd88be23d0d8642d0640cc';
            $code  = $_GET['code'];
            $query = array(
                "appid"      => $appid,
                "secret"     => $secret,
                "js_code"    => $code,
                "grant_type" => "authorization_code",
            );
            $url   = sprintf("%s/jscode2session",'https://api.weixin.qq.com/sns');
            $result  = self::oauthRequest($url,$query);
            if (false === $result) {
                Bd_Log::warning("Error:[getOpenId error], Detail:[url:$url default:".json_encode($result)."]");
                return $result;
            }
        }else{
            $appid  = 'wxabee9a8cf0a9677a';
            $secret = '0bc2c595b30d64a08d33671ba31ea12c';
            if (!isset($_GET['code'])) {
                $url = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $redirect_uri = urlencode($url);
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=wappay#wechat_redirect";
                Header("HTTP GET");
                Header("Location: $url");
                exit();
            }else {
                $code = $_GET['code'];
                $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
                $result = self::curl_post($url);
                if(empty($result)) {
                    Bd_Log::warning("Error:[getOpenId error], Detail:[url:$url]]");
                    return '';
                }

                $result = json_decode($result,true);
            }
        }
        return $result['openid'];
    }
    private static function oauthRequest($url, $query) {
        $reqAddr  = sprintf("%s?%s", $url, http_build_query($query, "", "&"));
        $errmsg   = "";
        $response = self::xcurl($reqAddr, $errmsg);
        if (false === $response) {
            $arg  = array(
                "type"    => 'wxapp',
                "request" => $reqAddr,
                "errmsg"  => $errmsg,
            );
            Bd_Log::addNotice("oauthErr", @json_encode($arg));
            Bd_Log::warning("oauth http request failed", "OAUTH_HTTP_FAILED");
            return false;
        }

        $response = @json_decode($response, true);
        if (isset($response["errcode"])) {
            $arg  = array(
                "type"     => 'wxapp',
                "request"  => $reqAddr,
                "response" => $response,
            );
            Bd_Log::addNotice("oauthErr", @json_encode($arg));
            return false;
        }
        return $response;
    }
    protected static function xcurl($url, &$error = "", array $post = array(), $timeout = 5, $ua = "Mozilla/5.0(X11; Linux x86_64; rv:2.2a1pre) Gecko/20110324 Firefox/4.2a1pre") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_PROXY, "proxy.zuoyebang.com:80");

        if (false !== stripos($url, "https://")) {                # https处理，不校验相关证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        if (!empty($ua)) {
            curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        }
        if (count($post) > 0) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $output     = curl_exec($ch);
        if (false === $output) {
            $error  = curl_error($ch);
        }
        curl_close($ch);
        return $output;
    }

    /**
     * 用户是否有一网通优惠
     * @param int $uid
     * @return boolean
     */
    public static function getUserNetPayDiscountInfo($uid, $cuid) {
        $uid = intval($uid);
        if ($uid <= 0) {
            Bd_Log::warning("param error, uid[$uid]");
            return false;
        }
        $cuid = trim(strval($cuid));
        $arrHeader = array(
            'pathinfo' => '/pay/api/getusernetpaydiscountinfo',
        );
        $arrParams = array(
            'uid' => $uid,
            'cuid' => $cuid,
        );
        return self::requestPay($arrHeader, $arrParams);
    }


    /**
     * 使用代理发送请求
     * @param $url
     * @param null $data
     * @return mixed
     */
    private static function curl_post($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_PROXY, 'proxy.zuoyebang.com');
        curl_setopt($curl, CURLOPT_PROXYPORT, 80);
        curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output =   curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * 请求支付系统
     *
     * @param  mix $arrHeader
     * @param  mix $arrParams
     *
     * @return mix
     */
    private static function requestPay($arrHeader, $arrParams) {
        $ret = ral('zybpay', 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service zybpay connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        $ret = json_decode($ret, true);
        $errno = intval($ret['errNo']);
        $errmsg = strval($ret['errstr']);
        if($errno > 0) {
            Bd_Log::warning("Error:[service zybpay process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return $ret['data'];
    }
}
