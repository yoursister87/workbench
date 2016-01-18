<?php

/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_FangByAccountTest extends Testcase_PTest
{
    public function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
    }

    public function testGetCountHouseTypeByAccount()
    {
        $account_id = 120921;
        $arrMockRet = array(1 => 123);
        $objDao = $this->genObjectMock("Dao_Premier_HouseSourceList", array("selectGroupbyHouseType"));
        $objDao->expects($this->any())
            ->method('selectGroupbyHouseType')
            ->with($this->IsType("array"), array('account_id=' => $account_id, 'listing_status=' => Dao_Housepremier_HouseSourceList::STATUS_OK, 'premier_status in (2,102)'))
            ->will($this->returnValue($arrMockRet));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objDao);

        $a = new Service_Data_Source_FangByAccount();
        $res = $a->getCountHouseTypeByAccount($account_id);
        $this->assertEquals($res, array('errorno' => ErrorConst::SUCCESS_CODE, 'errormsg' => ErrorConst::SUCCESS_MSG, 'data' => $arrMockRet));
    }

    public function testGetXiaoQuListByAccount()
    {
        $account_id = 120921;
        $arrMockRet = array(array('xiaoqu_id' => 1, 'xiaoqu_name' => 123));
        $objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("selectGroupbyXiaoquId"));
        $objDao->expects($this->any())
            ->method('selectGroupbyXiaoquId')
            ->with($this->IsType("array"), $this->arrayHasKey("account_id="))
            ->will($this->returnValue($arrMockRet));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objDao);

        $a = new Service_Data_Source_FangByAccount();
        $res = $a->getXiaoQuListByAccount($account_id);
        $this->assertEquals($res, array('errorno' => ErrorConst::SUCCESS_CODE, 'errormsg' => ErrorConst::SUCCESS_MSG, 'data' => array(1 => 123)));

        $account_id = 120921;
        $house_type = 5;
        $arrFields = array('district_id', 'street_id', 'xiaoqu_id', 'xiaoqu_name');
        $arrMockRet = array(array('xiaoqu_id' => 1, 'xiaoqu_name' => 123));
        $objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("selectGroupbyXiaoquId"));
        $objDao->expects($this->any())
            ->method('selectGroupbyXiaoquId')
            ->with($this->IsType("array"), $this->arrayHasKey("account_id="))
            ->will($this->returnValue($arrMockRet));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objDao);

        $a = new Service_Data_Source_FangByAccount();
        $res = $a->getXiaoQuListByAccount($account_id, $house_type, $arrFields);
        $this->assertEquals($res, array('errorno' => ErrorConst::SUCCESS_CODE, 'errormsg' => ErrorConst::SUCCESS_MSG, 'data' => array(array('xiaoqu_id' => 1, 'xiaoqu_name' => 123))));
    }

    public function testgetPostCountByAccountId()
    {
        $account_id = 120921;
        $date = '2015-01-28';
        $arrMockRet = array('total' => 10, 'detail' => array('5' => array('num' => 10, 'xiaoquNames' => array('aaa'))));
        $objDao = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("selectAllInfo"));//创建mock对象
        $objDao->expects($this->any())//（匹配）设置方法调用的次数，any():期望调用任意次
        ->method('selectAllInfo')//设置期望期望调用的方法
        ->with($this->IsType("array"), $this->arrayHasKey("account_id="))//(约束)设置调用方法时的入参;arrayHasKey:断言入参数组是否有指定的键;isType:断言当前对象是某个具体的类型
        ->will($this->returnValue($arrMockRet));//(返回)设置调用方法后的返回值;returnValue:返回字面意思
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objDao);
        $obj = new Service_Data_Source_FangByAccount();
        $res = $obj->getPostCountByAccountId($account_id, $date);
        $data = array(
            'total' => 0,
            'detail' =>
                array(
                    1 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    3 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    5 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    6 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    7 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    8 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    9 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    10 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    4 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    2 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    12 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11001 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11003 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11011 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11013 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11021 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11023 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11031 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11033 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11041 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                    11043 =>
                        array(
                            'num' => 0,
                            'xiaoquNames' =>
                                array(),
                        ),
                ),
        );
        $this->assertEquals($res, array('errorno' => ErrorConst::SUCCESS_CODE, 'errormsg' => ErrorConst::SUCCESS_MSG, 'data' =>$data));
    }

    public function testGetRealHouseCountByAccount(){
        $intAccountId = 123;
        $InarrConds = array('type =' => 5);
        $arrConds = array('account_id=' => $intAccountId);
        $arrConds = array_merge($arrConds, $InarrConds);
        $arrFields = array('type', 'count(1) as num');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceList', array('selectGroupbyHouseType'));
        $obj->expects($this->any())
            ->method('selectGroupbyHouseType')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceList', $obj);
        $obj1 = new Service_Data_Source_FangByAccount();
        $res = $obj1->getRealHouseCountByAccount($intAccountId, $InarrConds);
        $data['data'] = 1;
        $data['errorno'] = ErrorConst::SUCCESS_CODE;
        $data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->assertEquals($data, $res);

        $intAccountId = 123;
        $InarrConds = array('type =' => 5);
        $arrConds = array('account_id=' => $intAccountId);
        $arrConds = array_merge($arrConds, $InarrConds);
        $arrFields = array('type', 'count(1) as num');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceList', array('selectGroupbyHouseType', 'getLastSQL'));
        $obj->expects($this->any())
            ->method('selectGroupbyHouseType')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(false));
        $obj->expects($this->any())
            ->method('getLastSQL')
            ->will($this->returnValue(''));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceList', $obj);
        $obj1 = new Service_Data_Source_FangByAccount();
        $res = $obj1->getRealHouseCountByAccount($intAccountId, $InarrConds);
        $data2['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $data2['errormsg'] = ErrorConst::E_DB_FAILED_MSG."sql :";
        $this->assertEquals($data2, $res);
    }

    public function testGetPostListByConds(){
        $arrConds = array('account_id =' => 123, 'type =' => 5);
        $arrFields = array('puid');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceList', array('selectAllInfo'));
        $obj->expects($this->any())
            ->method('selectAllInfo')
            ->with($arrFields,$arrConds)
            ->will($this->returnValue(array('puid' => '112233')));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceList', $obj);
        $obj1 = new Service_Data_Source_FangByAccount();
        $res = $obj1->getPostListByConds($arrConds, $arrFields);
        $data['data'] = array('puid' => '112233');
        $data['errorno'] = ErrorConst::SUCCESS_CODE;
        $data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->assertEquals($res,$data);

        $arrConds = array('account_id =' => 123, 'type =' => 5);
        $arrFields = array('puid');
        $obj = $this->genObjectMock('Dao_Housepremier_HouseSourceList', array('selectAllInfo', 'getLastSQL'));
        $obj->expects($this->any())
            ->method('selectAllInfo')
            ->with($arrFields,$arrConds)
            ->will($this->returnValue(false));
        $obj->expects($this->any())
            ->method('getLastSQL')
            ->will($this->returnValue(''));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceList', $obj);
        $obj1 = new Service_Data_Source_FangByAccount();
        $res = $obj1->getPostListByConds($arrConds, $arrFields);
        $data2['errorno'] = ErrorConst::E_DB_FAILED_CODE;
        $data2['errormsg'] = ErrorConst::E_DB_FAILED_MSG."sql :";
        $this->assertEquals($res,$data2);
    }
}
