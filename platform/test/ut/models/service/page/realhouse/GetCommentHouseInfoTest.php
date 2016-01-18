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
class GetCommentHouseInfo extends Testcase_PTest
{
	protected function setUp()
	{
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
	}
	public function testExecute(){
		$arrInput = array(
				'user_id'=>123456,
				'puid'=>654321,
		);
		$returnData = array ( 'data' => array ( 0 => array ( 'comment_id' => '167', 'house_id' => '69171975', 'house_type' => '5', 'puid' => '98057144', 'title' => 'dsfasdfasdfasdf', 'content' => 'dfasdffffffffffffffffffffffffffffffffffffffffffffff', 'post_at' => '1422265296', 'modified_at' => '1422265518', 'ip' => '3232249249', 'user_id' => '500008730', 'user_name' => 'bjfang13', 'user_phone' => '15133446678', 'owner_user_id' => '1000152808', 'stat' => '1', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentInfoByWhere"));
		$obj->expects($this->any())
		->method("getCommentInfoByWhere")
		->with($arrInput)
		->will($this->returnValue($returnData));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		$commObj = new Service_Page_RealHouse_GetCommentHouseInfo();
		$res = $commObj->execute($arrInput);
		$this->assertEquals($returnData,$res);
	}
}