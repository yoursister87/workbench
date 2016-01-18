<?php
/**
 * @package              
 * @subpackage           
 * @brief                微信data-service类的单元测试
 * @author               $Author:   liuhu <liuhu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * Service_Data_Weixin_WeixinApi的单元测试
 */

class CacheMock
{
    public function read($cache_key)
    {
        $ret;
        if ($cache_key == 'weixin_fang_userinfo_123'){
            $ret = array('openid' => '123');
        } else if ($cache_key =='weixin_getQRCode_by_conditions_3508144c1e7e0d6cbb440ecfa3c90306') {
            $ret = array('ret');
        } else if ($cache_key == 'weixin_fang_somemore_o6R-qjgd2Ny6ukVMmJm4i-JUMR3o') {
            $ret = array('getSomeMore');
        } else if($cache_key == 'weixinxiaofang_textReply') {
            $ret = array('ganji' => 'Good', 'Hi' => 'Ketty'); 
        } else {
            $ret = array();
        }
        return $ret;
    }
    public function write($cache_key, $state = 1, $expire = 1800)
    {
        return true;
    }
    public function delete($cache_key)
    {
        return true;
    }
}


class Service_Data_Weixin_WeixinApiChild extends Service_Data_Weixin_WeixinApi
{
    public $objWeixinSubscribe;
    public function __construct ()
    {
        $this->objWeixinSubscribe= Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinSubscribe');
        $this->cache = new CacheMock();
    }
    
    public function sendChineseSubcriptionInfo($conditions= array() , $major_category = 1)
    {
        return parent::sendChineseSubcriptionInfo($conditions , $major_category);
    }
    public function validTime($conf =  array())
    {
        return parent::validTime($conf);
    }
    public function addSubscriptionByScanInfo( $params = array())
    {
        return parent::addSubscriptionByScanInfo($params);
    }
    public function getFunnyReplyWithBoss()
    {
        return parent::getFunnyReplyWithBoss();
    }
    public function sendCustomTextReplyWithConf($key='')
    {
        return parent::sendCustomTextReplyWithConf($key);
    }
    public function getCustomTextReply($keyword = '')
    {
        return parent::getCustomTextReply($keyword);
        //return array('ganji' => 'Good', 'Hi' => 'Ketty');
    }
    public function getArrRet()
    {
        return $this->arrRet;
    }
    public function setWxObj($wxObj)
    {
        $this->wxObj = $wxObj;
    }
    public function setobjWeixinSubscribe($obj)
    {
        $this->objWeixinSubscribe = $obj;
    }
}

class Service_Data_Weixin_WeixinApiTest extends Testcase_PTest
{
    protected $obj;
    public function setUp()
    {
        Gj_LayerProxy::$is_ut =true;
        $this->obj = new Service_Data_Weixin_WeixinApiChild();
        //使用频繁的mock
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi', array('logcallback','news','text'));
        $mockObj->expects($this->any())
               ->method('logcallback')
               ->will($this->returnValue(true));
        $mockObj->expects($this->any())
               ->method('news')
               ->will($this->returnValue(true));
        $mockObj->expects($this->any())
               ->method('text')
               ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);
        $mockObj2 = $this->genObjectMock('Dao_Weixin_WeixinUser', array('existUser', 'addUser','updateUser'));
        $mockObj2->expects($this->any())
                 ->method('existUser')
                 ->will($this->returnValue(true));
        $mockObj2->expects($this->any())
                 ->method('addUser')
                 ->will($this->returnValue(array('addUser')));
        $mockObj2->expects($this->any())
                 ->method('updateUser')
                 ->will($this->returnValue(array('updateUser')));
        Gj_LayerProxy::registerProxy('Dao_Weixin_WeixinUser', $mockObj2);
    }

    public function testgetSubscribeReply()
    {
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi',array('getRevSceneId', 'sendCustomMessage', 'getUserInfo', 'logcallback', 'getRevFrom', 'news', 'text'));
        $mockObj->expects($this->any())
                ->method('getRevSceneId')
                ->will($this->returnValue('sceneid'));
        $mockObj->expects($this->any())
                ->method('sendCustomMessage')
                ->will($this->returnValue(true));
        $mockObj->expects($this->any())
                ->method('getUserInfo')
                ->will($this->returnValue(array('userInfo')));
        $mockObj->expects($this->any())
               ->method('logcallback')
               ->will($this->returnValue(true));
        $mockObj->expects($this->any())
               ->method('getRevFrom')
               ->will($this->returnValue('RevFrom'));
        $mockObj->expects($this->any())
               ->method('news')
               ->will($this->returnValue(true));
        $mockObj->expects($this->any())
               ->method('text')
               ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);

        $mockObj2 = $this->genObjectMock('Dao_Redis_Weixin_QRCode',array('getQRContentBySceneId'));
        $mockObj2->expects($this->any())
                ->method('getQRContentBySceneId')
                ->will($this->returnValue('QRContent'));
        Gj_LayerProxy::registerProxy('Dao_Redis_Weixin_QRCode', $mockObj2);

        $this->obj->setOpenid('lh');
        
        $this->obj->getSubscribeReply();
    }
    
    public function testaddUserInfo()
    {
        //可以读到cache
        $this->obj->setOpenid('123');
        $this->assertTrue($this->obj->addUserInfo());
        //读不到cache
        $this->obj->setOpenid('1234');
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi',array('getUserInfo', 'logcallback'));
        $mockObj->expects($this->any())
                ->method('getUserInfo')
                ->will($this->returnValue(array('userInfo')));
        $mockObj->expects($this->any())
                ->method('logcallback')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);

        $mockObj2 = $this->genObjectMock('Dao_Weixin_WeixinUser', array('existUser', 'addUser','updateUser'));
        $mockObj2->expects($this->any())
                 ->method('existUser')
                 ->will($this->returnValue(true));
        $mockObj2->expects($this->any())
                 ->method('addUser')
                 ->will($this->returnValue(array('addUser')));
        $mockObj2->expects($this->any())
                 ->method('updateUser')
                 ->will($this->returnValue(array('updateUser')));
        Gj_LayerProxy::registerProxy('Dao_Weixin_WeixinUser', $mockObj2);
        $this->obj->addUserInfo();
    }


    public function testgetUnsubscribeReply()
    {
        $this->obj->setOpenid('lh');
        $this->obj->getUnsubscribeReply();  
    }

     public function testgetScanReply()
    {
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi',array('getRevEvent', 'logcallback', 'text'));
        $mockObj->expects($this->any())
                ->method('getRevEvent')
                ->will($this->returnValue(array('key' => 'scene_id')));
        $mockObj->expects($this->any())
               ->method('logcallback')
               ->will($this->returnValue(true));
        $mockObj->expects($this->any())
               ->method('text')
               ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);

        $mockObj2 = $this->genObjectMock('Dao_Redis_Weixin_QRCode',array('getQRContentBySceneId'));
        $mockObj2->expects($this->any())
                ->method('getQRContentBySceneId')
                ->will($this->returnValue('QRContent'));
        Gj_LayerProxy::registerProxy('Dao_Redis_Weixin_QRCode', $mockObj2);
        $this->obj->getScanReply();

    }
    
    public function testgetInfoBySceneId()
    {
        $mockObj = $this->genObjectMock('Dao_Redis_Weixin_QRCode',array('getQRContentBySceneId'));
        $mockObj->expects($this->any())
                ->method('getQRContentBySceneId')
                ->will($this->returnValue('QRContent'));
        Gj_LayerProxy::registerProxy('Dao_Redis_Weixin_QRCode', $mockObj);
        $scene_id = 1;
        $this->obj->getInfoBySceneId($scene_id);
    }

    
    public function testgetSubscribeInfoBySceneInfo()
    {
        $QRContent = array();
        $this->obj->getSubscribeInfoBySceneInfo($QRContent);

        $QRContent = array('event_type' => 'subscribe_selection',
                          'params_str' => 'test');
        // $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('updateSubscribeConditionsByOpenid'));
        // $mockObj3->expects($this->any())
        //        ->method('updateSubscribeConditionsByOpenid')
        //        ->will($this->returnValue(array('updateSubscribeConditionsByOpenid')));
        // Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        // //var_dump($mockObj3);die;
        // $this->obj->setobjWeixinSubscribe($mockObj3);
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->getSubscribeInfoBySceneInfo($QRContent);
        
    }
    
    public function testsendChineseSubcriptionInfo()
    {
        $mockData = array('data' => array('conditions' =>array('domain' => 'bj'),
                                          'major_category' => '1'),
                          'errorno' => 0);
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('getChineseNameByConditions'));
        $mockObj3->expects($this->any())
               ->method('getChineseNameByConditions')
               ->will($this->returnValue($mockData));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);
        $conditions =  array('domain' =>"bj",
                             'district_id' =>"2", 
                             'street_id' =>"3", 
                             'price' =>"2",
                             'huxing' => "4");
        $this->obj->sendChineseSubcriptionInfo($conditions, 1);
    }

    public function testgetPostInfoBySceneInfo()
    {
        $QRContent = null;
        $this->obj->getPostInfoBySceneInfo($QRContent);

        $QRContent = array('event_type' => 'collection',
                          'params_str' => '{"puid":97965227}');
        $mockPostInfo = array('errorno' => 0,
                             'data' => array('price' =>'100'));
        $mockObj1 = $this->genObjectMock('Service_Data_Weixin_WeixinCollectionInfo',array('getPostInfoByPuid'));
        $mockObj1->expects($this->any())
               ->method('getPostInfoByPuid')
               ->will($this->returnValue($mockPostInfo));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinCollectionInfo', $mockObj1);
        $this->obj->setOpenid('ofL_bjnjF6Hys4DEyBLWun08yrYk');
        $this->obj->getPostInfoBySceneInfo($QRContent);
    }

    public function testgetSubscriptionNotice()
    {
        $mockData = array('data' => array('conditions' =>array('domain' => 'bj'),
                                          'major_category' => '1'),
                          'errorno' => 0);
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('getSubscribeConditions'));
        $mockObj3->expects($this->any())
               ->method('getSubscribeConditions')
               ->will($this->returnValue($mockData));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->getSubscriptionNotice();
    }

    public function testshowSubscriptionList()
    {
        $mockData = array('data' => array('conditions' =>array('domain' => 'bj'),
                                          'major_category' => '1'),
                          'errorno' => 0);
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('getSubscribeConditions', 'getChineseNameByConditions'));
        $mockObj3->expects($this->any())
               ->method('getSubscribeConditions')
               ->will($this->returnValue($mockData));
        $mockObj3->expects($this->any())
               ->method('getChineseNameByConditions')
               ->will($this->returnValue(array('getChineseNameByConditions')));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
       
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->showSubscriptionList();
    }
    
     public function testaddSubscriptionByScanInfo()
     {
    
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('addSubscribe', 'getChineseNameByConditions'));
        $mockObj3->expects($this->any())
               ->method('getSubscribeConditions')
               ->will($this->returnValue(array('errorno' => 1)));
        $mockObj3->expects($this->any())
               ->method('getChineseNameByConditions')
               ->will($this->returnValue(array('getChineseNameByConditions')));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);

        $params = array();
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->addSubscriptionByScanInfo($params);
     } 

    
    public function testgetSubscribeConditions()
    {
        $exp = array('conditions' => array('domain' => 'bj'),
                    'major_category' => '1', 
                    'redirect_url' => "http://fangweixin.3g.ganji.com/fangweixin/?openid=o6R-qjgd2Ny6ukVMmJm4i-JUMR3o&domain=bj&url=fang1&method=update&ifid=gjwx_user_dynew&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=menu", 
                    'openid' =>'o6R-qjgd2Ny6ukVMmJm4i-JUMR3o'
                  );
        $mockData = array('data' => array('conditions' =>array('domain' => 'bj'),
                                          'major_category' => '1'),
                          'errorno' => 0);
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('getSubscribeConditions'));
        $mockObj3->expects($this->any())
               ->method('getSubscribeConditions')
               ->will($this->returnValue($mockData));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);
        
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $ret = $this->obj->getSubscribeConditions();
        $this->assertEquals($ret, $exp);
    }
    
    public function testcancelSubscription()
    {

        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('cancelSubscribeByOpenid'));
        $mockObj3->expects($this->any())
               ->method('cancelSubscribeByOpenid')
               ->will($this->returnValue(array('errorno' => 0)));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->cancelSubscription();
    }
    
    public function testgetSomeMore()
    {

        $mockData1 = array('data' => array('conditions' =>null,
                                          'major_category' => '1'),
                          'errorno' => 0);
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $mockData2 = array('data' => array('images' => 'soo',
                                          'title' => '1',
                                          'brief' => 'it is brief',
                                          'url' =>'weixin.ganji.com'),
                          'errorno' => 0);
        $mockObj3 = $this->genObjectMock('Service_Data_Weixin_WeixinSubscribe',array('getOneArtilceByRand','getSubscribeConditions', 'getTopOneByOpenid'));
        $mockObj3->expects($this->any())
               ->method('getOneArtilceByRand')
               ->will($this->returnValue($mockData2));
        $mockObj3->expects($this->any())
               ->method('getSubscribeConditions')
               ->will($this->returnValue($mockData1));
        $mockObj3->expects($this->any())
               ->method('getTopOneByOpenid')
               ->will($this->returnValue(array('postInfoOne')));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinSubscribe', $mockObj3);
        $this->obj->setobjWeixinSubscribe($mockObj3);
        $this->obj->getSomeMore();
    }

    public function testclearCollection()
    {
        $mockPostInfo1 = array('data' => array());
        $data = array('title' => 'title', 'big_img' => 'hdakhdka.jpg', 'desc' => 'simple');
        $mockPostInfo2 = array('errorno'=> 0, 'data' => $data );
        $mockObj = $this->genObjectMock('Service_Data_Weixin_WeixinCollectionInfo',array('getCollectedPostsByWeixinOpenid','clearCollectionInfoByWeixinOpenid'));
        $mockObj->expects($this->any())
               ->method('getCollectedPostsByWeixinOpenid')
               ->will($this->onConsecutiveCalls($mockPostInfo1, $mockPostInfo2));
        $mockObj->expects($this->any())
               ->method('clearCollectionInfoByWeixinOpenid')
               ->will($this->returnValue(array('errorno'=>0)));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinCollectionInfo', $mockObj);

        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->clearCollection();
        $this->obj->clearCollection();
    }

    public function testshowCollectionList()
    {
        $mockPostInfo1 = array('data' => array());
        $data = array('title' => 'title', 'big_img' => 'hdakhdka.jpg', 'desc' => 'simple');
        $mockPostInfo2 = array('errorno'=> 0, 'data' => $data );
        $mockObj = $this->genObjectMock('Service_Data_Weixin_WeixinCollectionInfo',array('getCollectedPostsByWeixinOpenid','clearCollectionInfoByWeixinOpenid'));
        $mockObj->expects($this->any())
               ->method('getCollectedPostsByWeixinOpenid')
               ->will($this->onConsecutiveCalls($mockPostInfo1, $mockPostInfo2));
        $mockObj->expects($this->any())
               ->method('clearCollectionInfoByWeixinOpenid')
               ->will($this->returnValue(array('errorno'=>0)));
        Gj_LayerProxy::registerProxy('Service_Data_Weixin_WeixinCollectionInfo', $mockObj);
        $this->obj->setOpenid('o6R-qjgd2Ny6ukVMmJm4i-JUMR3o');
        $this->obj->showCollectionList();
    }

    public function testgetQRCode()
    {
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi',array('getQRCode', 'logcallback'));
        $mockObj->expects($this->any())
               ->method('getQRCode')
               ->will($this->returnValue(array('scene_id' =>'')));
        $mockObj->expects($this->any())
               ->method('logcallback')
               ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);

        $mockObj2 = $this->genObjectMock('Dao_Redis_Weixin_QRCode',array('getQRSceneId','insertQRContent'));
        $mockObj2->expects($this->any())
                ->method('getQRSceneId')
                ->will($this->returnValue('id'));
        $mockObj2->expects($this->any())
                ->method('insertQRContent')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Dao_Redis_Weixin_QRCode', $mockObj2);

        $exp = array('data' => array('ret'),
            'errorno' => '0',
            'errormsg' => "[数据返回成功]");
        $params = array();
        $ret = $this->obj->getQRCode("collection", $params);
        //var_dump($ret);
        $this->assertEquals($exp, $ret);

        $params=array('teststr');
        $exp = array('errorno' => 0, 
                     'errormsg' => '[数据返回成功]',
                     'data' => array('scene_id' => 'id'));
        $ret = $this->obj->getQRCode("collection", $params);

        $this->assertEquals($exp, $ret);

    }

    public function testcreateQRCode()
    {
        $ticket = 'ganji';
        $exp = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=ganji';
        $ticket = 'ganji';
        $this->obj->createQRCode($ticket);
        $ret = $this->obj->getArrRet();
        $this->assertEquals($exp, $ret['data']['url']);
    }

    public function testvalidTime()
    {
        $startTime = time() - 10000;
        $endTime = time() + 10000;
        $conf = array('startTime' => $startTime, 
                      'endTime' =>$endTime);
        $this->assertTrue($this->obj->validTime($conf));
        $this->assertTrue($this->obj->validTime());
        $conf['endTime'] =  time() - 50;
        $this->assertFalse($this->obj->validTime($conf));
    }

    public function testgetCustomTextReply()
    {
        $this->obj->getCustomTextReply();
    }

    public function testsendCustomTextReplyWithConf()
    {
        $this->obj->sendCustomTextReplyWithConf("ganji");
    }

    public function testgetFunnyReplyWithBoss()
    {
        $mockObj = $this->genObjectMock('Gj_Fang_Api_Platform_WeixinApi',array('getRevFrom', 'sendCustomMessage', 'text'));
        $mockObj->expects($this->any())
                ->method('getRevFrom')
                ->will($this->returnValue('ganij'));
        $mockObj->expects($this->any())
                ->method('sendCustomMessage')
                ->will($this->returnValue(true));
        $mockObj->expects($this->any())
                ->method('text')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_WeixinApi', $mockObj);
        $this->obj->setWxObj($mockObj);
        $this->obj->getFunnyReplyWithBoss();
    }

}
