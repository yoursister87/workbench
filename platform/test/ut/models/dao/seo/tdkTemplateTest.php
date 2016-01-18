<?php
/**
 * @package
 * @subpackage
 * @brief                $单测文件$
 * @file                 tdkTemplateTest.php
 * @author               wanyang:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-29
 * @lastmodified         下午3:13
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class tdkTemplateTest extends Testcase_PTest
{
    public function testgetTdkTemplate(){
        $obj = Gj_LayerProxy::getProxy("Dao_Seo_Tdk_Template");
        $input = array('title', 'RentDefault_1');
        $expect = '【100%{district_street}个人房源网|{district_street}{keyword}房东房源直租】{city}{district_street}个人房源出租信息-{city}赶集网';
        $this->assertEquals($expect , $obj->getTdkTemplate($input));
    }
}