<?php
class	CompanyStoresAddressTest extends Testcase_PTest{
   protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
   }	
	public function testaddCompanyStoresAddress(){
		$arrRows = 123;
		$obj = new Service_Data_Gcrm_CompanyStoresAddress();
		$ret = $obj->addCompanyStoresAddress( $arrRows);
		$data = array(
			'data'		=> array(),
			'errorno'	=>1002,
			'errormsg'	=> '[参数不合法]'
		);		
		$this->assertEquals($data,$ret);

		$arrRows = array(
			'company'	=> '赶集测试公司'
		);
		$res = true;
		$obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("insert"));
		$obj->expects($this->any())
		->method("insert")
		->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->addCompanyStoresAddress( $arrRows);
        $data = array(
            'data'      => true,
            'errorno'   =>0,
            'errormsg'  => '[数据返回成功]'
        );          
        $this->assertEquals($data,$ret);	

		 $arrRows = array(
            'company'   => '赶集测试公司'
        );
        $res = false;
		$res1 = true;
        $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("insert","getLastSQL"));
        $obj->expects($this->any())
        ->method("insert")
        ->will($this->returnValue($res));

		$obj->expects($this->any())
        ->method("getLastSQL")
        ->will($this->returnValue($res1));	
     	Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->addCompanyStoresAddress( $arrRows);
        $data = array(
            'data'      => array(),
            'errorno'   =>1003,
            'errormsg'  => '[SQL语句执行失败]'
        );          
        $this->assertEquals($data,$ret);	
	}
	public function testaddCompanyStoresAddressException(){
        $arrRows = array(
            'company'   => '赶集测试公司'
        );
		$res1 =true;
        $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("insert","getLastSQL"));
        $obj->expects($this->any())
        ->method("insert")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
		$obj->expects($this->any())
        ->method("getLastSQL")
        ->will($this->returnValue($res1));	
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->addCompanyStoresAddress( $arrRows);
        $data = array(
		    'data'      => array(),
            'errorno'   =>1003,
            'errormsg'  => '[SQL语句执行失败]'
        );          
        $this->assertEquals($data,$ret);
	}
	public function testupdateCompanyStoresAddressById(){
		$id = 123;	
		$arrChangeRow = array(
			'account_id'	=> 123
		);
		$res = true;
	    $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("update"));
        $obj->expects($this->any())
        ->method("update")
        ->will($this->returnValue($res));	
		Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
		$obj1 =new Service_Data_Gcrm_CompanyStoresAddress();
		$ret =$obj1->updateCompanyStoresAddressById($id,$arrChangeRow);
		$data = array(
			'data'		=> true,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($ret,$data);
	}
	
   public function testupdateCompanyStoresAddressByIdException(){
		$id = 123;	
		$arrChangeRow = array(
			'account_id'	=> 123
		);
        $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("update"));
        $obj->expects($this->any())
        ->method("update")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->updateCompanyStoresAddressById($id, $arrChangeRow);
        $data = array(
            'data'      => null,
            'errorno'   =>1002,
            'errormsg'  => '[参数不合法]'
        );
        $this->assertEquals($data,$ret);
    }	
	public function testgetStoreInfoByUserId(){
		$id = 123;
		$customer_id = 45;
		$admin_id  = 11;
		$creator_id = 101;
		$arrFileds = array('account_id');
		$res = array('success');
		$obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("select"));
        $obj->expects($this->any())
        ->method("select")
		->will($this->returnValue($res));
		  Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
		$obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
		$ret = $obj1->getStoreInfoByUserId($id,$customer_id,$admin_id,$creator_id,$arrFileds);
		$data = array(
			'data'		=> 'success',
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($ret,$data);

		$id = 123;
        $customer_id = 45;
        $admin_id  = 11;
        $creator_id = 101;
        $arrFileds = array('account_id');
        $res = false;
		$res1 = true;
        $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("select","getLastSQL"));
        $obj->expects($this->any())
        ->method("select")
        ->will($this->returnValue($res));

		$obj->expects($this->any())
        ->method("getLastSQL")
        ->will($this->returnValue($res1));	
          Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->getStoreInfoByUserId($id,$customer_id,$admin_id,$creator_id,$arrFileds);
        $data = array(
            'data'      => array(),
            'errorno'   => 1003,
            'errormsg'  => '[SQL语句执行失败]'
        );
        $this->assertEquals($ret,$data);	
	}
		
	public function testgetStoreInfoByUserIdException(){
	    $id = 123;
        $customer_id = 45;
        $admin_id  = 11;
        $creator_id = 101;
        $arrFileds = array('account_id');	
        $obj = $this->genObjectMock("Dao_Gcrm_CompanyStoresAddress",array("select"));
        $obj->expects($this->any())
        ->method("select")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CompanyStoresAddress",$obj);
        $obj1 = new Service_Data_Gcrm_CompanyStoresAddress();
        $ret = $obj1->getStoreInfoByUserId($id,$customer_id,$admin_id,$creator_id,$arrFileds);
        $data = array(
            'data'      => null,
            'errorno'   =>1002,
            'errormsg'  => '[参数不合法]'
        );
        $this->assertEquals($data,$ret);
    }  	
}

