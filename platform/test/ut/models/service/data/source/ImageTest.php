<?php


class Service_Data_Source_ImageTest extends Testcase_PTest
{
    protected $db_res_array;
    protected $ret_array;

    protected  function setup(){
        $this->db_res_array = array(array('id'=>112,'house_id'=>3344));
        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
        Gj_LayerProxy::$is_ut = true;


    }


    public  function testGetImageListByPostId(){
        $intPostId = 1234;
        $objDao = $this->genObjectMock("Dao_Fang_HouseImageRent",array("selectOrderByInd"));
        $objDao->expects($this->any())
            ->method('selectOrderByInd')
            ->with(array("*"), array('post_id ='=>$intPostId))
            ->will($this->returnValue($this->db_res_array));


        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->getImageListByPostId($intPostId,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array;
        $this->assertEquals($res,$this->ret_array);

    }

    public function testGetImageListByPostIdDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("selectOrderByInd"),false);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->getImageListByPostId(1234,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array;
        $this->assertEquals($res,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"select failed"));

    }
    public function testGetImageInfoByImageId(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("select"),$this->db_res_array);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->getImageInfoByImageId(1234,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array;
        $this->assertEquals($res,$this->ret_array);

    }


    public function testGetImageInfoByImageIdDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("select"),false);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->getImageInfoByImageId(1234,'beijing','house_source_rent');
        $this->ret_array['data'] = $this->db_res_array;
        $this->assertEquals($res,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"select failed"));

    }
    public function testInsertImageInfo(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("insert"),12345);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->InsertImageInfo(array('id'=>111,'image_id'=>123),'beijing','house_source_rent');
        $this->assertEquals($res,$this->ret_array);
    }

    public function testInsertImageInfoDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("insert"),false);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->InsertImageInfo(array('id'=>111,'image_id'=>123),'beijing','house_source_rent');
        $this->assertEquals($res,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"insert failed"));
    }

    public function testUpdateImageInfoByPostId(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("update"),12345);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->updateImageInfoByPostId(array('id'=>111,'image_id'=>123),'beijing','house_source_rent',12345);
        $this->assertEquals($res,$this->ret_array);
    }

    public function testUpdateImageInfoByPostIdDaoFailed(){
        $objDao = $this->genEasyObjectMock("Dao_Fang_HouseImageRent",array("update"),false);
        Gj_LayerProxy::registerProxy("Dao_Fang_HouseImageRent",$objDao);
        $obj = new Service_Data_Source_Image();
        $res = $obj->updateImageInfoByPostId(array('id'=>111,'image_id'=>123),'beijing','house_source_rent',12345);
        $this->assertEquals($res,array('errorno' =>ErrorConst::E_DB_FAILED_CODE,'errormsg' =>"update failed"));
    }

}
