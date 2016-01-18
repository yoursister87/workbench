<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class DictTest extends Testcase_PTest {
    /**  
     * @brief 
     *
     * @dataProvider getGetTdkUrlParamProvider 
     */
    public function testGetTdkUrlParam($category, $data) {
        $base= array('area'=> 1234);
        $params['category']= $category;
        $obj= new Dao_Seo_Url_Dict();
        foreach($data as $item){
           $params= array_merge($params, $item['input']);
           $params= array_merge($base, $params);
           $ret= $obj->getTdkUrlParam($params);
           foreach($item['output'] as $key=> $val) {
              $this->assertEquals($ret[$key], $item['output'][$key]);
           }
        }
    }

    public function testCommonParams() {
        $base= array('area'=> 1234, 'category'=> 1234);
        $obj= new Dao_Seo_Url_Dict();
        $data= array(
                array("input"=> array("image_count"=>1), "output"=> array('image_count' =>"[图]")),
                array("input"=> array("huxing_shi"=>'1室'), "output"=> array('huxing_shi' =>"1居室")),
                array("input"=> array("huxing_shi"=>'1室', "huxing_shi2"=>"2卧铺", "huxing_ting"=>"1客厅","huxing_wei"=>"2卫"), "output"=> array('huxing_shi' =>"1居室2卧铺1客厅2卫")),
                array("input"=> array("price"=>"1元/㎡·天"), "output"=> array('price' =>"1元/平方米每天")),
                array("input"=> array("area"=>"1㎡"), "output"=> array('area' =>"1平方米")),
                array("input"=> array("chaoxiang"=>"东"), "output"=> array('chaoxiang' =>"朝东")),
               );
        foreach($data as $item){
           $params= array_merge($base, $item['input']);
           $ret= $obj->getTdkUrlParam($params);
           foreach($item['output'] as $key=> $val) {
              $this->assertEquals($ret[$key], $item['output'][$key]);
           }
        }
    }

    public function getGetTdkUrlParamProvider(){
       $ret= array(
               //fang6
               array("Storerent", 
                     array(
                      array("input"=> array("price"=>"1元"), "output"=>array("price"=>"1元/月")),
                      array("input"=> array("store_rent_type"=> "swcz"), "output"=>array("store_rent_type"=> "|swcz")),
                      array("input"=> array("price"=>"1万"), "output"=>array("price"=>"1万元/月")),
                      array("input"=> array("price"=>143) , "output"=>array("price"=>"143元/月")),
               )),
               //fang7
               array("Storetrade", 
                     array(
                      array("input"=> array("price"=>124), "output"=>array("price"=>"124万元")),
               )),
               //fang1
               array("Rent", 
                     array(
                      array("input"=> array("huxing_shi"=>1), "output"=>array("huxing_shi"=>"一室一厅")),
               )),
               //fang8
               array("Officerent", 
                     array(
                      array("input"=> array("house_typeText"=>"写字楼"), "output"=>array("house_typeText"=>"写字楼")),
                      array("input"=> array("house_typeText"=>"商务公寓"), "output"=>array("house_typeText"=>"商务公寓")),
                      array("input"=> array("house_typeText"=>""), "output"=>array("house_typeText"=>"写字楼")),
                      array("input"=> array("price_type"=>1, "price"=> 1234), "output"=>array("price"=> "1234元/㎡·天")),
               )),
               //fang9
               array("Officetrade", 
                     array(
                      array("input"=> array("price"=>124), "output"=>array("price"=>'售价124万元', "house_typeText"=>"写字楼")),
                      array("input"=> array("house_typeText"=>"其他"), "output"=>array("house_typeText"=>"其他办公楼")),
               )),
               //fang10
               array("Shortrent", 
                     array(
                      array("input"=> array("tag_type"=>1), "output"=>array("tag_type"=>"在线预订")),
                      array("input"=> array("fang_xingText"=>"其他"), "output"=>array("fang_xingText"=>"集体宿舍|独栋别墅等")),
                      array("input"=> array("rent_type"=>1), "output"=>array("rent_type"=>"求租房屋")),
               )),
               //fang11
               array("Plant", 
                     array(
                      array("input"=> array("house_typeText"=>"其他"), "output"=>array("house_typeText"=>"工业园区等")),
                      array("input"=> array("price_type"=>1, "deal_type"=> 3, 'price'=> 124), "output"=>array("price"=>'售价124元/㎡·天')),
               )),
               //fang12
               array("Loupan", 
                     array(
                      array("input"=> array("agent"=>2), "output"=>array("agent2Title"=>"房产中介")),
               )),
          );
       return $ret;
    }
}
