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

class XiaoquPriceTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    protected $paramsArr = array(
        'cityCode' => 0,
        'districtId' => 0,
        'streetId' => 52,
        'xiaoquIds' => array(114),
        'huxing' => 3,
        'category'  => 1
    );
    protected $dateBEArr= array('begin' => '2014-09', 'end' => '2014-10');

    /**testGetPriceListFail{{{*/
    /**
     * @expectedException Exception
     */
    public function testGetPriceListFail(){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquPrice', array("select"));
        $mockObj->expects($this->at(0))
            ->method('select')
            ->will($this->returnValue(FALSE));

        $ret = $mockObj->getPriceList($this->paramsArr, $this->dateBEArr, 2);
    }//}}}
    /**testGetPriceList{{{*/
    /**
     * @dataProvider providerGetPriceList
     */
    public function testGetPriceList($param, $data){
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquPrice', array("select"));
        $mockObj->expects($this->at(0))
            ->method('select')
            ->will($this->returnValue($data));

        $ret = $mockObj->getPriceList($param[0], $param[1], $param[2]);
        $this->assertEquals($data, $ret);
    }//}}}
    /**providerGetPriceList{{{*/
    public function providerGetPriceList(){
        $data = array(0 => 1, 2 => 3, 4 => 2);
        return array(
            array(
                array($this->paramsArr, $this->dateBEArr, 0),
                $data
            ),
            array(
                array($this->paramsArr, $this->dateBEArr, 1),
                $data
            ),
            array(
                array($this->paramsArr, $this->dateBEArr, 2),
                $data
            ),
            array(
                array($this->paramsArr, $this->dateBEArr, 3),
                $data
            ),
        );
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

}
