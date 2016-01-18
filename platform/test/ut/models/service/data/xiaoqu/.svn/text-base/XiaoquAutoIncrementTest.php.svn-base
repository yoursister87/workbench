<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class XiaoquAutoIncrementTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    /**testAddXiaoquAutoIncrement{{{*/
    public function testAddXiaoquAutoIncrement(){
        $xiaoquInfo = array(
            'xiaoqu_name' =>  '上地东里',
            'city_id' => 12,
            'district_id' =>'13' ,
            'street_id' =>'13' ,
            'xiaoqu_address' => '上地东里'
        );
        $data = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => 123,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquAutoIncrement', array('addXiaoquAutoIncrement'));
        $mockObj->expects($this->at(0))
                ->method('addXiaoquAutoIncrement')
                ->will($this->returnValue(123));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquAutoIncrement', $mockObj);
        
        $obj = new Service_Data_Xiaoqu_AutoIncrement();
        $ret = $obj->addXiaoquAutoIncrement($xiaoquInfo);
        $this->assertEquals($data, $ret);
    }//}}}
    /**testAddXiaoquAutoIncrementOfCatch{{{*/
    public function testAddXiaoquAutoIncrementOfCatch(){
        $data = array(
            'errorno'  => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG,
        );
        $mockObj = $this->genObjectMock('Dao_Xiaoqu_XiaoquAutoIncrement', array('addXiaoquAutoIncrement'));
        $mockObj->expects($this->at(0))
                ->method('addXiaoquAutoIncrement')
                ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy('Dao_Xiaoqu_XiaoquAutoIncrement', $mockObj);
        
        $obj = new Service_Data_Xiaoqu_AutoIncrement();
        $ret = $obj->addXiaoquAutoIncrement(array());
        $this->assertEquals($data, $ret);
        
    }//}}}
}
