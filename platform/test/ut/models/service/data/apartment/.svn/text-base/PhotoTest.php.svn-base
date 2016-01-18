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
class PhotoTest extends TestCase_PTest
{
    protected function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
    }

    public function testGetApartmentPhotoByApartmentId()
    { 
        $expectValue = array(
            0 => array(
                'id' => 123,
                'apartment_id' => '12345',
                'image' => '',
            ),
        );
        //最终返回结果
        $expectData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $expectValue,
        );
        $mockObj = $this->genObjectMock('Dao_Fangproject_ApartmentPhoto', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));
        Gj_LayerProxy::registerProxy('Dao_Fangproject_ApartmentPhoto', $mockObj);
        $obj = new Service_Data_Apartment_Photo();
        $data = $obj->getApartmentPhotoByApartmentId(12345, array(), 1);
        $this->assertEquals($expectData, $data);
    }
}
