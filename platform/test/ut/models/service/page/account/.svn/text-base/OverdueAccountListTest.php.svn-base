<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class OverdueAccountList extends Testcase_PTest
{
    protected $data;
    protected $result;
    protected $arrFields;

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    public function testExecute(){
        $arrInput = array(
            'CityId'	=> 'aaa',
        );
        $obj = new Service_Page_Account_OverdueAccountList();
        $res = $obj->execute($arrInput);
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$res);

        $arrInput = array(
            'CityId'=>0,
            's_premier_expire'=>time(),
            'e_premier_expire'=>time()+7*24*3600,
        );
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' =>array (array ( 'AccountId' => '882978', 'UserId' => '296317196', 'GroupId' => '339809', 'AccountName' => '姜成龙', 'Picture' => '', 'CellPhone' => '15802143946'))
        );
        $accountInfoArr = array (
            'data' => array ( 0 => array ( 'AccountId' => '160741', 'UserId' => '82613696', 'GroupId' => '140272', 'AccountName' => '刘元龙', 'Picture' => 'gjfs08/M08/68/3B/wKhz9VPHcoqeW8jPAAAem2rG8xg076_76-101_8-5.jpg', 'CellPhone' => '18926550772', 'id' => '140272', 'full_name' => '家家顺－－新洲分行', 'city_id' => '17', 'district_id' => '0', 'street_id' => '0', 'company_id' => '12652', 'company_name' => '深圳市家家顺房产交易有限公司',)) ,
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
        );
        $obj = $this->genObjectMock("Service_Page_Account_OverdueAccountList",array("getOverdueAccountByCityId","getIsHouseListByAccount","getGroupInfoByAccount"));
        $obj->expects($this->any())
            ->method("getOverdueAccountByCityId")
            ->with($arrInput)
            ->will($this->returnValue($returnData));

        $obj->expects($this->any())
            ->method("getIsHouseListByAccount")
            ->with($returnData['data'])
            ->will($this->returnValue($returnData['data']));

        $obj->expects($this->any())
            ->method("getGroupInfoByAccount")
            ->with($returnData['data'])
            ->will($this->returnValue($accountInfoArr));
        $res = $obj->execute($arrInput);
        $this->assertEquals($accountInfoArr,$res);

        $obj = $this->genObjectMock("Service_Page_Account_OverdueAccountList",array("getOverdueAccountByCityId","getIsHouseListByAccount","getGroupInfoByAccount"));
        $obj->expects($this->any())
            ->method("getOverdueAccountByCityId")
            ->with($arrInput)
            ->will($this->returnValue($data));

        $obj->expects($this->any())
            ->method("getIsHouseListByAccount")
            ->will($this->returnValue($returnData['data']));

        $obj->expects($this->any())
            ->method("getGroupInfoByAccount")
            ->will($this->returnValue($accountInfoArr));
        $res = $obj->execute($arrInput);
        $this->assertEquals($accountInfoArr,$res);

        $obj = $this->genObjectMock("Service_Page_Account_OverdueAccountList",array("getOverdueAccountByCityId","getIsHouseListByAccount","getGroupInfoByAccount"));
        $obj->expects($this->any())
            ->method("getOverdueAccountByCityId")
            ->with($arrInput)
            ->will($this->returnValue($returnData));

        $obj->expects($this->any())
            ->method("getIsHouseListByAccount")
            ->with($returnData['data'])
            ->will($this->returnValue($returnData['data']));

        $obj->expects($this->any())
            ->method("getGroupInfoByAccount")
            ->will($this->returnValue($accountInfoArr));
        $res = $obj->execute($arrInput);
        $this->assertEquals($accountInfoArr,$res);
    }
    public function testGetOverdueAccountByCityId(){
        $arrInput = array(
            'CityId'=>0,
            's_premier_expire'=>time(),
            'e_premier_expire'=>time()+7*24*3600,
        );
        $arrFields = array('AccountId','UserId',"GroupId",'AccountName','Picture',"CellPhone");
        $arrConds = array(
            'CityId'=> $arrInput['CityId'],
            's_premier_expire'=>$arrInput['s_premier_expire'],
            'e_premier_expire'=>$arrInput['e_premier_expire'],
        );
        $returnData = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' =>array (array ( 'AccountId' => '882978', 'UserId' => '296317196', 'GroupId' => '339809', 'AccountName' => '姜成龙', 'Picture' => '', 'CellPhone' => '15802143946'))
        );
        $obj = $this->genObjectMock("Service_Data_Gcrm_CustomerAccount",array("getAccountListByCustomerId"));
        $obj->expects($this->any())
            ->method("getAccountListByCustomerId")
            ->with($arrConds, $arrFields, 1, 60)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_CustomerAccount", $obj);
        $accountObj = new Service_Page_Account_OverdueAccountList();
        $res = $accountObj->getOverdueAccountByCityId($arrInput);
        $this->assertEquals($returnData,$res);
    }
    public function testGetIsHouseListByAccount(){
        $accountList = array (array ( 'AccountId' => '882978', 'UserId' => '296317196', 'GroupId' => '339809', 'AccountName' => '姜成龙', 'Picture' => '', 'CellPhone' => '15802143946'));
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' =>array (array ( 'puid' => '882978', 'house_id' => '296317196', 'type' => '3'))
        );
        $arrConds = array(
            'premier_status'=>array(2,102),
            'account_id' => $accountList[0]['AccountId']
        );
        $obj = $this->genObjectMock("Service_Data_Source_PremierQuery",array("getTuiguangHouseByAccountId"));
        $obj->expects($this->any())
            ->method("getTuiguangHouseByAccountId")
            ->with($arrConds, array(), 1, 1)
            ->will($this->returnValue($data));
        Gj_LayerProxy::registerProxy("Service_Data_Source_PremierQuery", $obj);

        $accountObj = new Service_Page_Account_OverdueAccountList();
        $res = $accountObj->getIsHouseListByAccount($accountList);
        $this->assertEquals($accountList,$res);
    }
    public function testGetGroupInfoByAccount(){
        $accountList = array (array ( 'AccountId' => '882978', 'UserId' => '296317196', 'GroupId' => '339809', 'AccountName' => '姜成龙', 'Picture' => '', 'CellPhone' => '15802143946'));
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' =>array (array ( 'id' => '339809', 'company_id' => '296317196', 'company_name' => '3'))
        );
        $whereConds = array('GroupId'=>array($accountList[0]['GroupId']));
        $obj = $this->genObjectMock("Service_Data_Gcrm_Customer",array("getCustomerInfoListByCompanyId"));
        $obj->expects($this->any())
            ->method("getCustomerInfoListByCompanyId")
            ->with($whereConds)
            ->will($this->returnValue($data));
        Gj_LayerProxy::registerProxy("Service_Data_Gcrm_Customer", $obj);
        $accountObj = new Service_Page_Account_OverdueAccountList();
        $res = $accountObj->getGroupInfoByAccount($accountList);
        $accountInfoArr['data'] = array(array_merge($accountList[0],$data['data'][0]));
        $accountInfoArr['errorno'] = ErrorConst::SUCCESS_CODE;
        $accountInfoArr['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->assertEquals($accountInfoArr,$res);
    }
}
