<?php
/**
 * File Name:HouseAccountHoursTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
*/
class ServiceDataHouseAccountHours extends Testcase_PTest{

    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Data_HouseReport_HouseAccountHours();
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;

        $this->result = array('list'=>array(array('account_id'=>1,'account_pv'=>'100','refresh_count'=>100)));
    }

    public function testhoursClickByCity(){
        $res = array();
        $ret = $this->obj->hoursClickByCity('abc','123');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 

        $res = array(array('h1'=>10,'h2'=>12));
        $houseType = array(1,3);
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountHours", array("select")); 
        $obj->expects($this->any())
            ->method('select')
            ->will($this->returnValue($res));


        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountHours", $obj);
        $obj = new Service_Data_HouseReport_HouseAccountHours();
        $ret = $obj->hoursClickByCity(12,$houseType);
        $this->assertEquals($ret['data'],$res); 
    }

    public function testhoursClickByCustomerId(){
        $res = array();
        $ret = $this->obj->hoursClickByCustomerId('abc','123','123','2015-01-14');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 

        $res = array(array('h1'=>10,'h2'=>12));
        $customerId = array(1,2,3,4);
        $houseType = array(1,3);
        $optType = 1;
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountHours", array("select")); 
        $obj->expects($this->any())
            ->method('select')
            ->will($this->returnValue($res));


        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountHours", $obj);
        $obj = new Service_Data_HouseReport_HouseAccountHours();
        $ret = $obj->hoursClickByCustomerId($customerId,$houseType,$optType);
        $this->assertEquals($ret['data'],$res); 
    }

    public function testhoursClickByAccountId(){
        $res = array();
        $ret = $this->obj->hoursClickByAccountId('abc','123','123','2015-01-14');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
        
        $res = array(array('h1'=>10,'h2'=>12));
        $accountId = 123;
        $houseType = array(1,3);
        $optType = 1;
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountHours", array("select")); 
        $obj->expects($this->any())
            ->method('select')
            ->will($this->returnValue($res));


        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountHours", $obj);
        $obj = new Service_Data_HouseReport_HouseAccountHours();
        $ret = $obj->hoursClickByAccountId($accountId,$houseType,$optType);
        $this->assertEquals($ret['data'],$res); 

    }
}
