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
class OutLet extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('CustomerId'=>123,'CompanyId'=>835,'FullName'=>'北京我爱我家房地产－新龙腾苑店'));
	}
	public function testExecute(){
		$obj = new Service_Page_HouseReport_Org_OutLet();
		$arrInput = array(
				'company_id' =>835,
		) ;
		$this->data['data'] = $this->result;
		$obj = $this->genObjectMock("Service_Data_Gcrm_Customer", array("getOutlet"));
		$obj->expects($this->any())
		->method('getOutlet')
		->with($arrInput['company_id'])
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer", $obj);
		
		$pageConfig = array(
				'total_rows' =>count($this->result),
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
		
		$data = array('data'=>5);
		$whereConds = array('customer_id'=>$this->result[0]['CustomerId']);
		$objNum = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountCountByCustomerId"));
		$objNum->expects($this->any())
		->method('getAccountCountByCustomerId')
		->with($whereConds)
		->will($this->returnValue($data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $objNum);
		
		$this->result[0]['num'] = $data['data'];
		$outLetObj = new Service_Page_HouseReport_Org_OutLet();
		$res = $outLetObj->execute($arrInput);
		$this->result[0]['customer_id'] = $this->result[0]['CustomerId'];
		unset($this->result[0]['CustomerId']);
		$this->data['data'] = array(
				'data'=>$this->result,
				'type'=>'outLet',
				'pageStr' =>$pageStr,
				'totalNum'=>count($this->result),
		);
		$this->assertEquals($this->data,$res);
	}
}