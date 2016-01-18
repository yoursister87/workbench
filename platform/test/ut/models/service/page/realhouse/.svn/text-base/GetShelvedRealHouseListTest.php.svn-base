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

class GetShelvedRealHouseList extends Testcase_PTest{
    protected $data;

    public function __construct(){
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    public function testExecute(){
        $arrInput = array('puidList' => array('0123456789'), 'filter_puid' => 'mark', 'arrFields' => array('puid', 'premier_status', 'listing_status'));
        $obj = $this->genObjectMock('Service_Data_Source_FangByAccount', array('getPostListByConds'));
        $obj->expects($this->any())
            ->method('getPostListByConds')
            ->with(array('puid = ' => '0123456789'), $arrInput['arrFields'])
            ->will($this->returnValue(array('data' => array('puid' => '0123456789', 'premier_status' => 110, 'listing_status' => 1))));
        Gj_LayerProxy::registerProxy('Service_Data_Source_FangByAccount', $obj);
        $obj1 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj1->execute($arrInput);
        $this->assertEquals(array(array('puid' => '0123456789', 'premier_status' => 110, 'listing_status' => 1)), $res);


        $arrInput = array('arrChangeRow' => array('num' => 2), 'account_id' => 111, 'bussiness_scope' => 4);
        $IntAccountId = 111;
        $arrConds = array(
            'account_id' => 111,
            'bussiness_scope' => 4,
            'daynum' => mktime(0, 0, 0, date('m'), 1, date('Y'))
        );
        $obj = $this->genObjectMock('Service_Data_Source_UserRefreshNum', array('getRefreshInfoByAccount', 'updateNumByAccount'));
        $obj->expects($this->any())
            ->method('getRefreshInfoByAccount')
            ->with($arrConds)
            ->will($this->returnValue(array('data' => array('num' => 1), 'errorno' => 0, 'errormsg' => '数据返回成功')));
        $obj->expects($this->any())
            ->method('updateNumByAccount')
            ->with($arrInput['arrChangeRow'], $arrInput['account_id'], $arrInput['bussiness_scope'])
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy('Service_Data_Source_UserRefreshNum', $obj);
        $obj2 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj2->execute($arrInput);
        $this->assertEquals(true, $res);

        $arrInput = array('arrChangeRow' => array('num' => 2), 'account_id' => 111, 'bussiness_scope' => 4);
        $IntAccountId = 111;
        $arrConds = array(
            'account_id' => 111,
            'bussiness_scope' => 4,
            'daynum' => mktime(0, 0, 0, date('m'), 1, date('Y'))
        );
        $obj = $this->genObjectMock('Service_Data_Source_UserRefreshNum', array('getRefreshInfoByAccount', 'insertNumCountInfo'));
        $obj->expects($this->any())
            ->method('getRefreshInfoByAccount')
            ->with($arrConds)
            ->will($this->returnValue(array('data' => array('num' => 0), 'errorno' => 0, 'errormsg' => '数据返回成功')));
        $obj->expects($this->any())
            ->method('insertNumCountInfo')
            ->with(array('account_id' => 111, 'num' => 2, 'bussiness_scope' => 4))
            ->will($this->returnValue($this->data));
        Gj_LayerProxy::registerProxy('Service_Data_Source_UserRefreshNum', $obj);
        $obj2 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj2->execute($arrInput);
        $this->assertEquals($this->data, $res);

        $arrInput = array('account_id' => 111, 'listing_status' => 1);
        $arrConds = array('listing_status=' => 1, 'premier_status in (111,112)');
        $obj = $this->genObjectMock('Service_Data_Source_FangByAccount', array('getRealHouseCountByAccount'));
        $obj->expects($this->any())
            ->method('getRealHouseCountByAccount')
            ->with($arrInput['account_id'])
            ->will($this->returnValue(array('data' => array(array('num' => 3, 'type' => 5)), 'errorno' => 0, 'errormsg' => '数据返回成功')));
        Gj_LayerProxy::registerProxy('Service_Data_Source_FangByAccount', $obj);
        $obj1 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj1->execute($arrInput);
        $this->data['data'] = 3;
        $this->assertEquals($this->data, $res);

        $arrInput = array('puidList' => array(111), 'update_user_post_list' => 'mark');
        $arrFields = array('puid', 'house_id', 'type', 'account_id');
        $obj =  $this->genObjectMock('Service_Data_Source_FangByAccount', array('getPostListByConds'));
        $obj->expects($this->any())
            ->method('getPostListByConds')
            ->with(array('puid = ' => 111), $arrFields)
            ->will($this->returnValue(array('data' => array(array('puid' => 111, 'house_id' => 222, 'type' => 5, 'account_id' => 111)), 'errorno' => 0, 'errormsg' => '数据返回成功')));
        Gj_LayerProxy::registerProxy('Service_Data_Source_FangByAccount', $obj);
        $obj1 = $this->genObjectMock('Service_Data_Source_UserPostList', array('insertHouseRecord'));
        $obj1->expects($this->any())
            ->method('insertHouseRecord')
            ->with(array('puid' => 111, 'house_id' => 222, 'type' => 5, 'account_id' => 111))
            ->will($this->returnValue(array('errorno' => 0, 'errormsg' => '数据返回成功')));
        Gj_LayerProxy::registerProxy('Service_Data_Source_UserPostList', $obj1);
        $obj2 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj2->execute($arrInput);
        $this->assertEquals(array('data' => array(), 'errorno' => 0, 'errormsg' => '[数据返回成功]'), $res);

        $arrInput = array('account_id' => 111);
        $arrConds = array('account_id' => 111, 's_post_at' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'e_post_at' => time());
        $obj = $this->genObjectMock('Service_Data_Source_UserPostList', array('getHouseListByWhere'));
        $obj->expects($this->any())
            ->method('getHouseListByWhere')
            ->with($arrConds)
            ->will($this->returnValue(array('data' => array(array('puid' => 111)))));
        Gj_LayerProxy::registerProxy('Service_Data_Source_UserPostList', $obj);
        $obj1 = new Service_Page_RealHouse_GetShelvedRealHouseList();
        $res = $obj1->execute($arrInput);
        $this->data['data'] = array(111);
        $this->assertEquals($this->data, $res);
    }


}


