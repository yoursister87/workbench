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

class ServiceXiaoquPriceTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    protected $paramsArr = array(
        'cityCode' => 0,
        'districtId' => 0,
        'streetId' => 52,
        'category' => 1,
        'xiaoquIds' => array(1418),
    );
    protected $dateBEArr = array('begin' => '2014-01-01', 'end' => '2014-03-01', 'monthNum' => 4);
    /**
     * testGetSellXiaoquCountException{{{
     */
    public function testGetSellXiaoquCountException(){
        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->getSellXiaoquCount('');
        $this->assertEquals(ErrorConst::E_PARAM_INVALID_CODE, $ret['errorno']);
    }
    /*}}}*/
    /*{{{*/
    public function testGetSellXiaoquCount(){
         $mockConfig = array(
             'Service_Data_Xiaoqu_Price' => array(
                 'getXiaoquInfo' => array(
                    'return' => array(array('id' => 1)),
                 ),
             ),
            'Dao_Xiaoqu_XiaoquXiaoqu' => array(
                'getXiaoquInfoByCity' => array(
                    'return' => array(array('id' => 1)),
                )
            ),
            'Dao_Xiaoqu_XiaoquStat' => array(
                'getXiaoquStatInfoByXiaoquId' => array(
                    'return' => array(array('avg_price' => 1)),
                )
            ),
        );
        $data = array(
            'errorno' => '0',
            'errormsg' => '[数据返回成功]',
            'data' => array(
                'result' => array(
                    1 => 1,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0
                ),
            ),
        );
        $mockObjArr = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquXiaoqu', $mockObjArr['Dao_Xiaoqu_XiaoquXiaoqu']);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquStat', $mockObjArr['Dao_Xiaoqu_XiaoquStat']);
        $Memcache = $this->genObjectMock('MemCacheAdapter', array('read', 'write'));
        $Memcache->expects($this->any())
            ->method('read')
            ->will($this->returnValue($data['data']['result']));
        $Memcache->expects($this->any())
            ->method('write')
            ->will($this->returnValue(True));
        Gj_Cache_CacheClient::setInstance($Memcache);

        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->getSellXiaoquCount('bj');
        $this->assertEquals($data, $ret);
    }
    /*}}}*/
    /**testGetRentPriceTrendException{{{*/
    public function testGetRentPriceTrendException(){
        $data = array(
            'errorno' =>  ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG.":category为必选项"
        );
        $obj = $this->genObjectMock('Service_Data_Xiaoqu_Price', array('validatorDate'));
        $obj->expects($this->at(0))
            ->method('validatorDate')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG.":category为必选项", ErrorConst::E_PARAM_INVALID_CODE)));

        $ret = $obj->getRentPriceTrend($this->paramsArr, $this->dateBEArr, 1);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetSellPriceTrendException{{{*/
    public function testGetSellPriceTrendException(){
        $data = array(
            'errorno' =>  ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG.":category为必选项"
        );
        $obj = $this->genObjectMock('Service_Data_Xiaoqu_Price', array('validatorDate'));
        $obj->expects($this->at(0))
            ->method('validatorDate')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG.":category为必选项", ErrorConst::E_PARAM_INVALID_CODE)));

        $ret = $obj->getSellPriceTrend($this->paramsArr, $this->dateBEArr, 1);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetRentPriceTrend{{{*/
    public function testGetRentPriceTrend(){
        $list = array(
            array(
                'id' => 6406,
                'district_id' => 0,
                'street_id' => -1,
                'xiaoqu_id' => -1,
                'huxing' => 1,
                'record_time' => 0,
            ),
            array(
                'id' => 6407,
                'district_id' => 0,
                'street_id' => -1,
                'xiaoqu_id' => -1,
                'huxing' => 1,
                'record_time' => 0,
            ),
        );
        $dateReturn = array('begin' => '2014-01-01', 'end' => '2015-01-10');
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array('items'=>array(
                -999 => array(
                    1 => array(
                        '2014-03' => array(),
                        '2014-02' => array(),
                    ), 
                ),
            )),
        );
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquPrice' => array(
                'getPriceList' => array(
                    'return' => $list,
                    //'params' => array($this->paramsArr, array('begin' => 1396281600, 'end' => 1409500800), 1)
                )
            ),
            'Service_Data_Xiaoqu_Price' => array(
                'validatorDate' => array(
                    'return' => array('begin' => 1396281600, 'end' => 1409500800),
                    //'params' => $this->dateBEArr
                ),
                'validateParams' => array(
                    'return' => true,
                    //'params' => array($this->paramsArr, 1)
                ),
                'validateMajorCategory' => array(
                    'return' => true,
                    //'params' => $this->paramsArr['category']
                )
            ),
        );
        $mockObjArr = $this->genAllObjectMock($mockConfig);

        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquPrice', $mockObjArr['Dao_Xiaoqu_XiaoquPrice']);

        $obj = $mockObjArr['Service_Data_Xiaoqu_Price'];
        $ret = $obj->getRentPriceTrend($this->paramsArr, $this->dateBEArr, 1);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetSellPriceTrend{{{*/
    public function testGetSellPriceTrend(){
        $list = array(
            array(
                'id' => 2873816,
                'district_id' => -999,
                'street_id' => -999,
                'xiaoqu_id' => -999,
                'avg_price_change' => 0.00,
                'avg_price' => 26829,
                'record_time' => 1392438700
            ),
            array(
                'id' => 2873815,
                'district_id'=> -999,
                'street_id' => -999,
                'xiaoqu_id' => -999,
                'avg_price_change' => 0.00,
                'avg_price' => 26257,
                'record_time' => 1392440700,
            ),
        );
        $dateReturn = array('begin' => '2014-01-01', 'end' => '2015-01-10');
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array('items'=>array(
                -999 => array(
                    2873816 => array(
                        '2014-03' => array(),
                        '2014-02' => array(), 
                    ),
                    2873815 => array(
                        '2014-03' => array(),
                        '2014-02' => array(), 

                    ),
                ),
            )),
        );
        $mockConfig = array(
            'Dao_Xiaoqu_HouseSellAvgPrice' => array(
                'getPriceList' => array(
                    'return' => $list,
                    //'params' => array($this->paramsArr, array('begin' => 1396281600, 'end' => 1409500800), 1)
                )
            ),
            'Service_Data_Xiaoqu_Price' => array(
                'validatorDate' => array(
                    'return' => array('begin' => 1396281600, 'end' => 1409500800),
                    //'params' => $this->dateBEArr
                ),
                'validateParams' => array(
                    'return' => true,
                    //'params' => array($this->paramsArr, 1)
                ),
                'validateMajorCategory' => array(
                    'return' => true,
                    //'params' => $this->paramsArr['category']
                )
            ),
        );
        $mockObjArr = $this->genAllObjectMock($mockConfig);

        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_HouseSellAvgPrice', $mockObjArr['Dao_Xiaoqu_HouseSellAvgPrice']);

        $obj = $mockObjArr['Service_Data_Xiaoqu_Price'];
        $ret = $obj->getSellPriceTrend($this->paramsArr, $this->dateBEArr, 1);
        $this->assertEquals($data, $ret);
    }//}}}
    /*{{{testGetRentPriceTrendByMultiAreaException*/
    public function testGetRentPriceTrendByMultiAreaException(){
        $res = array(
            'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG . '查询均价类型错误或为空'
        ); 
        $mockObj = $this->genObjectMock('Service_Data_Xiaoqu_Price', array('validateParams'));
        $mockObj->expects($this->at(0))
            ->method('validateParams')
            ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG . '查询均价类型错误或为空', ErrorConst::E_PARAM_INVALID_CODE)));
        $ret = $mockObj->getRentPriceTrendByMultiArea($this->paramsArr, 5);
        $this->assertEquals($res, $ret);
    }
    /*}}}*/
    /*{{{testGetRentPriceTrendByMultiArea*/
    public function testGetRentPriceTrendByMultiArea(){
        $res = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array('items' => array(1, 2, 3)),
        );
        $list = array(1, 2, 3);
        $mockConfig = array(
            'Dao_Xiaoqu_XiaoquPrice' => array(
                'getPriceListByMultiArea' => array(
                    'return' => $list,
                ),
            ),
            'Service_Data_Xiaoqu_Price' => array(
                'validateParams' => array(
                    'return' => true,
                ),
            ),
        );
        $mockObj = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquPrice', $mockObj['Dao_Xiaoqu_XiaoquPrice']);
        $obj = $mockObj['Service_Data_Xiaoqu_Price'];
        $ret = $obj->getRentPriceTrendByMultiArea($this->paramsArr, 0);
        $this->assertEquals($res, $ret);
   }
    /*}}}*/
    /**testGetXiaoquSellPriceRank{{{*/
    public function testGetXiaoquSellPriceRank(){
        $xiaoquInfo = array(
            'cityCode' => 0,
            'districtId' => 0,
            'streetId' => 3
        );
        $list = array(1, 2, 3, 4, 5, 6, 7);
        $top = array(1, 2, 3, 4, 5);
        $last = array(3, 4, 5, 6, 7);
        $sliceArray = array($top, $last);
        $order = array(
            'rateTop' => $top,
            'rateLast' => $last,
            'priceTop' => $top,
            'priceLast' => $last
        );
        $data = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => $order,
        );
        //mock model{{{
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('getXiaoquPriceRankList', 'getXiaoquPriceOrderList'));
        $modelObj->expects($this->any())
                ->method('getXiaoquPriceRankList')
                ->with($xiaoquInfo)
                ->will($this->returnValue($list));
        $modelObj->expects($this->any())
                ->method('getXiaoquPriceOrderList')
                ->with($xiaoquInfo)
                ->will($this->returnValue($list));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_HouseSellAvgPrice',$modelObj);
        //}}}

        $mockObj = $this->genObjectMock('Service_Data_Xiaoqu_Price', array('sliceArray', 'formataAvgChange'));
        $mockObj->expects($this->any())
                //->with($list, 5)
                ->method('sliceArray')
                ->will($this->returnValue($sliceArray));
        $mockObj->expects($this->any())
                //->with($order)
                ->method('formataAvgChange')
                ->will($this->returnValue($order));

        $ret = $mockObj->getXiaoquSellPriceRank($xiaoquInfo, 5);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetXiaoquSellPriceRankException{{{*/
    public function testGetXiaoquSellPriceRankException(){
        $xiaoquInfo = array(
            'cityCode' => 0,
            'districtId' => 0,
            'streetId' => 3
        );
        $data = array(
            'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        //mock model{{{
        $modelObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('getXiaoquPriceRankList', 'getXiaoquPriceOrderList'));
        $modelObj->expects($this->any())
                ->method('getXiaoquPriceRankList')
                //->with($xiaoquInfo)
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_HouseSellAvgPrice',$modelObj);
        //}}}

        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->getXiaoquSellPriceRank($xiaoquInfo, 5);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testSliceArray{{{*/
    /**
     * @dataProvider providerSliceArray
     */
    public function testSliceArray($param, $data){
        $obj= new Service_Data_Xiaoqu_Price();
        $ret = $obj->sliceArray($param);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerSliceArray{{{*/
    public function providerSliceArray(){
        $itemList = array(1, 2, 3, 4, 5, 6, 7);
        $data = array(array(1, 2, 3, 4, 5), array(3, 4, 5, 6, 7));
        return array(
            array('', array(array(), array())),
            array('123', array(array(), array())),
            array($itemList, $data)
        );
    }//}}}
    /**testFormataAvgChange{{{*/
    /**
     * @dataProvider providerFormataAvgChange
     */
    public function testFormataAvgChange($param, $data){
        $obj= new Service_Data_Xiaoqu_Price();
        $ret = $obj->formataAvgChange($param);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerFormataAvgChange{{{*/
    public function providerFormataAvgChange(){
        $param = array('rateTop' => array( 0 =>array('avg_price_change' => 0.23, 'num' => 3)));
        $data = array('rateTop' => array( 0 =>array('avg_price_change' => 23)));
        return array(
            array('', array()),
            array(123, array()),
            array($param, $data)
        );
    }//}}}
    /**testValidateParamsException{{{*/
    /** 
     * @expectedException Exception
     * @dataProvider providerValidateParams
     */
    public function testValidateParamsException($data){
	$obj = new Service_Data_Xiaoqu_Price();
        $obj->validateParams($data[0], $data[1]);
    }//}}}
    /**providerValidateParams{{{*/
    public function providerValidateParams(){
        $paramsCity = array('aa' => 0);
        $paramsDistrictEx = array('cityCode' => 0, 'bb' => 0);
        $paramsStreet1 = array('cityCode' => 0, 'bb' => 0, 'streetId' => 52);
        $paramsStreet2 = array('cityCode' => 0, 'districtId' => 0, 'cc' => 52);
        $paramsXiaoqu = array('cityCode' => 0, 'xiaoquIds' => array());
        return array(
                array(array($paramsCity, 4)),
                array(array(array(), 0)),
                array(array($paramsCity, 0)),
                array(array($paramsDistrictEx, 1)),
                array(array($paramsStreet1, 2)),
                array(array($paramsStreet2, 2)),
                array(array($paramsXiaoqu, 3)),
                );
    }//}}}
    /**testValidateParams{{{*/
    public function testValidateParams(){
        $paramsXiaoqu = array('cityCode' => 0, 'xiaoquIds' => array('123'), 'huxing' => 1);
        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->validateParams($paramsXiaoqu, 3);
        $this->assertEquals(true, $ret);
    }//}}}
    /*testValidatorDateException{{{*/
    /** 
     * @expectedException Exception
     */
    public function testValidatorDateException(){
        $obj = new Service_Data_Xiaoqu_Price();
        $dateBEArr = array('begin' => '2014-08-01', 'end' => '2014-03-01', 'monthNum' => 6);
        $obj->validatorDate($dateBEArr);
    }//}}}
    /*testValidatorDate{{{*/
    public function testValidatorDate(){
        $return = array('begin' => 1396281600, 'end' => 1409500800);
        $dateBEArr = array('begin' => '2014-03-01', 'end' => '2014-08-01', 'monthNum' => 6);
        $obj = new Service_Data_Xiaoqu_Price();
        $res = $obj->validatorDate($dateBEArr);
        $this->assertEquals($return, $res);
    }//}}}
    /**testValidateMajorCategory{{{*/
    public function testValidateMajorCategory(){
        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->validateMajorCategory(1);
        $this->assertEquals(true, $ret);
    }//}}}
    /**testValidateMajorCategoryException{{{*/
    /**
     * @expectedException Exception
     * @dataProvider providerValidateMajorCategory
     */
    public function testValidateMajorCategoryException($param){
        $obj = new Service_Data_Xiaoqu_Price();
        $ret = $obj->validateMajorCategory($param);
    }//}}}
    /**providerValidateMajorCategory{{{*/
    public function providerValidateMajorCategory(){
        return array(
            array(null),
            array(7),
        );
    }//}}}
}
