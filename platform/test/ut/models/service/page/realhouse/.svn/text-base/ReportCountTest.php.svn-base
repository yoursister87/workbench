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
class ReportCount extends Testcase_PTest
{
    protected $data;

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    public function testExecute(){
        $arrInput['account_id'] = 'aaa';
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $obj = new Service_Page_RealHouse_ReportCount();
        $res = $obj->execute($arrInput);
        $this->assertEquals($data,$res);

        $arrInput = array(
        	'user_id'=>123,
           'account_id'=>123456,
        	'owner_account_id'=>123456,
        );
        $obj1 = $this->genObjectMock("Service_Page_RealHouse_ReportCount",array("getOneDayByWhere","getHouseCommentArr","getHouseListArr","getHousePvByAccountId"),array(),'',true);
        $resTodayArr = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>array(
                'puids'=>array(15453),
                'house_ids'=>array(125643)
            ),
        );
        $obj1->expects($this->any())
            ->method("getOneDayByWhere")
            ->with($arrInput['owner_account_id'])
            ->will($this->returnValue($resTodayArr));

        $resHouseComment = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>array('puid_ids' => array(123456),'house_ids' => array(123456)),
        );
        $obj1->expects($this->any())
            ->method("getHouseCommentArr")
            ->with($resTodayArr['data']['puids'],$arrInput['user_id'])
            ->will($this->returnValue($resHouseComment));
        
        $resHouseList = array(
        		'errorno' => ErrorConst::SUCCESS_CODE,
        		'errormsg' => ErrorConst::SUCCESS_MSG,
        		'data'=>array('puid_ids' => array(15453),'house_ids' => array(125643)),
        );
        $obj1->expects($this->any())
        ->method("getHouseListArr")
        ->with(array(15453))
        ->will($this->returnValue($resHouseList));

        $housePv = array(
            'data'=>array('yesterdayPv'     => 10,'houseTotalPv'     => 10),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $obj1->expects($this->any())
            ->method("getHousePvByAccountId")
            ->with($arrInput['account_id'])
            ->will($this->returnValue($housePv));

        $res = $obj1->execute($arrInput);
        $data = array(
            'data'=>array(
                'yesterdayPv'     => 10,
                'houseTotalPv'     => 10,
                'newHouse'=>count($resTodayArr['data']['puids']),
                'commentHouse'=>count($resHouseComment['data']['puid_ids']),
                'noCommentHouse'=>count($resHouseList['data']['puid_ids']),
            ),
            'errorno'  =>  ErrorConst::SUCCESS_CODE,
            'errormsg' =>  ErrorConst::SUCCESS_MSG,
        );
        $this->assertEquals($data,$res);
    }
    public function testGetHouseCommentArr(){
        $resHouseComment = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>array(array(
            		'house_id'=>123456,
            		'puid'=>123456,
            )),
        );
        $resPuidArr = array(123456);
        $user_id = 123456;
        $whereConds = array(
        		'puid'=>$resPuidArr,
        		'user_id'=>$user_id,
        		's_post_at'=>strtotime(today),
        		'e_post_at'=>strtotime(Tomorrow)-1,
        );
        $arrFields = array("house_id","puid");
        $obj1 = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentListByWhere"));
        $obj1->expects($this->any())
            ->method("getCommentListByWhere")
            ->with($whereConds, $arrFields, 1, NULL)
            ->will($this->returnValue($resHouseComment));
        Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj1);
        $obj = new Service_Page_RealHouse_ReportCount();
        $res = $obj->getHouseCommentArr($resPuidArr, $user_id);
        $houseArr['puid_ids'] = array(123456);
        $houseArr['house_ids'] = array(123456);
        $this->data['data'] = $houseArr;
        $this->assertEquals($this->data,$res);
    }
    public function testGetOneDayByWhere(){
        $account_id = 123456;
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
        $obj1 = $this->genObjectMock("Service_Data_Source_FangHistoryPv",array("getOneDayByWhere"));
        $obj1->expects($this->any())
            ->method("getOneDayByWhere")
            ->with($whereConds)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangHistoryPv", $obj1);
        $obj = new Service_Page_RealHouse_ReportCount();
        $res = $obj->getOneDayByWhere($account_id);
        $this->assertEquals($returnData,$res);
    }
    public function testGetHousePvByAccountId(){
        $account_id = 123456;
        $returnData = array(
            'errorno' => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=> array(
            'yesterdayPv'=>10,
            'houseTotalPv'=>10,
        ),
        );
        $obj1 = $this->genObjectMock("Service_Data_Source_FangHistoryPv",array("getHousePvByAccountIdByCache"));
        $obj1->expects($this->any())
            ->method("getHousePvByAccountIdByCache")
            ->with($account_id)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Service_Data_Source_FangHistoryPv", $obj1);
        $obj = new Service_Page_RealHouse_ReportCount();
        $res = $obj->getHousePvByAccountId($account_id);
        $this->assertEquals($returnData,$res);
    } 
    public function testGetHouseListArr(){
    	$puidArr = array(123456);
    	$arrFields = array("house_id","puid");
    	$puids = implode(',', $puidArr);
    	$whereConds = array(
    			'listing_status'=>1,
    			'premier_status'=>array(111,112),
    			'puid'=>$puidArr,
    	);
    	$returnData = array(
    			'errorno' => ErrorConst::SUCCESS_CODE,
    			'errormsg' => ErrorConst::SUCCESS_MSG,
    			'data'=>array(array(
    					'house_id'=>123456,
    					'puid'=>123456,
    			)),
    	);
    	$obj1 = $this->genObjectMock("Service_Data_Source_PremierQuery",array("getTuiguangHouseByAccountId"));
    	$obj1->expects($this->any())
    	->method("getTuiguangHouseByAccountId")
    	->with($whereConds, $arrFields)
    	->will($this->returnValue($returnData));
    	Gj_LayerProxy::registerProxy("Service_Data_Source_PremierQuery", $obj1);
    	
    	$obj = new Service_Page_RealHouse_ReportCount();
    	$res = $obj->getHouseListArr($puidArr);
    	$houseArr['puid_ids'] = array(123456);
    	$houseArr['house_ids'] = array(123456);
    	$this->data['data'] = $houseArr;
    	$this->assertEquals($this->data,$res);
    }
}