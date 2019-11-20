<?php
/***************************************************************************
 *
 * Copyright (c) 2015 zybang.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @file   Coupon.php
 * @author zhangxiao
 * @date   2015/12/25 16:28:25
 * @brief  优惠券服务
 *
 **/
class Hk_Service_Coupon
{
    //获取itemid信息
    public static function getCouponItemInfo($source, $secret, $itemId){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($itemId <=0)) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getcouponiteminfo',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
            'itemId'=>intval($itemId),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }
    //根据itemid获取usercouponlist
    public static function getUserCouponListByItemId($source, $secret, $itemIds){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || strval($itemIds <=0)) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken(0, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponbyitemid',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
            'itemIds'=>$itemIds,
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }


    //优惠券退还
    public static function refundcoupon($source, $secret, $couponId, $uid, $orderId){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($couponId) <= 0 || intval($uid) <= 0 || intval($orderId) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret uid:$uid couponId:$couponId orderId:$orderId]");
            return false;
        }
        $token = self::getToken($couponId.'_'.$uid.'_'.$orderId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/refundusercoupon',
        );
        $arrParams = array(
            'source'    => strval($source),
            'token'     => strval($token),
            'uid'       => intval($uid),
            'couponId'  => intval($couponId),
            'orderId'   => intval($orderId),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    public static function pullCouponByItemId($source, $secret, $itemId){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($itemId <=0)) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/pullcouponbyitemid',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
            'itemId'=>intval($itemId),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }
    //通过uid
    public static function addCouponByItemId($source, $secret, $itemId, $uid){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($itemId) <=0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/addcouponbyitemid',
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
            'itemId'=>intval($itemId),
            'targetUid'=>intval($uid),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    //通过uid itemids 1,2,3
    public static function addCouponByItemIds($source, $secret, $itemIds, $uid){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || strlen($itemIds) <=0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemIds, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/addcouponbyitemids',
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
            'itemIds'=>strval($itemIds),
            'targetUid'=>intval($uid),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }
    //tutormis后台发放优惠券
    public static function pullCouponFromMis($source, $secret, $arrParams){
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($arrParams['uid'] <=0)) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($arrParams['uid'], $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/pullcouponfrommis',
        );
        $arrParams['source'] = strval($source);
        $arrParams['token'] = strval($token);
        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 业务线获取用户对应优惠券
     * @param $source
     * @param $secret
     * @return bool|mix
     */
    public static function getUserCouponBySource($source, $secret){
        if (strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken('', $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponbysource',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'source' => strval($source),
            'token' => strval($token),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }


    /**
     * 获取优惠券信息
     * @param $itemId
     * @param $source
     * @param $secret
     * @return bool|mix
     */
    public static function getUserCouponInfo($uid,$itemId, $source, $secret){
        if (intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponinfo',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'couponId' => intval($itemId),
            'source' => strval($source),
            'token' => strval($token),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 获取优惠券信息
     * @param $itemId
     * @param $source
     * @param $secret
     * @return bool|mix
     */
    public static function getUserItemInfo($uid,$itemId, $source, $secret){
        if (intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponinfobyitem',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'uid' => intval($uid),
            'itemId' => intval($itemId),
            'source' => strval($source),
            'token' => strval($token),
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 根据兑换码发送优惠券
     * @param $code
     * @param $uid
     * @param $cuid
     * @return bool|mix
     */
    public static function pullUserCouponCode($code, $uid, $cuid){
        if (intval($uid) <= 0 || strlen($code) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[uid:$uid code:$code]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/couponsubmit/pullusercouponcode',
            'cookie' => $_COOKIE,
        );
        $arrPara = array(
            'code' => $code,
            'uid'  => $uid,
            'cuid' => $cuid,
        );

        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }

    /**
     * 端外根据兑换码发送优惠券
     * @param $code
     * @param $uid
     * @param $cuid
     * @return bool|mix
     */
    public static function pullWapUserCouponCode($code, $uid, $cuid){
        if (intval($uid) <= 0 || strlen($code) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[uid:$uid code:$code]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/couponsubmit/pullwapusercouponcode',
            'cookie' => $_COOKIE,
        );
        $arrPara = array(
            'code' => $code,
            'uid'  => $uid,
            'cuid' => $cuid,
        );

        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }
    /**
     * 生成优惠券功能
     * @param $code
     * @param $uid
     * @param $cuid
     * @return bool|mix
     */
    public static function addCoupon($extData,$rule,$opUid,$itemName,$description,$maxNum,$leftNum,$unit,$discount,$duration,$grade,$source){
        if (strlen($itemName) <= 0||strlen($description) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[itemName:$itemName description:$description]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/addcoupon',
            'cookie' => $_COOKIE,
        );
        $arrPara = array(
            'extData' => $extData,
            'rule'  => $rule,
            'opUid' => $opUid,
            'itemName'     => $itemName,
            'description'  => $description,
            'maxNum'       => $maxNum,
            'leftNum'      => $leftNum,
            'unit'         => $unit,
            'discount'     => $discount,
            'duration'     => $duration,
            'grade'        => $grade,
            'source'       => $source,
        );

        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }


    /**
     * 端外根据兑换码发送优惠券
     * @param $code
     * @param $uid
     * @param $cuid
     * @return bool|mix
     */
    public static function getCouponList($arrPara){
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getcouponlist',
            'cookie' => $_COOKIE,
        );
        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }


    /**
     * 根据兑换码获取对应的优惠券列表
     * @param $codeName
     * @return bool|mix
     */
    public static function getCouponCodeInfo($codeName){
        if (strlen($codeName) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[code:$codeName]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getcouponcodeinfo',
            'cookie' => $_COOKIE,
        );
        $arrPara = array(
            'codeName' =>$codeName,
        );

        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }

    /**
     * 停用优惠券
     * @param $codeName
     * @return bool|mix
     */
    public static function couponOutage($itemId)
    {
        if (strlen($itemId) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[code:$itemId]");
        }
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/couponoutage',
            'cookie'   => $_COOKIE,
        );
        $arrPara = array(
            'itemId' =>$itemId,
        );
        $ret = self::requestCoupon($arrHeader, $arrPara);
    }
    /*
     * 获取用户领取某个兑换码时间
     * @param $codeName
     * @return bool|mix
     */
    public static function getUserCouponByCode($uid,$codeName){
        if (strlen($codeName) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[code:$codeName]");
            return false;
        }

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponbycode',
            'cookie' => $_COOKIE,
        );
        $arrPara = array(
            'codeName' =>$codeName,
            'uid'      => $uid,
        );

        $ret = self::requestCoupon($arrHeader, $arrPara);
        return $ret;
    }

    /**
     * 使用优惠券
     * @param $itemId
     * @param $source
     * @param $secret
     * @param $orderId
     * @param $productId
     * @param $productType
     * @param $productName
     * @param $porductPrice
     * @return bool
     */
    public static function useCoupon($itemId, $source, $secret, $orderId, $productId, $productType, $productName, $productPrice, $uid = 0){
        if (intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/usecoupon',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'couponId'    => intval($itemId),
            'source'    => strval($source),
            'token'     => strval($token),
            'orderId'   => intval($orderId),
            'productId'     => intval($productId),
            'productType'   => intval($productType),
            'productName'   => strval($productName),
            'productPrice'  => intval($productPrice),
            'uid'       => $uid,
        );
        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }

    /**
     * 优惠券加锁（微信支付场景使用）
     * @param $itemId
     * @param $source
     * @param $secret
     * @return bool
     */
    public static function lockCoupon($itemId, $source, $secret, $orderId){
        if (intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/lockcoupon',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'itemId' => intval($itemId),
            'source' => strval($source),
            'token'  => strval($token),
            'orderId'       => intval($orderId),
        );
        $ret = self::requestCoupon($arrHeader, $arrParams);
    }


    /**
     * 优惠券解锁 (微信支付失败，取消)
     * @param $itemId
     * @param $source
     * @param $secret
     * @return bool
     */
    public static function unLockCoupon($itemId, $source, $secret){
        if (intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }

        $token = self::getToken($itemId, $source, $secret);
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/unlockcoupon',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'couponId' => intval($itemId),
            'source' => strval($source),
            'token' => strval($token),
        );
        $ret = self::requestCoupon($arrHeader, $arrParams);
    }


    /**
     * 获取token（业务线和优惠券系统交互凭证）
     *
     * @param  string     $orderId    订单号
     * @param  string     $source     产品线名称（接入时约定）
     * @param  string     $secret     产品线密钥（接入时约定）
     * @return string
     */
    public static function getToken($itemId, $source, $secret) {
        return md5($itemId . '_' . $source . '_' . $secret);
    }

    /**
     * 请求支付系统优惠券
     *
     * @param  mix $arrHeader
     * @param  mix $arrParams
     *
     * @return mix
     */
    private static function requestCoupon($arrHeader, $arrParams) {
        $ret = ral('zbcoupon', 'POST', $arrParams, 123, $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service zbcoupon connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }


        $ret = json_decode($ret, true);
        $errno = intval($ret['errNo']);
        $errmsg = strval($ret['errstr']);
        if($errno > 0) {
            Bd_Log::warning("Error:[service zbcoupon process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }
        return $ret['data'];
    }

    /**
     * 检查优惠券是否可用
     * @param $rules
     * @param $tradeInfo
     * @return bool
     * ps: 目前支持 price价格 各业务线对应productId 如辅导对应courseId
     */
    public static function checkCouponRule($rules, $tradeInfo){
        if(empty($rules)) {
            return true;
        }
        //规则解析
        foreach($rules as $ruleInfo) {
            foreach($ruleInfo as $key => $rule) {
                if(!isset($tradeInfo[$key])) {
                    continue;
                }
                if(!self::matchOp($rule['op'], $tradeInfo[$key], $rule['v'])){
                    return false;
                }
            }
        }
        return true;
    }



    /**
     * 规则映射
     * @param $op
     * @param $front
     * @param $last
     * @return bool
     * ps：暂时支持 '=' '>' '>=' '<' '<=' 'in' 'notin'
     */
    public static function matchOp($op, $front, $last) {
        switch($op) {
            case 'eq':
                return ($front === $last);
            case 'gt':
                return ($front > $last);
            case 'ge':
                return ($front >= $last);
            case 'lt':
                return ($front < $last);
            case 'le':
                return ($front <= $last);
            case 'in':
                return in_array($front, $last);
            case 'notin':
                return !in_array($front, $last);
            default:
                return true;
        }
    }

    /**
     * 根据uid和status获取某个产品线的优惠券
     * @param $source
     * @param $secret
     * @param $uid
     * @param $status
     * @param int $pn
     * @param int $rn
     * @return bool|mix
     */
    public static function getUserCouponByUidAndStatus($source, $secret, $uid, $status, $pn = 0, $rn = 10) {
        if (strlen($source) <= 0 || strlen($secret) <= 0 || intval($uid) <=0 || intval($status) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret uid:$uid status:$status]");
            return false;
        }

        $token = self::getToken(0, $source, $secret);

        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponbyuidandstatus',
        );
        $arrParams = array(
            'source'   => strval($source),
            'token'    => strval($token),
            'itemId'   => 0,
            'targetUid'=>intval($uid),
            'status'   => $status,
            'pn'        => $pn,
            'rn'        => $rn,
        );

        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }
    
    /**
     * 获取用户优惠券信息
     * @param $uid
     * @param $itemId
     * @param $source
     * @param $secret
     * @return bool|mix
     */
    public static function getUserCouponByUidNCouponId($uid,$itemId, $source, $secret){
        if (intval($uid) <= 0 || intval($itemId) <= 0 || strlen($source) <= 0 || strlen($secret) <= 0) {
            Bd_Log::warning("Error:[param error], Detail[source:$source secret:$secret]");
            return false;
        }
        
        $token = self::getToken($itemId, $source, $secret);
        
        $arrHeader = array(
            'pathinfo' => '/pay/couponapi/getusercouponbyuidncouponid',
            'cookie' => $_COOKIE,
        );
        $arrParams = array(
            'uid'      => $uid,
            'couponId' => intval($itemId),
            'source'   => strval($source),
            'token'    => strval($token),
        );
        
        $ret = self::requestCoupon($arrHeader, $arrParams);
        return $ret;
    }
}
