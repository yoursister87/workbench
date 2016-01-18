<?php
class AddOrUpdateOrgTest extends  Testcase_PTest{
	protected function setUp(){ 
		 Gj_LayerProxy::$is_ut = true; 
	}
	public function testexecute(){
		$arrInput = array(
			'id'            => 18,
			'pid'			=> 42244,
			'level'			=> 4,
		    'title'			=> '公司级别',
			'account'		=> 'admin_gjcs',
			'company_id'	=> '1575',
			'customer_id'	=> '0',
			'district_id'	=> '1,北京',
			'street_id'		=> '2,上地',
			'admin_id'		=> 18,
			'address'		=> '北京',
			'address_id'    => ''
		);	
		$res = array(
			'errorno'   => 0,
			'errormsg' 	=> '[数据返回成功]'
		);
		$res1 = array(
			 'errorno'   => 0,  
			 'errormsg'  => '[数据返回成功]'   		 
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CompanyStoresAddress",array("addCompanyStoresAddress"));
		$obj ->expects( $this->any())
			 ->method('addCompanyStoresAddress') 
			 ->will ($this->returnValue($res));    
		$obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("updateOrgById"));
		$obj1->expects( $this->any())  
			 ->method('updateOrgById')   
			 ->will ($this->returnValue($res1));  
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CompanyStoresAddress",$obj);
	    Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj1);
		//$orgObj = new Service_Page_HouseReport_Org_AddOrUpdateOrg();
		 $orgObj = Gj_LayerProxy::getProxy("Service_Page_HouseReport_Org_AddOrUpdateOrg");
		$ret = $orgObj->execute($arrInput);
		$data = array(
				'data' => array(
				'pid'           => 42244,
				'level'         => 4,
				'title'         => '公司级别',
				'account'       => 'admin_gjcs',
				'company_id'    => '1575',
				'customer_id'   => '0',
				'name'          => NULL,
				'phone'         => NULL
			),
			'errorno'     => '0',
			'errormsg'  => '[数据返回成功]'		
		);
		 $this->assertEquals($data,$ret);  
	
		$arrInput = array(
            'id'            => 18,
            'pid'           => 42244,
            'level'         => 4,
            'title'         => '公司级别',
            'account'       => 'admin_gjcs',
            'company_id'    => '1575',
            'customer_id'   => '0',
            'district_id'   => '1,北京',
            'street_id'     => '2,上地',
            'admin_id'      => 18,
            'address'       => '北京',
            'address_id'    => '111'
        );
        $res = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $res1 = array(
             'errorno'   => 0,  
             'errormsg'  => '[数据返回成功]'    
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_CompanyStoresAddress",array("updateCompanyStoresAddressById"));
        $obj ->expects( $this->any())
             ->method('updateCompanyStoresAddressById') 
             ->will ($this->returnValue($res));    
        $obj1 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("updateOrgById"));
        $obj1->expects( $this->any())  
             ->method('updateOrgById')   
             ->will ($this->returnValue($res1));  
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CompanyStoresAddress",$obj);
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj1);
        //$orgObj = new Service_Page_HouseReport_Org_AddOrUpdateOrg();
         $orgObj = Gj_LayerProxy::getProxy("Service_Page_HouseReport_Org_AddOrUpdateOrg");	
		 $ret = $orgObj->execute($arrInput);
         $data = array(
                'data' => array(
                'pid'           => 42244,
                'level'         => 4,
                'title'         => '公司级别',
                'account'       => 'admin_gjcs',
                'company_id'    => '1575',
                'customer_id'   => '0',
                'name'          => NULL,
                'phone'         => NULL
            ),
            'errorno'     => '0',
            'errormsg'  => '[数据返回成功]'
        );
         $this->assertEquals($data,$ret);	

	}
} 
