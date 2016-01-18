<?php
class CheckAgentPwdTest extends Testcase_PTest{
     protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     } 	
	public function testexecute(){
		$arrInput = array(
			'newemail'	=> '',
			'passwd'	=> ''
		);
		$obj = new Service_Page_HouseReport_Org_CheckAgentPwd();	
		$ret = $obj->execute($arrInput);
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);


		$arrInput = array(
            'newemail'  => '928025455@qq.com', 
            'passwd'    => 123
        );  
		$res = true;
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("login"));
		$obj->expects($this->any())
		->method("login")
		->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy('Service_Data_Gcrm_CustomerAccount',$obj);
        $obj = new Service_Page_HouseReport_Org_CheckAgentPwd();    
        $ret = $obj->execute($arrInput);
        $data = true;
        $this->assertEquals($data,$ret);	
	}
} 
