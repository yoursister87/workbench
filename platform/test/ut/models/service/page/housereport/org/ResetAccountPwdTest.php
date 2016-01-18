<?php
class ResetAccountPwdTest extends Testcase_PTest {
    protected function setUp() {
        Gj_LayerProxy::$is_ut = true;
    } 
	public function testexecute(){
		$arrInput = array(
			'UserId'	=> 'sss'
		);	
		$obj = new Service_Page_HouseReport_Org_ResetAccountPwd();
		$ret = $obj->execute($arrInput);
		$data = array(
			'data'	=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($ret,$data);
		
	    $arrInput = array(
            'UserId'    => '123',
			'passwd'	=> '345',
			'id'		=> 111,
			'user_id'	=> 222,
        );
		$res = array(
			'errorno'	=> 0
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("setPassword"));
		$obj->expects($this->any())
		->method("setPassword")
		->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $obj = new Service_Page_HouseReport_Org_ResetAccountPwd();
        $ret = $obj->execute($arrInput);
        $data = array(
            'errorno'   => 0,
        );
		$this->assertEquals($ret,$data);


	    $arrInput = array(
            'UserId'    => '123',
            'passwd'    => '345',
            'id'        => 111,
            'user_id'   => 222,
        );
        $res = array(
            'errorno'   => 1002
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("setPassword"));
        $obj->expects($this->any())
        ->method("setPassword")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $obj = new Service_Page_HouseReport_Org_ResetAccountPwd();
        $ret = $obj->execute($arrInput);
        $data = array(
            'errorno'   => 1002,
        );  	
		$this->assertEquals($ret,$data);
	}

}
