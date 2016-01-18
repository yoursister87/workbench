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
            

            class Dao_Fang_HouseSourceModelroom_Puti_c6e13b8ddebad08f_Test extends Testcase_PTest
            {
                public $daoFangHouseSourceModelroom;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->daoFangHouseSourceModelroom = new Dao_Fang_HouseSourceModelroom( beijing);
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetModelroomByParams_0 */
               
          public function testPutiGetModelroomByParams_0()
          {

        $params=array (
  0 => 1,
  1 => 2,
  2 => 3,
);
        $num=1;
        $pageNo=1;

           $fakeObj_same = $this->genObjectMock("Dao_Fang_HouseSourceModelroom", array("selectByPage"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("selectByPage")->will($this->returnValue("123"));


                      $rs = $fakeObj_same->getModelroomByParams($params, $num, $pageNo);
        
        $expect = '123';

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }