<?php
/**
 * //只做了简单入口，根据不同的类型跳转到不同的方法 处理，无特殊或复杂逻辑，无需测试
 *  @codeCoverageIgnore
 */
class Service_Page_Weixin_WeixinApi {
    protected $options = array(
        'token'=>'ganji', //填写你设定的key
        'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
        'appid'=>'wx8797734b43e9c146', //填写高级调用功能的app id
        'appsecret'=>'871cf839b14da5e3ce524eaa61486a5c', //填写高级调用功能的密钥
        //'partnerid'=>'88888888', //财付通商户身份标识
        //'partnerkey'=>'', //财付通商户权限密钥Key
        //'paysignkey'=>'' //商户签名密钥Key
    );
    protected $allReciveInfo;



    public function __construct (){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->objServiceWeixinApi = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinApi');

    }

    protected  function init(){
        $this->objServiceWeixinApi->valid();
        $this->allReciveInfo = $this->objServiceWeixinApi->getRev();
        //对openid 赋值
        $this->objServiceWeixinApi->setOpenid( $this->objServiceWeixinApi->getRevFrom() );

        $this->msgType = $this->objServiceWeixinApi->getRevType();

        switch($this->msgType) {
            case "event":
                return $this->getEventReply();
            case "text":
                return $this->objServiceWeixinApi->getTextReply()->reply();
                //return $this->objServiceWeixinApi->getRandomTextReply()->reply();
            case "image":
                return $this->objServiceWeixinApi->getRandomImageReply()->reply();
            case "voice":
                return $this->objServiceWeixinApi->getVoiceReply()->reply();
            case "video":
                return $this->objServiceWeixinApi->getVideoReply()->reply();
            case "location":
                return $this->objServiceWeixinApi->getLocationReply()->reply();
            case "link":
                return $this->objServiceWeixinApi->getLinkReply()->reply();
            default:
                exit;
        }
    }

    /***
     * 处理各种事件，主要是点击菜单事件 以及 扫码事件
     */
    protected function getEventReply(){
        $this->Event = $this->objServiceWeixinApi->getRevEvent();
        switch ($this->Event['event']){
            case "subscribe" :
                return $this->objServiceWeixinApi->getSubscribeReply()->reply();
            case "unsubscribe" :
                return $this->objServiceWeixinApi->getUnsubscribeReply()->reply();
            case "SCAN" :
                return $this->objServiceWeixinApi->getScanReply()->reply();
            case "LOCATION" :
                return $this->objServiceWeixinApi->getLocationEventReply()->reply();
            case "CLICK" :
                //return $this->objServiceWeixinApi->getQRCode("collection" , array("puid"=>1 , "uid" =>2 ) )->reply();
                return $this->getClickReply( $this->Event['key'] )->reply();
            case "VIEW" :
                return $this->objServiceWeixinApi->getViewReply()->reply();
        }
    }

    /**
     * 处理各种点击事件
     */
    protected function getClickReply( $click_key = null ){
        $click_keys = array(
            "my_collection"    =>  "showCollectionList",   //收藏rent历史
            "my_collection_sell"    =>  "showSellCollectionList", //收藏sell历史
            "clear_collection"      =>  "clearCollection",      //清空收藏
            "my_subscription"       =>  "showSubscriptionList", //现有订阅
            "new_subscription"      =>  "getSubscriptionNotice",//新的订阅
            "cancel_subscription"   =>  "cancelSubscription",   //取消订阅

            "some_more"             =>  "getSomeMoreSecond",          //我还要
            "rent_collection_list" => "showCollectionList",
            "rent_subcribe_content" =>  "showSubscriptionList",
            "sell_collection_list" => "showSellCollectionList",
            "sell_subscribe_content" => "showSellSubscribeContent"
        );

        $method = $click_keys[$click_key];
        return $this->objServiceWeixinApi->$method();
    }

    public function execute(){
        $this->data['data'] = $this->init();
        return $this->data;
        #print_r($a);die;
    }

}
