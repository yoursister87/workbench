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

class XinloupanTest extends Testcase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    /**providerNeedsInfo{{{*/
    protected function providerNeedsInfo($key){
        $infoList = array(
            'loupanList' => array(
                524570 => array(
                    'id' => "31",
                    'xiaoqu_id' => "524570",
                    'city' => "sh",
                    'start_sale' => "2012年10月",
                    'check_in' => "2015年年底",
                    'homes_num' => "3200",
                    'pre_sale_permit' => "青浦房管（2013）预备字0000354号",
                    'intro' => "区域内唯一上海产权房， 11号线直达徐家汇。易买得生活全配套，完全人车分层。",
                    'sales_office_address' => "江苏省苏州市昆山市花桥经济开发区绿地大道211号",
                    'sales_office_tel' => "021-39805555",
                    'status' => "0",
                    'price' => "11800",
                    'activity' => "存3万享6万优惠",
                    'hot_huxing' => "2室（83㎡）;2室（88㎡）;2室（104㎡）;1室（61㎡）",
                    'loupan_label' => "上海产权、低首付、江景房、11号线",
                    'huxing_price' => json_encode(array('C1C4户型' => 1038400, 'D2户型' => 719800, 'C2C3户型' => 731600))
                )
            ),
            'xiaoquList' => array(
                0 => array(
                    'id' => "524570",
                    'pinyin' => "lv4di4zzzzzzcheng2bin1jiang1hui4",
                    'city' => "sh",
                    'name' => "绿地21城滨江汇",
                    'latlng' => "b121.084235,31.307285",
                    'thumb_image' => "gjfstmp2/M00/00/01/wKgCzFP-792IJ5xBAAFfyuMCjvcAAAAuQChwY0AAV,i584.jpg",
                    'district_id' => "7",
                    'street_id' => "104",
                    'address' => "昆山市花桥经济开发区集善路102号",
                    'developer' => "昆山联合商业发展有限公司",
                )
            ),
            'xiaoquIds' => array(524570),
            'huxingList' => array(
                0 => array(
                    'id' => "993781",
                    'title' => "C1C4户型",
                    'image' => "gjfstmp2/M00/00/01/wKgCzFP-7,GIZgjZAAyCmNREVIUAAAAuQC3R,sADIKw023.jpg",
                    'thumb_image' =>  NULL,
                    'huxing_shi' => "2",
                    'huxing_ting' => "2",
                    'huxing_wei' => "1",
                    'huxing_chu' => "1",
                    'area' => "88"
                )
            ),
            'huxingPriceList' =>array(
                'C1C4户型' => array(
                    'id' => "993781",
                    'title' => "C1C4户型",
                    'image' => "gjfstmp2/M00/00/01/wKgCzFP-7,GIZgjZAAyCmNREVIUAAAAuQC3R,sADIKw023.jpg",
                    'thumb_image' => NULL,
                    'huxing_shi' => "2",
                    'huxing_ting' => "2",
                    'huxing_wei' => "1",
                    'huxing_chu' => "1",
                    'area' => "88",
                    'price' => 1038400 
                )
            ),
        );
        return $infoList[$key];
    }//}}}
    /**testGetXinloupanCityConfig{{{*/
    public function testGetXinloupanCityConfig(){
        $cityConfig = array(
            'sh' => array('sh', 'su'),
            'su' => array('sh', 'su'),
        );
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXinloupanCityConfig();
        $this->assertEquals($cityConfig, $ret);
    }//}}}
    /**testCheckCityValid{{{*/
    public function testCheckCityValid(){
        $cityArr = array('sh', 'su');
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->checkCityValid('sh');
        $this->assertEquals($cityArr, $ret);
    }//}}}
    /**testCheckCityValidException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testCheckCityValidException(){
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->checkCityValid('bj');
    }//}}}
    /**testGetXinloupanDataByCity{{{*/
    public function testGetXinloupanDataByCity(){
        $loupanList = $this->providerNeedsInfo('loupanList');
        $total = 5;
        $data = array('list' => $loupanList, 'total' => $total);
        $modelObj = $this->genObjectMock("Dao_Xiaoqu_Xinloupan", array("getXinloupanDataByCityArr", "getXinloupanCountByCityArr"));
        $modelObj->expects($this->any())
                ->method('getXinloupanDataByCityArr')
                ->will($this->returnValue($loupanList));
        $modelObj->expects($this->any())
                ->method('getXinloupanCountByCityArr')
                ->will($this->returnValue($total));
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_Xinloupan', $modelObj);
        $ret = $obj->getXinloupanDataByCity(array('sh', 'su'));
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquInfoByIds{{{*/
    /**
     * @dataProvider providerGetXiaoquInfoByIds
     */
    public function testGetXiaoquInfoByIds($params, $data){
        $serviceObj = $this->genObjectMock("Service_Data_Xiaoqu_Info", array("getXiaoquInfoByIds"));
        $serviceObj->expects($this->any())
                ->method('getXiaoquInfoByIds')
                ->will($this->returnValue(array('errorno' => $params[1], 'data' => $data)));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);
        $obj = new Service_data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquInfoByIds($params[0]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerGetXiaoquInfoByIds{{{*/
    public function providerGetXiaoquInfoByIds(){
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');
        $xiaoquIds = $this->providerNeedsInfo('xiaoquIds');
        return array(
            array(
                array($xiaoquIds, '0'),     //正常
                $xiaoquList,
            ),
            array(
                array($xiaoquIds, '10'),      //接口返回失败 
                array(),
            ),
            array(
                array(28, '0'),        //参数不合法
                array(),
            ),
        );
    }//}}}
    /**testFormataXinloupanData{{{*/
    public function testFormataXinloupanData(){
        $loupanList = $xinloupan = $this->providerNeedsInfo('loupanList');
        $loupanList[524570]['huxing_price'] = json_decode($loupanList[524570]['huxing_price'], true);
        $xiaoquIds = $this->providerNeedsInfo('xiaoquIds');

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->formataXinloupanData($xinloupan);
        $this->assertEquals(array($loupanList, $xiaoquIds), $ret);
        //参数不合法
        $ret = $obj->formataXinloupanData('123');
        $this->assertEquals(array(array(), array()), $ret);
    }//}}}
    /**testMergeXiaoquXinloupan{{{*/
    public function testMergeXiaoquXinloupan(){
        $loupanList = $this->providerNeedsInfo('loupanList');
        $loupanList[524570]['huxing_price'] = json_decode($loupanList[524570]['huxing_price'], true);
        $xiaoquInfo = $this->providerNeedsInfo('xiaoquList');
        $data[524570] = array_merge($xiaoquInfo[0], $loupanList[524570]);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->mergeXiaoquXinloupan($loupanList, $xiaoquInfo);
        $this->assertEquals($data, $ret);

        //以下为参数不合法
        $ret = $obj->mergeXiaoquXinloupan(array(), $xiaoquInfo);
        $this->assertEquals(array(), $ret);

        $ret = $obj->mergeXiaoquXinloupan($loupanList, array());
        $this->assertEquals(array(), $ret);

        $ret = $obj->mergeXiaoquXinloupan(array(), array());
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testGetXiaoquInfoById{{{*/
    /**
     * @dataProvider providerGetXiaoquInfoById
     */
    public function testGetXiaoquInfoById($params, $data){
        $serviceObj = $this->genObjectMock("Service_Data_Xiaoqu_Info", array("getXiaoquInfoById"));
        $serviceObj->expects($this->any())
                ->method('getXiaoquInfoById')
                ->will($this->returnValue(array('errorno' => $params[1], 'data' => $data)));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);
        $obj = new Service_data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquInfoById($params[0]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerGetXiaoquInfoById{{{*/
    public function providerGetXiaoquInfoById(){
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');
        $xiaoquIds = $this->providerNeedsInfo('xiaoquIds');
        return array(
            array(
                array($xiaoquIds[0], '0'),     //正常
                $xiaoquList,
            ),
            array(
                array($xiaoquIds[0], '10'),      //接口返回失败 
                array(),
            ),
            array(
                array('abc', '0'),        //参数不合法
                array(),
            ),
        );
    }//}}}
    /**testGetXinloupanDataByXiaoquId{{{*/
    /**
     * @dataProvider providerGetXinloupanDataByXiaoquId
     */
    public function testGetXinloupanDataByXiaoquId($params, $data){
        $mockObj = $this->genObjectMock("Dao_Xiaoqu_Xinloupan", array("getXinloupanInfoByXiaoquId"));
        $mockObj->expects($this->any())
                ->method('getXinloupanInfoByXiaoquId')
                ->will($this->returnValue($params[1]));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_Xinloupan', $mockObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXinloupanDataByXiaoquId($params[0]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerGetXinloupanDataByXiaoquId{{{*/
    public function providerGetXinloupanDataByXiaoquId(){
        $loupanInfo = $this->providerNeedsInfo('loupanList');
        $returnData = $loupanInfo = $loupanInfo['524570'];
        $returnData['huxing_price'] = json_decode($returnData['huxing_price'], true);
        return array(
            array(
                array(524570, $loupanInfo),
                $returnData
            ),
            array(
                array(-1, $loupanInfo),
                array()
            ),
        );
    }//}}}
    /**testGetXinloupanListByCity{{{*/
    public function testGetXinloupanListByCity(){
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => "",
        );
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', null);
        $mockObj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $mockObj->getXinloupanListByCity('bj');
        $this->assertEquals($returnData, $ret);

        $loupanList = $this->providerNeedsInfo('loupanList');
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');
        $xiaoquIds = $this->providerNeedsInfo('xiaoquIds');
        $loupanList[524570]['huxing_price'] = json_decode($loupanList[524570]['huxing_price'], true);
        $data[524570] = array_merge($xiaoquList[0], $loupanList[524570]);

        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array('item' => $data, 'total' => 5)
        );

        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'getXinloupanDataByCity',
                'formataXinloupanData',
                'getXiaoquInfoByIds',
                'mergeXiaoquXinloupan',
                'readCacheData',
                'writeCacheData'
                )
        );
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByCity')
                ->will($this->returnValue(array('list' => $loupanList, 'total' => 5)));
        $mockObj->expects($this->any())
                ->method('formataXinloupanData')
                ->will($this->returnValue(array($loupanList, $xiaoquIds)));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByIds')
                ->will($this->returnValue($xiaoquList));
        $mockObj->expects($this->any())
                ->method('mergeXiaoquXinloupan')
                ->will($this->returnValue($data));
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->getXinloupanListByCity('sh');
        $this->assertEquals($returnData, $ret);
    }//}}}
    /**testGetXinloupanInfoByXiaoquId{{{*/
    public function testGetXinloupanInfoByXiaoquId(){
        $loupanList = $this->providerNeedsInfo('loupanList');
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');
        $loupanList[524570]['huxing_price'] = json_decode($loupanList[524570]['huxing_price'], true);
        $data = array_merge($xiaoquList[0], $loupanList[524570]);
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'     => $data
        );
        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'readCacheData',
                'writeCacheData',
                'getXiaoquInfoById',
                'getXinloupanDataByXiaoquId'
                )
        );
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoById')
                ->will($this->returnValue($xiaoquList[0]));
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByXiaoquId')
                ->will($this->returnValue($loupanList[524570]));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);
        $ret = $mockObj->getXinloupanInfoByXiaoquId('524570');
        $this->assertEquals($returnData, $ret);
    }//}}}
    /**testGetXiaoquXiaoquFieldsForList{{{*/
    public function testGetXiaoquXiaoquFieldsForList(){
        $fileds = array('id', 'pinyin', 'city', 'name', 'latlng', 'thumb_image', 'district_id', 'street_id', 'address', 'developer');
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquXiaoquFieldsForList();
        $this->assertEquals($fileds, $ret);
    }//}}}
    /**testValidatorArray{{{*/
    public function testValidatorArray(){
        $data = array(1, 2);
        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->validatorArray($data);
        $this->assertEquals(true, $ret);

        $ret = $obj->validatorArray('');
        $this->assertEquals(false, $ret);

        $ret = $obj->validatorArray(array());
        $this->assertEquals(false, $ret);
    }//}}}
    /**testGetAndformataXiaoquHuxingPhoto{{{*/
    public function testGetAndformataXiaoquHuxingPhoto(){
        $huxingList = $this->providerNeedsInfo('huxingList');
        $huxingPrice = array('C1C4户型' => 1038400);
        $huxingPriceList = $this->providerNeedsInfo('huxingPriceList');
        $mockObj = $this->genObjectMock('Service_Data_Xiaoqu_Xinloupan', array('getXiaoquHuxingPhoto'));
        $mockObj->expects($this->any())
                ->method('getXiaoquHuxingPhoto')
                ->will($this->returnValue($huxingList));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->getAndformataXiaoquHuxingPhoto(524570, $huxingPrice);
        $this->assertEquals($huxingPriceList, $ret);

        //参数xiaoquId不合法
        $ret = $mockObj->getAndformataXiaoquHuxingPhoto('aa', $huxingPrice);
        $this->assertEquals(array(), $ret);

        //参数huxingInfo不合法
        $ret = $mockObj->getAndformataXiaoquHuxingPhoto(524570, array());
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testGetXinloupanHuxingPriceByXiaoquId{{{*/
    public function testGetXinloupanHuxingPriceByXiaoquId(){
        $loupanList = $this->providerNeedsInfo('loupanList');
        $huxingPriceList = $this->providerNeedsInfo('huxingPriceList');
        $returnData = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $huxingPriceList,
        );

        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'readCacheData',
                'getXinloupanDataByXiaoquId',
                'getAndformataXiaoquHuxingPhoto',
                'writeCacheData'
            )
        );
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByXiaoquId')
                ->will($this->returnValue($loupanList[524570]));
        $mockObj->expects($this->any())
                ->method('getAndformataXiaoquHuxingPhoto')
                ->will($this->returnValue($huxingPriceList));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));

       $ret = $mockObj->getXinloupanHuxingPriceByXiaoquId(524570);
       $this->assertEquals($returnData, $ret);
    }//}}}
    /**testGetXiaoquHuxingPhoto{{{*/
    public function testGetXiaoquHuxingPhoto(){
        $huxingList = $this->providerNeedsInfo('huxingList');
        $serviceObj = $this->genObjectMock('Service_Data_Xiaoqu_Photo', array('getXiaoquHuxingPhoto'));
        $serviceObj->expects($this->any())
                ->method('getXiaoquHuxingPhoto')
                ->will($this->returnValue(array('errorno' => '0', 'data' => array('items' => $huxingList))));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Photo', $serviceObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquHuxingPhoto(524570);
        $this->assertEquals($huxingList, $ret);

        //参数不合法
        $ret = $obj->getXiaoquHuxingPhoto('aa');
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testGetXinloupanRecommendByCity{{{*/
    public function testGetXinloupanRecommendByCity(){
        $loupanList = $this->providerNeedsInfo('loupanList');
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');
        $xiaoquIds = $this->providerNeedsInfo('xiaoquIds');
        $loupanList[524570]['huxing_price'] = json_decode($loupanList[524570]['huxing_price'], true);
        $data[524570] = array_merge($xiaoquList[0], $loupanList[524570]);

        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $data
        );

        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'getXinloupanDataByCity',
                'formataXinloupanData',
                'getXiaoquInfoByIds',
                'mergeXiaoquXinloupan',
                'readCacheData',
                'writeCacheData'
                )
        );
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByCity')
                ->will($this->returnValue(array('list' => $loupanList, 'total' => 5)));
        $mockObj->expects($this->any())
                ->method('formataXinloupanData')
                ->will($this->returnValue(array($loupanList, $xiaoquIds)));
        $mockObj->expects($this->any())
                ->method('getXiaoquInfoByIds')
                ->will($this->returnValue($xiaoquList));
        $mockObj->expects($this->any())
                ->method('mergeXiaoquXinloupan')
                ->will($this->returnValue($data));
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->getXinloupanRecommendByCity('sh');
        $this->assertEquals($returnData, $ret);
    }//}}}
    /**testGetXiaoquBaseInfoByCityPinyin{{{*/
    public function testGetXiaoquBaseInfoByCityPinyin(){
        $xiaoquList = $this->providerNeedsInfo('xiaoquList');

        $serviceObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityPinyin'));
        $serviceObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityPinyin')
                ->will($this->returnValue(array('errorno' => '0', 'data' => $xiaoquList[0])));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquBaseInfoByCityPinyin('sh', 'lv4di4zzzzzzcheng2bin1jiang1hui4');
        $this->assertEquals($xiaoquList[0], $ret);

        //返回异常
        $serviceObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityPinyin'));
        $serviceObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityPinyin')
                ->will($this->returnValue(array('errorno' => '8', 'data' => $xiaoquList[0])));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquBaseInfoByCityPinyin('sh', 'lv4di4zzzzzzcheng2bin1jiang1hui4');
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testGetXinloupanInfoByCityPinyin{{{*/
    /**
     * @dataProvider providerTestGetXinloupanInfoByCityPinyin
     */
    public function testGetXinloupanInfoByCityPinyin($params, $data){
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'     => $data
        );

        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'readCacheData',
                'getXiaoquBaseInfoByCityPinyin',
                'getXinloupanDataByXiaoquId',
                'writeCacheData'
            )
        );
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityPinyin')
                ->will($this->returnValue($params[1]));
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByXiaoquId')
                ->will($this->returnValue($params[0]));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->getXinloupanInfoByCityPinyin('sh', 'lv4di4zzzzzzcheng2bin1jiang1hui4');
        $this->assertEquals($returnData, $ret);
    }//}}}
    /**providerTestGetXinloupanInfoByCityPinyin{{{*/
    public function providerTestGetXinloupanInfoByCityPinyin(){
        $loupanInfo = $this->providerNeedsInfo('loupanList');
        $xiaoquInfo = $this->providerNeedsInfo('xiaoquList');
        return array(
            array(
                array(
                    $loupanInfo[524570],
                    $xiaoquInfo[0]
                ),
                array_merge($xiaoquInfo[0], $loupanInfo[524570])
            ),
            array(
                array(
                    array(),
                    $xiaoquInfo[0]
                ),
                array()
            ),
        );
    }//}}}
    /**testGetXiaoquBaseInfoByCityName{{{*/
    public function testGetXiaoquBaseInfoByCityName(){
        $xiaoquInfo = $this->providerNeedsInfo('xiaoquList');

        $serviceObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityName'));
        $serviceObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityName')
                ->will($this->returnValue(array('errorno' => '0', 'data' => $xiaoquInfo[0])));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquBaseInfoByCityName('sh', '名字');
        $this->assertEquals($xiaoquInfo[0], $ret);
        
        //返回异常
        $serviceObj = $this->genObjectMock('Service_Data_Xiaoqu_Info', array('getXiaoquBaseInfoByCityName'));
        $serviceObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityName')
                ->will($this->returnValue(array('errorno' => '8', 'data' => $xiaoquInfo[0])));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Info', $serviceObj);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->getXiaoquBaseInfoByCityName('sh', '名字');
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testPatchLoupanInfo{{{*/
    public function testPatchLoupanInfo(){
        $xiaoquInfo = $this->providerNeedsInfo('xiaoquList');
        $loupanInfo = $this->providerNeedsInfo('loupanList');
        $data[] = array_merge($xiaoquInfo[0], $loupanInfo[524570]);

        $mockObj = $this->genObjectMock('Service_Data_Xiaoqu_Xinloupan', array('getXinloupanDataByXiaoquId'));
        $mockObj->expects($this->any())
                ->method('getXinloupanDataByXiaoquId')
                ->will($this->returnValue($loupanInfo[524570]));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->patchLoupanInfo($xiaoquInfo);
        $this->assertEquals($data, $ret);

        $ret = $mockObj->patchLoupanInfo(array());
        $this->assertEquals(array(), $ret);
    }//}}}
    /**testGetXinloupanInfoByCityName{{{*/
    public function testGetXinloupanInfoByCityName(){
        $xiaoquInfo = $this->providerNeedsInfo('xiaoquList');
        $loupanInfo = $this->providerNeedsInfo('loupanList');
        $data[] = array_merge($xiaoquInfo[0], $loupanInfo[524570]);
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'     => $data
        );

        $mockObj = $this->genObjectMock(
            'Service_Data_Xiaoqu_Xinloupan',
            array(
                'readCacheData',
                'getXiaoquBaseInfoByCityName',
                'patchLoupanInfo',
                'writeCacheData'
            )
        );
        $mockObj->expects($this->any())
                ->method('readCacheData')
                ->will($this->returnValue(array()));
        $mockObj->expects($this->any())
                ->method('getXiaoquBaseInfoByCityName')
                ->will($this->returnValue($xiaoquInfo));
        $mockObj->expects($this->any())
                ->method('patchLoupanInfo')
                ->will($this->returnValue($data));
        $mockObj->expects($this->any())
                ->method('writeCacheData')
                ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Service_Data_Xiaoqu_Xinloupan', $mockObj);

        $ret = $mockObj->getXinloupanInfoByCityName('sh', '名字');
        $this->assertEquals($returnData, $ret);
    }//}}}
    /**testReadCacheData{{{*/
    public function testReadCacheData(){
        $Memcache = $this->genObjectMock('MemCacheAdapter', array('read'));
        $Memcache->expects($this->any())
                ->method('read')
                ->will($this->returnValue(false));
        Gj_Cache_CacheClient::setInstance($Memcache);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->readCacheData('aa');
        $this->assertEquals(array(), $ret);

        //normal
        $Memcache = $this->genObjectMock('MemCacheAdapter', array('read'));
        $Memcache->expects($this->any())
                ->method('read')
                ->will($this->returnValue(array(1, 3, 5)));
        Gj_Cache_CacheClient::setInstance($Memcache);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->readCacheData('aa');
        $this->assertEquals(array(1, 3, 5), $ret);
    }//}}}
    /**testWriteCacheData{{{*/
    public function testWriteCacheData(){
        $Memcache = $this->genObjectMock('MemCacheAdapter', array('write'));
        $Memcache->expects($this->any())
                ->method('write')
                ->will($this->returnValue(true));
        Gj_Cache_CacheClient::setInstance($Memcache);

        $obj = new Service_Data_Xiaoqu_Xinloupan();
        $ret = $obj->writeCacheData('aa', array(1, 3, 5));
    }//}}}
}
