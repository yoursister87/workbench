<?php
class MainTest extends Testcase_PTest{
	 protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     } 	
	 public function testorgInfo(){
		$arrInput	= array(
			'level'			=> 2,
			'name'			=> 'zsj',
			'phone'			=> 13752967124,
			'account'		=> 'zsjlaoye',
			'company_id'	=> 235,
			'id'			=> 18
		);	
		$res = array(
			'data'	=> array(
			'loging_time'	=> 111
			)
		);
		$res1 = array(
			'data'	=> array(222,333)
		);
		$res2 = true;
		$res3 = array(
			'data'	=> array(
				'list'	=> array(
					0	=> array(
						'customer_count' =>15,
						'account_count'	=> 5
					)
				)
			)	
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("getLastedCustomerLoginLog"));
		$obj->expects($this->any())
		 ->method("getLastedCustomerLoginLog")
		->will($this->returnValue($res));
		
		$obj1 = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getOutlet"));
        $obj1->expects($this->any())
         ->method("getOutlet")
        ->will($this->returnValue($res1));	

		$obj2 = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgPremierReportById"));
        $obj2->expects($this->any())
         ->method("setVal")
        ->will($this->returnValue($res2));

		$obj2->expects($this->any())
        ->method("getOrgPremierReportById")
        ->will($this->returnValue($res3));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer",$obj1);
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj2);
		$obj3 = new Service_Page_HouseReport_Index_Main();
		$ret = $obj3->orgInfo($arrInput);
		$data	= array(
			'level'				=> 2,
			'manager'			=>'zsj',
			'phone'				=> '13752967124',
			'last_login'		=>'1970-01-01 08:01:51',
			'login_city'		=>null,
			'outlet_count'		=> 2,
			'customer_count'	=> 15,
			'account_count'		=>5
		);
		$this->assertEquals($ret,$data);

	 }
	public function testorgInfoException(){
	     $arrInput   = array(
            'level'         => 2,
            'name'          => 'zsj',
            'phone'         => 13752967124,
            'account'       => 'zsjlaoye',
            'company_id'    => 235,
            'id'            => 18
        );
        $res = array(
            'data'  => array(
            'loging_time'   => 111
            )
        );
        $res1 = array(
            'data'  => array(222,333)
        );
        $res2 = true;
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccountLoginEvent",array("getLastedCustomerLoginLog"));
        $obj->expects($this->any())
         ->method("getLastedCustomerLoginLog")
        ->will($this->returnValue($res));

        $obj1 = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getOutlet"));
        $obj1->expects($this->any())
         ->method("getOutlet")
        ->will($this->returnValue($res1));

        $obj2 = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgPremierReportById"));
        $obj2->expects($this->any())
         ->method("setVal")
        ->will($this->returnValue($res2));

        $obj2->expects($this->any())
        ->method("getOrgPremierReportById")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer",$obj1);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj2);
        $obj3 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj3->orgInfo($arrInput);
        $data   = array(
			'level'             => 2,
            'manager'           =>'zsj',
            'phone'             => '13752967124',
            'last_login'        =>'1970-01-01 08:01:51',
            'login_city'        =>null,
            'outlet_count'      => 2,
            'errorno'    		=> 1002,
            'errormsg'     =>'[参数不合法]'	
        );
        $this->assertEquals($ret,$data);
	}
	public function testformatDisplay(){
		$data = array(
			3	=>	'zsj' 
		);	
		$obj = new Service_Page_HouseReport_Index_Main();
		$ret = $obj->formatDisplay($data);
		$result = 'zsj/01-01';
		$this->assertEquals($result,$ret);
	}
	public function testhoursInfo(){
		$arrInput = array(
			'company_id'	=> 235	
		);	
		$res = true;
		$res1 = array(
			'data'	=> array(
				0	=> array(
					'count'	=> 5
				)
			)	
		);
		$obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgHoursReport"));
		$obj->expects($this->any())
			->method("setVal")
			->will($this->returnValue($res));
		$obj->expects($this->any())
			->method("getOrgHoursReport")
			 ->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj);
		$obj1 = new Service_Page_HouseReport_Index_Main(); 
		$ret =  $obj1->hoursInfo($arrInput);
		$data	= array(
			'refresh'	=> ''	
		);
		$this->assertEquals($data,$ret);
	}
	public function testhoursInfoException(){
		$arrInput = array(
            'company_id'    => 235  
        );  
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                    'count' => 5
                )   
            )   
        );  
        $obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgHoursReport"));
        $obj->expects($this->any())
            ->method("setVal")
            ->will($this->returnValue($res));
        $obj->expects($this->any())
            ->method("getOrgHoursReport")
             ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main(); 
        $ret =  $obj1->hoursInfo($arrInput);
        $data   = array(
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
        );  
        $this->assertEquals($data,$ret);	
	}
	public function testclickTrend(){
		$arrInput = array(
			'id'	=> 235
		);	
		$res = true;
		$res1 = array(
			'PREMIER'	=> array(
				1	=> 1
			),
			'ASSURE'	=> array(
				2	=> 2
			) ,
			'BID'		=> array(
				3	=> 3
			)
		);
		$res2 = true;
		$obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("setVal","getOrgClickByDay"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));	
		$obj->expects($this->any())
        ->method("getOrgClickByDay")
        ->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj);
		$obj1= $this->genObjectMock("Service_Page_HouseReport_Index_Main",array("formatDisplay"));
        $obj1->expects($this->any())
        ->method("formatDisplay")
        ->will($this->returnValue($res2));   
		$ret = $obj1->clickTrend($arrInput);
		$data = array(
			'trend'	=> true,
			'bid'	=> true
		);
		$this->assertEquals($data,$ret);
	}
	public function testclickTrendException(){
	    $arrInput = array(
            'id'    => 235
        );
        $res = true;
        $res1 = array(
            'PREMIER'   => array(
                1   => 1
            ),
            'ASSURE'    => array(
                2   => 2
            ) ,
            'BID'       => array(
                3   => 3
            )
        );
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("setVal","getOrgClickByDay"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));
        $obj->expects($this->any())
        ->method("getOrgClickByDay")
        ->will($this->returnValue($res1));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData",$obj);
        $obj1= $this->genObjectMock("Service_Page_HouseReport_Index_Main",array("formatDisplay"));
        $obj1->expects($this->any())
        ->method("formatDisplay")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        $ret = $obj1->clickTrend($arrInput);
        $data = array(
			'errorno'	=>1002,
			'errormsg'	=> '[参数不合法]'
        );
        $this->assertEquals($data,$ret);	
    }
    /*
	public function testexecute(){
		$arrInput = array(
			'id'			=> 123,
			'level'			=> 4,
			'customer_id'	=> 222,
		);	
		$res = array(
			'data'	=> array(
				'list'	=> array(
					'customer_id'	=> 333
				)
			)
		);
		$res1 = 1;
		$res2 = 2;
		$res3 = 3;
		$res4 = 4;
		$res5 = 5;
		
		$obj1 = $this->genObjectMock("Service_Page_HouseReport_Index_Main",array("accountBusinessInfo","orgInfo","bizUsage","clickTrend","hoursInfo"));
        $obj1->expects($this->any())
        ->method("accountBusinessInfo")
        ->will($this->returnValue($res1));
	
		$obj1->expects($this->any())
        ->method("orgInfo")
        ->will($this->returnValue($res2));

		$obj1->expects($this->any())
        ->method("bizUsage")
        ->will($this->returnValue($res3));
		
		$obj1->expects($this->any())
        ->method("clickTrend")
        ->will($this->returnValue($res4));
		
		$obj1->expects($this->any())
        ->method("hoursInfo")
        ->will($this->returnValue($res5));
		$ret =  $obj1->execute($arrInput);
		$data	= array(
			'data'	=> array(
				'bangbang'	=> 1,
				'orginfo'	=> 2,
				'usage'		=> 3,
				'click'		=> 4,
				'hours'		=> 5
			)
		);
		$this->assertEquals($ret,$data);


		$arrInput = array(
            'id'            => 123,
            'level'         => 2,
            'customer_id'   => 222,
        );
        $res = array(
            'data'  => array(
                'list'  => array(
                    'customer_id'   => 333
                )
            )
        );
        $res1 = 1;
        $res2 = 2;
        $res3 = 3;
        $res4 = 4;
        $res5 = 5;
   
        $obj1 = $this->genObjectMock("Service_Page_HouseReport_Index_Main",array("accountBusinessInfo","orgInfo","bizUsage","clickTrend","hoursInfo"));
        $obj1->expects($this->any())
        ->method("accountBusinessInfo")
        ->will($this->returnValue($res1));	

        $obj1->expects($this->any())
            ->method("orgInfo")
            ->will($this->returnValue($res2));

        $obj1->expects($this->any())
            ->method("bizUsage")
            ->will($this->returnValue($res3));

        $obj1->expects($this->any())
            ->method("clickTrend")
            ->will($this->returnValue($res4));

        $obj1->expects($this->any())
            ->method("hoursInfo")
            ->will($this->returnValue($res5));
        //$ret =  $obj1->execute($arrInput);
        $data   = array(
            'data'  => array(
                'bangbang'  => 1,
                'orginfo'   => 2,
                'usage'     => 3,
                'click'     => 4,
                'hours'     => 5
            )
        );
        $this->assertEquals($ret,$data);
    }*/
	public function testaccountBusinessInfo(){
		HousingVars::$bizTxt = array(
			1 =>'民宅综合'
		);
		$arrInput = array(
			'company_id'	=> 235
		);	
		$res = true;
		$res1 = array(
			'data'	=> array(
				0	=> array(
						'InDurationEndTime'	=> strtotime("+6 days midnight") ,
						'CountType'			=> 1,
					)
			)
		);
		$res2 = array(
			'data'	=> 4
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));	
		
		$obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

		$obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));	
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->accountBusinessInfo($arrInput);
		$data	= array(
			'assure_month_count'	=> 0,
			'assure_2week_count'	=> 0,
			'assure_week_count'		=> 0,
			'premier_month_count'	=> 0,
			'premier_2week_count'  => 0,
			'premier_week_count'	=> 1,
			'businessInfo'			=>array(
				1 => array(
						'data'	=> array(
						'biz_status'	=> '1,0,0,0,0,0',
						'effect_count'	=> 1,
						'next_count'	=> 4,
						'stale_count'	=> 4
					),
					'title'	=> '民宅综合'
				)
			)
		);
		$this->assertEquals($data,$ret);


		HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );
        $arrInput = array(
            'company_id'    => 235
        );
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+6 days midnight") ,
                        'CountType'         => 2,
                    )
            )
        );
        $res2 = array(
            'data'  => 4
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));
   
        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->accountBusinessInfo($arrInput);	
		$data   = array(
            'assure_month_count'    => 0,
			'assure_2week_count'	=> 0,
            'assure_week_count'     => 1,
            'premier_month_count'   => 0,
			'premier_2week_count'	=> 0,
            'premier_week_count'    => 0,
            'businessInfo'          =>array(
                1 => array(
                        'data'  => array(
                        'biz_status'    => '0.00001,1,0,0,0,0',
                        'effect_count'  => 1,
                        'next_count'    => 4,
                        'stale_count'   => 4
                    ),
                    'title' => '民宅综合'
                )
            )
        );
        $this->assertEquals($data,$ret);

        HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );	
       $arrInput = array(
            'company_id'    => 235
        );
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+14 days midnight") ,
                        'CountType'         => 1,
                    )
            )
        );
        $res2 = array(
            'data'  => 4
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));
  
        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->accountBusinessInfo($arrInput);	
		$data   = array(
            'assure_month_count'    => 0,
			'assure_2week_count'	=> 0,
            'assure_week_count'     => 0,
            'premier_month_count'   => 0,
			'premier_2week_count'	=> 1,
            'premier_week_count'    => 0,
            'businessInfo'          =>array(
                1 => array(
                        'data'  => array(
                        'biz_status'    => '0.00001,0,1,0,0,0',
                        'effect_count'  => 1,
                        'next_count'    => 4,
                        'stale_count'   => 4
                    ),
                    'title' => '民宅综合'
                )
            )
        );
        $this->assertEquals($data,$ret);

	    HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );
       $arrInput = array(
            'company_id'    => 235
        );
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+14 days midnight") ,
                        'CountType'         => 2,
                    )
            )
        );
        $res2 = array(
            'data'  => 4
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));
 
        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();	
		$ret = $obj1->accountBusinessInfo($arrInput);
        $data   = array(
            'assure_month_count'    => 0,
			'assure_2week_count'	=> 1,
            'assure_week_count'     => 0,
            'premier_month_count'   => 0,
			 'premier_2week_count'	=> 0,
            'premier_week_count'    => 0,
            'businessInfo'          =>array(
                1 => array(
                        'data'  => array(
                        'biz_status'    => '0.00001,0,0,1,0,0',
                        'effect_count'  => 1,
                        'next_count'    => 4,
                        'stale_count'   => 4
                    ),
                    'title' => '民宅综合'
                )
            )
        );
        $this->assertEquals($data,$ret);

		
        HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );  
       $arrInput = array(
            'company_id'    => 235 
        );  
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+29 days midnight") ,
                        'CountType'         => 1,
                    )   
            )   
        );  
        $res2 = array(
            'data'  => 4
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));
 
        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();  
        $ret = $obj1->accountBusinessInfo($arrInput);	
		$data   = array(
            'assure_month_count'    => 0,
            'assure_2week_count'    => 0,
            'assure_week_count'     => 0,
            'premier_month_count'   => 1,
             'premier_2week_count'  => 0,
            'premier_week_count'    => 0,
            'businessInfo'          =>array(
                1 => array(
                        'data'  => array(
                        'biz_status'    => '0.00001,0,0,0,1,0',
                        'effect_count'  => 1,
                        'next_count'    => 4,
                        'stale_count'   => 4
                    ),
                    'title' => '民宅综合'
                )
            )
        );
        $this->assertEquals($data,$ret);

       HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );
       $arrInput = array(
            'company_id'    => 235
        );
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+29 days midnight") ,
                        'CountType'         => 2,
                    )
            )
        );
        $res2 = array(
            'data'  => 4
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->returnValue($res2));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->accountBusinessInfo($arrInput);	
		$data   = array(
            'assure_month_count'    => 1,
            'assure_2week_count'    => 0,
            'assure_week_count'     => 0,
            'premier_month_count'   => 0,
             'premier_2week_count'  => 0,
            'premier_week_count'    => 0,
            'businessInfo'          =>array(
                1 => array(
                        'data'  => array(
                        'biz_status'    => '0.00001,0,0,0,0,1',
                        'effect_count'  => 1,
                        'next_count'    => 4,
                        'stale_count'   => 4
                    ),
                    'title' => '民宅综合'
                )
            )
        );
        $this->assertEquals($data,$ret);
 	}
	public function testaccountBusinessInfoException(){
		 HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );
       $arrInput = array(
            'company_id'    => 235
        );
        $res = true;
        $res1 = array(
            'data'  => array(
                0   => array(
                        'InDurationEndTime' => strtotime("+29 days midnight") ,
                        'CountType'         => 2,
                    )
            )
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("setVal","getBusinessList","getBusinessStatusCount"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getBusinessList")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getBusinessStatusCount")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->accountBusinessInfo($arrInput);	
		$data = array(
			'assure_month_count'    => 1,
			'assure_2week_count'	=> 0,
            'assure_week_count'     => 0,
            'premier_month_count'   => 0,
            'premier_week_count'    => 0,
			'premier_2week_count'	=> 0,
			'errorno'				=> 1002,
			'errormsg'				=> '[参数不合法]'
		);
 		$this->assertEquals($data,$ret);
 	}
	public function testbizUsage(){
		$arrInput = array(
			'id'	=> 123
		);		
		$res = true;
	    HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );	
		$res1 = array(
			'data'	=> array(
				'list'	=> array(
					0	=> array(
						 3 => 3	
					)
				)
			)
		);
		$res2 = array(
           'data'  => array(
                'list'  => array(
                    0   => array(
                         3 => 3
                    )
                )
            )	
		);
		$obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgPremierReportById","getOrgAssureReportById"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));

	    $obj->expects($this->any())
        ->method("getOrgPremierReportById")
        ->will($this->returnValue($res1));
	
		  $obj->expects($this->any())
        ->method("getOrgAssureReportById")
        ->will($this->returnValue($res2)); 

		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->bizUsage($arrInput);
		$data = array(
			'premier'	=> array(
				1	=> array(
						'title'	=>  "民宅综合",
						'data'	=> array(
							3	=> 3
						)
					)
			),
          'assure'   => array(
                1   => array(
                        'title' =>  "民宅综合",
                        'data'  => array(
                            3 => 3
                        )
                    )
            ),	
	        'collect'   => array(
                1   => array(
                        'title' =>  "民宅综合",
                        'data'  => array(
                            3 => "3/3"
                        )
                    )
            ),	
		);
		$this->assertEquals($ret,$data);
	}
	public function testbizUsageException(){
	  $arrInput = array(
            'id'    => 123
        );
        $res = true;
        HousingVars::$bizTxt = array(
            1 =>'民宅综合'
        );
        $res1 = array(
            'data'  => array(
                'list'  => array(
                    0   => array(
                         3 => 3
                    )
                )
            )
        );
		$obj = $this->genObjectMock("Service_Data_HouseReport_OrgReport",array("setVal","getOrgPremierReportById","getOrgAssureReportById"));
        $obj->expects($this->any())
        ->method("setVal")
        ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getOrgPremierReportById")
        ->will($this->returnValue($res1));

          $obj->expects($this->any())
        ->method("getOrgAssureReportById")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_OrgReport",$obj);
        $obj1 = new Service_Page_HouseReport_Index_Main();
        $ret = $obj1->bizUsage($arrInput);
		$data = array(
          'premier'   => array(
                1   => array(
                        'title' =>  "民宅综合",
                        'data'  => array(
                            3   => 3
                        )
                    )
            ),	
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);
	}
} 
