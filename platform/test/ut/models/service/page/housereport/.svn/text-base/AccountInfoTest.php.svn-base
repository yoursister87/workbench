<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class AccountInfo extends Testcase_PTest{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    public function testExecute(){
        $caidInput = 123;
        $caidRes = array(
            'GroupId' => 1,
            'AccountId' => 123,
            'UserId' => 3,
            'CreatedTime' => '2014-7-1'
        );
        $cuidRes = array(
            "id" => 1,
            "full_name" => 1,
            "company_name" => 1,
            "company_id" => 1,         
        );
        $accountInfo['customerId']= $customerRes['data']['id'];
        $accountInfo['customerName'] = $customerRes['data']['full_name'];
        $accountInfo['companyId'] = $customerRes['data']['company_id'];
        $accountInfo['companyName'] = $customerRes['data']['company_name'];
        $finalRes = array(
            'GroupId' => 1,
            'CreatedTime' => strtotime('2014-7-1'),
            'AccountId' => 123,
            'UserId' => 3,
            'CustomerId' => 1,
            'CustomerName' => 1,
            'CompanyId' => 1,
            'CompanyName' => 1,
        );
        $finalOutput = $caidOutput = $cuidOutPut = $retArr = array(
            'errorno' => 0,
            'errormsg' => '[数据返回成功]',
            'data' => array()       
        );
        $caidOutput['data'][] = $caidRes;
        $cuidOutPut['data'] = $cuidRes;
        $finalOutput['data']= $finalRes;
		$customerAccountObj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount", array("getAccountInfoById", "getAccountInfoByUserId"));
        $customerAccountObj->expects($this->any())
            ->method('getAccountInfoById')
            ->will($this->returnValue($caidOutput));
        $customerObj = $this->genObjectMock("Service_Data_Gcrm_Customer", array("getCustomerInfoByCustomerId"));
        $customerObj->expects($this->any())
            ->method('getCustomerInfoByCustomerId')
            ->will($this->returnValue($cuidOutPut));
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $customerAccountObj);
		Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer", $customerObj);
        $arrInput = array('account_id' => 123,);
        $testObj = new Service_Page_Account_AccountInfo();
        $ret = $testObj->execute($arrInput);
        $this->assertEquals($ret, $finalOutput);
    }
}

