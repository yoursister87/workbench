<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class XiaoquLockTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquLockInfoByXiaoquIdException(){
        $obj = new Dao_Xiaoqu_XiaoquLock();
        $obj->getXiaoquLockInfoByXiaoquId('');
        $obj->getXiaoquLockInfoByXiaoquId('id');
    }
    public function testGetXiaoquLockInfoByXiaoquId(){
        $list = array(0 => array(1), 2 => array(3), 3 => array(4));
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquLock', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquLock', $mockObj);
        $ret = $mockObj->getXiaoquLockInfoByXiaoquId(75, 1, 3);
        $this->assertEquals($list, $ret);
    }
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquLockInfoByXiaoquIdFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquLock', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquLock', $mockObj);
        $ret = $mockObj->getXiaoquLockInfoByXiaoquId(75, 1, 3);
    }
}
