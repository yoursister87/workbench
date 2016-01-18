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
            

            class Service_Data_Apartment_Modelroom_Puti_3108bf4b2b061f0d_Test extends Testcase_PTest
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
        $puid='';
        $num='';


                      $rs = $this->serviceDataApartmentModelroom->getRecommendModelroom($puid, $num);
        
        $expect = array (
  'data' => NULL,
  'errorno' => '0',
  'errormsg' => '[数据返回成功]',
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }