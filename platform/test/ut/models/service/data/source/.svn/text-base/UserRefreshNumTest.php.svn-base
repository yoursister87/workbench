<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */

class UserRefreshNum extends Testcase_PTest{
    protected $data;

    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    public function testGetRefreshInfoByAccount(){
        $arrInput = array('account_id' => 111, 'bussiness_scope' => 4, 'daynum' => '0123456789');
        $arrConds = array('account_id=' => $arrInput['account_id'], 'bussiness_scope=' => $arrInput['bussiness_scope'], 'daynum=' => $arrInput['daynum']);
        $arrFields = array('account_id', 'daynum', 'num', 'bussiness_scope');
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('getRealHouseCountByAccount'));
        $obj->expects($this->any())
            ->method('getRealHouseCountByAccount')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(array('account_id' => 111, 'bussiness_scope' => 4, 'daynum' => '0123456789', 'num' => 1)));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->getRefreshInfoByAccount($arrInput);
        $this->data['data'] = array('account_id' => 111, 'bussiness_scope' => 4, 'daynum' => '0123456789', 'num' => 1);
        $this->assertEquals($this->data, $res);

        $arrInput = array('account_id' => 111, 'bussiness_scope' => 4, 'daynum' => '0123456789');
        $arrConds = array('account_id=' => $arrInput['account_id'], 'bussiness_scope=' => $arrInput['bussiness_scope'], 'daynum=' => $arrInput['daynum']);
        $arrFields = array('account_id', 'daynum', 'num', 'bussiness_scope');
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('getRealHouseCountByAccount', 'getLastSQL'));
        $obj->expects($this->any())
            ->method('getRealHouseCountByAccount')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(false));
        $obj->expects($this->any())
            ->method('getLastSQL')
            ->will($this->returnValue('select failed'));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->getRefreshInfoByAccount($arrInput);
        $data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $data['errormsg'] = ErrorConst::E_DB_FAILED_MSG."sql :select failed";
        $this->assertEquals($data, $res);
    }

    public function testUpdateNumByAccount(){
        $arrChangeRow = array('num' => 2);
        $intAccountId = 111;
        $bussiness_scope = 4;
        $arrConds = array('account_id =' => $intAccountId, 'daynum = ' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'bussiness_scope' => $bussiness_scope);
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('update'));
        $obj->expects($this->any())
            ->method('update')
            ->with($arrChangeRow, $arrConds)
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->updateNumByAccount($arrChangeRow, $intAccountId, $bussiness_scope);
        $this->assertEquals($res, $this->data);

        $arrChangeRow = array('num' => 2);
        $intAccountId = 111;
        $bussiness_scope = 4;
        $arrConds = array('account_id =' => $intAccountId, 'daynum = ' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'bussiness_scope' => $bussiness_scope);
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('update'));
        $obj->expects($this->any())
            ->method('update')
            ->with($arrChangeRow, $arrConds)
            ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->updateNumByAccount($arrChangeRow, $intAccountId, $bussiness_scope);
        $data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $data['errormsg'] = 'update failed';
        $this->assertEquals($res, $data);
    }

    public function testInsertNumCountInfo(){
        $arrFields = array('account_id' => 111, 'daynum = ' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'bussiness_scope' => 4);
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('insertCountInfo'));
        $obj->expects($this->any())
            ->method('insertCountInfo')
            ->with($arrFields)
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->insertNumCountInfo($arrFields);
        $this->assertEquals($res,$this->data);

        $arrFields = array('account_id' => 111, 'daynum = ' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'bussiness_scope' => 4);
        $obj = $this->genObjectMock('Dao_Housepremier_UserRefreshNum', array('insertCountInfo'));
        $obj->expects($this->any())
            ->method('insertCountInfo')
            ->with($arrFields)
            ->will($this->returnValue(false));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_UserRefreshNum', $obj);
        $obj1 = new Service_Data_Source_UserRefreshNum();
        $res = $obj1->insertNumCountInfo($arrFields);
        $this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $this->data['errormsg'] = 'insert failed';
        $this->assertEquals($this->data,$res);
    }
}
