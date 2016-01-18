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

class XiaoquAutoIncrementDaoTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /** 
     * @expectedException Exception
     */
    public function testAddXiaoquAutoIncrementException(){
        $obj = new Dao_Xiaoqu_XiaoquAutoIncrement();
        $obj->addXiaoquAutoIncrement(array());

        $obj->addXiaoquAutoIncrement('a');
    }
    public function testAddXiaoquAutoIncrement(){
        $xiaoquInfo = array(
            'xiaoqu_name' => 'name',
            'city_id' => 12,
            'district_id' => 0,
            'street_id' => 0,
            'xiaoqu_address' => 'address'
        );
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->setTime(123);

        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquAutoIncrement', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(true));

        $ret = $mockObj->addXiaoquAutoIncrement($xiaoquInfo);
        $this->assertEquals(true, $ret);
    }
    /** 
     * @expectedException Exception
     */
    public function testAddXiaoquAutoIncrementFail(){
        $xiaoquInfo = array(
            'xiaoqu_name' => 'name',
            'city_id' => 12,
            'district_id' => 0,
            'street_id' => 0,
            'xiaoqu_address' => 'address'
        );
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->setTime(123);

        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquAutoIncrement', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(FALSE));

        $ret = $mockObj->addXiaoquAutoIncrement($xiaoquInfo);
    }
}
