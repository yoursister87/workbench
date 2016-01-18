<?php
class AddOrgByCustomerTest extends Testcase_PTest{
	protected function setUp(){
		  Gj_LayerProxy::$is_ut = true;
	}
	public function testexecute(){
		$arrInput = array(
			'customer_id' => '123,111',
			'pid'         => 0, 
			'company_id'  => 235,
			'level'       => 1,
		);	
		$res = array (
				'CustomerId' => '222',
				'FullName'   => '测试公司',
				'CompanyId'  => 235
		);
		$res1 = array (
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
		$obj ->expects( $this->any())
			->method('getCustomerInfoByCustomerId')
			->will ($this->returnValue($res));
		Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_Customer', $obj );
		$obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("addOrg"));
		 $obj1 ->expects( $this->any())  
			   ->method('addOrg')   
			   ->will ($this->returnValue($res1));  
		 Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj1 );
		$orgObj = new Service_Page_HouseReport_Org_AddOrgByCustomer();
		$ret = $orgObj->execute($arrInput);
		$data = array(
			'errorno'	=> 0,
			'errormsg'  => '[数据返回成功]',
			'data'		=> 1
		);
		$this->assertEquals($data,$ret);   
		


		        $arrInput = array(
            'customer_id' => '123,111',
            'pid'         => 0,  
            'company_id'  => 235,
            'level'       => 1,
        );  
        $res = array (
                'CustomerId' => '222',
                'FullName'   => '',
                'CompanyId'  => 235 
        );  
        $res1 = array (
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
        $obj ->expects( $this->any())
            ->method('getCustomerInfoByCustomerId')
            ->will ($this->returnValue($res));
        Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_Customer', $obj );
        $obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("addOrg"));
         $obj1 ->expects( $this->any())  
               ->method('addOrg')   
               ->will ($this->returnValue($res1));  
         Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj1 );
        $orgObj = new Service_Page_HouseReport_Org_AddOrgByCustomer();
        $ret = $orgObj->execute($arrInput);
        $data = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]',
            'data'      => 1
        );  
        $this->assertEquals($data,$ret);   
		
		
		 $arrInput = array(
            'customer_id' => '123,null',
            'pid'         => 0,  
            'company_id'  => 235,
            'level'       => 1,
        );  
        $res = array (
                'CustomerId' => '222',
                'FullName'   => '测试公司',
                'CompanyId'  => 235 
        );  
        $res1 = array (
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
        $obj ->expects( $this->any())
            ->method('getCustomerInfoByCustomerId')
            ->will ($this->returnValue($res));
        Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_Customer', $obj );
        $obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("addOrg"));
         $obj1 ->expects( $this->any())  
               ->method('addOrg')   
               ->will ($this->returnValue($res1));  
         Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj1 );
        $orgObj = new Service_Page_HouseReport_Org_AddOrgByCustomer();
        $ret = $orgObj->execute($arrInput);
        $data = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]',
            'data'      => 1
        );  
        $this->assertEquals($data,$ret);   	
		

		  $arrInput = array(
            'customer_id' => '"",""',
            'pid'         => 0,  
            'company_id'  => 235,
            'level'       => 1,
        );  
        $res = array (
                'CustomerId' => '222',
                'FullName'   => '',
                'CompanyId'  => 235 
        );  
        $res1 = array (
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
        $obj ->expects( $this->any())
            ->method('getCustomerInfoByCustomerId')
            ->will ($this->returnValue($res));
        Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_Customer', $obj );
        $obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("addOrg"));
         $obj1 ->expects( $this->any())  
               ->method('addOrg')   
               ->will ($this->returnValue($res1));  
         Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj1 );
        $orgObj = new Service_Page_HouseReport_Org_AddOrgByCustomer();
        $ret = $orgObj->execute($arrInput);
        $data = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]',
            'data'      => 1
        );  
        $this->assertEquals($data,$ret);   

	}
} 
