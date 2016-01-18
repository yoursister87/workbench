<?php
class GroupOrgDownloadDataTest extends Testcase_PTest{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    } 	
	public function testgetReportDate(){
		/*
		$params = array(
			'orgIds'	=> array(
				'key'	=> 123
			)
		);	
		$obj = new Service_Data_HouseReport_GroupOrgDownloadData();
		$ret = $obj->getReportDate($params);
		$data = array(
			'data'	=> array(
				123	=> array(
					'org_id'				=> 123,
					'report_start_time'		=> null,
					'report_end_time'		=> null
				)
			)
		);
		$this->assertEquals($data,$ret);		
		 */
	}
	public function testgetAccountData(){
		/*
		$params = array(
			'orgIds'	=> array(
				'key'	=> 123
			),
			'account'		=> array(
					123=>		'zsj'
					),
			'houseTypeText'	=> '租房'
		);	
		$data	= array(
			1	=> array(
				'data'	=> array(
					123	=> array(
						'login_count'	=> 50
					)
				)
			)
		);
		$obj = new Service_Data_HouseReport_GroupOrgDownloadData();
        $ret = $obj->getAccountData($params,$data);
		$data = array(
			'data'	=> array(
				123	=> array(
					'org_id'			=> 123,
					'login_name'		=> 'zsj',
					'business_scope'	=> '租房',
					'login_count'		=> 50
				)
			)	
		);
		$this->assertEquals($data,$ret);


		$params = array(
            'orgIds'    => array(
                'key'   => 123 
            ),  
            'account'       => array(
                    123=>       'zsj'
                    ),
            'houseTypeText' => '租房'
        );  
        $data   = array(
            2   => array(
                'data'  => array(
                    123 => array(
                        'login_count'   => 50
                    )   
                )   
            )   
        );  
		$res = null;
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgDownloadData",array("getPremierData"));
		$obj->expects($this->any())
			->method("getPremierData")
			->will($this->returnValue($res));
        $obj = new Service_Data_HouseReport_GroupOrgDownloadData();
        $ret = $obj->getAccountData($params,$data);
        $data = array(
            'data'  => array(
                123 => array(
                    'org_id'            => 123,
                    'login_name'        => 'zsj',
                    'business_scope'    => '租房',
                    'login_count'       => 0
                )
            )
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
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal"));
        $obj->expects($this->any())
        ->method("isShowTotal")
        ->will($this->returnValue($res));			
		 Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);
		$obj1 = new Service_Data_HouseReport_GroupOrgDownloadData();
		$ret = $obj1->groupDownLoadData($tags,$params);
	}
     /**   
     *@expectedException Exception
     */
	/*
	public function testgroupDownLoadDataException1(){
		 $tags = array(13);
		 $params = array();
		 $res =false;		
		 $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal"));
         $obj->expects($this->any())
         ->method("isShowTotal")
         ->will($this->returnValue($res));
		  Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);
		  $obj1 = new Service_Data_HouseReport_GroupOrgDownloadData();
		 $ret = $obj1->groupDownLoadData($tags,$params);
	}
	public function testgroupDownLoadData(){
		$tags = array(
			1,2,4,10,12
		);
        $params = array();
        $res = $res1 = $res2 = $res3 = $res4 = $res5 = $res6 = $res7 = $res8 =true;	
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgDownloadData",array("isShowTotal","mergeData"));
        $obj->expects($this->any())
        ->method("isShowTotal")
        ->will($this->returnValue($res));
		 $obj->expects($this->any())
        ->method("mergeData")
        ->will($this->returnValue($res8));
		   Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);		
		
		$obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOrgDownloadData",array("getPremierData","getAssureData","getBidData","getFrameData","getFrameAuditingData","getReportDate","getAccountData","getSortCategory"));
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
        ->method("getReportDate")
        ->will($this->returnValue($res6));  	
		$obj1->expects($this->any())
        ->method("getAccountData")
        ->will($this->returnValue($res7)); 
		$ret = $obj1->groupDownLoadData($tags,$params);
		$data = true;
		$this->assertEquals($data,$ret);
	}
	 */
}
