<?php
class AgentToCustomerTest extends Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
    }
	public function testexecute(){
		$arrInput = array(
			'AccountIds'	=> '134,234'
		);	
		$res = array(
			'errorno'	=> 0
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("UpdateCustomer"));
		$obj->expects($this->any())
		->method("UpdateCustomer")
		->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
		$objOrg = $this->genObjectMock("Service_Page_HouseReport_Org_AgentToCustomer",array("addQueueAccountChange"),array(),'',true);
		$objOrg->expects($this->any())
		->method("addQueueAccountChange")
		->will($this->returnValue($res));
		//$objOrg = new Service_Page_HouseReport_Org_AgentToCustomer();
		$ret = $objOrg->execute($arrInput);
		$data = array(
			 'errorno'   => 0
		);
		$this->assertEquals($ret,$data);


		$arrInput = array(
            'AccountIds'    => 134
        );  
        $res = array(
            'errorno'   => 0
        );  
        
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("UpdateCustomer"));
        $obj->expects($this->any())
        ->method("UpdateCustomer")
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $objOrg = new Service_Page_HouseReport_Org_AgentToCustomer();
        $ret = $objOrg->execute($arrInput);
        $data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
        );  
        $this->assertEquals($ret,$data);	
	}
	public function testaddQueueAccountChange(){
		$account_id = 123;
		$user_id = 456;
		$res = array(
				'errorno'	=> 0
		);
		$obj = $this->genObjectMock('Service_Data_Gcrm_AccountChange',array("addAccountChange"));
		$obj ->expects( $this->any())
		->method('addAccountChange')
		->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountChange",$obj);
		$objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
		$ret = $objOrg->addQueueAccountChange($account_id,$user_id);
		$data = array(
				'errorno'   => 0
		);
		$this->assertEquals($ret,$data);
	}	
}	
