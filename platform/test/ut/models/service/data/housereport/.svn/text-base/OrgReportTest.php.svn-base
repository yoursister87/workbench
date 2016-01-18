<?php
/*
 * File Name:OrgReportTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class OrgReportTest extends Testcase_PTest{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Data_HouseReport_OrgReport();
        
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;

        $this->result = array('list'=>array(array('org_id'=>1,'account_pv'=>'100','refresh_count'=>100)));

    }

    public function testGetOrgPremierReportById(){
        $ret = $this->obj->getOrgPremierReportById('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 

        $orgId = 1234;
        $obj = $this->genObjectMock("Dao_Housereport_HouseCompanyOrgReport", array("selectByPage")); 
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseCompanyOrgReport", $obj);
        $orgObj = new Service_Data_HouseReport_OrgReport();
        $res = $orgObj->getOrgPremierReportById($orgId,false);
        $this->data['data']['list'] = $this->result;
        $this->assertEquals($this->data['data']['list'],$res['data']['list']);
    }

     public function testGetOrgPremierReportList(){
        $ret = $this->obj->getOrgPremierReportList('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
        
        $orgId = array(1234);
        $obj = $this->genObjectMock("Dao_Housereport_HouseCompanyOrgReport", array("getSumList",'selectByPage')); 
        $obj->expects($this->any())
            ->method('getSumList')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result['list']));

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseCompanyOrgReport", $obj);
        $orgObj = new Service_Data_HouseReport_OrgReport();
        $res = $orgObj->getOrgPremierReportList($orgId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);
     }

    public function testGetOrgAssureReportById(){
        $ret = $this->obj->getOrgAssureReportById('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 

        $orgId = 1234;
        $obj = $this->genObjectMock("Dao_Housereport_HouseOrgReportV2", array("selectByPage")); 
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseOrgReportV2", $obj);
        $orgObj = new Service_Data_HouseReport_OrgReport();
        $res = $orgObj->getOrgAssureReportById($orgId,false);
        $this->data['data']['list'] = $this->result;
        $this->assertEquals($this->data['data']['list'],$res['data']['list']);
    }

    public function testGetOrgAssureReportList(){
        $ret = $this->obj->getOrgAssureReportList('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 

        $orgId = array(1234);
        $obj = $this->genObjectMock("Dao_Housereport_HouseOrgReportV2", array("getSumList",'selectByPage')); 
        $obj->expects($this->any())
            ->method('getSumList')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result['list']));

 
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseOrgReportV2", $obj);
        $orgObj = new Service_Data_HouseReport_OrgReport();
        $res = $orgObj->getOrgAssureReportList($orgId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);
    }
}
