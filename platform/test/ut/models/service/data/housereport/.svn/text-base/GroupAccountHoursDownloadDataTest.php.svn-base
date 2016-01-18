<?php
class GroupAccountHoursDownloadDataTest  extends Testcase_PTest{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }	
	public function testgetAccountHoursData(){
		$params = array(
			'accountIds'	=> array(
				0	=> 123
			),
			'account_name'	=> 'zsj'
		);	
		$data[22]['data'] = array(
				123=>array('account_id'=>123)
		);
		$obj = new Service_Data_HouseReport_GroupAccountHoursDownloadData();
		$ret = $obj->getAccountHoursData(  $params,$data);
		$data = array(
			'data'	=> array(
				123	=> array(
					'account_id'	=> 123,
					'account_name'	=> null
				)
			)
		);
		$this->assertEquals($data,$ret);
	}
	public function testgetRefreshHouse(){
		$params = array(
			'date'	=> array(
				'sDate'	=> '2014-11-26',
				'eDate'	=> '2014-11-27'
			),
			'accountIds'	=> 122,
			'houseType'		=> 1,
			'countType'		=> 4,
			'user_id' =>123
		);	
		$res = true;
		$res1 = array(
			'data'	=> array(123)
        );
        $res2 = array(
            2=>array(
                array(1)
            ),
        );
		//$res2 = true;
         $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("setVal","getAccountHoursReport"));
         $obj->expects($this->any())
         ->method("setVal")
         ->will($this->returnValue($res));
		 $obj->expects($this->any())
         ->method("getAccountHoursReport")
         ->will($this->returnValue($res1));
          Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
		  $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
         $obj1->expects($this->any())
         ->method("changeData")
         ->will($this->returnValue($res2));

          Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAccountUseBusinessScope"));
        $obj->expects($this->any())
            ->method("groupAccountUseBusinessScope")
            ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletData", $obj);


          $obj2 = $this->genObjectMock("Service_Data_HouseReport_GroupAccountHoursDownloadData",array("mergeBizScopeField"));
          $obj2->expects($this->any())
              ->method("mergeBizScopeField")
              ->will($this->returnValue($res2));

          $ret =  $obj2->getRefreshHouse($params);

		$this->assertEquals($res2,$ret);
	}
	public function testgroupDownLoadData(){
		$tags = true;
		$params = array(
			'accountIds'	=> 123,
			'countType'		=> array(4)
		);	
		$res = $res1= $res2 = $res3 = $res4 = true;
         $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountHoursDownloadData",array("getRefreshHouse","getAccountHoursData","getSortCategory","getCrmAccountEmail"));
         $obj->expects($this->any())
         ->method("getRefreshHouse")
         ->will($this->returnValue($res));
         $obj->expects($this->any())
         ->method("getAccountHoursData")
         ->will($this->returnValue(array()));
		   $obj->expects($this->any())
         ->method("getSortCategory")
         ->will($this->returnValue($res2));
		 $obj->expects($this->any())
		 ->method("getCrmAccountEmail")
		 ->will($this->returnValue(array()));
          $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("getBusinessScope","mergeData"));
         $obj1->expects($this->any())
         ->method("getBusinessScope")
         ->will($this->returnValue($res3));
		 $obj1->expects($this->any())
         ->method("mergeData")
         ->will($this->returnValue($res4));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);
         $ret =  $obj->groupDownLoadData($tags,$params);	
		 $data = true;
		$this->assertEquals($data,$ret);
	}
}
