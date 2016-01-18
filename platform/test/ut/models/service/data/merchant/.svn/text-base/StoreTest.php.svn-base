<?php
/**
 * Created by PhpStorm.
 * @author          liuhaipeng1@ganji.com
 * @create          2015-07-17 12:11
 * @file            $HeadURL$
 * @version         $Rev$
 * @lastChangeBy    $LastChangedBy$
 * @lastmodified    $LastChangedDate$
 * @copyright       Copyright (c) 2015, www.ganji.com
 */

class Store extends Testcase_PTest{
    protected $data;

    protected function setUp() {
        // 注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    /**
     * @author 刘海鹏 <liuhaipeng1@ganji.com>
     * @create 2015-07-17
     */
    public function testGetMerchantStoreByMerchantId() {
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
                'address'       => '广安门乐凯大厦'
            )
        );
        $objCustomer = $this->genObjectMock('Dao_Merchant_Store', array('select'));
        $objCustomer->expects($this->any())
            ->method('select')
            ->will($this->returnValue($retVal));
        Gj_LayerProxy::registerProxy('Dao_Merchant_Store', $objCustomer);

        $objCustomer = new Service_Data_Merchant_Store();
        $res = $objCustomer->getMerchantStoreByMerchantId($arrConds, $arrFields);

        $this->data['data'] = $retVal[0];
        $this->assertEquals($this->data, $res);


        // 失败的情况
        $retVal = false;
        $objCustomer = $this->genObjectMock("Dao_Merchant_Store", array("select", "getLastSQL"));
        $objCustomer->expects($this->any())
            ->method('select')
            ->will($this->returnValue($retVal));
        Gj_LayerProxy::registerProxy("Dao_Merchant_Store", $objCustomer);

        $objCustomer = new Service_Data_Merchant_Store();
        $res = $objCustomer->getMerchantStoreByMerchantId($arrConds, $arrFields);

        $this->data = array('errorno' => 1003, 'errormsg' => '[SQL语句执行失败]', 'data' => array());
        $this->assertEquals($this->data, $res);
    }
}
