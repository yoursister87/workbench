<?php
error_reporting(E_ALL^E_NOTICE);
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
class ZufangTest extends Testcase_PTest{
    
      protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
      }
      public function testPreSearch(){
          $mockClass = array(
                        'Util_XapianSearchHandleUtil' => array(
                           'query' => array(
                              'return' => 'SUCCESS',
                              )),
                        );
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
          //PlatformSingleton::setInstance('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
          $queryFilterArr = array('queryFilter' =>
                               array('city_code' => 0,
                                  'offset_limit' => array(0, 10),
                                  'agent' => 3,
                       ));
          $obj = new Dao_Xapian_Zufang();
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
        //PlatformSingleton::setInstance('Util_XapianSearchHandleUtil', $mockObj['Util_XapianSearchHandleUtil']);
        $obj = new Dao_Xapian_Zufang();
        $ret = $obj->getSearchResult(111);
        $this->assertEquals($ret, 'SUCCESS');
      }

     
}
