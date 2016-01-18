<?php
/**
 * Created by PhpStorm.
 * User: lihongyun1
 * Date: 2014/10/28
 * Time: 13:52
 */
class Service_Data_Source_PremierSourceOperationTest extends Testcase_PTest{

    public function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    public function testAddSourceOperation(){
        $param =array($intHouseId = 1, $intType =2, $intUser =3, $strOp =4, $strMsg = '');
        $objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceOperation",array("insert"));
        $objDao->expects($this->any())
            ->method('insert')
            ->with($this->isType('array'))
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceOperation",$objDao);

        $objDaoNew = $this->genObjectMock("Dao_HousepremierOperation_HouseSourceOperation",array("insertOp"));
        $objDaoNew->expects($this->any())
            ->method('insertOp')
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy("Dao_HousepremierOperation_HouseSourceOperation",$objDaoNew);
        $obj = new Service_Data_Source_PremierSourceOperation();
        $res  = call_user_func_array(array($obj,"addSourceOperation"),$param);

        $this->assertEquals($res,true);
    }
	public function testgetOPCountByAccountId(){
        $param = array('HouseId'=>111);
        $objDao = $this->genObjectMock("Dao_HousepremierOperation_HouseSourceOperation",array("getSelectBySplit"));
        $ret = array('count'=>1);
        $objDao->expects($this->any())
            ->method('getSelectBySplit')
            ->will($this->returnValue($ret));
        Gj_LayerProxy::registerProxy("Dao_HousepremierOperation_HouseSourceOperation",$objDao);
        $obj = new Service_Data_Source_PremierSourceOperation();
        $res = $obj->getOPCountByAccountId(array(),$param);
        $this->assertEquals($ret,$res);
	}
	public function testgetOPHouseList(){
		$accountIds = array();
		$arrConds = array();
		$obj = new Service_Data_Source_PremierSourceOperation();
		$ret = $obj->getOPHouseList($accountIds,$arrConds);
		$result = null;
		$this->assertEquals($res,$result);

		$accountIds = 123;
		$arrConds = array();
		$res = array(); 
		$objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceOperation",array("select"));
		$objDao->expects($this->any())
			->method('select')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceOperation",$objDao);
		$obj = new Service_Data_Source_PremierSourceOperation();
		$ret = $obj->getOPHouseList($accountIds,$arrConds);
		$result = array(); 
		$this->assertEquals($ret,$result);

		$accountIds = 123;
		$arrConds = array();
		$objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceOperation",array("select"));
		$objDao->expects($this->any())
			->method('select')
			->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceOperation",$objDao);
		$obj = new Service_Data_Source_PremierSourceOperation();
		$ret = $obj->getOPHouseList($accountIds,$arrConds);
		$result = array('errorno' => 1005,'errormsg' => '[参数不合法]'); 
		$this->assertEquals($ret,$result);	
	}
}
