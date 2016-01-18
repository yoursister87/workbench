<?php
            /**
             * @package              
             * @subpackage           
             * @author               $Author: zhangrong3 $
             * @file                 $HeadURL$
             * @version              $Rev$
             * @lastChangeBy         $LastChangedBy$
             * @lastmodified         $LastChangedDate$
             * @copyright            Copyright (c) 2015, www.ganji.com
             */
            

            class Service_Data_Recommend_Part_Commercial_Puti_07f5f6ff1eca996f_Test extends Testcase_PTest
            {
                public $serviceDataRecommendPartCommercial;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->serviceDataRecommendPartCommercial = new Service_Data_Recommend_Part_Commercial( );
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetRecommendResult_0 */
               
          public function testPutiGetRecommendResult_0()
          {

        $queryFilterArr=array (
  0 => 1,
  1 => 2,
);
        $num=1;

        $fakeObj_1= $this->genObjectMock("Dao_Recommend_House", array("getListSimilarHousing"), array(), "", 0);
        $fakeObj_1->expects($this->any())->method("getListSimilarHousing")->will($this->returnValue(array()));

           $fakeObj_same = $this->genObjectMock("Service_Data_Recommend_Part_Commercial", array("fillRecommendParams"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("fillRecommendParams")->will($this->returnValue(array('agent'=>1)));

        Gj_LayerProxy::registerProxy("Dao_Recommend_House", $fakeObj_1);

                      $rs = $fakeObj_same->getRecommendResult($queryFilterArr, $num);
        
        $expect = array (
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }