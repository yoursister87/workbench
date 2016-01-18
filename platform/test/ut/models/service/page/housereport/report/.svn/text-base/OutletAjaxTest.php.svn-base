<?php
/**
 * File Name:OutletAjaxTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
 */

class OutletAjaxTest extends Testcase_PTest
{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Page_HouseReport_Report_OutletAjax();
    }

    public function testexecute(){
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
        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAjaxData"));
        $obj->expects($this->any())
            ->method("groupAjaxData")
            ->will($this->returnValue(123));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOutletData", $obj);

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("matchTableData","matchTitleData"));
        $obj->expects($this->any())
        ->method("matchTableData")
        ->will($this->returnValue($res));
        
        $obj->expects($this->any())
        ->method("matchTitleData")
        ->will($this->returnValue($res));

        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);

        $obj = $this->genObjectMock("Service_Data_HouseReport_ReportService"
            ,array("getPageStr"));
        $obj->expects($this->any())
            ->method("getPageStr")
            ->will($this->returnValue(array('page')));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService", $obj);

        $obj = $this->genObjectMock("Service_Page_HouseReport_Report_OutletAjax",array("groupOutletData"));
        $obj->expects($this->any())
            ->method("groupOutletData")
            ->will($this->returnValue(123));
        $ret = $obj->execute($arr);
        $res =  array (
            'data' => 
            array (
                'dataList' => 
                array (
                    'data' => 
                    array (
                        'titleList' => 456,
                    ),
                ),
                'titleList' => array('titleList'=>456),
                'page' => 
                array (
                    0 => 'page',
                ),
            ),
        );
        $this->assertEquals($ret['data'],$res['data']);
    }


    
}
