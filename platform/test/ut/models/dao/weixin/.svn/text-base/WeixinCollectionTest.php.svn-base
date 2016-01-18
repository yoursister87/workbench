<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏数据操作测试$
 * @file                 WeixinCollectionTest.php
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-27
 * @lastmodified         上午10:59
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WeixinCollectionTestObj extends Dao_Weixin_WeixinCollection 
{
	public function selectByPage($arrFields,$arrConds,$currentPage = 1,$pageSize = 10,$orderArr = array(),$appends = null)
	{
		return array();
	}
      public function update($arrRows, $arrConds)
      {
          return 1;
      }
}
class WeixinCollectionTest extends Testcase_PTest
{
    protected $obj;
    protected $time = '1417057677';
    protected $openid = 'o1R-qjgd2Ny6ukVMmJm4i-JUMR31';
    protected $new_openid;

    public function __construct()
    {
        $this->new_openid = '03RCce-3MQPQmjw-'.time();
    }

    public function testinsertOneCollection()
    {
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinCollection');
        $data = array(
            'puid' => '12344321',
            'openid' => $this->new_openid,
            'title' => '菜市口 2室 900元/月',
            'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
            'url' => 'http://3g.ganji.com/bj_fang1/12344321x',
            'major_category' => 1,
            'create_time' => $this->time
        );
        $this->assertEquals(true, $obj->insertOneCollection($data));
    }

    public function testselectCollections()
    {
        $obj = Gj_LayerProxy::getProxy('WeixinCollectionTestObj');
        $expect = array(
            array(
            'puid' => '12344321',
            'title' => '菜市口 2室 900元/月',
            'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
            'url' => 'http://3g.ganji.com/bj_fang1/12344321x',
            'create_time' => $this->time
            ));
        $fields = array('puid', 'title', 'thumb_img', 'url', 'create_time');
        $this->assertEquals(array(), $obj->selectCollections('failure_'.$this->openid, $fields));

    }

    public function testdeleteAllCollectionsByOpenid()
    {
        $obj = Gj_LayerProxy::getProxy('WeixinCollectionTestObj');
        $this->assertEquals(true, $obj->deleteAllCollectionsByOpenid($this->new_openid));
    }
	
	public function testupdateCollectionsInfoById()
	{
		$value_list = array(
            'puid' => '12344321',
            'openid' => $this->new_openid,
            'title' => '菜市口 2室 900元/月',
            'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
            'url' => 'http://3g.ganji.com/bj_fang1/12344321x',
            'major_category' => 1,
            'create_time' => $this->time
        );
		$obj = Gj_LayerProxy::getProxy('WeixinCollectionTestObj');
        $this->assertEquals(true, $obj->updateCollectionsInfoById(1,$value_list));
	}
}
