<?php
class CompanyTest extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
	protected function setUp(){ 
		Gj_LayerProxy::$is_ut = true; 
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->result = array(array('id'=>123,'company_id'=>45888));
		$this->arrFields = array('CompanyId','FullName');
	}
	public function testgetCompanyInfoById(){
		$Input = '';
		Gj_LayerProxy::registerProxy('Dao_Gcrm_Company',$obj);
		$orgObj = new Service_Data_Gcrm_Company();
		$rs = $orgObj->getCompanyInfoById($Input);
		$data = array (
			'data' => array(),
			'errorno' => 1002,
			'errormsg' => '[参数不合法]'
		);
		$this->assertEquals($data, $rs);

		$Input = '1575';
		$arrInput = array ('CompanyId','ShortcutName');
		$res = array ( array (
			'CompanyId' => 1575,
			'ShortcutName' => '赶集测试')
		);
		 $obj = $this->genObjectMock("Dao_Gcrm_Company",array("select"));
		 $obj ->expects( $this->any())  
			 ->method('select') 
			 ->will ($this->returnValue($res));
		 Gj_LayerProxy::registerProxy('Dao_Gcrm_Company',$obj);
		 $orgObj = new Service_Data_Gcrm_Company();
		 $ret =  $orgObj->getCompanyInfoById($Input,$arrInput); 
		 $data = array (
			'data' => array(
					'CompanyId' => 1575, 
					'ShortcutName' => '赶集测试'
				),
			 'errorno' => 0,
			 'errormsg' => '[数据返回成功]'
		 );
		 $this->assertEquals($data,$ret); 
		 $Input = '1575';
        $arrInput = array ('CompanyId','ShortcutName');
        $res = false;  
		$res1 = 1;
         $obj = $this->genObjectMock("Dao_Gcrm_Company",array("select","getLastSQL"));
         $obj ->expects( $this->any())  
             ->method('select') 
             ->will ($this->returnValue($res));
		   $obj ->expects( $this->any())  
           	 ->method('getLastSQL') 
             ->will ($this->returnValue($res1));
         Gj_LayerProxy::registerProxy('Dao_Gcrm_Company',$obj);
         $orgObj = new Service_Data_Gcrm_Company();
         $ret =  $orgObj->getCompanyInfoById($Input,$arrInput); 
         $data = array (
             'errorno' => 1003,
             'errormsg' => '[SQL语句执行失败]',
			 'data'    => array()
         );  
         $this->assertEquals($data,$ret); 
	}
	public function testGetCompanyListByWhere(){
		$whereConds = array('city_id'=>12);
		// $obj = new Service_Data_Gcrm_Company();
		//$res = $obj->getCompanyListByWhere($whereConds, $this->arrFields, 1, 10, $orderArr); 
		$arrConds=array(
				'city_id ='=>12,
		);
		$res1 = array(
		  	'CityId ='      =>12,
            'CompanyId ='   => 235	
		);
        $arrFields = array('id as CompanyId', 'full_name as FullName');
		$obj = $this->genObjectMock("Dao_Gcrm_Company", array("selectByPage"));
		$obj->expects($this->any())
		->method('selectByPage')
		->will($this->returnValue($this->result));
		
		$obj1= $this->genObjectMock("Service_Data_Gcrm_Company", array("getWhere"));
        $obj1->expects($this->any())
        ->method('getWhere')
        ->will($this->returnValue($res1));	
		Gj_LayerProxy::registerProxy("Dao_Gcrm_Company", $obj);
		$orgObj = new Service_Data_Gcrm_Company();
		$res = $orgObj->getCompanyListByWhere($whereConds, $this->arrFields, 1, 30, $orderArr);
		
		$this->data['data'] = $this->result;
		$this->assertEquals($this->data,$res);

	}
	public function testGetWhere(){
		$data=array(
			'CityId ='		=>12,
			'CompanyId ='	=> 235
		);
		$whereConds = array('CityId'=>12,'CompanyId'=> 235);
		$obj = new Service_Data_Gcrm_Company();
		$ret = $obj->getWhere($whereConds);
		$this->assertEquals($data,$ret);
		
		$data=array(
            'CityId ='      =>12,
			 0 => 'CompanyId in ( 235,234 )'
        );  
        $whereConds = array('CityId'=>12,'CompanyId'=> array(235,234));
        $obj = new Service_Data_Gcrm_Company();
        $ret = $obj->getWhere($whereConds);
        $this->assertEquals($data,$ret);
	}
	public function testGetRealHouseCompanyByCityId(){
		$city_id = 12;
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":0,"data":{"result":111}}'));
		Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_Company();
		$ret = $obj->getRealHouseCompanyByCityId($city_id);
		$data = array(
				'data'      => array(),
				'errorno'   => 2126,
				'errormsg'  => null
		);
		$this->assertEquals($data,$ret);
		
		$objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"   =>  '{"succeed":1,"data":{"result":111}}'));
		Gj_Util_Curl::setInstance($objUtil);
		$obj = new Service_Data_Gcrm_Company();
		$ret = $obj->getRealHouseCompanyByCityId($city_id);
		$this->data['data'] = 111;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->assertEquals($this->data,$ret);
	}
}
