<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class CustomerAccount extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('id'=>123,'company_id'=>45888));
		$this->arrFields = array("UserType","AccountId","BussinessScope","ServicePlanId","GroupId as CustomerId","CityId","DistrictId","AreaId","Email","AccountName","AliasName","ICNo","ICImage","BusinessCardImage","Gender","OfficePhone","CellPhone","IM","Fax","DepositAmount","AwardAmount","Balance","IsCPC","LastestRecharge","PremierExpire","RestExpire","RegistrationDate","PersonalIntroduction","HasLicence","Picture","Status","PremierUnfreezeTime","LoginTime","ModifierId","CreatedTime","CreatorName","CreatorId","ModifierName","ModifiedTime","ShopName","ShopNotice","ShopService","AuditAt","AuditId","AuditName","AuditResult","ModifiedAt","UserId","RecentTag","PremierTag");
	}
	public function testGetAccountListByCustomerId(){
		$orderArr = array('DESC'=>'CreatedTime');
		$arrConds = array('GroupId ='=>835);
		$obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount", array("selectByPage")); 
    	$obj->expects($this->any())
	    	->method('selectByPage')
	    	->with($this->arrFields, $arrConds, 1, 30, $orderArr)
	    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccount();
		$whereConds = array('customer_id'=>835);
		$res = $Cusobj->getAccountListByCustomerId($whereConds, $this->arrFields, 1, 30, $orderArr);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);
	}
	public function testGetAccountCountByCustomerId(){
		$arrConds = array('GroupId ='=>835);
		$obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount", array("selectByCount"));
		$obj->expects($this->any())
		->method('selectByCount')
		->with($arrConds)
		->will($this->returnValue(1));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccount();
		$whereConds = array('customer_id'=>835);
		$res = $Cusobj->getAccountCountByCustomerId($whereConds, $this->arrFields);
		$this->data['data'] = 1;
		$this->assertEquals($this->data,$res);
	}
	public function testGetAccountInfoById(){
        $arrConds[] = "AccountId = (835)";
        $arrFields = array("UserType","AccountId");
        $this->data['data'] = $this->result;
        $Cusobj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountInfoByConds"),array(),'',true);
        $Cusobj->expects($this->any())
            ->method('getAccountInfoByConds')
            ->with($arrConds,$arrFields)
            ->will($this->returnValue($this->data));
        $res = $Cusobj->getAccountInfoById(835,$arrFields);
		$this->assertEquals($this->data,$res);
	}
    public function testGetAccountInfoByUserId(){
        $arrConds[] = "UserId = (835)";
        $arrFields = array("UserType","AccountId");
        $this->data['data'] = $this->result;
        $Cusobj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountInfoByConds"),array(),'',true);
        $Cusobj->expects($this->any())
            ->method('getAccountInfoByConds')
            ->with($arrConds,$arrFields)
            ->will($this->returnValue($this->data));
        $res = $Cusobj->getAccountInfoByUserId(835,$arrFields);
        $this->assertEquals($this->data,$res);
    }
	public function testgetCustomerAccountWhere(){
		$whereConds = array(
			'customer_id'	=> 123,
			'account_id'	=> 234,
			'cell_phone'	=> '13752967124',
			'account_name'	=> 'zsj'
		);	
		$obj = new Service_Data_Gcrm_CustomerAccount();
		$ret = $obj->getCustomerAccountWhere($whereConds);
		$data = array(
			'GroupId ='   => 123,
            'AccountId ='    => 234,
            'CellPhone ='    => '13752967124',
            'AccountName ='  => 'zsj'	
		);
		$this->assertEquals($data,$ret);
	}
	public function testUpdateCustomer(){
		$arrChangeRow = array(
			'AccountIds'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试'	
		);	
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"	=>  '{"succeed":1,"data":{"result":111}}'));
		Gj_Util_Curl::setInstance($objUtil);
		$obj1 = new Service_Data_Gcrm_CustomerAccount();
		$ret = $obj1->UpdateCustomer($arrChangeRow);
		$data = array(
			'data'		=> 111,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($data,$ret);
	 	

		$arrChangeRow = array(
            'AccountIds'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试'
        );
        $objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":0,"data":{"result":111}}'));
        Gj_Util_Curl::setInstance($objUtil);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->UpdateCustomer($arrChangeRow);
        $data = array(
            'data'      => array(),
            'errorno'   => 2112,
            'errormsg'  => null
        );
        $this->assertEquals($data,$ret);	
	}
	public function testUpdateProfile(){
	 
		$arrChangeRow = array(
            'AccountId'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试',
			'Key'			=> 'sds'
        );	
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":1,"data":{"result":111}}'));
        Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->UpdateProfile($arrChangeRow);
        $data = array(
            'data'      => null,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);	
		
		$arrChangeRow = array(
            'AccountId'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试',
            'Key'           => 'sds'
        );  
        $objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":0,"data":{"result":111}}'));
        Gj_Util_Curl::setInstance($objUtil);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->UpdateProfile($arrChangeRow);
        $data = array(
            'data'      => array(),
            'errorno'   => 2111,
            'errormsg'  => null
        );  
        $this->assertEquals($data,$ret);   	
	}
	public function testTransferBalance(){
		$arrChangeRow = array(
				'AccountId'    =>'31',
				'CreatorId'     => 1001,
				'CreatorName'   =>'系统管理员',
				'GroupId'    => 312570,
				'CustomerName'  => 'BEIJNG－赶集测试',
				'Key'			=> 'sds'
		);
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"data":{"result":{"Succeed":1,"Message":"转端口成功"}},"succeed":1}'));
		Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_CustomerAccount();
		$ret = $obj->TransferBalance($arrChangeRow);
		$data = array(
				'data'      => '转端口成功',
				'errorno'   => '0',
				'errormsg'  => '[数据返回成功]'
		);
		$this->assertEquals($data,$ret);
		
		$arrChangeRow = array(
				'AccountId'    =>'31',
				'CreatorId'     => 1001,
				'CreatorName'   =>'系统管理员',
				'GroupId'    => 312570,
				'CustomerName'  => 'BEIJNG－赶集测试',
				'Key'           => 'sds'
		);
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":0,"data":{"result":111}}'));
		Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_CustomerAccount();
		$ret = $obj->TransferBalance($arrChangeRow);
		$data = array(
				'data'      => array(),
				'errorno'   => 2110,
				'errormsg'  => null
		);
		$this->assertEquals($data,$ret);
	}
	public function testAddEmailModifyRecord(){
	    $arrChangeRow = array(
            'AccountId'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试',
			'interface'		=> 'IAccountService'
        );
        $objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":1,"data":{"result":111}}'));
        Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->AddEmailModifyRecord($arrChangeRow);
        $data = array(
            'data'      =>null,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);	
		
      $arrChangeRow = array(
            'AccountId'    =>'31',
            'CreatorId'     => 1001,
            'CreatorName'   =>'系统管理员',
            'GroupId'    => 312570,
            'CustomerName'  => 'BEIJNG－赶集测试',
            'interface'     => 'IAccountService'
        );
        $objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":0,"data":{"result":111}}'));
        Gj_Util_Curl::setInstance($objUtil);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->AddEmailModifyRecord($arrChangeRow);
        $data = array(
            'data'      =>array(),
            'errorno'   => 2113,
            'errormsg'  => null
        );  
        $this->assertEquals($data,$ret);	

	}
	public function testgetUid(){
		$name = 'zsj';	
		$res = true;
		$obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUid"));
		$obj->expects($this->any())
		->method("getUid")
		->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);		
		 $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUid($name);
        $data = array(
            'data'      =>true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);	

		$name = 'zsj';
        $res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUid"));
        $obj->expects($this->any())
        ->method("getUid")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUid($name);
        $data = array(
            'data'      =>array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]'
        );
        $this->assertEquals($data,$ret);	
	}
	public function testgetUidException(){
		$name = 'zsj';
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUid"));
        $obj->expects($this->any())
        ->method("getUid")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUid($name);	
		$data = array(
			'data'	=> null,
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]',
		);
		 $this->assertEquals($data,$ret);
	}
	public function testgetUser(){
		$user_id = 123;
        $res = true;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUser"));
        $obj->expects($this->any())
        ->method("getUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUser($user_id);
        $data = array(
            'data'      =>true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);

        $user_id = 123;
        $res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUser"));
        $obj->expects($this->any())
        ->method("getUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUser($user_id);
        $data = array(
            'data'      =>array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]'
        );
        $this->assertEquals($data,$ret);		
	}
	    public function testgetUserException(){
        $user_id  = 123;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getUser"));
        $obj->expects($this->any())
        ->method("getUser")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getUser($user_id );
        $data = array(
            'data'  => null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]',
        );
         $this->assertEquals($data,$ret);	
	}
	public function testgetBizTypeList(){
		$user_id = 123;
        $res = true;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getBizTypeList"));
        $obj->expects($this->any())
        ->method("getBizTypeList")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getBizTypeList($user_id);
        $data = array(
            'data'      =>true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);

        $user_id = 123;
        $res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getBizTypeList"));
        $obj->expects($this->any())
        ->method("getBizTypeList")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getBizTypeList($user_id);
        $data = array(
            'data'      =>array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]'
        );
        $this->assertEquals($data,$ret);		
	}
	  public function testgetBizTypeListException(){
        $user_id  = 123;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getBizTypeList"));
        $obj->expects($this->any())
        ->method("getBizTypeList")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->getBizTypeList($user_id );
        $data = array(
            'data'  => null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]',
        );
         $this->assertEquals($data,$ret);
    }	
	public function testcreateEmailAuthCode(){
/*		$user_id = 123;
		$old_email = '928025455@qq.com';
		$res = 1;
		$obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("createEmailAuthCode"));
		$obj->expects($this->any())
		->method("createEmailAuthCode")
		->will($this->returnValue($res));
		  Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
		$objOrg = new Service_Data_Gcrm_CustomerAccount();
		$ret = $objOrg->createEmailAuthCode($user_id,$old_email);
		$data = array(
			'data'		=> 1,
			'errorno'	=> 0,
			'errormsg'	=>'[数据返回成功]'
		);
		$this->assertEquals($data,$ret);

        $user_id = 123;
        $old_email = '928025455@qq.com';
        $res = 0;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("createEmailAuthCode"));
        $obj->expects($this->any())
        ->method("createEmailAuthCode")
        ->will($this->returnValue($res));
          Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $objOrg = new Service_Data_Gcrm_CustomerAccount();
        $ret = $objOrg->createEmailAuthCode($user_id,$old_email);
        $data = array(
            'data'      => array(),
            'errorno'   => 1006,
            'errormsg'  =>'[接口异常]'
        );
        $this->assertEquals($data,$ret);	
*/
	}

     public function testcreateEmailAuthCodeException(){
        $user_id  = 123;
		$old_email = '928025455@qq.com';
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("createEmailAuthCode"));
        $obj->expects($this->any())
        ->method("createEmailAuthCode")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->createEmailAuthCode($user_id,$old_email);
        $data = array(
            'data'  => array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]',
        );
         $this->assertEquals($data,$ret);
    }	
	public function testunauthEmail(){
		$user_id  = 123;
        $old_email = '928025455@qq.com';
		$code = 456;
		$res = true;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("unauthEmail"));
        $obj->expects($this->any())
        ->method("unauthEmail")
		->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->unauthEmail($user_id,$old_email,$code);
        $data = array(
            'data'  => true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]',
        );
         $this->assertEquals($data,$ret);		

		$user_id  = 123;
        $old_email = '928025455@qq.com';
        $code = 456;
        $res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("unauthEmail"));
        $obj->expects($this->any())
        ->method("unauthEmail")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->unauthEmail($user_id,$old_email,$code);
        $data = array(
            'data'  => array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]',
        );
         $this->assertEquals($data,$ret);
	}	
	
	  public function testunauthEmailException(){
        $user_id  = 123;
        $old_email = '928025455@qq.com';
		$code = 456;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("unauthEmail"));
        $obj->expects($this->any())
        ->method("unauthEmail")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->unauthEmail($user_id,$old_email,$code);
        $data = array(
            'data'  => null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]',
        );
         $this->assertEquals($data,$ret);
    } 

	  public function testupdateUser(){
		$user = array(
			'id'	=> 123
		);
        $res = true;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("updateUser"));
        $obj->expects($this->any())
        ->method("updateUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->updateUser($user);
        $data = array(
            'data'  => true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]',
        );
         $this->assertEquals($data,$ret);

        $user = array(
            'id'    => 123
        );
		$res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("updateUser"));
        $obj->expects($this->any())
        ->method("updateUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->updateUser($user);
        $data = array(
            'data'  => array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]',
        );
         $this->assertEquals($data,$ret);
	} 

     public function testupdateUserException(){	
        $user = array(
            'id'    => 123
        );	
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("updateUser"));
        $obj->expects($this->any())
        ->method("updateUser")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj1 = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj1->updateUser($user);
        $data = array(
            'data'  => null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]',
        );
         $this->assertEquals($data,$ret);
    }	
	public function testaddQueueAccountChange(){
		$arrRows = "123";
		$obj = new Service_Data_Gcrm_CustomerAccount();
		$ret = $obj->addQueueAccountChange( $arrRows );
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);


		$arrRows =  array(
			'account_id'	=> 123
		);
		$res = true;
		$obj = $this->genObjectMock("Dao_Tgqe_AccountChangeQueue",array("insert"));
		$obj->expects($this->any())
		->method("insert")
		->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Dao_Tgqe_AccountChangeQueue", $obj);
		$obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->addQueueAccountChange($arrRows);
		$data = array(
			'data'		=> true,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'

		);
		$this->assertEquals($data,$ret);

        $arrRows =  array(
            'account_id'    => 123
        );
        $res = false;
		$res1 = true;
        $obj = $this->genObjectMock("Dao_Tgqe_AccountChangeQueue",array("insert","getLastSQL"));
        $obj->expects($this->any())
        ->method("insert")
        ->will($this->returnValue($res));
		 $obj->expects($this->any())
        ->method("getLastSQL")
        ->will($this->returnValue($res1));	
         Gj_LayerProxy::registerProxy("Dao_Tgqe_AccountChangeQueue", $obj);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->addQueueAccountChange($arrRows);
        $data = array(
            'data'      => array(),
            'errorno'   => 1003,
            'errormsg'  => '[SQL语句执行失败]'

        );
        $this->assertEquals($data,$ret);
	}
	public function testsetPassword(){
		$uid = 123;
		$passwd = 345;
		$res = true;
		$obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("setPassword"));
        $obj->expects($this->any())
        ->method("setPassword")
        ->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->setPassword($uid,$passwd,$desc);
        $data = array(
            'data'      => true,
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'

        );
        $this->assertEquals($data,$ret);

		$uid = 123;
        $passwd = 345;
        $res = -2;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("setPassword"));
        $obj->expects($this->any())
        ->method("setPassword")
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->setPassword($uid,$passwd,$desc);
        $data = array(
            'errorno'   => 2101,
            'errormsg'  => '和其他密码一致，修改失败'

        );
        $this->assertEquals($data,$ret);	

	    $uid = 123;
        $passwd = 345;
        $res = -3;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("setPassword"));
        $obj->expects($this->any())
        ->method("setPassword")
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
        $obj = new Service_Data_Gcrm_CustomerAccount();
        $ret = $obj->setPassword($uid,$passwd,$desc);
        $data = array(
            'errorno'   => 2102,
            'errormsg'  => '密码修改失败'

        );
        $this->assertEquals($data,$ret);	
	}	

	public function testbatchGetUser(){
		$user_ids = '123';
		$res = true;
     	$obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("batchGetUser"));
        $obj->expects($this->any())
        ->method("batchGetUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface",$obj); 
		 $obj1 = new Service_Data_Gcrm_CustomerAccount();
		 $ret = $obj1->batchGetUser($user_ids);
		 $data = array(
			'data'		=> true,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($data,$ret);

		$user_ids = '123';
        $res = false;
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("batchGetUser"));
        $obj->expects($this->any())
        ->method("batchGetUser")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface",$obj);
         $obj1 = new Service_Data_Gcrm_CustomerAccount();
         $ret = $obj1->batchGetUser($user_ids);
         $data = array(
            'data'      => array(),
            'errorno'   => 1006,
            'errormsg'  => '[接口异常]'
        );  
        $this->assertEquals($data,$ret);	
	}
    public function testGetAccountInfoByConds(){
        $user_id = 835;
        $arrFields = array("UserType","AccountId");
        $arrConds[] = "UserId = ({$user_id})";
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount", array("select"));
        $obj->expects($this->any())
            ->method('select')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);
        $cusObj = new Service_Data_Gcrm_CustomerAccount();
        $res = $cusObj->getAccountInfoByConds($arrConds,$arrFields);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data,$res);
    }
}
