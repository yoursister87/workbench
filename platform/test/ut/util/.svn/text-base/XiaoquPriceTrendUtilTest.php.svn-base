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
class XiaoquPriceTrendUtilTest extends TestCase_PTest
{
    protected $monthData = array(
        '2014-10' => array(),
        '2014-09' => array(),
        '2014-08' => array(),
        '2014-07' => array(),
        '2014-06' => array(),
        '2014-05' => array(),
        '2014-04' => array(),
        '2014-03' => array(),
        '2014-02' => array(),
        '2014-01' => array(),
        '2013-12' => array(),
        '2013-11' => array(),
    );
    protected $monthData2 = array(
        '2014-09' => array(),
        '2014-08' => array(),
        '2014-07' => array(),
        '2014-06' => array(),
        '2014-05' => array(),
        '2014-04' => array(),
        '2014-03' => array(),
        '2014-02' => array(),
        '2014-01' => array(),
        '2013-12' => array(),
        '2013-11' => array(),
        '2013-10' => array(),
    );

    protected $priceList;
    protected $timeStampPriceList;
    protected $infoList;
    public function formataData(){
        $info = array(
            'id' => 0,
            'district_id' => "1",    
            'street_id' => "58",
            'xiaoqu_id' => "114", 
            'huxing' => "3", 
            'avg_price_change' => "0.02",
            'avg_price' => "63966",
            'record_time' => "1383235200"
        );
        $i = 1;
        foreach ($this->monthData as $key => $item) {
            $timeStr = strtotime($key);
            $info['id'] = $i;
            $info['record_time'] = $timeStr;
            $this->timeStampPriceList[$timeStr] = $info;
            $this->priceList[$key] = $info;
            $this->infoList[$i] = $info;
            ++$i;
        }
    }
    public function formataData2(){
        $info = array(
            'id' => 0,
            'district_id' => "1",    
            'street_id' => "58",
            'xiaoqu_id' => "114", 
            'huxing' => "3", 
            'avg_price_change' => "0.02",
            'avg_price' => "63966",
            'record_time' => "1383235200"
        );
        $i = 1;
        foreach ($this->monthData as $key => $item) {
            $timeStr = strtotime($key);
            $info['id'] = $i;
            $info['record_time'] = $timeStr;
            $this->timeStampPriceList[$timeStr] = $info;
            $this->priceList[date('Y-m', strtotime($key . ' -1 month'))] = $info;
            $this->infoList[$i] = $info;
            ++$i;
        }
    }

    public function testGenerateMonthTimePeriod(){
        $obj = new Util_XiaoquPriceTrendUtil();
        $period = $obj->generateMonthTimePeriod('2014-10-01');
        $this->assertEquals($this->monthData, $period);
    }
    /**testPatchEmptyData{{{*/
    /**
     * @dataProvider providerPatchEmptyData
     */
    public function testPatchEmptyData($param, $data){
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->patchEmptyData($param[0], $param[1]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerPatchEmptyData{{{*/
    public function providerPatchEmptyData(){
        $this->formataData();
        $newReturn = $newList = $this->priceList;
        unset($newList['2014-10']);
        unset($newList['2014-08']);
        unset($newList['2013-12']);
        unset($newList['2013-11']);
        $newReturn['2014-10']= $this->priceList['2014-09']; 
        $newReturn['2014-08'] = $this->priceList['2014-07'];
        $newReturn['2013-12'] = $this->priceList['2014-01'];
        $newReturn['2013-11'] = $this->priceList['2014-01'];
        return array(
            array(
                array($this->priceList, $this->monthData),
                $this->priceList
            ),
            array(
                array($newList, $this->monthData),
                $newReturn
            ),
            array(
                array(array(), $this->monthData),
                array()
            ),
            array(
                array('abc', $this->monthData),
                array()
            ),
        );
    }//}}}
    /**testFilerPriceListByMonth{{{*/
    /**
     * @dataProvider providerFilerPriceListByMonth
     */
    public function testFilerPriceListByMonth($param, $data){
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->filerPriceListByMonth($param);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerFilerPriceListByMonth{{{*/
    public function providerFilerPriceListByMonth(){
        $this->formataData2();
        $key = strtotime('2014-10-15');
        $this->infoList[13] = $this->infoList[12];
        $this->infoList[13]['record_time'] = $key;
        return array(
            array($this->infoList, $this->priceList),
            array(array(), array()),
            array('123', array()),
        );
    }//}}}
    /**testDataPacketByXiaoquId{{{*/
    /**
     * @dataProvider providerDataPacketByXiaoquId
     */
    public function testDataPacketByXiaoquId($param, $data){
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->dataPacketByXiaoquId($param[0], $param[1]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerDataPacketByXiaoquId{{{*/
    public function providerDataPacketByXiaoquId(){
        $this->formataData();
        $listHuxing["114"]["3"] = $this->infoList;
        $listXiaoqu["114"] = $this->infoList;
        return array(
            array(
                array(array(), 1),
                array()
            ),
            array(
                array(123, 1),
                array()
            ),
            array(
                array($this->infoList, 1),
                $listHuxing
            ),
            array(
                array($this->infoList, 5),
                $listXiaoqu
            ),
        );
    }//}}}
    /**testSubPriceList{{{*/
    public function testSubPriceList(){
        $this->formataData();
        $data = array(
            '2014-10' => array( 
                'id' => 1,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1412092800
            ),
            '2014-09' => array( 
                'id' => 2,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1409500800
            ),
        );
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->subPriceList($this->priceList, '2014-09', '2014-10');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testFormataPriceListDetail{{{*/
    public function testFormataPriceListDetail(){
        $this->formataData2();
        $data = array(
            '2014-09' => array( 
                'id' => 1,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1412092800
            ),
            '2014-08' => array( 
                'id' => 2,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1409500800
            ),
        );
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->formataPriceListDetail($this->infoList, $this->monthData, '2014-08', '2014-09');
        $this->assertEquals($data, $ret);
    }//}}}
    /**testFormataPriceList{{{*/
    /**
     * @dataProvider providerFormataPriceList
     */
    public function testFormataPriceList($param, $data){
        $obj = new Util_XiaoquPriceTrendUtil();
        $ret = $obj->formataPriceList($param[0], $param[1], $param[2], $param[3]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerFormataPriceList{{{*/
    public function providerFormataPriceList(){
        $this->formataData2();
        $data = array(
            '2014-09' => array( 
                'id' => 1,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1412092800
            ),
            '2014-08' => array( 
                'id' => 2,
                'district_id' => "1",
                'street_id' => "58",
                'xiaoqu_id' => "114", 
                'huxing' => "3",  
                'avg_price_change' => "0.02",
                'avg_price' => "63966", 
                'record_time' => 1409500800
            ),
        );
        $huxingList["114"]["3"] = $data;
        $xiaoquList["114"] = $data;
        return array(
            array(
                array($this->infoList, '2014-08', '2014-09', 1),
                $huxingList
            ),
            array(
                array($this->infoList, '2014-08', '2014-09', 5),
                $xiaoquList
            ),
        );
    }//}}}
}

