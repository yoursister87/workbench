<?php
class GroupOutletAvgDownloadDataTest extends Testcase_PTest{
   protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
   }	
    /**   
     *@expectedException Exception
     */
    public function testgroupDownLoadDataException(){
        $tags = array();
        $params = array();
        $res =false;
        $obj1 = new Service_Data_HouseReport_GroupOutletAvgDownloadData();
        $ret = $obj1->groupDownLoadData($tags,$params);
    }
     /**   
     *@expectedException Exception
     */
    public function testgroupDownLoadDataException1(){
         $tags = array(13);
         $params = array();
         $obj = new Service_Data_HouseReport_GroupOutletAvgDownloadData();
         $ret = $obj->groupDownLoadData($tags,$params);
    }	
	public function testgroupDownLoadData(){
		$tags = array(
            1,2,4,10,12
        );
        $params = array();
        $res1 = $res2 = $res3 = $res4 = $res5 = $res6 = $res7 = $res8 = $res9 = true;
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("mergeData"));
        $obj->expects($this->any())
        ->method("mergeData")
        ->will($this->returnValue($res9));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);	
		$obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletAvgDownloadData",array("getPremierData","getAssureData","getBidData","getFrameData","getFrameAuditingData","getAccountData","getSortCategory",'getReportDate',"formartData","getCrmAccountEmail","getUserCenterBalance","getBidBalance"));
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
        ->method("getFrameData")
        ->will($this->returnValue($res4));
        $obj1->expects($this->any())
        ->method("getFrameAuditingData")
        ->will($this->returnValue($res5));
        $obj1->expects($this->any())
        ->method("getAccountData")
        ->will($this->returnValue(array()));
        $obj1->expects($this->any())
            ->method("getReportDate")
            ->will($this->returnValue($res7));
        $obj1->expects($this->any())
            ->method("formartData")
            ->will($this->returnValue($res8));
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
