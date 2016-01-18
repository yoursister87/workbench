<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$
 * @file                 $RentBusListPageTest.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-18$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class RentBusListPageTest extends Testcase_PTest
{
    protected function setUp(){
    }

    /** 
     *@dataProvider TDKProvider
     */ 
    public function testGetMetaInfo($aParams,$aWant){
        return;
        $obj = Gj_LayerProxy::getProxy("Service_Data_SEO_TDK");
        $ret = $obj->getMetaInfo($aParams);
        $this->assertArrayHasKey("meta",$ret,$ret["errormsg"]);
        $this->assertEquals($ret["meta"]["title"],$aWant["meta"]["title"]);
        $this->assertEquals($ret["meta"]["description"],$aWant["meta"]["description"]);
        $this->assertEquals($ret["meta"]["keywords"],$aWant["meta"]["keywords"]);
    } 

    public function TDKProvider(){
        return array(
                //#1
                array(
                    array(
                        'pageType' => 'list',
                        'category' => 'fang1',
                        'majorPath'=> 'bus',
                        "list_type" => "bus",
                        "postCount" => 3,
                        'city' => 'bj',
                        'bus_line' => '183',
                        'station' => '2'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta'=>array(
                            'title' => '【北京公交冷泉村沿线租房|公交冷泉村附近房屋出租信息】- 北京赶集网',
                            'description' => '赶集网北京租房网，为您提供最新3条北京公交328路(龙泉驾校--安定门东站)附近房屋出租信息，房源更新快、信息全。查找公交冷泉村附近个人房屋出租房源、经纪人房源、冷泉村附近单间、合租，请到赶集北京租房网。',
                            'keywords' => '冷泉村租房,冷泉村租房出租信息'
                            ),
                        ),
                    ),

                    //#2
                    array(
                            array(
                                'pageType' => 'list',
                                'category' => 'fang1',
                                'majorPath'=> 'bus',
                                "list_type" => "bus",
                                "postCount" => 3,
                                'city' => 'bj',
                                'bus_line' => '183',
                                ),
                            array(
                                'errorno' => '0',
                                'errormsg' => '[数据返回成功]',
                                'meta'=>array(
                                    'title' => '【北京公交328路(龙泉驾校--安定门东站)沿线租房|公交328路(龙泉驾校--安定门东站)附近房屋出租信息】- 北京赶集网',
                                    'description' => '赶集网北京租房网，为您提供最新3条北京公交328路(龙泉驾校--安定门东站)附近房屋出租信息，房源更新快、信息全。查找公交附近个人房屋出租房源、经纪人房源、附近单间、合租，请到赶集北京租房网。',
                                    'keywords' => '328路(龙泉驾校--安定门东站)租房,328路(龙泉驾校--安定门东站)租房出租信息'
                                    ),
                                ),
                         ),


                         //#3
                         array(
                                 array(
                                     'pageType' => 'list',
                                     'category' => 'fang1',
                                     "list_type" => "bus",
                                     'majorPath'=> 'bus',
                                     "postCount" => 3,
                                     'city' => 'bj',
                                     'price'=> '800以下'
                                     ),
                                 array(
                                     'errorno' => '0',
                                     'errormsg' => '[数据返回成功]',
                                     'meta'=>array(
                                         'title' => '【北京公交沿线租房|公交附近800以下房屋出租信息】- 北京赶集网',
                                         'description' => '赶集网北京租房网，为您提供最新3条北京公交附近房屋出租信息，房源更新快、信息全。查找公交附近个人房屋出租房源、经纪人房源、附近单间、合租，请到赶集北京租房网。',
                                         'keywords' => '租房,租房出租信息'
                                         ),
                                     ),
                              ),
                         //#4
                         array(
                                 array(
                                     'pageType' => 'list',
                                     'category' => 'fang1',
                                     "list_type" => "bus",
                                     'majorPath'=> 'bus',
                                     "postCount" => 3,
                                     'city' => 'bj',
                                     ),
                                 array(
                                     'errorno' => '0',
                                     'errormsg' => '[数据返回成功]',
                                     'meta'=>array(
                                         'title' => '【北京公交沿线租房|公交附近房屋出租信息】- 北京赶集网',
                                         'description' => '赶集网北京租房网，为您提供最新3条北京公交附近房屋出租信息，房源更新快、信息全。查找公交附近个人房屋出租房源、经纪人房源、附近单间、合租，请到赶集北京租房网。',
                                         'keywords' => '租房,租房出租信息'
                                         ),
                                     ),
                              ),
                         );
    }

}

