<?php
class SearchCustomerAccountTest extends  Testcase_PTest{
	 protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     } 	
	public function testgetCustomerListByOrgIdLevel(){
		$orgId = 123;
		$res = array(
			'data'	=> array(
				'list'	=> array(
					array(
					'customer_id'	=>223
					)
				),
			),
		'errorno'	=> 0
		);
	    $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getChildTreeByOrgId"));
        $obj->expects($this->any())
        ->method('getChildTreeByOrgId')
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);				
		
		$orgObj = new Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount();
		$data = array(
				0 => array(
					0 => 223
				),
				1 => array(
					223 => array(
						'customer_id'	=> 223
					)
				)
		);
		$ret = $orgObj->getCustomerListByOrgIdLevel($orgId);
		$this->assertEquals($data,$ret);
		
		$orgId = 123;
        $res = array(
            'data'  => array(
                'list'  => array(
                    array(
                    'customer_id'   =>223
                    )   
                ) 
              ),
			'errorno'   => 1002
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getChildTreeByOrgId"));
        $obj->expects($this->any())
        ->method('getChildTreeByOrgId')
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);    
        $orgObj = new Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount();
		$ret = $orgObj->getCustomerListByOrgIdLevel($orgId);
		$data = array(
			 'data'  => array(
                'list'  => array(
                    array(
                    'customer_id'   =>223
                    )
                )
              ),
            'errorno'   => 1002	
		);
        $this->assertEquals($data,$ret);	
	}
	public function testSearchAgent(){
		$whereConds = array(
			'search_type'		=> 2,
			'search_keyword'	=>'上地',
			'induration'		=>true,
			'id'				=> 89,
			'isShowParent'		=> true,
		);
		$res = array(
			'data'	=> array(
				0	=> array(
					'CustomerId'	=> 123,
				)
			),
			'errorno'	=> 0
		);
		$res1 = array(
			0 => array(
				123,234
			),
			1 => array(
				123 =>  array(
					'id'			=> 89,
					'company_id'	=> 235,
				)
			)
		);
		$res2 = array(
				'data'	=> array(
					2 => array(
						'activeList'	=> array(
							"title"		=> "北京",
						)
					),
					3 => array(
				    'activeList'    => array(
                            "title"     => "上地",	
					)
				),
	                4 => array(
                    'activeList'    => array(
                            "title"     => "西街赶集测试公司",
                    )
                )
			)			
		);
	    $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountListByCustomerId"));
        $obj->expects($this->any())
        ->method('getAccountListByCustomerId')
        ->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

		$obj2 = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getTreeByOrgId"));
		$obj2->expects($this->any())
		->method('getTreeByOrgId')
		 ->will($this->returnValue($res2));
	     Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj2);

		$obj1 = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",array("getCustomerListByOrgIdLevel"));
		$obj1->expects($this->any())
		 ->method('getCustomerListByOrgIdLevel')
		 ->will($this->returnValue($res1));
		$ret =  $obj1->SearchAgent($whereConds);
		$data = array(
			'data'	=> array(
				0 	=> array(
					'CustomerId'	=> 123,
					'FullName'		=>'北京=>上地=>西街赶集测试公司',
					'company_id'	=> 235,
					'id'			=>89
				)
			)
		);
		$this->assertEquals($data,$ret);


		  $whereConds = array(
            'search_type'       => 2,
            'search_keyword'    =>'上地',
            'induration'        =>true,
            'id'                => 89,
            'isShowParent'      => true,
        );
        $res = array(
            'data'  => array(
                0   => array(
                    'CustomerId'    => 123,
                )
            ),
            'errorno'   => 1002
        );
        $res1 = array(
            0 => array(
                123,234
            ),
            1 => array(
                123 =>  array(
                    'id'            => 89,
                    'company_id'    => 235,
                )
            )
        );	
		  $res2 = array(
                'data'  => array(
                    2 => array(
                        'activeList'    => array(
                            "title"     => "北京",
                        )
                    ),
                    3 => array(
                    'activeList'    => array(
                            "title"     => "上地",
                    )
                ),
                    4 => array(
                    'activeList'    => array(
                            "title"     => "西街赶集测试公司",
                    )
                )
            )
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountListByCustomerId"));
        $obj->expects($this->any())
        ->method('getAccountListByCustomerId')
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);	
		 $obj = new Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount();
		 $ret = $obj->SearchAgent($whereConds);
		 $data = array(
			'data'	=> array(
				0	=> array(
					'CustomerId'	=> 123
				)
			),
			'errorno'	=> 1002
		);
		$this->assertEquals($data,$ret);
	}
	public function testGetCrmEmail(){
		$res1 = array(
				0 =>array ( 'AccountId' => '28104', 'CustomerId' => '7212', 'AccountName' => '孟令辉', 'CreatedTime' => '2010-07-19 09:51:45', 'UserId' => '8014', 'CellPhone' => '13522930456', 'Email' => NULL, 'Status' => '1', 'OwnerType' => '0', 'PremierExpire' => '1900943999', ),
		);
		$data =  array(
				'data'	=> array(
						'CustomerId' => 7212,
						'FullName'  => "北京我爱我家房地产-天通苑西区店",
						'CompanyId'=>835
				),
				'errorno'	=> 0,
				'errormsg'	=> '[数据返回成功]'
		);
		$arrFields = array("CustomerId","FullName","CompanyId");
		$obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoByCustomerId"));
		$obj->expects($this->any())
		->method('getCustomerInfoByCustomerId')
		->with($res1[0][CustomerId],$arrFields)
		->will($this->returnValue($data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer", $obj);
	
		$user_ids = array($res1[0]['UserId']);
		$data =  array(
				'data'	=> array(
						array(
								'user_id' => 8014,
								'email'  => "bjtest1@ganji.com",
								'phone' =>'18707197669',
								'user_name'=>'cinderella'
						)
				),
				'errorno'	=> 0,
				'errormsg'	=> '[数据返回成功]'
		);
		$crmData =  array(
				'data'	=> array(
						'28104'=>array(
								'status' => 1,
						)
				),
				'errorno'	=> 0,
				'errormsg'	=> '[数据返回成功]'
		);
		$obj1 = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("batchGetUser","getUserStatusFromCrm"));
		$obj1->expects($this->any())
		->method('batchGetUser')
		->with($user_ids)
		->will($this->returnValue($data));
	
		$obj1->expects($this->any())
		->method('getUserStatusFromCrm')
		->will($this->returnValue($crmData));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $obj1);
	
		$objOrg = new Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount();
		$ret = $objOrg->getCrmEmail($res1);
		$res1[0]['PremierExpire'] = $res1[0]['PremierExpire']>time() ? "生效中":"已过期";
		$res1[0]['key'] = md5($res1[0]['UserId'].'_'.$data['data'][0]['email']);
		$data['data'][0]['Email'] = $data['data'][0]['email'];
		unset($data['data'][0]['email']);
		$data['data'][0]['crmStatus'] = 1;
		$returnData[0] = array_merge($res1[0],$data['data'][0]);
		$returnData[0]['FullName'] = "北京我爱我家房地产-天通苑西区店";
		$data['data'] = $returnData;
		$data['errorno'] = ErrorConst::SUCCESS_CODE;
		$data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->assertEquals($data,$ret);
	}
} 
