<?php
class  VerifyCodeTest extends Testcase_PTest{
	protected function setUp(){ 
		Gj_LayerProxy::$is_ut = true; 
	}
	public function testexecute(){
		$arrInput = array (
			'phone' => '13752967124',
			'code'  =>'1234'
		);
		$res = array (
				'data' =>array ("msg" =>"验证码校验成功!"),
				'errorno' => 0,
				'errormsg' => "[数据返回成功]"
		);
		$obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("verifyPhone"));
		 $obj ->expects( $this->any()) 
			 ->method('verifyPhone')
			 ->will ($this->returnValue($res));
		 Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_PhoneCode',$obj);
		 $orgObj = new Service_Page_HouseReport_Org_VerifyCode();
		 $ret =  $orgObj->execute($arrInput);
		   $data = array (
				'data' => array (
					'errorno' => 0,
					'errormsg' => '[数据返回成功]',
					'msg'      =>'验证码校验成功!',
					'codeVerify' =>NULL
				)
		   );
		 $this->assertEquals($data,$ret);
		

	    $arrInput = array (
            'phone' => '13752967124',
            'code'  =>'1234'
        );  
        $res = array (
                'data' =>array ("msg" =>"校验失败"),
                'errorno' => 1002,
                'errormsg' => "校验失败"
        );  
        $obj = $this->genObjectMock("Gj_Fang_Api_Platform_PhoneCode",array("verifyPhone"));
         $obj ->expects( $this->any()) 
             ->method('verifyPhone')
             ->will ($this->returnValue($res));
         Gj_LayerProxy::registerProxy('Gj_Fang_Api_Platform_PhoneCode',$obj);
         $orgObj = new Service_Page_HouseReport_Org_VerifyCode();
         $ret =  $orgObj->execute($arrInput);
           $data = array (
                'data' => array (
					'data' => array(
							'msg' => '校验失败'
						),
                    'errorno' => 1002,
                    'errormsg' => '校验失败',
                )   
           );  
         $this->assertEquals($data,$ret);
}
}
