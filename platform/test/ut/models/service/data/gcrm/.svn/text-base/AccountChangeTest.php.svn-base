<?php
class	AccountChangeTest  extends Testcase_PTest{
	protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
     }   	
	public function testaddAccountChange(){
		$arrRow = '';
		$obj =  new Service_Data_Gcrm_AccountChange();
		$ret = $obj->addAccountChange($arrRow);
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'

		);
		$this->assertEquals($data,$ret);
	
		
		$arrRow = array(
			123	
		);
		$res = true;
        $obj = $this->genObjectMock("Dao_Tgqe_AccountChangeQueue",array("insert"));
        $obj->expects($this->any())
        ->method("insert")
        ->will($this->returnValue($res));	
		  Gj_LayerProxy::registerProxy("Dao_Tgqe_AccountChangeQueue",$obj);
        $obj1 =  new Service_Data_Gcrm_AccountChange();
        $ret = $obj1->addAccountChange( $arrRow);
        $data = array(
			'data'		=>true,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
        $this->assertEquals($data,$ret);	
	}
	public function testaddAccountChangeException(){
       $arrRow = array(
            123
        );
		$res1 = true;
        $obj = $this->genObjectMock("Dao_Tgqe_AccountChangeQueue",array("insert"));
        $obj->expects($this->any())
        ->method("insert")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
          Gj_LayerProxy::registerProxy("Dao_Tgqe_AccountChangeQueue",$obj);
        $obj1 =  new Service_Data_Gcrm_AccountChange();
        $ret = $obj1->addAccountChange( $arrRow);
        $data = array(
            'data'      =>null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]'
       );
        $this->assertEquals($data,$ret);				
	}
} 
