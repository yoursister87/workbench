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
            

            class Dao_Fang_HouseSourceModelroom_Puti_46a7419668d713df_Test extends Testcase_PTest
            {
                public $daoFangHouseSourceModelroom;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->daoFangHouseSourceModelroom = new Dao_Fang_HouseSourceModelroom('tianjin');
                }

                protected function tearDown()
                {

                }
                    
               /**
                * @expectedException Exception
                * {{{ testGetModelroomByParams_0
                */
               
          public function testPutiGetModelroomByParams_0()
          {
        $params='123';
        $num=1;
        $pageNo=1;


                      $rs = $this->daoFangHouseSourceModelroom->getModelroomByParams($params, $num, $pageNo);
          }
      /*}}}*/    


               }