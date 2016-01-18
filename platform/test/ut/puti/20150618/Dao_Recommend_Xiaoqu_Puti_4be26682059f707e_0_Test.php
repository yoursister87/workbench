<?php
            /**
             * @package              
             * @subpackage           
             * @author               $Author: shenweihai $
             * @file                 $HeadURL$
             * @version              $Rev$
             * @lastChangeBy         $LastChangedBy$
             * @lastmodified         $LastChangedDate$
             * @copyright            Copyright (c) 2015, www.ganji.com
             */
            

            class Dao_Recommend_Xiaoqu_Puti_4be26682059f707e_Test extends Testcase_PTest
            {
                public $daoRecommendXiaoqu;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->daoRecommendXiaoqu = new Dao_Recommend_Xiaoqu();
                }

                protected function tearDown()
                {

                }
                    
               /**
                * @expectedException Exception
                * {{{ testGetXiaoquLookAndLookRecommend_0
                */
               
          public function testPutiGetXiaoquLookAndLookRecommend_0()
          {
        $xiaoquId=0;
        $number=10;
        $uuid=0;


                      $rs = $this->daoRecommendXiaoqu->getXiaoquLookAndLookRecommend($xiaoquId, $number, $uuid);
          }
      /*}}}*/    


               }