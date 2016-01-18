<?php
class   GroupOutletDataTest extends Testcase_PTest{
    protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
    }

    public function testgetBusinessScopeByOutlet(){
        $obj = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOutletData');
        $indexFieldName = 'account_id';
        $accountId = 123;
        $data = array(
            2=> array(
                    $indexFieldName=>$accountId
                ),
            3=> array(
                $indexFieldName=>$accountId
            ),
        );
        $result = array (
            123 =>
                array (
                    'account_id' => 123,
                    'biz_text' => '商铺写字楼/民宅租赁'
                ),
        );
        $ret = $obj->getBusinessScopeByOutlet($data);
       $this->assertEquals($result,$ret);
    }

    public function testgroupAccountUseBusinessScope(){
        $params = array(
            'businessScope'=>1,
            'countType'=>array(1),
            'date'=>array('sDate'=>'2014-12-04'),

        );
        $res = array(
            'errorno'=>0,
            'data'=>array(
                array(
                    'BussinessScope'=>1,
                    'AccountId'=>123,
                	'UserId'=>123,
                    'InDurationBeginTime'=>1417622400,
                    'InDurationEndTime'=>1417622400,
                    'MaxFreeRefreshCount'=>100,
                    'MaxChargeRefreshCount'=>50)
            )
        );
        $result = array (
            1 =>
                array (
                    123 =>
                        array (
                            'account_id' => 123,
                        	'user_id' => 123,
                            'inDurationBeginTime' => 1417622400,
                            'inDurationEndTime' => 1417622400,
                            'last_deposit_time' => '2014-12-04',
                            'premier_end_time' => '2014-12-04',
                            'max_freerefresh_count' => 100,
                            'max_refresh_count' => 50,
							'online_premier' => null,
                        ),
                ),
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getBusinessInfoByAccountIds"));
        $obj->expects( $this->any())
            ->method("getBusinessInfoByAccountIds")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo", $obj);
        $obj = new Service_Data_HouseReport_GroupOutletData();
        $ret = $obj->groupAccountUseBusinessScope($params);
        $this->assertEquals($result,$ret);
    }

	/*public function testgetBusinessScope(){
		$params = array(
			 'businessScope'=> 1,
			  'accountIds'	=> array(123,234)
		);
		$res = array(
				'data'	=> array(
					array(
						'BussinessScope'	=> 1,
						'AccountId'			=> 123,
						'CountType'			=> 1
						)

				)
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",array("getBusinessInfoByAccountIds"));
		$obj->expects( $this->any())
		->method("getBusinessInfoByAccountIds")
		->will($this->returnValue($res));
		 Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo", $obj);
		$orgObj = new Service_Data_HouseReport_GroupOutletData();
		$data = array(
				'data'	=> array(
					123	=> array(
						"account_id"			=> 123,
						"business_scope_str"	=> '民宅综合'
					)
				)
		);
		$ret = $orgObj->getBusinessScope($params);
		$this->assertEquals($data,$ret);
	}*/
	public function testgetAssureData(){
        $accountList = array();
        $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("setVal","getAccountAssureReportList"));
        $obj->expects( $this->any())
        ->method('setVal')
        ->will($this->returnValue($res));
        $obj->expects( $this->any())
        ->method('getAccountAssureReportList')
        ->will($this->returnValue($res1));

        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("getUserBusinessInfo","changeData","mergeReportAndUserInfo"));
        $obj1->expects( $this->any())
        ->method('getUserBusinessInfo')
        ->will($this->returnValue($res2));
        $obj1->expects( $this->any())
        ->method('changeData')
          ->will($this->returnValue($res3));
        $obj1->expects($this->any())
        ->method('mergeReportAndUserInfo')
        ->will($this->returnValue($res4));

    Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
    Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);

	}
	/*public function testgetPremierData(){
		     $params = array(
            'date'      => array(

                'sDate' =>  '2016-11-06',
                'eDate' => '2016-11-08'
            ),
            'houseType' => 1
        );
        $res = true;
        $res1 = array(
               'data'  => array(
                    '1002'  => array(1),
                    '1003'  => array(2)
                )
        );
        $res2 = array(
            'userBusiness'  => array(
                '1' => '名宅综合'
            )
        );
        $res3 = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )
        );
        $res4 = array(
                'data'  => array(
                    '1002'  => array(1),
                    '1003'  => array(2)
                ),
             'userBusiness'  => array(
                '1' => '名宅综合'
            )
        );	
		$obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("setVal","getAccountPremierReportList"));
            $obj->expects( $this->any())
            ->method('setVal')
            ->will($this->returnValue($res));
            $obj->expects( $this->any())
            ->method('getAccountAssureReportList')
            ->will($this->returnValue($res1));
   
            $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("getUserBusinessInfo","changeData","mergeReportAndUserInfo"));
            $obj1->expects( $this->any())
            ->method('getUserBusinessInfo')
            ->will($this->returnValue($res2));
            $obj1->expects( $this->any())
            ->method('changeData')
              ->will($this->returnValue($res3));
            $obj1->expects($this->any())
            ->method('mergeReportAndUserInfo')
            ->will($this->returnValue($res4));
   
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);
        $orgObj = new Service_Data_HouseReport_GroupOutletData();
        $data = array(
                'data'  => array(
                        '1002'  => array(1),
                        '1003'  => array(2)
                ),
                'userBusiness' => array(
                     '1' => '名宅综合'
            )
        );
        $ret = $orgObj->getPremierData($params);
        $this->assertEquals($data,$ret);
	}*/
	/*public function testgetBidData(){
		 $params = array(
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
		    $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("getAccountPremierReportList","setVal"));
            $obj->expects( $this->any())
            ->method('getAccountPremierReportList')
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
        $orgObj = new Service_Data_HouseReport_GroupOutletData();
        $ret = $orgObj->getBidData($params);
        $data = array(
            'data'  => array(
                '1002'  => array(1),
                '1003'  => array(2)
            )
        );
        $this->assertEquals($data,$ret);	
	}*/
	public function testgetFrameData(){

        $params = array();

        $data = array();
        $premierData = array(array(1));
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getPremierData"));
        $obj->expects( $this->any())
        ->method("getPremierData")
         ->will($this->returnValue($premierData));
        $ret = $obj->getFrameData($params,$data);
        $this->assertEquals($premierData,$ret);

        $data = array(
            Service_Data_HouseReport_GroupData::PREMIER=>array(1),
        );

        $obj = new Service_Data_HouseReport_GroupOutletData();
        $ret = $obj->getFrameData($params,$data);
        $this->assertEquals($data[Service_Data_HouseReport_GroupData::PREMIER],$ret);

	}
	public function testgetAccountId(){
		$params = array(
			'accountIds'	=> array(123,234),
            'domain'=>'bj'
		);
		$data[21]['data'] = array(
					'123'  => array('account_id'=>123,),
					'234'  => array('account_id'=>234),
		);	
		$obj = new Service_Data_HouseReport_GroupOutletData();
		$result = array (
            'data' =>
                array (
                    123 =>
                        array (
                            'account_id' => 123,
                            'accountid_show' => "<a target='_blank' href='http://bj.ganji.com/fang_123/'>123</a>",
                        ),
                    234 =>
                        array (
                            'account_id' => 234,
                            'accountid_show' => "<a target='_blank' href='http://bj.ganji.com/fang_234/'>234</a>",
                        ),
                ),
        );
		$ret = $obj->getAccountId($params,$data);
        $this->assertEquals($ret,$result);
    }

	public function testgetFrameAuditingData(){
           $params = array();
           $data = array();
		   $pramierData = array(
            'data'  => array(
                    '123'   => array(
                        'account_id'=>123,
                        'similar_house_count' => 1,
                        'illegal_house_count' => 2,
                        'comment_count'       => 3
                    ),
                   '124'   => array(
                       'account_id'=>124,
                       'similar_house_count' => 1,
                       'illegal_house_count' => 2,
                       'comment_count'       => 3
                   ),

            ),
               'count' => 2
        );

        $assureData = array(
            'data'  => array(
                '123'   => array(
                    'account_id'=>123,
                    'similar_house_count' => 1,
                    'illegal_house_count' => 2,
                    'comment_count'       => 3
                ),
            ),
             'count' => 1
        );

        $bidData = array(
            'data'  => array(
                '123'   => array(
                    'account_id'=>123,
                    'similar_house_count' => 1,
                    'illegal_house_count' => 2,
                    'comment_count'       => 3
                ),
            ),
              'count' => 1
        );

        $result = array(
             'data'  => array(
                '123'   => array(
                    'account_id'=>123,
                    'similar_house_count' => 3,
                    'illegal_house_count' => 6,
                    'comment_count'       => 9
                        ),
                 '124'   => array(
                     'account_id'=>124,
                     'similar_house_count' => 1,
                     'illegal_house_count' => 2,
                     'comment_count'       => 3
                 ),
             ),
             'count' => 2
        );	
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getPremierData","getAssureData",'getBidData'));
        $obj->expects( $this->any())
        ->method('getPremierData')
        ->will($this->returnValue($pramierData));

        $obj->expects( $this->any())
        ->method('getAssureData')
        ->will($this->returnValue($assureData));

        $obj->expects( $this->any())
            ->method('getBidData')
            ->will($this->returnValue($bidData));
        $ret = $obj->getFrameAuditingData($params,$data);
        $this->assertEquals($ret,$result);

    }
	/*public function testgetTotalData(){
	      $arrInput = array(
                1 => array(
                    'data'  => array(
                            123 => array(
                                'house_total_count' => 1,
                                'house_count'         => 2,
                                'refresh_count'   => 3,
                                'account_pv'          => 4,
                                'premier_count'   => 5,
                                'max_premier_count'=> 6
                            )   
                        ),  
                    ),  
        2 => array(
                    'data'  => array(
                             '123' => array(
                                'assure_house_total_count' => 1,
                                'assure_house_count'       => 2,
                                'assure_refresh_count'     => 3,
                                'assure_account_pv'        => 4,
                                'assure_premier_count'     => 5,
                                'max_premier_count'        => 6
                            )   
                  ),  
                )   
        );  
	    $orgObj = new  Service_Data_HouseReport_GroupOutletData();
        $ret = $orgObj->getTotalData($arrInput);
               $data =  array(
            'data'  => array(
                123 => array(
                    'account_id' => null,
                    'house_total_count'   => 1,
                    'house_count'         => 2,
                    'refresh_count'       => 3,
                    'account_pv'          => 4,
                    'premier_count'       => 5,
                    'premier_scale'             => '41.7%',
                    'refresh_scale'             =>'0%'
                )
            )
        );
         $this->assertEquals($data,$ret);

                 $arrInput = array(
                1 => array(
                    'data'  => array(),
                    ),
        2 => array(
                    'data'  => array( ),
                )
        );
        $orgObj = new  Service_Data_HouseReport_GroupOutletData();
        $ret = $orgObj->getTotalData($arrInput);
           $data =  array();
        $this->assertEquals($data,$ret);	
    }*/

	public function testgroupAjaxData(){
		$tags = array(
            Service_Data_HouseReport_GroupData::PREMIER,
            Service_Data_HouseReport_GroupData::ASSURE,
            Service_Data_HouseReport_GroupData::BID,
            Service_Data_HouseReport_GroupData::TOTAL,
            Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
            Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
        );

        $params = array();

        $ollData = array(1);

		$groupData = array(1);
       $groupDataObj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("isShowTotal","getTotalData",'getBusinessScope','mergeData'));
        $groupDataObj->expects( $this->any())
        ->method('isShowTotal')
        ->will($this->returnValue($groupData));
        $groupDataObj->expects( $this->any())
            ->method('getTotalData')
            ->will($this->returnValue($groupData));
        $groupDataObj->expects( $this->any())
            ->method('getBusinessScope')
            ->will($this->returnValue($groupData));
        $groupDataObj->expects( $this->any())
            ->method('mergeData')
            ->will($this->returnValue($groupData));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $groupDataObj);
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",
            array("getPremierData","getAssureData","getBidData","getFrameData","getFrameAuditingData","getSortCategory","getAccountId","formartData","getCrmAccountEmail","getUserCenterBalance","getBidBalance"));
        //--取数据
        $obj->expects( $this->any())
        ->method('getPremierData')
        ->will($this->returnValue($ollData));
          $obj->expects( $this->any())
        ->method('getAssureData')
        ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
        ->method('getBidData')
        ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
        ->method('getFrameData')
        ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
        ->method('getFrameAuditingData')
        ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
            ->method('getFrameAuditingData')
            ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
            ->method('getAccountId')
            ->will($this->returnValue($ollData));

        $obj->expects( $this->any())
            ->method('getAccountId')
            ->will($this->returnValue($ollData));
        
        $obj->expects( $this->any())
        ->method('getCrmAccountEmail')
        ->will($this->returnValue($ollData));
        
        $obj->expects($this->any())
            ->method('getUserCenterBalance')
            ->will($this->returnValue(array()));

        $obj->expects($this->any())
            ->method('getBidBalance')
            ->will($this->returnValue(array()));
        $ret = $obj->groupAjaxData($tags,$params);

        $this->assertEquals($ollData,$ret);
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
            $objExp = new Service_Data_HouseReport_GroupOutletData();
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
            $objExp = new Service_Data_HouseReport_GroupOutletData();
            $ret = $objExp->groupAjaxData($tags,$params);
   }

    public function testgetReportDate(){
        $params = array(
            'accountIds'=>array(1),
            'date'=>array(
                'sDate'=>'2014-12-5',
                'eDate'=>'2014-12-5',
            )
        );
        $data[1]['data'] = array(
        		'123'  => array('account_id'=>123,),
        );
        $result = array(
            'data'=>
            array(
                123 => array(
                    'account_id'=>123,
                    'report_start_time'=>'2014-12-5',
                    'report_end_time'=>'2014-12-5',
                ),
            ),
        );
        $objExp = new Service_Data_HouseReport_GroupOutletData();
        $ret = $objExp->getReportDate($params,$data);
        $this->assertEquals($result,$ret);
    }

    public function testgetfatherOrgId(){
        $ret = array('data'=>array(array('id'=>123)));
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoListByPid"));
        $obj->expects( $this->any())
            ->method('getOrgInfoListByPid')
            ->will($this->returnValue($ret));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $objExp = new Service_Data_HouseReport_GroupOutletData();
        $res = $objExp->getfatherOrgId(835);
        $this->assertEquals($res,123);
    }

    public function testformartData(){
        $dataList = array(
            array('data'=>array(array('premier_scale'=>0.1,'refresh_scale'=>'0.2')))
        );
        $ret = array(
            array('data'=>array(array('premier_scale'=>'10%','refresh_scale'=>'20%'),))
        );

        $objExp = new Service_Data_HouseReport_GroupOutletData();
        $res = $objExp->formartData($dataList);
        $this->assertEquals($res,$ret);
    }

    public function testgetBidData(){
        $params = array(
            'date'=>array(
                'sDate'=>'2014-12-11',
                'eDate'=>'2014-12-11',
            )
        );
        $ret = array(2=>array(array(123)));
        $obj = $this->genObjectMock("Service_Data_HouseReport_AccountReport",array("getAccountPremierReportList"));
        $obj->expects( $this->any())
            ->method('getAccountPremierReportList')
            ->will($this->returnValue($ret));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_AccountReport", $obj);
        $result = array('data'=>array(123));
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("changeData"));
        $obj1->expects( $this->any())
            ->method('changeData')
            ->will($this->returnValue($result));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj1);
        $obj2 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAccountUseBusinessScope"));
        $obj2->expects( $this->any())
            ->method('groupAccountUseBusinessScope')
            ->will($this->returnValue($ret));
         $res =  $obj2->getBidData($params);
        $this->assertEquals($res,$result);
    }
	public function testgetUserCenterBalanceMoney(){
		$userIds = array(123);
		$res = array('123' => array('userCenterBalance' => 1, 'userCenterAwardBalance' => 2, 'userCenterCashBalance' => 3));
        $obj = $this->genObjectMock("Dao_Gcrm_Interface_UserInterface",array("getBatchAccount"));
        $obj->expects( $this->any())
            ->method('getBatchAccount')
            ->will($this->returnValue($res));

        Gj_LayerProxy::registerProxy("Dao_Gcrm_Interface_UserInterface", $obj);
		$obj1 = new Service_Data_HouseReport_GroupOutletData();
		$ret = $obj1->getUserCenterBalanceMoney($userIds);
		$result = array('123' => array('userCenterBalance' => 1, 'userCenterAwardBalance' => 2, 'userCenterCashBalance' => 3));
		$this->assertEquals($ret,$result);
	}
	public function testgetUserCenterBalance(){
		$params = array(
			'accountIds' => 123
		);
		$data = array(
			'21' => array(
				'data' => array(
					0 => array(
						'account_id' => 123
					)
				)
			)	
		);	
		$res = array(
			0 => array(
				'AccountId' => 123,
				'UserId'    => 321
			)	
		);
		$res1 = array(
            '321' => array('userCenterBalance' => 1, 'userCenterAwardBalance' => 2, 'userCenterCashBalance' => 3),					
		);
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount",array("selectAllInfo"));
        $obj->expects( $this->any())
            ->method('selectAllInfo')
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);


        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getUserCenterBalanceMoney"));
        $obj1->expects( $this->any())
            ->method('getUserCenterBalanceMoney')
            ->will($this->returnValue($res1));
		$ret = $obj1->getUserCenterBalance($params,$data);
        $result = array(
			'data'	=> array(0 =>array('account_id' => 123,'userCenterAwardBalance' => 2,'userCenterCashBalance' =>3))
		);
        $this->assertEquals($ret,$result);

		$params = array(
			'accountIds' => 123
		);
		$data = array(
			'21' => array(
				'data' => array(
					0 => array(
						'account_id' => 123
					)
				)
			)	
		);	
		$res = array(
			0 => array(
				'AccountId' => 123,
				'UserId'    => 321
			)	
		);
		$res1 = array(
			'321' => 24					
		);
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount",array("selectAllInfo"));
        $obj->expects( $this->any())
            ->method('selectAllInfo')
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);


        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getUserCenterBalanceMoney"));
        $obj1->expects( $this->any())
            ->method('getUserCenterBalanceMoney')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		$ret = $obj1->getUserCenterBalance($params,$data);
		$result = array(
			'errorno'	=> 1002,
			'errormsg'  => '[参数不合法]'
		);
		$this->assertEquals($ret,$result);
	}
	public function testgetBalanceMoney(){
		$userIds = array(
			0 => 123
		);	
		$params = array('city_code' => 100);
		$res = 24;
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getBalanceMoney"));
        $obj1->expects( $this->any())
            ->method('getBalanceMoney')
            ->will($this->returnValue($res));
		$ret = $obj1->getBalanceMoney($params,$data);
		$result = 24;
		$this->assertEquals($ret,$result);
	}
	public function testgetBidBalance(){
		$params = array(
			'accountIds' => 123
		);	
		$data = array(
			'21' => array(
				'data' => array(
					0 => array(
						'account_id' => 123
					)
				)
			)	
		);	
		$res = array(
			0 => array(
				'AccountId' => 123,
				'UserId'    => 321
			)	
		);
		$res1 = array(
			'321' => 24					
		);
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount",array("selectAllInfo"));
        $obj->expects( $this->any())
            ->method('selectAllInfo')
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);

        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getBalanceMoney"));
        $obj1->expects( $this->any())
            ->method('getBalanceMoney')
            ->will($this->returnValue($res1));
		$ret = $obj1->getBidBalance($params,$data);
		$result = array('data' => array(0 => array('account_id' =>'123','userBidBalance' => 24)));
		$this->assertEquals($ret,$result);
	}
	public function testgetBidBalanceException1(){
			$params = array(
			'accountIds' => 123
		);	
		$data = array(
			'21' => array(
				'data' => array(
					0 => array(
						'account_id' => 123
					)
				)
			)	
		);	
		$res = array(
			0 => array(
				'AccountId' => 123,
				'UserId'    => 321
			)	
		);
        $obj = $this->genObjectMock("Dao_Gcrm_CustomerAccount",array("selectAllInfo"));
        $obj->expects( $this->any())
            ->method('selectAllInfo')
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_CustomerAccount", $obj);

        $obj1 = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("getBalanceMoney"));
        $obj1->expects( $this->any())
            ->method('getBalanceMoney')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		$ret = $obj1->getBidBalance($params,$data);
		$result = array('errorno' => 1002, 'errormsg' => '[参数不合法]');
		$this->assertEquals($ret,$result);
	}
}
