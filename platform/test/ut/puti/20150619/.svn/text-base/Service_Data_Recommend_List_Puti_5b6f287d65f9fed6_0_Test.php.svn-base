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
            

            class Service_Data_Recommend_List_Puti_5b6f287d65f9fed6_Test extends Testcase_PTest
            {
                public $serviceDataRecommendList;

                protected function setUp()
                {
                    parent::setUp();
                    Gj_Layerproxy::$is_ut = true;
                    $this->serviceDataRecommendList = new Service_Data_Recommend_List();
                }

                protected function tearDown()
                {

                }
                    
               /*{{{ testGetRecommendList_0 */
               
          public function testPutiGetRecommendList_0()
          {
        $queryFilterArr='1';
        $num=1;


                      $rs = $this->serviceDataRecommendList->getRecommendList($queryFilterArr, $num);
        
        $expect = array (
  'errorno' => 1002,
  'errormsg' => '[参数不合法]',
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }