<?php
class SetAgentInfoTest extends Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     } 	
    public function testexecute(){
		$arrInput = array(
			'AccountId'				=> 31,
			'UserId'				=> 1937656,
			'AccountName'			=> '陈丽丽',
			'ICImage'				=> '123',
			'BusinessCardImage' 	=> '234',
			'Picture'				=> '/customer/picture/2009/0301/12/1235880510_8785.jpg',
			'CompanyProof'			=> '公司' ,
			'ICNo'					=>null,
			'CreatorId'				=> 333,
			'CreatorName'			=> 'zsj'
		);		
		$res = array(
			'data'	=> array(array(
					'AccountId'             => 31, 
					'UserId'                => 1937656,
					'AccountName'           => '陈方超',
					'ICImage'               => '123',
					'BusinessCardImage'     => '234',
					'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
					'CompanyProof'          => '公司' ,
					'ICNo'                  =>null 
				)),
					'errorno'	=> 0

		);
		$res1 = array(
			'errorno'	=> 0,
		);
		$res2 = array(
			'errorno'	=> 0,
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById","UpdateProfile"));
		$obj ->expects( $this->any())
		->method('getAccountInfoById')
		->will($this->returnValue($res));
		
		$obj ->expects( $this->any())
        ->method('UpdateProfile')
        ->will($this->returnValue($res1));	

		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
		
		$objOrg = new Service_Page_HouseReport_Org_SetAgentInfo();
		$ret = $objOrg->execute($arrInput);
		$data = array(
			'errorno'	=> 0
		);
		$this->assertEquals($data,$ret);
		
		  $arrInput = array(
            'AccountId'             => 31,
            'UserId'                => 1937656,
            'AccountName'           => '陈丽丽',
            'ICImage'               => '123',
            'BusinessCardImage'     => '234',
            'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
            'CompanyProof'          => '公司' ,
            'ICNo'                  =>null,
            'CreatorId'             => 333,
            'CreatorName'           => 'zsj'
        );
        $res = array(
            'data'  => array(array(
                    'AccountId'             => 31,
                    'UserId'                => 1937656,
                    'AccountName'           => '陈方超',
                    'ICImage'               => '123',
                    'BusinessCardImage'     => '234',
                    'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
                    'CompanyProof'          => '公司' ,
                    'ICNo'                  =>null
                )),
                    'errorno'   => 0

        );
        $res1 = array(
            'errorno'   =>1002,
        );
        $res2 = array(
            'errorno'   => 0,
        );	
		$obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById","UpdateProfile"));
        $obj ->expects( $this->any())
        ->method('getAccountInfoById')
        ->will($this->returnValue($res));

        $obj ->expects( $this->any())
        ->method('UpdateProfile')
        ->will($this->returnValue($res1));

        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);

        $objOrg = new Service_Page_HouseReport_Org_SetAgentInfo();
        $ret = $objOrg->execute($arrInput);
        $data = array(
            'errorno'   => 1002
        );
        $this->assertEquals($data,$ret);	
		  
		$arrInput = array(
            'AccountId'             => 31,
            'UserId'                => 1937656,
            'AccountName'           => '陈丽丽',
            'ICImage'               => '123',
            'BusinessCardImage'     => '234',
            'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
            'CompanyProof'          => '公司' ,
            'ICNo'                  =>null,
            'CreatorId'             => 333,
            'CreatorName'           => 'zsj'
        );
        $res = array(
            'data'  => array(array(
                    'AccountId'             => 31,
                    'UserId'                => 1937656,
                    'AccountName'           => '陈方超',
                    'ICImage'               => '123',
                    'BusinessCardImage'     => '234',
                    'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
                    'CompanyProof'          => '公司' ,
                    'ICNo'                  =>null
                )),
                    'errorno'   => 0

        );

	  $arrInput = array(
            'AccountId'             => 31,
            'UserId'                => 1937656,
            'AccountName'           => '陈丽丽',
            'ICImage'               => '123',
            'BusinessCardImage'     => '234',
            'Picture'               => '/customer/picture/2009/0301/12/1235880510_8785.jpg',
            'CompanyProof'          => '公司' ,
            'ICNo'                  =>111,
            'CreatorId'             => 333,
            'CreatorName'           => 'zsj'
        );      
        $res = array(
            'data'  => array(array(
                    'AccountId'             => 31, 
                    'UserId'                => 1937656,
                    'AccountName'           => '陈丽丽',
                    'ICImage'               => '321',
                    'BusinessCardImage'     => '432',
                    'Picture'               => '/customer/picture/2009/0301/12/3333.jpg',
                    'CompanyProof'          => '司公' ,
                    'ICNo'                  =>'aaa' 
                )),
                    'errorno'   => 0

        );
        $res1 = array(
            'errorno'   => 0,
        );
        $res2 = array(
            'errorno'   => 0,
        );	
		  $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountInfoById","UpdateProfile"));
        $obj ->expects( $this->any())
        ->method('getAccountInfoById')
        ->will($this->returnValue($res));
        
        $obj ->expects( $this->any())
        ->method('UpdateProfile')
        ->will($this->returnValue($res1));    

        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount",$obj);
        
        $objOrg = new Service_Page_HouseReport_Org_SetAgentInfo();
        $ret = $objOrg->execute($arrInput);
        $data = array(
            'errorno'   => 0
        );
        $this->assertEquals($data,$ret);	
	}
}
