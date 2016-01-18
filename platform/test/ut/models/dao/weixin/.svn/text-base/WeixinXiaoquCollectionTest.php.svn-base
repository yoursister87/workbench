<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏数据操作测试$
 * @file                 WeixinCollectionTest.php
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         2015-05-12
 * @lastmodified         上午11:40
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WeixinXiaoquCollectionObj extends Dao_Weixin_WeixinXiaoquCollection
{
	public function selectByPage($arrFields,$arrConds,$currentPage = 1,$pageSize = 10,$orderArr = array(),$appends = null)
	{
		return false;
	}
	public function insert($arrRows)
	{
		return 123;
	}
    public function deleteAllCollectionsByOpenid($openid)
    {
        return false;
    }
    public function deleteOneCollectionsByOpenidAndXiaoquID($openid,$xiaoqu_id)
    {
        return false;
    }
}

class WeixinXiaoquCollectionTest extends Testcase_PTest
{
    protected $obj;
    protected $time = '1431317181';
    protected $openid = 'onrqLs-el3DUTTcLeQJyLbNK1N-U';

    public function __construct()
    {
    }
    /**
      *写入小区收藏信息 
    */
    public function testinsertOneCollection()
    {
        $data = array(
            'xiaoqu_id' => '10075',
            'openid' => $this->openid,
            'name' =>'航华新苑新区1',
            'pinyin'=>'sh',
            'thumb_img' => 'http://image.ganjistatic1.com/gjfs01/M00/E1/43/wKhxwFFSITabImYzAAL4Ftm,WJo275_200-200c_6-0.jpg',
            'url' => 'http://fangweixin.3g.ganji.com/sh_xiaoqu/hanghuaxinyuan/?vvcc=3g&ifid=gjwx_gj_sc&ca_s=other_weixin&c',
            'city_name' => '上海',
            'district_name'=>'闵行',
            'street_name'=>'航华',
            'avg_price'=>'45120',
            'finish_at'=>'2015-10-01',
            'create_time' => $this->time
        );
		//$mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        //$mockObj->expects($this->any())
        //        ->method('select')
        //        ->will($this->returnValue($list));
		
		/*$mockObj = $this->genObjectMock('Dao_Weixin_WeixinXiaoquCollection', array('insert'));
        $mockObj->expects($this->any())
                ->method('insert')
                ->will($this->returnValue(true));
		*/
		$new_obj = Gj_LayerProxy::getProxy('WeixinXiaoquCollectionObj');
		//$new_obj = new WeixinXiaoquCollectionObj();
		$this->assertEquals(true, $new_obj->insertOneCollection($data));
    }
	/**
	 * 获取当前微信用户下所有的收藏数据
	 * @return array();
	*/
    public function testselectCollections()
    {
		//$this->obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinXiaoquCollection');
        $fields = array('openid', 'xiaoqu_id', 'name', 'pinyin', 'thumb_img', 'url', 'city_name', 'district_name', 'street_name', 'avg_price', 'finish_at');
		$openid ='onrqLs-el3DUTTcLeQJyLbNK1N-Y';
		$return_array  = array(
					array(
						'openid'=>'onrqLs-el3DUTTcLeQJyLbNK1N-Y',
						'xiaoqu_id'=>'10068',
						'name'=>'航华新苑新区1',
						'pinyin'=>'sh',
						'thumb_img'=>'http://image.ganjistatic1.com/gjfs01/M00/E1/43/wKhxwFFSITabImYzAAL4Ftm,WJo275_200-200c_6-0.jpg',
						'url'=>'http://fangweixin.3g.ganji.com/sh_xiaoqu/hanghuaxinyuan/?vvcc=3g&ifid=gjwx_gj_sc&ca_s=other_weixin&c',
						'city_name'=>'上海',
						'district_name'=>'闵行',
						'street_name'=>'航华',
						'avg_price'=>'45120',
						'finish_at'=>'2015-10-01'
					)
				);
		$obj = Gj_LayerProxy::getProxy('WeixinXiaoquCollectionObj');
        $this->assertEquals(false, $obj->selectCollections($openid, $fields));
    }
	/**
	 * 删除该openid下的所有收藏数据
	 * @return true;
	*/
    public function testdeleteAllCollectionsByOpenid()
    {
		$this->obj = Gj_LayerProxy::getProxy('WeixinXiaoquCollectionObj');
		$openid ='onrqLs-el3DUTTcLeQJyLbNK1N-W';
		$this->assertEquals(false, $this->obj->deleteAllCollectionsByOpenid($openid));
    }
		/**
	 * 删除该openid下制定的一个小区ID信息
	*/
	public function testdeleteOneCollectionsByOpenidAndXiaoquID()
	{
		$this->obj = Gj_LayerProxy::getProxy('WeixinXiaoquCollectionObj');
		$openid ='onrqLs-el3DUTTcLeQJyLbNK1N-L';
		$xiaoqu_id = '10054';
		$this->assertEquals(false, $this->obj->deleteOneCollectionsByOpenidAndXiaoquID($openid,$xiaoqu_id));
	}
}
