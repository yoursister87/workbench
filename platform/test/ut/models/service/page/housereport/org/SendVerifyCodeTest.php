<?php
class SendVerifyCodeTest extends Testcase_PTest{
	protected function setUp(){
		Gj_LayerProxy::$is_ut = true; 
	}
	public function testexecute(){
		$arrInput = array ('phone' => '13752967124');
		$res = array (
			'data' => array ('msg' =>'验证码已成功发送!'),
			'errorno' =>0 ,
			'errormsg' =>'[数据返回成功]'
		);
		$obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("sendVerifyCode"));
		 $obj ->expects( $this->any())
			  ->method('sendVerifyCode')
		      ->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Gj_Fang_Api_Platform_PhoneCode",$obj);
		$orgObj = new Service_Page_HouseReport_Org_SendVerifyCode();
		$ret = $orgObj->execute ( $arrInput);  
		$data = array (
			'data' => array(
				'errorno' =>0,
				'errormsg' =>'[数据返回成功]',
				'msg'      =>'验证码已成功发送!'
			)
		);
		$this->assertEquals($data,$ret);  	
	}

}
	

