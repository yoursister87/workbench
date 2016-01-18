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
class SearchOrgTest extends Testcase_PTest {
	protected function setUp() {
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		Gj_LayerProxy::$is_ut = true;
	}
	public function testgetCustomerListByCompanyIdLevelByCache() {
	/*	$company_id = "235";
		$arrFields = array (
				"id",
				"pid",
				"company_id",
				"customer_id",
				"level",
				"title" 
		);
		$whereConds = array (
				'company_id' => $company_id,
				'level' => 4 
		);
		$res = array (
				'data' => array (
						0 => array (
								'id' => 123,
								'company_id' => 235,
								'level' => 2,
								'customer_id' => 888 
						) 
				) 
		);
		$obj = $this->genObjectMock ( "Service_Data_Gcrm_HouseManagerAccount", array (
				"getOrgInfoListByPid" 
		) );
		$obj->expects ( $this->any () )->method ( 'getOrgInfoListByPid' )->will ( $this->returnValue ( $res ) );
		Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj );
		$orgObj = new Service_Page_HouseReport_Org_SearchOrg ();
		$ret = $orgObj->getCustomerListByCompanyIdLevelByCache ( $company_id );
		$data = array (
				0 => array (
						888 
				),
				1 => array (
						'888' => array (
								'id' => 123,
								'company_id' => 235,
								'level' => 2,
								'customer_id' => 888 
						) 
				) 
		);
		$this->assertEquals ( $data, $ret );

		 $company_id = "235";
        $arrFields = array (
                "id",
                "pid",
                "company_id",
                "customer_id",
                "level",
                "title" 
        );  
        $whereConds = array (
                'company_id' => $company_id,
                'level' => 4 
        );  
        $res = array (
			'errorno'   => 1002,
			'errormsg'  => '[数据返回失败]'
        );  
        $obj = $this->genObjectMock ( "Service_Data_Gcrm_HouseManagerAccount", array (
                "getOrgInfoListByPid" 
        ) );
        $obj->expects ( $this->any () )->method ( 'getOrgInfoListByPid' )->will ( $this->returnValue ( $res ) );
        Gj_LayerProxy::registerProxy ( 'Service_Data_Gcrm_HouseManagerAccount', $obj );
        $orgObj = new Service_Page_HouseReport_Org_SearchOrg (); 
        $ret = $orgObj->getCustomerListByCompanyIdLevelByCache ( $company_id );	
		$data  = array(
			'errorno'	=> 1002,
			'errormsg'	=> '[数据返回失败]'
		);
		 $this->assertEquals ( $data, $ret );
	 */
	}
	public function testexecute() {
		$arrInput = array (
				'search_type' => 1,
				'page' => null,
				'pageSize' => 5,
				'sTime' => '2014-10-24',
				'eTime' => '2014-10-25' 
		);
		$res = array (
				'search_type' => 1 
		)
		;
		$obj = $this->genObjectMock ( 'Service_Page_HouseReport_Org_SearchOrg', array (
				"SearchOrg" 
		) );
		$obj->expects ( $this->any () )->method ( 'SearchOrg' )->will ( $this->returnValue ( $res ) );
		Gj_LayerProxy::registerProxy ( 'Service_Page_HouseReport_Org_SearchOrg', $obj );
		// $orgObj = new Service_Page_HouseReport_Org_SearchOrg();
		$orgObj = Gj_LayerProxy::getProxy ( "Service_Page_HouseReport_Org_SearchOrg" );
		$ret = $orgObj->execute ( $arrInput );
		$data = array (
				'search_type' => 1 
		);
		$this->assertEquals ( $data, $ret );
		
		$arrInput1 = array (
				'search_type' => 's',
				'page' => null,
				'pageSize' => 5,
				'sTime' => '2014-10-24',
				'eTime' => '2014-10-25' 
		);
		$ret1 = $orgObj->execute ( $arrInput1 );
		$data1 = array (
				'search_type' => 1,
				'errorno' => '1002',
				'errormsg' => '[参数不合法]' 
		);
		$this->assertEquals ( $data1, $ret1 );
		

		$arrInput = array (
                'search_type' => 's',
                'page' => null,
                'pageSize' => 5,
                'sTime' => '0',
                'eTime' => '0' 
        );  
        $res = array (
                'search_type' => 1 
        )   
        ;   
        $obj = $this->genObjectMock ( 'Service_Page_HouseReport_Org_SearchOrg', array (
                "SearchOrg" 
        ) );
        $obj->expects ( $this->any () )->method ( 'SearchOrg' )->will ( $this->returnValue ( $res ) );
        Gj_LayerProxy::registerProxy ( 'Service_Page_HouseReport_Org_SearchOrg', $obj );
        // $orgObj = new Service_Page_HouseReport_Org_SearchOrg();
        $orgObj = Gj_LayerProxy::getProxy ( "Service_Page_HouseReport_Org_SearchOrg" );
        $ret = $orgObj->execute ( $arrInput );
        $data = array (
			'errorno'	=> '1002',
			'errormsg'	=> '[参数不合法]'
        );  
        $this->assertEquals ( $data, $ret );
	} 
	public function testSearchOrg(){
		$arrInput = array(
				'company_id' =>835,
				'search_type' =>1,
				'search_keyword' =>"富力信然庭",
				'page' =>1,
				'pageSize'=>15,
				'sTime' =>"2014-10-24",
				'eTime' =>"2014-10-24",
		);
		$whereConds = array(
				'company_id' =>$arrInput['company_id'],
				'title' =>$arrInput['search_keyword'],
				'level' =>4,
		);
		$this->data['data'] = 10;
		$arrFields = array("id","pid","company_id","customer_id","level", "title","name","account");
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getChildTreeByOrgId","getTreeByOrgId"));
		$result = array(
			'list' => array(
				array(	'id'=>30749,'pid'=>30678,'company_id'=>835,'customer_id'=>47279,'level'=>4,'title'=>'北京我爱我家房地产-上地店租赁2组','name'=>0,'account'=>'5i5jsdd2z')
				)
			);
		$this->data['data'] = $result;
		$obj->expects($this->any())
		->method('getChildTreeByOrgId')
		->will($this->returnValue($this->data));
		
		$parentNode = array(
				4=>array('activeList'=>array('id'=>30749,'pid'=>30678,'company_id'=>835,'customer_id'=>47279,'level'=>4,'title'=>'北京我爱我家房地产-上地店租赁2组','name'=>0,'account'=>'5i5jsdd2z')),
				3=>array('activeList'=>array('id'=>30678,'pid'=>30659,'company_id'=>835,'customer_id'=>0,'level'=>3,'title'=>'上地区','name'=>0,'account'=>'5i5jsd')),
				2=>array('activeList'=>array('id'=>30659,'pid'=>1,'company_id'=>835,'customer_id'=>0,'level'=>2,'title'=>'回龙观区','name'=>0,'account'=>'5i5jhlg')),
				1=>array('activeList'=>array('id'=>1,'pid'=>0,'company_id'=>835,'customer_id'=>0,'level'=>1,'title'=>'北京我爱我家','name'=>0,'account'=>'admin_5i5j@ganji.com')),
		);
		$this->data['data'] = $parentNode;
		$obj->expects($this->any())
		->method('getTreeByOrgId')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		
		//经纪人的数量
		$whereConds = array(
				'customer_id'=>$result[0]['customer_id'],
				'sTime'=>strtotime($arrInput['sTime']),
				'eTime'=>strtotime($arrInput['eTime'])+24*3600-1,
		);
		$this->data['data'] = 10;
		$objCustomer = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountCountByCustomerId"));
		$objCustomer->expects($this->any())
		->method('getAccountCountByCustomerId')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $objCustomer);
		
		$pageConfig = array(
				'total_rows' =>count($this->data['data']),
				'list_rows' => 15,
				'now_page' =>1,
				'method' =>'ajax',
				'func_name'=>'pagination',
				'parameter' =>'pagination',
		);
		$pageStr = '';
		$objPage = $this->genObjectMock("Util_HouseReport_Page", array("show"));
		$objPage->expects($this->any())
		->method('show')
		->with($pageConfig)
		->will($this->returnValue($pageStr));
		Gj_LayerProxy::registerProxy("Util_HouseReport_Page", $objPage);
		$this->data['data'] = 10;
		$objLoginCount = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getCustomerLoginCount"));
		$objLoginCount->expects($this->any())
		->method('getCustomerLoginCount')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $objLoginCount);
		
		$storesAddressData = array(
				'id'=>1,
				'district_id'=>1,
				'street_id'=>1,
				'address'=>'上地七街',
		);
		$this->data['data'] = $storesAddressData;
		$objStoresAddress = $this->genObjectMock("Service_Data_Gcrm_CompanyStoresAddress", array("getStoreInfoByUserId"));
		$objStoresAddress->expects($this->any())
		->method('getStoreInfoByUserId')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CompanyStoresAddress", $objStoresAddress);
		
		$searchObj = new Service_Page_HouseReport_Org_SearchOrg();
		$res = $searchObj->execute($arrInput);
		$result[0]['url']='?a=getAgentList&customer_id=47279';
		$result[0]['num']=10;
		$result[0]['loginNum']=10;
		$result[0]['title']=$parentNode[2]['activeList']['title']."=>".$parentNode[3]['activeList']['title']."=>".$parentNode[4]['activeList']['title'];
		$result[0]['address_id'] = $storesAddressData["id"];
		$result[0]['district_id'] = $storesAddressData["district_id"];
		$result[0]['street_id'] = $storesAddressData["street_id"];
		$result[0]['address'] = $storesAddressData["address"];
		$data = array(
			'data'	=> array(
				'data'	=> array(
					0 => array(
						'id'			=> 30749,
						'pid'			=> 30678,
						'company_id'	=> 835,
						'customer_id'	=> 47279,
						'level'			=> 4,
						'title'			=> '回龙观区=>上地区=>北京我爱我家房地产-上地店租赁2组',
						'name'			=> 0,
						'account'		=> '5i5jsdd2z',
						'url'			=>'?a=getAgentList&customer_id=47279',
						'num'			=> 10,
						'loginNum'		=> 10,
						'address_id'	=> 1,
						'district_id'	=> 1,
						'street_id'		=> 1,
						'address'		=> '上地七街'
					)
				),
				'pageStr' => ''
			),
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($data,$res);
	}
	public function testconditionSearchOne(){
		/*
		$data = array('AccountId' => 123,'CustomerId' => 12);
		$res = array();
		$obj= $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo", array("getAccountBusinessInfolist"));
		$obj->expects($this->any())
			->method('getAccountBusinessInfolist')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo", $obj);			
		$obj1 = new Service_Page_HouseReport_Org_SearchOrg();
		$ret = $obj1->conditionSearchOne($data);
		$result = array();
		$this->assertEquals($ret,$result);
		 */
	}
}
