<?php
class  ReportServiceTest  extends Testcase_PTest{
   protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
   }
	public function testsetHouseType(){
		$params = array(
			'businessScope'	=> ''
		);	
		$obj = new Service_Data_HouseReport_ReportService();
		$ret = $obj->setHouseType($params);
		$data = array(0);
		$this->assertEquals($data,$ret);

		$params = array(
            'businessScope' => array(
				0	=> 1
			)
        );  
        $obj = new Service_Data_HouseReport_ReportService();
        $ret = $obj->setHouseType($params);
        $data = array(1,3,5,12);
        $this->assertEquals($data,$ret);	
	}	
	public function testgetAllOutlet(){
		$orgId = 123;	
		$res = array(
			'data'	=> array(
				'list'	=> array(
					'customer_id'	=> 123
				)
			)
		);
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getChildTreeByOrgId"));
        $obj->expects($this->any())
        ->method("getChildTreeByOrgId")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);	
		$obj1 = new Service_Data_HouseReport_ReportService();
		$ret = $obj1->getAllOutlet($orgId);
		$data = array(
			0	=> null
		);
		  $this->assertEquals($data,$ret);
    }

    public function testgetCommonParams(){
  /*      $fields = array("product","companyId","pid","level","cid","orgIds","page","userId","userLevel","kword","stype","dtype",'domain');
        $res = array("product","companyId","pid","level","customerId","orgIds","page","userId","userLevel","keyword","stype","dtype",'domain');
        foreach ($fields as $item) {
            $result[$item] = 1;
        }
        foreach ($res as $item) {
            $res1[$item] = 1;
        }

        $obj = new Service_Data_HouseReport_ReportService();
		$ret = $obj->getCommonParams($result);
		$this->assertEquals($ret,$res1);
*/
    }

    public function testgetPageStr(){
        $obj = new Service_Data_HouseReport_ReportService();
        $page = 1;
        $totalCount = 20;
        $level = 1;
        $ret = $obj->getPageStr($page,$totalCount,$level);
        $str = '<li><a class="c linkOn"><span>1</span></a></li>
<li><a onclick="pagination(\'2\',pagination)" href="javascript:void(0)" ><span>2</span></a></li>

<li><a onclick="pagination(\'2\',pagination)" href="javascript:void(0)" class="next"><span>下一页</span></a></li>
<li><span class="all-broker">共<i>20</i>个区域</span></li>';
		$this->assertEquals($str,$ret);

    }
} 
