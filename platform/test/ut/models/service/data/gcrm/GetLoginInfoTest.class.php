<?php
class GetLoginInfo extends Testcase_PTest{
	protected function setUp(){
		Gj_LayerProxy::$is_ut = true;
	}
	public function testexecute(){
		$arrConds = array ('email = ' => 'admin_gjcs');
		$res['data']['company_id'] = 123;
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoByAccount"));  
		$obj ->expects( $this->any())
			//->with($email)
			->method('getOrgInfoByAccount') 
			->will($this->returnValue($res)); 		

		$companyRes = array(
			'errorno' => 0,
			'data' => array(
				'CompanyId' => 123,
				'CityId' => 12
			)
		);
		$companyObj = $this->genObjectMock("Service_Data_Gcrm_Company", array("getCompanyInfoById"));  
		$companyObj->expects( $this->any())
			//->with($email)
			->method('getCompanyInfoById') 
			->will($this->returnValue($companyRes)); 		

		$bizRes = array(
			'errorno' => 0,
			'data' => array(
				'companybriefintroduction' => 'aaaa',
				'companylogopicurl' => 'aaaa'
			)
		);
		$bizObj = $this->genObjectMock("Service_Data_CompanyShop_BizCompanyInfo", array("getBizCompanyByCompanyId"));  
		$bizObj->expects( $this->any())
			//->with($email)
			->method('getBizCompanyByCompanyId') 
			->will($this->returnValue($bizRes)); 		

		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Company", $companyObj);
		Gj_LayerProxy::registerProxy("Service_Data_CompanyShop_BizCompanyInfo", $bizObj);

		$orgObj = new Service_Page_HouseReport_Org_GetLoginInfo();
		$ret = $orgObj->execute($arrConds);
		$data = array(
			'errorno' => '0',
			'errormsg' => "[数据返回成功]",
			'data' => array(
				'user' =>  $res['data'],
				'company' => array(
					'CompanyId' => 123,
					'CityId' => 12,
					'companybriefintroduction' => 'aaaa',
					'companylogopicurl' => 'aaaa'
				)
			)
		);
		$this->assertEquals($data,$ret);
	}
}
