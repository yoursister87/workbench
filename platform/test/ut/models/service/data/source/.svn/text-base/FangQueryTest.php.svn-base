<?php
/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Source_FangQueryTest extends Testcase_PTest
{
    protected $db_res_array;
    protected $ret_array;

    protected  function setup(){
        $this->db_res_array = array(array('id'=>112,'house_id'=>3344));
        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
        Gj_LayerProxy::$is_ut = true;

        $this->mockDao();

    }
    protected function mockDao(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array("select"),array("select"=>$this->db_res_array));
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceRent",$objDao);
    }
    public function testGetHouseSourceByPuidInfo()
    {

        $a = new Service_Data_Source_FangQuery();
        $res = $a->getHouseSourceByPuidInfo(1234,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array[0];
        $this->assertEquals($res,$this->ret_array);

    }


    public function testGetHouseSourceByPuidInfoOnlyId()
    {
        $objUtil = $this->genEasyObjectMock("Util_Source_PostUid",array("lookUpIndex"),
            array("lookUpIndex"=>array('db_name'=>'beijing','table_name'=>'house_source_rent')));

        Util_Source_PostUid::setInstance($objUtil);
        $a = new Service_Data_Source_FangQuery();
        $res = $a->getHouseSourceByPuidInfo(97962302);
        $this->ret_array['data'] = $this->db_res_array[0];
        $this->assertEquals($res,$this->ret_array);

    }

    public function testGetHouseSourceByPuidinfoByMaster(){
        $puid= 97962302;
        $ret_by_master = array(array('id'=>222,'house_id'=>4455));
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array("select","selectByMaster"),
            array("select"=>$this->db_res_array,
                "selectByMaster"=>$ret_by_master));
        $objDao->expects($this->any())
            ->method('selectByMaster')
            ->with($this->equalTo(array('house_id','type')),array('puid ='=>$puid))
            ->will($this->returnValue($ret_by_master));


        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceRent",$objDao);



        $a = new Service_Data_Source_FangQuery();

        $res = $a->getHouseSourceByPuidInfo($puid,'beijing','house_source_rent',array('house_id','type'),true);

        $this->ret_array['data'] = $ret_by_master[0];
        $this->assertEquals($res,$this->ret_array);

    }

    public function testGetHouseSourceByPostId(){
        $obj = new Service_Data_Source_FangQuery();
        $res = $obj->getHouseSourceByPostId(1234,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array[0];
        $this->assertEquals($res,$this->ret_array);

    }

    public function testGetHouseSourceByPuidInfoWrongTable(){
        $obj = new Service_Data_Source_FangQuery();
        $res = $obj->getHouseSourceByPuidInfo(1234,'beijing','house_source_rent2');
        $this->assertEquals($res['errormsg'],'dao name is error');
    }

    public function testGetHouseSourceByPostIdWrongTable(){
        $obj = new Service_Data_Source_FangQuery();
        $res = $obj->getHouseSourceByPostId(1234,'beijing','house_source_rent2');
        $this->assertEquals($res['errormsg'],'dao name is error');
    }

    public function testGetHouseSourceByPostIdWrongObj(){
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceRent","123");
        $obj = new Service_Data_Source_FangQuery();
        $res = $obj->getHouseSourceByPostId(1234,'beijing','house_source_rent');
        $this->assertEquals($res['errormsg'],'error obj Dao');
        $this->mockDao();

    }


    public function testGetHouseSourceByPostIdByDbMaster(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseSourceRent",array("select","selectByMaster"),array("select"=>false,"selectByMaster"=>false));
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceRent",$objDao);
        $obj = new Service_Data_Source_FangQuery();
        $res = $obj->getHouseSourceByPostId(1234,'beijing','house_source_rent');
        $this->assertEquals($res['errormsg'],'get db data is empty');
        $this->mockDao();

    }

    public function testGetHouseSourceByConds(){
        $arrConds = array('puid =' => 98059937, 'huxing_shi =' => 2);
        $arrFields = array('price', 'huxing_wei');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceSellPremier', array('selectByPage'));
        $obj->expects($this->any())
            ->method('selectByPage')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(array(array('price' => 5000000, 'huxing_wei' => 1))));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceSellPremier', $obj);
        $obj1 = new Service_Data_Source_FangQuery();
        $res = $obj1->getHouseSourceByConds($arrConds, 'house_premier', 'house_source_sell_premier', $arrFields,false);
        $data['data'] = array(array('price' => 5000000, 'huxing_wei' => 1));
        $data['errorno'] = ErrorConst::SUCCESS_CODE;
        $data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->assertEquals($data, $res);

        $arrConds = array('puid =' => 0, 'huxing_shi =' => 2);
        $arrFields = array('price', 'huxing_wei');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceSellPremier', array('selectByPage', 'selectByMaster'));
        $obj->expects($this->any())
            ->method('selectByPage')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(null));
        $obj->expects($this->any())
            ->method('selectByMaster')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(null));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceSellPremier', $obj);
        $obj1 = new Service_Data_Source_FangQuery();
        $res = $obj1->getHouseSourceByConds($arrConds, 'house_premier', 'house_source_sell_premier', $arrFields);
        $data2['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $data2['errormsg'] = 'get db data is empty';
        $this->assertEquals($data2, $res);
    }
}
