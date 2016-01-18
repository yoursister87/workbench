<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: liuzhen1 <liuzhen1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class ApartmentInfoTest extends TestCase_PTest
{
    protected function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
    }
    /*
    public function testGetApartmentInfoByCityPinyin()
    {
        $expectValue = array(
            0 => array(
                'id' => 123,
                'city' => 'bj',
                'name' => '蘑菇公寓',
                'pinyin' => 'mogugongyu',
            ),
        );
        //最终返回结果
        $expectData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $expectValue[0],
        );
        $mockObj = $this->genObjectMock('Dao_Fangproject_Apartment', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));
        Gj_LayerProxy::registerProxy('Dao_Fangproject_Apartment', $mockObj);
        $obj = new Service_Data_Apartment_Info();
        $data = $obj->getApartmentInfoByCityPinyin('bj', 'mogugongyu');
        $this->assertEquals($expectData, $data);
    }
    */
    public function testGetApartmentInfoById()
    { 
        $expectValue = array(
            0 => array(
                'id' => 123,
                'city' => 'bj',
                'name' => '蘑菇公寓',
                'pinyin' => 'mogugongyu',
            ),
        );
        //最终返回结果
        $expectData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $expectValue[0],
        );
        $mockObj = $this->genObjectMock('Dao_Fangproject_Apartment', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));
        Gj_LayerProxy::registerProxy('Dao_Fangproject_Apartment', $mockObj);
        $obj = new Service_Data_Apartment_Info();
        $data = $obj->getApartmentInfoById(123);
        $this->assertEquals($expectData, $data);
    }
    
    public function testGetApartmentListByCompanyId()
    {
        $expectValue = array(
            0 => array(
                'id' => 123,
                'city' => 'bj',
                'name' => '蘑菇公寓',
                'pinyin' => 'mogugongyu',
            ),
        );
        //最终返回结果
        $expectData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $expectValue,
        );
        $mockObj = $this->genObjectMock('Dao_Fangproject_Apartment', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        Gj_LayerProxy::registerProxy('Dao_Fangproject_Apartment', $mockObj);
        $obj = new Service_Data_Apartment_Info();
        $data = $obj->getApartmentListByCompanyId(12345, true);
        $this->assertEquals($expectData, $data);
        
        $data = $obj->getApartmentListByCompanyId(12345, false);
        $this->assertEquals($expectData, $data);
    }
    
    public function testGetApartmentByAdLocation()
    {
    
        $expectValue = array(
            0 => array('apartment_id' => 123),
        );
        //最终返回结果
        $expectData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $expectValue[0],
        );
        $mockObj = $this->genObjectMock('Dao_Fangproject_ApartmentAdLocation', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        Gj_LayerProxy::registerProxy('Dao_Fangproject_ApartmentAdLocation', $mockObj);
        $obj = new Service_Data_Apartment_Info();
        $data = $obj->getApartmentByAdLocation(12345, 1);
        $this->assertEquals($expectData, $data);
    }
    
}
