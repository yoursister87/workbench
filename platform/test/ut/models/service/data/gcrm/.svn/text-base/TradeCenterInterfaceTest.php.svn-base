<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * Date: 2014/12/29
 * Time: 10:24
 */
class TradeCenterInterface extends Testcase_PTest{
    protected $data;
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->data['succeed'] = 1;
    }
    
    public function testGetBalanceList(){
        $arrInput = array(
            'UserId' => 50029116,
            'ProductCode' => 'pd_post_num',
            'CategoryType' => 7,
            'Extension' => 1,
            'CityId' => 12
        );
        $arrInputNew = array(
            'users' => array( 50029116 ),
            'products' => array('pd_post_num'),
            'categories' => array(7),
            'extension' => 1,
            'cities' => array(12)
        );
        $res = array( 0 =>
                array(
                'BeginAt' => 1314471111,
                'EndAt' => 1394471111,
                'Amount' => 40,
                'CreatedAt' => 1314471111,
                'Status' => 1
            )
        );
        $resNew = array( 0 =>
                (object) array(
                'beginAt' => 1314471111,
                'endAt' => 1394471111,
                'amount' => 40,
                'createdAt' => 1314471111,
                'status' => 1
            )
        );
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface", array("getBalances"));
        $obj->expects($this->any())
            ->method('getBalances')
            ->with($arrInputNew)
            ->will($this->returnValue($resNew));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->GetBalanceList($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($ret,$this->data);
    }

    public function testGetBalanceListByUserList(){
        $arrInput = array(
            'UserIdList' => array(50029116)
        );
        $arrInputNew = array(
            'users' => array(50029116),
            'categories' => array(7)
        );
        $res = array( 0 =>
            array(
                'Id' => 9145,
                'UserId' => 50029116,
                'ProductCode' => 'pd_post_num',
                'CategoryType' => 7,
                'CityId' => 12,
                'TotalAmount' => 40,
                'BeginAt' => 1314471111,
                'EndAt' => 1394471111,
                'Amount' => 40,
                'Status' => 1
            )
        );
        $resNew = array( 0 =>
            (object) array(
                'id' => 9145,
                'userId' => 50029116,
                'product' => 'pd_post_num',
                'category' => 7,
                'city' => 12,
                'amount' => 40,
                'beginAt' => 1314471111,
                'endAt' => 1394471111,
                'amountLeft' => 40,
                'status' => 1
            )
        );
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface", array("getBalances"));
        $obj->expects($this->any())
            ->method('getBalances')
            ->with($arrInputNew)
            ->will($this->returnValue($resNew));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->GetBalanceListByUserList($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($this->data,$ret);
    }

    public function testGetPagedBalanceList(){
        $arrInput = array(
            'UserId' => 50029116,
            'CityId' => 12,
            'BeginAt' => '',
            'EndAt' => '',
            'CategoryType' => 7,
            'PageSize' => 15,
            'PageIndex' => 1,
            'ProductCode' => 'pd_post_num'
        );
        $arrInputNew = array(
            'users' => array( 50029116),
            'cities' => array(12),
            'categories' => array(7),
            'pageSize' => 15,
            'pageIndex' => 1,
            'products' => array('pd_post_num')
        );
        $res = array( 
            'TotalCount' => 1,
            'BalanceList' => 
                array( 0 => 
                    array (
                        'Id' => 25236,
                        'OrderId' => 0,
                        'UserId' => 50029116,
                        'CityId' => 12,
                        'CategoryType' => 7,
                        'ProductCode' => 'pd_post_num',
                        'Amount' => 40,
                        'AmountLeft' => 40,
                        'BeginAt' => 1358438400,
                        'EndAt' => 1366300799,
                        'Status' => 1,
                        'SourceType' => 0,
                        'CreatedAt' => 1358480519,
                        'Extension' => '1',
                        'refund_at' => 0
                    )
                )
        );
        $resNew = array( 0 =>
                    (object) array (
                        'id' => 25236,
                        'orderId' => 0,
                        'userId' => 50029116,
                        'city' => 12,
                        'category' => 7,
                        'product' => 'pd_post_num',
                        'amount' => 40,
                        'amountLeft' => 40,
                        'beginAt' => 1358438400,
                        'endAt' => 1366300799,
                        'status' => 1,
                        'sourceType' => 0,
                        'createdAt' => 1358480519,
                        'extension' => '1',
                        'refundAt' => 0
                    )
        );
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface",array("getBalances"));
        $obj->expects($this->any())
            ->method('getBalances')
            ->with($arrInputNew)
            ->will($this->returnValue($resNew));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->GetPagedBalanceList($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($this->data,$ret);
    }

    public function testAdduserBalance(){
        $arrInput = array(
            'UserId' => 50029116,
            'CityId' => 12,
            'CategoryType' => 7,
            'ProductCode' => 'pd_post_num',
            'Remark' => 'youyouyou',
            'Extension'    => '',
            'BeginAt'      => 1358438400,
            'EndAt'        => 1368438400,
            'Amount'       => 1
        );
        $arrInputNew = array(
            'userId' => 50029116,
            'city' => 12,
            'category' => 7,
            'product' => 'pd_post_num',
            'extension'  => '',
            'beginAt'    => 1358438400,
            'endAt'      => 1368438400,
            'amount'     => 1,
            'sourceType' => 5
        );
        $userId = $arrInput['UserId'];
        $userName = '房产业务';
        $remark = $arrInput['Remark'];
        $res = 1;
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface", array("addUserBalance"));
        $obj->expects($this->any())
            ->method('addUserBalance')
            ->with($arrInputNew, $userId, $userName, $remark)
            ->will($this->returnValue(null));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->AdduserBalance($arrInput);
        $this->data['data'] = null;
        $this->assertEquals($this->data,$ret);
    }

    public function testConsumeByProduct(){
        $arrInput = array(
            "UserId"        => 50029116,
            "CityId"        => 12,
            "CategoryType"  => 7,
            "ProductCode"   => 'pd_post_num',
            "ConsumeKey"    => '123456789',
            "ConsumeAmount" => 40,
            "ConsumeAt" => 1414489926,
            "Token" => md5('a1e4a729e9cd4c9fafa35c536108703e'.'123456789'.'pd_post_num'.'1414489926')    
        );
        $arrInputNew = array(
            "userId"        => 50029116,
            "city"        => 12,
            "category"  => 7,
            "product"   => 'pd_post_num',
            "consumeKey"    => '123456789',
            "amount" => 40,
            "consumeTime" => 1414489926,
            "token" => md5('a1e4a729e9cd4c9fafa35c536108703e'.'123456789'.'pd_post_num'.'1414489926')
        );
        $res = 1;
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface",array('consume'));
        $obj->expects($this->any())
            ->method('consume')
            ->with($arrInputNew)
            ->will($this->returnValue(1));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->ConsumeByProduct($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($this->data,$ret);
    }

    public function testGetBalanceDuration(){
        $arrInput = array(
            "UserId"        => 50029116,
            "CityId"        => 12,
            "CategoryType"  => 7,
            "ProductCode"   => 'pd_post_num' 
        );
        $arrInputNew = array(
            'users' => array( 50029116),
            'cities' => array(12),
            'categories' => array(7),
            'products' => array('pd_post_num')
        );
        $res = array( 
                        'UserId' => 50029116,
                        'Balance' => array(
                        'CityId' => 12,
                        'CategoryType' => 7,
                        'ProductCode' => 'pd_post_num',
                        'Extension' => 1
                        ),
                        'Amount' => 0,
                        'TotalAmount' => 40,
                        'MinBeginTime' => 1358438400,
                        'MaxEndTime' => 1366300799,
                        'MinInDurationBeginTime' => 0,
                        'MaxInDurationEndTime' => 0,
                        'InDuration' => false,
                        'IsFreezed' => false,
                        'InDurationBeginAt' =>0,
                        'InDurationEndAt' => 0,
                        'OutDurationBeginAt' => 1358438400,
                        'OutDurationEndAt' => 1366300799
        );
        $resNew = array( 0 =>
                    (object) array (
                        'id' => 25236,
                        'orderId' => 0,
                        'userId' => 50029116,
                        'city' => 12,
                        'category' => 7,
                        'product' => 'pd_post_num',
                        'amount' => 40,
                        'amountLeft' => 40,
                        'beginAt' => 1358438400,
                        'endAt' => 1366300799,
                        'status' => 1,
                        'sourceType' => 0,
                        'createdAt' => 1358480519,
                        'extension' => '1',
                        'refundAt' => 0
                    )
                );
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface", array("getBalances"));
        $obj->expects($this->any())
            ->method('getBalances')
            ->with($arrInputNew)
            ->will($this->returnValue($resNew));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->GetBalanceDuration($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($this->data,$ret);
    }
     public function testGetBalanceDurationList(){
        $arrInput = array(
            "UserId"        => 50029116,
            "CityId"        => 12,
            "CategoryType"  => 7,
            "ProductCode"   => 'pd_post_num'
        );
        $arrInputNew = array(
            'users' => array( 50029116),
            'cities' => array(12),
            'categories' => array(7),
            'products' => array('pd_post_num')
        );
        $res = array( 0 => array(
                        'UserId' => 50029116,
                        'Balance' => array(
                        'CityId' => 12,
                        'CategoryType' => 7,
                        'ProductCode' => 'pd_post_num',
                        'Extension' => 1
                        ),
                        'Amount' => 0,
                        'TotalAmount' => 40,
                        'MinBeginTime' => 1358438400,
                        'MaxEndTime' => 1366300799,
                        'MinInDurationBeginTime' => 0,
                        'MaxInDurationEndTime' => 0,
                        'InDuration' => false,
                        'IsFreezed' => false,
                        'InDurationBeginAt' =>0,
                        'InDurationEndAt' => 0,
                        'OutDurationBeginAt' => 1358438400,
                        'OutDurationEndAt' => 1366300799
                    )
        );
        $resNew = array( 0 =>
                    (object) array (
                        'id' => 25236,
                        'orderId' => 0,
                        'userId' => 50029116,
                        'city' => 12,
                        'category' => 7,
                        'product' => 'pd_post_num',
                        'amount' => 40,
                        'amountLeft' => 40,
                        'beginAt' => 1358438400,
                        'endAt' => 1366300799,
                        'status' => 1,
                        'sourceType' => 0,
                        'createdAt' => 1358480519,
                        'extension' => '1',
                        'refundAt' => 0
                    )
                );
        $obj = $this->genObjectMock("Dao_Gcrm_TradeCenterInterface",             array("getBalances"));
        $obj->expects($this->any())
            ->method('getBalances')
            ->with($arrInputNew)
            ->will($this->returnValue($resNew));
        Gj_LayerProxy::registerProxy('Dao_Gcrm_TradeCenterInterface',$obj);
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->GetBalanceDurationList($arrInput);
        $this->data['data'] = $res;
        $this->assertEquals($this->data,$ret);
    }
    
    public function testInParamsProcess(){
        $arrInput = array(
            'UserId' => 50029116,
            'CityId' => 12,
            'Extension' => 1,
            'CategoryType' => 7,
            'PageSize' => 15,
            'PageIndex' => 1,
            'ProductCode' => 'pd_post_num' 
        );
        $res = array(
            'users' => array(50029116),
            'cities' => array(12),
            'extension' => 1,
            'categories' => array(7),
            'pageSize' => 15,
            'pageIndex' => 1,
            'products' => array('pd_post_num')
        );
        $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
        $ret = $orgObj->InParamsProcess($arrInput);
        $this->assertEquals($res,$ret);
    }

    public function testGroup(){
         $arrInput = array( 
                        0 =>
                            array (
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                            ),
                        1 =>
                            array (
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_sms',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                            ),
                        2 =>
                            array (
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438401,
                                'endAt' => 1366300798,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                            ),
                    
                );
                $res =array( 
                        'pd_post_num1271' =>
                            array ( 0 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                ),
                                1 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438401,
                                'endAt' => 1366300798,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                )    
                            ),
                        'pd_sms1271' =>
                            array ( 0 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_sms',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                )
                            )
                );
                $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
                $ret = $orgObj->Group($arrInput);
                $this->assertEquals($ret,$res);
    }

    public function testDataProcess(){
        $arrInput = array( 
                        'pd_post_num1271' =>
                            array ( 0 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                ),
                                1 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_post_num',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438401,
                                'endAt' => 1366300798,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                )    
                            ),
                        'pd_sms1271' =>
                            array ( 0 => array(
                                'id' => 25236,
                                'orderId' => 0,
                                'userId' => 50029116,
                                'city' => 12,
                                'category' => 7,
                                'product' => 'pd_sms',
                                'amount' => 40,
                                'amountLeft' => 40,
                                'beginAt' => 1358438400,
                                'endAt' => 1366300799,
                                'status' => 1,
                                'sourceType' => 0,
                                'createdAt' => 1358480519,
                                'extension' => '1',
                                'refundAt' => 0
                                )
                            )
                );
            $res = array( 0 => array(
                        'UserId' => 50029116,
                        'Balance' => array(
                        'CityId' => 12,
                        'CategoryType' => 7,
                        'ProductCode' => 'pd_post_num',
                        'Extension' => 1
                        ),
                        'Amount' => 0,
                        'TotalAmount' => 40,
                        'MinBeginTime' => 1358438400,
                        'MaxEndTime' => 1366300799,
                        'MinInDurationBeginTime' => 0,
                        'MaxInDurationEndTime' => 0,
                        'InDuration' => false,
                        'IsFreezed' => false,
                        'InDurationBeginAt' =>0,
                        'InDurationEndAt' => 0,
                        'OutDurationBeginAt' => 1358438400,
                        'OutDurationEndAt' => 1366300799
                        ),
                        1 => array(
                        'UserId' => 50029116,
                        'Balance' => array(
                        'CityId' => 12,
                        'CategoryType' => 7,
                        'ProductCode' => 'pd_sms',
                        'Extension' => 1
                        ),
                        'Amount' => 0,
                        'TotalAmount' => 40,
                        'MinBeginTime' => 1358438400,
                        'MaxEndTime' => 1366300799,
                        'MinInDurationBeginTime' => 0,
                        'MaxInDurationEndTime' => 0,
                        'InDuration' => false,
                        'IsFreezed' => false,
                        'InDurationBeginAt' =>0,
                        'InDurationEndAt' => 0,
                        'OutDurationBeginAt' => 1358438400,
                        'OutDurationEndAt' => 1366300799
                        )
            ); 
            $orgObj = new Service_Data_Gcrm_TradeCenterInterface();
            $ret = $orgObj->DataProcess($arrInput);
            $this->assertEquals($res,$res);
    }
}
?>
