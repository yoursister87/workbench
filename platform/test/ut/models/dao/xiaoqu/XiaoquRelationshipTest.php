<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhenyangze<zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class XiaoquRelationshipTest extends TestCase_PTest{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /*{{{testGetXiaoquRelationship*/
    public function testGetXiaoquRelationship(){
        $data = array(1,2,3);
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquRelationship', array('select'));
        $mockObj->expects($this->any())
            ->method("select")
            ->will($this->returnValue($data));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquRelationship', $mockObj);

        $ret = $mockObj->getXiaoquRelationship(1, 1);
        $this->assertEquals($ret, $data);
    }
    /*}}}*/
    /*{{{testGetXiaoquRelationshipException*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquRelationshipException(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquRelationship', array('select'));
        $mockObj->expects($this->any())
            ->method("select")
            ->will($this->returnValue(False));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquRelationship', $mockObj);
        $ret = $mockObj->getXiaoquRelationship(1, 1);
    }
    /*}}}*/

}

