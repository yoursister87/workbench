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
class DelOrg extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->data['data'] = array();
	}
	public function testExecute(){
		$arrInput = array('id'=>'1,2,3');
		$idArr = explode(',', $arrInput['id']);
		$this->data['data'] = 1;
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("deleteOrgById"));
		$obj->expects($this->any())
		->method('deleteOrgById')
		->with($idArr)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		$orgObj = new Service_Page_HouseReport_Org_DelOrg();
		$res = $orgObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
	}
}