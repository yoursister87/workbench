<?php
class StoreToAreaTest extends Testcase_PTest{
	protected function setUp(){
		Gj_LayerProxy::$is_ut = true;
	}
	public function testexecute(){
		$arrInput = array (
			'id' => 235,
			'pid' => 42244
		);
		$res = array(
			'id'		 => 235,
			'pid'		 => 42244,
			'company_id' => 1575,	
			'level'      => 1
		);
		$res1 = array(
			'errorno'  => 0,
			'errormsg' => '[数据返回成功]'	
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","updateOrgById"));	
		$obj ->expects( $this->any())
			->method('getOrgInfoByIdOrAccount')
			 ->will ($this->returnValue($res));
		 $obj ->expects( $this->any()) 
			 ->method('updateOrgById')
			 ->will ($this->returnValue($res1));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
		  $orgObj = new Service_Page_HouseReport_Org_StoreToArea();
		$ret =  $orgObj->execute($arrInput);
		$data = array(
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);		
		$this->assertEquals($data,$ret); 
	}
} 
