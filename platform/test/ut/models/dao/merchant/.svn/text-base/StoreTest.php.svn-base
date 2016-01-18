<?php
/**
 * Created by PhpStorm.
 * @author          liuhaipeng1@ganji.com
 * @create          2015-07-17 11:03
 * @file            $HeadURL$
 * @version         $Rev$
 * @lastChangeBy    $LastChangedBy$
 * @lastmodified    $LastChangedDate$
 * @copyright       Copyright (c) 2015, www.ganji.com
 */

class StoreTest extends Testcase_PTest {
    protected function setUp() {
        // 注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
    }

    /**
     * @author 刘海鹏 <liuhaipeng1@ganji.com>
     * @create 2015-07-17
     */
    public function testSelect() {
        $arrConds = array('id' => 4042);
        $arrFields = array('full_name', 'city_id', 'city_name', 'district_name', 'street_name', 'address');

        // 成功的情况
        $retVal = array(
            array(
                'full_name'     => '天地昊地产-广安门店',
                'city_id'       => 12,
                'city_name'     => '北京市',
                'district_name' => '',
                'street_name'   => '',
                'address'       => ''
            )
        );
        $objStore = $this->genObjectMock('Dao_Merchant_Store', array('select'));
        $objStore->expects($this->any())
            ->method('select')
            ->will($this->returnValue($retVal));
        Gj_LayerProxy::registerProxy('Dao_Merchant_Store', $objStore);

        $objStore = Gj_LayerProxy::getProxy('Dao_Merchant_Store');
        $res = $objStore->select($arrConds, $arrFields);
        $this->assertEquals($retVal, $res);


        // 失败的情况
        $retVal = false;
        $objCustomer = $this->genObjectMock("Dao_Merchant_Store", array("select", "getLastSQL"));
        $objCustomer->expects($this->any())
            ->method('select')
            ->will($this->returnValue($retVal));
        Gj_LayerProxy::registerProxy("Dao_Merchant_Store", $objCustomer);

        $objStore = Gj_LayerProxy::getProxy('Dao_Merchant_Store');
        $res = $objStore->select($arrConds, $arrFields);
        $this->assertEquals($retVal, $res);
    }
}
