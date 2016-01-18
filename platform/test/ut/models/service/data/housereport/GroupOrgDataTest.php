<?php
class	GroupOrgDataTest extends Testcase_PTest{
	protected function setUp(){
		 Gj_LayerProxy::$is_ut = true;
	}
	public function testgetAssureData(){
		$arrInput = array(
			'date'	=> array(
				'sDate'	=> '2014-09-11',
				'eDate'	=> '2014-11-06'
			),
			'houseType'	=> 1	
		);
		$res = array(
			'data'	=> array(
				'report_type'	=>4,
				'house_id'		=>123,
				'city_id'		=> 100	
			)		
		);
		$res2 = array(
			     'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600  	
		);
		 $obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("getOrgAssureReportList"));
            $obj->expects( $this->any())
            ->method('getOrgAssureReportList')
            ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
            $obj1->expects( $this->any())  
            ->method("changeData")
             ->will($this->returnValue($res2));    

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getAssureData($InputArray);			
		$data = array(
				'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600 	
		);
		 $this->assertEquals($data,$ret);
	}
	public function testgetPremierData(){
		 $arrInput = array(
            'date'  => array(
                'sDate' => '2014-09-11',
                'eDate' => '2014-11-06'
            ),
            'houseType' => 1
        );
        $res = array(
            'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )
        );
        $res2 = array(
                 'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600
        );
         $obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("getOrgPremierReportList"));
            $obj->expects( $this->any())
            ->method('getOrgPremierReportList')
            ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
            $obj1->expects( $this->any())
            ->method("changeData")
             ->will($this->returnValue($res2));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();	
		  $ret = $orgObj->getPremierData($InputArray);
        $data = array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600
        );
         $this->assertEquals($data,$ret);
	}
	public function testgetBidData(){
		$arrInput = array(
            'date'  => array(
                'sDate' => '2014-09-11',
                'eDate' => '2014-11-06'
            ),
            'houseType' => 1
        );
        $res = array(
            'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )
        );
        $res2 = array(
                 'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600
        );
         $obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("getOrgPremierReportList"));
            $obj->expects( $this->any())
            ->method('getOrgPremierReportList')
            ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
            $obj1->expects( $this->any())
            ->method("changeData")
             ->will($this->returnValue($res2));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();	
		$ret = $orgObj->getBidData($InputArray);
        $data = array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 3600
        );
         $this->assertEquals($data,$ret);
	}
	public function testgetFrameData(){
		$params = array(
			'userLevel'	=> 0,
			'level'		=>  1,
            'orgIds'=>array(1)
		);
		$data = array(
			1	=>	array(
					'data'	=> array(
					 		'1'	=> array(
								'plate_count'	=> 10
							)  	
				)
			) 
		);	
		$res = array(
			'data'	=> 10
		);
		   $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgCountByPid"));
            $obj->expects( $this->any())
            ->method('getOrgCountByPid')
            ->will($this->returnValue($res));	
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameData($params,$data);

        $data = array(
			'data'	=> array(
				1	=> array(
					'plate_count'	=> 10,
                    'org_id'=>1
				)
			)	
		);
		 $this->assertEquals($data,$ret);

        $params = array(
            'userLevel' => 0,
            'level'     =>  1,
            'orgIds'=>array(123)
        );
        $data = array(
            1   =>  array(
                    'data'  => array(
                            '123'   => array(
                                'plate_count'   => 10,
                                'org_id'=>123
                            )
                )
            )
        );
        $res = array(
            'data' => 0
        );
           $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgCountByPid"));
            $obj->expects( $this->any())
            ->method('getOrgCountByPid')
            ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameData($params,$data);
        $data = array(
            'data'  => array(
                123 => array(
                    'plate_count' => 0,
                    'org_id' => 123,
                )
            )
        );
         $this->assertEquals($data,$ret);

        $params = array(
            'userLevel' => 0,
            'level'     =>  3,
                    'orgIds'=>array(123)
        );
        $data = array(
            1   =>  array(
                    'data'  => array(
                            '123'   => array(
                                'plate_count'   => 10
                            )
                )
            )
        );
        $res = array(
            'data'  => array(
                'count' => 10
            )
        );
           $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getChildTreeByOrgId"));
            $obj->expects( $this->any())
            ->method('getChildTreeByOrgId')
            ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameData($params,$data);
        $data = array(
            'data'  => array(
                123 => array(
                    'plate_count'   => 10,
                    'org_id'=>123
                )
            )
        );
         $this->assertEquals($data,$ret);

	}
	public function testgetFrameAuditingData(){
		$params = array(
				1 => array(
					'data'	=> array(
							'123'	=> array(
								'similar_house_count' => 1,
								'illegal_house_count' => 2,
								'comment_count'		  => 3
									)
							)
						),
					 'count' => 5,
					 'product'	=> array(
						 1 => "premier" , 
						 2 => "assure"
					 )
		);
		$data = array(

				 1 => array(
                    'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            ),
					   'count' => 5
                        ),
				2 => array(
					'data'  => array(
						'123'   => array(
							'similar_house_count' => 1,
							'illegal_house_count' => 2,
							'comment_count'       => 3
								),
					 'count' => 4
						),
			)
		);
		$res = array(
			            'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            )
		);
		$res1 = array(
			             'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )   
                            )   
		);
		 $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgData",array("getPremierData","getAssureData"));
            $obj->expects( $this->any())
            ->method('getPremierData')
            ->will($this->returnValue($res));

		    $obj->expects( $this->any())
            ->method('getAssureData')
            ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOrgData",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameAuditingData($params,$data);
		$data = array(
			'data'	=> array(
				123   => array(
					'org_id' => null,
					'similar_house_count'	=> 2,
					'illegal_house_count'	=> 4,
					'comment_count'			=> 6
				)
			),
			'count'	=> 5

		);
		     $this->assertEquals($data,$ret);


		   $params = array(
                1 => array(
                    'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )   
                            )   
                        ),  
                     'count' => 5,
                     'product'  => array(
                         1 => "premier" , 
                     )   
        );  
        $data = array(

                 1 => array(
                    'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )   
                            ),  
                       'count' => 5
                        ),
		           2 => array(
                    'data'  => array(
                        '123'   => array(
                            'similar_house_count' => 1,
                            'illegal_house_count' => 2,
                            'comment_count'       => 3
                                ),
                     'count' => 4
                        ),
            )
        );
        $res = array(
                        'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            )
        );
        $res1 = array(
                         'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            )
        );
         $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgData",array("getPremierData","getAssureData"));
            $obj->expects( $this->any())
            ->method('getPremierData')
            ->will($this->returnValue($res));
            $obj->expects( $this->any())
            ->method('getAssureData')
            ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOrgData",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameAuditingData($params,$data);
        $data = array(
            'data'  => array(
                123   => array(
                    'org_id' => null,
                    'similar_house_count'   => 1,
                    'illegal_house_count'   => 2,
                    'comment_count'         => 3
                )
            ),
            'count' => 5

        );
             $this->assertEquals($data,$ret);
	
			          $params = array(
                1 => array(
                    'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            )
                        ),
                     'count' => 5,
                     'product'  => array(
                         2 => "assure" ,
                     )
        );
        $data = array(

                 1 => array(
                    'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            ),
                        ),	
				                   2 => array(
                    'data'  => array(
                        '123'   => array(
                            'similar_house_count' => 1,
                            'illegal_house_count' => 2,
                            'comment_count'       => 3
                                ),
                        ),
					'count' => 4
            ),
        );
        $res = array(
                        'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    )
                            )
        );
        $res1 = array(
                         'data'  => array(
                            '123'   => array(
                                'similar_house_count' => 1,
                                'illegal_house_count' => 2,
                                'comment_count'       => 3
                                    ),
                            ),
						'count'	=> 4
        );	
		         $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgData",array("getPremierData","getAssureData"));
            $obj->expects( $this->any())
            ->method('getPremierData')
            ->will($this->returnValue($res));
            $obj->expects( $this->any())
            ->method('getAssureData')
            ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOrgData",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $orgObj->getFrameAuditingData($params,$data);
        $data = array(
            'data'  => array(
                123   => array(
                    'org_id' => null,
                    'similar_house_count'   => 1,
                    'illegal_house_count'   => 2,
                    'comment_count'         => 3
                )
            ),
            'count' => 4

        );
             $this->assertEquals($data,$ret);


  
	}
	public function testgroupAjaxData(){
		$tags = array(1,2,4,10,12);
		$params	= array(
	        'date'  => array(
                'sDate' => '2014-09-11',
                'eDate' => '2014-11-06'
            )  
		);	
		$res = true;
		$res1 = array(
			  'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )	
		);
		$res2 = array(
			  'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )	
		);
		$res3 = array(
			  'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )	
		);
		$res4 = array(
			 'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )	
		);
		$res5 = array(
			 'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )
		);
		$res6 = array(
			 'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )
		);
		  $data = array(
             'data'  => array(
                'report_type'   =>4,
                'house_id'      =>123,
                'city_id'       => 100
            )
        );
		$res7  = array(
			'0'	=> 'pv',
			'1'	=> 'av'
		);
		$res8 = array(
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		    $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal","mergeData"));
            $obj1->expects( $this->any())
            ->method('isShowTotal')
            ->will($this->returnValue($res));
			 
			$obj1->expects( $this->any())
            ->method('mergeData')
            ->will($this->returnValue($res8));
			 Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);

		    $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOrgData",array("getPremierData","getAssureData","getBidData","getTotalData","getFrameData","getFrameAuditingData","getSortCategory"));
            $obj->expects( $this->any())
            ->method('getPremierData')
            ->will($this->returnValue($res1));

			 $obj->expects( $this->any())
            ->method('getAssureData')
            ->will($this->returnValue($res2));

			 $obj->expects( $this->any())
            ->method('getBidData')
            ->will($this->returnValue($res3));

            $obj->expects( $this->any())
            ->method('getTotalData')
            ->will($this->returnValue($res4));


			$obj->expects( $this->any())
            ->method('getFrameData')
            ->will($this->returnValue($res5));

			 $obj->expects( $this->any())
            ->method('getFrameAuditingData')
            ->will($this->returnValue($res6));

            $obj->expects( $this->any())
            ->method('getFrameAuditingData')
            ->will($this->returnValue($res7));		
	//	$orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $obj->groupAjaxData($tags,$params);
		$data1  = array(
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		  $this->assertEquals($data1,$ret);
	}
    /**   
     *@expectedException Exception
     */
	public function testgroupAjaxDataException(){
			$tags = 1;
		    $params = array(
            'date'  => array(
                'sDate' => '2014-09-11',
                'eDate' => '2014-11-06'
            		)
       		 );	
			$res = false;
		    $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal"));
            $obj->expects( $this->any())
            ->method('isShowTotal')
            ->will($this->returnValue($res));
			      Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);
			$objExp = new Service_Data_HouseReport_GroupOrgData();
			$ret = $objExp->groupAjaxData($tags,$params);						
    
	}
	/**   
     *@expectedException Exception
     */
	public function testgroupAjaxDataException1(){
		   $tags = array(33,22);
            $params = array(
            'date'  => array(
                'sDate' => '2014-09-11',
                'eDate' => '2014-11-06'
                    )
             );
            $res = false;
            $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal"));
            $obj->expects( $this->any())
            ->method('isShowTotal')
            ->will($this->returnValue($res));
                  Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);
            $objExp = new Service_Data_HouseReport_GroupOrgData();
            $ret = $objExp->groupAjaxData($tags,$params);			
	}
}
