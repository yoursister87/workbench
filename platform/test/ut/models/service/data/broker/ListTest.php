<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   renyajing$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
error_reporting(E_ALL^E_NOTICE);
class ListTest extends Testcase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    public function testGetSourceList(){
        $wrong = array(
                'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
                'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
                );
        $ok = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('data' => array(1, 2, 3)),
                );
        $obj = new Service_Data_Broker_List();
        $ret = $obj->getSourceList(123);
        $this->assertEquals($ret, $wrong);

        $ret = $obj->getSourceList('');
        $this->assertEquals($ret, $wrong);

        $ret = $obj->getSourceList(array('queryFilter' => 123));
        $this->assertEquals($ret, $wrong);

        $ret = $obj->getSourceList(array('queryFilter' => array()));
        $this->assertEquals($ret, $wrong);

        //normal
        $mockClass = array(
                'Dao_Xapian_Broker' => array(
                    'getSourceList' => array(
                        'return' => array(1, 2, 3)
                        )
                    )
                );
        $mockObj = $this->genAllObjectMock($mockClass);
        Gj_LayerProxy::registerProxy('Dao_Xapian_Broker', $mockObj['Dao_Xapian_Broker']);
        $obj = new Service_Data_Broker_List();
        $ret = $obj->getSourceList(array('queryFilter' => array('city' => 0)));
        $this->assertEquals($ret, $ok);
    }
    public function testGetSourceListException(){
        $exception = array(
            'errorno'  => ErrorConst::E_SQL_FAILED_CODE,
            'errormsg' => '检索searchId返回错误',
        );
        $mockObj = $this->genObjectMock('Dao_Xapian_Broker', array('getSourceList'));
        $mockObj->expects($this->at(0))
            ->method('getSourceList')
            ->will($this->throwException(new Exception('检索searchId返回错误', ErrorConst::E_SQL_FAILED_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xapian_Broker', $mockObj);
        $obj = new Service_Data_Broker_List();
        $ret = $obj->getSourceList(array('queryFilter' => array('city' => 0)));
        $this->assertEquals($ret, $exception);
    }
}
