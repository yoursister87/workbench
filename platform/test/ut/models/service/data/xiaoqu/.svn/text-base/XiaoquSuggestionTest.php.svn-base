<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   shenweihai$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Sugggestion extends TestCase_PTest{
     protected function setUp(){
          Gj_LayerProxy::$is_ut = true;
     }

     public function testGetXiaoquListByKeyCityCheck() {
         $obj = new Service_Data_Xiaoqu_Suggestion();
         $b1 = $obj->getXiaoquListByKeyCity('', '');
         $b2 = $obj->getXiaoquListByKeyCity('1234', '');
         $b3 = $obj->getXiaoquListByKeyCity('', '1234');
         $ret= $b1&& $b2&& $b3;
         $this->assertEquals($ret, false);
     }
     public function testGetXiaoquListByKeyCity() {
         $Memcache = $this->genObjectMock('MemCacheAdapter', array('read'));
         $Memcache->expects($this->any())
                  ->method('read')
                  ->will($this->returnValue('[{"name":"xin"}]'));
         Gj_Cache_CacheClient::setInstance($Memcache);
         $obj = new Service_Data_Xiaoqu_Suggestion();
         $ret = $obj->getXiaoquListByKeyCity('www', 'www');
         $this->assertEquals($ret, '[{"name":"xin"}]');

         //normal
         $Memcache = $this->genObjectMock('MemCacheAdapter', array('read'));
         $Memcache->expects($this->any())
                  ->method('read')
                  ->will($this->returnValue(false));
         Gj_Cache_CacheClient::setInstance($Memcache);
         $sug= $this->genObjectMock('Dao_Xiaoqu_XiaoquSuggestion', array('getSuggestion'));
         $sug->expects($this->any())
             ->method('getSuggestion')
             ->will($this->returnValue(array(array('name'=>'tx'))));
         Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquSuggestion', $sug);

         $obj = new Service_Data_Xiaoqu_Suggestion();
         $ret = $obj->getXiaoquListByKeyCity('bj', 'x');
         $this->assertEquals($ret, '[{"name":"tx"}]');
     }
}

