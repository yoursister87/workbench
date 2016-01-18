<?php
/*
 * File Name:HouseCompanyOrgReportTest.php
 * Author:lukang
 * mail:lukang@ganji.com
*/
class HouseCompanyOrgReportTest extends Testcase_PTest
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
        $mock = $this->genEasyObjectMock("Dao_Housereport_HouseCompanyOrgReport",array('selectByPage','selectByCount'),
            array('selectByPage'=>$this->data['list'],'selectByCount'=>$this->data['count']));        

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseCompanyOrgReport",$mock);
        $obj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseCompanyOrgReport');
        $obj->__construct();
        $ret = $obj->getSumList(array('AccountId'),$this->pageArr);
        //对返回的字段键进行验证
        $this->assertEquals($ret,$this->data);
    }
}
