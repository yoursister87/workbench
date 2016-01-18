<?php
class Service_Page_Source_Api_QueryAgentCommentByPuidMock extends Service_Page_Source_Api_QueryAgentCommentByPuid {
    public function getPhotoUrl($path) {
        return parent::getPhotoUrl($path);
    }
    public function checkInput($arrParams) {
        return parent::checkInput($arrParams);
    }
}
class Service_Page_Source_Api_QueryAgentCommentByPuidTest extends Testcase_PTest{
    private $ret_array;

    public function setUp(){
        Gj_LayerProxy::$is_ut = true;
        $this->ret_array =array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
    }

    /**
     * @expectedException Exception 
     */
    public function testGetPhotoUrlException(){
        $obj= new Service_Page_Source_Api_QueryAgentCommentByPuidMock(); 
        $obj->checkInput(array("puid"=> "zhoujielun"));
    }

    public function testExecute(){
        $base= array("post_at"=> "1418714941", 'modified_at'=>0, "user_phone"=>"18245082");
        $mockConfig= array(
                "Service_Data_Source_HouseRealComment"=> array(
                    "getCommentListByWhere"=> array(
                        "return"=> array(
                            "data"=> array(array_merge(array("user_id"=> 1), $base), array_merge(array("user_id"=>2), $base)),
                            "errorno"=>ErrorConst::SUCCESS_CODE,
                            'errormsg' => ErrorConst::SUCCESS_MSG,
                            ))),
                "Service_Data_Gcrm_CustomerAccount"=> array(
                    "getAccountInfoByUserId"=> array(
                        "return"=> array(
                            "data"=> array(
                                array("UserId"=> 1, "AccountId"=>11, "Picture"=>"1.jpg", 'CityId'=>0, 'CellPhone'=>'1234567', 'AccountName'=>'123456'), 
                                array("UserId"=> 2, "AccountId"=>12, "Picture"=>"2.jpg", 'CityId'=>0, 'CellPhone'=>'1234567', 'AccountName'=>'123456')),
                            "errorno"=>ErrorConst::SUCCESS_CODE,
                            'errormsg' => ErrorConst::SUCCESS_MSG,
                            ))),
                );
        $mockObjArr = $this->genAllObjectMock($mockConfig);
        Gj_LayerProxy::registerProxy('Service_Data_Source_HouseRealComment', $mockObjArr['Service_Data_Source_HouseRealComment']);
        Gj_LayerProxy::registerProxy('Service_Data_Gcrm_CustomerAccount', $mockObjArr['Service_Data_Gcrm_CustomerAccount']);
        $obj= new Service_Page_Source_Api_QueryAgentCommentByPuidMock(); 
        $ret_a= $obj->execute(array("puid"=> 87529));
        $data= $ret_a['data'];
        $this->assertEquals(1, $data[0]['user_id']);
        $this->assertEquals(2, $data[1]['user_id']);
    }

    public function testGetPhotoUrl() {
        $data= array("http://www.ganji.com"=> "http://www.ganji.com",
                     "ti32/2df/df.jpg"=> "http://image.ganjistatic1.com/ti32/2df/df.jpg",
                     "/ti32/2df/df.jpg"=> "http://image.ganjistatic1.com/ti32/2df/df.jpg",
                     false=> "http://stacdn201.ganjistatic1.com/src/image/house/fangvip/noimg_head.gif",
                     );
        $obj= new Service_Page_Source_Api_QueryAgentCommentByPuidMock(); 
        foreach($data as $key=> $val){
            $ret= $obj->getPhotoUrl($key);
            $this->assertEquals($ret, $val);
        }
    }
}
