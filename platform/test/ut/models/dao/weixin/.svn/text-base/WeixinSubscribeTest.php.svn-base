<?php

/**
 * @package
 * @subpackage
 * @brief                $微信订阅表测试$
 * @file                 WeixinSubscribeTest.php
 * @author               $Author:  wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-1
 * @lastmodified         下午2:38
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Weixin_SubChild extends Dao_Weixin_WeixinSubscribe
{
    public function selectByPage($arrFields, $arrConds, $currentPage = 1, $pageSize = 10, $orderArr = array(), $appends = null){

        return array();

    }
    
    public function update($data , $conds){
        
        return true;

    }


    public function selectByCount($arrConds, $appends = NULL){
        return 1;
    }

}
class WeixinSubscribeDAOTest extends Testcase_PTest
{
    protected $openid = 'oHR-qjgd2Ny6ukVMmJm4i-JUMR3o';
    protected $obj;

    public function __construct()
    {
    }

    public function testgetSubscribeConditionByOpenid()
    {
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $ret = $obj->getSubscribeConditionByOpenid($this->openid);
        $this->assertEquals(false, $ret[0]['conditions']);
    }

    public function testinsertSubscribeCondition()
    {
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $data = array(
                'openid' => time().rand(1,20)."test",
                'conditions' => array(
                    'domain' => 'bj',
                    'district_id' => '1',
                    'street_id' => '3',
                    'price' => '2',
                    'huxing' => '5'
                    ),
                'subType' => 1,
                'major_category' => 1,
                'create_time' => time()
                );
        $this->assertInternalType("string", $obj->insertSubscribeCondition($data));
    }


    public function testgetSubscribeConditionBySubscribeId(){

        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $ret = $obj->getSubscribeConditionBySubscribeId(1);
        $this->assertEquals(false, $ret);

    }
    public function testdeleteSubscribeBySubscribeId(){

        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        try{
            $ret = $obj->deleteSubscribeBySubscribeId(1);
        }catch(Exception $e){
            $errorcode = $e->getCode();
        }
        $this->assertEquals(1002, $errorcode);

    }

    public function testupdateSubscribeByScribeId(){

        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $data = array('id' => 2, 'openid' => 22222);
        $ret = $obj->updateSubscribeByScribeId($data);
        $this->assertTrue($ret);

    }

    public function testNotDuplicateCheck(){
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $data = array('id' => 2, 'openid' => 22222);
        $ret = $obj->NotDuplicateCheck($data);
        $this->assertTrue($ret);

    }

    public function testisUnderAllowSize(){
        
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $data = array('id' => 2, 'openid' => 22222);
        $ret = $obj->isUnderAllowSize($data);
        $this->assertTrue(!$ret);
    }

    public function testupdateSubscribeCondition()
    {
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $data = array(
                'openid' => '444444444444444',
                'conditions' => array(
                    'domain' => 'bj',
                    'district_id' => '2',
                    'street_id' => '3',
                    'price' => '2',
                    'huxing' => '4'
                    ));
        try{
            $obj->updateSubscribeCondition($data);
        }catch (Exception $e){
            $this->assertEquals(ErrorConst::E_DATA_NOT_EXIST_CODE, $e->getCode());
        }
    }
    public function testdeleteSubscribeCondition()
    {
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $openid = 'o7R-qjgd2Ny6ukVMmJm4i';
        try{
            $obj->deleteSubscribeCondition($openid);
        }catch (Exception $e){
            $this->assertEquals(ErrorConst::E_DATA_NOT_EXIST_CODE, $e->getCode());
        }
    }

    public function testisSubscribeAlreadyExits(){
        $obj = Gj_LayerProxy::getProxy('Dao_Weixin_SubChild');
        $openid = 'a7R-qjgd2Ny6ukVMmJm4i-JUMR3o';
        $this->assertEquals(false, $obj->isSubscribeAlreadyExits($openid));
    }
}
