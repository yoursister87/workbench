<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Customer extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('id'=>123,'company_id'=>45888));
		$this->arrFields = array("CustomerId","FullName","CompanyId","CompanyName","PostAddress","Postcode","Email","OfficePhone","CellPhone","IM","Fax","Level","SourceType","SaleGroup");
	}
	public function testGetCustomerInfoListByCompanyId(){
		$orderArr = array('DESC'=>'CreatedTime');
		$arrConds = array('company_id ='=>835);
        $arrFields = array('id as CustomerId', 'full_name as FullName', 'company_id as CompanyId', 'company_name as CompanyName');
		$res = array();
		$result = array(
			'data'	=> array(
				0	=> array(
					'id'=>123,
					'company_id'=>45888
				)
			)
		);
		$obj = $this->genObjectMock("Dao_Gcrm_Customer", array("selectCustomerByPage"));
		$obj->expects($this->any())
		->method('selectCustomerByPage')
		->will($this->returnValue($this->result));
		 $obj1 = $this->genObjectMock("Service_Data_Gcrm_Customer", array("getCustomerWhere","fieldNameRevert"));
		 $obj1->expects($this->any())
        ->method('getCustomerWhere')
        ->will($this->returnValue($arrConds));
		 $obj1->expects($this->any())
        ->method('fieldNameRevert')
        ->will($this->returnValue($res1));	
		Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $obj);
		$res = $obj1->getCustomerInfoListByCompanyId(array('CompanyId'=>835), $this->arrFields,1,30,$orderArr);
		$this->assertEquals($result,$res);


	    $orderArr = array('DESC'=>'CreatedTime');
		$arrConds = array('company_id ='=>835);
        $arrFields = array('id as CustomerId', 'full_name as FullName', 'company_id as CompanyId', 'company_name as CompanyName');
		$res = false;
		$res1 = array();
        $obj = $this->genObjectMock("Dao_Gcrm_Customer", array("selectCustomerByPage","getLastSQL"));
        $obj->expects($this->any())
        ->method('selectCustomerByPage')
        ->will($this->returnValue($res));

		 $obj1 = $this->genObjectMock("Service_Data_Gcrm_Customer", array("getCustomerWhere","fieldNameRevert"));
         $obj1->expects($this->any())
        ->method('getCustomerWhere')
        ->will($this->returnValue($arrConds));
         $obj1->expects($this->any())
        ->method('fieldNameRevert')
        ->will($this->returnValue($res1));  	
        Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $obj);
        $res = $obj1->getCustomerInfoListByCompanyId(array('CompanyId'=>835), $this->arrFields,1,30,$orderArr);
		$data = array(
			'errorno'	=> '1003',
			'errormsg'	=> '[SQL语句执行失败]',
			'data'		=> false
		);	
        $this->assertEquals($data,$res);
 	}
	public function testGetCustomerCountByCompanyId(){
		$arrConds = array('company_id ='=>835);
		$obj = $this->genObjectMock("Dao_Gcrm_Customer", array("selectCustomerByCount"));
		$obj->expects($this->any())
		->method('selectCustomerByCount')
		->with($arrConds)
		->will($this->returnValue(1));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $obj);
		$Cusobj = new Service_Data_Gcrm_Customer();
		$res = $Cusobj->getCustomerCountByCompanyId(array('CompanyId'=>835));
		$this->data['data'] = 1;
		$this->assertEquals($this->data,$res);
	}
	public function testGetChannelCoustomerInfo(){
		$arrConds = array('customer_id ='=>835);
		$obj = $this->genObjectMock("Dao_Gcrm_ChannelCustomerExtend", array("selectInfoByCustomerId"));
		$obj->expects($this->any())
		->method('selectInfoByCustomerId')
		->with($arrConds)
		->will($this->returnValue($this->result));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_ChannelCustomerExtend", $obj);
		$Cusobj = new Service_Data_Gcrm_Customer();
		$res = $Cusobj->getChannelCoustomerInfo(835);
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);


		$arrConds = array('customer_id ='=>835);
        $obj = $this->genObjectMock("Dao_Gcrm_ChannelCustomerExtend", array("selectInfoByCustomerId","getLastSQL"));
		$res = false;
        $obj->expects($this->any())
        ->method('selectInfoByCustomerId')
        ->with($arrConds)
        ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_ChannelCustomerExtend", $obj);
        $Cusobj = new Service_Data_Gcrm_Customer();
        $res = $Cusobj->getChannelCoustomerInfo(835);
		$data = array(
			'errorno'	=> 1003,
			'errormsg'	=> '[SQL语句执行失败]',
			'data'		=> false
		);
        $this->assertEquals($data,$res);	

	}
	public function testgetCustomerInfoByCustomerId(){
			$arrInput = array(
				'CustomerId ='	=> 123
			);
			$res = array(
					0 => array(
					'CustomerId'	=> 123,
					'CompanyId'		=> 235,
					'CompanyName'	=> '赶集测试公司'
				)
			);
			$obj =  $this->genObjectMock("Dao_Gcrm_Customer",array("select"));
			 $obj->expects($this->any())
			 ->method('select')
			 ->will($this->returnValue($res));		
			 Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $obj);
			 $Cusobj = new Service_Data_Gcrm_Customer();
			 $res = $Cusobj->getCustomerInfoByCustomerId($arrInput);
			 $data = array(
						'data'	=> array(
							'CustomerId'    => 123,
							'CompanyId'     => 235,
							'CompanyName'   => '赶集测试公司',
										),
					'errorno'		=> 0,
					'errormsg'		=> '[数据返回成功]',
			);
			$this->assertEquals($data,$res);

			$arrInput = array(
                'CustomerId ='  => 123
            );
            $res = false;
            $obj =  $this->genObjectMock("Dao_Gcrm_Customer",array("select","getLastSQL"));
             $obj->expects($this->any())
             ->method('select')
             ->will($this->returnValue($res));
             Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $obj);
             $Cusobj = new Service_Data_Gcrm_Customer();
             $res = $Cusobj->getCustomerInfoByCustomerId($arrInput);
             $data = array(
                    'errorno'       => 1003,
                    'errormsg'      => '[SQL语句执行失败]',
					'data'			=> array()
            );
            $this->assertEquals($data,$res);
	}
	public function testgetOutlet(){
		$company_id = 835;
		$whereCondsOrg = array(
				'company_id' => $company_id,
				'level' => 4
		);
		$arrFields = array('customer_id');
		$orgCustomerRes = array (
				'data'=>array(
						array ( 'customer_id' => '3185')
				),
				'errorno'=>0,
				'errormsg'=>'[数据返回成功]',
		);
		$obj1 =  $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoListByPid"));
		$obj1->expects($this->any())
		->method('getOrgInfoListByPid')
		->with($whereCondsOrg, $arrFields, 1, NULL)
		->will($this->returnValue($orgCustomerRes));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj1);
		$customerRes = array (
				'data'=>array(
					array ( 'CustomerId' => '3184', 'CompanyId' => '835', 'FullName' => '北京我爱我家房地产-紫竹花园店', ),
					array ( 'CustomerId' => '3185', 'CompanyId' => '835', 'FullName' => '北京我爱我家房地产-洋桥店', ),
					array ( 'CustomerId' => '3186', 'CompanyId' => '835', 'FullName' => '北京我爱我家房地产-亚运村店', ),
					array ( 'CustomerId' => '3187', 'CompanyId' => '835', 'FullName' => '北京我爱我家房地产-新华联店', )
				),
				'errorno'=>0,
				'errormsg'=>'[数据返回成功]',
		);
		$whereConds=array('CompanyId'=>$company_id);
        $cuDsFields = array("id as CustomerId", 'company_id as CompanyId', 'full_name as FullName');
		//调用本类时候加参数去掉默认的构造方法
		$obj =  $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoListByCompanyId"),array(),'',true);
		$obj->expects($this->any())
		->method('getCustomerInfoListByCompanyId')
		->with($whereConds, $cuDsFields, 1, NULL)
		->will($this->returnValue($customerRes));
		$result = $obj->getOutlet($company_id);
		$returnData = array(
				'data' => array(
                    $customerRes['data'][0],
                    $customerRes['data'][2],
                    $customerRes['data'][3],
                    ),
				'errorno'=>0,
				'errormsg'=>'[数据返回成功]',
		);
		$this->assertEquals($returnData,$result);
	}
	public function testGetCustomerWhere(){
		$whereConds = array(
				'CompanyId'	=> 835
		);
		$obj = new Service_Data_Gcrm_Customer();
		$ret = $obj->getCustomerWhere($whereConds);
		$data = array(
				'company_id ='    => 835,
		);
		$this->assertEquals($data,$ret);
		
		$whereConds = array(
				'CompanyId'	=> 835,
				'FullName'=>'赶集'
		);
		$obj = new Service_Data_Gcrm_Customer();
		$ret = $obj->getCustomerWhere($whereConds);
		$data = array(
				'company_id ='    => 835,
				0			  => "full_name like '%赶集%'"
		);
		$this->assertEquals($data,$ret);
	}
    public function testGetGroupInfoByUserIdOrAccountId(){
        $arrAccountFields = array('GroupId', 'UserId', 'AccountId');
        $this->data['data'] = array(array('GroupId'=>3056,'UserId'=>333467,'AccountId'=>2210));
        $returnData = array(
            'errorno'  => ErrorConst::E_SQL_FAILED_CODE,
            'errormsg' => ErrorConst::E_SQL_FAILED_MSG
        );
        /*$obj =  $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoByUserId","getAccountInfoById"));
        $obj->expects($this->any())
            ->method('getAccountInfoByUserId')
            ->with($this->data['data'][0]['UserId'], $arrAccountFields)
            ->will($this->returnValue($returnData));

        $obj->expects($this->any())
            ->method('getAccountInfoById')
            ->with($this->data['data'][0]['AccountId'], $arrAccountFields)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $obj);

        $objCustomer = new Service_Data_Gcrm_Customer();
        $ret = $objCustomer->getGroupInfoByUserIdOrAccountId($this->data['data']['UserId']);
        $this->assertEquals($returnData,$ret);*/


        $obj =  $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoByUserId","getAccountInfoById"));
        $obj->expects($this->any())
            ->method('getAccountInfoByUserId')
            ->with($this->data['data'][0]['UserId'], $arrAccountFields)
            ->will($this->returnValue($this->data));

        $obj->expects($this->any())
            ->method('getAccountInfoById')
            ->with($this->data['data'][0]['AccountId'], $arrAccountFields)
            ->will($this->returnValue($this->data));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $obj);

        $whereConds = array(
            'GroupId' => array($this->data['data'][0]['GroupId']),
        );
        $arrFields = array("id","full_name","city_id","district_id","street_id","company_id","company_name");
        $returnCustomerData = array(
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
            'data'     =>  array(
                array(
                    'id' =>  '3056',
                    'full_name' =>  '住臣地产-阳春光华店',
                    'city_id' =>  '12',
                    'district_id' =>  '0',
                    'street_id' =>  '0',
                    'company_id' =>  '1128',
                    'company_name' =>  '北京住臣房地产经纪有限公司'
                )
            )
        );
        //var_dump($whereConds, $arrFields);
        $objCustomer =  $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoListByCompanyId"),array(),'',true);
        $objCustomer->expects($this->any())
            ->method('getCustomerInfoListByCompanyId')
            ->with($whereConds, $arrFields)
            ->will($this->returnValue($returnCustomerData));
        $ret = $objCustomer->getGroupInfoByUserIdOrAccountId($this->data['data'][0]['UserId']);
        $this->data['data'] = array(array_merge($this->data['data'][0], $returnCustomerData['data'][0]));

        $this->assertEquals($this->data,$ret);
    }

	/**
	 * @author 刘海鹏 <liuhaipeng1@ganji.com>
	 * @create 2015-07-17
	 */
    public function testGetCustomerInfoByGroupId() {
		$arrConds = array('id' => 3056);
		$arrFields = array('full_name', 'city_id');

		// 成功的情况
		$retVal = array(
			array('full_name' => '住臣地产-阳春光华店', 'city_id' => 12)
		);
		$objCustomer = $this->genObjectMock("Dao_Gcrm_Customer", array("select"));
		$objCustomer->expects($this->any())
			->method('select')
			->will($this->returnValue($retVal));
		Gj_LayerProxy::registerProxy('Dao_Gcrm_Customer', $objCustomer);

		$objCustomer = new Service_Data_Gcrm_Customer();
		$res = $objCustomer->getCustomerInfoByGroupId($arrConds, $arrFields);

		$this->data['data'] = $retVal[0];
		$this->assertEquals($this->data, $res);


		// 失败的情况
		$retVal = false;
		$objCustomer = $this->genObjectMock("Dao_Gcrm_Customer", array("select", "getLastSQL"));
		$objCustomer->expects($this->any())
			->method('select')
			->will($this->returnValue($retVal));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_Customer", $objCustomer);

		$objCustomer = new Service_Data_Gcrm_Customer();
		$res = $objCustomer->getCustomerInfoByCustomerId($arrConds, $arrFields);

		$this->data = array('errorno' => 1003, 'errormsg' => '[SQL语句执行失败]', 'data' => array());
		$this->assertEquals($this->data, $res);
	}
}
