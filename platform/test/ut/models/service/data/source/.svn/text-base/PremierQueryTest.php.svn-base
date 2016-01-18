<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   lihongyun1$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_Source_PremierQueryTest extends Testcase_PTest{
    private $arrRet;
    public function setUp(){
        Gj_LayerProxy::$is_ut = true;
        $this->arrRet = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
    }
    public function testGetObjDaoByTableName(){
        $intType =1;
        $objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceRentPremier",array());
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceRentPremier",$objDao);
        $obj = new Service_Data_Source_PremierQuery();
        $res  = $obj->getObjDaoByType($intType);
        $this->assertEquals($res,$objDao);
    }

    public function testGetRowByHouseId(){
        $intHouseId = 1145291;
        $intType= 1;
        $arrFields = array('house_id','type');
        $arrRetVal = array('house_id'=>'123456','type'=>'1');
        $objDao = $this->genEasyObjectMock("Dao_Housepremier_HouseSourceRentPremier",
            array('select','selectByMaster'),
            array('select'=>array(),'selectByMaster'=>array($arrRetVal)));

        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceRentPremier",$objDao);

        $obj = new Service_Data_Source_PremierQuery();
        $res = $obj->getRowByHouseId($intHouseId,$intType,$arrFields);
        $this->arrRet['data'] = $arrRetVal;
        $this->assertEquals($res,$this->arrRet);

    }
    public function testGetRowByHouseIdFieldsHasDescription(){
        $intHouseId = 1145291;
        $intType= 1;
        $arrDetailRetVal = array('house_id'=>'123456','type'=>'1','puid'=>'96032137');
        $arrDescRetVal = array('description' => "真正好房子！快来看！2");
        $objDaoDetail = $this->genEasyObjectMock("Dao_Housepremier_HouseSourceRentPremier",
            array('select','selectByMaster'),
            array('select'=>array(),'selectByMaster'=>array($arrDetailRetVal)));

        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceRentPremier",$objDaoDetail);


        $objDaoDesc = $this->genEasyObjectMock("Dao_Housepremier_HouseSourceDescription",
            array('selectDesc'),array($arrDescRetVal));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceDescription",$objDaoDesc);


        $obj = new Service_Data_Source_PremierQuery();
        $res = $obj->getRowByHouseId($intHouseId,$intType);
        $this->arrRet['data'] = array_merge($arrDetailRetVal,$arrDescRetVal);
        $this->assertEquals($res,$this->arrRet);

    }

    public function testGetRowByHouseIdDbEmpty(){
        $intHouseId = 1145291;
        $intType= 1;
        $objDaoDetail = $this->genEasyObjectMock("Dao_Housepremier_HouseSourceRentPremier",
            array('select','selectByMaster'),
            array('select'=>array(),'selectByMaster'=>array()));

        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceRentPremier",$objDaoDetail);

        $obj = new Service_Data_Source_PremierQuery();
        $res = $obj->getRowByHouseId($intHouseId,$intType);
        $emptyRet = array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>'get house info empty');

        $this->assertEquals($res,$emptyRet);


    }
    public function testGetTuiguangHouseByAccountId(){
        $whereConds = array(
            'account_id'	=> 835,
            'premier_status'			=> 2,
            's_premier_expire'=>time(),
            'e_premier_expire'=>time()+7*24*3600,
        );
        $obj = $this->genObjectMock("Service_Data_Source_PremierQuery",array("getHouseWhere"));
        $obj->expects($this->any())
            ->method("getHouseWhere")
            ->will($this->returnValue(array()));
        $res = $obj->getTuiguangHouseByAccountId($whereConds);
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$res);

        $objT = $this->genObjectMock("Service_Data_Source_PremierQuery",array("getHouseWhere"));
        $objT->expects($this->any())
            ->method("getHouseWhere")
            ->will($this->returnValue($whereConds));
        $arrFields = array('house_id','type','puid');
        $arrConds = array(
            'account_id'	=> 835,
            'premier_status'			=> 2,
            's_premier_expire'=>time(),
            'e_premier_expire'=>time()+7*24*3600,
        );
        $orderArr = array();
        $returnData = array (array ( 'AccountId' => '882978', 'UserId' => '296317196', 'GroupId' => '339809', 'AccountName' => '姜成龙', 'Picture' => '', 'CellPhone' => '15802143946'));
        $obj1 = $this->genObjectMock("Dao_Housepremier_HouseSourceList",array("selectByPage"));
        $obj1->expects($this->any())
            ->method("selectByPage")
            //->with($arrFields, $arrConds, 1, 30, $orderArr)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $obj1);
        $res = $objT->getTuiguangHouseByAccountId($whereConds);
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' =>$returnData
        );
        $this->assertEquals($data,$res);
    }
    public function testGetHouseWhere(){
        $whereConds = array(
            'account_id'	=> 835,
            'premier_status'			=> 2,
            's_post_at'			=>1419236606,
            'e_post_at'			=>1419236606
        );
        $obj = new Service_Data_Source_PremierQuery();
        $res = $obj->getHouseWhere($whereConds);
        $data = array(
            'account_id ='	=> 835,
            'premier_status ='			=> 2,
            'post_at <='			=>1419236606,
            'post_at >='			=>1419236606
        );
        $this->assertEquals($data,$res);
    }
}