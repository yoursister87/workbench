<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
define("WEBSEARCH","");
class SellDefaultListPageTest extends Testcase_PTest
{
    protected function setUp(){

        Gj_Layerproxy::$is_ut = true;
    }
    public static function setUpBeforeClass()
    {
//        $_SERVER["HTTP_HOST"] = "bj.ganji.com";        
    }

    /**
     *@dataProvider TDKProvider
     */ 
    public function testGetMetaInfo($aParams,$aWant){
        $obj = Gj_LayerProxy::getProxy("Service_Data_SEO_TDK");
        $ret = $obj->getMetaInfo($aParams);
        $this->assertArrayHasKey("meta",$ret,$ret["errormsg"]);
        $this->assertEquals($ret["meta"]["title"],$aWant["meta"]["title"]);
        $this->assertEquals($ret["meta"]["description"],$aWant["meta"]["description"]);
        $this->assertEquals($ret["meta"]["keywords"],$aWant["meta"]["keywords"]);
    }

    public function TDKProvider(){
        return array(

            /* 城市无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'city' => 'bj',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京二手房买卖|北京二手房网】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。'
                        ),
                    ),
                ),

            
            /* 城市有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京800以下1居室二手房出售|北京二手房网】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。'
                        ),
                    ),
                ),
            
            
            /* 区域无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    "district_street" => "chaoyang",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳二手房|朝阳二手房信息网】-北京赶集网',
                        'keywords' => '朝阳二手房,朝阳二手房网',
                        'description' => '赶集网朝阳二手房频道是最专业的朝阳二手房网,为您提供大量的朝阳房屋出售信息,查找朝阳二手房信息,个人房屋出售二手房源,请到赶集朝阳二手房网。'
                        ),
                    ),
                ),

            /* 区域有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "chaoyang",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳800以下1居室二手房出售|朝阳二手房网】-北京赶集网',
                        'keywords' => '朝阳二手房,朝阳二手房网',
                        'description' => '赶集网朝阳二手房频道是最专业的朝阳二手房网,为您提供大量的朝阳房屋出售信息,查找朝阳二手房信息,个人房屋出售二手房源,请到赶集朝阳二手房网。'
                        ),
                    ),
                ),


            /* 街道无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'city' => 'bj',
                    "district_street" => "anzhen",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳安贞二手房|安贞二手房信息网】-北京赶集网',
                        'keywords' => '安贞二手房,安贞二手房网',
                        'description' => '赶集网安贞二手房频道是最专业的安贞二手房网,为您提供大量的安贞房屋出售信息,查找安贞二手房信息,个人房屋出售二手房源,请到赶集安贞二手房网。'
                        ),
                    ),
                ),


            /* 街道有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "anzhen",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳安贞800以下1居室二手房出售|安贞二手房网】-北京赶集网',
                        'keywords' => '安贞二手房,安贞二手房网',
                        'description' => '赶集网安贞二手房频道是最专业的安贞二手房网,为您提供大量的安贞房屋出售信息,查找安贞二手房信息,个人房屋出售二手房源,请到赶集安贞二手房网。'
                        ),
                    ),
                ),

            
            /* 个人房源无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'city' => 'bj',
                    "agent" => 1,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京二手房网-北京房屋出售|北京个人房屋出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。'
                        ),
                    ),
                ),


            /* 个人房源有街道参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "anzhen",
                    "agent" => 1,
                    "agent1Title" => "100%个人",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳安贞800以下1居室100%个人二手房出售|安贞二手房网】-北京赶集网',
                        'keywords' => '安贞二手房,安贞二手房网',
                        'description' => '赶集网安贞二手房频道是最专业的安贞二手房网,为您提供大量的安贞房屋出售信息,查找安贞二手房信息,个人房屋出售二手房源,请到赶集安贞二手房网。'
                        ),
                    ),
                ),


            /* 经纪人无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'city' => 'bj',
                    'agent'=> 2
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京房产中介二手房信息-北京房产中介房屋出售】-赶集网 北京',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        'keywords' => '北京二手房,北京二手房网'
                        ),
                    ),
                ),

            /* 经纪人有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    "postCount" => 3,
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "district_street" => "chaoyang",
                    "agent" =>2,
                    "agent2Title" => "经纪人",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京朝阳房产中介二手房信息-朝阳房产中介房屋出售】-赶集网 北京',
                        'keywords' => '朝阳二手房,朝阳二手房网',
                        'description' => '赶集网朝阳二手房频道是最专业的朝阳二手房网,为您提供大量的朝阳房屋出售信息,查找朝阳二手房信息,个人房屋出售二手房源,请到赶集朝阳二手房网。'
                        ),
                    ),
                ),


            /* 地铁无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【地铁附近二手房|北京地铁附近房屋出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 地铁有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近800以下1居室一层二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),
            

            /* 线路无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'subway_line' => '35',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【地铁13号线沿线二手房|北京地铁13号线沿线房屋出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 线路有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    'subway_line' => '35',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁13号线沿线800以下1居室一层二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 站点无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    "subway_line" => "35",
                    'station' => '6',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【西二旗地铁附近二手房|北京西二旗地铁附近房屋出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 站点有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    'subway_line' => '35',
                    "station" => "6",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(

                        'title' => '【西二旗地铁附近800以下1居室一层二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 个人房源无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    "agent" => 1,
                    "agentTitle" => "个人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            
            /* 个人房源有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "agent" => 1,
                    "agent1Title" => "个人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近800以下1居室一层个人房源二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 经纪人无参 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    "agent" => 2,
                    "agentTitle" => "经纪人",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            
            /* 经纪人有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'subway',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "agent" => 2,
                    "agent1Title" => "经纪人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近800以下1居室一层经纪人房源二手房信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),


            /* 公交有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交周边800以下1居室一层二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 公交无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交附近二手房|公交周边二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 线路有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京101电车(百万庄西口--红庙路口东)公交附近800以下1居室一层二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 线路无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【101电车(百万庄西口--红庙路口东)公交沿线二手房|北京101电车(百万庄西口--红庙路口东)公交沿线二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),


            /* 站点无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    "station" => 1,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【百万庄西口公交附近二手房|北京百万庄西口公交周边二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),


            /* 站点有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "station" =>1,
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京百万庄西口公交附近800以下1居室一层二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 个人房源有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "station" =>1,
                    "agent" =>1,
                    "agent1Title"=>"个人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京百万庄西口公交附近800以下1居室一层个人房源二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 个人房源无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "agent" =>1,
                    "agent1Title"=>"个人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交周边个人房源二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),


            /* 个人房源有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "bus_line" => 84,
                    'huxing_shi' => '1室',
                    "price" => "800以下",
                    "ceng" => "一层",
                    "station" =>1,
                    "agent" =>2,
                    "agent2Title"=>"经纪人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京百万庄西口公交附近800以下1居室一层经纪人房源二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 个人房源无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'bus',
                    'city' => 'bj',
                    "agent" =>2,
                    "agent2Title"=>"经纪人房源",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交周边经纪人房源二手房出售信息】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '北京赶集网公交线路附近二手房网为您提供全面海量优质公交线路附近二手房出售信息,包括个人房源和中介房源。 您可以免费查看和发布北京公交附近二手房买卖信息。',
                        ),
                    ),
                ),

            /* 首付找房无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京首付找二手房|北京首付找二手房网】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            
            /* 首付找房有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "ceng" => "一层",
                    "downpayment" => "30万以下",
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京1居室30万以下一层首付找二手房|北京首付找二手房网】-北京赶集网',
                        'keywords' => '北京二手房,北京二手房网',
                        'description' => '赶集网北京二手房频道是最专业的北京二手房网,为您提供大量的北京房屋出售信息,查找北京二手房信息,个人房屋出售二手房源,请到赶集北京二手房网。',
                        ),
                    ),
                ),

            /* 首付找房区域有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "ceng" => "一层",
                    "downpayment" => "30万以下",
                    "district_street" => "haidian"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀1居室30万以下一层首付找二手房|海淀首付找二手房网】-北京赶集网',
                        'keywords' => '海淀二手房,海淀二手房网',
                        'description' => '赶集网海淀二手房频道是最专业的海淀二手房网,为您提供大量的海淀房屋出售信息,查找海淀二手房信息,个人房屋出售二手房源,请到赶集海淀二手房网。',
                        ),
                    ),
                ),

            /* 首付找房区域无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    "district_street" => "haidian"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀首付找二手房|海淀首付找二手房网】-北京赶集网',
                        'keywords' => '海淀二手房,海淀二手房网',
                        'description' => '赶集网海淀二手房频道是最专业的海淀二手房网,为您提供大量的海淀房屋出售信息,查找海淀二手房信息,个人房屋出售二手房源,请到赶集海淀二手房网。',
                        ),
                    ),
                ),

            /* 首付找房街道有参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    'huxing_shi' => '1室',
                    "ceng" => "一层",
                    "downpayment" => "30万以下",
                    "district_street" => "xierqi"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀西二旗1居室30万以下一层首付找二手房|西二旗首付找二手房网】-北京赶集网',
                        'keywords' => '西二旗二手房,西二旗二手房网',
                        'description' => '赶集网西二旗二手房频道是最专业的西二旗二手房网,为您提供大量的西二旗房屋出售信息,查找西二旗二手房信息,个人房屋出售二手房源,请到赶集西二旗二手房网。',
                        ),
                    ),
                ),

            
            /* 首付找房街道无参数 */
            array(
                array(
                    'pageType' => 'list',
                    'category' => 'fang5',
                    'majorPath'=> 'payment',
                    'city' => 'bj',
                    "district_street" => "xierqi"
                    ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀西二旗首付找二手房|西二旗首付找二手房网】-北京赶集网',
                        'keywords' => '西二旗二手房,西二旗二手房网',
                        'description' => '赶集网西二旗二手房频道是最专业的西二旗二手房网,为您提供大量的西二旗房屋出售信息,查找西二旗二手房信息,个人房屋出售二手房源,请到赶集西二旗二手房网。',
                        ),
                    ),
                ),


            );
    }

   

}
