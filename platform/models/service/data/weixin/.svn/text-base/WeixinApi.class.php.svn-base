<?php

/**
 * @package
 * @subpackage
 * @brief                $微信官方接口$
 * @file                 WeixinApi.class.php
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Weixin_WeixinApi
{
    protected $wxObj;
    protected $openid;
    protected $userSubscribeCate;
    protected $option = array(
        'token' => 'ganji', //填写你设定的key
        'encodingaeskey' => 'encodingaeskey', //填写加密用的EncodingAESKey
        'debug' => true,
        'logcallback' => 'logcallback',
    );

    const DEFAULT_MAJORCATEGORY = 1;
    const DEFAULT_DISTANCE = 3000;
    const DAY_TIME_SECONDS = 86400;
    protected static $SIM_IP_ADDR = '10.1.8.187';
    protected static $QA_IP_ADDR = '10.3.255.194';
    protected static $WEIXIN_WAP_HOST = 'http://fangweixin.3g.ganji.com';
    protected static $PERMANENT_SCENE_IDS = array(100, 101, 102,103);
    protected static $MAJOR_CATEGORY_MAX = array(
        1 => 1,
        3 => 1,
        5 => 5,
    );
    protected static $STATIC_OPCODE = array(
        '取消二手房订阅' => "cancelSubscription,5",
        '清空二手房收藏' => "clearCollection, 5",
        '取消租房订阅'  => "cancelSubscription,1",
        '清空租房收藏'  => "clearCollection, 1",
		'我的收藏'  => "myCollectionCountNum,all",
    );

    protected static $AUTO_RESPONSE_BY_TIME = array(
        'morning' => array(
            'rent_sub' => '1##你等我啊！晚上八点，不见不散！要不我先给你点新闻看看？##getOneArtilceByRand',
            'sell_sub' => '1##你等我啊！中午一点，不见不散！要不我先给你点新闻看看？##getOneArtilceByRand',
            'rent_sell_sub' => '1##别着急啊，我还在准备房源，时间到了我就给你推！要不先给你点新闻看看？##getOneArtilceByRand',
            'null_sub' => "1##要啥？要啥我都有！<a href='http://fangweixin.3g.ganji.com/local_fang1/?ifid=gjwx_user_why1&ca_s=other_weixin&ca_n=menu&_gjassid=fV0x40R2Gk9V'>点这里点这里！</a>。##getOneArtilceByRand",
        ),
        'afternoon' => array(
            'rent_sub' => '1##你等我啊！晚上八点，不见不散！要不我先给你点新闻看看？##getOneArtilceByRand',
            'sell_sub' => '1##收到！有更新我会继续给你推送的！##getOneArtilceByRand',
            'rent_sell_sub' => '1##收到！有更新我会继续给你推送的！##getOneArtilceByRand',
            'null_sub' => "1##要啥？要啥我都有！<a href='http://fangweixin.3g.ganji.com/local_fang1/?ifid=gjwx_user_why1&ca_s=other_weixin&ca_n=menu&_gjassid=fV0x40R2Gk9V'>点这里点这里！</a>。##getOneArtilceByRand",
        ),
        'night' => array(
            'rent_sub' => 'getSomeMore',
            'sell_sub' => '1##收到！有更新我会继续给你推送的！##getOneArtilceByRand',
            'rent_sell_sub' => 'getSomeMore',
            'null_sub' => "1##要啥？要啥我都有！<a href='http://fangweixin.3g.ganji.com/local_fang1/?ifid=gjwx_user_why1&ca_s=other_weixin&ca_n=menu&_gjassid=fV0x40R2Gk9V'>点这里点这里！</a>。##getOneArtilceByRand",
        ),
    );
    protected $imageServer = 'http://image.ganjistatic1.com/';
    protected $domain = 'http://3g.ganji.com/';

    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * @codeCoverageIgnore
     * @param $method
     * @param $params
     * @return mixed
     */
    public function __call($method, $params)
    {

        return $this->wxObj->$method($params);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        if ($_GET['env'] == "web6" || $_SERVER['SERVER_ADDR'] == self::$SIM_IP_ADDR) {
            //web6 测试环境
            $this->option['appid'] = 'wxa544191e1daeda0c'; //填写高级调用功能的app id
            $this->option['appsecret'] = 'c5ce0b59fc34a60e1a4315d4ec1acfb4'; //填写高级调用功能的密钥

        }else if($_GET['env'] == 'qa' || $_SERVER['SERVER_ADDR'] == self::$QA_IP_ADDR){
            $this->option['appid'] = 'wx54d826c917f2e429';
            $this->option['appsecret'] = '4ab5dcfeab6192fc9c8a82c00de8d396';
        }else{
            // 线上环境
            $this->option['appid'] = 'wx03664bcafc1ed360'; //填写高级调用功能的app id
            $this->option['appsecret'] = '869a958f7a24a4bfefab9f67e3271476'; //填写高级调用功能的密钥
        }
        //$this->wxObj = Gj_LayerProxy::getProxy("Gj_Fang_Api_Platform_WeixinApi", $this->option);
        $this->wxObj = new Gj_Fang_Api_Platform_WeixinApi($this->option);
        $this->objWeixinSubscribe = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinSubscribe');
        //$cacheClient = Gj_LayerProxy::getProxy('Gj_Cache_Client_MemcacheClient');
        $cacheClient = new Gj_Cache_Client_MemcacheClient();
        $this->cache = $cacheClient->create();
    }

    /**
     * @codeCoverageIgnore
     * @param string $openid
     */
    public function setOpenid($openid = '')
    {
        $this->openid = $openid;
    }

    /**
     * @codeCoverageIgnore
     * @return mixed
     */
    public function reply()
    {
        $daoUserActive = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinUserActive');
        $ret = $daoUserActive->updateActiveTime($this->openid);
        //$this->wxObj->logcallback(print_r( $this, true) );

        return $this->wxObj->reply(null, true, "array");
    }

    /**
     * 关注公众号，区分带码关注  和 不带码关注 , 同时添加用户信息到用户表
     * @return $this
     */
    public function getSubscribeReply()
    {
        $welcome = "YES！租房买房就找赶集小房！";

        $lead_welcome = "想租房，找小房！ \n\n把想租房的大致位置坐标发给我，马上为你推荐周围好房子。
            \n<a href=\"http://mp.weixin.qq.com/s?__biz=MzA4OTA1Njc3Ng==&mid=208471109&idx=1&sn=b447eff2518f846ec046174e41d8a919 \">（如何发送位置坐标？请点这里。）</a>";

        //添加用户信息到用户表
        $this->addUserInfo();

        $scene_id = $this->wxObj->getRevSceneId();
        if ($scene_id) {
            //发送客服接口
            if(in_array($scene_id, self::$PERMANENT_SCENE_IDS)){
                return $this->specialPermanentQRCodeReply($scene_id, true);
            }
                       //$this->wxObj->logcallback(print_r($ret, true));

            $this->redisQRCodeObj = Gj_LayerProxy::getProxy('Dao_Redis_Weixin_QRCode');
            //$this->wxObj->logcallback("关注后 获取redis dao对象：" . json_encode($this->redisQRCodeObj));
            $QRContent = $this->redisQRCodeObj->getQRContentBySceneId($scene_id);
            //$this->wxObj->logcallback("关注后 通过sence id {$scene_id} 获取对应的数据：" . $QRContent . json_encode($this->redisQRCodeObj));
            $QRContent = json_decode($QRContent, true);

            //$this->wxObj->logcallback(print_r(array("对结果进行解码：", $QRContent), true));

            if ($QRContent['event_type'] == "collection" && $QRContent['params_str']) {
                $news = $this->getPostInfoBySceneInfo($QRContent);
                if( strpos( $news[0]['Url'], 'fang1') || strpos($news[0]['Url'], 'fang3')){
                    $welcome = $lead_welcome;
                }
            } elseif ($QRContent['event_type'] == "subscribe_selection" && $QRContent['params_str']) {
                $welcome = $lead_welcome;
                $this->getSubscribeInfoBySceneInfo($QRContent);
            }elseif ($QRContent['event_type'] == "xiaoquCollection" && $QRContent['params_str']) {
				$this->getXiaoQuInfoBySceneInfo($QRContent);
			}elseif($QRContent['event_type'] == "sell_subscribe" && $QRContent['params_str']){
                $this->getSellSubscribeInfoBySceneInfo($QRContent);
            }else{
                $welcome = $lead_welcome;
            }
            
            $info = array(
                "touser" => $this->wxObj->getRevFrom(),
                "msgtype" => "text",
                "text" => array(
                    "content" => $welcome,
                ),
            );

            //$this->wxObj->logcallback("关注后发送 客服接口 内容：" . json_encode($info));
            $ret = $this->wxObj->sendCustomMessage($info);


            if ($news) {
                $this->wxObj->news($news);
            } else {
                //$this->wxObj->text("未知错误，请重新扫描");
            }
        } else {
            $this->wxObj->text($lead_welcome);
        }

        return $this;
    }

    public function addUserInfo()
    {
        $cache_key = 'weixin_fang_userinfo_' . $this->openid;

        $userInfo = $this->cache->read($cache_key);
        if ($userInfo) {
            return true;
        } else {
            $daoUser = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinUser');
            $userInfo = $this->wxObj->getUserInfo($this->openid);
            //如果不存在
            if (!$daoUser->existUser($this->openid)) {
                $this->wxObj->logcallback(print_r(array("用户信息：", $userInfo), true));
                $daoUser->addUser($userInfo);
            } else {
                $userInfo['subscribe_status'] = 1;
                $daoUser->updateUser($userInfo);
            }

            //cache内容 只需要记录状态就好，节省空间 ， 7天有效期
            $this->cache->write($cache_key, 1, 3600 * 24 * 7);
        }
    }

    public function getUnsubscribeReply()
    {
        $cache_key = 'weixin_fang_userinfo_' . $this->openid;
        $userInfo = array(
            "openid" => $this->openid,
            "subscribe_status" => 0
        );
        $daoUser = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinUser');
        $daoUser->updateUser($userInfo);

        $this->cache->delete($cache_key);

        $this->wxObj->logcallback("取消订阅 事件getUnsubscribeReply");
        return $this;
    }

    /**
     * @brief handle special permanent QRcode
     * , while $unSubscribed is true, push the openid into the qrcode_queue
     * @param $scene_id
     * @param bool $unSubscribed
     * @return $this
     */
    public function specialPermanentQRCodeReply($scene_id, $unSubscribed = false){

        $pCodeRedisObj = Gj_LayerProxy::getProxy("Dao_Redis_Weixin_PermanentQRcodeCount");
        $scene_id_count_key = md5("xiaofang_pcode_scan_count".$scene_id.date("Y-m-d"));
        $pCodeRedisObj->incrValueByKey($scene_id_count_key);

        if($unSubscribed){
            $scene_id_openid_queue_key = md5("xiaofang_pcode_scan_queue".$scene_id.date("Y-m-d"));
            $pCodeRedisObj->pushValueToQueueByKey($scene_id_openid_queue_key, $this->openid);
        }

        if($scene_id == 100){
            $text = "别着急！\n把你要租房的位置发来，马上给你找！<a href=\"http://mp.weixin.qq.com/s?__biz=MzA4OTA1Njc3Ng==&mid=208471109&idx=1&sn=b447eff2518f846ec046174e41d8a919 \">（正确发送位置坐标，点这里）</a>";
            $this->wxObj->text($text);
        }else if($scene_id == 101){
            $text = "点击底部菜单“我要租房（或买房）”进入订阅，按照提示输入条件，然后就等着我给你推荐好房子吧！ ";
            $this->wxObj->text($text);
        }else if($scene_id == 102){
            $news[] = array(
                "Title" => "这么收藏房子，很方便对不对！",
                "Description" => "价格：8888元/月 \n户型：2室1厅1卫-整租-88.00㎡\n楼层：8/18\n小区：赶集小区\n位置：赶集街道888号\n电话：18588888888【小房丨经纪人】",
                "PicUrl" => "http://image.ganjistatic1.com/gjfs11/M0A/9A/1E/CgEHK1U96gDE1r9DAACqNFTmYJ0603_320-200c_6-0.jpg",
                "Url" => self::$WEIXIN_WAP_HOST."/bj_fang1/1589557166x?ifid=railway&ca_s=other_weixin&ca_n=other",
            );
            $this->wxObj->news($news);
            $info = array(
                "touser" => $this->openid,
                "msgtype" => "text",
                "text" => array(
                    "content" => "留意网页上的二维码，轻松一扫，1秒收藏！ ",
                ),
            );

            $this->wxObj->sendCustomMessage( $info );
        }else if ($scene_id == 103){
			$text = "想租房，找小房！ \n\n把想租房的大致位置坐标发给我，马上为你推荐周围好房子。
            \n<a href=\"http://mp.weixin.qq.com/s?__biz=MzA4OTA1Njc3Ng==&mid=208471109&idx=1&sn=b447eff2518f846ec046174e41d8a919 \">（如何发送位置坐标？请点这里。）</a>";
			$this->wxObj->text($text);
		}
        return $this;
    }

    /**
     * 通过扫描 带key的二维码
     * @return $this
     */
    public function getScanReply()
    {
        $event = $this->wxObj->getRevEvent();
        if ($event['key']) {
            $scene_id = $event['key'];
            $this->getInfoBySceneId($scene_id);
        }
        //$this->wxObj->text("扫描 事件getScanReply");
        return $this;
    }

    public function getInfoBySceneId($scene_id = 0)
    {
        //scene_id小等于10000的二维码留为永久二维码用来特殊用途
        if(!empty($scene_id) && (int)$scene_id < 10001) {
            //100~120暂且留给房产会的二维码
            if(in_array($scene_id, self::$PERMANENT_SCENE_IDS)){
                return $this->specialPermanentQRCodeReply($scene_id);
            }
            if($scene_id >= 100 && $scene_id < 120) {
                $msg = "hi 你好，这次测试用的scene_id ：$scene_id";
                $this->wxObj->text($msg);
                    $this->wxObj->logcallback("limitQRcode sence id 获取对应的数据：" . $scene_id);
                return $this;
            }
            //return $this->getLimitQRcodeBySceneInfo($scene_id);
        }
        $this->redisQRCodeObj = Gj_LayerProxy::getProxy('Dao_Redis_Weixin_QRCode');
        $QRContent = $this->redisQRCodeObj->getQRContentBySceneId($scene_id);
        $this->wxObj->logcallback("关注后 通过sence id 获取对应的数据：" . $QRContent . json_encode($this->redisQRCodeObj));
        $QRContent = json_decode($QRContent, true);

        //扫永久二微码
        //永久码时， 文案变成 买房租房
        //临时码 ， 欢迎语文案为 租房买房
        if (empty($QRContent)) {
            $welcome = "YES！买房租房就找赶集小房！";
            $this->wxObj->text($welcome);
            return $this;
        }

        if ($QRContent['event_type'] == "subscribe_selection" && $QRContent['params_str']) {
            return $this->getSubscribeInfoBySceneInfo($QRContent);
        } elseif ($QRContent['event_type'] == "collection" && $QRContent['params_str']) {
            return $this->getPostInfoBySceneInfo($QRContent);
        } elseif ($QRContent['event_type'] == "xiaoquCollection" && $QRContent['params_str']) {
            return $this->getXiaoQuInfoBySceneInfo($QRContent);
        }else if($QRContent['event_type'] == "sell_subscribe" && $QRContent['params_str']) {
            return $this->getSellSubscribeInfoBySceneInfo($QRContent);
        }

    }

    /**
     * @brief 根据二维码获取FANG5内容
     * @param array $QRContent
     */
    public function getSellSubscribeInfoBySceneInfo($QRContent = array()){
        if ($QRContent['event_type'] == "sell_subscribe" && $QRContent['params_str']) {
            $params = json_decode($QRContent['params_str'], true);
        } else {
            return $this;
        }
        $params['subType'] = 5;
        $params['major_category'] = 5;
        $this->addSubscriptionByScanInfo($params, $params['major_category']);
        return $this;
    }

    /**
     * 订阅 或 修改订阅
     * @param array $QRContent
     * @return $this|array
     */
    public function getSubscribeInfoBySceneInfo($QRContent = array())
    {
        $this->wxObj->logcallback(print_r($QRContent, true)); //die;
        if ($QRContent['event_type'] == "subscribe_selection" && $QRContent['params_str']) {
            $params = json_decode($QRContent['params_str'], true);
            $this->wxObj->logcallback(print_r($params, true));
        } else {
            return $this;
        }

        $subscribeConditions = $this->getSubscribeConditions();

        $params['openid'] = $this->openid;
        if ($subscribeConditions['conditions']) {
            //更新
            $subscribeCondition = $this->objWeixinSubscribe->updateSubscribeConditionsByOpenid($params);
            $this->wxObj->logcallback(print_r($subscribeCondition, true));
            if ($subscribeCondition['errorno'] == 0 || $subscribeCondition['errorno'] == 1003) { //1003 重复订阅
                $this->sendChineseSubcriptionInfo($params, $params['major_category']);
            }
        } else {
            //添加
            $this->addSubscriptionByScanInfo($params, $params['major_category']);

        }
        return $this;
    }


    protected function sendChineseSubcriptionInfo($conditions = array(), $major_category = 1)
    {
        $chineseNameInfo = $major_category == 5 ? $this->objWeixinSubscribe->getSellSubscribeChineseConditions($conditions):
            $this->objWeixinSubscribe->getChineseNameByConditions($conditions, $major_category);

        if ($chineseNameInfo['errorno'] == 0) {
            $timeStr = $major_category == 5? "你订阅了以下条件二手房\n\n".$chineseNameInfo['data'] ."\n每天中午1点，我们会给你推送最新房源。 \n查看、修改或取消订阅，请在底部菜单“我要买房”-“订阅”中进行操作。"
                : "你订阅了以下条件出租房\n\n".$chineseNameInfo['data']."\n每天晚上9点，我们会给你推送最新房源。 \n查看、修改或取消订阅，请在底部菜单“我要租房”-“订阅”中进行操作。 ";
            $this->wxObj->text($timeStr);
        }else {
            $this->wxObj->text("订阅失败 ， 请重新扫码订阅。");
        }
        return $this;
    }

    /**
     * 通过QR码内容，返回小区信息
     * @param array $QRContent
     * @return $this | array
     */
    public function getXiaoQuInfoBySceneInfo($QRContent = array()){

        if ($QRContent['event_type'] == "xiaoquCollection" && $QRContent['params_str']) {
            $params = json_decode($QRContent['params_str'], true);
            $this->wxObj->logcallback(print_r($params, true));
        } else {
            return $this;
        }

        $xiaoquCollection= Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollection');

        $xiaoquInfo = $xiaoquCollection->getXiaoquInfoById($params['xiaoquId'], $this->openid);

        if ($xiaoquInfo['errorno'] == 0 || $xiaoquInfo['errorno'] == '1008') {
            $xiaoquInfo = $xiaoquInfo['data'];
            $news = $this->formatXiaoquInfo($xiaoquInfo);
            $this->wxObj->news($news);
        /*
        }else if( $postInfo['errorno'] == '1008' ){
            $news[] = array(
                "Title" => "亲，您已经收藏过了哦",
                "Description"   => "看看其它小区吧！",
                "PicUrl" =>"",
                "Url"   => "",
            );
        */
        }
		//增加二手房参数传递
		$xiaoqu_data = array();
		$xiaoqu_data['c']['domain'] =$xiaoquInfo['pinyin'];//domain
		$xiaoqu_data['c']['x_p_y'] =$xiaoquInfo['xiaoqu_pinyin'];//xiaoqu_pinyin
		$xiaoqu_data['c']['p_ce'] ='';//price
		$xiaoqu_data['c']['d_id'] = $xiaoquInfo['district_id'];//district_id
		$xiaoqu_data['c']['s_id'] = $xiaoquInfo['street_id'];//street_id
		$xiaoqu_data['c']['d_name'] = $xiaoquInfo['district_name'];//district_name
		$xiaoqu_data['c']['s_name'] = $xiaoquInfo['street_name'];//district_name
		$xiaoqu_data['c_c']['c_name'] = $xiaoquInfo['city']; //chineseConditions-city_name
		$xiaoqu_data['c_c']['xq_name'] = $xiaoquInfo['name']; //chineseConditions-xiaoqu_name
		
		$xiaoqu_data  = base64_encode(json_encode($xiaoqu_data));
        $rent_text = self::$WEIXIN_WAP_HOST.'/fangweixin/?openid=%s&domain=%s&url=fang1&method=update&ifid=gjwx_user_dynew2&ca_s=other_weixin&ca_n=push&_gjassid=fV0x40R2Gk9V&xiaoqu_data='.$xiaoqu_data;

        $sell_text = self::$WEIXIN_WAP_HOST.'/fangweixin/?domain=%s&act=sellSubscribe&url=fang5&method=index&openid=%s&ca_s=other_weixin&ca_n=push&ifid=gjwx_user_dynew2f5&_gjassid=fV0x40R2Gk9V&xiaoqu_data='.$xiaoqu_data;
		
        $info = array(
            "touser" => $this->openid,
            "msgtype" => "text",
            "text" => array(
                "content" => "关注该小区二手房，点击<a href=\"".sprintf($sell_text, $xiaoquInfo['pinyin'], $this->openid)."\">这里</a>填写更多条件。\n关注附近出租房，点击<a href=\"".sprintf($rent_text, $this->openid, $xiaoquInfo['pinyin'])."\">这里</a>填写更多条件。",
            ),
        );

        $this->wxObj->sendCustomMessage( $info );

        return $news;
    }

    /**
     * 通过QR码的内容  返回 帖子的消息
     * @param array $QRContent
     * @return $this|array
     */
    public function getPostInfoBySceneInfo($QRContent = array())
    {
        //$this->wxObj->logcallback( print_r( array("ssss" , $QRContent) , true ));die;

        //发送房源信息
        if ($QRContent['event_type'] == "collection" && $QRContent['params_str']) {
            $params = json_decode($QRContent['params_str'], true);
            $this->wxObj->logcallback(print_r($params, true));
        } else {
            return $this;
        }

        $objWeixinCollectionInfo = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfo');
        $postInfo = $objWeixinCollectionInfo->getPostInfoByPuid($puid = $params['puid'], $this->openid);

        $this->wxObj->logcallback(print_r($postInfo, true));

        if ($postInfo['errorno'] == 0 || $postInfo['errorno'] == '1008') {
			$major_category  = !empty($postInfo['data']['major_category']) ? $postInfo['data']['major_category'] : 1;
            $postInfo = $postInfo['data'];
            $news = $this->formatNewsInfo($postInfo,$major_category);
            $this->wxObj->news($news);
            /*
        }else if( $postInfo['errorno'] == '1008' ){
            $news[] = array(
                "Title" => "亲，您已经收藏过了哦[文案待修正]",
                "Description"   => "看看其它房源吧！",
                "PicUrl" =>"",
                "Url"   => "",
            );
            */
        }

        return $news;
    }

    protected function formatXiaoquInfo($xiaoquInfo = array()){
		
		if(empty($xiaoquInfo['avg_price']))
		{
			 $desciption = "小区均价："."暂无均价 \n";
		}else{
			 $desciption  = "小区均价：" . $xiaoquInfo['avg_price'] . "元/㎡\n";
		}
		$desciption .= "所在区域：" . $xiaoquInfo['city'] . ( !empty($xiaoquInfo['district_name']) ? ' - ' . $xiaoquInfo['district_name'] : '') . ( !empty($xiaoquInfo['street_name']) ? ' - '.$xiaoquInfo['street_name'] : '') . "\n";
		$desciption .= "竣工时间：" . (!empty($xiaoquInfo['finish_at']) ? $xiaoquInfo['finish_at'] : '暂无信息');
        $news[] = array(
            "Title" => $xiaoquInfo['name'],
            "Description" => $desciption,
            "PicUrl" => $xiaoquInfo['big_img'] ? $xiaoquInfo['big_img'] : $xiaoquInfo['thumb_img'],
            "Url" => $xiaoquInfo['url'],
        );

        return $news;
    }
	
	/**
	 * 增加新的格式化function 
	*/
	protected function formatNewsInfo($postInfo,$major_category=1)
	{
		$obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionNewsInfo');
		return $obj->getNews($postInfo,$major_category);
	}
	
    protected function formatPostInfo($postInfo = array())
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "户型：" . $postInfo['huxing'] . "\n";
        $desciption .= "楼层：" . $postInfo['ceng'] . "\n";
        $desciption .= "小区：" . $postInfo['xiaoqu'] . "\n";
        $postInfo['address'] = empty($postInfo['address']) ? "暂无信息" : $postInfo['address'];
        $desciption .= "位置：" . $postInfo['address'] . "\n";
        $desciption .= "电话：" . $postInfo['phone'] . "\n";

        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getLocationEventReply()
    {
        //@todo 本期不涉及
        $this->wxObj->text("事件getLocationEventReply");
        return $this;
    }

    /**
     * 新的订阅
     * @return $this
     */
    public function getSubscriptionNotice()
    {
        $subscribeConditions = $this->getSubscribeConditions();

        $text = "新订阅成功之后，原先的订阅条件是不保存的哦！点<a href=\"{$subscribeConditions['redirect_url']}&ifid=gjwx_user_dynew&_gjassid=fV0x40R2Gk9V\">此处</a>进入修改或新增。";
        $this->wxObj->text($text);
        return $this;
    }

    /**
     *  fang5订阅
     * @return $this
     */
    public function showSellSubscribeContent()
    {
        $major_category = 5;
        $ret = $this->objWeixinSubscribe->getSubscribeConditions($this->openid, $major_category,
            null, self::$MAJOR_CATEGORY_MAX[$major_category]);
        if(!$ret['data'] || $ret['errorno'] != 0){
            $newSubUrl = self::$WEIXIN_WAP_HOST."/fangweixin/?domain=bj&act=sellSubscribe&url=fang5&method=index&openid=".$this->openid.
                "&ifid=gjwx_user_dynew3f5&ca_s=other_weixin&ca_n=menu&_gjassid=fV0x40R2Gk9V";
            $this->wxObj->text("点<a href=\"{$newSubUrl}\">这里</a>进行二手房订阅，小房会每天给你推荐符合条件的最新二手房。");
        }else{
            foreach($ret['data'] as $key => $value ){
                $tempInfo = $this->objWeixinSubscribe->getSellSubscribeChineseConditions($value['conditions']);
                $ret['contentStr'] .= $tempInfo['data'];
            }
            $updateSubUrl = self::$WEIXIN_WAP_HOST."/fangweixin/?domain=".$ret['data'][0]['conditions']['domain']."&act=sellSubscribe&url=fang5&method=index&openid=".$this->openid.
                "&ifid=gjwx_user_dynewf5&ca_s=other_weixin&ca_n=menu";
            $this->wxObj->text("你订阅了以下条件的二手房：\n\n" . $ret['contentStr'] . "\n点<a href=\"{$updateSubUrl}\">这里</a>修改条件，继续订阅。\n回复“取消二手房订阅”，不再收到新二手房源推送。");
        }
        return $this;
    }

    /**
     * FANG1 /3 我的订阅， fang5单独处理
     * @return $this
     */
    public function showSubscriptionList()
    {
        $subscribeConditions = $this->getSubscribeConditions();
        if (!empty($subscribeConditions['conditions'])) {
            $chineseNameInfo = $this->objWeixinSubscribe->getChineseNameByConditions($subscribeConditions['conditions'], $subscribeConditions['major_category']);
            $this->wxObj->text("你订阅了以下条件的出租房：\n\n" . $chineseNameInfo['data'] . "\n点<a href=\"{$subscribeConditions['redirect_url']}\">这里</a>修改条件，继续订阅。\n\n回复“取消租房订阅”，不再收到最新出租房源推送。");
        } else {
            $this->wxObj->text("点<a href=\"{$subscribeConditions['redirect_url']}\">这里</a>进行租房订阅，小房会每天给你推荐符合条件的最新出租房。！");
        }

        return $this;
    }

    protected function addSubscriptionByScanInfo($params = array())
    {
        $params['openid'] = $this->openid;
        $addStatus = $this->objWeixinSubscribe->addSubscribe($params);

        $this->wxObj->logcallback(json_encode($addStatus));

        if ($addStatus['errorno'] == 0 || $addStatus['errorno'] == 1008) { //1003 重复订阅
            $this->sendChineseSubcriptionInfo($params, $params['major_category']);
        }else if($addStatus['errorno'] == 1009){
            $updateSubUrl = self::$WEIXIN_WAP_HOST."/fangweixin/?domain=".$params['domain']."&act=sellSubscribe&url=fang5&method=index&openid=".$this->openid.
            "&ifid=gjwx_user_dynew4f5&ca_s=other_weixin&ca_n=push&_gjassid=fV0x40R2Gk9V";
            $this->wxObj->text("不好意思，我们最多只支持5个订阅条件，不要太贪心哦！如果想修改，请点<a href=\"{$updateSubUrl}\">这里</a>。");
        }else if($addStatus['errorno'] == 1004){
            $is_exist_domain = $addStatus['errormsg'];
            $cityInfo = GeoNamespace::getCityByDomain($is_exist_domain);
            $updateSubUrl = self::$WEIXIN_WAP_HOST."/fangweixin/?domain=".$is_exist_domain."&act=sellSubscribe&url=fang5&method=index&openid=".$this->openid.
                "&ifid=gxwx_user_dynew5f5&ca_s=other_weixin&ca_n=push&_gjassid=fV0x40R2Gk9V";

            $this->wxObj->text("你已经订阅了".$cityInfo['name']."城市房源，小房暂时不支持跨城市订阅哦。\n 点击<a href=\"{$updateSubUrl}\">这里</a>，删除或修改原条件后再订阅吧！");
        }

        return $this;
    }

    /**
     * 获取订阅信息及修改订阅的url
     * @return mixed
     */
    public function getSubscribeConditions($major_category = 1)
    {
        $subscribeConditions = $this->objWeixinSubscribe->getSubscribeConditions($this->openid, $major_category,
            null, self::$MAJOR_CATEGORY_MAX[$major_category]);


        if ($subscribeConditions['errorno'] == 0 && $subscribeConditions['data']['conditions']) {
            $subscribeCondition = $subscribeConditions['data'];
            $subscribeCondition['redirect_url'] = self::$WEIXIN_WAP_HOST. '/fangweixin/?openid=' . $this->openid . '&domain=' . $subscribeCondition['conditions']['domain'] . '&url=fang' . $subscribeCondition['major_category'] .
                '&method=update&ifid=gjwx_user_dynew&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=menu';
        } else {
            //默认bj fang1
            $subscribeCondition['redirect_url'] = self::$WEIXIN_WAP_HOST . '/fangweixin/?openid=' . $this->openid . '&domain=bj&url=fang1' . '&method=add&ifid=gjwx_user_dynew3&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=menu';
        }

        $subscribeCondition['openid'] = $this->openid;

        $this->wxObj->logcallback(print_r(array("getSubscribeContions", $subscribeConditions), true));

        return $subscribeCondition;
    }

    /**
     * 取消订阅
     * @return $this
     */
    public function cancelSubscription($major_category = 1)
    {
        $cancelStatus = $this->objWeixinSubscribe->cancelSubscribeByOpenid($this->openid, $major_category);
        $this->wxObj->logcallback(print_r(array("cancelSubscription", $cancelStatus), true));
        if ($cancelStatus['errorno'] == 0 || $cancelStatus['errorno'] == 1007) {
            $this->wxObj->text("想必你已经找到了心仪的房子啦，有需要再来找我吧~");
        } else {
            $this->wxObj->text("刚才微信抽了，没取消成功。再操作一次就OK了！");
        }

        return $this;
    }

    /**
     * @brief 根据当前时间段来返回结果
     * @param null $type
     * @return $this0
     */
    protected function getSomeMoreByCurrentTime($type = null)
    {
        $opcodeStr = self::$AUTO_RESPONSE_BY_TIME[$type][$this->userSubscribeCate];
        $opcodeArr = explode("##", $opcodeStr);
        if (!$opcodeStr) {
            return $this;
        }
        if (count($opcodeArr) == 1) {
            return $this->$opcodeArr[0]();
        } else {
            $ccKey = md5("weixin_xiaofang_" . $type . $this->userSubscribeCate . $this->openid);
            $ccValue = $this->cache->read($ccKey) ? $this->cache->read($ccKey) + 1 : 1;

            if ($ccValue <= $opcodeArr[0]) {
                $this->wxObj->text($opcodeArr[1]);
            } else {
                $oneArticleInfo = $this->objWeixinSubscribe->$opcodeArr[2]();
                if ($oneArticleInfo['errorno'] == 0) {
                    $imgInfo = json_decode($oneArticleInfo['data']['images'], true);
                    $pattern = '/_(\d+)-(\d+)(.*)_[0-9]-[0-9]/i';
                    $replaceStr = '_360-200c_6-0';
                    $imgInfo[0]['thumb_image'] = preg_replace($pattern, $replaceStr, $imgInfo[0]['thumb_image']);
                    $news[] = array(
                        "Title" => $oneArticleInfo['data']['title'],
                        "PicUrl" => $this->imageServer . $imgInfo[0]['thumb_image'],
                        "Description" => $oneArticleInfo['data']['brief'],
                        "Url" => $oneArticleInfo['data']['url'],
                    );
                    $this->wxObj->news($news);
                }
            }
            $this->cache->write($ccKey, $ccValue, (strtotime(date("Y-m-d")) + 7200 + self::DAY_TIME_SECONDS) - time());
        }

        return $this;
    }

    protected function setUserSubcribeCate(){

        $data_rent = $this->objWeixinSubscribe->getSubscribeConditions($this->openid, 1,
            null, self::$MAJOR_CATEGORY_MAX[1])['data'];
        $data_sell = $this->objWeixinSubscribe->getSubscribeConditions($this->openid, 5,
            null, self::$MAJOR_CATEGORY_MAX[5])['data'];

        if($data_rent['conditions'] && $data_sell[0]['conditions']){
            $this->userSubscribeCate = 'rent_sell_sub';
        }else if($data_rent['conditions']){
            $this->userSubscribeCate = 'rent_sub';
        }else if($data_sell[0]['conditions']){
            $this->userSubscribeCate = 'sell_sub';
        }else {
            $this->userSubscribeCate = 'null_sub';
        }

    }

    /**
     * @brief 我还要功能对外接口
     * @return $this
     */
    public function getSomeMoreSecond(){

        $current_sys_time = date("G");
        $this->setUserSubcribeCate($this->openid);
        if($current_sys_time >= 2 && $current_sys_time < 13){
            return $this->getSomeMoreByCurrentTime("morning");
        }

        if($current_sys_time >=13 && $current_sys_time < 21){
            return $this->getSomeMoreByCurrentTime("afternoon");
        }

        if($current_sys_time >= 21 || $current_sys_time < 2){
            return $this->getSomeMoreByCurrentTime("night");
        }

    }
    /**
     * 我还要
     * @return $this
     */
    public function getSomeMore()
    {
        $major_category = 1;
        $subscribeConditions = $this->getSubscribeConditions($major_category);
        $time = date("G");

        //cache key 添加条件 来区分唯一key
        $cache_key = 'weixin_fang_somemore_has_subcriped_' . $this->openid . '_' . md5(json_encode($subscribeConditions['conditions']));
        //推送 + 主动点击 我还要，显示完经纪人列表 之后，这个key 为设置为1 ，有效期3小时
        $hadDown = $this->cache->read($cache_key);
        $this->wxObj->logcallback(print_r(array($time, $cache_key, $hadDown), true));
        if ($time >= 21 || $time < 2) {
            if ($hadDown) {
                $this->wxObj->text("洗洗睡吧，我也困了，明天再给你推荐。");
                return $this;
            }

            $postInfo = $this->objWeixinSubscribe->getTopOneByOpenid($this->openid);
            $this->wxObj->logcallback(print_r(array("获取推送消息1条", $postInfo), true));
            if ($postInfo['errorno'] == 0) {
                $postInfo = $postInfo['data']['postInfo'];
                $news = $this->formatPostInfo($postInfo);
                $this->wxObj->news($news);
            }

            //修复 cursor 有可能大于10的情况
            if ($postInfo['errorno'] == 1007 || ($postInfo['errorno'] == 0 && $postInfo['data']['cursor'] > 10)) { //获取单条推荐不存在时
                //发送客服接口
                $info = array(
                    "touser" => $this->openid,
                    "msgtype" => "text",
                    "text" => array(
                        "content" => "您都看了" . ($postInfo['data']['cursor'] ? $postInfo['data']['cursor'] : 10) . "条了还没满意的，要不让专业的经纪人给你推荐吧？下面这些经纪人看上哪个了，小主请翻牌并留下手机号吧。",
                    ),
                );
                //产品要求 不再发送文案
                //$this->wxObj->sendCustomMessage( $info );
                $news = $this->objWeixinSubscribe->getAgentsByOpenid($this->openid);

                $this->wxObj->logcallback(print_r(array("获取经纪人列表", $news, $this->openid), true));
                if ($news['errorno'] == 0) {
                    if (count($news['data']) == 0) {
                        $this->wxObj->logcallback(print_r(array("获取经纪人列表为空,订阅情况：", $this->openid, $subscribeConditions['conditions']), true));
                        $this->wxObj->text("洗洗睡吧，我也困了，明天再给你推荐。");
                        return $this;
                    }
                    $postsInfo[] = array(
                        "Title" => "还没满意的？让专业经纪人给你推荐吧！",
                        "PicUrl" => "http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/agent_banner2.png",
                        "Description" => "",
                        "Url" => self::$WEIXIN_WAP_HOST."/local_fang1/?ifid=gjwx_gj_jjr0&ca_s=other_weixin&ca_n=push",
                    );
                    foreach ($news['data'] as $k => $v) {
                        //最多显示五条
                        if ($k == 5) {
                            break;
                        }
                        $postsInfo[] = array(
                            "Title" => $v['person'] . " " . $v['company'] . " 现有" . $v['postsnum'] . "套房子在租",
                            "PicUrl" => $v['user_picture'],
                            "Description" => "",
                            "Url" => $v['url'],
                        );
                    }
                    $this->wxObj->news($postsInfo);

                    //计算距离当天结束还有多少秒
                    $todayLastSecond = strtotime(date("Y-m-d 23:59:59"));
                    $expireTime = $todayLastSecond - time();

                    $this->cache->write($cache_key, 1, $expireTime);

                }
            }
        }else{
            $this->wxObj->text("你等我啊！晚上八点，不见不散！要不我先给你点新闻看看？");
            return $this;
        }

        return $this;
    }


    /**
     * 清空收藏
     * @return $this
     */
    public function clearCollection($major_catgory = 1)
    {
        $objWeixinCollectionInfo = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfo');
        $postInfos = $objWeixinCollectionInfo->getCollectedPostsByWeixinOpenid($this->openid, $major_catgory);

        if (count($postInfos['data']) < 1 ) {
            $this->wxObj->text("都没收藏过，怎么清空！臣妾做不到啊！");
        } else {
            $clearStatus = $objWeixinCollectionInfo->clearCollectionInfoByWeixinOpenid($this->openid, $major_catgory);

            $this->wxObj->logcallback(print_r($clearStatus, true));

            if ($clearStatus['errnono'] == 0) {
                $this->wxObj->text("相信你已经找到了心仪的房子，有需要再来找我吧！");
            }
        }

        return $this;
    }
	/**
	 * 回复"我的收藏",获取全部的收藏信息
	 * 推送图文展示
	 * @return $this
	*/
	public function myCollectionCountNum($category ='all')
	{
		
		if($category =='all')
		{
			//按照顺序获取房屋的收藏数据
			$category_list = array('FANG1/3'=>'出租房源','FANG5'=>'二手房出售','xiaoqu'=>'小区','FANG2'=>'求租信息','FANG4'=>'二手房求购信息',
									'FANG6'=>'门面旺铺出租','FANG7'=>'门面旺铺出售','FANG8'=>'写字楼出租','FANG9'=>'写字楼出售',
									'FANG10'=>'日租房/短租房','FANG11'=>'厂房/仓库/车位/土地等');
			$ifid_list_str = array('FANG1/3'=>'gjwx_user_scf1','FANG5'=>'gjwx_user_scf5','xiaoqu'=>'gjwx_user_scxq','FANG2'=>'gjwx_user_scf2',
									'FANG4'=>'gjwx_user_scf4','FANG6'=>'gjwx_user_scf6','FANG7'=>'gjwx_user_scf7',
									'FANG8'=>'gjwx_user_scf8','FANG9'=>'gjwx_user_scf9','FANG10'=>'gjwx_user_scf10',
									'FANG11'=>'gjwx_user_scf11');				
			$objWeixinCollectionInfo = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfo');
			$category_count_list = $objWeixinCollectionInfo->getCollectionsCountNum($this->openid,array_keys($category_list));
			//设置推送的图文消息的显示条数
			$max_msg_num = 9;
			if(is_array($category_count_list) && !empty($category_count_list))
			{
				$category_arr = $category_count_info = array();
				foreach($category_count_list as $category_type =>$value)
				{
					if($value > 0)
					{
						$category_arr[$category_type] = array('category_num'=>$value,'category_name'=>$category_list[$category_type]);
					}
				}
				if($category_arr)
				{
					if(count($category_arr) >= $max_msg_num)
					{
						$category_count_info = array_slice($category_arr,0,$max_msg_num);
					}else{
						$category_count_info = $category_arr;
					}
				}
			}
			if($category_count_info)
			{
				$news[] = array(
						"Title" => '点这里查看所有收藏记录',
						"PicUrl" => 'http://sta.ganjistatic1.com/src/image/mobile/touch/lazy-house/collect_banner.png',
						"Description" => '',
						"Url" => self::$WEIXIN_WAP_HOST.'/fangweixin/?act=myCollectionList&method=index&openid='.$this->openid.'&location=all'.'&ifid=gjwx_user_scall&ca_s=other_weixin&ca_n=reply&_gjassid=fV0x40R2Gk9V',
					);
				$num_img_url ='http://sta.ganjistatic1.com/src/image/mobile/touch/lazy-house/num/';
				foreach($category_count_info as $key =>$value)
				{
					$category_name = $value['category_name'];
					$category_num = $value['category_num'];
					$news[] =array(
							"Title" => $category_name,
							"PicUrl" => $num_img_url.$category_num.'.png',
							"Description" => '',
							"Url" => self::$WEIXIN_WAP_HOST.'/fangweixin/?act=myCollectionList&method=index&openid='.$this->openid.'&location='.$key.'&ifid='.$ifid_list_str[$key].'&ca_s=other_weixin&ca_n=reply&_gjassid=fV0x40R2Gk9V#'.$key,
						);
				}
				//发送图文消息
				$this->wxObj->news($news);
			}else{
				 $this->wxObj->text("找不到你的收藏记录哦！\n留意电脑上的二维码，扫一扫马上收藏！");
			}
		}
		return $this;
	}	
    /**
     * 我的rent/share收藏历史
     * @return $this
     */
    public function showCollectionList($major_category = 1)
    {
        $objWeixinCollectionInfo = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfo');
        $postInfos = $objWeixinCollectionInfo->getCollectedPostsByWeixinOpenid($this->openid, $major_category);

        $this->wxObj->logcallback(print_r($postInfos, true));
        if (count($postInfos['data']) < 1) {
            $this->wxObj->text("找不到你的收藏记录哦！\n留意电脑上的二维码，扫一扫马上收藏！");
        } else if ($postInfos['errorno'] == 0) {
            $postInfos = $postInfos['data'];
            foreach ($postInfos as $k => $v) {
                $news[] = array(
                    "Title" => $v['title'],
                    "PicUrl" => ($k == 0) ? $v['big_img'] : $v['thumb_img'],
                    "Description" => ($k == 0) ? "" . $v['desc'] : '',
                    "Url" => $v['url'],
                );
            }
            $this->wxObj->news($news);
            $opcodeStr = $major_category == 5?"这些是你最近收藏过的二手房，回复“清空二手房收藏”，全部删除。":
                "这些是你最近收藏过的出租房，回复“清空租房收藏”，全部删除。";
            $info = array(
                "touser" => $this->wxObj->getRevFrom(),
                "msgtype" => "text",
                "text" => array(
                    "content" => $opcodeStr,
                ),
            );

            $this->wxObj->sendCustomMessage($info);
        }

        return $this;
    }

    /**
     * @brief sell收藏历史
     */
    public function showSellCollectionList(){
       $major_category = 5;
       return $this->showCollectionList($major_category);
    }
    /**
     * @codeCoverageIgnore
     * @param string $click_key
     * @return $this
     */
    public function getClickReply($click_key = '')
    {

        $news = array(
            array(
                "Title" => '测试单条图文 标题1 ' . "订阅 事件getClickReply ",
                "Description" => "测试单条图文 描述 , \n测试换行\n再换行",
                "PicUrl" => 'https://mmbiz.qlogo.cn/mmbiz/BYC50PZWryiaRwX5B5lvuVOJicGlMU0a8NCxcJKaUvGYDuXjSZysh2SQyqjhyGsy3jtUygBqeliaPGmB7lR3Cpvqw/0',
                "Url" => "http://3g.ganji.com",
            ),

        );

        $this->wxObj->news($news);

        $info = array(
            "touser" => $this->wxObj->getRevFrom(),
            "msgtype" => "news",
            "news" => array(
                "articles" => $news,
            ),
        );
        $this->wxObj->sendCustomMessage(json_encode($info));
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getViewReply()
    {
        $this->wxObj->text("事件getViewReply");
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getVoiceReply()
    {
        $this->wxObj->text("啊？大声点我听不见！");
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getVideoReply()
    {
        $this->wxObj->text("太费流量了，我不看！");
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getLocationReply()
    {
        $geoInfo = $this->wxObj->getRevGeo();
        if (!$geoInfo) {
            $this->wxObj->text('异常情况');
            return $this;
        }
        $cityId = LatlngNamespace::getCityIdByLatlng($geoInfo['x'], $geoInfo['y']);
        $cityInfo = GeoNamespace::getCityById($cityId);
        $appendInfo = array(
            'Title' => "更详细筛选，点这里！",
            'Description' => "更详细筛选，点这里！",
            'Url' => self::$WEIXIN_WAP_HOST."/{$cityInfo['domain']}_fang" . self::DEFAULT_MAJORCATEGORY . "/?ifid=gjwx_user_location0&lat={$geoInfo['x']}&lng={$geoInfo['y']}&distance=".self::DEFAULT_DISTANCE . "&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=location",
            'PicUrl' => 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/arrow.jpg',
        );

        $latlngInfo = BusSubwayCollegeNamespce::getLatLngRange2($geoInfo['x'], $geoInfo['y'], self::DEFAULT_DISTANCE);
        $result = $this->objWeixinSubscribe->getPostsByLatLng(
            array(
                'latlng' => $latlngInfo,
                'domain' => $cityInfo['domain']
            ),
            self::DEFAULT_MAJORCATEGORY
        );
        if ($result['errorno'] == 0 && count($result['data']) > 0) {
            $articles = array();
            foreach ($result['data'] as $key => $value) {
                $articles[$key]['Title'] = $value['title'];
                $articles[$key]['Description'] = $value['title'];
                $articles[$key]['Url'] = str_replace('ca_n=push', 'ca_n=location', str_replace('gjwx_gj_dy','gjwx_user_location'.($key+1) , $value['url']));
                $articles[$key]['PicUrl'] = $value['big_img'] ? $value['big_img'] : $value['thumb_img'];
            }
            if (count($articles) == 10) {
                $articles[9] = $appendInfo;
            } else {
                $articles[] = $appendInfo;
            }
            $this->wxObj->news($articles);
        } else {
            $this->wxObj->text('该位置附近暂时没有房源');
        }
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getLinkReply()
    {
        $this->wxObj->text("你中毒了吗？");
        return $this;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getRandomImageReply()
    {
        //@todo 随机回表情。暂为调好，待功能完善后再调整
        //$this->wxObj->image('9bEAW61V9NMFYr-jMMnYywT6sYRg08GH2a_O1PtBZGRNCasr9n-kHvk7tCQCk1oG');
        $this->wxObj->text("别发图了，我网速慢打不开。");
        return $this;
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getTextReply()
    {
        $keyword = $this->wxObj->getRevContent();
        if(isset(self::$STATIC_OPCODE[$keyword])){
            $option = explode(",", self::$STATIC_OPCODE[$keyword]);
            return $this->$option[0]($option[1]);
        }

        $status = $this->sendCustomTextReplyWithConf($keyword);

        if ($status === false) { //未匹配上关键词
            return $this->getRandomTextReply();
        } else {
            return $this;
        }
    }

    /**
     * @codeCoverageIgnore
     * @return $this
     */
    public function getRandomTextReply()
    {
        $reply_array = array(
            "嗯，容我想想。",
            "让我自已静一静",
            "Interesting！",
            "哈哈！"
        );

        $this->wxObj->text($reply_array[array_rand($reply_array)]);
        return $this;
    }


    /**
     * @brief 获取微信二维码信息 ， PC调用
     * @param array $params
     * 比如： $params = array(
     * "puid"  => "131232131",
     *      "uid"   => "12323123",
     * )
     * @return array
     */
    public function getQRCode($event_type = "collection", $params = array())
    {
        $arrRet = $this->arrRet;

        try {
            //cache
            $params_str = json_encode($params);
            $cache_key = "weixin_getQRCode_by_conditions_" . md5($event_type . $params_str);
            //$this->wxObj->logcallback("PC getQRCode try get MC cache content with cache key : " . $cache_key);
            $QRInfo = $this->cache->read($cache_key);

            if ($QRInfo) {
                //$this->wxObj->logcallback("MC cache content : " . json_encode($QRInfo));
                //$this->wxObj->logcallback("return MC cache content ");
                //$this->wxObj->logcallback("getQRCode end");
                $arrRet['data'] = $QRInfo;
                return $arrRet;
            }

            //$this->wxObj->logcallback("MC cache content not exist.");
            //$this->wxObj->logcallback("ready to get new QRSceneId with Redis.");
            //$this->wxObj->logcallback("connecting Redis");
            $this->redisQRCodeObj = Gj_LayerProxy::getProxy('Dao_Redis_Weixin_QRCode');
            //$this->wxObj->logcallback("get Redis " . json_encode($this->redisQRCodeObj));

            $scene_id = $this->redisQRCodeObj->getQRSceneId();

            if (!$scene_id) { //重试一次
                //$this->wxObj->logcallback("repeat get scene_id with Redis.");
                $scene_id = $this->redisQRCodeObj->getQRSceneId();
            }

            if ($scene_id) {
                //$this->wxObj->logcallback("get scene_id :" . $scene_id);
                //$this->wxObj->logcallback("ready to get QRContent from weixin api by scene_id :" . $scene_id);
                //多进行一次尝试
                $QRContent = $this->wxObj->getQRCode($scene_id);


                if (!$QRContent) {
                    //$this->wxObj->logcallback("repeat get scene_id with Redis.");
                    $QRContent = $this->wxObj->getQRCode($scene_id);
                }

                if ($QRContent) {
                    //$this->wxObj->logcallback("get QRCode :" . json_encode($QRContent));

                    $arrRet['data'] = $QRContent;
                    $arrRet['data']['scene_id'] = $scene_id;

                    #$this->wxObj->logcallback(print_r( $arrRet['data'] , true ) );
                    //把从微信接口获取的数据缓存20分钟
                    //$this->wxObj->logcallback("set Mc cache 20 mins. key: " . $cache_key . " value:" . json_encode($arrRet['data']));
                    $this->cache->write($cache_key, $arrRet['data'], 20 * 60);

                    //在redis 中生成一条记录，缓存30分钟
                    //$this->wxObj->logcallback("insert to redis with content: event_type:" . $event_type . " param_str:" . $params_str);
                    $this->redisQRCodeObj->insertQRContent($event_type, $params_str);

                } else {
                    //$this->wxObj->logcallback("get qrcode info from weixin api error . " . $scene_id);
                    return array(
                        "errorno" => -1,
                        "errormsg" => "微信接口调用失败",
						"scene_id" => $scene_id
                    );
                }

            } else {
                //redis 获取 sceneid 失败
                //$this->wxObj->logcallback("not get scene_id .. return errorno -1 ");
                $arrRet['errorno'] = -1;
                $arrRet['errormsg'] = "Redis 获取Scene id 失败";
            }


        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        //$this->wxObj->logcallback(print_r($arrRet, true));
        //$this->wxObj->logcallback("getQRCode end");

        return $arrRet;
    }

    /***
     * @codeCoverageIgnore
     * @param string $ticket
     */
    public function createQRCode($ticket = '')
    {
        try {
            $this->arrRet['data'] = array(
                "url" => 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($ticket),
            );
        } catch (Exception $e) {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
    }

    /**
     * @todo 目前只针对 boss小彩蛋  处理。 待扩展
     * @param array $conf
     * @return mixed
     */
    protected function validTime($conf = array())
    {
        $startTime = isset($conf['startTime']) ? $conf['startTime'] : '';
        $endTime = isset($conf['endTime']) ? $conf['endTime'] : '';
        $currentTime = time();

        //@todo 上线时间比较赶，只考虑这种情况，其它情况未涉及，先不考虑。
        if (!empty($endTime) && $endTime >= $currentTime) {
            if (!empty($startTime) && $startTime <= $currentTime) {
                return true;
            }
        }

        if (empty($startTime) && empty($endTime)) {
            return true;
        }

        return false;
    }


    /**
     * 自定义回复二期 wanyang 2015-1-27
     */
    protected function getCustomTextReply($keyword = '')
    {
        $textForReplyCacheKey = 'weixinxiaofang_text_reply_ganji';
        $textForReply = $this->cache->read($textForReplyCacheKey);
        if(!$textForReply){
            $textForReply = Gj_Conf::getAppConf("weixin.ganjixiaofang", 'textForReply');
            $this->cache->write($textForReplyCacheKey, $textForReply, 7 * self::DAY_TIME_SECONDS);
        }
        $typeArr = array('contain', 'equal', 'random');
        foreach ($textForReply as $k1 => $v1) {
            $replyBlock = explode('$', $v1);

            //随机类型回复
            if ($replyBlock[0] == $typeArr[2]) {
                $randomArr = explode('#', $replyBlock[2]);
                return $randomArr[rand(0, 7)];
            }
            //模糊匹配类型回复
            if ($replyBlock[0] == $typeArr[0]) {
                $containArr = explode('#', $replyBlock[1]);
                foreach ($containArr as $k2 => $v2) {
                    if (stripos($keyword, $v2) !== false) {
                        return $replyBlock[2];
                    }
                }
            }
            //完全匹配类型回复
            if ($replyBlock[0] == $typeArr[1]) {
                $regArr = explode('#', $replyBlock[1]);
                if (in_array($keyword, $regArr)) {
                    return $replyBlock[2];
                }
            }
        }
        return false;
    }

    /**
     * 发送自定义文本回复
     * @param string $keyword
     * @return $this|bool
     */
    protected function sendCustomTextReplyWithConf($keyword = '')
    {
        if (stripos($keyword, '租') !== false) {
             $news = array(
                        array(
                            'Title'         => '小房使用指南之“查看附近房源”',
                            'Description'   => '总共三步，10秒学会。',
                            'PicUrl'        => 'https://mmbiz.qlogo.cn/mmbiz/BYC50PZWryhjp3SelQCEWMwvAeqRDgPSAZKibib9LaILrjXnkHOHsAyghIqFSxwqXtcXPeic8xFymurFw6tOxschw/0',
                            'Url'           => 'http://mp.weixin.qq.com/s?__biz=MzA4OTA1Njc3Ng==&mid=208471109&idx=1&sn=b447eff2518f846ec046174e41d8a919#rd '
                        )
                     );
            $this->wxObj->news($news);
        } else {
            $replyText = $this->getCustomTextReply($keyword);
            if ($replyText) {
                $this->wxObj->text($replyText);
            } else {
                $this->wxObj->text('异常情况');
            }
        }

        return $this;
    }

    /**
     * 正式上线前小彩蛋
     * @return $this
     */
    protected function getFunnyReplyWithBoss()
    {
        //发送客服接口
        $info = array(
            "touser" => $this->wxObj->getRevFrom(),
            "msgtype" => "image",
            "image" => array(
                //浩涌的头像
                "media_id" => 'GwB-bvUpi9lcUW2tbEBMlVfnXSgfkQkAEDmzoV_8gzLqktTsPp4DHSZ5ckw9zm70',
            ),
        );

        $this->wxObj->sendCustomMessage($info);

        $this->wxObj->text("你，现在来我办公室聊一下。");
        return $this;
    }

    /**
     * @brief 提供更新db中access_token的对外接口
     * @param $access_token
     * @param int $expire
     * @return bool
     */
    public function updateDbAccessToken($data){
        $arrRet = $this->arrRet;
        try{
            if(!$data['access_token'] || !$data['expire']){
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
            $access_token = $data['access_token'];
            $expire = $data['expire'];
            $weixin_auth_obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinAuth');
            $weixin_auth_obj->addAccessToken(
                array(
                    'access_token' => $access_token,
                    'expire_at' => time() + $expire
                ));
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 提供更新db中access_token的对外接口
     * @param $access_token
     * @param int $expire
     * @return bool
     */
    public function getDbAccessToken(){
        $weixin_auth_obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinAuth');
        return $weixin_auth_obj->getAccessToken();
    }

    /**
     * @brief 返回当日redis计数。
     * @return mixed
     */
    public function getLateQRCodeGenTimes(){
        $redisQRCodeObj = Gj_LayerProxy::getProxy('Dao_Redis_Weixin_QRCode');
        return $redisQRCodeObj->getCurrentKey();
    }

    /**
     * @brief get the count and openid list by sceneid
     * @param $scenid
     */
    public function getPermanentQRCodeDataBySceneid($scene_id)
    {
        try {
            $arrRet = $this->arrRet;
            $pRedisObj = Gj_LayerProxy::getProxy("Dao_Redis_Weixin_PermanentQRcodeCount");
            $scene_id_count_key = md5("xiaofang_pcode_scan_count" . $scene_id . date("Y-m-d"));
            $scene_id_openid_queue_key = md5("xiaofang_pcode_scan_queue" . $scene_id . date("Y-m-d"));

            $scene_id_count_value = $pRedisObj->getValueByKey($scene_id_count_key);
            $scene_id_openid_queue_size = $pRedisObj->getLengthOfQueueBykey($scene_id_openid_queue_key);
            $openid_list = $pRedisObj->popElementOfQueueByKey($scene_id_openid_queue_key, 0, -1);
            $arrRet['data'] = array(
                'scan_count' => $scene_id_count_value,
                'subscribe_count' => $scene_id_openid_queue_size,
                'openid_list' => $openid_list);
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }
}
