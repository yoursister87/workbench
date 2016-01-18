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
class GetPrivilegeInfo extends Testcase_PTest
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
		$arrInput['puid'] = "98109681";
		$arrInput['customer_id'] = "55680";
		$this->data = array ( 'data' => array ( 0 => array ( 'id' => '47', 'puid' => '98109681', 'district_id' => '30216', 'street_id' => '30229', 'customer_id' => '55680', 'agent_Id' => '0', 'user_id' => '1000139024', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj = $this->genObjectMock("Service_Data_Source_HouseCommentPrivilege",array("getHouseCommentPrivilegeInfo"));
		$obj->expects($this->any())
		->method("getHouseCommentPrivilegeInfo")
		->with($arrInput['puid'])
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseCommentPrivilege", $obj);
		$spam = new Service_Page_RealHouse_GetPrivilegeInfo();
		$res = $spam->execute($arrInput);
		$this->data['data'] = true;
		$this->assertEquals($this->data,$res);
		
		$arrInput['puid'] = array('sdfsadfas');
		unset($arrInput['customer_id']);
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->data['data'] = array();
		$spamObj = new Service_Page_RealHouse_GetPrivilegeInfo();
		$res = $spamObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
	}
}