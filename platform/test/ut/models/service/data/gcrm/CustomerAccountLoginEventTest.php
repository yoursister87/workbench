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
class CustomerAccountLoginEvent extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected $sTime;
	protected $eTime;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('id'=>123,'company_id'=>45888));
		$this->sTime = strtotime('2014-09-25');
		$this->eTime= strtotime('2014-09-27');
		$this->arrFields = array("EventId","AccountId","Email","IsSuccess","LoginTime","Ip","Message");
	}
	public function testGetCustomerAccountLoginList(){
		$arrConds = array(
				'AccountId =' =>835,
				'LoginTime >' =>$this->sTime,
				'LoginTime <' =>$this->eTime,
		);
		$orderArr = array('LoginTime'=>'DESC');
		$obj = $this->genObjectMock("Dao_Gcrm_CustomerAccountLoginEvent", array("selectByPage"));
		$obj->expects($this->any())
		->method('selectByPage')
		->with($this->arrFields, $arrConds, 1, 30, $orderArr)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccountLoginEvent", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->getCustomerAccountLoginList(835, $this->sTime, $this->eTime, $this->arrFields,1, 30, $orderArr);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);
	}
	public function testGetCustomerAccountLoginCount(){
		$arrConds = array(
				'AccountId =' =>835,
				'LoginTime >' =>$this->sTime,
				'LoginTime <' =>$this->eTime,
		);
		$obj = $this->genObjectMock("Dao_Gcrm_CustomerAccountLoginEvent", array("selectByCount"));
		$obj->expects($this->any())
		->method('selectByCount')
		->with($arrConds)
		->will($this->returnValue(1));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccountLoginEvent", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->getCustomerAccountLoginCount(835, $this->sTime, $this->eTime);
		$this->data['data'] = 1;
		$this->assertEquals($this->data,$res);
	}
	public function testGetCustomerLoginList(){
		$arrConds = array(
				'account_id =' =>835,
				'loging_time >' =>$this->sTime,
				'loging_time <' =>$this->eTime,
		);
		$orderArr = array('loging_time'=>'DESC');
		$obj = $this->genObjectMock("Dao_Housereport_CustomerLogin", array("selectByPage"));
		$obj->expects($this->any())
		->method('selectByPage')
		->with($this->arrFields, $arrConds, 1, 30, $orderArr)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->getCustomerLoginList(835, $this->sTime, $this->eTime, $this->arrFields);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);
	}
	public function testGetCustomerLoginCount(){
		$arrConds = array(
				'account_id =' =>835,
				'loging_time >' =>$this->sTime,
				'loging_time <' =>$this->eTime,
		);
		$obj = $this->genObjectMock("Dao_Housereport_CustomerLogin", array("selectByCount"));
		$obj->expects($this->any())
		->method('selectByCount')
		->with($arrConds)
		->will($this->returnValue(1));
		Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->getCustomerLoginCount(835, $this->sTime, $this->eTime);
		$this->data['data'] = 1;
		$this->assertEquals($this->data,$res);
	}
	public function testAddCustomerLoginLog(){
		//不传任何数据
		$arrConds = '';
		$obj = $this->genObjectMock("Dao_Housereport_CustomerLogin", array("insert"));
		$obj->expects($this->any())
		->method('insert')
		->with($arrConds)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->addCustomerLoginLog($arrConds);
		$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$data['data'] = array();
		$this->assertEquals($data,$res);
		 
		$arrConds = array('account ='=>'bjzlm@ganji.com');
		$obj = $this->genObjectMock("Dao_Housereport_CustomerLogin", array("insert"));
		$obj->expects($this->any())
		->method('insert')
		->with($arrConds)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
		$Cusobj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$res = $Cusobj->addCustomerLoginLog($arrConds);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);
	}

	public function testgetLastedCustomerLoginLog(){
/*		$account_id = '123';
		$arrFields = array(
			'loging_time','location'
		);
		$res = array(
			0 => array(
			'loging_time' => '2014-11-14',
			'location'	  => '北京'
		)
		);
		$obj = $this->genObjectMock("Dao_Housereport_CustomerLogin",array("selectLastLog"));
		$obj->expects($this->any())
			->method("selectLastLog")
			->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
		 $objOrg = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		 $ret = $objOrg->getLastedCustomerLoginLog($account_id,$arrFields);
		 $data = array(
			'data'	=> array(
				   'loging_time' => '2014-11-14',
            	   'location'    => '北京'	
			),
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]' 
		 );
		 $this->assertEquals($data,$ret);
*/
	 	$account_id = '123';
        $arrFields = array(
            'loging_time','location'
        );
        $res = array(
			'errorno'	=> 1003
        );
		$res1 = true;
        $obj = $this->genObjectMock("Dao_Housereport_CustomerLogin",array("selectLastLog","getLastSQL"));
        $obj->expects($this->any())
            ->method("selectLastLog")
            ->will($this->returnValue($res));
		$obj->expects($this->any())
			 ->method("getLastSQL")
			 ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Dao_Housereport_CustomerLogin", $obj);
         $objOrg = new Service_Data_Gcrm_CustomerAccountLoginEvent();
         $ret = $objOrg->getLastedCustomerLoginLog($account_id,$arrFields);
         $data = array(
			'data'	=> array(),
            'errorno'   => 1003,
            'errormsg'  => '[SQL语句执行失败]'
         );
         $this->assertEquals($data,$ret);	
	}
	public function testgetLoginCountByAccountId(){
/*		$arrInput = array();
		$whereConds =  array();
		$obj = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$ret = $obj->getLoginCountByAccountId($arrInput,$whereConds);
		$result = array(
			'data' => array(),
			'errorno' => 0,
			'errormsg' =>  "[数据返回成功]"
		);
		$this->assertEquals($result,$ret);
*/
		$arrInput = array( 0 => 123);
		$whereConds =  array();
		$res = array('data' => 1);
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccountLoginEvent",array("selectGroupbyAccountId"));
        $obj->expects($this->any())
            ->method("selectGroupbyAccountId")
            ->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccountLoginEvent", $obj);
		$obj1 = new Service_Data_Gcrm_CustomerAccountLoginEvent();
		$ret = $obj1->getLoginCountByAccountId($arrInput,$whereConds);
		$result = array(
			'data' => 1,
		);
		$this->assertEquals($result,$ret);
	}
}
