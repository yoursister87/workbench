<?php
/*
 * File Name:DaoAccountBusinessInfoTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class DaoAccountBusinessInfoTest extends Testcase_PTest
{

    function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    function testcheckMustFields(){
        $obj = new Dao_Housepremier_AccountBusinessInfo();
        $arrayFields = array(1,2,3,4,5);
        $mustFields = array(4,5,6);
        $ret = $obj->checkMustFields($arrayFields, $mustFields);
        $data = array(4,5);
        $this->assertEquals($ret,$this->data);
    }    
}
