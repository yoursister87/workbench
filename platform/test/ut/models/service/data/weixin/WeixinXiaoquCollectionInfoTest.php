<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏接口单元测试$
 * @file                 WeixinCollectionInfoTest.php
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         上午10:55
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Weixin_WeixinXiaoquCollectionMock extends Service_Data_Weixin_WeixinXiaoquCollection
{
	public function saveXiaoquCollection($xiaoquInfo, $annotationData)
	{
		return true;
	}
	
	public function clearXiaoquCollectionByOpenId($openid = null)
	{
		$expect = array('errorno' => '1004','errormsg' => '[逻辑执行失败]','data' => array());
		
        return $expect;
	}
	public function checkParams($paramsReal = array())
    {
        return parent::checkParams($paramsReal);
    }
}

class WeixinXiaoquCollectionInfoTest extends Testcase_PTest
{
	protected $obj; 
	protected static $default_img = 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png';
    protected $postNotFromDb = array(
        'puid' => 855239,
        'city' => 0,
        'district_name' => '宣武',
        'street_name' => '菜市口',
        'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
        'price' => '900',
        'person' => '小伟',
        'phone' => '13829760518',
        'major_category' => 1,
        'agent' => 1,
        'title' => '出租菜市口中信城,高档小区,新房,南北通透户型,地铁边,精装',
        'xiaoqu' => '中信城',
        'xiaoqu_address' => '宣武区菜市口大街',
        'area' => '96.0',
        'huxing_shi' => 2,
        'huxing_ting' => 2,
        'huxing_wei' => 1,
        'auth_status' => '0',
    );
	
	public function testgetXiaoquInfoById()
	{
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
		
		//$fangQueryObj = $this->genObjectMock('Service_Data_Weixin_WeixinXiaoquCollectionMock', array('saveXiaoquCollection'));
        //$fangQueryObj->expects($this->once())
        //             ->method('saveXiaoquCollection')
        //             ->will('true');
		$expect = array(
			'errorno'=>'0','errormsg'=>'[数据返回成功]',
				'data'=>array(
					'thumb_img' =>"http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png",
					'big_img' => "http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png",
					'name' =>"中信泰富又一城",
					'finish_at' =>"2014-06-01",
					'city' =>"上海",
					'pinyin' => "sh",
					'xiaoqu_pinyin' =>"zhongxintaifuyouyicheng",
					'district_name' =>"嘉定",
					'street_name' =>"嘉定城区",
					'avg_price' =>"14701",
					'district_id' =>"11",
					'street_id' =>"3",
					'url' =>"http://fangweixin.3g.ganji.com/sh_xiaoqu/zhongxintaifuyouyicheng/?vvcc=3g&ifid=gjwx_gj_sc&ca_s=other_weixin&ca_n=scan",
				)
		);
		$mockExpect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
				'id' => '230857',
				'thumb_img' => 'gjfs01/M00/BF/5A/wKhxwVFS6gOzbELLAAGyRwP7fMU223_135-100_9-0.jpg',
				"name"=> "中信泰富又一城",
				"pinyin"=>"zhongxintaifuyouyicheng",
				"city"=>"sh",
				"district_id"=>"11",
				"street_id"=>"3",
				"finish_at"=>"2014-06-01",
				"avg"=>"14701",
				"avg_price_change"=>"0.00",
            ));

        $xiaoquInfoObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquInfoById'));
        $xiaoquInfoObj->expects($this->once())
                     ->method('getXiaoquInfoById')
                     ->will($this->returnValue($mockExpect));
        $this->obj->setInnerObj($xiaoquInfoObj, 'xiaoquInfoObj');
		
		$this->assertEquals($expect, $this->obj->getXiaoquInfoById('230857', 'oCEQfuDkXIbGCxSVAqplIgyCJdvs'));
	}
	public function testgetXiaoquCollectionByOpenId()
	{
		$obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
		$expect = array(
			'errorno' =>"0",
			'errormsg' =>"[数据返回成功]",
			'data' =>array(
					'0'=>array(
						'openid' =>"oCEQfuDkXIbGCxSVAqplIgyCJdvs",
						'xiaoqu_id' =>"230857",
						'name' =>"中信泰富又一城",
						'pinyin' =>"sh",
						'thumb_img' =>"http://image.ganjistatic1.com/gjfs01/M00/BF/5A/wKhxwVFS6gOzbELLAAGyRwP7fMU223_200-200c_6-0.jpg",
						'url' => "http://fangweixin.3g.ganji.com/sh_xiaoqu/zhongxintaifuyouyicheng/?vvcc=3g&ifid=gjwx_gj_sc&ca_s=other",
						'city_name' =>"上海",
						'district_name' =>"嘉定",
						'street_name' =>"嘉定城区",
						'avg_price' =>"14701",
						'finish_at' =>"2014-06-01",
					)
			)
		);
		$xiaoquCollectionObj  = $this->genObjectMock('Dao_Weixin_WeixinXiaoquCollection', array('selectCollections'));
        $xiaoquCollectionObj->expects($this->any())
							->method('selectCollections')
							->will($this->returnValue(array()));
        $obj->setInnerObj($xiaoquCollectionObj, 'xiaoquCollectionObj');
		
		$ret = $obj->getXiaoquCollectionByOpenId('oCEQfuDkXIbGCxSVAqplIgyCJdvs',1,1);
		
		 $this->assertEquals(0, intval($ret['errorno']));
	}
	public function testclearXiaoquCollectionByOpenId()
	{
		$obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
        $openid = '1111111111';
        $expect = array(
            'errorno' => '1004',
            'errormsg' => '[逻辑执行失败]',
            'data' => array()
        );
        $this->assertEquals($expect, $obj->clearXiaoquCollectionByOpenId($openid));
	}
	public function testcheckParams()
	{
		$obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
		$this->assertEquals(true,$obj->checkParams(array('abc'=>123)));
	}
	public function testgetImageUrlBySize()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
        $expect = 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_300-200f_5-0.jpg';
        $expect2 = self::$default_img;
        $expect2 = self::$default_img;

        $ret = $this->obj->getImageUrlBySize($this->postNotFromDb['thumb_img'], 300, 200, 'f', 5, 0);
        $ret2 = $this->obj->getImageUrlBySize(self::$default_img, 30, 20, 'c', 6, 0);
        $this->assertEquals($expect, $ret);
        $this->assertEquals($expect2, $ret2);
    }
	public function testsaveXiaoquCollection()
	{
		$obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinXiaoquCollectionMock');
		$this->assertEquals(true,$obj->saveXiaoquCollection(array('abc'),array('openid'=>'11111','xiaoqu_id'=>64521,'pinyin'=>'meigui')));
	}
}
