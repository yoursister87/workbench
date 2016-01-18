<?php
class Service_Data_Source_PremierSubmit_Mock extends Service_Data_Source_PremierSubmit {
    public function addDbSource($arrFields){
        $this->objDaoDetail = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceRentPremier");
        return parent::addDbSource($arrFields);
    }
}

class Service_Data_Source_PremierSubmitTest extends Testcase_PTest
{
    protected $db_res_array;
    protected $ret_array;

    protected  function setup(){

        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
        Gj_LayerProxy::$is_ut = true;

    }

    public function testAddSource(){
        $arrPost = array (
            'xiaoqu' => '安宁里南区',
            'district_select_id' => '0,海淀',
            'street_select_id' => '14,清河',
            'xiaoqu_address' => '北京市海淀区安宁庄东路',
            'district_lock' => '',
            'street_lock' => '',
            'district_id' => -1,
            'street_id' => -1,
            'huxing_shi' => '1',
            'huxing_ting' => '1',
            'huxing_wei' => '1',
            'area' => '400',
            'ceng' => '1',
            'ceng_total' => '6',
            'chaoxiang' => '6',
            'zhuangxiu' => '1',
            'fang_xing' => '2',
            'peizhi' => 'chuang,jiajv',
            'price' => '1234',
            'pay_type' => '押一付三',
            'title' => '测试发帖信息334',
            'description' => 'test description',
            'tab_system' => '2,3',
            'tab_personality' => '',
            'latlng' => 'b116.33971722848,40.050417116991',
            'person' => 'bjtest',
            'phone' => '15652313231',
            'is_free' => '',
            'type' => '1',
            'city' => 0,
            'thumb_img' => 'gjfstmp2/M00/00/02/wKgCzFQ,m3mIZPvgAAAX7,w04sEAAAA0APQn2gAABgH518_120-100_9-0.jpg',
            'image_count' => 1,
            'district_name' => '',
            'street_name' => '',
            'priority' => 1009,
            'pinyin' => 'anninglinanqu',
            'xiaoqu_id' => '2367',
            'subway' => '',
            'college' => '',
            'refresh_at' => 1413454246,
            'post_at' => 1413454246,
            'modified_at' => 1413454246,
            'ip' => '3232249284',
            'account_id' => '29998',
            'premier_status' => 0,
            'bid_status' => 0,
            'listing_status' => '1',
            'puid' => 97955488,
            'user_code' => '',
        );
        $objDaoDetail = $this->genObjectMock("Dao_Housepremier_HouseSourceRentPremier",array("insert","startTransaction","commit"));
        $objDaoDetail->expects($this->any())
            ->method('insert')
            ->will($this->returnValue(12345));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceRentPremier",$objDaoDetail);
        $objDaoDesc = $this->genObjectMock("Dao_Housepremier_HouseSourceDescription",array("insertDesc"));
        $objDaoDesc->expects($this->any())
            ->method('insertDesc')
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceDescription",$objDaoDesc);
        $objDaoList = $this->genObjectMock("Dao_Housepremier_HouseSourceList",array("insert"));
        $objDaoList->expects($this->any())
            ->method('insert')
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList",$objDaoList);

        $objDaoList = $this->genObjectMock("Service_Data_Source_PremierSubmit",array("insert"));
        $objDaoList->expects($this->any())
            ->method('insert')
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList",$objDaoList);


        $obj = new Service_Data_Source_PremierSubmit_Mock();
        $ret = $obj->addDbSource($arrPost);
        $this->ret_array['data']['house_id'] = 12345;
        $this->assertEquals($this->ret_array,$ret);

    }
}