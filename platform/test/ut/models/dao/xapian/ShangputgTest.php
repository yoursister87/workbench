<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class ShangputgTest extends Testcase_PTest{
    
      protected function setUp(){
         Gj_Layerproxy::$is_ut = true;
       }

      public function testPreSearch(){
          $obj = new Dao_Xapian_Shangputg();
          $mockClass = array(
                        'Util_XapianSearchHandleUtil' => array(
                           'query' => array(
                              'return' => 'SUCCESS',
                              )),
                        );
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
          $queryFilterArr = array('queryFilter' =>
                               array('city_code' => 0,
                                  'offset_limit' => array(0, 10),
                                  'agent' => 3,
                       ));
          $ret = $obj->preSearch($queryFilterArr);
          $this->assertEquals($ret, 'SUCCESS');
      }

      public function testGetSearchResult(){
        $mockClass = array(
                        'Util_XapianSearchHandleUtil' => array(
                           'getResult' => array(
                              'return' =>  'SUCCESS'
                           ))
                      );
        $mockObj = $this->genAllObjectMock($mockClass);
        Gj_LayerProxy::registerProxy('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
        $obj = new Dao_Xapian_Shangputg();
        $ret = $obj->getSearchResult(111);
        $this->assertEquals($ret, 'SUCCESS');
      }
}
