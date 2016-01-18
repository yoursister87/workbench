<?php
class	CheckTitleTest extends Testcase_PTest{
	protected function setUp(){
         Gj_LayerProxy::$is_ut = true;
     }  	
	public function testexecute(){
		$arrInput = array(
			'title'		=> '',
			'level'		=> '',
			'id'		=> 123
		);			
		$obj = new Service_Page_HouseReport_Org_CheckTitle();
		$ret = $obj->execute($arrInput);
		$data	= array(
			'data'		=> array(),
			'errorno'	=>1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);

		$arrInput = array(
            'title'     => 'zsj', 
            'level'     => 2, 
            'id'        => 123 
        );    
		$res = array(
			'data'	=> array(
				'id'	=> 123
			)
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
         ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue($res));
        $obj = new Service_Page_HouseReport_Org_CheckTitle();
        $ret = $obj->execute($arrInput);
        $data   = array(
            'data'      => false,
            'errorno'   =>0,
            'errormsg'  => '[数据返回成功]'
        );  
        $this->assertEquals($data,$ret);	

		$arrInput = array(
            'title'     => 'zsj',
            'level'     => 2,
            'id'        => 123
        );
        $res = array(
            'data'  => array(
                'id'    => 124
            )
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
         ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue($res));
        $obj = new Service_Page_HouseReport_Org_CheckTitle();
        $ret = $obj->execute($arrInput);
        $data   = array(
            'data'      => false,
            'errorno'   =>0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);
	}

	public function testexecuteException(){
		$arrInput = array(
            'title'     => 'zsj',
            'level'     => 2,
            'id'        => 123
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
         ->method("getOrgInfoByIdOrAccount")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        $obj = new Service_Page_HouseReport_Org_CheckTitle();
        $ret = $obj->execute($arrInput);
        $data   = array(
            'data'      => false,
            'errorno'   =>0,
            'errormsg'  => '[数据返回成功]'
        );
        $this->assertEquals($data,$ret);	
	}
} 
