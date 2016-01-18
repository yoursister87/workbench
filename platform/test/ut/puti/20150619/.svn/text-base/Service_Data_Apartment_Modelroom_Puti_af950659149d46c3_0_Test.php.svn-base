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
            

            class Service_Data_Apartment_Modelroom_Puti_af950659149d46c3_Test extends Testcase_PTest
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
                    
               /*{{{ testGetModelroomByParams_0 */
               
          public function testPutiGetModelroomByParams_0()
          {

        $params=array (
  'queryFilter' => 
  array (
    'city_domain' => 'bj',
  ),
);
        $num=1;
        $pageNo=1;

        $fakeObj_0= $this->genObjectMock("Dao_Fang_HouseSourceModelroom", array("getModelroomByParams"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("getModelroomByParams")->will($this->returnValue(array()));

           $fakeObj_same = $this->genObjectMock("Service_Data_Apartment_Modelroom", array("getModel"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("getModel")->will($this->returnValue($fakeObj_0));

        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceModelroom", $fakeObj_0);

                      $rs = $fakeObj_same->getModelroomByParams($params, $num, $pageNo);
        
        $expect = array (
  'data' => 
  array (
  ),
  'errorno' => '0',
  'errormsg' => '[数据返回成功]',
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }