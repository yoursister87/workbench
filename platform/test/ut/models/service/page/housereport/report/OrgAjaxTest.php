<?php
/**
 * File Name:OrgAjaxTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
 */
class OrgAjaxTest extends Testcase_PTest
{
    
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Page_HouseReport_Report_OrgAjax();
    }

    public function testsubstrTitle(){
        $title = '123-又是单元测试';
        $obj =  new Service_Page_HouseReport_Report_OrgAjax();
        $res = $obj->substrTitle($title);
        $this->assertEquals('又是单元测试',$res);
        
        $title = '123－又是单元测试';
        $obj =  new Service_Page_HouseReport_Report_OrgAjax();
        $res = $obj->substrTitle($title);
        $this->assertEquals('又是单元测试',$res);

    }

    public function testgroupOrgData(){
        $params = array(
            'page'=>1,
            'level'=>1,
            'companyId'=>1234,
            'userId'=>100,
            'keyword'=>'keyword'
        );

        $orgData = array(
            'errorno'=>0,
            'data'=>array(
                'list'=>array(
                    array('level'=>1,'id'=>1234,'title'=>'title'),
                ),
                'count'=>1,
            ),
        );

        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",
            array("getChildTreeByOrgId"));
        $obj->expects($this->any())
            ->method("getChildTreeByOrgId")
            ->will($this->returnValue($orgData));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $obj =  new Service_Page_HouseReport_Report_OrgAjax();
        $ret = $obj->groupOrgData($params);
        $res = array (
            'data' => 
            array (
                'title' => '大区',
                'count' => 1,
                'title_list' => 
                array (
                    1234 => 
                    array (
                        'name' => 'title',
                        'href' => '?c=report&level=1&pid=1234',
                        'title' => 'title',
                    ),
                ),
            ),
            'orgIds' => 
            array (
                0 => 1234,
            ),
        );
        $this->assertEquals($ret,$res);
    }

    public function testexecute(){
        $arr['companyId'] = 835;
        $arr['product'] = array('premier','assure','bid');
        $arr['dtype'] = array('org','verify');
        $arr['businessScope'] = array(1);
        $arr['cid'] = 3188;
        $arr['date']['sDate'] = '2014-10-29';
        $arr['date']['eDate'] = '2014-10-30';
        $arr['page']=1; 
        $arr['level']=1; 

        $res = array(
            'data'=>array('dataList'=>123),
            'data'=>array('titleList'=>456),
        );

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupData",array("matchData"));
        $obj->expects($this->any())
            ->method("matchData")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupData", $obj);

        $obj = $this->genObjectMock("Service_Data_HouseReport_GroupOutletData",array("groupAjaxData"));
        $obj->expects($this->any())
            ->method("groupAjaxData")
            ->will($this->returnValue(123));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_GroupOrgData", $obj);


        $obj = $this->genObjectMock("Service_Data_HouseReport_ReportService"
            ,array("getPageStr"));
        $obj->expects($this->any())
            ->method("getPageStr")
            ->will($this->returnValue(array('page')));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService", $obj);


        $result = array(
            'orgIds'=>1234,
            'data'=>array(
                'orgData',
                'count'=>1
            ),
        );
        $obj = $this->genObjectMock("Service_Page_HouseReport_Report_OrgAjax",array("groupOrgData"));
        $obj->expects($this->any())
            ->method("groupOrgData")
            ->will($this->returnValue($result));
        $ret = $obj->execute($arr);
        $res = array (
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
                    0 => 'orgData',
                    'count' => 1,
                ),
                'page' => 
                array (
                    0 => 'page',
                ),
            ),
        );
        $this->assertEquals($ret,$res);
    }
}
