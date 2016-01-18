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

class XiaoquStatTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquStatInfoByXiaoquIdException(){
        $obj = new Dao_Xiaoqu_XiaoquStat();
        $obj->getXiaoquStatInfoByXiaoquId('12', 'id');
        $obj->getXiaoquStatInfoByXiaoquId(array(), array('id'));
        $obj->getXiaoquStatInfoByXiaoquId(array(12), 'id');
    }
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquStatInfoByXiaoquIdFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquStat', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj);

        $ret = $mockObj->getXiaoquStatInfoByXiaoquId(array(12), array());
    }
    public function testGetXiaoquStatInfoByXiaoquId(){
        $val = array(0 => array(1));
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquStat', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($val));

        $ret = $mockObj->getXiaoquStatInfoByXiaoquId(array(12), array());
        $this->assertEquals($val, $ret);
    }
}
