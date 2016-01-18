<?php
class InsertLoginRecordTest extends Testcase_PTest{
	protected function setUp(){
		Gj_LayerProxy::$is_ut = true; 
	}
	public function testexecute(){
		/*
		$arrInput = array();
		$res = array (
                'errorno' => 1002,
                'errormsg' =>'[参数不合法]'
        );
		$res1 = -1;
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("addCustomerLoginLog"));
         $obj ->expects( $this->any())
             ->method('addCustomerLoginLog')
            ->will ($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
		$obj1 = $this->genStaticMock("HttpNamespace",array("ip2city"));
			$obj1->expects( $this->any())
			->method('ip2city')
			->will ($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("HttpNamespace",$obj1);
        $orgObj = new Service_Page_HouseReport_Org_InsertLoginRecord();
         $ret =  $orgObj->execute($arrInput);
         $data =  array(
                'errorno' => 1002,
                'errormsg' => '[参数不合法]'
        );
        $this->assertEquals($data,$ret);


		$arrInput = array (
			 'account_id' =>'235',
  			 'account' =>'admin_gjcs',
  			 'company_id' =>'1575',
		     'loging_time' =>1413803285,
   			 'ip' => '192.168.2.162',
  			 'is_success' => true	
		);
		$res = array ( 
				'errorno' => 0,
				'errormsg' =>'[数据返回成功]'
		);
		$res1 = 1;
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("addCustomerLoginLog"));
		 $obj ->expects( $this->any())  
			 ->method('addCustomerLoginLog') 
			 ->will ($this->returnValue($res)); 
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
		$obj1 = $this->genStaticMock("HttpNamespace",array("ip2city"));
		$obj1->expects( $this->any())
			->method('ip2city')
			->will ($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("HttpNamespace",$obj1);
		$orgObj = new Service_Page_HouseReport_Org_InsertLoginRecord();
		 $ret =  $orgObj->execute($arrInput);  
		 $data =  array(
				'errorno' => 0,
				'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($data,$ret);	


		 $arrInput = array (
             'account_id' =>'235',
             'account' =>'admin_gjcs',
             'company_id' =>'1575',
             'loging_time' =>1413803285,
             'ip' => '192.168.2.162',
             'is_success' => true   
        );  
        $res = array ( 
                'errorno' => '1002',
                'errormsg' =>'插入登陆记录失败'
        );
		$res1 = 1;
		  $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("addCustomerLoginLog"));	
         $obj ->expects( $this->any())
             ->method('addCustomerLoginLog')
			 ->will ($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
		$obj1 = $this->genStaticMock("HttpNamespace",array("ip2city"));
		$obj1->expects( $this->any())
			->method('ip2city')
			->will ($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("HttpNamespace",$obj1);
        $orgObj = new Service_Page_HouseReport_Org_InsertLoginRecord();
         $ret =  $orgObj->execute($arrInput);
         $data =  array(
                'errorno' => '1002',
                'errormsg' =>'插入登陆记录失败'
        );

        $this->assertEquals($data,$ret); 
	
		$arrInput = 1;
		$res = array (
			'errorno' => '1002',
			'errormsg' =>'[参数不合法]'
		);
		$res1 = 1;
          $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("addCustomerLoginLog"));
         $obj ->expects( $this->any())
             ->method('addCustomerLoginLog')
            ->will ($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
		$obj1 = $this->genStaticMock("HttpNamespace",array("ip2city"));
		$obj1->expects( $this->any())
			->method('ip2city')
			->will ($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("HttpNamespace",$obj1);
        $orgObj = new Service_Page_HouseReport_Org_InsertLoginRecord();
         $ret =  $orgObj->execute($arrInput);
         $data =  array(
                'errorno' => '1002',
                'errormsg' =>'[参数不合法]'
        );

		 $this->assertEquals($data,$ret);
*/
	}
}
