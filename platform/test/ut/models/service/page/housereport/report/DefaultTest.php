<?php
/**
 * File Name:DefaultTest.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
*/
class DefaultTest extends Testcase_PTest
{
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->obj =  new Service_Page_HouseReport_Report_Default();
    }

    public function testgetBackOutletUrl(){
        $res = $this->obj->getBackOutletUrl(123);
        $this->assertEquals($res,'?c=report&pid=123&level=4');
    }

    /**   
     *@expectedException Exception
     */
    public function testvalidPowerException(){
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",
            array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
        ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue(array()));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $obj =  new Service_Page_HouseReport_Report_Default();
        $obj->validPower(array(123));
    }

    public function testgetShellBalanceList(){
        $result = array(
            1 => array(1441036800)
        );
        $obj = $this->genObjectMock("Util_HouseReport_BalanceOperationUtil",
            array("getListHaveshallList"));
        $obj->expects($this->any())
            ->method("getListHaveshallList")
            ->will($this->returnValue($result));
        Gj_LayerProxy::registerProxy("Util_HouseReport_BalanceOperationUtil", $obj);
        $params = array(
            'CityId'=>12,
            'UserId'=>123,
        );
        $business = array(123);
        $obj =  new Service_Page_HouseReport_Report_Default();
        $res = $obj->getShellBalanceList($params,$business);
        $ret = array (
            1 => 
            array (
                0 => '2015-09-01',
                'bizText' => '民宅综合',
            ),
        );
        $this->assertEquals($res,$ret);
    }

    public function testgetOrgTree(){
        $params = array(
            'userLevel'=>1
        );
        $data = array(
            'data'=>array(
                1=>array('activeList'=>123),
            ),
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",
            array("getTreeByOrgId"));
        $obj->expects($this->any())
            ->method("getTreeByOrgId")
            ->will($this->returnValue($data));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $obj =  new Service_Page_HouseReport_Report_Default();
        $res = $obj->getOrgTree($params);
        $this->assertEquals($data['data'],$res);
    }

    public function testinDurationBizInput(){
        $data = array(
            'data'=>array(
                array('BusinessScope'=>1)
            )
        );

        $obj = $this->genObjectMock("Service_Data_HouseReport_ReportService",
            array("getAllOutlet"));
        $obj->expects($this->any())
            ->method("getAllOutlet")
            ->will($this->returnValue(123));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_ReportService", $obj);


        $obj = $this->genObjectMock("Service_Data_Gcrm_AccountBusinessInfo",
            array("getOpenBizByCompanyId"));
        $obj->expects($this->any())
            ->method("getOpenBizByCompanyId")
            ->will($this->returnValue($data));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_AccountBusinessInfo", $obj);

        $params = array(
            'id'=>123,
            'company_id'=>456,
        );
        $obj =  new Service_Page_HouseReport_Report_Default();
        $res = $obj->inDurationBizInput($params);
        $ret = array (
            0 => '全部',
            1 => '民宅综合',
        );
        $this->assertEquals($res,$ret);
    }

    public function testgetNavUrl(){
        $_GET['pid'] = 1;
        $params['userLevel'] = 1;
        $orgTree = array(
            2=>array('activeList'=>array('id'=>1,'title'=>'title')),
            3=>array('activeList'=>array('id'=>1,'title'=>'title')),
            4=>array('activeList'=>array('id'=>1,'title'=>'title')),
        );

        $obj =  new Service_Page_HouseReport_Report_Default();
        $ret = $obj->getNavUrl($orgTree,$params);
        $res = array (
            '?c=report' => '使用情况统计',
            '?c=report&pid=1&level=2' => 
            array (
                'content' => 'title',
                'show' => true,
            ),
            '?c=report&pid=1&level=3' => 
            array (
                'content' => 'title',
                'show' => true,
            ),
            '?c=report&pid=1&level=4' => 
            array (
                'content' => 'title',
                'show' => true,
            ),
        );
        $this->assertEquals($res,$ret);
    }

    public function testgetOrgSelect(){
        $tree = array(
            'data'=>array(
                array(array('id'=>123,'level'=>1)),
            ),
        );
        $obj = $this->genObjectMock("Service_Page_HouseReport_Report_Default",
            array("getChildTree"),array(),'',true);
        $obj->expects($this->any())
            ->method("getChildTree")
            ->will($this->returnValue($tree));

        $ret = $obj->getOrgSelect(array(),array());
        $res = array (
            0 => 
            array (
                0 => 
                array (
                    'id' => 123,
                    'level' => 1,
                    'url' => '?c=report&pid=123&level=1',
                ),
            ),
        );
        $this->assertEquals($res,$ret);
    }

    public function testgetChildTree(){
        $params = array(
            'userLevel'=>1,
            'id'=>1,
            'company_id'=>1,
            'slevel'=>1,
        );
        //失败状态
        $result = array(
            'errorno'=>1,
        );
        
        $obj =  new Service_Page_HouseReport_Report_Default();
        $ret = $obj->getChildTree($result,$params);
        $res = array (
            'errorno' => 2009,
            'errormsg' => '此结构下面没有数据',
        ); 
        $this->assertEquals($res,$ret);
        $obj =  new Service_Page_HouseReport_Report_Default();
        //成功状态
        $result = array(
            'errorno'=>0,
            'errormsg'=>'success'
        );
        $res = array(
            'data'=>array('data')
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",
        array("getOrgInfoListByPid"),array(),'',true);
        $obj->expects($this->any())
            ->method("getOrgInfoListByPid")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);
        $obj =  new Service_Page_HouseReport_Report_Default();
        $ret = $obj->getChildTree($result,$params);
        $res = array (
            'data' => 
            array (
                'errorno' => 0,
                'errormsg' => 'success',
                1 => 
                array (
                    0 => 'data',
                ),
            ),
        );
        $this->assertEquals($res,$ret);

        #测试二
        unset($params['id']);
        $obj =  new Service_Page_HouseReport_Report_Default();
        $ret = $obj->getChildTree($result,$params);
        $res = array (
            'data' => 
            array (
                'errorno' => 0,
                'errormsg' => 'success',
                2 => 
                array (
                    0 => 'data',
                ),
            ),
        );
        $this->assertEquals($res,$ret);
    }
    /*
    public function testgetUserPage(){
        $params = array(
            'accountId'=>1234,
        );   

        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",
            array("getOrgInfoListByPid"),array(),'',true);
        $obj->expects($this->any())
            ->method("getOrgInfoListByPid")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_HouseManagerAccount", $obj);

    }*/
}
