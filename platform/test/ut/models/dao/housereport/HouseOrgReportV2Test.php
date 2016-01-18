<?php
/*
 * File Name:HouseOrgReportV2Test.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class HouseOrgReportV2Test extends Testcase_PTest
{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['list'] = array('pv'=>123);
        $this->data['count'] = 1;
        $this->pageArr['currentPage'] = 1;
        $this->pageArr['pageSize'] = 20;
    }

    public function testGetSumList(){
        $mock = $this->genEasyObjectMock("Dao_Housereport_HouseOrgReportV2",array('selectByPage','selectByCount'),
            array('selectByPage'=>$this->data['list'],'selectByCount'=>$this->data['count']));        

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseOrgReportV2",$mock);
        $obj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseOrgReportV2');
        $obj->__construct();
        $ret = $obj->getSumList(array('AccountId'),$this->pageArr);
        //对返回的字段键进行验证
        $this->assertEquals($ret,$this->data);
    }
}
