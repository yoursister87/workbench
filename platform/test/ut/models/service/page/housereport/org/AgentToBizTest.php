<?php
class AgentToBizTest extends Testcase_PTest{
     protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     }
	 public function testaddQueueAccountChange(){
		$account_id = 123;
		$user_id = 456;	
		$res = array(
			'errorno'	=> 0
		);
		$obj = $this->genObjectMock('Service_Data_Gcrm_AccountChange',array("addAccountChange"));
        $obj ->expects( $this->any())
         ->method('addAccountChange')
         ->will($this->returnValue($res));	
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountChange",$obj);	
		$objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
		$ret = $objOrg->addQueueAccountChange($account_id,$user_id);
		$data = array(
			 'errorno'   => 0
		);
		$this->assertEquals($ret,$data);
	}
	public function testauthEmail(){
		$arrInput = array(
			'newemail'	=> '928025455@qq.com',
			'passwd'	=> 123
		);	
		$res = array(
			'errorno'	=> 1002
		);
		$obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("login"));
        $obj ->expects( $this->any())
         ->method('login')
         ->will($this->returnValue($res));  
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);	
	    $objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
		$ret = $objOrg->authEmail($arrInput);
		$data = array(
			 'errorno'   => 1002
		);
		$this->assertEquals($data,$ret);
	    $arrInput = array(
            'newemail'  => '928025455@qq.com',
            'passwd'    => 123 
        );  
        $res = array(
            'errorno'   => 0
        );  
		$res1 = array(
			 'errorno'   => 1002
		);
        $obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("login","getUid"));
        $obj ->expects( $this->any())
         ->method('login')
         ->will($this->returnValue($res));  
		$obj ->expects( $this->any())
		->method('getUid')
		 ->will($this->returnValue($res1));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj); 
        $objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
        $ret = $objOrg->authEmail($arrInput);
        $data = array(
             'errorno'   => 1002
        );  
        $this->assertEquals($data,$ret);		

		$arrInput = array(
            'newemail'  => '928025455@qq.com',
			'oldemail'	=> '1234@qq.com',
            'passwd'    => 123 ,
			'AccountId'	=> 333,
			'CreatorId'	=> 444
        );  
        $res = array(
            'errorno'   => 0
        );  
        $res1 = array(
             'errorno'   => 0,
			 'data'		=> array(
					'user_id'	=> 555,
					'user_name'	=> 'zsj',
			)
        );
		$res2 = array(
			'errorno'	=> 1002
		);
        $obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("login","getUid","TransferBalance"));
        $obj ->expects( $this->any())
         ->method('login')
         ->will($this->returnValue($res));  
        $obj ->expects( $this->any())
        ->method('getUid')
         ->will($this->returnValue($res1));
		 $obj ->expects( $this->any())
		 ->method('TransferBalance')
		->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj); 
        $objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
        $ret = $objOrg->authEmail($arrInput);
        $data = array(
				'errorno'	=> 1002,
        );
        $this->assertEquals($data,$ret);    	

		 $arrInput = array(
            'newemail'  => '928025455@qq.com',
            'oldemail'  => '1234@qq.com',
            'passwd'    => 123 ,
            'AccountId' => 333,
            'CreatorId' => 444
        );
        $res = array(
            'errorno'   => 0
        );
        $res1 = array(
             'errorno'   => 0,
             'data'     => array(
                    'user_id'   => 555,
                    'user_name' => 'zsj',
            )
        );
        $res2 = array(
            'data'  => array(
                'errorno'   => 1001
            )
        );
        $res3 = array(
            'data'  => array(
                'errorno'   => 1003
            )
        );	
	    $obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("login","getUid","TransferBalance","AddEmailModifyRecord"));
        $obj ->expects( $this->any())
         ->method('login')
         ->will($this->returnValue($res));
        $obj ->expects( $this->any())
        ->method('getUid')
         ->will($this->returnValue($res1));
         $obj ->expects( $this->any())
         ->method('TransferBalance')
        ->will($this->returnValue($res2));

        $obj ->expects( $this->any())
        ->method('AddEmailModifyRecord')
         ->will($this->returnValue($res3));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $objOrg = new Service_Page_HouseReport_Org_AgentToBiz();
        $ret = $objOrg->authEmail($arrInput);
        $data = array(
            'data'  => array(
				'user_name'	=> 'zsj',
				'user_id'	=> 555
            ),
        		'errorno' =>0,
        		'errormsg' =>'转端口成功'
        );
        $this->assertEquals($data,$ret);
	}
	public function testunauthEmail(){
		$arrInput = array(
			'UserId'	=> 123,
			'oldemail'	=> '12345@qq.com',
			'newemail'	=> '928025455@qq.com',
		);
		$res = array(
			'data'	=> array(
				'email_auth_time'	=>  100000
			)
		);
		$res1 = array(
			'errorno'	=> 1002
		);
		$res2 = array(
			'errorno'	=> 0
		);
		$res3 = array(
			'errorno'	=> 0
		);
		$obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("getUser","createEmailAuthCode","unauthEmail","updateUser"));
        $obj ->expects( $this->any())
         ->method('getUser')
         ->will($this->returnValue($res));	
		
		$obj ->expects( $this->any())
		->method('createEmailAuthCode')
		 ->will($this->returnValue($res1));  
		
		 $obj ->expects( $this->any())
		->method('unauthEmail')
		 ->will($this->returnValue($res2));
		
		 $obj ->expects( $this->any())
        ->method('updateUser')
         ->will($this->returnValue($res3));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
		$obj = new Service_Page_HouseReport_Org_AgentToBiz();
		$ret = $obj->unauthEmail($arrInput);
		$data = array(
			'errorno'	=> 1002
		);
		$this->assertEquals($data,$ret);

		

		 $arrInput = array(
            'UserId'    => 123,
            'oldemail'  => '12345@qq.com',
            'newemail'  => '928025455@qq.com',
        );
        $res = array(
            'data'  => array(
                'email_auth_time'   =>  100000
            )
        );
        $res1 = array(
            'errorno'   => 0
        );
        $res2 = array(
            'errorno'   => 1003
        );
        $res3 = array(
            'errorno'   => 0
        );
		$obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("getUser","createEmailAuthCode","unauthEmail","updateUser"));
        $obj ->expects( $this->any())
         ->method('getUser')
         ->will($this->returnValue($res));

        $obj ->expects( $this->any())
        ->method('createEmailAuthCode')
         ->will($this->returnValue($res1));
   
         $obj ->expects( $this->any())
        ->method('unauthEmail')
         ->will($this->returnValue($res2));
   
         $obj ->expects( $this->any())
        ->method('updateUser')
         ->will($this->returnValue($res3));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $obj = new Service_Page_HouseReport_Org_AgentToBiz();
        $ret = $obj->unauthEmail($arrInput);
        $data = array(
            'errorno'   => 1003
        );
        $this->assertEquals($data,$ret);
		

		$arrInput = array(
            'UserId'    => 123,
            'oldemail'  => '12345@qq.com',
            'newemail'  => '928025455@qq.com',
        );
        $res = array(
            'data'  => array(
                'email_auth_time'   =>  100000
            )
        );
        $res1 = array(
            'errorno'   => 0
        );
        $res2 = array(
            'errorno'   => 0
        );
        $res3 = array(
            'errorno'   => 0
        );	
		$obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("getUser","createEmailAuthCode","unauthEmail","updateUser"));
        $obj ->expects( $this->any())
         ->method('getUser')
         ->will($this->returnValue($res));

        $obj ->expects( $this->any())
        ->method('createEmailAuthCode')
         ->will($this->returnValue($res1));
  
         $obj ->expects( $this->any())
        ->method('unauthEmail')
         ->will($this->returnValue($res2));
  
         $obj ->expects( $this->any())
        ->method('updateUser')
         ->will($this->returnValue($res3));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $obj = new Service_Page_HouseReport_Org_AgentToBiz();
        $ret = $obj->unauthEmail($arrInput);
        $data = array(
            'errorno'   => 0
        );
        $this->assertEquals($data,$ret);
	}
	public function testexecute(){
		$arrInput = array(
			'newemail'	=>'928025455@qq.com',
			'oldemail'	=>'12345@qq.com',
			'status'	=> 2
		);
		$res = array(
			'errorno'	=> 0,
			'errorno'	=> true
		);
		$res1 = true;
		$obj =  $this->genObjectMock("Service_Page_HouseReport_Org_AgentToBiz",array("authEmail","unauthEmail"));
		 $obj->expects( $this->any())
		 ->method('authEmail')
          ->will($this->returnValue($res));
			
		$ret = $obj->execute($arrInput);
		$returndata = array(
			'errorno'	=> true,
		);
		$this->assertEquals($returndata,$ret);

		 $arrInput = array(
            'newemail'  =>'928025455@qq.com',
            'oldemail'  =>'12345@qq.com',
            'status'    => 3
        );
        $res = array(
            'errorno'   => 0,
        );
        $res1 = true;
        $obj =  $this->genObjectMock("Service_Page_HouseReport_Org_AgentToBiz",array("unauthEmail"),array(),'',true);
         $obj->expects( $this->any())
         ->method('unauthEmail')
          ->will($this->returnValue($res));

        $ret = $obj->execute($arrInput);
        $data = array(
			 'errorno'   => 0,
        		'errormsg'=>'转端口成功',
        		'data'=>array()
		);
        $this->assertEquals($data,$ret);	
		

	   $arrInput = array(
            'newemail'  =>'',
            'oldemail'  =>'12345@qq.com',
            'status'    => 3
        );	
		$obj = new Service_Page_HouseReport_Org_AgentToBiz();
		$ret = $obj->execute($arrInput);
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);
	}

}
