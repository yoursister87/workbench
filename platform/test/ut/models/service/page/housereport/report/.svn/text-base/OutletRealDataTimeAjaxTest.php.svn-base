<?php
/**
 * File Name:OutletAjaxTest.php
 * @author              $Author:zhangshijin$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
 */

class OutletRealDataTimeAjaxTest extends Testcase_PTest
{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
		$this->obj =  new Service_Page_HouseReport_Report_OutletRealDataTimeAjax();
	}   
	public function testexecute(){

		$arr['countType'] =  array(1,3,5,12);
		$res = array(
			'product'=> array('premier','assure','bid'),
			'dtype'  => array('org','verify'),
			'page'	=> 1,
			'data' => array( 'count' => 2),
			'level' => 9
		);
		$res3 = array(1,3,5,12);
		$res7 = 15;
		$obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",array("getCommonParams","setHouseType","getPageStr"));
		$obj->expects($this->any()) 
			->method("getCommonParams")
			->will($this->returnValue($res));

		$obj->expects($this->any()) 
			->method("setHouseType")
			->will($this->returnValue($res3));

		$obj->expects($this->any()) 
			->method("getPageStr")
			->will($this->returnValue($res7));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);

		$res1= 1;
		$res2 = 1;
		$obj = $this->genObjectMock("Service_Data_HouseReport_CheckData",array("setCountType","setBusinessScope"));
		$obj->expects($this->any()) 
			->method("setCountType")
			->will($this->returnValue($res1));

		$obj->expects($this->any()) 
			->method("setBusinessScope")
			->will($this->returnValue($res2));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_CheckData",$obj);

		$res4 = array(
			"accountIds" => "123"
		);
		$obj1 = $this->genObjectMock("Service_Page_HouseReport_Report_OutletRealDataTimeAjax",array("groupOutletData"));
		$obj1->expects($this->any()) 
			->method("groupOutletData")
			->will($this->returnValue($res4));

		$res5 = true;
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAjaxData"));
		$obj->expects($this->any()) 
			->method("groupAjaxData")
			->will($this->returnValue($res5));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletData",$obj);


		$res6 = array('data' => '数据显示');
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("matchTitleData"));
		$obj->expects($this->any()) 
			->method("matchTitleData")
			->will($this->returnValue($res6));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj);
		$ret = $obj1->execute($arr);
		$result = array(
			'data'	=> array(
				'dataList' => true,
				'titleList' => '数据显示',
				'page'	=> 15
			),
		);
		$this->assertEquals($ret,$result);
	}
	public function  testexecuteException1(){
		
		$arr['countType'] =  array(1,3,5,12);
		$res = array(
			'product'=> array('premier','assure','bid'),
			'dtype'  => array('org','verify'),
			'page'	=> 1,
			'data' => array( 'count' => 2),
			'level' => 9
		);
		$res3 = array(1,3,5,12);
		$obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",array("getCommonParams","setHouseType"));
		$obj->expects($this->any()) 
			->method("getCommonParams")
			->will($this->returnValue($res));

		$obj->expects($this->any()) 
			->method("setHouseType")
			->will($this->returnValue($res3));
		  Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);

		$res1= 1;
		$res2 = 1;
		$obj = $this->genObjectMock("Service_Data_HouseReport_CheckData",array("setCountType","setBusinessScope"));
	
		$obj->expects($this->any()) 
			->method("setBusinessScope")
			->will($this->returnValue($res1));	
		
		$obj->expects($this->any()) 
			->method("setCountType")
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_CheckData",$obj);
		$obj1 = new Service_Page_HouseReport_Report_OutletRealDataTimeAjax();
		$ret = $obj1->execute($arr);
		$result = array(
			
			'data' => array(),
			'errorno' =>  1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);

	}
		public function  testexecuteException2(){
			$arr['countType'] =  array(1,3,5,12);
			$res = array(
				'product'=> array('premier','assure','bid'),
				'dtype'  => array('org','verify'),
				'page'  => 1,
				'data' => array( 'count' => 2),
				'level' => 9
			);
			$res3 = array(1,3,5,12);
			$res7 = 15;
			$obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",array("getCommonParams","setHouseType","getPageStr"));
			$obj->expects($this->any()) 
				->method("getCommonParams")
				->will($this->returnValue($res));

			$obj->expects($this->any()) 
				->method("setHouseType")
				->will($this->returnValue($res3));

			$obj->expects($this->any()) 
				->method("getPageStr")
				->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
			Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);				

			$res1= 1;
			$res2 = 1;
			$obj = $this->genObjectMock("Service_Data_HouseReport_CheckData",array("setCountType","setBusinessScope"));
			$obj->expects($this->any()) 
				->method("setCountType")
				->will($this->returnValue($res1));

			$obj->expects($this->any()) 
				->method("setBusinessScope")
				->will($this->returnValue($res2));
			Gj_LayerProxy::registerProxy("Service_Data_HouseReport_CheckData",$obj);

			$res4 = array(
				"accountIds" => "123"
			);
			$obj1 = $this->genObjectMock("Service_Page_HouseReport_Report_OutletRealDataTimeAjax",array("groupOutletData"));
			$obj1->expects($this->any()) 
				->method("groupOutletData")
				->will($this->returnValue($res4));

			$res5 = true;
			$obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAjaxData"));
			$obj->expects($this->any()) 
				->method("groupAjaxData")
				->will($this->returnValue($res5));
			Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletData",$obj);	

			$res6 = array('data' => '数据显示');
			$obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("matchTitleData"));
			$obj->expects($this->any()) 
				->method("matchTitleData")
				->will($this->returnValue($res6));
			Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj);
			$ret = $obj1->execute($arr);
			$result = array(
				'data'  => array(
					'dataList' => true,
					'titleList' => '数据显示',
				),
				'errorno' => 1002,
				'errormsg' => '[参数不合法]'
			);
			$this->assertEquals($ret,$result);	
		}
	public function testgroupOutletData(){
		$params['page'] = 1;
		$params['stype'] = 1;
		$params['keyword'] = 123;
		$res = array('data' => array(0 => array('AccountId' => 123,'name' => 'zsj')));
		$res1 = array('data' => array(0 => array('AccountId' => 123,'AccountName' => 'zsj','CustomerId' =>456)));
		$res2 = array();
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",array("SearchAgent"));
		$obj->expects($this->any())
			->method("SearchAgent")
			->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount",$obj);


		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById"));
		$obj->expects($this->any())
			->method("getAccountInfoById")
			->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

		$obj = $this->genObjectMock("Util_CommonUrl",array("createUrl"));
		$obj->expects($this->any())
			->method("createUrl")
			->will($this->returnValue($res2));
		Gj_LayerProxy::registerProxy("Util_CommonUrl",$obj);

		$obj1 = new Service_Page_HouseReport_Report_OutletRealDataTimeAjax();
		$ret = $obj1->groupOutletData($params);
		$result = array(
			'data'	=> array(
				'count'	=> 1,
				'title'	=> '姓名',
				'title_list'	=> array(
					'123'	=> array(
						'name' => 'zsj'
					)
				)
			),
			'accountIds' => array( 0 => 123)
		);

		$params['page'] = 1;
		$params['stype'] = 1;
		$params['keyword'] = '';
		$params['companyId'] = 235;
		$res = array('data' => array(0 => array('AccountId' => 123,'name' => 'zsj')));
		$res1 = array('data' => array(0 => array('AccountId' => 123,'AccountName' => 'zsj','CustomerId' =>456)));
		$res2 = array();
		$res3 = 235;
		$res4 = array('data' => 5);

		$obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",array("getAllOutlet"));
		$obj->expects($this->any())
			->method("getAllOutlet")
			->will($this->returnValue($res3));
		 Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService",$obj);

		$obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getAccountListByCompanyId","getAccountListByCompanyIdCount"));
		$obj->expects($this->any())
			->method("getAccountListByCompanyId")
			->will($this->returnValue($res1));
		$obj->expects($this->any())
			->method("getAccountListByCompanyIdCount")
			->will($this->returnValue($res4));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);


		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById"));
		$obj->expects($this->any())
			->method("getAccountInfoById")
			->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

		$obj = $this->genObjectMock("Util_CommonUrl",array("createUrl"));
		$obj->expects($this->any())
			->method("createUrl")
			->will($this->returnValue($res2));
		Gj_LayerProxy::registerProxy("Util_CommonUrl",$obj);

		$obj1 = new Service_Page_HouseReport_Report_OutletRealDataTimeAjax();
		$ret = $obj1->groupOutletData($params);
		$result = array(
			'data'	=> array(
				'count'	=> 5,
				'title'	=> '姓名',
				'title_list'	=> array(
					'123'	=> array(
						'name' => 'zsj'
					)
				)
			),
			'accountIds' => array( 0 => 123)
		);
		$this->assertEquals($ret,$result);
		$this->assertEquals($ret,$result);
	}
}
