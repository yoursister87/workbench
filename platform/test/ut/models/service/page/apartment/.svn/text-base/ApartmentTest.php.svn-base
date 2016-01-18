<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Page_Apartment_Apartment_Test extends Testcase_PTest
{
    public $servicePageApartmentApartment;

    protected function setUp()
    {
        parent::setUp();
        Gj_Layerproxy::$is_ut = true;
        $this->servicePageApartmentApartment = new Service_Page_Apartment_Apartment();
    }
    protected function tearDown()
    {
    }
    /*{{{ testExecuteException */

    public function testPutiExecuteException()
    {
        $arrParams=array (
        );

        $rs = $this->servicePageApartmentApartment->execute($arrParams);
    }
    /*}}}*/ 
    public function testPutiExecute_1()
    {

        $arrParams=array (
            'apartmentId' => 1,
        );

        $fakeObj_same = $this->genObjectMock("Service_Page_Apartment_Apartment", array("getApartmentInfo"), array(), "", true);
        $fakeObj_same->expects($this->any())->method("getApartmentInfo")->will($this->returnValue(array()));


        $rs = $fakeObj_same->execute($arrParams);

        $expect = array (
            'errorno' => '1002',
            'errormsg' => '[参数不合法]',
            'data' => 
            array (
            ),
        );

        $this->assertEquals($expect, $rs);


    }
    public function testPutiExecute_2()
    {

        $arrParams=array (
            'apartmentId' => 1,
        );

        $fakeObj_same = $this->genObjectMock("Service_Page_Apartment_Apartment", array("getOtherApartment","getApartmentInfo","getModelroomInfo","getPeitao","getPhoto"), array(), "", true);
        $fakeObj_same->expects($this->any())->method("getOtherApartment")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getApartmentInfo")->will($this->returnValue(array('id' => 1, 'company_id' => 1)));

        $fakeObj_same->expects($this->any())->method("getModelroomInfo")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getPeitao")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getPhoto")->will($this->returnValue(array()));


        $rs = $fakeObj_same->execute($arrParams);

        $expect = array (
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => 
            array (
                'id' => 1,
                'company_id' => 1,
                'otherApartment' => 
                array (
                ),
                'modelroom' => 
                array (
                ),
                'peitao' => 
                array (
                ),
                'photo' => 
                array (
                ),
            ),
        );

        $this->assertEquals($expect, $rs);
    }

}
