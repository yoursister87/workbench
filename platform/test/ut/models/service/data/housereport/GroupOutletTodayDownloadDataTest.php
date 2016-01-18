<?php
class GroupOutletTodayDownloadDataTest  extends Testcase_PTest{
   protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
   }  	
   public function testgetDateArr(){
		$params = array(
			'date'	=> array(
				'sDate'	=> '2014-11-24',
				'eDate'	=> '2014-11-25'
			)
		);	
		$obj = new Service_Data_HouseReport_GroupOutletTodayDownloadData();
		$ret = $obj->getDateArr( $params);
		$data = array(
			0	=>'2014-11-24',
			1	=> "2014-11-25"
		);
		$this->assertEquals($data,$ret);
	}
	public function testgetReportTodayDate(){
		$params = array(
			'accountIdBiz'	=> array(
				'key'	=> "123_456"
			)
		);	
		$obj = new Service_Data_HouseReport_GroupOutletTodayDownloadData();
        $ret = $obj->getReportTodayDate( $params);
        $data = array(
			'data'	=> array(
				'1970-01-01_123_456'	=> array(
						'date_account_id' => '1970-01-01_123_456',
					'report_time' => '1970-01-01'
				)
			)
        );
        $this->assertEquals($data,$ret);
	}
	public function testgetPremierData(){
/*		$params = array(
			'accountIds' => array(
				0	=> 123
			)
		);	
		$res = array(
     		  '2014-11-24'
		);
		$res1 = array(
			'data'	=> array(
				0	=> array(
					123	=> 345	
				)	
			)	
		);
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletTodayDownloadData",array("getDateArr"));
        $obj->expects($this->any())
        ->method("getDateArr")
        ->will($this->returnValue($res));
		
		$objUtil = $this->genEasyObjectMock("Service_Data_HouseReport_GroupOutletData",array("getPremierData"),array("getPremierData"   =>$res1));
        Service_Data_HouseReport_GroupOutletData::setInstance($objUtil);
		$ret = $obj->getPremierData( $params);
var_dump($ret);exit;

		$data = array(

		);
		$this->assertEquals($data,$ret);	
*/
	}
	 /**   
     *@expectedException Exception
     */
    public function testgroupDownLoadDataException(){
        $tags = array();
        $params = array();
        $res =false;
        $obj1 = new Service_Data_HouseReport_GroupOutletTodayDownloadData();
        $ret = $obj1->groupDownLoadData($tags,$params);
    }
	 /**   
     *@expectedException Exception
     */
    public function testgroupDownLoadDataException1(){
         $tags = array(13);
         $params = array();
         $res =false;    
          $obj1 = new Service_Data_HouseReport_GroupOutletTodayDownloadData();
         $ret = $obj1->groupDownLoadData($tags,$params);
    }
	public function testgroupDownLoadData(){
        $tags = array(
            1,2,4,12
        );
        $params = array();
        $res = $res1 = $res2 = $res3 = $res4 = $res5 = $res6 = $res7 = $res8 = $res9 = true;
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("mergeData","getBusinessScope"));
         $obj->expects($this->any())
        ->method("mergeData")
        ->will($this->returnValue($res));
         $obj->expects($this->any())
         ->method("getBusinessScope")
         ->will($this->returnValue(array('data'=>array())));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);		
		$obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletTodayDownloadData",array("getPremierData","getAssureData","getBidData","getFrameAuditingData","getReportTodayDate","getAccountData","getSortCategory","formartData","accountIdAndIndex","getCrmAccountEmail","getUserCenterBalance","getBidBalance"));
        $obj1->expects($this->any())
        ->method("getPremierData")
        ->will($this->returnValue($res1));

        $obj1->expects($this->any())
        ->method("getAssureData")
        ->will($this->returnValue($res2));
        $obj1->expects($this->any())
        ->method("getBidData")
        ->will($this->returnValue($res3));
        $obj1->expects($this->any())
        ->method("getFrameAuditingData")
        ->will($this->returnValue($res4));
        $obj1->expects($this->any())
        ->method("getReportTodayDate")
        ->will($this->returnValue($res5));
        $obj1->expects($this->any())
        ->method("getAccountData")
        ->will($this->returnValue(array()));
		$obj1->expects($this->any())
        ->method("getSortCategory")
        ->will($this->returnValue($res7));
        $obj1->expects($this->any())
        ->method("formartData")
        ->will($this->returnValue($res8));
		$obj1->expects($this->any())
        ->method("accountIdAndIndex")
        ->will($this->returnValue($res9));
		$obj1->expects($this->any())
		->method("getCrmAccountEmail")
        ->will($this->returnValue(array()));
        $obj1->expects($this->any())
            ->method("getUserCenterBalance")
            ->will($this->returnValue(array()));
        $obj1->expects($this->any())
            ->method("getBidBalance")
            ->will($this->returnValue(array()));
        $ret = $obj1->groupDownLoadData($tags,$params);
        $data = true;
        $this->assertEquals($data,$ret);	
	}
}
