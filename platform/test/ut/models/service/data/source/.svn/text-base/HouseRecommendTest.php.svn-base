<?php
/*{{{class Service_Data_Source_HouseRecommend_Object */
class Service_Data_Source_HouseRecommend_Object extends Service_Data_Source_HouseRecommend {
    public function __construct() {
        $this->params= array('city_code' => '0',
                             'major_index' => 3,
                             'puid'  => '96044583',
                             'agent'  => 1,
                             'source' => 'pc',
                             'price' => 1200,
                             'street_name'=>'南大信息东街',
                             'show_pos' => 'first_page_right');
        $this->POS_RIGTH= 1;
        $this->POS_BOTTOM= 0;
        /*非归档贴*/
        $this->COMMON_TYPE= 1; 
        /*归档贴*/
        $this->ARCHIVE_TYPE= 2;
    }
    public function handler_params($params= array()) { 
        return  parent::handler_params($params); 
    }
    public function getParam($params, $str, $default='') {
        return  parent::getParam($params, $str, $default);
    }
    public function formatRecListBottom($recList, $cityCode, $majorIndex) {
        return parent::formatRecListBottom($recList, $cityCode, $majorIndex); 
    }
    public function formatRecListRight($recList, $cityCode, $majorIndex) {
        return parent::formatRecListRight($recList, $cityCode, $majorIndex);
    }
    public function getHuxingDisplay($pos, $post, $majorIndex)  {
        return parent::getHuxingDisplay($pos, $post, $majorIndex);
    }
    public function getPriceDisplay($pos, $post, $majorIndex){
        return parent::getPriceDisplay($pos, $post, $majorIndex);
    } 
    public function getLocation_link($pos, $post) {
        return parent::getLocation_link($pos, $post);
    }
    public  function getHouseDisplay($pos, $post, $majorIndex) {
        return parent::getHouseDisplay($pos, $post, $majorIndex);
    }
}
/*}}}*/
class Service_Data_Source_HouseRecommendTest extends Testcase_PTest{
    /*{{{setUp*/
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    /*}}}*/
    /*{{{testGetHouseDisplay*/
    public function testGetHouseDisplay() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $ret= $obj->getHouseDisplay($obj->POS_RIGTH, array(), -1);
        $this->assertEquals($ret, '');
        $ret= $obj->getHouseDisplay($obj->POS_RIGTH, array('house_type'=> 1), 3);
        $this->assertEquals($ret, '主卧');
        $ret= $obj->getHouseDisplay($obj->POS_RIGTH, array('house_type'=> 2), 3);
        $this->assertEquals($ret, '次卧');
        $ret= $obj->getHouseDisplay($obj->POS_RIGTH, array('house_type'=> 3), 3);
        $this->assertEquals($ret, '隔断间');
        $ret= $obj->getHouseDisplay($obj->POS_RIGTH, array('huxing_shi'=> 3, 'huxing_ting'=>4), 1);
        $this->assertEquals($ret, '3室4厅');
    }
    /*}}}*/
    /*{{{testGetHuxingDisplay*/
    public function testGetHuxingDisplay() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $ret= $obj->getHuxingDisplay($obj->POS_RIGTH, array(), 1);
        $this->assertEquals($ret, '');
        $ret= $obj->getHuxingDisplay($obj->POS_BOTTOM, array('huxing_shi'=> 3), 1);
        $this->assertEquals($ret, '3室');
        $ret= $obj->getHuxingDisplay($obj->POS_BOTTOM, array('house_type'=> 1, 'share_mode'=>1), 3);
        $this->assertEquals($ret, HousingVars::$SHARE_HOUSE_TYPE[1]);
        $ret= $obj->getHuxingDisplay($obj->POS_BOTTOM, array('share_mode'=>2), 3);
        $this->assertEquals($ret, HousingVars::$SHARE_MODE_LIST[2]);
        $ret= $obj->getHuxingDisplay($obj->POS_BOTTOM, array('rent_sex_request'=>1), 3);
        $this->assertEquals($ret, HousingVars::$SHARE_SEX_REQUEST[1]);
        $ret= $obj->getHuxingDisplay($obj->POS_BOTTOM, array(), 3);
        $this->assertEquals($ret, '合租房');
    }
    /*}}}*/
    /*{{{testGetPriceDisplay*/
    public function testGetPriceDisplay() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $ret= $obj->getPriceDisplay($obj->POS_BOTTOM, $obj->params, 3);
        $this->assertEquals($ret, '<b class="fc-org">1200</b>元/月');
        $ret= $obj->getPriceDisplay($obj->POS_BOTTOM, $obj->params, 5);
        $this->assertEquals($ret, '<b class="fc-org"></b>面议');
        $ret= $obj->getPriceDisplay($obj->POS_BOTTOM, array('price'=> 15000), 5);
        $this->assertEquals($ret, '<b class="fc-org">1.5</b>万');
    }
    /*}}}*/
    /*{{{testGetLocation_link*/
    public function testGetLocation_link() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $ret= $obj->getLocation_link(-1, array(), 3);
        $this->assertEquals($ret, '');
        $ret= $obj->getLocation_link($obj->POS_RIGTH, array('street_name'=>'南大信息东路', 'xiaoqu'=>'硅谷'));
        $this->assertEquals($ret, '南大信息东路-硅谷');
        $ret= $obj->getLocation_link($obj->POS_RIGTH, array('street_name'=>'南大信息东路', 'xiaoqu'=>'硅谷小区'));
        $this->assertEquals($ret, '南大信息东路-硅谷小');
        $ret= $obj->getLocation_link($obj->POS_RIGTH, array('street_name'=>'南大信息东路的大东边街道', 'xiaoqu'=>'硅谷'));
        $this->assertEquals($ret, '南大信息-硅谷');
        $ret= $obj->getLocation_link($obj->POS_RIGTH, array('street_name'=>'南大信息东路的大东边街道', 'xiaoqu'=>'硅谷小区的东小区'));
        $this->assertEquals($ret, '南大信息-硅谷小区');
        $ret= $obj->getLocation_link($obj->POS_BOTTOM,  array('street_name'=>'南大信息东路的大东边街道', 'xiaoqu'=>'硅谷小区的东小区'));
        $this->assertEquals($ret, '南大信息东路的大东边街道 - 硅谷小区的东小区');
        $ret= $obj->getLocation_link($obj->POS_BOTTOM,  array('district_name'=>'南大信息东路的大东边街道', 'xiaoqu'=>'硅谷小区的东小区'));
        $this->assertEquals($ret, '南大信息东路的大东边街道 - 硅谷小区的东小区');
    }
    /*}}}*/
    /*{{{testHandlerParams*/
    public function testHandlerParams() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $p0= array('city_code'=> 1);
        $ret0= $obj->handler_params($p0);
        $this->assertEquals($ret0['city_code'], 1);
        $p1= array();
        $ret1= $obj->handler_params($p1);
        $this->assertEquals($ret1['city_code'], 0);
    }
    /*}}}*/
    /*{{{testGetParam*/
    public function testGetParam() {
        $obj= new Service_Data_Source_HouseRecommend_Object();
        $params= $obj->params;
        $this->assertEquals($obj->getParam($params, 'puid'), '96044583');
        $this->assertEquals($obj->getParam($params, 'ganji', 'com'), 'com');
    }
    /*}}}*/
    public function testGetHouseRecList() {
      $recList= array (
            0 => array (
              'agent' => '经纪人', //经纪人、个人
              'area' =>15, //面积
              'district_id' => '0',
              'district_name' => '海淀', //区域名字
              'house_id' => '5051298',
              'house_type' => '2',
              'huxing_shi' => 1,//室
              'huxing_ting' => 2,//厅
              'huxing_wei' => 2,//卫
              'image_count' => '5',
              'listing_status' => 1,
              'major_category' => '3',
              'person' => '陈远', //联系人
              'phone' => '13683319551',//电话号
              'pinyin' => 'huashengjiayuan',
              'post_at' => '08-13',//发帖时间
              'post_type' => 1,
              'price' =>1200, //价格
              'price_type' => '',
              'puid' => '207395',
              'refresh_at' => '2小时前',//刷新时间
              'share_mode' => '1',
              'street_id' => '46',
              'street_name' => '牡丹园',//街道
              'thumb_img' => 'gjfs04/M00/41/B0/wKhzK1IJqPGMaO49AAEsmHSNWNI043_120-100_9-0.jpg',
              'title' => '最新房源 华盛家园 精装明隔带空调 五家合住 可洗澡做饭',//标题
              'xiaoqu' => '华盛家园',//小区名称
              'zhuangxiu' => 2,
              'ceng' =>1,
              'chaoxiang' =>1,
             ),
         ); 
    } 
    public function testFormatRecListBottom() {
       
    }
    public function testFormatRecListRight() {
    }
}

