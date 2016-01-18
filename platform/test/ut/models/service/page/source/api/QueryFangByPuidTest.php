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
class Service_Page_Source_Api_QueryFangByPuidTest  extends Testcase_PTest{
    private $ret_array;

    public function setUp(){
        Gj_LayerProxy::$is_ut = true;
        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
    }


    public function testExecute(){
        $fields = 'id,user_id';
        $puid = '97964417';
        $params = array('puid' => $puid,'db'=>'beijing','table'=>'house_source_rent','fields'=>$fields);
        $ret_ds_arr =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array('id'=>14000246,'user_id'=>500011722),
        );
        $fakeObj = $this->genObjectMock("Service_Data_Source_FangQuery", array("getHouseSourceByPuidInfo"));
        $fakeObj->expects($this->any())
            ->method("getHouseSourceByPuidInfo")
            //->with($this->equalTo($puid))
            ->will($this->returnValue($ret_ds_arr));

        Gj_LayerProxy::registerProxy("Service_Data_Source_FangQuery",$fakeObj);

        //测试条件有
        $obj = new Service_Page_Source_Api_QueryFangByPuid();
        $res = $obj->execute($params);
        $return_right = $this->ret_array;
        $return_right['data'] =array($puid => $ret_ds_arr['data']);

        $this->assertEquals($return_right, $res);
        //测试多个puid
        $params = array('puid' => $puid.',1234','fields'=>$fields);
        $obj = new Service_Page_Source_Api_QueryFangByPuid();
        $res = $obj->execute($params);
        $return_right['data'] = array($puid=>$ret_ds_arr['data'],'1234'=>$ret_ds_arr['data']);
        $this->assertEquals($return_right, $res);

    }
}

