<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
define("WEBSEARCH","");
/**                                  
 * @package                          
 * @subpackage                       
 * @brief                $$              
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$
 * @file                 $ShareDefaultListPageTest.class.php$
 * @lastChangeBy         $wanyang$       
 * @lastmodified         $2014-09-23$    
 * @copyright            Copyright (c) 2014, www.ganji.com
 */                           

class ShareDefaultListPageTest extends Testcase_PTest
{
    protected function setUp()
    {
    }

    /**
     * @dataProvider TDKProvider
     */
    public function testGetMetaInfo($aParams, $aWant)
    {
        $obj = Gj_LayerProxy::getProxy("Service_Data_SEO_TDK");
        $ret = $obj->getMetaInfo($aParams);
        $this->assertArrayHasKey("meta", $ret, $ret["errormsg"]);
        $this->assertEquals($ret["meta"]["title"], $aWant["meta"]["title"]);
        $this->assertEquals($ret["meta"]["description"], $aWant["meta"]["description"]);
        $this->assertEquals($ret["meta"]["keywords"], $aWant["meta"]["keywords"]);
    }

    public function TDKProvider()
    {
        return array(

            /* 城市无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'city' => 'bj',
                    "postCount" => 2,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京合租房|北京合租房信息网】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 城市有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京800以下1居室房屋合租信息|北京合租房网】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'

                        ),
                    ),
                ),


            /* 区域无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "district_street" => "chaoyang",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【朝阳合租房|北京朝阳合租房信息网】-北京赶集网',
                        'keywords' => '朝阳合租房,朝阳合租房网',
                        'description' => '今日2条，朝阳合租房、北京朝阳单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        
                        ),
                    ),
                ),

            /* 区域有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "chaoyang",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【朝阳合租房网_朝阳800以下1居室房屋合租信息】-北京赶集网',
                        'keywords' => '朝阳合租房,朝阳合租房网',
                        'description' => '今日2条，朝阳合租房、北京朝阳单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 合租床位有参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "chaoyang",
                    "share_mode" => 2,
                    "share_mode2Title" => "合租床位",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京床位出租_北京朝阳800以下1居室合租床位床位出租信息】-北京赶集网',
                        'keywords' => '朝阳合租房,朝阳合租房网',
                        'description' => '今日2条 - 朝阳合租床位、北京朝阳床位出租信息。【赶集网】为您提供精装修、家电齐全、临近地铁、市政供暖的北京床位出租信息，查找普通住宅、公寓合租床位，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 合租床位无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "share_mode" => 2,
                    "share_mode2Title" => "合租床位",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京合租床位|北京合租床位网】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 - 北京合租床位、北京床位出租信息。【赶集网】为您提供精装修、家电齐全、临近地铁、市政供暖的北京床位出租信息，查找普通住宅、公寓合租床位，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            
            /* 街道有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "anzhen",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【安贞合租房网_北京安贞800以下1居室房屋合租信息】-北京赶集网',
                        'keywords' => '安贞合租房,安贞合租房网',
                        'description' => '今日2条，安贞合租房、北京安贞单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 个人房源无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "agent" => 1,
                    "agent1Title" => "认证个人"
                    
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京房东直租合租房_认证个人合租房源信息】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 北京个人房源无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "agent" => 1,
                    "agent1Title" => "100%个人"
                    
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京房东直租合租房_100%个人合租房源信息】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 北京经纪人无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "agent" => 2,
                    "agent1Title" => "经纪人"
                    
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京合租房|北京经纪人合租信息】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 北京放心房无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    "postCount" => 2,
                    'city' => 'bj',
                    "agent" => 4,
                    "agent1Title" => "_赶集网放心房"
                    
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京合租房_北京合租房信息网_赶集网放心房】-北京赶集网',
                        'keywords' => '北京合租房,北京合租房网',
                        'description' => '今日2条 北京合租房、北京单间出租信息。【赶集网】为您提供主卧、次卧、隔断间、床位等合租房子信息，查找精装修、家电齐全、临近地铁、市政供暖的北京单间出租信息，请到赶集北京房屋合租频道。'
                        ),
                    ),
                ),

            /* 地铁无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    "postCount" => 3,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁沿线合租单间租房信息】-北京赶集网',
                        'keywords' => '北京地铁沿线合租房,北京地铁单间出租,北京地铁合租房信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京地铁附近合租房屋出租信息，房源更新快、信息全。查找地铁附近个人合租房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京合租房网。',
                        ),
                    ),
                ),

            
            /* 地铁有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    "postCount" => 3,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁沿线800以下1居室合租房信息】-北京赶集网',
                        'keywords' => '北京地铁沿线合租房,北京地铁单间出租,北京地铁合租房信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京地铁附近合租房屋出租信息，房源更新快、信息全。查找地铁附近个人合租房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京合租房网。',
                        ),
                    ),
                ),

            /* 线路有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    'subway_line' => '35',
                    "postCount" => 3,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁13号线沿线800以下1居室一层合租房信息】-北京赶集网',
                        'keywords' => '地铁13号线沿线合租房,地铁13号线单间出租,地铁13号线合租房信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京地铁13号线沿线合租房屋出租信息，房源更新快、信息全。查找地铁附近个人合租房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京13号线合租房网。',

                        ),
                    ),
                ),

            /* 站点有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    'subway_line' => '35',
                    "postCount" => 3,
                    "station" => "6",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京西二旗地铁附近800以下1居室一层合租房信息】-北京赶集网',
                        'keywords' => '西二旗地铁附近合租房,西二旗地铁附近单间出租,西二旗地铁附近合租房信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京西二旗地铁附近合租房屋出租信息，房源更新快、信息全。查找西二旗地铁附近个人合租房屋出租房源、经纪人房源、西二旗地铁附近单间、合租，请到赶集北京合租房网。',

                        ),
                    ),
                ),
            
            
            /* 公交有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "postCount" => 3,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交附近800以下1居室一层合租单间租房_公交周边合租单间出租信息】-北京赶集网',
                        'keywords' => '北京公交附近合租房,北京公交周边单间出租,北京公交附近房屋出租信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京公交附近合租房屋出租信息，房源更新快、信息全。查找公交附近个人合租房屋出租房源、经纪人房源、公交附近单间、合租，请到赶集北京合租房网。',
                        ),
                    ),
                ),

            /* 公交无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "postCount" => 3,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交附近合租单间租房|北京公交周边合租单间出租信息】-北京赶集网',
                        'keywords' => '北京公交附近合租房,北京公交周边单间出租,北京公交附近房屋出租信息',
                        'description' => '赶集网北京合租房网，为您提供最新3条北京公交附近合租房屋出租信息，房源更新快、信息全。查找公交附近个人合租房屋出租房源、经纪人房源、公交附近单间、合租，请到赶集北京合租房网。',
                        ),
                    ),
                ),

            /* 大学无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'college',
                    'city' => 'bj',
                    "postCount" => 3,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【大学附近合租房_大学附近单间出租信息】-北京赶集网',
                        'keywords' => '大学附近合租房,大学周边单间出租,大学附单间出租信息',
                        'description' => '赶集网北京租房频道是最专业的北京合租房网，为您提供大量大学周边北京单间出租信息，查找大学附近北京合租房信息，个人房屋出租房源，请到赶集北京合租房网',
                        ),
                    ),
                ),

            /* 大学有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'college',
                    'city' => 'bj',
                    "postCount" => 3,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【大学附近800以下1居室一层合租房_单间出租信息】-北京赶集网',
                        'keywords' => '大学附近合租房,大学周边单间出租,大学附单间出租信息',
                        'description' => '赶集网北京租房频道是最专业的北京合租房网，为您提供大量大学周边北京单间出租信息，查找大学附近北京合租房信息，个人房屋出租房源，请到赶集北京合租房网',
                        ),
                    ),
                ),

            /* 大学学校有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang3',
                    'majorPath'=> 'college',
                    'city' => 'bj',
                    "college" => "bjdx",
                    "postCount" => 3,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京大学附近800以下1居室一层合租房_单间出租信息】-北京赶集网',
                        'keywords' => '北京大学附近合租房,北京大学周边单间出租,北京大学附单间出租信息',
                        'description' => '赶集网北京租房频道是最专业的北京合租房网，为您提供大量大学周边北京单间出租信息，查找大学附近北京合租房信息，个人房屋出租房源，请到赶集北京合租房网',
                        ),
                    ),
                ),
            );
    }

}
