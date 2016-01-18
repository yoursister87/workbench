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
class GetXiaoquList extends Testcase_PTest
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
		$arrInput['xiaoqu_type'] = 1;
		$house_type = 5;
		$arrFields = array('district_id','street_id','xiaoqu_id','xiaoqu_name');
		$xiaoquObj = new Service_Page_RealHouse_GetXiaoquList();
		$res = $xiaoquObj->execute($arrInput);
		$returnData['data'] = array();
		$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$this->assertEquals($returnData,$res);
		
		$arrInput['account_id'] = 123;
		$this->data = array ( 'errorno' => '0', 'errormsg' => '[数据返回成功]', 'data' => array ( 0 => array ( 'district_id' => '1', 'street_id' => '46', 'xiaoqu_id' => '8708', 'xiaoqu_name' => '人民日报社家属区', ), 1 => array ( 'district_id' => '1', 'street_id' => '46', 'xiaoqu_id' => '100730', 'xiaoqu_name' => '七贤村', ), ), );
		$arrFields = array('district_id','street_id','xiaoqu_id','xiaoqu_name');
		$obj = $this->genObjectMock("Service_Data_Source_FangByAccount",array("getXiaoQuListByAccount"));
		$obj->expects($this->any())
		->method("getXiaoQuListByAccount")
		->with($arrInput['account_id'], $house_type, $arrFields)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_FangByAccount", $obj);
		$xiaoquObj = new Service_Page_RealHouse_GetXiaoquList();
		$res = $xiaoquObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput['xiaoqu_type'] = 2;
		$arrInput['district_id'] = 1;
		$this->data = array ( 'errorno' => '0', 'errormsg' => '[数据返回成功]', 'data' => array ( 0 => array ( 'district_id' => '1', 'street_id' => '46', 'xiaoqu_id' => '8708', 'xiaoqu_name' => '人民日报社家属区', ), 1 => array ( 'district_id' => '1', 'street_id' => '53', 'xiaoqu_id' => '100730', 'xiaoqu_name' => '七贤村', ), ), );
		$obj = $this->genObjectMock("Service_Data_Source_FangByAccount",array("getXiaoQuListByAccount"));
		$obj->expects($this->any())
		->method("getXiaoQuListByAccount")
		->with($arrInput['account_id'], $house_type, $arrFields)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_FangByAccount", $obj);
		$xiaoquObj = new Service_Page_RealHouse_GetXiaoquList();
		$res = $xiaoquObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput['xiaoqu_type'] = 3;
		$arrInput['district_id'] = 1;
		$arrInput['street_id'] = 46;
		$this->data = array ( 'errorno' => '0', 'errormsg' => '[数据返回成功]', 'data' => array ( 0 => array ( 'district_id' => '1', 'street_id' => '46', 'xiaoqu_id' => '8708', 'xiaoqu_name' => '人民日报社家属区', ), 1 => array ( 'district_id' => '1', 'street_id' => '46', 'xiaoqu_id' => '100730', 'xiaoqu_name' => '七贤村', ), ), );
		$arrFields = array('district_id','street_id','xiaoqu_id','xiaoqu_name');
		$obj = $this->genObjectMock("Service_Data_Source_FangByAccount",array("getXiaoQuListByAccount"));
		$obj->expects($this->any())
		->method("getXiaoQuListByAccount")
		->with($arrInput['account_id'], $house_type, $arrFields)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_FangByAccount", $obj);
		$xiaoquObj = new Service_Page_RealHouse_GetXiaoquList();
		$res = $xiaoquObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
	}
}