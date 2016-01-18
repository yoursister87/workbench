<?php
/*
 * File Name:AccountReportTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */

class AccountReportTest extends Testcase_PTest{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Data_HouseReport_AccountReport();
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;

        $this->result = array('list'=>array(array('account_id'=>1,'account_pv'=>'100','refresh_count'=>100)));
    }


    public function testGetAccountPremierReportDetail(){
        $ret = $this->obj->getAccountPremierReportDetail('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
	    
        $accountId = 1234;
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountGeneralstatReport", array("getAccountPremierReportDetail")); 
        $obj->expects($this->any())
            ->method('getAccountPremierReportDetail')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountGeneralstatReport", $obj);
        $orgObj = new Service_Data_HouseReport_AccountReport();
        $res = $orgObj->getAccountPremierReportDetail($accountId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);

    }

    public function testGetAccountAssureReportDetail(){
        $ret = $this->obj->getAccountAssureReportDetail('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
	    
        $accountId = 1234;
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountReportV2", array("getAccountAssureReportDetail")); 
        $obj->expects($this->any())
            ->method('getAccountAssureReportDetail')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountReportV2", $obj);
        $orgObj = new Service_Data_HouseReport_AccountReport();
        $res = $orgObj->getAccountAssureReportDetail($accountId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);
    }

    public function testGetAccountPremierReportList(){
        $ret = $this->obj->getAccountPremierReportList('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
	    
        $accountId = array(1234);
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountGeneralstatReport", array("getAccountPremierReportList",'selectByPage')); 
        $obj->expects($this->any())
            ->method('getAccountPremierReportList')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result['list']));

        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountGeneralstatReport", $obj);
        $orgObj = new Service_Data_HouseReport_AccountReport();
        $res = $orgObj->getAccountPremierReportList($accountId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);

    }


    public function testGetAccountAssureReportList(){
        $ret = $this->obj->getAccountAssureReportList('abc');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE,$ret['errorno']); 
	    
        $accountId = array(1234);
        $obj = $this->genObjectMock("Dao_Housereport_HouseAccountReportV2", array("getAccountAssureReportList",'selectByPage')); 
        $obj->expects($this->any())
            ->method('getAccountAssureReportList')
            //->with($accountId)
            ->will($this->returnValue($this->result));
        $obj->expects($this->any())
            ->method('selectByPage')
            //->with($accountId)
            ->will($this->returnValue($this->result['list']));


        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseAccountReportV2", $obj);
        $orgObj = new Service_Data_HouseReport_AccountReport();
        $res = $orgObj->getAccountAssureReportList($accountId);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data['data'],$res['data']);
    }


    //{{{getAgentReportDetail
    public function testGetAgentReportDetail(){
        //$ret = $this->obj->getAgentReportDetail('', '2015-04-03', '2015-04-03');
        //$this->assertEquals(array(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_CODE), array($ret[0]['errorno'], $ret[1]['errorno'])); 
    }//}}}
	public function testgetLoginList(){
		$accountIds = 123;
		$params = array();
		$res = array();
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getLoginCountByAccountId")); 
        $obj->expects($this->any())
			->method('getLoginCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getLoginList($accountIds,$params);
		$result = array(
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);

		$accountIds = array(123);
		$params = array();
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getLoginCountByAccountId")); 
		$obj->expects($this->any())
			->method('getLoginCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getLoginList($accountIds,$params);
		$result = array(
			'data' => array('list' => array()),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);  
		$this->assertEquals($ret,$result);

	}
	public function testgetLoginListException(){
		$accountIds = array(123);
		$params = array();
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent", array("getLoginCountByAccountId")); 
		$obj->expects($this->any())
			->method('getLoginCountByAccountId')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getLoginList($accountIds,$params);
		$result = array(
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);  
		$this->assertEquals($ret,$result);	
	}
	public function testgetSourceOperationRealDataTime(){
		$accountIds = 123;
		$type = 1;
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getSourceOperationRealDataTime($accountIds,$type);
		$result = array(
			'data'	=> array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);	
		$this->assertEquals($ret,$result);

		$accountIds = array(123);
		$type = 1;
		$res = 5;
		$res1 = 6;
		$obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport", array("getUserRefresh","getUserAddHouse")); 
		$obj->expects($this->any())
			->method('getUserRefresh')
			->will($this->returnValue($res));
		$obj->expects($this->any())
			->method('getUserAddHouse')
			->will($this->returnValue($res1));
		$ret = $obj->getSourceOperationRealDataTime($accountIds,$type);
		$result = array(
			'list' => array(
				'user-add-refresh' => 5,
				'user-add' => 6
			)
		);  
		$this->assertEquals($ret,$result);	
 
/*		$accountIds = array(123);
		$type = 1;
		$res = 5;
		$res1 = 6;
		$obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport", array("getUserRefresh","getUserAddHouse")); 
		$obj->expects($this->any())
			->method('getUserRefresh')
			->will($this->returnValue($res));
		$obj->expects($this->any())
			->method('getUserAddHouse')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
		$ret = $obj->getSourceOperationRealDataTime($accountIds,$type);
		$result = array(
			'errorno' => 1002,
			'errormsg' =>'[参数不合法]'
		);  
		$this->assertEquals($ret,$result);  	
*/
	}
	public function testgetAccountPremierReportListPremierRealDataTime(){
		$accountIds = 123;
		$type = 1;
		$params =  array();
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getSourceOperationRealDataTime($accountIds,$type);
		$result = array(
			'data'  => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);		

		$accountIds = array(123);
		$type = 1;
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListPremierRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array('list' => array()),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);

		$accountIds = array(123);
		$type = 2;
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListPremierRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array('list' => array()),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);	

/*		$accountIds = array(123);
		$type = 2;
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListPremierRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
*/
		$this->assertEquals($ret,$result);	

	}
	public function testgetAccountPremierReportListRealDataTime(){
		$accountIds = 123;
		$type = 1;
		$params =  array();
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getAccountPremierReportListRealDataTime($accountIds,$params,$type);
		$result = array(
			'data'  => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);		

		$accountIds = array(123);
		$type = 1;
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array('list' => array()),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);

		$accountIds = array(123);
		$type = 2;
		$res = array();
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array('list' => array()),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);	

/*		$accountIds = array(123);
		$type = 2;
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getAccountPremierReportListPremierRealDataTime($accountIds,$params,$type);
		$result = array(
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);	
*/
	}
	public function testgetUserAddHouse(){
		$accountIds = 123;
		$type = 1;
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getUserAddHouse($accountIds,$type);
		$result = array(
			'data'	=> array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		 $this->assertEquals($ret,$result);

		$accountIds = array(0 => 123);
		$type = 1;
		$res = array('data' => 123);
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId1"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId1')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getUserAddHouse($accountIds,$type);
		$result = array('data' => 123);
		$this->assertEquals($ret,$result);

		$accountIds = array(0 => 123);
		$type = 2;
		$res = array('data' => 123);
		$obj = $this->genObjectMock("Service_Data_Sourcelist_PremierList", array("getPostCountByAccountId1"));
		$obj->expects($this->any())
			->method('getPostCountByAccountId1')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Sourcelist_PremierList", $obj);
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getUserAddHouse($accountIds,$type);
		$result = array('data' => 123);
		$this->assertEquals($ret,$result);
	}
	public function testgetUserRefresh(){
		$accountIds = 123;
		$type = 1;
		$obj = new Service_Data_HouseReport_AccountReport();
		$ret = $obj->getUserAddHouse($accountIds,$type);
		$result = array(
			'data'  => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);		

		$accountIds = array( 0 => 123);
		$type = 1;

		$res = array(0 => array('CreatorId' => 123,'count' => 55));
		$obj = $this->genObjectMock("Service_Data_Source_PremierSourceOperation", array("getOPCountByAccountId"));
		$obj->expects($this->any())
			->method('getOPCountByAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierSourceOperation", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getUserRefresh($accountIds,$type);
		$result = array(
			0	=> array(
				'CreatorId' => 123,
				'count'		=> 0
			)
		);
		$this->assertEquals($ret,$result);


		$accountIds = array(0 => 123);
		$type = 1;

/*		$res = array(0 => array('CreatorId' => 123,'count' => 55));
		$obj = $this->genObjectMock("Service_Data_Source_PremierSourceOperation", array("getOPCountByAccountId"));
		$obj->expects($this->any())
			->method('getOPCountByAccountId')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierSourceOperation", $obj);
		$obj1 = new Service_Data_HouseReport_AccountReport();
		$ret = $obj1->getUserRefresh($accountIds,$type);
		$result = array(
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);	
*/
	}
	public function testmergeRealDataTime(){
		$accountIds = 123;
		$params = array(); 	
		$type = 1;
		$res = array('data' => array('list' => array(0	=>array( 'house_count' => 12,'account_id' => 123))));
		$res1 = array('data' => array('list' => array(0 =>array('account_id' => 123,'house_count' => 13))));
		$res2 = array('list' => array('user-add' =>array(0 =>array( 'account_id' => 123,'count' => 12)),'user-add-refresh' =>array(0 =>array( 'account_id' => 123,'count' => 12))));
		$res3 = array('data' => array('list' => array('AccountId' => 123,'count' => 13)));
		$obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport", array("getAccountPremierReportListRealDataTime","getAccountPremierReportListPremierRealDataTime","getSourceOperationRealDataTime","getLoginList"));
		$obj->expects($this->any())
			->method('getAccountPremierReportListRealDataTime')
			->will($this->returnValue($res));

		
		$obj->expects($this->any())
			->method('getAccountPremierReportListPremierRealDataTime')
			->will($this->returnValue($res1));

		
		$obj->expects($this->any())
			->method('getSourceOperationRealDataTime')
			->will($this->returnValue($res2));
		
		
		$obj->expects($this->any())
			->method('getLoginList')
			->will($this->returnValue($res3));
		$ret = $obj->mergeRealDataTime( $accountIds,$params,$type);
		$result = array(
			'data' => array('list' => array( 0 => array('house_count' => 12,'account_id' => 123,'login_count' => 0,'house_total_count' => 12,'premier_count' => 13,'refresh_count' => 0)))
		);
		$this->assertEquals($ret,$result);
	}
}
