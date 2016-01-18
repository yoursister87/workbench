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

class NewsTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testAddXiaoquNews{{{*/
    public function testAddXiaoquNews(){
        $info = array(
            'xiaoquId' => 1418,
            'accountId' => 29998,
            'userId' => 123456789,
            'content' => "新改版接口调用，当然也是新的结构哦~~~",
            'ip' => 123456,
            'domain' => 'bj',
            'imageList' =>  array(
                "xxxxxxxxxxxxxxxxxxxxxxx",
                "ssssssssssssssssssssssssss",
                "uuuuuuuuuuuuuuuuuuuuuu"
            )
        );
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => 12,
        );
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquNews' => array(
                'addXiaoquNews' => array(
                    'return' => 12
                ),
            ),
            'Dao_Xiaoqu_XiaoquNewsImage' => array(
                'addXiaoquNewsImage' => array(
                    'return' => 12
                )
            )
        );
        $mockObj = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $mockObj['Dao_Xiaoqu_XiaoquNews']);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNewsImage', $mockObj['Dao_Xiaoqu_XiaoquNewsImage']);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->addXiaoquNews($info);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testAddXiaoquNewsOfCatch{{{*/
    public function testAddXiaoquNewsOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $news = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('addXiaoquNews'));
        $news->expects($this->at(0))
            ->method('addXiaoquNews')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $news);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->addXiaoquNews(array());
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetAndPatchNewsImagesListByNewsId{{{*/
    public function testGetAndPatchNewsImagesListByNewsId(){
        $list = array(0 => array(1), 2 => array(3));
        $mockObj = $this->genObjectMock('Service_Data_Xiaoqu_News', array('getXiaoquNewsImagesListByNewsId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquNewsImagesListByNewsId')
                ->will($this->returnValue(array('errorno' => '0', 'data' =>$list)));

        $ret = $mockObj->getAndPatchNewsImagesListByNewsId(123);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquNewsImagesListByNewsId{{{*/
    public function testGetXiaoquNewsImagesListByNewsId(){
        $list = array(0 => array(1), 2 => array(3));
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $list,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('getXiaoquNewsImageListByNewsId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquNewsImageListByNewsId')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNewsImage', $mockObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->getXiaoquNewsImagesListByNewsId(123);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsImagesListByNewsIdOfCatch{{{*/
    public function testGetXiaoquNewsImagesListByNewsIdOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNewsImage', array('getXiaoquNewsImageListByNewsId'));
        $mockObj->expects($this->at(0))
                ->method('getXiaoquNewsImageListByNewsId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNewsImage', $mockObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->getXiaoquNewsImagesListByNewsId('a');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsList{{{*/
    public function testGetXiaoquNewsList(){
        $info = $list = array(0 => array('id' => 12, 'account_id' => 29998));
        $imageList = array(0 => array('url' => 'xxxxxxxxxx'));
        $info[0]['image_list'] = $imageList;
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $info,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByStatus'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByStatus')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = $this->genObjectMock('Service_Data_Xiaoqu_News', array('getAndPatchNewsImagesListByNewsId'));
        $obj->expects($this->at(0))
                ->method('getAndPatchNewsImagesListByNewsId')
                ->will($this->returnValue($imageList));

        $ret = $obj->getXiaoquNewsList();
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsListOfCatch{{{*/
    public function testGetXiaoquNewsListOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByStatus'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByStatus')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->getXiaoquNewsList();
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsListByAccountId{{{*/
    public function testGetXiaoquNewsListByAccountId(){
        $info = $list = array(0 => array('id' => 12, 'account_id' => 29998));
        $imageList = array(0 => array('url' => 'xxxxxxxxxx'));
        $info[0]['image_list'] = $imageList;
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $info,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByAccountId'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByAccountId')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = $this->genObjectMock('Service_Data_Xiaoqu_News', array('getAndPatchNewsImagesListByNewsId'));
        $obj->expects($this->at(0))
                ->method('getAndPatchNewsImagesListByNewsId')
                ->will($this->returnValue($imageList));

        $ret = $obj->getXiaoquNewsListByAccountId(2998);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsListByAccountIdOfCatch{{{*/
    public function testGetXiaoquNewsListByAccountIdOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByAccountId'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByAccountId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->getXiaoquNewsListByAccountId('a');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsListByXiaoquId{{{*/
    public function testGetXiaoquNewsListByXiaoquId(){
        $info = $list = array(0 => array('id' => 12, 'account_id' => 29998));
        $imageList = array(0 => array('url' => 'xxxxxxxxxx'));
        $info[0]['image_list'] = $imageList;
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $info,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByXiaoquId'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByXiaoquId')
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = $this->genObjectMock('Service_Data_Xiaoqu_News', array('getAndPatchNewsImagesListByNewsId'));
        $obj->expects($this->at(0))
                ->method('getAndPatchNewsImagesListByNewsId')
                ->will($this->returnValue($imageList));

        $ret = $obj->getXiaoquNewsListByXiaoquId(2998);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquNewsListByXiaoquIdOfCatch{{{*/
    public function testGetXiaoquNewsListByXiaoquIdOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('getXiaoquNewsByXiaoquId'));
        $modelObj->expects($this->at(0))
                ->method('getXiaoquNewsByXiaoquId')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->getXiaoquNewsListByXiaoquId('a');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testUpdateXiaoquNewsStatus{{{*/
    public function testUpdateXiaoquNewsStatus(){
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => 1,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('updateXiaoquNewsStatusById'));
        $modelObj->expects($this->at(0))
                ->method('updateXiaoquNewsStatusById')
                ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);


        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->updateXiaoquNewsStatus(2998, 2);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testUpdateXiaoquNewsStatusOfCatch{{{*/
    public function testUpdateXiaoquNewsStatusOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );

        $modelObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquNews', array('updateXiaoquNewsStatusById'));
        $modelObj->expects($this->at(0))
                ->method('updateXiaoquNewsStatusById')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquNews', $modelObj);

        $obj = new Service_Data_Xiaoqu_News();
        $ret = $obj->updateXiaoquNewsStatus('a', '');
        $this->assertEquals($data, $ret);
    }//}}}
}
