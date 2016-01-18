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
class XinloupanMock extends Dao_Xiaoqu_Xinloupan
{
    public function __construct(){
    }
    public function setDbhandler($obj){
        $this->dbHandler = $obj;
    }
}

class XinloupanDaoTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testGetXinloupanIdsByCityArr{{{*/
    public function testGetXinloupanIdsByCityArr(){
        $list = array(0 => array('id' => 12));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanIdsByCityArr(array('sh'));
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXinloupanIdsByCityArrFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanIdsByCityArrFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanIdsByCityArr(array('sh'));
    }//}}}
    /**testGetXinloupanDataByCityArr{{{*/
    public function testGetXinloupanDataByCityArr(){
        $list = array(0 => array('id' => 12));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanDataByCityArr(array('sh'));
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXinloupanDataByCityArrFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanDataByCityArrFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanDataByCityArr(array('sh'));
    }//}}}
    /**testGetXinloupanCountByCityArr{{{*/
    public function testGetXinloupanCountByCityArr(){
        $list = array(0 => array('total' => 12));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanCountByCityArr(array('sh'));
        $this->assertEquals($list[0]['total'], $ret);
    }//}}}
    /**testGetXinloupanCountByCityArrFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanCountByCityArrFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanCountByCityArr(array('sh'));
    }//}}}
    /**testGetXinloupanInfoByXiaoquId{{{*/
    public function testGetXinloupanInfoByXiaoquId(){
        $list = array(0 => array('total' => 12));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanInfoByXiaoquId(1418);
        $this->assertEquals($list[0], $ret);
    }//}}}
    /**testGetXinloupanInfoByXiaoquIdFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanInfoByXiaoquIdFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XinloupanMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXinloupanInfoByXiaoquId(1418);
    }//}}}
    /**testGetXinloupanInfoByXiaoquIdException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanInfoByXiaoquIdException(){
        $obj = new XinloupanMock();
        $obj->getXinloupanInfoByXiaoquId('');

        $obj->getXinloupanInfoByXiaoquId('ab');
    }//}}}
    /**testGetXinloupanCountByCityArrException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanCountByCityArrException(){
        $obj = new XinloupanMock();
        $obj->getXinloupanCountByCityArr('');

        $obj->getXinloupanCountByCityArr('ab');
    }//}}}
    /**testGetXinloupanDataByCityArrException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanDataByCityArrException(){
        $obj = new XinloupanMock();
        $obj->getXinloupanDataByCityArr('');

        $obj->getXinloupanDataByCityArr('ab');
    }//}}}
    /**testGetXinloupanIdsByCityArrException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXinloupanIdsByCityArrException(){
        $obj = new XinloupanMock();
        $obj->getXinloupanIdsByCityArr('');

        $obj->getXinloupanIdsByCityArr('ab');
    }//}}}
}
