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
class XiaoquXiaoquMock extends Dao_Xiaoqu_XiaoquXiaoqu
{
     public function __construct(){}
     public function setDbhandler($obj){
           $this->dbHandler = $obj;
     }
}
class XiaoquXiaoquTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testGetXiaoquInfoByIdException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByIdException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $obj->getXiaoquInfoById('a', array('id', 'name'));

        $obj->getXiaoquInfoById(12, '123');

        $obj->getXiaoquInfoById('', '123');
    }//}}}
    /**testGetXiaoquInfoById{{{*/
    public function testGetXiaoquInfoById(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoById(12, array('id', 'name'));
        $this->assertEquals($val[0], $ret);
    }//}}}
    /**testGetXiaoquInfoByIdSqlFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByIdSqlFail(){
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoById(12, array('id', 'name'));
    }//}}}
    /*testGetXiaoquInfoByCityException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $ret = $obj->getXiaoquInfoByCity('bj', 'www');
    }
    /*}}}*/
    /*testGetXiaoquInfoByCity{{{*/
    public function testGetXiaoquInfoByCity(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                 ->method('select')
                 ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);
        $ret = $modelObj->getXiaoquInfoByCity('bj', array('id'));
        $this->assertEquals($val, $ret);
    }//}}}
    /*testGetXiaoquInfoByCityDistrictException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityDistrictException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $ret = $obj->getXiaoquInfoByCityDistrict('bj', 0, 'www');
    }
    /*}}}*/
    /*testGetXiaoquInfoByCityDistrict{{{*/
    public function testGetXiaoquInfoByCityDistrict(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                 ->method('select')
                 ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);
        $ret = $modelObj->getXiaoquInfoByCityDistrict('bj', 0,array('id'));
        $this->assertEquals($val, $ret);
    }//}}}
    /*testGetXiaoquInfoByCityDistrictStreetException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityDistrictStreetException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $ret = $obj->getXiaoquInfoByCityDistrictStreet('bj', 0, 1,'www');
    }
    /*}}}*/
    /*testGetXiaoquInfoByCityDistrict{{{*/
    public function testGetXiaoquInfoByCityDistrictStreet(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                 ->method('select')
                 ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);
        $ret = $modelObj->getXiaoquInfoByCityDistrictStreet('bj', 0, 0, array('id'));
        $this->assertEquals($val, $ret);
    }//}}}

    /*}}}*/
    /* testGetXiaoquInfoByCityByRandomException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityByRandomException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $ret = $obj->getXiaoquInfoByCityByRandom('www', 'www');
    }
    /*}}}*/
    /*testGetXiaoquInfoByCityByRandom{{{*/
    public function testGetXiaoquInfoByCityByRandom(){
        $fc = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $fc->expects($this->any())
           ->method('select')
           ->will($this->returnValue(array(0 => array(1))));

        $modelObj = $this->genObjectMock('XiaoquXiaoquMock', array('selectByCount'));
        $modelObj->expects($this->any())
                 ->method('selectByCount')
                 ->will($this->returnValue(1));

        $modelObj->setDbhandler($fc);
        $ret = $modelObj->getXiaoquInfoByCityByRandom('bj', array('id', array('id'), 100));
        $this->assertEquals(array('result'=>array(0=>array(1)), 'count'=>1), $ret);
    }
    /*}}}*/
    /**testGetXiaoquInfoByIdsException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByIdsException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $obj->getXiaoquInfoByIds('a', array('id', 'name'));

        $obj->getXiaoquInfoByIds(array(), array('id', 'name'));

        $obj->getXiaoquInfoByIds(array(1418), 'id');
    }//}}}
    /**testGetXiaoquInfoByIds{{{*/
    public function testGetXiaoquInfoByIds(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByIds(array(12), array('id', 'name'));
        $this->assertEquals($val, $ret);
    }//}}}
    /**testGetXiaoquInfoByIdsFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByIdsFail(){
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByIds(array(12), array('id', 'name'));
    }//}}}
    /**testGetXiaoquInfoByCityNameException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityNameException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $obj->getXiaoquInfoByCityName('bj', '名字', 'id');
        $obj->getXiaoquInfoByCityName('bj', '名字', array());
    }//}}}
    /**testGetXiaoquInfoByCityPinyinException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityPinyinException(){
        $obj = new Dao_Xiaoqu_XiaoquXiaoqu();
        $obj->getXiaoquInfoByCityPinyin('', '');
        $obj->getXiaoquInfoByCityPinyin('bj', '');
        $obj->getXiaoquInfoByCityPinyin('', 'pinyin');
    }//}}}
    /**testGetXiaoquInfoByCityName{{{*/
    public function testGetXiaoquInfoByCityName(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByCityName('bj', '名字', array('id', 'name'));
        $this->assertEquals($val, $ret);
    }//}}}
    /**testGetXiaoquInfoByCityNameFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityNameFail(){
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByCityName('bj', '名字', array('id', 'name'));
    }//}}}
    /**testGetXiaoquInfoByCityPinyin{{{*/
    public function testGetXiaoquInfoByCityPinyin(){
        $val = array(0 => array(1));
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($val));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByCityPinyin('bj', 'pinyin');
        $this->assertEquals($val[0], $ret);
    }//}}}
    /**testGetXiaoquInfoByCityPinyinFail{{{*/
    /** 
     * @expectedException Exception
     */
    public function testGetXiaoquInfoByCityPinyinFail(){
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('select'));
        $modelObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue(FALSE));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $modelObj);

        $ret = $modelObj->getXiaoquInfoByCityPinyin('bj', 'pinyin');
    }//}}}
}
