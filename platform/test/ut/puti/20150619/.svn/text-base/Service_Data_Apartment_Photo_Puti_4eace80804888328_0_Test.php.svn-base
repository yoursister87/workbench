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
            

            class Service_Data_Apartment_Photo_Puti_4eace80804888328_Test extends Testcase_PTest
            {
                public $serviceDataApartmentPhoto;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->serviceDataApartmentPhoto = new Service_Data_Apartment_Photo();
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetApartmentPhotoByApartmentId_0 */
               
          public function testPutiGetApartmentPhotoByApartmentId_0()
          {
        $apartmentId=0;

        $arrFields=array (
);
        $status=1;

        $fakeObj_0= $this->genObjectMock("Dao_Fangproject_ApartmentPhoto", array("select"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("select")->will($this->returnValue(array()));

        Gj_LayerProxy::registerProxy("Dao_Fangproject_ApartmentPhoto", $fakeObj_0);

                      $rs = $this->serviceDataApartmentPhoto->getApartmentPhotoByApartmentId($apartmentId, $arrFields, $status);
        
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