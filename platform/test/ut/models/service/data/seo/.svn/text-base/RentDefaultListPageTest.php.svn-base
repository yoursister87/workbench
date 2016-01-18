<?php
/**
 *
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$ 
 * fang1出租房(区域/地铁/公交/大学)单测
 *
 */
ini_set("error_reporting",E_ALL ^ E_NOTICE);
define("WEBSEARCH","");
class RentDefaultListPageTest extends Testcase_PTest
{
    protected function setUp(){
    }

    /**
     * @dataProvider TDKProvider
     */ 
    public function testGetMetaInfo($aParams,$aWant){
        $obj = Gj_LayerProxy::getProxy("Service_Data_SEO_TDK");
        $ax =array('category'=>'fang1', 'agent'=>3, 'page'=>1, 'source'=>'PC', 'pageType'=>'list', 'postCount'=>100, 'city'=> 'bj'); 
        $ax= array_replace($ax, $aParams);
        $ret = $obj->getMetaInfo($ax);
        $this->assertArrayHasKey("meta",$ret,$ret["errormsg"]);
        $this->assertEquals($ret["meta"]["title"],$aWant["meta"]["title"]);
        $this->assertEquals($ret["meta"]["description"],$aWant["meta"]["description"]);
        $this->assertEquals($ret["meta"]["keywords"],$aWant["meta"]["keywords"]);
    }

    public function TDKProvider(){
        return array(
            /*------------------------------- start default-------------------------------------------------*/
            //keyword
            array(
                array('keyword' =>  'swh'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【swh信息】-北京赶集网',
                        'keywords' => '北京租房,北京房屋出租',
                        'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供最新100条北京房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。'
                        ),
                    ),
                ),
            //a4
            array(
                array('agent4Title' => '_赶集网放心房', 'agent' => 4),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京租房子|北京租房信息网_赶集网放心房】-北京赶集网',
                        'keywords' => '北京租房,北京房屋出租',
                        'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供最新100条北京房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。'
                        ),
                    ),
                ),
            //a2
            array(
                array('agent2Title' => '经纪人', 'agent' => 2, 'district_street' => 'haidian'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀房产中介租房信息-北京海淀房产中介房屋出租】-赶集网 北京',
                        'keywords' => '海淀租房,北京海淀房屋出租',
                        'description' => '赶集网海淀出租频道是最专业的北京海淀租房网，为您提供最新【100条】海淀房屋出租信息，查找海淀租房信息，海淀个人房屋出租房源，请到赶集北京海淀租房网。'
                        ),
                    ),
                ),
            //a1
            array(
                array('agent1Title' => '100%个人', 'agent' => 1 ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【100%个人房源网|北京房东房源直租】北京个人房源出租信息 - 北京赶集网',
                        'keywords' => '北京个人房源,北京房东房源,北京个人房源出租',
                        'description' => '个人房源赶集网北京频道，免费提供最新【100条北京房东房源直租】信息，网站包含精装修、家电齐全、押一付一、单身公寓、邻近地铁、市政供暖等各类个人房源出租信息，查找个人房源出租信息，请到北京赶集个人房源网。'
                        ),
                    ),
                ),
            array(
                array('agent1Title' => '100%个人', 'agent' => 1, 'district_street' =>'haidian'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀100%个人房源网_海淀房东房源直租信息】-北京赶集网',
                        'keywords' => '海淀个人房源,海淀房东房源,海淀个人房源出租',
                        'description' => '个人房源赶集网海淀频道，免费提供最新【100条北京海淀房东房源直租】信息，网站包含精装修、家电齐全、押一付一、单身公寓、邻近地铁、市政供暖等各类个人房源出租信息，查找个人房源出租信息，上北京赶集海淀个人房源网。'),
                    ),
                ),
            array(
                array('agent1Title' => '认证个人', 'agent' => 1, 'city' => 'sh'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【上海租房网-上海房屋出租|上海认证个人房屋出租信息】-上海赶集网',
                        'keywords' => '上海个人房源,上海房东房源,上海个人房源出租',
                        'description' => '个人房源赶集网上海频道，免费提供最新【100条上海房东房源直租】信息，网站包含精装修、家电齐全、押一付一、单身公寓、邻近地铁、市政供暖等各类个人房源出租信息，查找个人房源出租信息，请到上海赶集个人房源网。'
                        ),
                    ),
                ),
            array(
                array('agent1Title' => '认证个人', 'agent' => 1, 'city' => 'sh', 'district_street' =>'minhang'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【上海闵行认证个人房屋出租信息|上海闵行租房】-上海赶集网',
                        'keywords' => '闵行个人房源,闵行房东房源,闵行个人房源出租',
                        'description' => '个人房源赶集网闵行频道，免费提供最新【100条上海闵行房东房源直租】信息，网站包含精装修、家电齐全、押一付一、单身公寓、邻近地铁、市政供暖等各类个人房源出租信息，查找个人房源出租信息，上上海赶集闵行个人房源网。'
                        ),
                    ),
                ),
            //huxing_shi
            array(
                array('huxing_shi' =>  '1'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【一室一厅出租|北京一室一厅房屋出租信息】-  北京赶集网',
                        'keywords' => '一室一厅出租,北京一室一厅房屋出租',
                        'description' => '赶集网北京一室一厅出租频道是最专业的北京租房网，为您提供最新【100条】北京一室一厅房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。'
                        ),
                    ),
                ),
            //district
            array(
                array('huxing_shi' =>  '1', 'district_street' => 'haidian'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京海淀一室一厅房屋出租信息|北京海淀租房】-北京赶集网',
                        'keywords' => '海淀一室一厅出租,北京海淀一室一厅房屋出租',
                        'description' => '赶集网北京一室一厅出租频道是最专业的北京租房网，为您提供最新【100条】北京一室一厅房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。'
                        ),
                    ),
                ),
            //city
            array(
                array(),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京租房子|北京租房网|北京房屋出租信息】-北京赶集网',
                        'keywords' => '租房,北京租房,北京租房网,北京房屋出租,北京租房信息,北京赶集网',
                        'description' => '赶集网北京租房频道是最专业的北京租房网，为您提供最新100条北京房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。',
                        ),
                    ),
                ),
            array(
                array('huxing_shi' =>'1'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【一室一厅出租|北京一室一厅房屋出租信息】-  北京赶集网',
                        'keywords' => '一室一厅出租,北京一室一厅房屋出租',
                        'description' => '赶集网北京一室一厅出租频道是最专业的北京租房网，为您提供最新【100条】北京一室一厅房屋出租信息，查找北京租房信息，北京个人房屋出租房源，请到赶集北京租房网。'
                        ),
                    ),
                ),
           /*-------------------------------end default-------------------------------------------------*/

           /*-------------------------------start subway-------------------------------------------------*/
           //station
           array(
                array('list_type' => 'ditie','majorPath' => 'subway', 'subway_line' => '2055', 'walk' => '30分钟以内', 'station' =>  '6'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京西土城附近30分钟以内租房信息】-北京赶集网',
                        'keywords' => '西土城附近租房,西土城附近出租信息',
                        'description' => '赶集网北京租房网，为您提供最新100条北京西土城附近房屋出租信息，房源更新快、信息全。查找西土城附近个人房屋出租房源、经纪人房源、西土城附近单间、合租，请到赶集北京租房网。'
                        ),
                    ),
                ),
           //subway_line
           array(
                array('list_type' => 'ditie','majorPath' => 'subway', 'subway_line' => '2055', 'walk' => '30分钟以内' ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁10号线(M10)(区间)沿线30分钟以内租房信息】-北京赶集网',
                        'keywords' => '地铁10号线(M10)(区间)租房,地铁10号线(M10)(区间)出租信息',
                        'description' => '赶集网北京租房网，为您提供最新100条北京地铁10号线(M10)(区间)沿线房屋出租信息，房源更新快、信息全。查找地铁10号线(M10)(区间)个人房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京租房网。'
                        ),
                    ),
                ),
           //ditie
           array(
                array('list_type' => 'ditie','majorPath' => 'subway'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京地铁附近租房|北京地铁附近房屋出租信息】-北京赶集网',
                        'keywords' => '地铁附近租房,地铁附近出租信息',
                        'description' => '赶集网北京租房网，为您提供最新100条北京地铁附近房屋出租信息，房源更新快、信息全。查找地铁附近个人房屋出租房源、经纪人房源、地铁附近单间、合租，请到赶集北京租房网。'
                        ),
                    ),
                ),
           /*-------------------------------end subway-------------------------------------------------*/

           /*-------------------------------start bus-------------------------------------------------*/
           array(
                array('list_type' => 'bus','majorPath' => 'bus','chaoxiang' =>  '西向', 'bus_line' =>  '88','price' => '1500-2000元'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京公交102电车(动物园--北京南站北广场)沿线租房|公交102电车(动物园--北京南站北广场)附近1500-2000元朝西房屋出租信息】- 北京赶集网',
                        'keywords' => '102电车(动物园--北京南站北广场)租房,102电车(动物园--北京南站北广场)租房出租信息',
                        'description' => '赶集网北京租房网，为您提供最新100条北京公交102电车(动物园--北京南站北广场)附近房屋出租信息，房源更新快、信息全。查找公交附近个人房屋出租房源、经纪人房源、附近单间、合租，请到赶集北京租房网'
                        ),
                    ),
                ),
           /*-------------------------------end bus-------------------------------------------------*/
           /*-------------------------------start college-------------------------------------------------*/
           //college
           array(
                array('list_type' => 'daxue','majorPath' => 'college', 'college' =>  'bjdx', 'walk' =>  '20分钟以内'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京大学附近20分钟以内租房信息】-北京赶集网',
                        'keywords' => '北京租房,北京房屋出租',
                        'description' => ' 赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网'
                        ),
                    ),
                ),
           //daxue
           array(
                array('list_type' => 'daxue','majorPath' => 'college', 'walk' =>  '10分钟以内'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【大学附近10分钟以内租房信息】-北京赶集网',
                        'keywords' => '北京租房,北京房屋出租',
                        'description' => ' 赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网'
                        ),
                    ),
                ),
           array(
                array('list_type' => 'daxue','majorPath' => 'college'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【大学附近租房_大学附近房屋出租信息】-北京赶集网',
                        'keywords' => '大学附近租房,大学周边房屋出租,大学附近租房信息',
                        'description' => ' 赶集网北京租房频道是最专业的北京租房网，为您提供大量的北京房屋出租信息，查找北京租房信息，个人房屋出租房源，请到赶集北京租房网'
                        ),
                    ),
                ),
           /*-------------------------------end college-------------------------------------------------*/
             
          );
    }

}
