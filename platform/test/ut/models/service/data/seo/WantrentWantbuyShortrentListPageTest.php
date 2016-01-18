<?php
ini_set("error_reporting",E_ALL ^ E_NOTICE);
define("WEBSEARCH","");
/**                                  
 * @package                          
 * @subpackage                       
 * @brief                $$              
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$
 * @file                 $WantrentWantbuyShortrentListPageTest.class.php$
 * @lastChangeBy         $wanyang$       
 * @lastmodified         $2014-09-23$    
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WantrentWantbuyShortrentListPageTest  extends Testcase_PTest
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
            /*------------------------------- start fang2-------------------------------------------------*/
            //district
            array(
                array('category' =>'fang2', 'district_street' =>'beitaipingzhuang', 'huxing_shi' =>'5室', 'price' =>  '1000-1500元' ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北太平庄5居室1000-1500元求租房|北京北太平庄房屋求租信息】-北京赶集网',
                        'keywords' => '北太平庄求租房，北京北太平庄求租房网，5居室1000-1500元求租信息',
                        'description' => '赶集网北京北太平庄房屋求租信息发布平台，对发布的北太平庄房屋求租信息进行严格审核，寻找海量、真实的北太平庄房屋求租信息，请到北京赶集网。',
                        ),
                    ),
                ),
            //city
            array(
                array('category' =>'fang2',  'huxing_shi' =>'5室', 'price' =>  '1000-1500元' ),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【北京5居室1000-1500元求租房|北京房屋求租信息】-北京赶集网',
                        'keywords' => '北京求租房，北京求租房网，5居室1000-1500元求租信息',
                        'description' => '赶集网北京房屋求租信息发布平台，对发布的北京房屋求租信息进行严格审核，寻找海量、真实的北京房屋求租信息，请到北京赶集网。'
                        ),
                    ),
                ),
            /*------------------------------- end fang2-------------------------------------------------*/
            /*------------------------------- start fang4-------------------------------------------------*/
            array(
                array('category' =>'fang4',  'huxing_shi' =>'5室以上', 'district_street' =>'chongwen', 'price' => '60-80万'),
                array(
                    'errorno' => '0',
                    'errormsg' => '[数据返回成功]',
                    'meta'=>array(
                        'title' => '【崇文5居室以上60-80万二手房求购信息】-北京赶集网',
                        'keywords' => '崇文二手房求购，崇文二手房求购网，5居室以上60-80万二手房求购信息',
                        'description' => '赶集网崇文二手房求购信息发布平台，对发布的崇文二手房求购信息进行严格审核，寻找海量、真实的崇文二手房求购信息，请到北京赶集网。'
                        ),
                    ),
                ),
            /*------------------------------- end fang4-------------------------------------------------*/
        );
   }
}
