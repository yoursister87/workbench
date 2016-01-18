<?php
class GetAgentListTest extends Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
    }  	
	public function testexecute(){
		$arrInput = array(
			'customer_id'	=> 'sss',
		);	
		$obj = new Service_Page_HouseReport_Org_GetAgentList();
		$ret =   $obj->execute($arrInput);
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($ret,$data);
		
		$arrInput = array(
			'customer_id'	=> 123,
			'page'			=> '',
			'pageSize'		=> '',
		);
		$res = array(
			'data'	=> 5
		);
		$res1 = array(
			'errorno'	=> 0,
			'errormsg'=>'[数据返回成功]',
			'data'		=> array(
				0 => array(
					'CustomerId' => 333,
				)
			)
		);
		$res2 = array(
			'data'	=> array(
				'FullName'	=> "赶集测试公司"
			)
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountCountByCustomerId","getAccountListByCustomerId"));
		$obj->expects($this->any())
		->method("getAccountCountByCustomerId")
		->will($this->returnValue($res));

		$obj->expects($this->any())
		->method("getAccountListByCustomerId")
		->will($this->returnValue($res1));
		
		
		$obj1 = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
		$obj1->expects($this->any())
        ->method("getCustomerInfoByCustomerId")
        ->will($this->returnValue($res2));	
		
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer",$obj1);
			
		 $data =  array(
		 		'data'	=> array(
		 				'pageStr'	=>null,
		 				'data'	=> array(
		 						0 	=> array(
		 								'CustomerId' => 333,
		 					  		'FullName'  => "赶集测试公司",
		 								'PremierExpire' => '已过期',
		 								'key' => 'b14a7b8059d9c055954c92674ce60032'
		 						)
		 				),
						 'totalNum' => 5,
		 		),
		 );
		 $objOrg = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",array("getCrmEmail"));
		 $objOrg->expects($this->any())
		 ->method("getCrmEmail")
		 ->will($this->returnValue($data['data']));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",$objOrg);
		 
		 $res = 1;
		 $objList = $this->genObjectMock("Service_Page_HouseReport_Org_GetAgentList",array("mergePort"));
         $objList->expects($this->any())
         ->method("mergePort")
         ->will($this->returnValue($res));

		 $objList->expects($this->any())
			 ->method("returnObj")
			 ->will($this->returnValue($res));	
		$ret = $objList->execute($arrInput);
		$this->assertEquals($data,$ret);


      $arrInput = array(
            'customer_id'   => 123,
            'page'          => '',
            'pageSize'      => '',
        );
        $res = array(
            'data'  => 5
        );
        $res1 = array(
            'errorno'   =>1002,
            'data'      => array(
                0 => array(
                    'CustomerId' => 333,
                	'UserId'=>1234,
                )
            )
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountCountByCustomerId","getAccountListByCustomerId"));
        $obj->expects($this->any())
        ->method("getAccountCountByCustomerId")
        ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getAccountListByCustomerId")
        ->will($this->returnValue($res1));
        
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

		$res = array('errorno' => 0);
		$objList = $this->genObjectMock("Service_Page_HouseReport_Org_GetAgentList",array("mergePort","returnObj"));
		$objList->expects($this->any())
			->method("mergePort")
			->will($this->returnValue($res));	
		$objList->expects($this->any())
			->method("returnObj")
			->will($this->returnValue($res));   
		$objOrg = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",array("getCrmEmail"));
		$objOrg->expects($this->any())
			->method("getCrmEmail")
			->will($this->returnValue($data['data']));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",$objOrg);
		$ret = $objList->execute($arrInput);
		$data =  array(
			'data'  => array(
				'pageStr'   =>array('errorno' => 0),
				'data'  => array(
					0   => array(
						'CustomerId' => 333,
						'FullName'  => "赶集测试公司",
						'PremierExpire' => '已过期',
						'key' => 'b14a7b8059d9c055954c92674ce60032'
					)
				),
				'totalNum' => 5,
			),
		);
    /*    $data =  array(
            'data'  => array(
				'pageStr' =>array('errorno' => 0),
				'totalNum' => 5,
				'data' => null
            ),
        );
*/
        $this->assertEquals($data,$ret);
	}
	public function testmergePort(){
		$whereConds = array();
		$accountData = array();
		$obj = new Service_Page_HouseReport_Org_GetAgentList();
		$ret = $obj->mergePort($whereConds,$accountData);
		$result = array();
		$this->assertEquals($ret,$result);

		$res = array( 0 => array('BussinessScope' => 1,'AccountId' => 123));
		$accountData = array('data' => array(0 => array('AccountId' => 123)));
		$whereConds = array('customer_id' => 1);
		$objOrg = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getAccountBusinessInfolist"));
		$objOrg->expects($this->any())
			->method("getAccountBusinessInfolist")
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$objOrg);
		$obj = new Service_Page_HouseReport_Org_GetAgentList();
		$ret = $obj->mergePort($whereConds,$accountData);
		$result = array('data' => array(0 => array('AccountId' => 123,'BussinessScope' =>"民宅综合" )));
		$this->assertEquals($ret,$result);

		$res = array( 0 => array('AccountId' => 123));
		$accountData = array('data' => array(0 => array('AccountId' => 123)));
		$whereConds = array('customer_id' => 1);
		$objOrg = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getAccountBusinessInfolist"));
		$objOrg->expects($this->any())
			->method("getAccountBusinessInfolist")
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$objOrg);
		$obj = new Service_Page_HouseReport_Org_GetAgentList();
		$ret = $obj->mergePort($whereConds,$accountData);
		$result = array('data' => array(0 => array('AccountId' => 123,'BussinessScope' =>"" )));
		$this->assertEquals($ret,$result);
	}
}
