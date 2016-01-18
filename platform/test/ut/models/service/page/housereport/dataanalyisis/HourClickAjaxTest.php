<?php
/**
 * File Name:HourClickAjaxTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
*/

class HourClickAjaxTest extends Testcase_PTest{
    
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    public function testsumHourData(){
        $obj = new Service_Page_HouseReport_Dataanalysis_HourClickAjax();
        $data = array('h1'=>1,'h2'=>1,'h3'=>3);
        $ret = $obj->sumHourData($data);
        $res = array (
            'h1' => 1,
            'h2' => 1,
            'h3' => 3,
            'sum' => 5,
        );
        $this->assertEquals($ret, $res);
    }

    public function testgetCustomerList(){
        $data = array('data'=>array('list'=>array(array('customer_id'=>11))));
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", 
            array("getChildTreeByOrgId"));
        $obj->expects($this->any())
            ->method('getChildTreeByOrgId')
            ->will($this->returnValue($data));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $pid = 123;
        $obj = new Service_Page_HouseReport_Dataanalysis_HourClickAjax();
        $ret = $obj->getCustomerList($pid);
        $res = array (
            0 => 11,
        );
        $this->assertEquals($ret, $res);
    }

    public function testformartData(){
        $data = array (
            'h1' => 1,
            'h2' => 1,
            'h3' => 3,
            'sum' => 5,
        );
        $obj = new Service_Page_HouseReport_Dataanalysis_HourClickAjax();
        $params = array(
            'color'=>'#111',
        );
        $ret = $obj->formartData($data,$params);
        $res = array (
            0 => 
            array (
                'data' => 
                array (
                    0 => 
                    array (
                        'F1' => 0,
                        'F2' => '0',
                    ),
                    1 => 
                    array (
                        'F1' => 20,
                        'F2' => '1',
                    ),
                    2 => 
                    array (
                        'F1' => 20,
                        'F2' => '2',
                    ),
                    3 => 
                    array (
                        'F1' => 60,
                        'F2' => '3',
                    ),
                    4 => 
                    array (
                        'F1' => 0,
                        'F2' => '4',
                    ),
                    5 => 
                    array (
                        'F1' => 0,
                        'F2' => '5',
                    ),
                    6 => 
                    array (
                        'F1' => 0,
                        'F2' => '6',
                    ),
                    7 => 
                    array (
                        'F1' => 0,
                        'F2' => '7',
                    ),
                    8 => 
                    array (
                        'F1' => 0,
                        'F2' => '8',
                    ),
                    9 => 
                    array (
                        'F1' => 0,
                        'F2' => '9',
                    ),
                    10 => 
                    array (
                        'F1' => 0,
                        'F2' => '10',
                    ),
                    11 => 
                    array (
                        'F1' => 0,
                        'F2' => '11',
                    ),
                    12 => 
                    array (
                        'F1' => 0,
                        'F2' => '12',
                    ),
                    13 => 
                    array (
                        'F1' => 0,
                        'F2' => '13',
                    ),
                    14 => 
                    array (
                        'F1' => 0,
                        'F2' => '14',
                    ),
                    15 => 
                    array (
                        'F1' => 0,
                        'F2' => '15',
                    ),
                    16 => 
                    array (
                        'F1' => 0,
                        'F2' => '16',
                    ),
                    17 => 
                    array (
                        'F1' => 0,
                        'F2' => '17',
                    ),
                    18 => 
                    array (
                        'F1' => 0,
                        'F2' => '18',
                    ),
                    19 => 
                    array (
                        'F1' => 0,
                        'F2' => '19',
                    ),
                    20 => 
                    array (
                        'F1' => 0,
                        'F2' => '20',
                    ),
                    21 => 
                    array (
                        'F1' => 0,
                        'F2' => '21',
                    ),
                    22 => 
                    array (
                        'F1' => 0,
                        'F2' => '22',
                    ),
                    23 => 
                    array (
                        'F1' => 0,
                        'F2' => '23',
                    ),
                ),
                'color' => '#111',
            ),
        );
        $this->assertEquals($ret, $res);
    }

    public function testgetDefaultData(){
        $defaultHour = 0;
        $otherDefault = array('color'=>'#111');
        $obj = new Service_Page_HouseReport_Dataanalysis_HourClickAjax();
        $ret = $obj->getDefaultData($defaultHour,$otherDefault);       
        $res = array (
            0 => 
            array (
                'h0' => 0,
                'h1' => 0,
                'h2' => 0,
                'h3' => 0,
                'h4' => 0,
                'h5' => 0,
                'h6' => 0,
                'h7' => 0,
                'h8' => 0,
                'h9' => 0,
                'h10' => 0,
                'h11' => 0,
                'h12' => 0,
                'h13' => 0,
                'h14' => 0,
                'h15' => 0,
                'h16' => 0,
                'h17' => 0,
                'h18' => 0,
                'h19' => 0,
                'h20' => 0,
                'h21' => 0,
                'h22' => 0,
                'h23' => 0,
                'color' => '#111',
            ),
        );
        $this->assertEquals($ret, $res);
    }
}
