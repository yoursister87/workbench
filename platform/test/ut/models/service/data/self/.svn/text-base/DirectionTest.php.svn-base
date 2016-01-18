<?php
class Fake_Service_Data_Self_Direction extends Service_Data_Self_Direction
{
}
class DirectionTest extends Testcase_PTest
{
    public function testGetList1(){
        //define("PLATFORM_CODE", 'web'); 
        $obj = new Fake_Service_Data_Self_Direction();
        $ret = $obj->getList(null, null, null);
        $this->assertEquals(array(), $ret);
    }
    public function testGetList3(){
        $pcList = array(1, 2, 3);
        $mockClass = array('Fake_Service_Data_Self_Direction' => array('getPcList' => array('return' => $pcList)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_Self_Direction'];
        $ret = $obj->getList(array(1), 2, 3);
        $this->assertEquals($pcList, $ret);
    }
    /*
    public function testGetList2(){
        define("PLATFORM_CODE", 'wap'); 

        $wxList = array(5, 6, 7);
        $mockClass = array('Fake_Service_Data_Self_Direction' => array('getWapList' => array('return' => $wxList)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_Self_Direction'];
        $ret = $obj->getList(array(1), 2, 3);
        $this->assertEquals($wxList, $ret);
    }
    */
}

