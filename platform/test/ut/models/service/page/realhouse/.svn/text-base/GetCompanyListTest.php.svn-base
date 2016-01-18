<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class GetCompanyList extends Testcase_PTest
{
	protected $data;

	protected function setUp()
	{
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
	}
	public function testExecute(){
		$arrInput['CityId'] = 12;
		$resCom = array (
		  'data' => 
		  array (
		    array (
		      'CompanyId' => 369100,
		      'HasPaidDeposit' => false,
		      'AccountId' => 1269943,
		      'UserId' => 432926890,
		    ),
	  		array (
	  				'CompanyId' => 369104,
	  				'HasPaidDeposit' => false,
	  				'AccountId' => 1270040,
	  				'UserId' => 432939778,
	  		),
		  ),
		  'errorno' => '0',
		  'errormsg' => '[数据返回成功]',
		);
		$companyList = array(
            		array (
            				'CompanyId' => 369130,
            				'HasPaidDeposit' => false,
            				'AccountId' => 1270485,
            				'UserId' => 433101558,
            				'logo' => 'gjfstmp2/M00/00/02/wKgCzFS,S7KIIE,oAAAYpfGa65MAAAA5wC45S4AABi9612_180-80_9-0.jpg',
            				'company_name' => '真房源总公司11',
            				'short_name' => '真房源测试公司11',
            		),
            		array (
            				'CompanyId' => 369129,
            				'HasPaidDeposit' => false,
            				'AccountId' => 1270484,
            				'UserId' => 433100578,
            				'logo' => 'gjfstmp2/M00/00/02/wKgCzFS,S7KIIE,oAAAYpfGa65MAAAA5wC45S4AABi9612_180-80_9-0.jpg',
            				'company_name' => '真房源测试公司22',
            				'short_name' => '真房源测试公司22',
            		),
            		array (
            				'CompanyId' => 369131,
            				'HasPaidDeposit' => false,
            				'AccountId' => 1270486,
            				'UserId' => 433101670,
            				'logo' => 'gjfstmp2/M00/00/02/wKgCzFS,S7KIIE,oAAAYpfGa65MAAAA5wC45S4AABi9612_180-80_9-0.jpg',
            				'company_name' => '真房源测试公司33',
            				'short_name' => '真房源测试公司33',
            		),
            		array (
            				'CompanyId' => 369109,
            				'HasPaidDeposit' => false,
            				'AccountId' => 1270430,
            				'UserId' => 432985030,
            				'logo' => 'gjfstmp2/M00/00/02/wKgCzFS,S7KIIE,oAAAYpfGa65MAAAA5wC45S4AABi9612_180-80_9-0.jpg',
            				'company_name' => '真房源测试公司44',
            				'short_name' => '真房源测试公司44',
            		),
            );
		$obj1 = $this->genObjectMock("Service_Page_RealHouse_GetCompanyList",array("getRealHouseCompanyByCityId","getCompanyInfoByCompanyId"),array(),'',true);
		$obj1->expects($this->any())
		->method("getRealHouseCompanyByCityId")
		->with($arrInput['CityId'])
		->will($this->returnValue($resCom));
		
		$obj1->expects($this->any())
		->method("getCompanyInfoByCompanyId")
		->with($resCom)
		->will($this->returnValue($companyList));
		
		$res = $obj1->execute($arrInput);
		$this->data['data'] = $companyList;
		$this->assertEquals($this->data,$res);
	}
	public function testGetRealHouseCompanyByCityId(){
		$arrInput['CityId'] = 12;
		$this->data['data'] = array(
				array (
						'CompanyId' => 369130,
						'HasPaidDeposit' => false,
						'AccountId' => 1270485,
						'UserId' => 433101558,
				),
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_Company",array("getRealHouseCompanyByCityId"));
		$obj->expects($this->any())
		->method("getRealHouseCompanyByCityId")
		->with($arrInput['CityId'])
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Company", $obj);
		$comObj = new Service_Page_RealHouse_GetCompanyList();
		$res = $comObj->getRealHouseCompanyByCityId($arrInput['CityId']);
		$this->assertEquals($this->data,$res);
	}
	public function testGetCompanyInfoByCompanyId(){
		$city_id = 12;
		$resCustomer = array(
				'errorno' => ErrorConst::SUCCESS_CODE,
				'errormsg' => ErrorConst::SUCCESS_MSG,
				'data'=>array(array (
						'CompanyId' => 369130,
						'HasPaidDeposit' => false,
						'AccountId' => 1270485,
						'UserId' => 433101558,
				),)
		);
		$this->data['data'] = array(
				369130=>array (
						'CompanyId' => 369130,
						'companylogopicurl' => 'gjfstmp2/M00/00/02/wKgCzFS,S7KIIE,oAAAYpfGa65MAAAA5wC45S4AABi9612_180-80_9-0.jpg',
						'companyfullname' => '真房源总公司11',
						'companyname' => '真房源测试公司11',
				),
		);
		
		$obj = $this->genObjectMock("Service_Data_CompanyShop_BizCompanyInfo",array("getAllBizCompanyList"));
		$obj->expects($this->any())
		->method("getAllBizCompanyList")
		->with($city_id,HousingVars::SELL_ID)
		->will($this->returnValue($this->data));
		
		Gj_LayerProxy::registerProxy("Service_Data_CompanyShop_BizCompanyInfo", $obj);
		$comObj = new Service_Page_RealHouse_GetCompanyList();
		$res = $comObj->getCompanyInfoByCompanyId($resCustomer, $city_id);
		$resCustomer['data'][0]['logo'] = $this->data['data'][369130]['companylogopicurl'];
		$resCustomer['data'][0]['company_name'] = $this->data['data'][369130]['companyfullname'];
		$resCustomer['data'][0]['short_name'] = $this->data['data'][369130]['companyname'];
		$this->assertEquals($resCustomer['data'],$res);
	}
}