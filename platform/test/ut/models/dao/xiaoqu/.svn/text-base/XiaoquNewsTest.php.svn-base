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
class XiaoquNewsMock extends Dao_Xiaoqu_XiaoquNews
{
    public function __construct(){
    }
    public function setDbHandler($obj){
        $this->dbHandler = $obj;
    }
}
class XiaoquNewsTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testAddXiaoquNewsException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testAddXiaoquNewsException(){
        $obj = new Dao_Xiaoqu_XiaoquNews();
        $obj->addXiaoquNews('');
        $obj->addXiaoquNews('aa');
        $obj->addXiaoquNews(array());
    }//}}}
    /**testAddXiaoquNewsImage{{{*/
    public function testAddXiaoquNewsImage(){
        $info = array(
            'xiaoquId' => 1418,
            'accountId' => 29998,
            'userId' => 123456789,
            'content' => "新改版接口调用，当然也是新的结构哦~~~",
            'ip' => 123456,
            'domain' => 'bj',
        );
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->setTime(123);
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(12));

        $ret = $mockObj->addXiaoquNews($info);
        $this->assertEquals(12, $ret);
    }//}}}
    /**testAddXiaoquNewsFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testAddXiaoquNewsFail(){
        $info = array(
            'xiaoquId' => 1418,
            'accountId' => 29998,
            'userId' => 123456789,
            'content' => "新改版接口调用，当然也是新的结构哦~~~",
            'ip' => 123456,
            'domain' => 'bj',
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(False));

        $ret = $mockObj->addXiaoquNews($info);
    }//}}}
    /**testValidatorParameters{{{*/
    public function testValidatorParameters(){
        $obj = new XiaoquNewsMock();

        $ret = $obj->validatorParameters('');
        $this->assertequals(false, $ret);

        $ret = $obj->validatorParameters('aa');
        $this->assertequals(false, $ret);

        $ret = $obj->validatorParameters(12);
        $this->assertequals(true, $ret);
    }//}}}
    /**testGetXiaoquNewsByXiaoquIdException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByXiaoquIdException(){
        $obj = new Dao_Xiaoqu_XiaoquNews();
        $obj->getXiaoquNewsByXiaoquId('');
        $obj->getXiaoquNewsByXiaoquId('a');
    }//}}}
    /**testGetXiaoquNewsByXiaoquId{{{*/
    public function testGetXiaoquNewsImageListByNewsId(){
        $list = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByXiaoquId(12);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquNewsByXiaoquIdFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByXiaoquIdFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByXiaoquId(12);
    }//}}}
    /**testGetXiaoquNewsByAccountIdException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByAccountIdException(){
        $obj = new Dao_Xiaoqu_XiaoquNews();
        $obj->getXiaoquNewsByAccountId('');
        $obj->getXiaoquNewsByAccountId('a');
    }//}}}
    /**testGetXiaoquNewsByAccountId{{{*/
    public function testGetXiaoquNewsListByAccountId(){
        $list = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByAccountId(12, 3);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquNewsByAccountIdFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByAccountIdFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByAccountId(12);
    }//}}}
    /**testGetXiaoquNewsByStatusException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByStatusException(){
        $obj = new Dao_Xiaoqu_XiaoquNews();
        $obj->getXiaoquNewsByStatus('', '');
        $obj->getXiaoquNewsByStatus('a', 10);
        $obj->getXiaoquNewsByStatus(10, 'a');
    }//}}}
    /**testGetXiaoquNewsByStatus{{{*/
    public function testGetXiaoquNewsListByStatus(){
        $list = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByStatus(12, 3);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquNewsByStatusFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsByStatusFail(){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Gj_Db_DbFactory', $mockObj);

        $obj = new XiaoquNewsMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquNewsByStatus(12);
    }//}}}
    /**testUpdateXiaoquNewsStatusByIdException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testUpdateXiaoquNewsStatusByIdException(){
        $obj = new XiaoquNewsMock();
        $obj->updateXiaoquNewsStatusById('a', 'a');
        $obj->updateXiaoquNewsStatusById(12, 'a');
        $obj->updateXiaoquNewsStatusById('a', 12);
    }//}}}
    /**testUpdateXiaoquNewsStatusByIdFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testUpdateXiaoquNewsStatusByIdFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('update'));
        $mockObj->expects($this->at(0))
                ->method('update')
                ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $mockObj);

        $ret = $mockObj->updateXiaoquNewsStatusById(12, 10);
    }//}}}
    /**testUpdateXiaoquNewsStatusById{{{*/
    public function testUpdateXiaoquNewsStatusById(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('update'));
        $mockObj->expects($this->at(0))
                ->method('update')
                ->will($this->returnValue(12));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $mockObj);

        $ret = $mockObj->updateXiaoquNewsStatusById(12, 10);
        $this->assertEquals(12, $ret);
    }//}}}
}

