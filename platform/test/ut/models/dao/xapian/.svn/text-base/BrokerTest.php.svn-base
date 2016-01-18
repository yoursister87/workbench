<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   renyajing$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
error_reporting(E_ALL^E_NOTICE);
class BrokerTest extends Testcase_PTest
{
      protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
      }
      /**testGetSourceList{{{*/
      /**
        * @dataProvider providertestGetSourceList
        * @expectedException Exception
       */
      public function testGetSourceListException($searchId, $searchResult){
          $queryFilterArr = array('queryFilter' =>
                  array('city_code' => 0,
                      'offset_limit' => array(0, 10),
                      'agent' => 3,
                      ));
          $mockClass = array(
                        'Util_XapianSearchHandleUtil' => array(
                           'query' => array(
                              'return' => $searchId,
                              ),
                           'getResult' => array(
                              'return' => $searchResult,
                           )
                        ),
                  );
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
          $obj = new Dao_Xapian_Broker();
          $ret = $obj->getSourceList($queryFilterArr);
          //$this->assertEquals($ret, $searchResult);
      }
      /**{{{providertestGetSourceList*/
      public function providertestGetSourceList(){
          return array(
                    array('wrong', array()),
                    array(0, array()),
                    //array(123, array('success')),
            );
      }//}}}
      public function testGetSourceList(){
          $queryFilterArr = array('queryFilter' =>
                  array('city_code' => 0,
                      'offset_limit' => array(0, 10),
                      'agent' => 3,
                      ));
          $mockClass = array(
                        'Util_XapianSearchHandleUtil' => array(
                           'query' => array(
                              'return' => 123,
                              ),
                           'getResult' => array(
                              'return' => array('success'),
                           )
                        ),
                  );
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
          $obj = new Dao_Xapian_Broker();
          $ret = $obj->getSourceList($queryFilterArr);
          $this->assertEquals($ret, array('success'));
      }
}
