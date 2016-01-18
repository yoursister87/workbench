<?php
/**
 * @package              
 * @subpackage           
 * @brief                $微信认证表测试$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @file                 WeixinAuthTest.php$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WeixinAuthTest extends Testcase_PTest
{
    public function __construct()
    {
    }

    public function testgetAccessToken(){
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinAuth');
        $result = $obj->getAccessToken();
        $this->assertEquals(true, is_array($result) || $result === false);
    }

    public function testaddAccessToken(){
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinAuth');
        $data = array(
            'access_token' => 'FsKreJaTXJM50XqSLjyExlBz-TqUoQgjrDZLfiGNHmGkERkYKD0NZavuQ3AgXQRDszEmYWGMEQAnze6AQ4JogqMaxCSrNEhsY4mUT9L4N7U',
            'expire_at' => time()+5*60);
        $result = $obj->addAccessToken($data);
        $this->assertEquals(true, $result);
    }
}
