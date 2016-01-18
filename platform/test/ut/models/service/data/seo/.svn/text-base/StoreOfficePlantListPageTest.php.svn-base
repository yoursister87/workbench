<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
define("WEBSEARCH","");
/**
 * @package
 * @subpackage
 * @brief                $$
 * fang6, 7, 8单测 
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$ 
 * @file                 $StoreOfficePlantListPageTest.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-18$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class StoreOfficePlantListPageTest  extends Testcase_PTest
{
    /**
     * @dataProvider TDKProvider
     */
    public function testGetMetaInfo($aParams,$aWant){
        $obj = Gj_LayerProxy::getProxy("Service_Data_SEO_TDK");
        $ax =array('agent'=>3, 'page'=>1, 'pageType'=>'list', 'postCount'=>100, 'city'=> 'bj');
        $ax= array_replace($ax, $aParams);
        $ret = $obj->getMetaInfo($ax);
        $this->assertArrayHasKey("meta",$ret,$ret["errormsg"]);
        $this->assertEquals($ret["meta"]["title"],$aWant["meta"]["title"]);
        $this->assertEquals($ret["meta"]["description"],$aWant["meta"]["description"]);
        $this->assertEquals($ret["meta"]["keywords"],$aWant["meta"]["keywords"]);
    }

    public function TDKProvider(){
        return array(
            /*------------------------------- start fang6-------------------------------------------------*/
            //default: district
            array(
                array('category' =>'fang6', 'district_street' =>'chongwen', 'house_type' =>'百货/购物中心', 'deal_type1Title' => '出租'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【崇文百货/购物中心商铺出租/招租|崇文门面出租】-北京赶集网',
                        'keywords' => '崇文商铺出租，崇文出租网',
                        'description' => '赶集网崇文商铺出租信息频道，提供北京崇文门面房门脸房出租信息的免费发布查询，我们对发布的崇文商铺、门面房门脸房出租信息进行严格审核，寻找最新、真实的崇文商铺门面门脸房出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
            //city
            array(
                array('category' =>'fang6', 'house_type' =>'百货/购物中心', 'deal_type1Title' => '出租'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京百货/购物中心商铺出租/招租|北京门面出租】-北京赶集网',
                        'keywords' => '北京商铺出租，北京出租网',
                        'description' => '赶集网北京商铺出租信息频道，提供北京门面房门脸房出租信息的免费发布查询，我们对发布的北京商铺、门面房门脸房出租信息进行严格审核，寻找最新、真实的北京商铺门面门脸房出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
            array(
                array('category' =>'fang6', 'deal_type1Title' => '出租'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京门面出租|北京商铺出租信息】-北京赶集网',
                        'keywords' => '北京门面出租,北京商铺出租,北京商铺转让,北京商铺网',
                        'description' => '赶集网北京商铺出租信息发布平台，对发布的北京商铺出租信息进行严格审核，寻找海量、真实的北京商铺出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //shangquan: district
           array(
                array('category' =>'fang6', 'majorPath' =>'shangquan', 'list_type' =>'shangquan', 'business_district' =>'上地', 'trade' =>'洗浴健身', 'price'=>'8000-1.2万'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【上地8000-1.2万元/月洗浴健身商铺出租信息_上地门面门脸房出租】-北京赶集网',
                        'keywords' => '上地洗浴健身商铺出租，上地洗浴健身商铺出租网',
                        'description' => '赶集网上地商铺出租信息频道，提供上地门面房门脸房出租信息的免费发布查询，我们对发布的上地商铺、门面房门脸房出租信息进行严格审核，寻找最新、真实的上地商铺门面门脸房出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //city
           array(
                array('category' =>'fang6', 'majorPath' =>'shangquan', 'list_type' =>'shangquan', 'trade' =>'洗浴健身', 'price'=>'8000-1.2万'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京8000-1.2万元/月洗浴健身商铺出租/招租|北京洗浴健身门面出租】-北京赶集网',
                        'keywords' => '北京洗浴健身商铺出租，北京洗浴健身出租网',
                        'description' => '赶集网北京商铺出租信息频道，提供北京门面房门脸房出租信息的免费发布查询，我们对发布的北京商铺、门面房门脸房出租信息进行严格审核，寻找最新、真实的北京商铺门面门脸房出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
          //loupan
          array(
                array('category' =>'fang6', 'majorPath' =>'loupan',  'district_street' =>'changxindian'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【长辛店商铺大全|长辛店商铺信息|长辛店商铺网】-北京赶集网',
                        'keywords' => '长辛店商铺大全，长辛店商铺信息，长辛店商铺网',
                        'description' => '长辛店赶集网商铺大全频道，为您提供大量长辛店商铺信息，是您对比查询商铺物业费用、租金价格、销售价格等商铺信息的最佳商铺网站，帮助您快速找到理想的北京商铺。'
                        ),
                    ),
                ),
            /*------------------------------- end fang6-------------------------------------------------*/
            /*------------------------------- start fang7-------------------------------------------------*/
            //default: district
            array(
                array('category' =>'fang7', 'district_street' =>'chongwen', 'house_type' =>'百货/购物中心', 'deal_type1Title' => '出售'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【崇文百货/购物中心商铺转让/出售信息|崇文门面转让】-北京赶集网',
                        'keywords' => '崇文商铺出售，崇文出售网',
                        'description' => '赶集网崇文商铺出售信息频道，提供北京崇文门面房门脸房出售信息的免费发布查询，我们对发布的崇文商铺、门面房门脸房出售信息进行严格审核，寻找最新、真实的崇文商铺门面门脸房出售信息，请到北京赶集网。'
                        ),
                    ),
                ),
            //city
            array(
                array('category' =>'fang7', 'house_type' =>'百货/购物中心', 'deal_type1Title' => '出售'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京百货/购物中心商铺转让/出售信息|北京门面转让】-北京赶集网',
                        'keywords' => '北京商铺出售，北京出售网',
                        'description' => '赶集网北京商铺出售信息频道，提供北京门面房门脸房出售信息的免费发布查询，我们对发布的北京商铺、门面房门脸房出售信息进行严格审核，寻找最新、真实的北京商铺门面门脸房出售信息，请到北京赶集网。'
                        ),
                    ),
                ),
            array(
                array('category' =>'fang7', 'deal_type1Title' => '出售'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京商铺出售|北京门面出售信息】-北京赶集网',
                        'keywords' => '北京商铺出售,北京门面出售,北京店面出售,北京出售网',
                        'description' => '赶集网北京商铺出售信息发布平台，对发布的北京商铺出售信息进行严格审核，寻找海量、真实的北京商铺出售信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //shangquan: district
           array(
                array('category' =>'fang7', 'majorPath' =>'shangquan', 'list_type' =>'shangquan', 'business_district' =>'上地', 'trade' =>'洗浴健身', 'price'=>'1-3'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【上地1-3万元洗浴健身商铺出售信息_门面门脸房出售】-北京赶集网',
                        'keywords' => '上地洗浴健身商铺出售，上地洗浴健身商铺出售网',
                        'description' => '赶集网上地商铺出售信息频道，提供上地门面房门脸房出售信息的免费发布查询，我们对发布的上地商铺、门面房门脸房出售信息进行严格审核，寻找最新、真实的上地商铺门面门脸房出售信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //city
           array(
                array('category' =>'fang7', 'majorPath' =>'shangquan', 'list_type' =>'shangquan', 'trade' =>'洗浴健身', 'price'=>'1-3', 'deal_type1Title' =>  '出售'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京1-3万元洗浴健身商铺转让/出售信息|北京洗浴健身门面转让】-北京赶集网',
                        'keywords' => '北京洗浴健身商铺出售，北京洗浴健身出售网',
                        'description' => '赶集网北京商铺出售信息频道，提供北京门面房门脸房出售信息的免费发布查询，我们对发布的北京商铺、门面房门脸房出售信息进行严格审核，寻找最新、真实的北京商铺门面门脸房出售信息，请到北京赶集网。'
                        ),
                    ),
                ),
            //fang7的loupan和fang6相同
            /*------------------------------- end fang7-------------------------------------------------*/
            /*------------------------------- start fang8-------------------------------------------------*/
           //default:city
           array(
                array('category' =>'fang8', 'district_street' =>'xicheng', 'house_type' =>'商务公寓', 'price' =>'1-34元/月', 'area' =>'2-34㎡' ,'deal_type1Title' => '出租'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【西城2-34平方米1-34元/月商务公寓出租网】-北京赶集网',
                        'keywords' => '西城商务公寓出租，西城商务公寓出租网',
                        'description' => '赶集网西城商务公寓出租信息发布平台，对发布的西城商务公寓出租信息进行严格审核，寻找海量、真实的西城商务公寓出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //shangqu: city
           array(
                array('category' =>'fang8',  'majorPath' =>'shangquan',  'list_type' =>'shangquan', 'business_district' =>'中关村', 'house_type' =>'商务公寓', 'price' =>'1-34元/月', 'area' =>'2-34㎡'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京中关村热门商圈2-34平方米1-34元/月商务公寓出租网】-北京赶集网',
                        'keywords' => '北京中关村热门商圈商务公寓出租，北京中关村热门商圈商务公寓出租网',
                        'description' => '赶集网北京中关村热门商圈商务公寓出租信息发布平台，对发布的北京中关村热门商圈商务公寓出租信息进行严格审核，寻找海量、真实的北京中关村热门商圈商务公寓出租信息，请到北京赶集网。'
                        ),
                    ),
                ),
           //loupan: city
           array(
                array('category' =>'fang8',  'majorPath' =>'loupan',  'district_street' =>'shijingshan' ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【石景山写字楼大全|石景山写字楼信息|石景山写字楼网】-北京赶集网',
                        'keywords' => '石景山写字楼大全，石景山写字楼信息，石景山写字楼网',
                        'description' => '石景山赶集网写字楼大全频道，为您提供大量石景山写字楼信息，是您对比查询写字楼物业费用、租金价格、销售价格等写字楼信息的最佳写字楼网站，帮助您快速找到理想的北京写字楼。'
                        ),
                    ),
                ),
            /*------------------------------- end fang8-------------------------------------------------*/

         );
    }
}
