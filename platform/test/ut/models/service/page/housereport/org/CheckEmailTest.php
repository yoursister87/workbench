<?php
class CheckEmailTest extends Testcase_PTest{
	 protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     }  	
	public function testexecute(){
		$arrInput = array(
			'newemail'	=> '',
		);	
		$obj = new Service_Page_HouseReport_Org_CheckEmail();
		$ret = $obj->execute($arrInput);
		$data = array(
			'data'	=> array(),
			'errorno'	=> 1002,
			'errormsg'	=>'[参数不合法]'
		);
		$this->assertEquals($data,$ret);	
	

		$arrInput = array(
			 'newemail'  => '928025455@qq.com',
		);
		$res = array(
			'errorno'	=> 0,
			'data'		=> 123
		);
		$res1 = array(
			'data'	=> array(
				0	=>	1
			)
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getUid","getBizTypeList"));
		$obj->expects($this->any())
		->method("getUid")
		->will($this->returnValue($res));
		$obj->expects($this->any())
		->method("getBizTypeList")
		->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
		   $objOrg = new Service_Page_HouseReport_Org_CheckEmail();
		   $ret = $objOrg->execute($arrInput);
		   $data = array(
				'errorno'	=> 2104,
				'errormsg'	=> '该邮箱已经注册，可以输入密码后转端口'
			);
		   $this->assertEquals($data,$ret);    


		
        $arrInput = array(
             'newemail'  => '928025455@qq.com',
        );  
        $res = array(
            'errorno'   => 0,
            'data'      => 123 
        );  
        $res1 = array(
            'data'  => array(
                0   =>  2
            )   
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getUid","getBizTypeList"));
        $obj->expects($this->any())
        ->method("getUid")
        ->will($this->returnValue($res));
        $obj->expects($this->any())
        ->method("getBizTypeList")
        ->will($this->returnValue($res1));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
           $objOrg = new Service_Page_HouseReport_Org_CheckEmail();
           $ret = $objOrg->execute($arrInput);
           $data = array(
                'errorno'   => 2104,
                'errormsg'  => '该邮箱已经注册，可以输入密码后转端口'
            );  
           $this->assertEquals($data,$ret);   


		$arrInput = array(
             'newemail'  => '928025455@qq.com',
        );  
        $res = array(
            'errorno'   => 0,
            'data'      => 0 
        );  
        $res1 = array(
            'data'  => array(
                0   =>  2
            )   
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getUid"));
        $obj->expects($this->any())
        ->method("getUid")
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
           $objOrg = new Service_Page_HouseReport_Org_CheckEmail();
           $ret = $objOrg->execute($arrInput);
           $data = array(
                'errorno'   => 2103,
                'errormsg'  => '该邮箱没有注册，可以转端口'
            );  
           $this->assertEquals($data,$ret);   		

		$arrInput = array(
             'newemail'  => '928025455@qq.com',
        );
        $res = array(
            'errorno'   => 1002,
            'data'      => 0
        );
        $res1 = array(
            'data'  => array(
                0   =>  2
            )
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getUid"));
        $obj->expects($this->any())
        ->method("getUid")
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
           $objOrg = new Service_Page_HouseReport_Org_CheckEmail();
           $ret = $objOrg->execute($arrInput);
           $data = array(
				'data'		=>0 ,
				'errorno'	=> 1002
            );
           $this->assertEquals($data,$ret);	
	}
}
