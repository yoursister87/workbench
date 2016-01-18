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
class HouseSellAvgPriceMock extends Dao_Xiaoqu_HouseSellAvgPrice
{
    public function __construct(){
    }
    public function setDbhandler($obj){
        $this->dbHandler = $obj;
    }
}
class HouseSellAvgPriceTest extends TestCase_PTest
{
    protected $xiaoquInfo = array(
        'cityCode' => 0,
        'districtId' => 0,
        'streetId' => 3
    );
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**testGetPriceListException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetPriceListException($params){
        $geoInfo = array(
            'cityCode' => 0,
            'districtId' => 0,
            'streetId' => 52,
            'xiaoquIds' => array(1418),
        );
        $dateInfo = array('begin' => '2014-09-01', 'end' => '2014-10-15');
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('select'));
        $mockObj->expects($this->at(0))
            ->method('select')
            ->will($this->returnValue(FALSE));

        $ret = $mockObj->getPriceList($geoInfo, $dateInfo, 2);
    }//}}}
    /**testGetPriceList{{{*/
    /**
     * @dataProvider providerGetPriceList
     */
    public function testGetPriceList($params){
        $list = array(1, 2, 4, 3);
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('select'));
        $mockObj->expects($this->at(0))
            ->method('select')
            ->will($this->returnValue($list));

        $ret = $mockObj->getPriceList($params[0], $params[1], $params[2]);
        $this->assertEquals($list, $ret);
    }//}}}
    /*{{{testGetPriceListByMultiAreaException*/
    /**
     * @expectedException Exception
     */
    public function testGetPriceListByMultiAreaException(){
        $paramArr = array(
            'cityCode' => 0,
            'districtIds' => array(1, 2, 3)
        );
        $type = 0;
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('select', 'formatPriceTrendByMultiArea'));
        $mockObj->expects($this->any(0))
            ->method('select')
            ->will($this->returnValue(FALSE));
        $mockObj->expects($this->any(0))
            ->method('formatPriceTrendByMultiArea')
            ->will($this->returnValue(FALSE));
        $ret = $mockObj->getPriceListByMultiArea($paramArr, $type);
    }
    /*}}}*/
    /*{{{testGetPriceListByMultiArea*/
    /**
     * @dataProvider providerGetPriceListByMultiArea
     */
    public function testGetPriceListByMultiArea($paramArr, $type){
        $res = array(1, 2, 3);
        $paramArr = array(
            'cityCode' => 0,
            'districtIds' => array(1, 2, 3),
        );
        $type = 0;
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_HouseSellAvgPrice', array('select', 'formatPriceTrendByMultiArea'));
        $mockObj->expects($this->any(0))
            ->method('select')
            ->will($this->returnValue($res));
        $mockObj->expects($this->any(0))
            ->method('formatPriceTrendByMultiArea')
            ->will($this->returnValue($res));
        $ret = $mockObj->getPriceListByMultiArea($paramArr, $type);
        $this->assertEquals($res, $ret);
    }

    /*}}}*/
    /*{{{providerGetPriceListByMultiArea*/
    public function providerGetPriceListByMultiArea(){
        return array(
            array(
                array(
                    'cityCode' => 0,
                    'districtId' => array(1, 2, 3)
                ), 1
            ),
            array(
                array(
                    'cityCode' => 0,
                    'districtId' => 1,
                    'streetId' => array(1, 2, 3)
                ), 2
            ),
            array(
                array(
                    'cityCode' => 0,
                    'districtId' => 1,
                    'streetId' => 2,
                    'xiaoquIds' => array(1, 2, 3),
                ), 3
            ), 
        );
    }
    /*}}}*/
    /**providerGetPriceList{{{*/
    public function providerGetPriceList(){
        $geoInfo = array(
            'cityCode' => 0,
            'districtId' => 0,
            'streetId' => 52,
            'xiaoquIds' => array(1418),
        );
        $dateInfo = array('begin' => '2014-09-01', 'end' => '2014-10-15');
        $list = array(1, 2, 3, 4);
        return array(
            array(
                array($geoInfo, $dateInfo, 1), 
            ),
            array(array($geoInfo, $dateInfo, 1)),
            array(array($geoInfo, $dateInfo, 2)),
            array(array($geoInfo, $dateInfo, 3)),
        );
    }//}}}
    /**mockDbHander{{{*/
    public function mockDbHander($list){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select', 'getLastSql'));
        $mockObj->expects($this->any())
            ->method('select')
            ->will($this->returnValue($list));
        $mockObj->expects($this->any())
            ->method('getLastSql')
            ->will($this->returnValue('select * from aa where id = 1'));
        return $mockObj;
    }//}}}
    /**testGetXiaoquPriceRankList{{{*/
    public function testGetXiaoquPriceRankList(){
        $list = array(0 => array('xiaoqu_id' => 12), 1 => array('xiaoqu_id' => 13));
        $mockObj = $this->mockDbHander($list);

        $obj = new HouseSellAvgPriceMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXiaoquPriceRankList($this->xiaoquInfo);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetXiaoquPriceOrderList{{{*/
    public function testGetXiaoquPriceOrderList(){
        $list = array(0 => array('xiaoqu_id' => 12), 1 => array('xiaoqu_id' => 13));
        $mockObj = $this->mockDbHander($list);

        $obj = new HouseSellAvgPriceMock();
        $obj->setDbhandler($mockObj);
        $ret = $obj->getXiaoquPriceOrderList($this->xiaoquInfo);
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetListInfo{{{*/
    public function testGetListInfo(){
        $list = array(0 => array('xiaoqu_id' => 12), 1 => array('xiaoqu_id' => 13));
        $mockObj = $this->mockDbHander($list);

        $obj = $this->genObjectMock('HouseSellAvgPriceMock', array('getSearchTime'));
        $obj->expects($this->any(0))
            ->method('getSearchTime')
            ->will($this->returnValue(12345));

        $fields = array('xiaoqu_id', 'avg_price', 'avg_price_change');
        $conArrays = $obj->getConArrays($this->xiaoquInfo);
        $obj->setDbhandler($mockObj);
        $ret = $obj->getListInfo($fields, $conArrays, array('order by id desc'));
        $this->assertEquals($list, $ret);
    }//}}}
    /**testGetListInfoException{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetListInfoException(){
        $mockObj = $this->mockDbHander(false);
        $obj = $this->genObjectMock('HouseSellAvgPriceMock', array('getSearchTime'));
        $obj->expects($this->any(0))
            ->method('getSearchTime')
            ->will($this->returnValue(12345));

        $fields = array('xiaoqu_id', 'avg_price', 'avg_price_change');
        $conArrays = $obj->getConArrays($this->xiaoquInfo);
        $obj->setDbhandler($mockObj);
        $ret = $obj->getListInfo($fields, $conArrays, array('order by id desc'));
    }//}}}
    /**testGetConArraysException{{{*/
    /**
     * @expectedException Exception
     * @dataProvider providerConArrays
     */
    public function testGetConArraysException($params){
        $obj = new Dao_Xiaoqu_HouseSellAvgPrice();
        $obj->getConArrays($params);
    }//}}}
    /**providerConArrays{{{*/
    public function providerConArrays(){
        return array(
            array(''),
            array(123)

        );
    }//}}}
    /*{{{testFormatPriceTrendByMultiArea*/
    /**
     * @dataProvider providerMultiArea
     */
    public function testFormatPriceTrendByMultiArea($params, $res){
        $obj = new Dao_Xiaoqu_HouseSellAvgPrice(); 
        $ret = $obj->formatPriceTrendByMultiArea($params);
        $this->assertEquals($res, $ret);
    }
    /*}}}*/
    /*{{{providerMultiArea*/
    public function providerMultiArea(){
        return array(
            array(array(), array()),
            array(
                array(
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 4,
                        'avg_price' => 1,
                        'avg_price_change' => 1
                    ),
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 2,
                        'avg_price' => 1,
                        'avg_price_change' => 1
                    ),
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 2,
                        'avg_price' => 0,
                        'avg_price_change' => 1
                    ),
                     array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 1,
                        'avg_price' => 1,
                        'avg_price_change' => 1
                    ),
                    array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 1,
                        'avg_price_change' => 1
                    ),
                    array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 0,
                        'avg_price_change' => 1
                    ), 
                    array(
                        'district_id' => 2,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 1,
                        'avg_price_change' => 1
                    ), 
 
                ),
                array(
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 4,
                        'avg_price' => 1,
                        'avg_price_change' => 0
                    ),
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 4,
                        'avg_price' => 1,
                        'avg_price_change' => 0
                    ),
                    array(
                        'district_id' => 1,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 2,
                        'avg_price' => 0,
                        'avg_price_change' => -1
                    ),
                     array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 1,
                        'avg_price_change' => 0
                    ),
                    array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 0,
                        'avg_price_change' => -1
                    ),
                    array(
                        'district_id' => 3,
                        'street_id' => 2,
                        'xiaoqu_id' => 3,
                        'record_time' => 5,
                        'avg_price' => 0,
                        'avg_price_change' => -1
                    ), 
                )
            ),
        ); 
    }
    /*}}}*/
    /**testGetConArrays{{{*/
    public function testGetConArrays(){
        $obj = $this->genObjectMock('HouseSellAvgPriceMock', array('getSearchTime'));
        $obj->expects($this->any(0))
            ->method('getSearchTime')
            ->will($this->returnValue(12345));

        $data = array(
            'step_type =' => 3,
            'city=' => 0,
            'district_id =' => 0,
            'street_id =' => 3,
            'record_time =' => 12345
        );

        $ret = $obj->getConArrays($this->xiaoquInfo);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testGetSearchTime{{{*/
    /**
     * @dataProvider providerSearchTime
     */
    public function testGetSearchTime($params, $searchTime){
        $obj = $this->genObjectMock('HouseSellAvgPriceMock', array('getTime'));
        $obj->expects($this->any(0))
            ->method('getTime')
            ->will($this->returnValue($searchTime));

        $ret = $obj->getSearchTime($params);
        $this->assertEquals($searchTime, $ret);
    }//}}}
    /**providerSearchTime{{{*/
    public function providerSearchTime(){
        $date15 = strtotime('2014-10-15');
        $date01 = strtotime('2014-10-01');
        $preDate15 = strtotime('2014-09-15');
        return array(
            array(0, $date15),
            array(0, $date01),
            array($date15, $date01),
            array($date01, $preDate15),
        );
    }//}}}
}

