<?php
/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   kongxiangshuai <kongxiangshuai@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
*/
class Service_Data_Source_ImageOuterTest extends Testcase_PTest
{
    public function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
        $this->arrFields = array("house_id","type","is_cover","category","image",'middle_image','thumb_image');
    }

    public function testinsertImageInfo()
    {

        $arrRows = array(
            'house_id' => 123,
            'type' => 1
        );
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("insert"));
        $obj->expects($this->any())
            ->method("insert")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $obj = new Service_Data_Source_ImageOuter();
        $ret = $obj->insertImageInfo($arrRows);
        $data = array(
            'data' => array(),
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );
        $this->assertEquals($data, $ret);
    }

    public function testdelImageInfo()
    {
        $arrRows = 'ss';
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("delete"),array('house_premier'));
        $obj->expects($this->any())
            ->method("delete")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $obj = new Service_Data_Source_ImageOuter();
        $ret = $obj->delImageInfo($arrRows);
        $data = array(
            'data' => false,
            'errorno' => 1002,
            'errormsg' => '[参数不合法]'
        );
        $this->assertEquals($data, $ret);

        $arrRows = 123;
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("delete"));
        $obj->expects($this->any())
            ->method("delete")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $obj = new Service_Data_Source_ImageOuter();
        $ret = $obj->delImageInfo($arrRows);
        $data = array(
            'data' => array(),
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );
        $this->assertEquals($data, $ret);
        $arrRows = array(
            'house_id' => 123,
        );
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("delete"));
        $obj->expects($this->any())
            ->method("delete")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $obj = new Service_Data_Source_ImageOuter();
        $ret = $obj->delImageInfo($arrRows);
        $this->assertEquals($data, $ret);
    }

    public function testupdateImageInfo(){
        $arrRows = array('image'=>'xxxx');
        $conds = array('id'=>1);
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("update"));
        $obj->expects($this->any())
            ->method("update")
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $obj = new Service_Data_Source_ImageOuter();
        $ret = $obj->updateImageInfo($arrRows,$conds);
        $data = array(
            'data' => array(),
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );
        $this->assertEquals($data, $ret);
    }
    public function testgetImageListByHouseId(){
        $res = true;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseImageOuter", array("selectGroupbyConds"));
        $obj->expects($this->any())
            ->method('selectGroupbyConds')
            ->will($this->returnValue($res));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseImageOuter", $obj);
        $Obj = new Service_Data_Source_ImageOuter();
        $whereConds = array('house_id'=>123,'type'=>5);
        $res = $Obj->getImageListByHouseId($whereConds, $this->arrFields);
        $data = array(
            'data' => true,
            'errorno' => 0,
            'errormsg' => '[数据返回成功]'
        );
        $this->assertEquals($data,$res);
    }
}
