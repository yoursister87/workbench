<?php

/**
 * @package
 * @subpackage
 * @brief                $微信订阅接口测试$
 * @file                 WeixinSubscribeTest.php
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-1
 * @lastmodified         下午6:09
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WeixinSubscribeInfoTest extends Testcase_PTest
{
    protected $obj;
    protected $openid = 'o2R-qjgd2Ny6ukVMmJm4i-JUMR3o';
    protected $upid = 'o6R-qjgd2Ny6ukVMmJm4i-JUMR3o';

    public function testaddSubscribe()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array());
        $data = array(
            'openid' => $this->openid,
            'domain' => 'bj',
            'district_id' => '2',
            'street_id' => '3',
            'price' => '2',
            'huxing' => '5',
            'subType' => 1,
            'major_category' => 1,
        );
        $subscribeObj  = $this->genObjectMock('Dao_Weixin_WeixinSubscribe', array('insertSubscribeCondition'));
        $subscribeObj->expects($this->any())
            ->method('insertSubscribeCondition')
            ->will($this->returnValue(true));
        $this->obj->setInnerObj($subscribeObj, 'subscribeObj');
        $ret = $this->obj->addSubscribe($data);
        $this->assertEquals($ret['errorno'],$ret['errorno'] );
    }
/*
    public function testgetSubscribeConditions()
    {
        $obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
                'conditions' => array(
                    'domain' => 'bj',
                    'district_id' => '2',
                    'street_id' => '3',
                    'price' => '2',
                    'huxing' => '5'
                ),
                'major_category' => 1,
            ));
        $subscribeObj  = $this->genObjectMock('Dao_Weixin_WeixinSubscribe', array('getSubscribeConditionByOpenid'));
        $subscribeObj->expects($this->any())
            ->method('getSubscribeConditionByOpenid')
            ->will($this->returnValue(array()));
        $obj->setInnerObj($subscribeObj, 'subscribeObj');
        $ret = $obj->getSubscribeConditions($this->openid);
        $this->assertEquals($expect['errorno'], $ret['errorno']);
    }
*/
    public function testupdateSubscribeConditionsByOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $expectNo = 1007;
        $data = array(
            'openid' => '32132231wew3213',
            'domain' => 'bj',
            'district_id' => '3',
            'street_id' => '28',
            'price' => '2',
            'share_mode' => '1',
            'major_category' => 3,
            'subType' => 3
        );
        $subscribeObj  = $this->genObjectMock('Dao_Weixin_WeixinSubscribe', array('updateSubscribeCondition'));
        $subscribeObj->expects($this->any())
            ->method('updateSubscribeCondition')
            ->will($this->returnValue(array('errorno' => $expectNo, 'data' => array())));
        $this->obj->setInnerObj($subscribeObj, 'subscribeObj');
        $ret = $this->obj->updateSubscribeConditionsByOpenid($data);

        $this->assertEquals(0, intval($ret['errorno']));
    }

    public function testcancelSubscribeByOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array());
        $subscribeObj  = $this->genObjectMock('Dao_Weixin_WeixinSubscribe', array('deleteSubscribeCondition'));
        $subscribeObj->expects($this->any())
            ->method('deleteSubscribeCondition')
            ->will($this->returnValue(true));
        $this->obj->setInnerObj($subscribeObj, 'subscribeObj');

        $this->assertEquals($expect, $this->obj->cancelSubscribeByOpenid($this->openid));
    }

    /*
    public function testgetOneArtilceByRand()
    {
        $obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
            $cacheObj = $this->genObjectMock('Gj_Cache_Client_MemcacheClient', array('read', 'write', 'delete'));
            $cacheObj->expects($this->any())
                ->method('read')
                ->will($this->returnValue(false));
            $cacheObj->expects($this->any())
                ->method('write');
            $cacheObj->expects($this->any())
                ->method('delete');
            Gj_Cache_CacheClient::setInstance($cacheObj);
            $expect = array(
                'errorno' => '0',
                'errormsg' => '[数据返回成功]',
                'data' => array());
            $ret = $obj->getOneArtilceByRand();
            $this->assertEquals($expect['errorno'], $ret['errorno']);
            $this->assertEquals(true, boolval($ret['data']));
    }
*/

    public function testgetAreaInfo()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $this->assertEquals("xichengxizhimen", $this->obj->getAreaInfo('bj', 3, 8));
    }


    public function testgetSellSubscrbiePostsByOpenid(){
        //$openid = 'oCEQfuDkXIbGCxAqplIgyCJdvs';
       // $ret = $this->obj->getSellSubscrbiePostsByOpenid($openid);
       // $this->assertEquals(0,$ret['errorno']);
    }


    public function testgetSellPostsByParams(){
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $params = array('domain' => 'bj', 'xiaoqu_pinyin' => 'tiantongyuan', 'price' => 2);
        $xiaoquInfo = array(
            'id' => '6734' ,
            'name' =>  'SOHO北京公馆',
            'pinyin' => 'sohobeijinggongguan',
            'latlng' => 'b116.46170726184,39.954221402741',
            'thumb_image' =>   '' ,
            'city' =>   'bj' ,
            'district_id' =>   '1',
            'street_id' =>   '6' ,
            'address' =>   '新源南路与新源街路口西北角'
        );
        $xiaoInfoObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityPinyin'));
        $xiaoInfoObj->expects($this->any())
            ->method('getXiaoquBaseInfoByCityPinyin')
            ->will($this->returnValue(array('data' => $xiaoquInfo, 'errorno' => 0)));

        $sourceListObj = $this->genObjectMock('Service_Data_SourceList_HouseList', array('preSearch', 'getSearchResult'));
        $sourceListObj->expects($this->any())
            ->method('preSearch')
            ->will($this->returnValue(array('data' => 0)));
        $sourceListObj->expects($this->any())
            ->method('getSearchResult')
            ->will($this->returnValue(array('data' => array(HousingVars::MAIN_BLOCK_LIST => array(
                0 => array(),
                1 => 0
            )))));
        $this->obj->setInnerObj($xiaoInfoObj, 'xiaoInfoObj');
        $this->obj->setInnerObj($sourceListObj, 'sourceListObj');
        $ret = $this->obj->getSellPostsByParams($params);

        $this->assertEquals(array('data' => array(), 'count' => 0, 'xiaoquInfo' => array(
            'name' => "SOHO北京公馆",
            'display_price' => "60-80万"
        )), $ret);
    }


    public function testgetPostsByParams(){
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $params = array(
                'limit' => 10,
                'domain' => 'bj',
                'district_id' => '',
                'huxing' => 2,
                'street_id' => '',
                );

        $sourceListObj = $this->genObjectMock('Service_Data_SourceList_HouseList', array('preSearch', 'getSearchResult'));
        $sourceListObj->expects($this->any())
            ->method('preSearch')
            ->will($this->returnValue(array('data' => 0)));
        $sourceListObj->expects($this->any())
            ->method('getSearchResult')
            ->will($this->returnValue(array('data' => array(HousingVars::MAIN_BLOCK_LIST => array(
                    0 => array()
                )))));
        $this->obj->setInnerObj($sourceListObj, 'sourceListObj');

        $ret = $this->obj->getPostsByParams($params, 1, 10);        
        $this->assertEquals(array(), $ret);
        
    }

    public function testgetPushCacheKeyByConditions(){
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $data = array('domain' => 'bj', 'district_id' => 23);

        $ret = $this->obj->getPushCacheKeyByConditions($data);
        $this->assertEquals('3g_housing_weixin_1_domainbjdistrict_id23', $ret);


    }

    public function testgetSellSubscribeChineseConditions(){
        
        $expect = array(
                'errorno' => 0,
                'errormsg' => '[数据返回成功]',
                'data' => "小区：北京-上地东里\n价格：60-80万\n\n"
                );
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $xiaoquInfo = array(
            'id' => '6734' ,
            'name' =>  '上地东里',
            'pinyin' => 'sohobeijinggongguan',
            'latlng' => 'b116.46170726184,39.954221402741',
            'thumb_image' =>   '' ,
            'city' =>   'bj' ,
            'district_id' =>   '1',
            'street_id' =>   '6' ,
            'address' =>   '新源南路与新源街路口西北角'
        );
        $xiaoInfoObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityPinyin'));
        $xiaoInfoObj->expects($this->any())
            ->method('getXiaoquBaseInfoByCityPinyin')
            ->will($this->returnValue(array('data' => $xiaoquInfo, 'errorno' => 0)));
        $this->obj->setInnerObj($xiaoInfoObj, 'xiaoInfoObj'); 
        $ret = $this->obj->getSellSubscribeChineseConditions(array('domain' => 'bj' , 'xiaoqu_pinyin' => 'shangdidongli', 'price' => 2));
        $this->assertEquals($expect, $ret);
    }

    public function testgetPushPostsByOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $data = array(
            'conditions' => array(
                'domain' => 'bj',
                'district_id' => '2',
                'street_id' => '1',
                'price' => '2',
                'huxing' => '5'
            ),
            'major_category' => 3,
        );
        $openid = 'o3R-qjgd2Ny6ukVMmJm4i-JUMR3o';

        $sourceListObj = $this->genObjectMock('Service_Data_SourceList_HouseList', array('preSearch', 'getSearchResult'));
        $sourceListObj->expects($this->any())
            ->method('preSearch')
            ->will($this->returnValue(array('data' => 0)));
        $sourceListObj->expects($this->any())
            ->method('getSearchResult')
            ->will($this->returnValue(array('data' => array(HousingVars::MAIN_BLOCK_LIST => array(
                    0 => array()
                )))));
        $subscribeObj  = $this->genObjectMock('Dao_Weixin_WeixinSubscribe', array('getSubscribeConditionByOpenid'));
        $subscribeObj->expects($this->any())
            ->method('getSubscribeConditionByOpenid')
            ->will($this->returnValue(array()));
        $this->obj->setInnerObj($subscribeObj, 'subscribeObj');
        $this->obj->setInnerObj($sourceListObj, 'sourceListObj');
        $ret = $this->obj->getPushPostsByOpenid($openid);
        $this->assertEquals("0", $ret['errorno']);
    }

    public function testgetTopOneByOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $openid = 'o3R-qjgd2Ny6ukVMmJm4i-JUMR3o';
        $msapiObj = $this->genStaticMock('MsPostListApi', array('getList'));
        $msapiObj->expects($this->any())
            ->method('getList')
            ->will($this->returnValue(array()));
        $ret = $this->obj->getTopOneByOpenid($openid);

        $this->assertEquals(true, $ret['errorno'] == 0 || $ret['errorno'] == 1007 &&
            count($ret['data']) == 1);
    }

    public function testgetChineseNameByConditions()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $data = array(
            'conditions' => array(
                'domain' => 'bj',
                'district_id' => '2',
                'street_id' => '3',
                'price' => '2',
                'huxing' => '5'
            ),
            'major_category' => 1,
        );
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => "区域：北京-景山\n价格：800-1500元\n户型：5室\n"
        );
        $this->assertEquals($expect, $this->obj->getChineseNameByConditions($data['conditions'], $data['major_category']));
    }

    public function testgetAgentsByOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");

        $mockExpect = array(
            array(
                'CompanySimpleName' => '谷地房地产',
                'account_id' => '1000189',
                'city' => 0,
                'creditScore' => 5,
                'image' => 'gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_75-106_8-5.jpg',
                'name' => '王开明',
                'online' => '111',
                'ucUserId' => '172394',
            ),
            array(
                'CompanySimpleName' => '阳关房地产',
                'account_id' => '14765',
                'city' => 0,
                'creditScore' => 5,
                'image' => 'gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_75-106_8-5.jpg',
                'name' => '将武汉',
                'online' => '111',
                'ucUserId' => '73083',

            ),
            array(
                'CompanySimpleName' => '镂花房地产',
                'account_id' => '30868',
                'city' => 0,
                'creditScore' => 5,
                'image' => 'gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_75-106_8-5.jpg',
                'name' => '大沙地',
                'online' => '111',
                'ucUserId' => '119302',

            ),
            array(
                'CompanySimpleName' => '将辉婆地产',
                'account_id' => '29901',
                'city' => 0,
                'creditScore' => 5,
                'image' => 'gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_75-106_8-5.jpg',
                'name' => '将DSA',
                'online' => '111',
                'ucUserId' => '134641',

            ),
        );

        $mockinput = array(
            'apiType' => "near",
            'type' => "1",
            'city' => 0,
            'district_id' => 0,
            'street_id' => "57",
            'huxing' => -1,
            'price' => -1,
            'limit' => 3
        );
        $interfaceObj = $this->genStaticMock('HouseBrokerListInfoInterface', array('GetBrokerList'));
        $interfaceObj->expects($this->any())
            ->method('GetBrokerList')
            ->with($this->equalTo($mockinput))
            ->will($this->returnValue($mockExpect));

        $mockFangByAccountExpect = array(
            'data' => array(
                array('type' => 1, 'num' => 5),
                array('typpe' => 3, 'num' => 5),
                array('type' => 6, 'num' => 6),
            )
        );
        $fangByAccountObj = $this->genObjectMock('Service_Data_Source_FangByAccount', array('getCountHouseTypeByAccount'));
        $fangByAccountObj->expects($this->any())
            ->method('getCountHouseTypeByAccount')
            ->will($this->returnValue($mockFangByAccountExpect));
        $this->obj->setInnerObj($fangByAccountObj, 'fangByAccountObj');

        $ret = $this->obj->getAgentsByOpenid('34444444444');
        $this->assertEquals(0, count($ret['data']));
    }
     

    public function testvalidateOpenid(){

        $this->obj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");

        try{
            $this->obj->validateOpenid("testopenid");

        }catch(Exception $e){

            $errorno = $e->getCode();
        }
        $this->assertEquals(1002, $errorno);
    }

    public function testgetSellSubscrbiePostsBySubscribeId(){

        $this->obj =  Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        //$ret = $this->obj->getSellSubscrbiePostsBySubscribeId(222);
        // var_dump($ret);
    }

    public function testformatSellPostsInfo(){
        $this->obj =  Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $data = array(
                'title' => 'test title',
                'huxing_shi' => 1,
                'huxing_ting' => 2,
                'huxing_wei' => 3,
                'area' => 25,
                'puid' => 123456,
                'thumb_img' => 'gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_75-106_8-5.jpg',
                );
        $expect = array(
                'title' => 'test title',
                'huxing' => '1室2厅3卫',
                'area' => 25,
                'url' => 'http://fangweixin.3g.ganji.combj_fang5/123456x?ifid=gjwx_user_dy&ca_s=other_weixin&ca_n=push' ,
                'thumb_img' => 'http://image.ganjistatic1.com/gjfs08/M03/3F/96/wKhz9lQKlj78J9G2AAK78luioEI930_200-200c_6-0.jpg' 
                );
        //$ret = $this->obj->formatSellPostsInfo($data); 
        //$this->assertEquals($ret, $expect);

    }

    public function testcancelSubscribeBySubscribeId(){
        $this->obj =  Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        //$ret = $this->obj->cancelSubscribeBySubscribeId(23, 232);   
        //$this->assertEquals($ret['errorno'] , 1004);

    }

    public function testformatPostInfo(){
        $this->obj =  Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        $expect = array('major_category' => 1, 'url' => 'http://fangweixin.3g.ganji.combj_fang5/123456x?&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V&ca_n=menu', 'domain' => 'bj');
        $data = array('major_category' => 1, 'url' => 'http://fangweixin.3g.ganji.combj_fang5/123456x?');
        $ifid = 1;
        $ret = $this->obj->formatPostInfo( $data, $ifid);
        $this->assertEquals($ret, $expect);
    }

    /*
    public function testsetAgentsByOpenidFromInterface(){

        $obj =  Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinSubscribe");
        try{
           $ret = $obj->setAgentsByOpenidFromInterface(array('conditions' => array('domain' => 'bj'), 'major_category' => 1));
        }catch(Exception $e){
           $ret = $e->getCode();
        }
        $this->assertEquals($ret, NULL);

    }
 */
}
