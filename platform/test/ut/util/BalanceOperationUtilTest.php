<?php
/*
 * File Name:BalanceOperationUtilTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class BalanceOperationUtilTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    function testgetBizTime(){
        $obj = Gj_LayerProxy::getProxy('Util_HouseReport_BalanceOperationUtil');
        $option = array(
            'userId'=>50029116,
            'cityId'=>12,
            'productCode'=>'pd_post_num',
        );

        $balanceList = array();
        $allowList = array();
        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->getBizTime($balanceList,$allowList);
        $this->assertEquals($ret,array());
        $allowList = array(1,3);
        $beginAt = time() + 3600 * 24;
        $endAt = time() + 3600 * 24 * 2;
        $balanceList = array(
            array('Extension'=>1,'BeginAt'=>$beginAt,'EndAt'=>$endAt,'Status'=>1),
        );
        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->getBizTime($balanceList,$allowList);

        $res = array (
            1 => 
            array (
                'BeginAt' => 
                array (
                    0 => $beginAt,
                ),
                'EndAt' => 
                array (
                    0 => $endAt,
                ),
            ),
        );
        $this->assertEquals($ret,$res);
    }
    
    public function testseriesOrder(){
        $startTime = array(
            strtotime(date('2014-12-24')),#订单一
            strtotime(date('2015-1-24')),
            strtotime(date('2015-1-23')),
            strtotime(date('2015-7-20')),#订单二
            strtotime(date('2015-8-20')),
            strtotime(date('2015-12-1')),#订单三

        );
        $endTime = array(
            (strtotime(date('2015-1-24'))-1),#订单一
            strtotime(date('2015-2-24')),
            strtotime(date('2015-5-1')),
            (strtotime(date('2015-8-20'))-1),#订单二
            strtotime(date('2015-10-10')),
            strtotime(date('2015-12-10')),#订单三
        );

        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->seriesOrder($startTime,$endTime);

       $res = array (
           'beginAt' =>
               array (
                   1 => strtotime(date('2014-12-24')),
                   2 => strtotime(date('2015-7-20')),
                   3 => strtotime(date('2015-12-1')),
               ),
           'endAt' =>
               array (
                   1 =>  strtotime(date('2015-5-1')),
                   2 =>  strtotime(date('2015-10-10')),
                   3 => strtotime(date('2015-12-10')),#订单三
               ),
       );
        $this->assertEquals($ret,$res);

        $startTime = array(
            strtotime(date('2014-12-24')),
        );
        $endTime = array(
            (strtotime(date('2015-1-24'))-1),
        );

        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->seriesOrder($startTime,$endTime);

        $res = array (
            'beginAt' =>
                array (
                    0 => strtotime(date('2014-12-24')),
                ),
            'endAt' =>
                array (
                    0 => (strtotime(date('2015-1-24'))-1),
                ),
        );
        $this->assertEquals($ret,$res);

        $startTime = array(
            strtotime(date('2014-12-24')),
            strtotime(date('2015-8-20')),
        );
        $endTime = array(
            (strtotime(date('2015-1-24'))-1),
            strtotime(date('2015-9-20')),
        );

        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->seriesOrder($startTime,$endTime);

        $res = array (
            'beginAt' =>
                array (
                    1 => strtotime(date('2014-12-24')),
                    2 =>strtotime(date('2015-8-20')),
                ),
            'endAt' =>
                array (
                    1 => (strtotime(date('2015-1-24'))-1),
                    2 =>strtotime(date('2015-9-20')),
                ),
        );
        $this->assertEquals($ret,$res);
    }

    public function testgetListHaveshallList(){
        $option = array(
            'UserId'=>123,
            'CityId'=>123,
            'ProductCode'=>123
        );
        $businessScope = array();
        $obj = new Util_HouseReport_BalanceOperationUtil();
        $ret = $obj->getListHaveshallList($option,$businessScope);
        $this->assertEquals($ret,array());

        $businessScope = array(
            3=>array(
                2=>true,
            ),
            4=>array(
                1=>true,
            )
        );
        $balanceServiceData = array(
           array(
                'Extension'=>3,
                'beginAt'=>strtotime(date('2014-12-24')),
                'endAt'=>(strtotime(date('2015-1-24'))-1),
            ),
            array(
                'Extension'=>4,
                'beginAt'=>strtotime(date('2014-12-24')),
                'endAt'=>(strtotime(date('2015-1-24'))-1),
            ),
        );
        $timeList = array(
            3 =>array(
                'BeginAt' =>
                    array (
                        1 => strtotime(date('2014-12-24')),
                    ),
                'EndAt' =>
                    array (
                        1 => (strtotime(date('2015-1-24'))-1),
                    ),
            ),
            4 =>array(
                'BeginAt' =>
                    array (
                        1 => strtotime(date('2014-12-24')),
                    ),
                'EndAt' =>
                    array (
                        1 => (strtotime(date('2015-1-24'))-1),
                    ),
            )
        );
        $obj = $this->genObjectMock("Util_HouseReport_BalanceOperationUtil",array("getBalanceList",'getBalanceAssureList','getBizTime'));
        $obj->expects( $this->any())
            ->method('getBalanceList')
            ->will($this->returnValue($balanceServiceData));
         $obj->expects( $this->any())
             ->method('getBalanceAssureList')
             ->will($this->returnValue($balanceServiceData));
         $obj->expects( $this->any())
             ->method('getBizTime')
             ->will($this->returnValue($timeList));

        $res = array (
            3 =>
                array (
                    1 =>
                        array (
                            'beginAt' =>
                                array (
                                    0 => strtotime(date('2014-12-24')),
                                ),
                            'endAt' =>
                                array (
                                    0 => (strtotime(date('2015-1-24'))-1),
                                ),
                        ),
                    2 =>
                        array (
                            'beginAt' =>
                                array (
                                    0 => strtotime(date('2014-12-24')),
                                ),
                            'endAt' =>
                                array (
                                    0 => (strtotime(date('2015-1-24'))-1),
                                ),
                        ),
                ),
            4 =>
                array (
                    1 =>
                        array (
                            'beginAt' =>
                                array (
                                    0 => strtotime(date('2014-12-24')),
                                ),
                            'endAt' =>
                                array (
                                    0 => (strtotime(date('2015-1-24'))-1),
                                ),
                        ),
                    2 =>
                        array (
                            'beginAt' =>
                                array (
                                    0 => strtotime(date('2014-12-24')),
                                ),
                            'endAt' =>
                                array (
                                    0 => (strtotime(date('2015-1-24'))-1),
                                ),
                        ),
                ),
        );
        $ret = $obj->getListHaveshallList($option,$businessScope);
       $this->assertEquals($ret,$res);
    }



}
