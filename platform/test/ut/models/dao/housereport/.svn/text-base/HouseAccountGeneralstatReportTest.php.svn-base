<?php
/*
 * File Name:HouseAccountGeneralstatReportTest.php
 * Author:lukang
 * mail:lukang@ganji.com
*/
class HouseAccountGeneralstatReportTest extends Testcase_PTest
{

    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['list'] = array('pv'=>123);
        $this->data['count'] = 1;
        $this->pageArr['currentPage'] = 1;
        $this->pageArr['pageSize'] = 20;
        $this->arrConds = array("accountid IN (1234)",'reportdate >='=>'2014-09-28','reportdate <='=>'2014-09-28',"housetype IN (0)");
        $this->orderField = 'reportdate';
    } 

    public function testGetAccountPremierReportDetail(){
        $mock = $this->genEasyObjectMock("Dao_Housereport_HouseAccountGeneralstatReport",array('selectByPage','selectByCount'),
            array('selectByPage'=>$this->data['list'],'selectByCount'=>$this->data['count']));        

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountGeneralstatReport",$mock);
        $obj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
        $obj->__construct();
        $ret = $obj->getAccountPremierReportDetail(array('AccountId'),'2014-09-29',$this->pageArr,$this->arrConds,$this->orderField);
        //对返回的字段键进行验证
        $this->assertEquals($ret,$this->data);

        $mock = $this->genEasyObjectMock("Dao_Housereport_HouseAccountGeneralstatReport",array('selectByPage','selectByCount'),
            array('selectByPage'=>$this->data['list'],'selectByCount'=>$this->data['count']));        

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountGeneralstatReport",$mock);
        $obj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
        $obj->__construct();
        $ret = $obj->getAccountPremierReportDetail(array(),'2014-09-29',$this->pageArr,$this->arrConds,$this->orderField);
        //对返回的字段键进行验证
        $this->assertEquals($ret,$this->data);

    }

    public function testGetAccountPremierReportList(){
        $mock = $this->genEasyObjectMock("Dao_Housereport_HouseAccountGeneralstatReport",array('selectByPage','selectByCount'),
            array('selectByPage'=>$this->data['list'],'selectByCount'=>$this->data['count']));        

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountGeneralstatReport",$mock);
        $obj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
        $obj->__construct();
        $ret = $obj->getAccountPremierReportList(array('AccountId'),'2014-09-29',$this->pageArr,$this->arrConds,$this->orderField);
        //对返回的字段键进行验证
        $this->assertEquals($ret,$this->data);
    }
}
