<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Recommend extends TestCase_PTest{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /*{{{testGetXiaoquRelationshipByXiaoquId*/
    public function testGetXiaoquRelationshipByXiaoquId(){
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquStat' => array(
                'getXiaoquStatInfoByXiaoquId' => array(
                    'return' => array(
                        0 => array(
                            'id' => 1562464,
                            'xiaoqu_id' => 2,
                        ),
                        1 => array(
                            'id' => 1562465,
                            'xiaoqu_id' => 3,
                        ),
                   ),
                ),
            ),
            'Dao_Xiaoqu_XiaoquXiaoqu' => array(
                'getXiaoquInfoByIds' => array(
                    'return' => array(
                         0 => array(
                             'id' => 2,
                             'name' => '宝润苑',
                             'pinyin' => 'baorunyuan',
                             'avg_price' => 0,
                             'thumb_image' => '',
                          ),
                         1 => array(
                             'id' => 3,
                             'name' => '名都园',
                             'pinyin' => 'mingduyuan',
                             'avg_price' => 0,
                             'thumb_image' => '',
                         ),
                   ),
                )
            ),
            'Dao_Xiaoqu_XiaoquRelationship' => array(
                'getXiaoquRelationship' => array(
                    'return' => array(
                        0 => array(
                            'id' => 2857,
                            'xiaoqu_id' => 491523,
                            'type' => 1,
                            'similar_xiaoqu' => '{"2": {"y": "2398.78", "x": "8606.51", "r": "1.00"}, "3": {"y": "21692.35", "x": "17810.33", "r": "0.80"}}',
                            'mtime' => 1403859745
                        ),
                    ), 
                ),
            ),
            'Service_Data_Xiaoqu_Recommend' => array(
            ),
        ); 

        $data = array(
            1 => array(
                'id' => 2857,
                'xiaoqu_id' => 491523,
                'type' => 1,
                'similar_xiaoqu' => array(
                    2 => array(
                        'id' => 1562464,
                        'xiaoqu_id' => 2,
                        'name' => '宝润苑',
                        'pinyin' => 'baorunyuan',
                        'avg_price' => 0, 
                        'x' => '8606.51',
                        'y' => '2398.78',
                        'r' => '1.00',
                        'thumb_image' => '' 
                    ),
                    3 => array(
                        'id' => 1562465,
                        'xiaoqu_id' => 3,
                        'name' => '名都园',
                        'pinyin' => 'mingduyuan',
                        'avg_price' => 0,
                        'x' => '17810.33',
                        'y' => '21692.35',
                        'r' => '0.80',
                        'thumb_image' => '' 
                    ),
                ),
                'mtime' => 1403859745
            ),
        );
        $mockObj = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquRelationship', $mockObj['Dao_Xiaoqu_XiaoquRelationship']);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObj['Dao_Xiaoqu_XiaoquStat']);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObj['Dao_Xiaoqu_XiaoquXiaoqu']);
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Recommend', $mockObj['Service_Data_Xiaoqu_Recommend']);

        $obj = new Service_Data_Xiaoqu_Recommend();
        $result = $obj->getXiaoquRelationshipByXiaoquId(491523, 1);
        $this->assertEquals($data, $result);
    }
    /*}}}*/
    /*{{{testGetXiaoquRelationshipByXiaoquIdOfCatch*/
    public function testGetXiaoquRelationshipByXiaoquIdOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $relationModel = $this->genObjectMock('Dao_Xiaoqu_XiaoquRelationship', array('getXiaoquRelationship'));
        $relationModel->expects($this->any())
            ->method('getXiaoquRelationship')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquRelationship', $relationModel);

        $obj = new Service_Data_Xiaoqu_Recommend();
        $ret = $obj->getXiaoquRelationshipByXiaoquId(1);
        $this->assertEquals($data, $ret);
    }
    /*}}}*/
    /*{{{testUpdateKeyByXiaoquIdData*/
    public function testUpdateKeyByXiaoquIdData(){
        return array(
            array('a', 'id',array()),
            array(
                array(
                    0 => array(
                        'id' => 1
                    )
                ), 
                'id',
                array(
                    1 => array(
                        'id' => 1
                    )
                )
            ),
        );
    }
    /*}}}*/
    /*{{{testUpdateKeyByXiaoquId*/
    /**
     * @dataProvider testUpdateKeyByXiaoquIdData
     */
    public function testUpdateKeyByXiaoquId($info, $key, $data){
        $obj = new Service_Data_Xiaoqu_Recommend();
        $ret = $obj->updateKeyByXiaoquId($info, $key);
        $this->assertEquals($ret, $data);
    }
    /*}}}*/
}
