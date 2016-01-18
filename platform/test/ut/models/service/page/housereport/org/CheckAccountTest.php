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
class CheckAccount extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->data['data'] = array('id'=>1,'status'=>1,'company_id'=>835);
	}
	public function testExecute(){
		$arrInput = array('account'=>'zhanglm');
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoByIdOrAccount"));
		$obj->expects($this->any())
		->method('getOrgInfoByIdOrAccount')
		->with($arrInput, $this->anything())
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
		$caObj = new Service_Page_HouseReport_Org_CheckAccount();
		$res = $caObj->execute($arrInput);
		$this->data['data'] = true;
		$this->assertEquals($this->data,$res);
	    	
		$this->data['data'] = array('id'=>'','status'=>1,'company_id'=>835);
		$arrInput = array('account'=> "zhanglm");
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
        ->method('getOrgInfoByIdOrAccount')
        ->with($arrInput, $this->anything())
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $caObj = new Service_Page_HouseReport_Org_CheckAccount();
        $res = $caObj->execute($arrInput);
		$this->data['data'] = false;
		$this->assertEquals($this->data,$res);
	}
}
