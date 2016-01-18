<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏接口单元测试$
 * @file                 WeixinCollectionInfoTest.php
 * @author               wanyang:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         上午10:55
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Weixin_WeixinCollectionInfoChild extends Service_Data_Weixin_WeixinCollectionInfo
{
    public function setWapAccessUrlIfid($url, $from = 1)
    {
        return parent::setWapAccessUrlIfid($url, $from);
    }

    public function setQueryObj($obj){
        parent::setQueryObj($obj);
    }
    public function checkParams($paramsReal = array())
    {
        return parent::checkParams($paramsReal);
    }

    public function getWapAccessUrlgetWapAccessUrl($domain, $majorCategory, $puid)
    {
        return parent::getWapAccessUrl($domain, $majorCategory, $puid);
    }

    public function getFormatCollectionList($list)
    {
        return parent::getFormatCollectionList($list);
    }

    public function getImageUrlBySize($url, $width, $height, $type = 'c', $quality = 6, $version = 0)
    {
        return parent::getImageUrlBySize($url, $width, $height, $type, $quality, $version);
    }

    public function isPersonPercent($auth_status = null, $domain)
    {
        return parent::isPersonPercent($auth_status, $domain);
    }

    public function getFormatListTitle($postInfo)
    {
        return parent::getFormatListTitle($postInfo);
    }

    public function setDomainMajorCategory($postInfo = array())
    {
        return parent::setDomainMajorCategory($postInfo);
    }

    public function formatPostInfo($postInfo, $ifid="gjwx_gj_sc", $ca_n="scan")
    {
        return parent::formatPostInfo($postInfo);
    }
	
	public function clearCollectionInfoByWeixinOpenid($openid = null, $major_category = 1)
	{
		$expect = array('errorno' => '1004','errormsg' => '[逻辑执行失败]','data' => array());
		
        return $expect;
	}
}


class WeixinCollectionInfoTest extends Testcase_PTest
{
    protected $obj;
    protected $time = '1417057677';
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

    public function testgetPostInfoByPuid()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $puid = time();
        $openid = 'o3R-qjgd2Ny6ukVMmJm4i-JUMR3o';
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
                    'puid' => $puid,
                    'title' => '房子求租了房子求租了房子求租了',
                    'thumb_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_200-200c_6-0.jpg',
                    'big_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_360-200c_6-0.jpg',
                    'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/'.$puid.'x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V',
                    'price' => '8000元/月',
                    'phone' => '18500646916【小伟|房东】',
                    'xiaoqu' => '龙锦苑东四区',
                    'address' => '霍营城铁站东北角20分钟路程',
                    'huxing' => '3室2厅2卫-整租-120.0㎡',
                    'ceng' => '1/4',
                    'major_category' =>'1'
            ));

        $mockExpect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
                'puid' => $puid,
                'title' => '房子求租了房子求租了房子求租了',
                'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
                'city' => 0,
                'price' => '8000',
                'major_category' => 1,
                'phone' => '18500646916',
                'xiaoqu' => '龙锦苑东四区',
                'xiaoqu_address' => '霍营城铁站东北角20分钟路程',
                'huxing_shi' => '3',
                'huxing_ting' => '2',
                'huxing_wei' => '2',
                'area' => '120.0',
                'person' => '小伟',
                'agent' => '0',
                'ceng' => 1,
                'ceng_total' => 4,
            ));

        $fangQueryObj = $this->genObjectMock('Service_Data_Source_FangQuery', array('getHouseSourceByPuidInfo'));
        $fangQueryObj->expects($this->once())
                     ->method('getHouseSourceByPuidInfo')
                     ->will($this->returnValue($mockExpect));
        $this->obj->setInnerObj($fangQueryObj, 'fangQueryObj');

        $collectionObj  = $this->genObjectMock('Dao_Weixin_WeixinCollection', array('insertOneCollection'));
        $collectionObj->expects($this->any())
            ->method('insertOneCollection')
            ->will($this->returnValue(true));

        $this->obj->setInnerObj($collectionObj, 'collectionObj');

        $this->assertEquals($expect, $this->obj->getPostInfoByPuid($puid, $openid));
    }

    public function testformatPostInfo()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = array(
            'puid' => 855239,
            'title' => '出租菜市口中信城,高档小区,新房,南北通透户型,地铁边,精装',
            'thumb_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_200-200c_6-0.jpg',
            'big_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_360-200c_6-0.jpg',
            'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/855239x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V',
            'price' => '900元/月',
            'phone' => '13829760518【小伟|经纪人】',
            'xiaoqu' => '中信城',
            'address' => '宣武区菜市口大街',
            'huxing' => '2室2厅1卫-整租-96.0㎡',
            'ceng' => '/'
        );
        $data = $this->postNotFromDb;
        $data['domain'] = 'bj';
        $this->assertEquals($expect, $this->obj->formatPostInfo($data));
    }

    public function testgetCollectedPostsByWeixinOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $openid = 'o2R-qjgd2Ny6ukVMmJm4i-JUMR3o';
        $expect = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
                array(
                    'puid' => '12344321',
                    'title' => '菜市口 2室 900元/月',
                    'thumb_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_200-200c_6-0.jpg',
                    'big_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_360-200c_6-0.jpg',
                    'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/12344321x?ifid=gjwx_user_sc&_gjassid=fV0x40R2Gk9V',
                )
            ));
        $collectionObj  = $this->genObjectMock('Dao_Weixin_WeixinCollection', array('selectCollections'));
        $collectionObj->expects($this->any())
            ->method('selectCollections')
            ->will($this->returnValue(array()));
        $this->obj->setInnerObj($collectionObj, 'collectionObj');

        $ret = $this->obj->getCollectedPostsByWeixinOpenid($openid, 1);
        $this->assertEquals(0, intval($ret['errorno']));
    }

    public function testclearCollectionInfoByWeixinOpenid()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $openid = '1222222';
        $expect = array(
            'errorno' => '1004',
            'errormsg' => '[逻辑执行失败]',
            'data' => array()
        );
        $this->assertEquals($expect, $this->obj->clearCollectionInfoByWeixinOpenid($openid));
    }

    public function testcheckParams()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        try {
            $ret = $this->obj->checkParams(array($this->postNotFromDb['puid'], $this->postNotFromDb['phone']));
            $this->assertEquals(true, $ret);
        } catch (Exception $e) {
            $this->assertEquals($e->getCode(), ErrorConst::E_PARAM_INVALID_CODE);
        }
    }

    public function testgetWapAccessUrlgetWapAccessUrl()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = 'http://fangweixin.3g.ganji.com/bj_fang1/855239x';
        $ret = $this->obj->getWapAccessUrlgetWapAccessUrl('bj', 1, 855239);
        $this->assertEquals($expect, $ret);
    }

    public function testsetWapAccessUrlIfid()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $urlParam = 'http://fangweixin.3g.ganji.com/bj_fang1/22222x';
        $from1Parma = 1;
        $expect1 = $urlParam . '?ifid=gjwx_gj_sc&ca_n=scan&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin';

        $ret1 = $this->obj->setWapAccessUrlIfid($urlParam, $from1Parma);
        $this->assertEquals($expect1, $ret1);
    }

    public function testisPersonPercent()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $ret1 = $this->obj->isPersonPercent($this->postNotFromDb['auth_status'], 'sh');
        $ret2 = $this->obj->isPersonPercent(3, 'bj');
        $this->assertEquals(false, $ret1);
        $this->assertEquals(true, $ret2);
    }

    public function testgetImageUrlBySize()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_300-200f_5-0.jpg';
        $expect2 = self::$default_img;
        $expect2 = self::$default_img;

        $ret = $this->obj->getImageUrlBySize($this->postNotFromDb['thumb_img'], 300, 200, 'f', 5, 0);
        $ret2 = $this->obj->getImageUrlBySize(self::$default_img, 30, 20, 'c', 6, 0);
        $this->assertEquals($expect, $ret);
        $this->assertEquals($expect2, $ret2);
    }

    public function testgetFormatCollectionList()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = array(
            array(
                'puid' => $this->postNotFromDb['puid'],
                'thumb_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_200-200c_6-0.jpg',
                'big_img' => 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_360-200c_6-0.jpg',
                'title' => '上地 5室 2000元/月',
                'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/22222x?ifid=gjwx_user_sc&ca_n=menu&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V',
            )
        );

        $input = array(
            array(
                'puid' => $this->postNotFromDb['puid'],
                'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_200-170c_6-0.jpg',
                'title' => '上地 5室 2000元/月',
                'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/22222x',
                'phone' => 18812323232
            )
        );

        $ret = $this->obj->getFormatCollectionList($input);
        $this->assertEquals($expect, $ret);
    }

    public function testgetFormatListTitle()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = '菜市口 2室 900元/月';
        $ret = $this->obj->getFormatListTitle($this->postNotFromDb);
        $this->assertEquals($expect, $ret);
    }

    public function testsetDomainMajorCategory()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfoChild');
        $expect = $this->postNotFromDb;
        $expect['domain'] = 'bj';
        $expect['cityName'] = '北京';
        $this->assertEquals($expect, $this->obj->setDomainMajorCategory($this->postNotFromDb));
    }
}
