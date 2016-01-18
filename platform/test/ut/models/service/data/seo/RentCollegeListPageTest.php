<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$ 
 * @file                 $RentCollegeListPageTest.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-18$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class RentCollegeListPageTest extends  Testcase_PTest
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
                        'majorPath'=> 'college',
                        'postCount' => 3,
                        'agent'=> 4,
                        'city' => 'bj'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta' => array(
                            'title' => '【大学附近租房_大学附近房屋出租信息】-北京赶集网',
                            'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网。',
                            'keywords' => '大学附近租房,大学周边房屋出租,大学附近租房信息'
                            ),
                        ),
                    ), 
                //#2
                array(
                    array(
                        'pageType' => 'list',
                        'category' => 'fang1',
                        'majorPath'=> 'college',
                        'postCount' => 3,
                        'agent'=> 1,
                        'city' => 'bj',
                        'agentTitle' => '认证个人'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta' => array(
                            'title' => '【大学附近认证个人租房信息】-北京赶集网',
                            'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网。',
                            'keywords' => '大学附近租房,大学周边房屋出租,大学附近租房信息'
                            ),
                        ),
                    ), 
                //#3
                array(
                    array(
                        'pageType' => 'list',
                        'category' => 'fang1',
                        'majorPath'=> 'college',
                        'postCount' => 3,
                        'college'=> 'qhdx',
                        'agent'=> 4,
                        'city' => 'bj'
                        ),
                    array(
                        'errorno' => '0',
                        'errormsg' => '[数据返回成功]',
                        'meta' => array(
                            'title' => '【清华大学附近租房信息】-北京赶集网',
                            'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网。',
                            'keywords' => '大学附近租房,大学周边房屋出租,大学附近租房信息'
                            ),
                        ),
                    ), 

                );
    }
}
