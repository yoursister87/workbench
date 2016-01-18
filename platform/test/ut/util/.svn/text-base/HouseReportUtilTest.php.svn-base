<?php
/*
 * File Name:HouseReportUtilTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class HouseReportUtilTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    public function testcheckDate(){
        $date = '2014-11-01';
        $obj = new Util_HouseReportUtil();
        $ret = $obj->checkDate($date);
        $this->assertEquals($ret,true);
        $date = '2014-11';
        $ret = $obj->checkDate($date);
        $this->assertEquals($ret,false);
    }
    public function testgetTableName(){
        $date = null;
        $obj = new Util_HouseReportUtil();
        $ret = $obj->getTableName($date);
        $this->assertEquals($ret,date('_Y_m',strtotime('yesterday')));
        $date = '2014-10-01';
        $ret = $obj->getTableName($date);
        $this->assertEquals($ret,'_2014_10');
    }

    public function testassertIsValidDatePeriod(){
        $sdate = null;
        $edate = null;
        $obj = new Util_HouseReportUtil();
        $ret = $obj->assertIsValidDatePeriod($sdate,$edate);
        $this->assertEquals($ret,true);

        $sdate = '2014-11-20';
        $edate = '2014-11-11';
        $ret = $obj->assertIsValidDatePeriod($sdate,$edate);
        $this->assertEquals($ret,true);

        $sdate = '2014-10-20';
        $edate = '2014-11-11';
        $ret = $obj->assertIsValidDatePeriod($sdate,$edate);
        $this->assertEquals($ret,false);

    }
}
