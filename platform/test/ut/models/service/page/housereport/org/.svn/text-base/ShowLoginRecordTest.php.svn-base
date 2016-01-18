<?php
class ShowLoginRecordTest extends Testcase_PTest{
	 protected function setUp(){
		 Gj_LayerProxy::$is_ut = true;
	 }
	 public function testexecute(){
		$arrInput = array(
				'date' => array(
					'sDate' => '2014-10-24',
					 'edate'=> '2014-10-25'
				 ),
			     'page' => null,
				'account_id' => '235',
				'arrFields' => array('loging_time','ip','city')		
			);
			$res = array (
				'data' => 36,
				'errorno' => 0,
				'errormsg' =>'[数据返回成功]'
			);
			$res1 = array(
						'data' => array (array(
											'loging_time' => '1414116883',
											'ip'          => '2147483647',
											'city'		=> '未获取到'
						)),
					 'errorno' => 0, 
					 'errormsg' => '[数据返回成功]'  
			);
			 $obj = $this->genObjectMock('Service_Data_Gcrm_CustomerAccountLoginEvent',array("getCustomerLoginCount","getCustomerLoginList"));
			 $obj ->expects( $this->any())
				 ->method('getCustomerLoginCount')
				->will($this->returnValue($res));	 
			  $obj ->expects( $this->any())   
				  ->method('getCustomerLoginList')
				  ->will($this->returnValue($res1));  
				Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccountLoginEvent",$obj);
				$orgObj = new Service_Page_HouseReport_Org_ShowLoginRecord(); 
				$ret = $orgObj->execute($arrInput);   
				$data = array(
					'data' => array(
						'dataList' => array(
							'data' => array(
								array(
									'loging_time'=> '2014-10-24 10:14:43',
									'ip' => '127.255.255.255',
									'city' => '未获取到'
								)
							),
							'count' => array(
								'data' => 36,
								'errorno' => 0,
								'errormsg' => '[数据返回成功]'
							)
						)
					)
				);
				$this->assertEquals($data,$ret); 
	}
} 
