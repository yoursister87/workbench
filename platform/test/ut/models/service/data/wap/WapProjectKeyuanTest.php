<?php

/**
 * @package
 * @subpackage
 * @brief                $wap科园项目单测文件$
 * @file                 WapProjectKeyuanTest.php
 * @author               wanyang:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-25
 * @lastmodified         下午8:35
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WapProjectKeyuanTest extends Testcase_PTest
{
    public function testaddKeyuanData()
    {
        $obj = Gj_LayerProxy::getProxy("Service_Data_Wap_WapProjectKeyuan");
        $input = array(
            'domain' => 'bj',
            'district' => 1,
            'street' => 1,
            'price' => 1,
            'huxing_shi' => 4,
            'type' => 1,
            'major_category' => 1,
            'sales_name' => '王五',
            'phone' => '15987654321'
        );
        $ret = $obj->addKeyuanData($input);
        $this->assertEquals(true, $ret['errorno'] == 0 ||  $ret['errorno'] == 1004);
    }
}