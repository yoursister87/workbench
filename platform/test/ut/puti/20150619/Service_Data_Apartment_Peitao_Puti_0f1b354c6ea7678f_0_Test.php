<?php
            /**
             * @package              
             * @subpackage           
             * @author               $Author: liuzhen1   zhenyangze  $
             * @file                 $HeadURL$
             * @version              $Rev$
             * @lastChangeBy         $LastChangedBy$
             * @lastmodified         $LastChangedDate$
             * @copyright            Copyright (c) 2015, www.ganji.com
             */
            

            class Service_Data_Apartment_Peitao_Puti_0f1b354c6ea7678f_Test extends Testcase_PTest
            {
                public $serviceDataApartmentPeitao;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->serviceDataApartmentPeitao = new Service_Data_Apartment_Peitao();
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetApartmentPeitaoByApartmentId_0 */
               
          public function testPutiGetApartmentPeitaoByApartmentId_0()
          {
        $apartmentId=0;

        $arrFields=array (
);
        $status=1;

        $fakeObj_0= $this->genObjectMock("Dao_Fangproject_ApartmentPeitao", array("select"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("select")->will($this->returnValue(array()));

        Gj_LayerProxy::registerProxy("Dao_Fangproject_ApartmentPeitao", $fakeObj_0);

                      $rs = $this->serviceDataApartmentPeitao->getApartmentPeitaoByApartmentId($apartmentId, $arrFields, $status);
        
        $expect = array (
  'errorno' => '0',
  'errormsg' => '[数据返回成功]',
  'data' => 
  array (
  ),
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }