<?php
/*
 * File Name:GroupDataTest.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
class GroupDataTest extends Testcase_PTest{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Data_HouseReport_GroupData();
        $this->dataList['data'] = array();
        $this->errorData['data'] = array();
        $this->errorData['errorno'] = 1;
        $this->errorData['errnormsg'] = 'error';
    }

    public function testIsShowTotal(){
        $tags = array(Service_Data_HouseReport_GroupData::ASSURE);
        $params['houseType'] = array(0);
        $status = $this->obj->isShowTotal($tags,$params);
        $this->assertEquals($status,false);
       
        $obj =  new Service_Data_HouseReport_GroupData();
        $tags = array(Service_Data_HouseReport_GroupData::ASSURE,Service_Data_HouseReport_GroupData::PREMIER);
        $params['houseType'] = array(0);
        $status = $obj->isShowTotal($tags,$params);
        $this->assertEquals($status,true);

        $obj =  new Service_Data_HouseReport_GroupData();
        $tags = array(Service_Data_HouseReport_GroupData::ASSURE,Service_Data_HouseReport_GroupData::PREMIER);
        $params['houseType'] = 1;
        $status = $obj->isShowTotal($tags,$params);
        $this->assertEquals($status,false);

        $obj =  new Service_Data_HouseReport_GroupData();
        $tags = array(Service_Data_HouseReport_GroupData::ASSURE,Service_Data_HouseReport_GroupData::PREMIER);
        $params['houseType'] = array(2);
        $status = $obj->isShowTotal($tags,$params);
        $this->assertEquals($status,false);

    }

    public function testSetDefaultValue(){
        $obj =  new Service_Data_HouseReport_GroupData();
        $orgids = array(1);
        $fields = array('pv'=>'点击量');
		$indexFieldName = 'secondFloor';
        $result = array(1=>array('pv'=>0,'secondFloor'=>1));
        $status = $obj->setDefaultValue($orgids,$fields,$indexFieldName);
        $this->assertEquals($status,$result);
    }

    public function testChangeData(){
		$changeFields = array(
			0	=> 'secondFloor'
		);
		$dataList = array(
			'errorno'	=> 0,
			'data'		=> array(
				'list'	=> array(
					0	=> array(
						'secondFloor'	=> 11
					),
				),
			'count'	=> 1
			),
					);
		$indexFieldName = 'secondFloor';
		$obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->changeData($dataList,$changeFields,$indexFieldName);
		$data = array(
			'data'	=> array(
				array(
						'' => 11)
			),
			'count'	=> 1	
		);
		    $this->assertEquals($data,$ret);
	}
	/**
	*@expectedException Exception
	*/
	public function testChangeDataException(){
		$changeFields = array(
            0   => 'secondFloor'
        );

		 $dataList = array(
            'errorno'   => 0,
            'data'      => array(
                'list'  => array(
                    0   => array(
                        'secondFloor'   => 11
                    ),
                ),
            'count' => 32
            ),
                    );
        $dataList = array(
            'errorno'   => 0,
            'data'=>array('list'=>array(1)),
            'errormsg'	=> '抛出异常'        
			);
        $indexFieldName = 'secondFloor';
        $obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->changeData($dataList,$changeFields,$indexFieldName);
	}
	/**
    *@expectedException Exception
    */
	public function testChangeDataException1(){
          $changeFields = array(
            0   => 'secondFloor'
        );
		  $dataList = array(
            'errorno'   => 0,
            'data'      => array(
                'list'  => array(
                    0   => array(
                        'secondFloor'   => 11
                    ),
                ),
            'count' => 32
            ),
                    );
        $indexFieldName = 'secondFloor1';
        $obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->changeData($dataList,$changeFields,$indexFieldName);
    }
	public function testsortAjaxData(){
		$dataList = array(
			'ph'	=> array(1,2,3)
		);
		$sortCategory = array(
			'premierHouse'	=> 'ph',
			'securityHose'	=> 'sh'
		);
		$data = array(
			'ph'	=> array(1,2,3)
		);
		$obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->sortAjaxData($dataList,$sortCategory);
        $this->assertEquals($data,$ret);	
	}
	public function testmatchData(){
		$titleData = array(
			'pv'	=> array(
			)	
		);
		$tableData = array(
			'total_data'	=> array(
				'pv'	=> array(1,2,3)
			),
			'maxCol'	=> 3
		);
		$sortIndex = 'pv';
		$obj =  new Service_Data_HouseReport_GroupData();
		$ret = $obj->matchData( $titleData,$tableData,$sortIndex);
		$data= array(
			'total_data'	=> array(
				'pv'	=> array(1,2,3),
				'total_data'	=> array()
			),
			'maxCol'	=> 3	
		);
		$this->assertEquals($data,$ret);


		$titleData = array(
            'pv'    => array(
				0	=> 'string'
            )   
        );  
        $tableData = array(
            'total_data'    => array(
				'pv'	=> ''
            ),  
            'maxCol'    => 3
        );  
        $sortIndex = 'pv';
        $obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->matchData( $titleData,$tableData,$sortIndex);
        $data= array(
            'total_data'    => array(
                'pv'    => '',
                'total_data'    => array(
					'string'	=> null
				)
            ),  
            'maxCol'    => 3    
        );  
        $this->assertEquals($data,$ret);
		


	    $titleData = array(
            'pv'    => array(
				 0   => 'string'
            )   
        );
        $tableData = array(
            'total_data'    => array(
                'pv'    => array(1,2,3),
				'total_data'	=> array(
						'string'    => array(1,2)
				),
				  'maxCol'    =>  2
            ),
        );
        $sortIndex = 'pv';
        $obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->matchData( $titleData,$tableData,$sortIndex);
        $data= array(
            'total_data'    => array(
                'pv'    => array(1,2,3),
                'total_data'    =>array(
					 'string'	=>array(1,2)
            ),
				'maxCol'    => 2
			)
        );
        $this->assertEquals($data,$ret);


		
		 $titleData = array(
            'pv'    => array(
                 0   => 'string'
            )   
        );
        $tableData = array(
            'total_data'    => array(
                'pv'    => array(1,2,3),
                'total_data'    => array(
                        'string'    => array(1,2)
                ),
                  'maxCol'    =>  3
            ),
        );
        $sortIndex = 'pv';
        $obj =  new Service_Data_HouseReport_GroupData();
        $ret = $obj->matchData( $titleData,$tableData,$sortIndex);
        $data= array(
            'total_data'    => array(
                'pv'    => array(1,2,3),
                'total_data'    =>array(
                     'string'   =>array(1,2,Service_Data_HouseReport_GroupData::NO_DATA_STR)
            ),
			 'maxCol'    =>  3
           )
        );
        $this->assertEquals($data,$ret);
	}

    public function testMergeData(){
        $dataList = array(
            1=>array(
                'data'=>array(
                    1=>array(
                        'account_id'=>1,
                        'click_count'=>1,
                    )
                )
            ),
           2=>array(
                'data'=>array(
                   1=>array( 'account_id'=>1,
                    'account_pv'=>2,)
                )
            ),
        );
        $sortCategory = array(2,1);
        $productData = array(
            1=> array(
                'title'=>'精品',
                'title_data'=>array('account_id'=>'用户id','click_count'=>'点击量'),
                ),
            2=> array(
                'title'=>'点击量',
                'title_data'=>array('account_id'=>'用户id','account_pv'=>'用户点击'),
            )
        );

        $indexFieldName = 'account_id';

        $zsObj = new Service_Data_HouseReport_GroupData();
        $ret = $zsObj->mergeData($dataList,$sortCategory,$productData,$indexFieldName);
        $result1 = array(
            1 =>
                array (
                    'title' => '点击量',
                    'title_data' =>
                        array (
                            0 => '用户id',
                            1 => '用户点击',
                        ),
                    'total_data' =>
                        array (
                            1 =>
                                array (
                                    0 => 1,
                                    1 => 2,
                                    2 => 1,
                                    3 => 1,
                                ),
                        ),
                    'maxCol' => 4,
                ),
            2 =>
                array (
                    'title' => '精品',
                    'title_data' =>
                        array (
                            0 => '用户id',
                            1 => '点击量',
                        ),
                ),
           );
        $this->assertEquals($ret,$result1);
        $dataList = array(
            1=>array(
                'data'=>array(
                    1=>array('account_id'=>1,
                        'click_count'=>1,)
                )
            ),
            2=>array()
        );
        $default = array(
            1=>array('account_id'=>1,
                'account_pv'=>0,)
        );
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("sortAjaxData",'setDefaultValue'));
        $obj->expects( $this->any())
            ->method('sortAjaxData')
            ->will($this->returnValue($dataList));
        $obj->expects( $this->any())
            ->method('setDefaultValue')
            ->will($this->returnValue($default));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);
        $zsObj = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $result2 = array (
            1 =>
                array (
                    'title' => '精品',
                    'title_data' =>
                        array (
                            0 => '用户id',
                            1 => '点击量',
                        ),
                    'total_data' =>
                        array (
                            1 =>
                                array (
                                    0 => 1, //account_id
                                    1 => 1, //用户点击 click_count
                                    2 => 1,//account_id
                                    3 => 0, //account_pv
                                ),
                        ),
                    'maxCol' => 4,
                ),
            2 =>
                array (
                    'title' => '点击量',
                    'title_data' =>
                        array (
                            0 => '用户id',
                            1 => '用户点击',
                        ),
                ),
        );
        $ret = $zsObj->mergeData($dataList,$sortCategory,$productData,$indexFieldName);
        $this->assertEquals($ret,$result2);
    }
	public function testgetOrgClickByDay(){
		$params = array(
			'countType'	=> array(4)
		);	
		$res = array(
			'data'	=> array(
				'list'	=> array(
					array(
						'click_count'	=> 4
					)
				)
			)	
		);
		$obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("getOrgAssureReportById"));
		$obj->expects($this->any())
			->method("getOrgAssureReportById")
			->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport", $obj);
		 $obj1 = new Service_Data_HouseReport_GroupData();
		 $ret = $obj1->getOrgClickByDay($params);
		 $data = array(
			 0	=> 4
		 );
		 $this->assertEquals($data,$ret);


	    $params = array(
            'countType' => array(3),
			'orgid'		=> 123
        );  
		$res = array(
			'data'	=> array(
            	'list'  => array(
                	    array(
                    	    'click_count'   => 3
                    	)
                	)
				)
         );	
        $obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("getOrgPremierReportById"));
		$obj->expects($this->any())
        ->method("getOrgPremierReportById")
        ->will($this->returnValue($res));
         Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport", $obj);
         $obj1 = new Service_Data_HouseReport_GroupData();
         $ret = $obj1->getOrgClickByDay($params);
         $data = array(
             0  => 3
         );  
         $this->assertEquals($data,$ret);
	}
}
