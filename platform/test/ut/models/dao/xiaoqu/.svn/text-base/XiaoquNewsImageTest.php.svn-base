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
class XiaoquNewsImageMock extends Dao_Xiaoqu_XiaoquNewsImage
{
    public function __construct(){
    }
}
class XiaoquNewsImageTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testGetXiaoquNewsImageListByNewsIdException{{{*/
    /** 
     * @expectedException Exception
     * @dataProvider providerGetXiaoquNewsImageList
     */
    public function testGetXiaoquNewsImageListByNewsIdException($params){
        $obj = new Dao_Xiaoqu_XiaoquNewsImage();
        $obj->getXiaoquNewsImageListByNewsId($params[0], $params[1]);
    }//}}}
    /**providerGetXiaoquNewsImageList{{{*/
    public function providerGetXiaoquNewsImageList(){
        return array(
            array('', ''),
            array('a', ''),
            array('a', array()),
            array('a', 'abc'),
        );
    }//}}}
    /**testGetXiaoquNewsImageListByNewsId{{{*/
    public function testGetXiaoquNewsImageListByNewsId(){
        $list = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($list));

        $ret = $mockObj->getXiaoquNewsImageListByNewsId(12);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquNewsImageListByNewsIdFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquNewsImageListByNewsIdFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue(false));

        $ret = $mockObj->getXiaoquNewsImageListByNewsId(12);
    }//}}}
    /**testaddXiaoquNewsImageException{{{*/
    /** 
     * @expectedException Exception
     * @dataProvider provideraddXiaoquNewsImage
     */
    public function testaddXiaoquNewsImageException($params){
        $obj = new Dao_Xiaoqu_XiaoquNewsImage();
        $obj->getXiaoquNewsImageListByNewsId($params[0], $params[1], $params[2]);
    }//}}}
    /**provideraddXiaoquNewsImage{{{*/
    public function provideraddXiaoquNewsImage(){
        return array(
            array('', 12, 12),
            array('ab', 12, 12),
            array(12, '', 12),
            array(12, 'ab', 12),
            array(12, 12, ''),
            array(12, 12, 'ab'),
            array('a', 'a', 'a'),
            array('a', 'a', 1),
            array('a', 1, 'a'),
            array(1, 'a', 'a'),
        );
    }//}}}
    /**testtestaddXiaoquNewsImage{{{*/
    public function testtestaddXiaoquNewsImage(){
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->setTime(123);
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(12));

        $ret = $mockObj->addXiaoquNewsImage(12, 13, 'xxxxxx');
        $this->assertEquals(12, $ret);
    }//}}}
    /**testtestaddXiaoquNewsImageFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testtestaddXiaoquNewsImageFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('insert'));
        $mockObj->expects($this->at(0))
                ->method('insert')
                ->will($this->returnValue(False));

        $ret = $mockObj->addXiaoquNewsImage(12, 13, 'xxxxxx');
    }//}}}
    /**testValidatorParameters{{{*/
    public function testValidatorParameters(){
        $obj = new XiaoquNewsImageMock();

        $ret = $obj->validatorParameters('');
        $this->assertequals(false, $ret);

        $ret = $obj->validatorParameters('aa');
        $this->assertequals(false, $ret);

        $ret = $obj->validatorParameters(12);
        $this->assertequals(true, $ret);
    }//}}}
}

