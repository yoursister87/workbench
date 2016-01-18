<?php


class Service_Data_Source_FangSubmitTest extends Testcase_PTest
{
    protected $db_res_array;
    protected $ret_array;

    protected  function setup(){

        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
        Gj_LayerProxy::$is_ut = true;

    }
    public function testAddHouseSource(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('insert'),12345);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);

        $objDsPostChangeAsync = $this->genEasyObjectMock("Service_Data_Source_PostChangeAsync",array('addOneMsg'),12345);
        Gj_LayerProxy::registerProxy("Service_Data_Source_PostChangeAsync",$objDsPostChangeAsync);

        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->addHouseSource(array('id'=>123,'house_id'=>3456),'house_source_rent','beijing');
        $this->ret_array['data']['post_id'] = 12345;
        $this->assertEquals($ret,$this->ret_array);


    }

    public function testAddHouseSourceDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('insert'),false);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);
        $objDsPostChangeAsync = $this->genEasyObjectMock("Service_Data_Source_PostChangeAsync",array('addOneMsg'),12345);
        Gj_LayerProxy::registerProxy("Service_Data_Source_PostChangeAsync",$objDsPostChangeAsync);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->addHouseSource(array('id'=>123,'house_id'=>3456),'house_source_rent','beijing');
        $this->assertEquals($ret,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"insert failed"));


    }

    public function testUpdateHouseSourceByPuid(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('update'),10);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->updateHouseSourceByPuid(array('id'=>123,'house_id'=>3456),123456,'house_source_rent','beijing');
        $this->assertEquals($ret,$this->ret_array);

    }

    public function testUpdateHouseSourceByPuidDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('update'),false);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->updateHouseSourceByPuid(array('id'=>123,'house_id'=>3456),123456,'house_source_rent','beijing');
        $this->assertEquals($ret,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"update failed"));


    }
    public  function testUpdateHouseSourceByPostId(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('update'),10);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->updateHouseSourceByPostId(array('id'=>123,'house_id'=>3456),0,123456,'house_source_rent','beijing');
        $this->assertEquals($ret,$this->ret_array);

    }

    public function testUpdateHouseSourceByPostIdDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array('update'),false);
        $objDsFangQuery = $this->genEasyObjectMock("Service_Data_Source_FangQuery",array('getObjDaoByTableName'),$objDao);
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$objDsFangQuery);

        $obj = new Service_Data_Source_FangSubmit();
        $ret = $obj->updateHouseSourceByPostId(array('id'=>123,'house_id'=>3456),0,123456,'house_source_rent','beijing');
        $this->assertEquals($ret,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"update failed"));
    }
    public function testUpdateHouseSourceListByPuid(){
    	$intPuid = 123456;
    	$arrChangeRow = array('id'=>123,'house_id'=>3456);
    	$arrConds = array('puid =' =>$intPuid);
    	$arrRet = array(
    			'errorno'  => ErrorConst::SUCCESS_CODE,
    			'errormsg' => ErrorConst::SUCCESS_MSG,
    			'data' => array(),
    	);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("update"));
    	$obj->expects($this->any())
    	->method('update')
    	->with($arrChangeRow, $arrConds)
    	->will($this->returnValue($arrRet));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $obj);
    	$objService = new Service_Data_Source_FangSubmit();
    	$res = $objService->updateHouseSourceListByPuid($arrChangeRow,$intPuid);
    	$this->assertEquals($arrRet,$res);
    	
    	$intPuid = 123456;
    	$arrChangeRow = array('id'=>123,'house_id'=>3456);
    	$arrConds = array('puid =' =>$intPuid);
    	$arrRet = array(
    			'errorno'  => ErrorConst::E_DB_FAILED_CODE,
    			'errormsg' => 'update failed',
    	);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("update"));
    	$obj->expects($this->any())
    	->method('update')
    	->with($arrChangeRow, $arrConds)
    	->will($this->returnValue(false));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $obj);
    	$objService = new Service_Data_Source_FangSubmit();
    	$res = $objService->updateHouseSourceListByPuid($arrChangeRow,$intPuid);
    	$this->assertEquals($arrRet,$res);
    }
}
