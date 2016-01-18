<?php
class GetAgentInfoTest extends Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
	}	
	public function testexecute(){
		$arrInput = array(
			'account_id'	=> 123,
		);
		$res = array(
			'errorno'	=> 0,
			'data'		=> array(array(
				'Picture'			=> '11111',
				'ICImage'			=> '22222',
				'BusinessCardImage'	=> '33333'
			)),
			'errormsg' => '[数据返回成功]'
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById"));
		$obj->expects($this->any())
		->method("getAccountInfoById")
		->will($this->returnValue($res));	
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        $obj = $this->genObjectMock("Service_Page_HouseReport_Org_GetAgentInfo",array("judgePrivilege"));
        $res2 = true;
        $obj->expects( $this->any())
            ->method('judgePrivilege')
            ->will($this->returnValue($res2));
		$ret = $obj->execute($arrInput);

		$data = array(
			 'errorno'   => '0',
			'errormsg' => '[数据返回成功]',
            'data'      => array(
                'Picture'           => '11111',
                'ICImage'           => '22222',
                'BusinessCardImage' => '33333'
            )	
		);
		$this->assertEquals($data,$ret);

	   $arrInput = array(
            'account_id'    => 'sss',
        );
        $obj = $this->genObjectMock("Service_Page_HouseReport_Org_GetAgentInfo",array("judgePrivilege"));
        $res2 = true;
        $obj->expects( $this->any())
            ->method('judgePrivilege')
            ->will($this->returnValue($res2));
        $ret = $obj->execute($arrInput);
        $data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
        );
        $this->assertEquals($data,$ret);
	}

    public function testjudgePrivilege(){
        $arrInput = 1122;
        $res = true;
        $res1 = array();
        $res1['data'][0]['CustomerId'] = 1;
        $res2['data']['list']= array(
            array('customer_id'=>1),
            array('customer_id'=>2)
        );
        $obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccount',array("getAccountInfoById"));
        $obj ->expects( $this->any())
            ->method('getAccountInfoById')
            ->will($this->returnValue($res1));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

        $obj = $this->genObjectMock('Service_Data_Gcrm_HouseManagerAccount',array("getChildTreeByOrgId"));
        $obj ->expects( $this->any())
            ->method('getChildTreeByOrgId')
            ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $objOrg = new Service_Page_HouseReport_Org_GetAgentInfo();
        $ret = $objOrg->judgePrivilege($arrInput);

        $this->assertEquals($res,$ret);
    }
} 
