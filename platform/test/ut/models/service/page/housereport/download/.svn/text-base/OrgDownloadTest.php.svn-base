<?php
class OrgDownloadTest extends	 Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
    }
	public function testgetSumData(){
		$downData = array(
			0	=> array(
				'title'			=> '房1',
				'title_data'	=> array( '123'	=> 345),
				'total_data'	=> array('234'	=> '345')
			)
		);	
		$obj = new Service_Page_HouseReport_Download_OrgDownload();
		$data = array(
			'汇总数据报表'	=> array(
				'title'	=> array(
							array(
								0	=> array(
										'name'	=> '房1',
										'width'	=> 1
										)
							)
						),
			'data'	=> array(
				0	=> array(
					0	=> 345	
				),
				1=> '345'
					)
			)
		);
		$ret = $obj->getSumData($downData);
		$this->assertEquals($data['data'],$ret['data']);
	}
    /**   
     *@expectedException Exception
     */
	public function testgroupOrgDataException(){
		$params = array(
			'level'			=> 1,
			'keyword'		=> 'zsj',
			'userId'		=> 234,
			
		);	
		$res = array(
			'data'	=> array(
				'list'	=>	 array(
								'id'		=> 11,
								'title'		=> '赶集测试公司',
								'account'	=>'admin_gjcs'
							)
			)
		);
	    $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getChildTreeByOrgId"));
        $obj->expects($this->any())
         ->method("getChildTreeByOrgId")
        ->will($this->returnValue($res));
        $obj = new Service_Page_HouseReport_Download_OrgDownload();
		$ret = $obj->groupOrgData($params);
	}
	public function testgroupOrgData(){
		$params = array(
            'level'         => 1,
            'keyword'       => 'zsj',
            'userId'        => 234,
            
        );  
        $res = array(
            'data'  => array(
                'list'  =>   array(
                                0	=> array(
									'id'        => 1,
                                	'title'     => '赶集测试公司',
                                	'account'   =>'admin_gjcs'
									)
                            )   
            ),
			'errorno'	=> 0               
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getChildTreeByOrgId"));
        $obj->expects($this->any())
         ->method("getChildTreeByOrgId")
        ->will($this->returnValue($res));	
		   Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $obj = new Service_Page_HouseReport_Download_OrgDownload();
        $ret = $obj->groupOrgData($params);	
		$data = array(
			'data'	=> array(
				'areaName'	=> 
						array(	1	=> '赶集测试公司'),
				 'account'	=> array( 1	=> 'admin_gjcs')
			),
			'orgIds'	=> array(1)
		);
		$this->assertEquals($data,$ret);
	}
	public function testexecute(){
		$arrInput = array(
			'date'	=> array(
						'sDate'	=> '2014-11-30',
						'eDate'	=> '2014-12-01'
					),
			'houseType'		=> 1,
			'countType'		=> 2,
			'houseTypeText'	=> '租房',
			'downloadType'	=> 1
		);	
		$res = array(

		);
	}
} 
