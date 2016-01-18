<?php
/**
 * @package
 * @subpackage
 * @brief                $微信用户测试$
 * @file                 WeixinUserTest.php
 * @author               Author: liuhu<liuhu@ganji.com> 
 * @file                 
 * @version              
 * @lastChangeBy         2015-01-08 
 * @lastmodified         下午2:38
 * @copyright            Copyright (c) 2014, www.ganji.com
  */
class WeixinUserChild extends Dao_Weixin_WeixinUser
{
    public function __construct()
    {
        parent::__construct('');
    }
    public function update($data, $conds)
    {
        if (!(isset($data['uid']) && isset($data['nickname']) && isset($data['sex']) && isset($data['language']) && isset($data['city']) && 
            isset($data['province']) && isset($data['country']) && isset($data['headimgurl']) && isset($data['subscribe_time']) && isset($data['subscribe_status']))) {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
        return true;
    }
    public function insert($data)
    {
        if($data['openid'] ==null)
        {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        return true;
    }

    public function select($fields , $conds)
    {
        if($conds['openid = '] == 'ofL_bjnjF6Hys4DEyBLWun08yrYk')
            return true;
        else
            return false;
    }
}


class WeixinUserTest extends Testcase_PTest
{
    protected $obj;
    protected $openid = 'ofL_bjnjF6Hys4DEyBLWun08yrYk';

    public function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
        //$this->obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinUser');
        $this->obj = new WeixinUserChild();
    }

	public function testexistUser()
	{
		$this->assertTrue($this->obj->existUser($this->openid) );
        $this->assertFalse($this->obj->existUser('no-exist-openid'));
        /*
          openid为空的异常测试
         */
        try{
            $this->obj->existUser(null);
            echo 'F';
        }catch (Exception $e) {
            $this->assertInstanceOf('Gj_Exception', $e);
        }
	}
   
	public function testaddUser()
	{
        $data = array(
                'openid' => 'someOpenid',
                'uid' => '0',
                'nickname' => 'Pook',
                'sex' => '1',
                'language' => 'zh_CN',
                'city' => '湖北',
                'province' => '武汉',
                'country' => '中国',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM6OgWTeDguiaMYbhPY5jTEuLKeoQ3nWwR6MLbTGkktibaFxCSlExFjNY8icdiaxeNRDXEnga6HsJJA7hQ/0',
                'subscribe_time' => '1418283055',
                'unionid' => 's',
                'subscribe_status' => '1'
            );
        /*
        $mockObj = $this->genObjectMock('Dao_Weixin_WeixinUser', array('insert'));
        $mockObj->expects($this->any())
                ->method('insert')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Dao_Weixin_WeixinUser', $mockObj);
        */
        $this->assertTrue($this->obj->addUser($data));
        $data['openid'] = null;
        try
        {
            $this->obj->addUser($data);
        }
       catch (Exception $e)
       {
           $this->assertInstanceOf('Gj_Exception', $e);
       }

	}
	
	public function testupdateUser()
	{
        $noExistOpenid = 'no-exist-openid';
        $existOpenid = $this->openid;
        $nullOpenid = null;
		$data = array(
            'id' => 9,
            'openid' => $existOpenid,
            'uid' => '0',
            'nickname' => 'Like',
            'sex' => '1',
            'language' => 'zh_CN',
            'city' => '湖南',
            'province' => '长沙',
            'country' => '中国',
            'headimgurl' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM6OgWTeDguiaMYbhPY5jTEuLKeoQ3nWwR6MLbTGkktibaFxCSlExFjNY8icdiaxeNRDXEnga6HsJJA7hQ/0',
            'subscribe_time' => '1418283089',
            'unionid' => '1',
            'subscribe_status' => '1'
        );
        $this->assertTrue($this->obj->updateUser($data));
        $data['openid'] = $noExistOpenid;
        try
        {
            $this->assertTrue($this->obj->updateUser($data));
        }
        catch (Exception $e) 
        {
            $this->assertInstanceOf('Gj_Exception', $e);
        }
	}
}
