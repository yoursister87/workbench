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
class GetChilds extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->arrFields = array("id","pid","company_id","customer_id","level", "title","name","account","phone");
	}
	 public function testExecute(){
		$companyId = "测试公司";
		$pid = 1;
		$level = 1;
		$arrInput = array(
				'company_id' =>$companyId,
				'pid' =>$pid,
				'level' =>$level,
		);
		$obj = new Service_Page_HouseReport_Org_GetChilds();
		$res = $obj->execute($arrInput);
		$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$data['data'] = array();
		$this->assertEquals($data,$res);
		//输入正确的数据，但是查询数据库，返回错误
		$companyId = "835";
		$pid = 1;
		$level = 1;
		$time = "2014-10-17";
		$arrInput = array(
				'company_id' =>$companyId,
				'pid' =>$pid,
				'level' =>$level,
				'sTime' =>$time,
				'eTime' =>$time,
		);
		$whereConds = array(
				'company_id' =>$arrInput['company_id'],
				'pid' =>$arrInput['pid'],
				'level' =>$arrInput['level'] + 1,
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoListByPid"));
		$obj->expects($this->any())
		->method('getOrgInfoListByPid')
		->with($whereConds, $this->arrFields, 1, NULL)
		->will($this->returnValue($data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		$orgObj = new Service_Page_HouseReport_Org_GetChilds();
		$res = $orgObj->execute($arrInput);
		$this->assertEquals($data,$res);
		//输入正确的数据，但是查询数据库，返回正确数据
		$result = array(
				array('id'=>31002,'pid'=>1,'company_id'=>835,'customer_id'=>0,'level'=>2,'title'=>'西南区','name'=>0,)
		);
		$this->data['data'] = $result;
		$objGcrm = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoListByPid","getOrgCountByPid"));
		$objGcrm->expects($this->any())
		->method('getOrgInfoListByPid')
		->with($whereConds, $this->arrFields, 1, NULL)
		->will($this->returnValue($this->data));
		
		$whereConds = array(
				'company_id' =>$arrInput['company_id'],
				'pid' =>$result[0]['id'],
				'level' =>$result[0]['level'] + 1,
		);
		$this->data['data'] = 5;
		$objGcrm->expects($this->any())
		->method('getOrgCountByPid')
		->with($whereConds)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $objGcrm);

		$sTime = strtotime($time);
		$eTime = strtotime($time)+24*3600-1;
		$objLoginCount = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getCustomerLoginCount"));
		$objLoginCount->expects($this->any())
		->method('getCustomerLoginCount')
		->with($result[0]['id'], strtotime($arrInput['sTime']), $eTime)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $objLoginCount);
		
		$childsObj = new Service_Page_HouseReport_Org_GetChilds();
		$res = $childsObj->execute($arrInput);
		$result[0]['url']='?a=getChilds&level=2&pid=31002';
		$result[0]['num']=5;
		$result[0]['loginNum']=5;
		$this->data['data'] = array('data'=>$result);
		$this->assertEquals($this->data,$res);
		
		//输入正确的数据，但是查询数据库，返回正确数据
		$companyId = 835;
		$pid = 30855;
		$level = 3;
		$arrInput = array(
				'company_id' =>$companyId,
				'pid' =>$pid,
				'level' =>$level,
		);
		$whereConds = array(
				'company_id' =>$arrInput['company_id'],
				'pid' =>$arrInput['pid'],
				'level' =>$arrInput['level'] + 1,
		);
		$result = array(
				array('id'=>31185,'pid'=>30855,'company_id'=>835,'customer_id'=>47829,'level'=>4,'title'=>'北京我爱我家房地产-大运村店5组','name'=>0,)
		);
		$this->data['data'] = $result;
		$objGcrm = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoListByPid"));
		$objGcrm->expects($this->any())
		->method('getOrgInfoListByPid')
		->with($whereConds, $this->arrFields, 1, NULL)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $objGcrm);
		
		$sTime = strtotime('today');
		$eTime = strtotime('tomorrow')-1;
		$whereConds = array(
				'customer_id'=>$result[0]['customer_id'],
				'sTime'=>$sTime,
				'eTime'=>$eTime,
		);
		$this->data['data'] = 5;
		$objCustomer = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountCountByCustomerId"));
		$objCustomer->expects($this->any())
		->method('getAccountCountByCustomerId')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $objCustomer);
		
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
		->with(NULL, $result[0]['customer_id'], $arrInput['id'], NULL, $this->anything())
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CompanyStoresAddress", $objStoresAddress);
		
		$this->data['data'] = 5;
		$objLoginCount = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getCustomerLoginCount"));
		$objLoginCount->expects($this->any())
		->method('getCustomerLoginCount')
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $objLoginCount);
		
		$childsObj = new Service_Page_HouseReport_Org_GetChilds();
		$res = $childsObj->execute($arrInput);
		$result[0]['url']='?a=getAgentList&level=4&pid=31185&customer_id=47829';
		$result[0]['num']=5;
		$result[0]['loginNum']=5;
		$result[0]['address_id'] = $storesAddressData["id"];
		$result[0]['district_id'] = $storesAddressData["district_id"];
		$result[0]['street_id'] = $storesAddressData["street_id"];
		$result[0]['address'] = $storesAddressData["address"];
		$this->data['data'] = array('data'=>$result);
		$this->assertEquals($this->data,$res);
	} 
}
