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
            

            class Service_Data_Apartment_Modelroom_Puti_85771f0291ce6a9c_Test extends Testcase_PTest
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
                    
               /*{{{ testGetModelroomBySqlParams_0 */
               
          public function testPutiGetModelroomBySqlParams_0()
          {
        $domain='bj';
        $fields='';
        $conds='';
        $appends='';

        $fakeObj_0= $this->genObjectMock("Dao_Fang_HouseSourceModelroom", array("getModelroomBySqlParams"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("getModelroomBySqlParams")->will($this->returnValue(array()));

           $fakeObj_same = $this->genObjectMock("Service_Data_Apartment_Modelroom", array("getModel"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("getModel")->will($this->returnValue($fakeObj_0));

        Gj_LayerProxy::registerProxy("Dao_Fang_HouseSourceModelroom", $fakeObj_0);

                      $rs = $fakeObj_same->getModelroomBySqlParams($domain, $fields, $conds, $appends);
        
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