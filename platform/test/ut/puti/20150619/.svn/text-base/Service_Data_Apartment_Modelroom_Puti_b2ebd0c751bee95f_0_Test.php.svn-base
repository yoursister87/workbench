<?php
            /**
             * @package              
             * @subpackage           
             * @author               $Author: zhangrong3   zhenyangze  $
             * @file                 $HeadURL$
             * @version              $Rev$
             * @lastChangeBy         $LastChangedBy$
             * @lastmodified         $LastChangedDate$
             * @copyright            Copyright (c) 2015, www.ganji.com
             */
            

            class Service_Data_Apartment_Modelroom_Puti_b2ebd0c751bee95f_Test extends Testcase_PTest
            {
                public $serviceDataApartmentModelroom;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->serviceDataApartmentModelroom = new Service_Data_Apartment_Modelroom();
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetRecommendModelroom_0 */
               
          public function testPutiGetRecommendModelroom_0()
          {
        $puid=1;
        $num=1;

        $fakeObj_0= $this->genObjectMock("Service_Data_Source_FangQuery", array("getHouseSourceByPuidInfo"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("getHouseSourceByPuidInfo")->will($this->returnValue(array('data'=>array(
'apartment_id' => 1
))));

           $fakeObj_same = $this->genObjectMock("Service_Data_Apartment_Modelroom", array("getModelroomByApartmentId"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("getModelroomByApartmentId")->will($this->returnValue(array(
'data' => array(
    0 => array(
        'puid' => 1,
    ),
    1 => array(
        'puid' => 1,
    ),
    2 => array(
        'puid' => 2,
    ),
)
)));

        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery", $fakeObj_0);

                      $rs = $fakeObj_same->getRecommendModelroom($puid, $num);
        
        $expect = array (
  'errorno' => '0',
  'errormsg' => '[数据返回成功]',
  'data' => 
  array (
    0 => 
    array (
      'puid' => 1,
    ),
    1 => 
    array (
      'puid' => 1,
    ),
    2 => 
    array (
      'puid' => 2,
    ),
  ),
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }