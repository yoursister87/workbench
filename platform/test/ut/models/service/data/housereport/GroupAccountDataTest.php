<?php
class GroupAccountDataTest extends Testcase_PTest{
	protected function setUp(){
		 Gj_LayerProxy::$is_ut = true;	
    }
    /*
	public function testgetAssureData(){
		$InputArray  = array(
			'date'		=> array(
				
				'sDate' =>	'2016-11-06',
				'eDate' => '2016-11-08'
			),
			'houseType'	=> 1
		);
		$res = array(
				'data'	=> array(
					'1002'	=> array(1),
					'1003'	=> array(2)
				)	
		);
		$res1 = true;
		$res2 = array(
			'data'	=> array(
				'1002'	=> array(1),
				'1003'	=> array(2)
			)	
		);
		$res3 = array(
			    'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )  	
		);
		$res4 = 4;
		$obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("getAccountAssureReportDetail","setVal"));
			$obj->expects( $this->any())
			->method('getAccountAssureReportDetail')
			->will($this->returnValue($res));
			$obj->expects( $this->any())
			->method('setVal') 
			  ->will($this->returnValue($res1)); 		
		$obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData","mergeReportAndUserInfo","getUserBusinessInfo"));
			$obj1->expects( $this->any())  
			->method("changeData")
			 ->will($this->returnValue($res2));  

			 $obj1->expects( $this->any())  
			 ->method("mergeReportAndUserInfo")
			 ->will($this->returnValue($res3));  
			  $obj1->expects( $this->any())  
				  ->method("getUserBusinessInfo")
				  ->will($this->returnValue($res4));  
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
		$orgObj = new Service_Data_HouseReport_GroupAccountData();
		$ret = $orgObj->getAssureData($InputArray);
		$data = array(
			'data'	=> array(
				'1002'	=> array(1),
				'1003'	=> array(2)
			)	
		);
		$this->assertEquals($data,$ret);	
	}
	public function testgetPremierData(){
		 $InputArray  = array(
            'date'      => array(
    
                'sDate' =>  '2016-11-06',
                'eDate' => '2016-11-08'
            ),  
            'houseType' => 1
        );  
        $res = array(
                'data'  => array(
                    '1002'  => array(1),
                    '1003'  => array(2)
                )   
        );  
        $res1 = true;
        $res2 = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )   
        );  
		$res3 = 3;
		$res4 = array(
			   'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )  	
		);
        $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("getAccountPremierReportDetail","setVal"));
            $obj->expects( $this->any())
            ->method('getAccountPremierReportDetail')
            ->will($this->returnValue($res));
            $obj->expects( $this->any())
            ->method('setVal') 
              ->will($this->returnValue($res1));    
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData","getUserBusinessInfo","mergeReportAndUserInfo"));
            $obj1->expects( $this->any())  
            ->method("changeData")
             ->will($this->returnValue($res2));  	

				$obj1->expects( $this->any())  
					 ->method("getUserBusinessInfo")
					 ->will($this->returnValue($res3)); 

			$obj1->expects( $this->any())
				 ->method("mergeReportAndUserInfo")
				 ->will($this->returnValue($res4)); 
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
        $orgObj = new Service_Data_HouseReport_GroupAccountData();
        $ret = $orgObj->getPremierData($InputArray);
        $data = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )
        );
		$this->assertEquals($data,$ret);
    }
    */    
	public function testgetBidData(){
		$params = array(
			'date'      => array(
                'sDate' =>  '2016-11-06',
                'eDate' => '2016-11-08'
            ),
			'houseType'	=> 1	
		);
		 $res = array(
                'data'  => array(
                    '1002'  => array(1),
                    '1003'  => array(2)
                )
        );
        $res1 = true;
        $res2 = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )
        );	
		    $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("getAccountPremierReportDetail","setVal"));
            $obj->expects( $this->any())
            ->method('getAccountPremierReportDetail')
            ->will($this->returnValue($res));
            $obj->expects( $this->any())
            ->method('setVal')
              ->will($this->returnValue($res1));
        	$obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
            $obj1->expects( $this->any())
            ->method("changeData")
             ->will($this->returnValue($res2));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj1);
        $orgObj = new Service_Data_HouseReport_GroupAccountData();
        $ret = $orgObj->getBidData($params);
        $data = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )
        );
        $this->assertEquals($data,$ret);	
	}
	public function testgetFrameData(){
		$data = array(
				1	=> array(
					 '1002'  => array(1),
                	 '1003'  => array(2)
				)
		);
		$params = array(
			'date'      => array(
                'sDate' =>  '2016-11-06',
                'eDate' => '2016-11-08'
            ),
            'houseType' => 1		
		);
		$obj = new Service_Data_HouseReport_GroupAccountData();
		$ret = $obj->getFrameData($params,$data);
		$result = array(
			     	'1002'  => array(1),
                    '1003'  => array(2)
		);
		$this->assertEquals($result,$ret);


		$data = array(
                2   => array(
                     '1002'  => array(1),
                     '1003'  => array(2)
                )
        );
        $params = array(
            'date'      => array(
                'sDate' =>  '2016-11-06',
                'eDate' => '2016-11-08'
            ),
            'houseType' => 1
        );
		$res = array(
			        '1002'  => array(1),
                    '1003'  => array(2)				
		);
		    $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountData",array("getPremierData"));
            $obj->expects( $this->any())
            ->method("getPremierData")
             ->will($this->returnValue($res));
        $ret = $obj->getFrameData($params,$data);
        $result = array(
                    '1002'  => array(1),
                    '1003'  => array(2)
        );
        $this->assertEquals($result,$ret);	
		
	}
	public function testgetFrameAuditingData(){
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
         $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountData",array("getPremierData","getAssureData"));
            $obj->expects( $this->any())
            ->method('getPremierData')
            ->will($this->returnValue($res));

            $obj->expects( $this->any())
            ->method('getAssureData')
            ->will($this->returnValue($res1));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupAccountData",$obj);
        $orgObj = new  Service_Data_HouseReport_GroupAccountData();
        $ret = $orgObj->getFrameAuditingData($params,$data);
        $data = array(
            'data'  => array(
                123   => array(
                    'report_date' => null,
                    'similar_house_count'   => 2,
                    'illegal_house_count'   => 4,
                    'comment_count'         => 6
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
                        'count' => 4
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
            $objExp = new Service_Data_HouseReport_GroupAccountData();
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
            $objExp = new Service_Data_HouseReport_GroupAccountData();
            $ret = $objExp->groupAjaxData($tags,$params);
   }
	public function testgroupAjaxData(){
		        $tags = array(1,2,4,10,12);
        $params = array(
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
            '0' => 'pv',
            '1' => 'av'
        );
        $res8 = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
		 $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal","mergeData"));
            $obj1->expects( $this->any())
            ->method('isShowTotal')
            ->will($this->returnValue($res));

            $obj1->expects( $this->any())
            ->method('mergeData')
            ->will($this->returnValue($res8));
             Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);
			 $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountData",array("getPremierData","getAssureData","getBidData","getTotalData","getFrameData","getFrameAuditingData","getSortCategory"));
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
    //  $orgObj = new  Service_Data_HouseReport_GroupOrgData();
        $ret = $obj->groupAjaxData($tags,$params);
        $data1  = array(
            'errorno'   => 0,
            'errormsg'  => '[数据返回成功]'
        );
          $this->assertEquals($data1,$ret);	         			
	}
}
