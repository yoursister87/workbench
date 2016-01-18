<?php
/*
 * File Name:BizCompanyInfoTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class BizCompanyInfoTest extends Testcase_PTest{

    public function setUp(){
        //Gj_LayerProxy::$is_ut = true;
        $this->data['data'][835]['companyid'] = 835;
    }
    public function testGetAllBizCompanyList(){
        $a = new Service_Data_CompanyShop_BizCompanyInfo();
        $ret = $a->getAllBizCompanyList(12,20,'abc');
        $this->assertEquals($ret['errorno'],ErrorConst::E_PARAM_INVALID_CODE);
        
        $obj = $this->genEasyObjectMock("MsCrmAdPostApp",array('getCrmAdPost'),array(
                'housing_ppzq'=>array(
                    array('ExtInfo'=>array('companyid'=>835))
                )
        ));
        $a = new Service_Data_CompanyShop_BizCompanyInfo();
        $a->objUtil = $obj;
        $ret = $a->getAllBizCompanyList(12,20);
        $this->assertEquals($ret['data'][835]['companyid'],$this->data['data'][835]['companyid']);
    }

    public function testGetBizCompanyByCompanyId(){
        $a = new Service_Data_CompanyShop_BizCompanyInfo();
        $ret = $a->getBizCompanyByCompanyId(1065,12,20,'abc');
        $this->assertEquals($ret['errorno'],ErrorConst::E_PARAM_INVALID_CODE);
        
        #测试一
        $obj = $this->genEasyObjectMock("Service_Data_CompanyShop_BizCompanyInfo",array('getBizCompanyByCompanyId'),array(
                'data'=>
                    array('835'=>array('companyid'=>835))
        ));
        $ret = $obj->getBizCompanyByCompanyId(835,12,20);
        $this->assertEquals($ret['data'][835],array('companyid'=>835));
        #测试二
        $obj = $this->genEasyObjectMock("Service_Data_CompanyShop_BizCompanyInfo",array('getAllBizCompanyList'),array(
                'errorno'=>ErrorConst::E_PARAM_INVALID_CODE
        ));
        $a = new Service_Data_CompanyShop_BizCompanyInfo();
        $ret = $obj->getBizCompanyByCompanyId(835,12,20,'abc');
        $this->assertEquals($ret['errorno'],ErrorConst::E_PARAM_INVALID_CODE);
        #测试三
        $obj = $this->genEasyObjectMock("Service_Data_CompanyShop_BizCompanyInfo",array('getBizCompanyByCompanyId'),array(
            'data'=>
            array()
        ));
        $ret = $obj->getBizCompanyByCompanyId(835,12,20);
        $this->assertEquals($ret['data'],array());
        
    }
}
