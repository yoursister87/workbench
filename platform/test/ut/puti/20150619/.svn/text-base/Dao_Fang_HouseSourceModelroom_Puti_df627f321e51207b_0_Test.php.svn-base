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
            

            class Dao_Fang_HouseSourceModelroom_Puti_df627f321e51207b_Test extends Testcase_PTest
            {
                public $daoFangHouseSourceModelroom;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->daoFangHouseSourceModelroom = new Dao_Fang_HouseSourceModelroom('beijing');
                }

                protected function tearDown()
                {

                }
                    
               /**
                * @expectedException Exception
                * {{{ testGetModelroomByApartmentId_0
                */
               
          public function testPutiGetModelroomByApartmentId_0()
          {
        $apartmentId=1;
        $num=-1;
        $pageNo=1;

           $fakeObj_same = $this->genObjectMock("Dao_Fang_HouseSourceModelroom", array("__construct"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("__construct")->will($this->returnValue(true));


                      $rs = $fakeObj_same->getModelroomByApartmentId($apartmentId, $num, $pageNo);
          }
      /*}}}*/    


               }