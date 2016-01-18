<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:  lifangzheng$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Broker_InfoMock extends Service_Data_Broker_Info {

    public function getUserBangbangYears($userIdArr){
        return parent::getUserBangbangYears($userIdArr);
    }   
}

class InfoTest extends Testcase_PTest {
    // {{{getAccountInfoProvider
    /**  
     * @brief 
     */
    public function getAccountInfoProvider(){
        $providerData = array();
        $providerData[] = array(
                array(1, 2, 3),                                                                            //accountIdArr 
                array(                                                                                     //accountCacheInfoArr
                    1 => array('UserId' => '1', 'AccountName' => 'a1', 'AccountId' => '1'),
                    2 => array('UserId' => '2', 'AccountName' => 'a2', 'AccountId' => '2'),
                    3 => array('UserId' => '3', 'AccountName' => 'a3', 'AccountId' => '3'),
                    ), 
                array(                                                                                     //userBangbangYesrs
                    '1' => array('years' => '2'), 
                    '2' => array('years' => '1'), 
                    '3' => array('years' => '3'), 
                    ),
                array(                                                                                     //result
                    1 => array('user_id' => '1', 'account_name' => 'a1', 'bangbang_years' => '2', 'bangbang_active' => 0),
                    2 => array('user_id' => '2', 'account_name' => 'a2', 'bangbang_years' => '1', 'bangbang_active' => 0),
                    3 => array('user_id' => '3', 'account_name' => 'a3', 'bangbang_years' => '3', 'bangbang_active' => 0),
                    ), 
                );
        return $providerData;
    }//}}}
    // {{{testGetAccountInfo
    /**  
     * @brief 
     *
     * @dataProvider getAccountInfoProvider 
     */
    public function testGetAccountInfo($accountIdArr, $accountInfoArr, $userBangbangYesrs, $result){
        $mockClass = array(
                    'Service_Data_Broker_InfoMock' => 
                        array(
                            'getUserBangbangYears' => array('return' => $userBangbangYesrs),
                            'getAccountInfoByAccountId' => array('return' => $accountInfoArr),
                        ),
                    );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Service_Data_Broker_InfoMock'];
        $ret = $obj->getAccountInfo($accountIdArr);
        $this->assertEquals($ret, $result);
    }//}}}
    // {{{testGetUserBangbangYears
    /**  
     * @brief 
     */
/*
    public function testGetUserBangbangYears(){
        $providerData   = array();
        $providerData[] = array(
                'params' => array(),
                'data'   => array(
                    ),
                'result' => array(
                    )
                );
        $providerData[] = array(
                'params' => array(1, 2, 3),
                'data'   => array(
                    array('a1' => array('years' => 'y1')),
                    array('a2' => array('years' => 'y2')),
                    array('a3' => array('years' => 'y3'))
                    ),
                'result' => array(
                    array('a1' => array('years' => 'y1')),
                    array('a2' => array('years' => 'y2')),
                    array('a3' => array('years' => 'y3'))
                    )
                );
        $mockObj = new Service_Data_Broker_InfoMock();
        foreach ($providerData as $line) {
            $fakeObj = $this->genStaticMock("BangNamespace", array("getUserCooperationYear"));
            $fakeObj->expects($this->any())
                ->method('getUserCooperationYear')
                ->will($this->returnValue($line['data']));
            $ret = $mockObj->getUserBangbangYears($line['params']);
            $this->assertEquals($ret, $line['result']);
        }
    }//}}}
    */
}
