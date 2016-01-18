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
class RootNode extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array('data'=>array('id'=>1,'pid'=>0,'company_id'=>835,'customer_id'=>0,'level'=>1,'create_time'=>'1281424727','account'=>'admin_5i5j@ganji.com','phone'=>'13856903942','title'=>'总管','name'=>'我爱我家管理员','passwd'=>'1111111'));
		$this->arrFields = array("CustomerId","FullName","CompanyId","CompanyName","PostAddress","Postcode","Email","OfficePhone","CellPhone","IM","Fax","Level","SourceType","SaleGroup");
	}
	public function testExecute(){
		$arrInput = array('id' =>'测试公司');
		$obj = new Service_Page_HouseReport_Org_RootNode();
		$res = $obj->execute($arrInput);
		$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$data['data'] = array();
		$this->assertEquals($data,$res);
		
		$id = 1;
		$arrInput = array('id' =>$id);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoByIdOrAccount","getOrgCountByPid"));
		$obj->expects($this->any())
		->method('getOrgInfoByIdOrAccount')
		->with($arrInput)
		->will($this->returnValue($this->result));
		
		$whereConds = array(
				'company_id' =>$this->result['data']['company_id'],
				'pid' =>$id,
				'level' =>$this->result['data']['level']+1,
		);
		$orgTotal["data"] = 17;
		$obj->expects($this->any())
		->method('getOrgCountByPid')
		->with($whereConds)
		->will($this->returnValue($orgTotal));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		
		$outLetTotal['data'] = array(
				array(1),
				array(2),
				array(3),
				array(4),
		);
		$objCustomer = $this->genObjectMock("Service_Data_Gcrm_Customer", array("getOutlet"));
		$objCustomer->expects($this->any())
		->method('getOutlet')
		->with($this->result['data']['company_id'])
		->will($this->returnValue($outLetTotal));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer", $objCustomer);
		
		$companyObj = new Service_Page_HouseReport_Org_RootNode();
		$res = $companyObj->execute($arrInput);
		$this->data['data'] = $this->result['data'];
		$this->data['data']['url'] = "?c=org&a=getChilds&level=".$this->result['data']['level']."&pid=".$this->result['data']['id'];
		$this->data['data']['num'] = $orgTotal["data"];
		$this->data['data']['outletNum'] = count($outLetTotal['data']);
		$this->assertEquals($this->data,$res);
	}
}
