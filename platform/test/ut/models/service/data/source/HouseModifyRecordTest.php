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
class HouseModifyRecord extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('id'=>123,'company_id'=>45888));
		$this->arrFields = array("id","puid","fieldname","oldvalue","newvalue","user_id","ip","post_at");
	}
    public function testGetPriceRangeByPuid() {
         $obj = $this->genObjectMock("Service_Data_Source_HouseModifyRecord", array("getModifyRecordListByWhere"));
		 $obj->expects($this->any())
	       	->method('getModifyRecordListByWhere')
		        ->will($this->returnValue(array('data'=>array(array("MAX"=>100, "MAX_M"=>200, "MIN"=>100, "MIN_M"=>20)))));
         $res= $obj->getPriceRangeByPuid('www');
         $this->assertEquals(array("MAX"=>0, "MIN"=>0), $res);
         $res= $obj->getPriceRangeByPuid(1);
         $this->assertEquals(array("MAX"=>200, "MIN"=>20), $res);
    }
	public function testGetModifyRecordListByWhere(){
		$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseModifyRecord", array("getWhere"));
		$obj->expects($this->any())
		->method('getWhere')
		->with(array())
		->will($this->returnValue(array()));
		$res = $obj->getModifyRecordListByWhere(array());
		$this->assertEquals($data,$res);
		
		$whereConds['puid'] = 123456;
		$page=1;
		$pageSize=30;
		$orderArr=array();
		$arrConds['puid ='] = $whereConds['puid'];
		$obj = $this->genObjectMock("Service_Data_Source_HouseModifyRecord", array("getWhere"),array(),'',true);
		$obj->expects($this->any())
		->method('getWhere')
		->with($whereConds)
		->will($this->returnValue($arrConds));
		
		$daoObj = $this->genObjectMock("Dao_Housepremier_HouseModifyRecord", array("selectByPage"));
		$daoObj->expects($this->any())
		->method('selectByPage')
		->with($this->arrFields, $arrConds, $page, $pageSize, $orderArr)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseModifyRecord", $daoObj);
		$res = $obj->getModifyRecordListByWhere($whereConds);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);
		
		$whereConds['puid'] = 123456;
		$page=1;
		$pageSize=30;
		$orderArr=array();
		$arrConds['puid ='] = $whereConds['puid'];
		$obj = $this->genObjectMock("Service_Data_Source_HouseModifyRecord", array("getWhere"),array(),'',true);
		$obj->expects($this->any())
		->method('getWhere')
		->with($whereConds)
		->will($this->returnValue($arrConds));
		
		$daoObj = $this->genObjectMock("Dao_Housepremier_HouseModifyRecord", array("selectByPage","getLastSQL"));
		$daoObj->expects($this->any())
		->method('selectByPage')
		->with($this->arrFields, $arrConds, $page, $pageSize, $orderArr)
		->will($this->returnValue(false));
		
		$daoObj->expects($this->any())
		->method('getLastSQL')
		->will($this->returnValue(false));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseModifyRecord", $daoObj);
		$res = $obj->getModifyRecordListByWhere($whereConds);
		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		$this->data['data'] = array();
		$this->assertEquals($this->data,$res);
	}
	public function testGetWhere(){
		$obj = new Service_Data_Source_HouseModifyRecord;
		$whereConds = array(
				'puid'=>123456,
				'fieldname'=>'price',
				'user_id'=>123456,
		);
		$arrConds = array(
				'puid ='=>123456,
				'fieldname ='=>'price',
				'user_id ='=>123456,
		);
		$res = $obj->getWhere($whereConds);
		$this->assertEquals($arrConds,$res);
	}


	public function testInsertPriceModifyInfo(){
		$arrFields = array("puid" => 111111111, "fieldname" => 'price', "oldvalue" => 3000000, "newvalue" => 5000000, "user_id" => 22222222, "ip" => 123, "post_at" => 1400000000);
		$obj = $this->genObjectMock('Dao_Housepremier_HouseModifyRecord', array('insert'));
		$obj->expects($this->any())
			->method('insert')
			->with($arrFields)
			->will($this->returnValue(true));
		Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseModifyRecord', $obj);
		$obj1 = new Service_Data_Source_HouseModifyRecord();
		$res = $obj1->insertPriceModifyInfo($arrFields);
		$this->data['data'] = array();
		$this->assertEquals($this->data,$res);

		$arrFields = array("puid" => 111111111, "fieldname" => 'price', "oldvalue" => 3000000, "newvalue" => 5000000, "user_id" => 22222222, "ip" => 123, "post_at" => 1400000000);
		$obj = $this->genObjectMock('Dao_Housepremier_HouseModifyRecord', array('insert'));
		$obj->expects($this->any())
			->method('insert')
			->with($arrFields)
			->will($this->returnValue(false));
		Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseModifyRecord', $obj);
		$obj1 = new Service_Data_Source_HouseModifyRecord();
		$res = $obj1->insertPriceModifyInfo($arrFields);
		$this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
		$this->data['errormsg'] = 'insert failed';
		$this->assertEquals($this->data,$res);
	}
}
