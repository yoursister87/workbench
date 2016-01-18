<?php
/**
 * @package
 * @subpackage
 * @brief                $客源测试类$
 * @file                 WapProjectKeyuanDaoTest.php
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-12-25
 * @lastmodified         下午8:37
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class WapProjectKeyuanDaoTest extends Testcase_PTest
{
    public function testinsertKeyuanData(){
        $obj = Gj_LayerProxy::getProxy("Dao_Wap_WapProjectKeyuan");
        $input = array(
            'conditions' => array(
                'domain' => 'bj',
                'district' => 1,
                'street' => 2,
                'price' => 2,
                'huxing_shi' => 3,
            ),
            'type' => 1,
            'major_category' => 1,
            'sales_name' => '张三',
            'phone' => '18812345678'
        );
        $this->assertEquals(true,$obj->insertKeyuanData($input));
    }
}