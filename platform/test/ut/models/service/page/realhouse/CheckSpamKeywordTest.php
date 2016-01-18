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
class CheckSpamKeyword extends Testcase_PTest
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
		$arrInput['keyword'] = "测试防垃圾";
		$this->data['data'] = true;
		$obj = $this->genObjectMock("Service_Data_Gcc_CallOuttaskExport",array("checkSpamKeyword"));
		$obj->expects($this->any())
		->method("checkSpamKeyword")
		->with($arrInput['keyword'])
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcc_CallOuttaskExport", $obj);
		$spam = new Service_Page_RealHouse_CheckSpamKeyword();
		$res = $spam->execute($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput['keyword'] = array('sdfsadfas');
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->data['data'] = array();
		$spamObj = new Service_Page_RealHouse_CheckSpamKeyword();
		$res = $spamObj->execute($arrInput);
		$this->assertEquals($this->data,$res);
	}
}