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
class FangHistoryPv extends Testcase_PTest
{
    protected $data;

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    public function testGetOneDayByWhere(){
        $whereConds = array(
            'account_id'=>$account_id,
            's_post_at'=>strtotime('today'),
            'e_post_at'=>time(),
        );
        $returnData = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>array(array('house_id'=>125643,'puid'=>15453),),
        );
        $obj1 = $this->genObjectMock("Service_Data_Source_UserPostList",array("getHouseListByWhere"),array(),'',true);
        $obj1->expects($this->any())
            ->method("getHouseListByWhere")
            ->with($whereConds, array(), 1, NULL)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_Source_UserPostList", $obj1);

        $obj = new Service_Data_Source_FangHistoryPv();
        $res = $obj->getOneDayByWhere($whereConds);
        $this->data['data'] = array(
            'puids'=>array($returnData['data'][0]['puid']),
            'house_ids'=>array($returnData['data'][0]['house_id'])
        );
        $this->assertEquals($this->data,$res);
    }
    public function testGetHousePvByAccountId(){
        $obj = new Service_Data_Source_FangHistoryPv();
        $res = $obj->getHousePvByAccountId('aaa');
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$res);

        $whereConds = array(
            'account_id'=>123456,
            's_post_at'=>strtotime('yesterday')-1,
            'e_post_at'=>strtotime('today'),
        );
        $this->data['data'] = array(
            'puids'=>array(15453),
            'house_ids'=>array(125643)
        );
        $obj1 = $this->genObjectMock("Service_Data_Source_FangHistoryPv",array("getOneDayByWhere","getYesterdayPvByHouseId","getHouseTotalPvByPuid"),array(),'',true);
        $obj1->expects($this->any())
            ->method("getOneDayByWhere")
            ->with($whereConds)
            ->will($this->returnValue($this->data));

        $yesterdayPv = array(
            'data'=>array(array('account_pv'     => 10)),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $houseTotalPv = array(
            'data'=>array(array('account_history_pv'     => 10)),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $obj1->expects($this->any())
            ->method("getYesterdayPvByHouseId")
            ->with($whereConds['account_id'], $this->data['data']['house_ids'])
            ->will($this->returnValue($yesterdayPv));

        $obj1->expects($this->any())
            ->method("getHouseTotalPvByPuid")
            ->with($this->data['data']['puids'])
            ->will($this->returnValue($houseTotalPv));
        $resData = array(
            'data'     => array(
                'yesterdayPv'=>$yesterdayPv['data'][0]['account_pv'],
                'houseTotalPv'=>$houseTotalPv['data'][0]['account_history_pv'],
            ),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $res = $obj1->getHousePvByAccountId($whereConds['account_id']);
        $this->assertEquals($resData,$res);
    }
    public function testGetYesterdayPvByHouseId(){
        $account_id = 123456;
        $houseIdArr = array(123456,123456);
        $whereConds = array(
            'account_id'=>$account_id,
            'house_id'=>$houseIdArr,
            'house_type'=>5,
            'ReportDate'=>strtotime('yesterday'),
        	'HouseBiddingMode'=>6,
        );
        $returnData = array(
            'data'=>array('account_pv'     => 10),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $obj1 = $this->genObjectMock("Service_Data_HouseReport_HouseSourceReport",array("getReportByWhere"));
        $obj1->expects($this->any())
            ->method("getReportByWhere")
            ->with($whereConds)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_HouseReport_HouseSourceReport", $obj1);
        $obj = new Service_Data_Source_FangHistoryPv();
        $res = $obj->getYesterdayPvByHouseId($account_id, $houseIdArr);
        $this->assertEquals($returnData,$res);
    }
    public function testGetHouseTotalPvByPuid(){
        $obj = new Service_Data_Source_FangHistoryPv();
        $arrFields = array(
            "SUM(history_count) AS account_history_pv",
        );
        $arrConds = array('puid in ( 123456,123456 )');
        $returnData = array(
            'data'=>array(array('account_history_pv'     => 10)),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $obj1 = $this->genObjectMock("Dao_Housepremier_HouseSourceList",array("selectSumPvByPuids"),array(),'',true);
        $obj1->expects($this->any())
            ->method("selectSumPvByPuids")
            ->with($arrFields, $arrConds)
            ->will($this->returnValue($returnData['data']));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $obj1);
        $puid = array('123456','123456');
        $res = $obj->getHouseTotalPvByPuid($puid);
        $this->assertEquals($returnData,$res);
        
        $objerr = new Service_Data_Source_FangHistoryPv();
        $reserr = $objerr->getHouseTotalPvByPuid('');
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$reserr);
    }
}
