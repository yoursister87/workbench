<?php
/*
 * File Name:AccountAjaxTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 *
 */

class AccountAjaxTest extends Testcase_PTest
{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Page_HouseReport_Report_AccountAjax();
    }
    
    public function testgroupAccountData(){
        $params = array(
            'date'=>
            array(
                'sDate'=>'2015-01-18',
                'eDate'=>'2015-01-19',
            ),
        );
        $stime = strtotime($params['date']['sDate']);
        $etime = strtotime($params['date']['eDate']);
        $res = array(
            'data'=>array(
                'title'=>'日期',
                'title_list'=>array(
                    $params['date']['sDate']=>array('name'=>$params['date']['sDate']),
                    $params['date']['eDate']=>array('name'=>$params['date']['eDate']),
                ),
            ),
            'reportDate'=>array(
                $stime,$etime,
            ),

        );
        $obj = new Service_Page_HouseReport_Report_AccountAjax();
        $ret = $obj->groupAccountData($params);
        $this->assertEquals($ret,$res);

    }

    public function testExecute(){
        $arr['companyId'] = 835;
        $arr['product'] = array('premier','assure','bid');
        $arr['dtype'] = array('org','verify');
        $arr['businessScope'] = array(1);
        $arr['cid'] = 3188;
        $arr['date']['sDate'] = '2014-10-29';
        $arr['date']['eDate'] = '2014-10-30';

        $res = array(
            'data'=>array('dataList'=>123),
            'data'=>array('titleList'=>456),
        );
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupAccountData",array("groupAjaxData"));
        $obj->expects($this->any())
            ->method("groupAjaxData")
            ->will($this->returnValue(123));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupAccountData", $obj);

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("matchData"));
        $obj->expects($this->any())
        ->method("matchData")
        ->will($this->returnValue($res));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);

        $obj = new Service_Page_HouseReport_Report_AccountAjax();
        $ret = $obj->execute($arr);
        $res = array(
            'data' => 
            array (
                'dataList' => 
                array (
                    'data' => 
                    array (
                        'titleList' => 456,
                    ),
                ),
                'titleList' => 
                array (
                    'title' => '日期',
                    'title_list' => 
                    array (
                        '2014-10-29' => 
                        array (
                            'name' => '2014-10-29',
                        ),
                        '2014-10-30' => 
                        array (
                            'name' => '2014-10-30',
                        ),
                    ),
                ),
            ),
        );
        $this->assertEquals($ret['data'],$res['data']);
    }

}
