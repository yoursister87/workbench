<?php
/**
* @package
* @subpackage
* @brief                $微信活跃用户表测试$
* @file                 WeixinUserActiveTest.php
* @author               Author: liuhu<liuhu@ganji.com> 
* @file                 
* @version              
* @lastChangeBy         2015-01-08 
* @lastmodified         下午2:38
* @copyright            Copyright (c) 2014, www.ganji.com
*/
class WeixinUserActiveTest extends Testcase_PTest
{
    protected $obj;
    protected $openid = 'ofL_bjnQDjAE1nb1lbddRATywa3Q';

    public function __construct()
    {
        $this->obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinUserActive');
    }

    public function testupdateActiveTime()
    {
 //       $this->assertTrue($this->obj->updateActiveTime($this->openid));
        /*
        *异常测试
         */  
        try{
          $this->obj->updateActiveTime(null);
          echo 'F';
        } catch (Exception $e){
            $this->assertInstanceOf('Gj_Exception', $e);
        }
    }

    public function testgetUserList()
    {
       /*
       *异常测试
        */
       try {
        $ret = $this->obj->getUserList(null);
        echo 'F';
       } catch (Exception $e) {
           $this->assertInstanceOf('Gj_Exception', $e);
       }
    }
}

