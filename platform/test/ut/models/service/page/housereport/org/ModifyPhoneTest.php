<?php
class ModifyPhoneTest extends Testcase_PTest{
	 protected function setUp(){ 
		 Gj_LayerProxy::$is_ut = true;  
	 }
	 public function testexecute(){
		 $arrInput = array ('phone'=>111,
						'phone =' =>'222',
						'account ='=> 'admin_gjcs'
		 );
		$arrRows = array ('phone' =>'111');
	    $arrCord = array ('phone =' =>'222',
			'account ='=> 'admin_gjcs');
		$res['data']['errorno'] = 0;
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("updateOrgById"));
		$obj ->expects( $this->any())
			->method('updateOrgById')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy('Service_Data_Gcrm_HouseManagerAccount',$obj);
		$orgObj = new  Service_Page_HouseReport_Org_ModifyPhone();
		$ret = $orgObj->execute($arrInput);
		$data = array ('errorno' =>0,
						'errormsg' => '[数据返回成功]',
						'errorno' =>0
		);
		$this->assertEquals($data,$ret);
	 }
}

