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
class Service_Page_Apartment_Modelroom_Test extends Testcase_PTest
{
    public $servicePageApartmentModelroom;

    protected function setUp()
    {
        parent::setUp();
        Gj_Layerproxy::$is_ut = true;
        $this->servicePageApartmentModelroom = new Service_Page_Apartment_Modelroom();
    }

    protected function tearDown()
    {

    }

    /*{{{ testExecuteException */

    public function testPutiExecuteException()
    {

        $arrParams=array (
            'puid' => 1,
        );


        $rs = $this->servicePageApartmentModelroom->execute($arrParams);

    }
    /*}}}*/ 
    public function testPutiExecute_1()
    {

        $arrParams=array (
            'puid' => 1,
        );

        $fakeObj_same = $this->genObjectMock("Service_Page_Apartment_Modelroom", array("getModelroomInfo","getPhoto","getApartmentInfo","getPeitao","getOtherModelroom"), array(), "", true);
        $fakeObj_same->expects($this->any())->method("getModelroomInfo")->will($this->returnValue(array('data' => array(
            'puid' => 1,
            'apartment_id' => 1,
        ),
        'errorno' =>'0'
    )));

        $fakeObj_same->expects($this->any())->method("getPhoto")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getApartmentInfo")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getPeitao")->will($this->returnValue(array()));

        $fakeObj_same->expects($this->any())->method("getOtherModelroom")->will($this->returnValue(array()));


        $rs = $fakeObj_same->execute($arrParams);

        $expect = array (
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => 
            array (
                'puid' => 1,
                'apartment_id' => 1,
                'photo' => 
                array (
                ),
                'peitao' => 
                array (
                ),
                'apartment' => 
                array (
                ),
                'otherModelroom' => 
                array (
                ),
                'lookAndLookModelroom' => 
                array (
                ),
            ),
        );

        $this->assertEquals($expect, $rs);


    }



}
