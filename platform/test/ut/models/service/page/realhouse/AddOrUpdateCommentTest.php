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
class AddOrUpdateComment extends Testcase_PTest
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
		$arrInput['house_id'] = 123456;
		$arrInput['house_type'] = 5;
		$arrInput['puid'] = 123456;
		$arrInput['title'] = 123456;
		$this->data['data'] = 0;
		$obj = $this->genObjectMock("Service_Page_RealHouse_AddOrUpdateComment",array("getCommentCountByWhere","getCommentInfoByPuid","getHouseInfoByHouseId","updateHouseInfoByPuid","insertHouseComment"));
		$obj->expects($this->any())
		->method("getCommentCountByWhere")
		->with($arrInput['user_id'])
		->will($this->returnValue($this->data));
		$this->data['data'] = array();
		$obj->expects($this->any())
		->method("getCommentInfoByPuid")
		->with($arrInput)
		->will($this->returnValue($this->data));
		$this->data['data'] = array('puid'=>123456,'title'=>'','premier_status'=>111);
		$obj->expects($this->any())
		->method("getHouseInfoByHouseId")
		->with($arrInput['house_id'],$arrInput['house_type'])
		->will($this->returnValue($this->data));
		$arrChangeRow = array(
				'title'=>$arrInput['title'],
				'premier_status'=>112
		);
		$this->data['data'] = true;
		$obj->expects($this->any())
		->method("updateHouseInfoByPuid")
		->with($arrInput['puid'], $arrChangeRow)
		->will($this->returnValue($this->data));
		
		$obj->expects($this->any())
		->method("insertHouseComment")
		->with($arrInput)
		->will($this->returnValue($this->data));
		$res = $obj->execute($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput['comment_id']=123456;
		$this->data['data'] = true;
		$obj = $this->genObjectMock("Service_Page_RealHouse_AddOrUpdateComment",array("updateHouseCommentByCommentId"));
		$obj->expects($this->any())
		->method("updateHouseCommentByCommentId")
		->with($arrInput)
		->will($this->returnValue($this->data));
		$res = $obj->execute($arrInput);
		$this->assertEquals($this->data,$res);
	}
	public function testGetCommentCountByWhere(){
		$user_id=123456;
		$puid=123456;
		$whereConds = array(
				'user_id'=>$user_id,
				'puid'=>$puid,
		);
		$this->data['data'] = 5;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentCountByWhere"));
		$obj->expects($this->any())
		->method("getCommentCountByWhere")
		->with($whereConds)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->getCommentCountByWhere($user_id, $puid);
		$this->assertEquals($this->data,$res);
	}
	public function testGetCommentInfoByPuid(){
		$user_id=123456;
		$puid=123456;
		$arrInput = array(
				'user_id'=>$user_id,
				'puid'=>$puid,
		);
		$this->data['data'] = array(array('id'=>123,'company_id'=>45888));
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentInfoByWhere"));
		$obj->expects($this->any())
		->method("getCommentInfoByWhere")
		->with($arrInput)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->getCommentInfoByPuid($arrInput);
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseInfoByHouseId(){
		$house_id=123456;
		$house_type=5;
		$arrFields = array('puid','title','premier_status','phone');
		$this->data['data'] = array(array('puid'=>123,'title'=>45888,'premier_status'=>111,'phone'=>45888));
		$obj = $this->genObjectMock("Service_Data_Source_PremierQuery",array("getRowByHouseId"));
		$obj->expects($this->any())
		->method("getRowByHouseId")
		->with($house_id,$house_type, $arrFields)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierQuery", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->getHouseInfoByHouseId($house_id,$house_type);
		$this->assertEquals($this->data,$res);
	}
	public function testUpdateHouseInfoByPuid(){
		$puid = 123456;
		$arrChangeRow = array(
				'title'=>123456,
		);
		$this->data['data'] = 1;
		$obj = $this->genObjectMock("Service_Data_Source_FangSubmit",array("updateHouseSourceByPuid","updateHouseSourceListByPuid"));
		$obj->expects($this->any())
		->method("updateHouseSourceByPuid")
		->with($arrChangeRow, $puid, 'house_source_sell_premier')
		->will($this->returnValue($this->data));
		
		$obj->expects($this->any())
		->method("updateHouseSourceListByPuid")
		->with($arrChangeRow, $puid)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_FangSubmit", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->updateHouseInfoByPuid($puid, $arrChangeRow);
		$this->assertEquals($this->data,$res);
	}
	public function testInsertHouseComment(){
		$arrInput = array(
				'puid'  =>  123456,
				'title'  =>  123456,
				'content'  =>  123456,
				'house_id' => 123456,
				'house_type' => 123456,
				'ip'  => 123456,
				'stat' => 1,
				'user_id'  =>  123456,
				'user_name'  =>  123456,
				'user_phone'  =>  123456,
				'owner_user_id'  =>  123456,
		);
		$this->data['data'] = 1;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("insertHouseComment"));
		$obj->expects($this->any())
		->method("insertHouseComment")
		->with($this->isType('array'))
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->insertHouseComment($arrInput);
		$this->assertEquals($this->data,$res);
	}
	public function testUpdateHouseCommentByCommentId(){
		$arrInput = array(
				'puid'  =>  123456,
				'title'  =>  123456,
				'content'  =>  123456,
				'content_id'  =>  123456,
		);
		$this->data['data'] = true;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("updateHouseCommentByCommentId"));
		$obj->expects($this->any())
		->method("updateHouseCommentByCommentId")
		->with($arrInput['comment_id'],$this->isType('array'))
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->updateHouseCommentByCommentId($arrInput);
		$this->assertEquals($this->data,$res);
		
		$arrInput = array(
				'puid'  =>  123456,
				'title'  =>  123456,
				'content'  =>  123456,
				'content_id'  =>  123456,
				'stat' => 1,
				'house_id' => 123456,
				'house_type' => 123456,
		);
		$this->data['data'] = array(array('puid'=>123,'title'=>45888,'premier_status'=>112,'phone'=>''));
		$obj = $this->genObjectMock("Service_Page_RealHouse_AddOrUpdateComment",array("getHouseInfoByHouseId","getCommentCountByWhere","updateHouseInfoByPuid","addSourceOperation"));
		$obj->expects($this->any())
		->method("getHouseInfoByHouseId")
		->with($arrInput['house_id'],$arrInput['house_type'])
		->will($this->returnValue($this->data));
		
		$this->data['data'] = 1;
		$obj->expects($this->any())
		->method("getCommentCountByWhere")
		->with(NULL, $arrInput['puid'])
		->will($this->returnValue($this->data));
		
		$arrChangeRow = array(
				'premier_status'=>111
		);
		$obj->expects($this->any())
		->method("updateHouseInfoByPuid")
		->with($arrInput['puid'], $arrChangeRow)
		->will($this->returnValue($this->data));
		
		$obj->expects($this->any())
		->method("addSourceOperation")
		->with($arrInput['puid'], $arrChangeRow)
		->will($this->returnValue($this->data));
		
		$arrRows = array(
				'stat'=>$arrInput['stat'],
				'modified_at'  =>  time(),
		);
		$this->data['data'] = true;
		$objComm = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("updateHouseCommentByCommentId"));
		$objComm->expects($this->any())
		->method("updateHouseCommentByCommentId")
		->with($arrInput['comment_id'],$this->isType('array'))
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $objComm);
		
		$res = $obj->updateHouseCommentByCommentId($arrInput);
		$this->assertEquals($this->data,$res);
	}
	public function getAddSourceOperation(){
		$arrInput['house_id'] = 123456;
		$arrInput['house_type'] = 5;
		$arrInput['account_id']=123456;
		$arrInput['strOp']='user-cancel-real';
		$arrInput['city_id'] = 12;
		$this->data['data'] = true;
		$obj = $this->genObjectMock("Service_Data_Source_PremierSourceOperation",array("addSourceOperation"));
		$obj->expects($this->any())
		->method("addSourceOperation")
		->with($arrInput['house_id'],$arrInput['house_type'],$arrInput['account_id'],$arrInput['strOp'],'',$arrInput['city_id'])
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierSourceOperation", $obj);
		
		$commObj = new Service_Page_RealHouse_AddOrUpdateComment();
		$res = $commObj->addSourceOperation($arrInput);
		$this->assertEquals($this->data,$res);
	}
}