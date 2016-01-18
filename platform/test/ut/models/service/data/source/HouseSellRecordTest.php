<?php

/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class HouseSellRecord extends Testcase_PTest
{

    protected $data;
    protected $arrFields = array("puid", "type", "account_id", "sellername", "sellerphone", "price", "price_unit");

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    public function testGetSellInfoByPuid()
    {
        $intPuid = 1234567;
        $arrFields = array();
        $data = array("puid" => 1234567, "type" => 5, "account_id" => 123, "sellername" => 'you', "sellerphone" => 13111111111, "price" => 21, "price_unit" => 2);
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSellRecord', array('selectSellInfoBypuid'));
        $obj->expects($this->any())
            ->method('selectSellInfoBypuid')
            ->with($this->arrFields, array('puid = ' => $intPuid))
            ->will($this->returnValue(array($data)));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSellRecord', $obj);
        $obj1 = new Service_Data_Source_HouseSellRecord();
        $res = $obj1->getSellInfoByPuid($intPuid, $arrFields);
        $this->data['data'] = $data;
        $this->assertEquals($this->data, $res);

        $intPuid = 1234567;
        $arrFields = array("type");
        $data = array("type" => 5);
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSellRecord', array('selectSellInfoBypuid'));
        $obj->expects($this->any())
            ->method('selectSellInfoBypuid')
            ->with($arrFields, array('puid = ' => $intPuid))
            ->will($this->returnValue(array($data)));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSellRecord', $obj);
        $obj1 = new Service_Data_Source_HouseSellRecord();
        $res = $obj1->getSellInfoByPuid($intPuid, $arrFields);
        $this->data['data'] = $data;
        $this->assertEquals($this->data, $res);

        $intPuid = 1234567;
        $arrFields = array("type");
        $data = array("type" => 5);
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSellRecord', array('selectSellInfoBypuid', 'getLastSQL'));
        $obj->expects($this->any())
            ->method('selectSellInfoBypuid')
            ->with($arrFields, array('puid = ' => $intPuid))
            ->will($this->returnValue(false));
        $obj->expects($this->any())
            ->method('getLastSQL')
            ->will($this->returnValue('select * from'));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSellRecord', $obj);
        $obj1 = new Service_Data_Source_HouseSellRecord();
        $res = $obj1->getSellInfoByPuid($intPuid, $arrFields);
        $this->assertEquals(array('errorno' => 1005, 'errormsg' => '[数据库失败]sql :select * from', 'data' => array()), $res);
    }

    public function testInsertSellInfo()
    {
        $arrFields = array("puid" => 1234567, "type" => 5, "account_id" => 123, "sellername" => 'you', "sellerphone" => 13111111111, "price" => 21, "price_unit" => 2);
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSellRecord', array('insertSellInfo'));
        $obj->expects($this->any())
            ->method('insertSellInfo')
            ->with($arrFields)
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSellRecord', $obj);
        $obj1 = new Service_Data_Source_HouseSellRecord();
        $res = $obj1->insertSellInfo($arrFields);
        $this->data['data'] = 1;
        $this->assertEquals($this->data, $res);

        $arrFields = array("puid" => 1234567, "type" => 5, "account_id" => 123, "sellername" => 'you', "sellerphone" => 13111111111, "price" => 21, "price_unit" => 2);
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSellRecord', array('insertSellInfo'));
        $obj->expects($this->any())
            ->method('insertSellInfo')
            ->with($arrFields)
            ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSellRecord', $obj);
        $obj1 = new Service_Data_Source_HouseSellRecord();
        $res = $obj1->insertSellInfo($arrFields);
        $this->assertEquals(array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"insert failed", 'data' => array()), $res);
    }
}