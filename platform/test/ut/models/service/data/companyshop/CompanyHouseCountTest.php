<?php
/*
 * File Name:CompanyHouseCountTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
require dirname(__FILE__).'/CompanyHouseCountMock.php';
class CompanyHouseCountTest extends Testcase_PTest{
    protected function setUp(){
        $this->obj = new CompanyHouseCountMock();
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->result = array(array('id'=>123,'company_id'=>835));
    }
    
    public function testGetManagerAccountData(){
        $obj = $this->genEasyObjectMock("Dao_Gcrm_HouseManagerAccount",array('select'),$this->result);   
        //注册对象
        Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount",$obj);
        $res = $this->obj->getManagerAccountData(835);
        $this->assertEquals($res,$this->result[0]);
    }

    public function testGetCompanyOrgReportByOrgData(){
        $obj = $this->genEasyObjectMock("Dao_Housereport_HouseCompanyOrgReport",array('select'),$this->result);   
        //注册对象
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseCompanyOrgReport",$obj);
        $res = $this->obj->getCompanyOrgReportByOrgData(123456,strtotime('yesterday'));
        $this->assertEquals($res,$this->result[0]);
    }
    
    public function testGetCompanyHouseCountByCompanyId(){
        $obj = $this->genEasyObjectMock("Dao_Gcrm_HouseManagerAccount",array('select'),array());   
        
        $test = new Service_Data_CompanyShop_CompanyHouseCount();
        $res = $test->getCompanyHouseCountByCompanyId(835,'abc');
        $this->assertEquals($res['errorno'],ErrorConst::E_PARAM_INVALID_CODE);
        
        //注册对象
        Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount",$obj);
        $test = new Service_Data_CompanyShop_CompanyHouseCount();
        $res = $test->getCompanyHouseCountByCompanyId(835);
        $this->assertEquals($res['errorno'],ErrorConst::E_INNER_FAILED_CODE);
        
        $obj1 = $this->genEasyObjectMock("Dao_Gcrm_HouseManagerAccount",array('select'),$this->result);
        //注册对象
        Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount",$obj1);
        $obj2 = $this->genEasyObjectMock("Dao_Housereport_HouseCompanyOrgReport",array('select'),$this->result);
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseCompanyOrgReport",$obj2);
        $test = new Service_Data_CompanyShop_CompanyHouseCount();
        $res = $test->getCompanyHouseCountByCompanyId(835);
        $this->assertEquals($res['data'],$this->result[0]);
    }
}
