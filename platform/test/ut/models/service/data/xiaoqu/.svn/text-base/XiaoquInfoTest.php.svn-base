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

class XiaoquInfoTest extends Testcase_PTest
{
    protected $xiaoquInfo = array(
            'id' => '6734' ,
            'name' =>  'SOHO北京公馆',
            'pinyin' => 'sohobeijinggongguan', 
            'latlng' => 'b116.46170726184,39.954221402741',
            'thumb_image' =>   '' ,
            'city' =>   'bj' ,
            'district_id' =>   '1',
            'street_id' =>   '6' , 
            'address' =>   '新源南路与新源街路口西北角' 
    );
    protected $geoInfo = array(
            'district_info' => array(
                'type' =>   '3' ,  
                'id' =>   '174' ,
                'script_index' =>   '1' ,
                'parent_id' =>   '12' , 
                'name' =>   '朝阳',
                'url' =>   'chaoyang' , 
                'location' =>   'b116.4500837025,39.927533915375'
            ),
            'street_info' => array ( 
                'type' =>   '4' ,
                'id' =>   '219' ,
                'script_index' =>   '6',
                'parent_id' =>   '174', 
                'name' =>   '燕莎' , 
                'url' =>   'yansha' ,
                'location' =>   'b116.46984808657,39.954967181691' 
            )
    );
    protected $fileds = array('id', 'name', 'pinyin', 'city');
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    /**testGetXiaoquBaseInfoByCityPinyin{{{*/
    public function testGetXiaoquBaseInfoByCityPinyin(){
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $this->xiaoquInfo,
        );
        $mockObj = $this->genObjectMock("Dao_Xiaoqu_XiaoquXiaoqu", array('getXiaoquInfoByCityPinyin'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByCityPinyin')
                ->will($this->returnValue($this->xiaoquInfo));

        $obj = new Service_Data_Xiaoqu_Info();
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);
        $ret = $obj->getXiaoquBaseInfoByCityPinyin('bj', 'shangdidongli');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquBaseInfoByCityPinyinOfCatch{{{*/
    public function testGetXiaoquBaseInfoByCityPinyinOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock("Dao_Xiaoqu_XiaoquXiaoqu", array('getXiaoquInfoByCityPinyin'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByCityPinyin')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));

        $obj = new Service_Data_Xiaoqu_Info();
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);
        $ret = $obj->getXiaoquBaseInfoByCityPinyin('', '');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquBaseInfoByCityName{{{*/
    /**
     * @dataProvider providerXiaoquName
     */
    public function testGetXiaoquBaseInfoByCityName($xiaoquInfo, $retInfo){
        //$xiaoquInfo[] = array_merge($this->xiaoquInfo, $this->geoInfo);
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $xiaoquInfo,
        );
        $mockObj = $this->genObjectMock("Dao_Xiaoqu_XiaoquXiaoqu", array('getXiaoquInfoByCityName'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByCityName')
                ->will($this->returnValue($retInfo));

        $obj = new Service_Data_Xiaoqu_Info();
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);
        $ret = $obj->getXiaoquBaseInfoByCityName('bj', '上地东里');
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerXiaoquName{{{*/
    public function providerXiaoquName(){
        $xiaoquInfo[] = array_merge($this->xiaoquInfo, $this->geoInfo);
        return array(
            array($xiaoquInfo, array(0 => $this->xiaoquInfo)),
            array(array(), array())
        );
    }//}}}
    /**testGetXiaoquBaseInfoByCityNameOfCatch{{{*/
    public function testGetXiaoquBaseInfoByCityNameOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock("Dao_Xiaoqu_XiaoquXiaoqu", array('getXiaoquInfoByCityName'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByCityName')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));

        $obj = new Service_Data_Xiaoqu_Info();
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);
        $ret = $obj->getXiaoquBaseInfoByCityName('', '');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquInfoById{{{*/
    public function testGetXiaoquInfoById(){
         $xiaoquStatInfo = array(
            0 => array(
                'avg_price' => 1200,
                'avg_price_change' => 0
            ),
        );
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $this->xiaoquInfo,
        );
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquXiaoqu' => array(
                'getXiaoquInfoById' => array(
                    'return' => $this->xiaoquInfo,
                    'params' => array(1418, $this->fileds)
                ),
            ),
            'Dao_Xiaoqu_XiaoquStat' => array(
                'getXiaoquStatInfoByXiaoquId' => array(
                    'return' => $xiaoquStatInfo,
                    'params' => array(array(1418), array('avg_price', 'avg_price_change'))
                ),
            ),
        );
        $mockObj = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj['Dao_Xiaoqu_XiaoquXiaoqu']);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj['Dao_Xiaoqu_XiaoquStat']);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquInfoById(1418, $this->fileds);
        $this->assertEquals($data, $ret);

        $dataNeed = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array_merge($this->xiaoquInfo, array('avg' => 1200, 'avg_price_change' => 0)),
        );
        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquInfoById(1418, $this->fileds, true);
        $this->assertEquals($dataNeed, $ret);
    }//}}}
    /**testGetXiaoquInfoByIdOfCatch{{{*/
    public function testGetXiaoquInfoByIdOfCatch(){
         $xiaoquStatInfo = array(
            0 => array(
                'avg_price' => 1200,
                'avg_price_change' => 0.2
            ),
        );
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquStat' => array(
                'getXiaoquStatInfoByXiaoquId' => array(
                    'return' => $xiaoquStatInfo,
                    'params' => array(array(1418), array('avg_price', 'avg_price_change'))
                ),
            ),
        );
        $mockObj = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj['Dao_Xiaoqu_XiaoquStat']);

        $mockXiaoqu = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('getXiaoquInfoById'));
        $mockXiaoqu->expects($this->any())
                ->method('getXiaoquInfoById')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockXiaoqu);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquInfoById('', '');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquInfoByIds{{{*/
    public function testGetXiaoquInfoByIds(){
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(0 => $this->xiaoquInfo),
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('getXiaoquInfoByIds'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByIds')
                ->will($this->returnValue(array(0 => $this->xiaoquInfo)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquInfoByIds(array(1418), $this->fileds);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquInfoByIdsOfCatch{{{*/
    public function testGetXiaoquInfoByIdsOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquXiaoqu', array('getXiaoquInfoByIds'));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByIds')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquInfoByIds('1418', $this->fileds);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquStatInfoByXiaoquId{{{*/
    public function testGetXiaoquStatInfoByXiaoquId(){
         $xiaoquStatInfo = array(
            0 => array(
                'avg_price' => 1200,
                'avg_price_change' => 0
            ),
        );
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $xiaoquStatInfo,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquStat', array('getXiaoquStatInfoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquStatInfoByXiaoquId')
                ->will($this->returnValue($xiaoquStatInfo));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquStatInfoByXiaoquId(array(1418), array('avg_price', 'avg_price_change'));
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquStatInfoByXiaoquIdOfCatch{{{*/
    public function testGetXiaoquStatInfoByXiaoquIdOfCatch(){
         $xiaoquStatInfo = array(
            0 => array(
                'avg_price' => 1200,
                'avg_price_change' => 0
            ),
        );
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquStat', array('getXiaoquStatInfoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquStatInfoByXiaoquId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj);

        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquStatInfoByXiaoquId('aa', '123');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquPeitaoV2ByXiaoquId{{{*/
    public function testGetXiaoquPeitaoV2ByXiaoquId(){
        $content = array(0 => array('distance' => 12, 'duration' => 34));
        $info = array(
            0 => array(
                'id' => 12,
                'xiaoqu_id' => 1418,
                'content' => json_encode($content)
            ),
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquPeitaoV2', array('getXiaoquPeitaoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquPeitaoByXiaoquId')
                ->will($this->returnValue($info));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquPeitaoV2', $mockObj);

        $info[0]['content'] = $content;
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $info,
        );
        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquPeitaoV2ByXiaoquId(1418);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquPeitaoV2ByXiaoquIdOfCatch{{{*/
    public function testGetXiaoquPeitaoV2ByXiaoquIdOfCatch(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquPeitaoV2', array('getXiaoquPeitaoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquPeitaoByXiaoquId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquPeitaoV2', $mockObj);

        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquPeitaoV2ByXiaoquId(1418);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquLockInfoByXiaoquId{{{*/
    public function testGetXiaoquLockInfoByXiaoquId(){
        $info = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquLock', array('getXiaoquLockInfoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquLockInfoByXiaoquId')
                ->will($this->returnValue($info));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquLock', $mockObj);

        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $info,
        );
        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquLockInfoByXiaoquId(1418);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquLockInfoByXiaoquIdOfCatch{{{*/
    public function testGetXiaoquLockInfoByXiaoquIdOfCatch(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquLock', array('getXiaoquLockInfoByXiaoquId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquLockInfoByXiaoquId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquLock', $mockObj);

        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $obj = new Service_Data_Xiaoqu_Info();
        $ret = $obj->getXiaoquLockInfoByXiaoquId(1418);
        $this->assertEquals($data, $ret);
    }//}}}
}
