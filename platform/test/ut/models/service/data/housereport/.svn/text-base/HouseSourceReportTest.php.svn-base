<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class HouseSourceReport extends Testcase_PTest
{
    protected $data;
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    public function testGetReportByWhere(){
        $whereConds = array('house_id'=>454534);
        $arrFields = array('ClickCount');
        $obj = $this->genObjectMock("Service_Data_HouseReport_HouseSourceReport",array("getReportWhere"));
        $obj->expects($this->any())
            ->method("getReportWhere")
            ->will($this->returnValue(array()));
        $res = $obj->getReportByWhere($whereConds,$arrFields);
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$res);

        $arrConds = array(
            'HouseSourceId ='=>$whereConds['house_id']
        );
        $arrFields = array("SUM(ClickCount) AS account_pv");
        $returnData = 50;
        $obj1 = $this->genObjectMock("Dao_Housereport_HouseSourceReport",array("select"));
        $obj1->expects($this->any())
            ->method("select")
            ->with($arrFields, $arrConds)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Dao_Housereport_HouseSourceReport", $obj1);


        $obj = $this->genObjectMock("Service_Data_HouseReport_HouseSourceReport",array("getReportWhere"),array(),'',true);
        $obj->expects($this->any())
            ->method("getReportWhere")
            ->will($this->returnValue($arrConds));

        $res = $obj->getReportByWhere($whereConds);
        $data = array(
            'data'     => $returnData,
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $this->assertEquals($data,$res);
    }
    public function testGetReportWhere(){
        $obj = new Service_Data_HouseReport_HouseSourceReport();
        $whereConds = array(
            'house_id'=>454534,
            'house_type'=>3,
            'account_id'=>454534,
            'ReportDate'=>1419923282
        );
        $res = $obj->getReportWhere($whereConds);
        $data = array (
            'HouseSourceId =' => 454534,
            'HouseSourceCategoryId =' => 3,
            'AccountId =' => 454534,
            'ReportDate =' => 1419923282,
        );
        $this->assertEquals($data,$res);

        $whereConds = array(
            'house_id'=>array(454534,454535),
            'house_type'=>array(3,5),
            'account_id'=>454534,
            'ReportDate'=>1419923282
        );
        $res = $obj->getReportWhere($whereConds);
        $data = array (
            'HouseSourceId in ( 454534,454535 )',
            'HouseSourceCategoryId in ( 3,5 )',
            'AccountId =' => 454534,
            'ReportDate =' => 1419923282,
        );
        $this->assertEquals($data,$res);
    }
}