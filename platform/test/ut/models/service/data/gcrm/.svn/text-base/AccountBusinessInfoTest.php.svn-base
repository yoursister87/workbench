<?php
class AccountBusinessInfoTest  extends Testcase_PTest{
	protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
  	 }
	public function testgetWhereArr(){
		$whereConds = array(
			'customerId'	=> 11,
			'companyId'		=> 22,
			'accountId'		=> 33,
			'inDuration'	=> 1,
			'userId'		=> 44,
			'countType'		=> 5,
			'businessScope'	=>1
		);	
		$obj = new Service_Data_Gcrm_AccountBusinessInfo();
		$ret = $obj->getWhereArr($whereConds);
		$data = array(
			'CustomerId ='    => 11, 
            'CompanyId ='     => 22, 
            'AccountId ='     => 33, 
            'InDuration ='    => 1,
            'UserId ='        => 44, 
            'CountType ='     => 5,
            'BussinessScope =' =>1 
		);
		$this->assertEquals($data,$ret);

		 $whereConds = array(
            'customerId'    => array('11,23'), 
            'companyId'     => 22, 
            'accountId'     => array('33,55'), 
            'inDuration'    => 1,
            'userId'        => 44, 
            'countType'     => 5,
            'businessScope' =>1 
        );  
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getWhereArr($whereConds);
        $data = array(
			//'CustomerId IN (11,23) AND ' => 1,
            'CompanyId ='     => 22,          
			// 'AccountId IN (33,55) AND ' => 1,
            'InDuration ='    => 1,
            'UserId ='        => 44,
            'CountType ='     => 5,
            'BussinessScope ='	=> 1,
            0 => 'CustomerId IN (11,23)',
            1 => 'AccountId IN (33,55)',
        );  
        $this->assertEquals($data,$ret);
    }

	public function testgetAccountListByCompanyId(){
		
		$companyId = 835;	
		$customerIds = array(123);
		$businessScope = 1;
		$effective = true;
		$countType = 4;
		$res = true;
		$res1 = true;
		$obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
		$obj->expects($this->any())
		->method("getWhereArr")
		->will($this->returnValue($res));
	    $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByPage"));
        $obj1->expects($this->any())
        ->method("selectByPage")
		->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
		$ret = $obj->getAccountListByCompanyId($companyId,$businessScope,$effective,$countType);
		$data = array(
			'errorno'   => 1002,
			'errormsg'  => '[参数不合法]',
			'data'  =>  array()
		);  
		$this->assertEquals($data,$ret);
	}
	public function testgetAccountListByCompanyIdException(){
		$companyId = 'ss';		
		$customerIds = array(123);
        $businessScope = 1;
        $effective = true;
        $countType = 4;	
		$obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getAccountListByCompanyId($companyId,$customerIds,$businessScope,$effective,$countType);	
	}
	public function testgetAccountListByCompanyIdException1(){
		$companyId = 835;
        $customerIds = array(123);
        $businessScope = 1;
        $effective = true;
        $countType = 4;
        $res = true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByPage"));
        $obj1->expects($this->any())
        ->method("selectByPage")
        ->will( $this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getAccountListByCompanyId($companyId,$businessScope,$effective,$countType);
        $data = array(
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]',
			'data'	=>  array()
		);
        $this->assertEquals($data,$ret);	
	}
	public function testgetAccountListByCompanyIdCount(){

		$companyId = '835';
        $customerIds = array(123);
        $businessScope = 1;
        $effective = true;
        $countType = 4;
        $res = true;				
		$res1 =true;
		$obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByCount"));
        $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getAccountListByCompanyIdCount($companyId,$customerIds);
		$data = array('data'	=> true);
		$this->assertEquals($data,$ret);
	}

   public function testgetAccountIdByCustomerId(){
        $customerId = 123;
        $businessScope = 1;
        $effective = true;
        $res = true;
        $res1 =true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByPage"));
        $obj1->expects($this->any())
        ->method("selectByPage")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getAccountIdByCustomerId($customerId,$businessScope,$effective);
        $data = array('data'    => true);
        $this->assertEquals($data,$ret);
    }
    public function testgetAccountIdByCustomerIdException(){
        $customerIds = array(123);
        $businessScope = 1;
        $effective = true;
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getAccountIdByCustomerId($customerId,$businessScope,$effective);
    }
	  public function testgetAccountIdByCustomerIdCount(){
        $customerId = 123;
        $businessScope = 1;
        $effective = true;
        $res = true;
        $res1 =true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByCount"));
        $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getAccountIdByCustomerIdCount($customerId,$businessScope,$effective);
        $data = array('data'    => true);
        $this->assertEquals($data,$ret);
    }	
   public function testgetAccountIdByCustomerIdCountException(){
        $customerIds = array(123);
        $businessScope = 1;
        $effective = true;
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getAccountIdByCustomerIdCount($customerId,$businessScope,$effective);
    }	
     public function testgetBusinessInfoByAccountIds(){
        $accountIds = 123;
		$countType	= 4;
        $res = true;
        $res1 =true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("select"));
        $obj1->expects($this->any())
        ->method("select")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getBusinessInfoByAccountIds($accountIds,$countType);
        $data = array('data'    => true);
        $this->assertEquals($data,$ret);
    }	
 	 public function testgetBusinessInfoByAccountIdsException(){
		$accountIds = '';
        $countType  = 4;
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getBusinessInfoByAccountIds($accountIds,$countType);
    } 	
	public function testgetBusinessList(){
		$companyId =  123;
		$customerIds = array(234);
		$businessScope = 1;
		$effective = true;
		$countType = 4;	
		$res = true;
        $res1 =true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("select"));
        $obj1->expects($this->any())
        ->method("select")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getBusinessList($companyId,$customerIds,$businessScope,$effective,$countType);
		$data = array('data'	=> true);
		$this->assertEquals($data,$ret);
	}
    public function testgetBusinessListException(){
		$companyId =  "ssss";
        $customerIds = array(234);
        $businessScope = 1;
        $effective = true;
        $countType = 4;
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getBusinessList($companyId,$customerIds,$businessScope,$effective,$countType);
  }	
	public function testgetBusinessStatusCount(){
		$companyId = 123;
		$customerIds = array(234);	
		$businessScope = 1;
		$flag = 1;
		$res = array(
			'NextDurationBeginTime >='	=> '',
			'MaxEndTime <'				=> ''
		);
        $res1 =true;
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getWhereArr"));
        $obj->expects($this->any())
        ->method("getWhereArr")
        ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("selectByCount"));
        $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj1);
        $ret = $obj->getBusinessStatusCount($companyId,$customerIds,$businessScope,$flag);
		$data = array('data'	=> true);
		$this->assertEquals($data,$ret);
	}
    public function testgetBusinessStatusCountException(){
		$companyId = "sss";
        $customerIds = array(234);
        $businessScope = 1;
        $flag = 1;	
        $obj = new Service_Data_Gcrm_AccountBusinessInfo();
        $ret = $obj->getBusinessStatusCount($companyId,$customerIds,$businessScope,$flag);
  }
	public function testgetAccountBusinessInfolist(){
		$arrInput = array();
		$whereConds = array();
		$obj = new Service_Data_Gcrm_AccountBusinessInfo();
		$ret = $obj->getAccountBusinessInfolist($arrInput,$whereConds);
		$result = null;
		$this->assertEquals($ret,$result);

/*		$arrInput = array();
		$whereConds = array();
		$res = array('data' => 123);
		$obj = $this->genObjectMock("Dao_Housepremier_AccountBusinessInfo",array("select"));
		$obj->expects($this->any())
			->method("select")
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_AccountBusinessInfo",$obj);
		$obj1 = new Service_Data_Gcrm_AccountBusinessInfo();
		$ret = $obj1->getAccountBusinessInfolist($arrInput,$whereConds);
		$result = null; 
		$this->assertEquals($ret,$result);
*/
	}
} 
