<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$
 * @file                 $RentSubwayListPageTest.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-18$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class RentSubwayListPageTest extends Testcase_PTest
{

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
                        'majorPath'=> 'subway',
                        "list_type" => "ditie",
                        "postCount" => 3,
                        'city' => 'bj',
                        'agent' => '4'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta'=>array(
                            'title' => '【北京地铁附近租房|北京地铁附近房屋出租信息】-北京赶集网',
                            'description' => '赶集网北京租房网，为您提供最新3条北京地铁附近房屋出租信息，房源更新快、信息全。查找地铁附近个人房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京租房网。',
                            'keywords' => '地铁附近租房,地铁附近出租信息'
                            ),
                        ),
                    ),
            //#2
            array(
                    array(
                        'pageType' => 'list',
                        'category' => 'fang1',
                        'majorPath'=> 'subway',
                        "list_type" => "ditie",
                        "postCount" => 3,
                        'subway_line' => '35',
                        'station'=> '4',
                        'city' => 'bj',
                        'agent' => '4'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta'=>array(
                            'title' => '【北京五道口附近租房信息】-北京赶集网',
                            'description' => '赶集网北京租房网，为您提供最新3条北京五道口附近房屋出租信息，房源更新快、信息全。查找五道口附近个人房屋出租房源、经纪人房源、五道口附近单间、合租，请到赶集北京租房网。',
                            'keywords' => '五道口附近租房,五道口附近出租信息'
                            ),
                        ),
                 ),
                 //#3
                 array(
                         array(
                             'pageType' => 'list',
                             'category' => 'fang1',
                             'majorPath'=> 'subway',
                             "list_type" => "ditie",
                             "postCount" => 3,
                             'city' => 'bj',
                             'subway_line' => '35',
                             'agent' => '4'
                             ),
                         array(
                             'errorno' => '0',
                             'errormsg' => '[数据返回成功]',
                             'meta'=>array(
                                 'title' => '【北京地铁13号线沿线租房信息】-北京赶集网',
                                 'description' => '赶集网北京租房网，为您提供最新3条北京地铁13号线沿线房屋出租信息，房源更新快、信息全。查找地铁13号线个人房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京租房网。',
                                 'keywords' => '地铁13号线租房,地铁13号线出租信息'
                                 ),
                             ),
                      ),
                 //#4
                      array(
                              array(
                                  'pageType' => 'list',
                                  'category' => 'fang1',
                                  "list_type" => "ditie",
                                  'majorPath'=> 'subway',
                                  "postCount" => 3,
                                  'city' => 'bj',
                                  'agent' => '4',
                                  'price'=> '800以下'
                                  ),
                              array(
                                  'errorno' => '0',
                                  'errormsg' => '[数据返回成功]',
                                  'meta'=>array(
                                      'title' => '【北京地铁沿线800以下租房信息】-北京赶集网',
                                      'description' => '赶集网北京租房网，为您提供最新3条北京地铁附近房屋出租信息，房源更新快、信息全。查找地铁附近个人房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京租房网。',
                                      'keywords' => '地铁附近租房,地铁附近出租信息'
                                      ),
                                  ),
                           ),
                      );
    }   
}
