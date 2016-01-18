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
            

            class Service_Data_Recommend_List_Puti_4d0a66374fd01f6e_Test extends Testcase_PTest
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

        $queryFilterArr=array (
  'major_category_script_index' => 1,
  'city_domain' => 'bj',
);
        $num=1;

        $fakeObj_0= $this->genObjectMock("Service_Data_Recommend_Part_Commercial", array("getRecommendResult"), array(), "", 0);
        $fakeObj_0->expects($this->any())->method("getRecommendResult")->will($this->returnValue(array(1,2)));

           $fakeObj_same = $this->genObjectMock("Service_Data_Recommend_List", array("getSubDataService"), array(), "", 0);
           $fakeObj_same->expects($this->any())->method("getSubDataService")->will($this->returnValue($fakeObj_0));

        Gj_LayerProxy::registerProxy("Service_Data_Recommend_Part_Commercial", $fakeObj_0);

                      $rs = $fakeObj_same->getRecommendList($queryFilterArr, $num);
        
        $expect = array (
  'errorno' => '0',
  'errormsg' => '[数据返回成功]',
  'data' => 
  array (
    0 => 1,
    1 => 2,
  ),
);

        $this->assertEquals($expect, $rs);


          }
      /*}}}*/    


               }