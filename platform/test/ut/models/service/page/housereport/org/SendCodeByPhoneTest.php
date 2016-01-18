<?php
class SendCodeByPhoneTest extends Testcase_PTest{
     protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     }  
	public function testexecute(){
		$arrInput = array(
			'phone'	=> 13752967124	
		);
		$res = array(
			'errorno'	=> 0
		);
		 $obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("sendCodeByPhone"));
	 	 $obj ->expects( $this->any())
		 ->method('sendCodeByPhone')
		->will($this->returnValue($res));		
		 Gj_LayerProxy::registerProxy("Gj_Fang_Api_Platform_PhoneCode",$obj);
		 $objOrg = new Service_Page_HouseReport_Org_SendCodeByPhone();
		 $ret = $objOrg->execute($arrInput);
		 $data = array(
			'errorno'   => 0
		);
		$this->assertEquals($data,$ret);


		 $arrInput = array(
            'phone' => 13752967124  
        );  
        $res = array(
            'errorno'   => 1003
        );  
         $obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("sendCodeByPhone"));
         $obj ->expects( $this->any())
         ->method('sendCodeByPhone')
        ->will($this->returnValue($res));    
         Gj_LayerProxy::registerProxy("Gj_Fang_Api_Platform_PhoneCode",$obj);
         $objOrg = new Service_Page_HouseReport_Org_SendCodeByPhone();
         $ret = $objOrg->execute($arrInput);
         $data = array(
            'errorno'   => 1003
        );  
        $this->assertEquals($data,$ret);	

	    $arrInput = array(
            'phone' => 1311752967124
        );
        $res = array(
            'errorno'   => 1003
        );
         $obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("sendCodeByPhone"));
         $obj ->expects( $this->any())
         ->method('sendCodeByPhone')
        ->will($this->returnValue($res));    
         Gj_LayerProxy::registerProxy("Gj_Fang_Api_Platform_PhoneCode",$obj);
         $objOrg = new Service_Page_HouseReport_Org_SendCodeByPhone();
         $ret = $objOrg->execute($arrInput);
         $data = array(
			'data'	=> array(
				'errorno'	=> 0,
				'errormsg'	=> '[数据返回成功]'
			),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
        );
        $this->assertEquals($data,$ret);	
	}
}
