<?php
class ModifyPasswordTest extends Testcase_PTest{
	protected function setUp(){
		 Gj_LayerProxy::$is_ut = true;  
	}
	public function testexecute(){
		$arrInput = array (
			'password' =>'1111111',//oldpassword
			'oldpassword' => '111111', //newpassword
			'account' => 'admin_gjcs'
		);
		$res = array (
				'data' =>0,
				'errorno' =>0,
				'errormsg' =>'[数据返回成功]'
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("updateOrgById"));
		$obj ->expects( $this->any())
			 ->method('updateOrgById')
			  ->will($this->returnValue($res)); 
		Gj_LayerProxy::registerProxy('Service_Data_Gcrm_HouseManagerAccount',$obj);
		$orgObj = new  Service_Page_HouseReport_Org_ModifyPassword();
		$ret = $orgObj->execute ( $arrInput);
		$data = array (
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($data,$ret); 
	}
}
