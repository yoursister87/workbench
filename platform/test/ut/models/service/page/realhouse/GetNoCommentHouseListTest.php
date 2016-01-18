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
class GetNoCommentHouseList extends Testcase_PTest
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
		$arrInput['user_id'] = 123456;
		$arrInput['owner_account_id'] = 123456;
		$this->data = array ( 'data' => array ( 'list' => array ( 0 => array ( 'house_id' => '69171975', 'puid' => '98057144', 'user_code' => '1231', 'history_count' => '0', 'yesterday_count' => '0', 'title' => 'dsfasdfasdfasdf', 'type' => '5', 'account_id' => '1002790', 'user_id' => '0', 'xiaoqu' => '人民日报社家属区', 'xiaoqu_id' => '8708', 'district_id' => '1', 'district_name' => '朝阳', 'street_id' => '46', 'street_name' => '朝外', 'image_count' => '4', 'price' => '2050000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => NULL, 'huxing_shi' => '2', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '5', 'area' => '111.00', ), 1 => array ( 'house_id' => '69171977', 'puid' => '98060452', 'user_code' => 'fdsf', 'history_count' => '0', 'yesterday_count' => '0', 'title' => '', 'type' => '5', 'account_id' => '1002790', 'user_id' => '0', 'xiaoqu' => '人民日报社家属区', 'xiaoqu_id' => '8708', 'district_id' => '1', 'district_name' => '朝阳', 'street_id' => '46', 'street_name' => '朝外', 'image_count' => '4', 'price' => '3000000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => NULL, 'huxing_shi' => '3', 'huxing_ting' => '2', 'huxing_wei' => '1', 'chaoxiang' => '5', 'area' => '222.00', ), ), 'count' => 2, ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery",array("getNoCommentHouseList"));
		$obj->expects($this->any())
		->method("getNoCommentHouseList")
		->with($arrInput)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealQuery", $obj);
		$spam = new Service_Page_RealHouse_GetNoCommentHouseList();
		$res = $spam->execute($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput['owner_account_id'] = array('sdfsadfas');
		$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$spamObj = new Service_Page_RealHouse_GetNoCommentHouseList();
		$res = $spamObj->execute($arrInput);
		$this->assertEquals($returnData,$res);
	}
}