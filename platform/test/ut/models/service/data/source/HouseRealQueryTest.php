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
class HouseRealQuery extends Testcase_PTest
{
	protected $data;
	protected $result;
	protected $arrFields;
    protected $arrListFields;
    protected $arrPremierFields;
	protected function setUp(){
		//注册对象用于单元测试
		Gj_LayerProxy::$is_ut = true;
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$this->result = array(array('puid'=>123,'company_id'=>45888));
		$this->arrFields = array("comment_id","puid","title","content","post_at","modified_at","ip","user_id","user_name","user_phone","owner_user_id");
		$this->arrListFields = array('house_id', 'puid', 'user_code', 'history_count', 'yesterday_count');
		$this->arrPremierFields = array('house_id','title', 'type', 'puid', 'account_id', 'user_id', 'xiaoqu', 'xiaoqu_id', 'district_id', 'district_name', 'street_id', 'street_name', 'image_count', 'price', 'fiveyears', 'only_house', 'elevator', 'huxing_shi', 'huxing_ting', 'huxing_wei','chaoxiang','area');
	}
	public function testGetHouseRealCommentByUserId(){
		$user_id = 123456;
		$owner_user_id =1000152808;
		$arrConds = array(
				'user_id'=>$user_id,
				'owner_user_id'=>$owner_user_id,
		);
		$this->data['data'] = $this->result;
		$arrFields = array('puid');
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment", array("getCommentListByWhere"));
		$obj->expects($this->any())
		->method('getCommentListByWhere')
		->with($arrConds,$arrFields,1,NULL)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		$commentObj = new Service_Data_Source_HouseRealQuery();
		$res = $commentObj->getHouseRealCommentByUserId($user_id,$owner_user_id);
		$this->data['data'] = array($this->result[0]['puid']);
		$this->assertEquals($this->data,$res);
		
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealComment", array("getCommentListByWhere"));
		$obj->expects($this->any())
		->method('getCommentListByWhere')
		->with($arrConds,$arrFields,1,NULL)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseRealComment", $obj);
		$commentObj = new Service_Data_Source_HouseRealQuery();
		$res = $commentObj->getHouseRealCommentByUserId($user_id,$owner_user_id);
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseListByWhere(){
		$account_id = 123456;
		$premier_status = 110;
		$listing_status = 1;
		$owner_user_id = 1000152808;
		$arrConds = array(
			'premier_status'=>$premier_status,
            'account_id'=> $account_id,
        	'listing_status'=>1,
			'type'=>5
		);
		$this->data['data'] = $this->result;
		$arrFields = array('puid');
		$obj = $this->genObjectMock("Service_Data_Source_PremierQuery", array("getTuiguangHouseByAccountId"));
		$obj->expects($this->any())
		->method('getTuiguangHouseByAccountId')
		->with($arrConds, array(), 1, NULL)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierQuery", $obj);
		$commentObj = new Service_Data_Source_HouseRealQuery();
		$res = $commentObj->getHouseListByWhere($account_id,$premier_status,$listing_status,NULL);
		$this->data['data'] = array($this->result[0]['puid']);
		$this->assertEquals($this->data,$res);
		
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_PremierQuery", array("getTuiguangHouseByAccountId"));
		$obj->expects($this->any())
		->method('getTuiguangHouseByAccountId')
		->with($arrConds, array(), 1, NULL)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_PremierQuery", $obj);
		$commentObj = new Service_Data_Source_HouseRealQuery();
		$res = $commentObj->getHouseListByWhere($account_id,$premier_status,$listing_status,NULL);
		$this->assertEquals($this->data,$res);
	}
	public function testGetNoCommentHouseList(){
		$user_id = 123456;
		$account_id = 123456;
		$owner_user_id = 1000152808;
		$premier_status = array(111,112);
		$listing_status = 1;
		$whereConds = array(
				'user_id'=>$user_id,
				'customer_id'=>123,
				'owner_account_id'=>$account_id,
				'owner_user_id'=>$owner_user_id,
		);
		$realComm = array(123,456);
		$houseList = array(123,456,789);
		//评论返回错误
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id,$owner_user_id)
		->will($this->returnValue($this->data));
		
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//帖子返回错误
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));

		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//评论、帖子都返回空
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$this->data['data'] = array();
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//评论、帖子puid完全相同
		$this->data['data'] = $realComm;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$this->data['data'] = $realComm;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->data['data'] = array();
		$this->assertEquals($this->data,$res);
		
		//评论、帖子puid不完全相同
		$this->data['data'] = $realComm;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere","getHouseListByPuid","getHouseCommentPrivilegeInfo"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id,$owner_user_id)
		->will($this->returnValue($this->data));
		
		$this->data['data'] = $houseList;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		
		$puidArr = array_diff($houseList, $realComm);
		$this->data['data'] = $puidArr;
		$obj->expects($this->any())
		->method('getHouseCommentPrivilegeInfo')
		->with($puidArr, $whereConds['customer_id'])
		->will($this->returnValue($this->data));
		
		$arrConds = $whereConds;
		$arrConds['puid'] = $puidArr;
		$this->data['data'] = $this->result;
		$obj->expects($this->any())
		->method('getHouseListByPuid')
		->with($arrConds)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->data['data']['count'] = count($puidArr);
		$this->assertEquals($this->data,$res);
		
		//评论为空，帖子不为空
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere","getHouseListByPuid","getHouseCommentPrivilegeInfo"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$this->data['data'] = $houseList;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, $premier_status, $listing_status)
		->will($this->returnValue($this->data));
		
		$obj->expects($this->any())
		->method('getHouseCommentPrivilegeInfo')
		->with($houseList, $whereConds['customer_id'])
		->will($this->returnValue($this->data));
		
		$puidArr = $houseList;
		$arrConds = $whereConds;
		$arrConds['puid'] = $puidArr;
		$this->data['data'] = $this->result;
		$obj->expects($this->any())
		->method('getHouseListByPuid')
		->with($arrConds)
		->will($this->returnValue($this->data));
		$res = $obj->getNoCommentHouseList($whereConds);
		$this->data['data']['count'] = count($puidArr);
		$this->assertEquals($this->data,$res);
	}
	public function testGetCommentHouseList(){
		$user_id = 123456;
		$account_id = 123456;
		$premier_status = 110;
		$owner_user_id = 1000152808;
		$whereConds = array(
				'user_id'=>$user_id,
				'owner_account_id'=>$account_id,
				'owner_user_id'=>$owner_user_id,
		);
		$realComm = array(123,456);
		$houseList = array(123);
		//评论返回错误
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id)
		->will($this->returnValue($this->data));
		
		$res = $obj->getCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//评论正确
		$this->data['data'] = $realComm;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere","getHouseListByPuid"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$conds = "premier_status = {$premier_status} or listing_status != 1";
		$this->data['data'] = $houseList;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, NULL,NULL,$conds)
		->will($this->returnValue($this->data));
		
		$arrConds = $whereConds;
		$puidArr = array_diff($realComm, $houseList);
		$arrConds['puid'] = $puidArr;
		$this->data['data'] = $this->result;
		$obj->expects($this->any())
		->method('getHouseListByPuid')
		->with($arrConds)
		->will($this->returnValue($this->data));
		$res = $obj->getCommentHouseList($whereConds);
		$this->data['data']['count'] = count($houseList);
		$this->assertEquals($this->data,$res);
	}
	public function testGetDelCommentHouseList(){
		$user_id = 123456;
		$account_id = 123456;
		$premier_status = 110;
		$owner_user_id = 1000152808;
		$whereConds = array(
				'user_id'=>$user_id,
				'owner_account_id'=>$account_id,
				'owner_user_id'=>$owner_user_id,
		);
		$realComm = array(123,456);
		$houseList = array(123,456,789);
		//评论返回错误
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$conds = "premier_status = {$premier_status} or listing_status != 1";
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, NULL,NULL,$conds)
		->will($this->returnValue($this->data));
		$res = $obj->getDelCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//帖子返回错误
		$this->data['data'] = $realComm;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere"));
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$conds = "premier_status = {$premier_status} or listing_status != 1";
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, NULL,NULL,$conds)
		->will($this->returnValue($this->data));
		$res = $obj->getDelCommentHouseList($whereConds);
		$this->assertEquals($this->data,$res);
		
		//评论、帖子puid不完全相同
		$this->data['data'] = $realComm;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseRealCommentByUserId","getHouseListByWhere","getHouseListByPuid"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseRealCommentByUserId')
		->with($user_id, $owner_user_id)
		->will($this->returnValue($this->data));
		
		$conds = "premier_status = {$premier_status} or listing_status != 1";
		$this->data['data'] = $houseList;
		$obj->expects($this->any())
		->method('getHouseListByWhere')
		->with($account_id, NULL,NULL,$conds)
		->will($this->returnValue($this->data));
		
		$puidArr = array_intersect($houseList, $realComm);
		$arrConds = $whereConds;
		$arrConds['puid'] = $puidArr;
		$this->data['data'] = $this->result;
		$obj->expects($this->any())
		->method('getHouseListByPuid')
		->with($arrConds)
		->will($this->returnValue($this->data));
		$res = $obj->getDelCommentHouseList($whereConds);
		$this->data['data']['count'] = count($puidArr);
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseListByPuid(){
		$whereConds = array ( 'user_id' => '500008706', 'owner_account_id' => 1002772, 'page' => 1, 'pageSize' => 10, 'puid' => array ( 0 => '98055377', 1 => '98059408', ), );
		$resList = array ( 'data' => array ( 0 => array ( 'house_id' => '69171885', 'puid' => '98055377', 'user_code' => 'qqqqq3', 'history_count' => '0', 'yesterday_count' => '0', 'title' => '测试房源标题', 'type' => '5', 'account_id' => '1001634', 'user_id' => '0', 'xiaoqu' => '人民日报社家属区', 'xiaoqu_id' => '8708', 'district_id' => '1', 'district_name' => '朝阳', 'street_id' => '46', 'street_name' => '朝外', 'image_count' => '4', 'price' => '5000000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => NULL, 'huxing_shi' => '2', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '7', 'area' => '121.00', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', 'count' => 1, );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseSourceList","getHouseSourceSellPremier","getHouseModifyRecordByPuid","getYesterdayPvByHouseId"),array(),'',true);
		$arrConds['user_code'] = 'aaaa';
		$obj->expects($this->any())
		->method('getHouseSourceList')
		->with($arrConds)
		->will($this->returnValue($resList));
		
		$obj->expects($this->any())
		->method('getHouseSourceSellPremier')
		->with($whereConds)
		->will($this->returnValue($resList));
		
		$upRes = array ( 'data' => array ( 0 => array ( 'puid' => '97988974', 'newvalue' => '5000000', 'oldvalue' => '4000000', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj->expects($this->any())
		->method('getHouseModifyRecordByPuid')
		->with($resList['data'][0]['puid'])
		->will($this->returnValue($upRes));
		
		$resPv = array('data'=>array(array('house_id'=>$resList['data'][0]['house_id'],'ClickCount'=>10)), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$houseIdArr =array($resList['data'][0]['house_id']);
		$obj->expects($this->any())
		->method('getYesterdayPvByHouseId')
		->with($whereConds['owner_account_id'], $houseIdArr)
		->will($this->returnValue($resPv));
		
		$res = $obj->getHouseListByPuid($whereConds);
		$resList['data'][0]['ClickCount'] = $resPv['data'][0]['ClickCount'];
		$resList['data'][0]['oldprice'] = $upRes['data'][0]['oldvalue'];
		$this->data['data']['list'] = $resList['data'];
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseSourceSellPremier(){
		$whereConds = array ( 'user_id' => '500008706', 'owner_account_id' => 1002772, 'page' => 1, 'pageSize' => 10, 'puid' => array ( 0 => '97988974', 1 => '97985672', 2 => '97985672', 3 => '97985672', 4 => '97985672', 5 => '97985672', 6 => '97985672', 7 => '97985672', 8 => '97985672', 9 => '97985672', 10 => '97985672', 11 => '97985672', 12 => '97985672', 13 => '97985672', 14 => '97985672', 15 => '97985672', 16 => '96103723', 17 => '96103723', 18 => '96103723', 19 => '96103723', 20 => '96103723', ), );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseWhere"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseWhere')
		->with($whereConds)
		->will($this->returnValue(array()));
		
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$res = $obj->getHouseSourceSellPremier($whereConds);
		$this->assertEquals($this->data,$res);
		
		$resPremier = array ( 0 => array ( 'house_id' => '69171452', 'title' => '100%真房源帖子1', 'type' => '5', 'puid' => '96103723', 'account_id' => '123328', 'user_id' => '0', 'xiaoqu' => '潍坊八村', 'xiaoqu_id' => '0', 'district_id' => '-1', 'district_name' => '', 'street_id' => '-1', 'street_name' => '', 'image_count' => '8', 'price' => '1300000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => '0', 'huxing_shi' => '1', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '6', 'area' => '35.00', ),);
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseWhere","getPageListByRes","getHouseSourceListByPuids"),array(),'',true);
		
		$arrConds = array ( 0 => 'puid in ( 97988974,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,96103723,96103723,96103723,96103723,96103723 )', );
		$obj->expects($this->any())
		->method('getHouseWhere')
		->with($whereConds)
		->will($this->returnValue($arrConds));
		
		$resPageList = array ( 0 => array ( 0 => '96103723', ), 1 => array ( 96103723 => array ( 'house_id' => '69171452', 'title' => '100%真房源帖子1', 'type' => '5', 'puid' => '96103723', 'account_id' => '123328', 'user_id' => '0', 'xiaoqu' => '潍坊八村', 'xiaoqu_id' => '0', 'district_id' => '-1', 'district_name' => '', 'street_id' => '-1', 'street_name' => '', 'image_count' => '8', 'price' => '1300000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => '0', 'huxing_shi' => '1', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '6', 'area' => '35.00', ), ), );
		$obj->expects($this->any())
		->method('getPageListByRes')
		->with($whereConds['page'],$whereConds['pageSize'],$resPremier)
		->will($this->returnValue($resPageList));
		
		$resList = array ( 'data' => array ( 0 => array ( 'house_id' => '69171452', 'puid' => '96103723', 'user_code' => 'A104206', 'history_count' => '0', 'yesterday_count' => '0', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj->expects($this->any())
		->method('getHouseSourceListByPuids')
		->with($resPageList[0])
		->will($this->returnValue($resList));
		
		$objPremier = $this->genObjectMock("Dao_Housepremier_HouseSourceSellPremier", array("select"));
		$objPremier->expects($this->any())
		->method('select')
		->with($this->arrPremierFields, $arrConds)
		->will($this->returnValue($resPremier));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceSellPremier", $objPremier);
		
		$res = $obj->getHouseSourceSellPremier($whereConds);
		$this->data['count'] = count($resPremier);
		$this->data['data'][0] = array_merge($resPremier[0],$resList['data'][0]);
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseSourceSellPremierByPuids(){
		$obj = new Service_Data_Source_HouseRealQuery;
		$puidArr = array(98056202);
		$puids = implode(',',$puidArr);
		$arrPuidConds[] = "puid in ( $puids )";
		$resPremier = array ( 0 => array ( 'house_id' => '69171914', 'title' => '测试试试。。。。', 'type' => '5', 'puid' => '98056202', 'account_id' => '1002790', 'user_id' => '0', 'xiaoqu' => 'Master领寓', 'xiaoqu_id' => '8568', 'district_id' => '1', 'district_name' => '朝阳', 'street_id' => '3', 'street_name' => '国展', 'image_count' => '4', 'price' => '1200000', 'fiveyears' => '1', 'only_house' => '1', 'elevator' => NULL, 'huxing_shi' => '2', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '5', 'area' => '60.00', ), );
		$objPremier = $this->genObjectMock("Dao_Housepremier_HouseSourceSellPremier", array("select"));
		$objPremier->expects($this->any())
		->method('select')
		->with($this->arrPremierFields, $arrPuidConds)
		->will($this->returnValue($resPremier));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceSellPremier", $objPremier);
		$this->data['data'] = $resPremier;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$res = $obj->getHouseSourceSellPremierByPuids($puidArr);
		$this->assertEquals($this->data,$res);
		

		$puidArr = array();
		$res = $obj->getHouseSourceSellPremier($puidArr);
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseSourceList(){
		$whereConds = array ( 'user_id' => '500008706', 'owner_account_id' => 1002772, 'page' => 1, 'pageSize' => 10, 'puid' => array ( 0 => '97988974', 1 => '97985672', 2 => '97985672', 3 => '97985672', 4 => '97985672', 5 => '97985672', 6 => '97985672', 7 => '97985672', 8 => '97985672', 9 => '97985672', 10 => '97985672', 11 => '97985672', 12 => '97985672', 13 => '97985672', 14 => '97985672', 15 => '97985672', 16 => '96103723', 17 => '96103723', 18 => '96103723', 19 => '96103723', 20 => '96103723', ), );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseWhere"),array(),'',true);
		$obj->expects($this->any())
		->method('getHouseWhere')
		->with($whereConds)
		->will($this->returnValue(array()));
		
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$res = $obj->getHouseSourceList($whereConds);
		$this->assertEquals($this->data,$res);
		
		$resList = array ( 0 => array ( 'house_id' => '69171927', 'puid' => '98058759', 'user_code' => 'a3', 'history_count' => '0', 'yesterday_count' => '0', ), );
		$obj = $this->genObjectMock("Service_Data_Source_HouseRealQuery", array("getHouseWhere","getPageListByRes","getHouseSourceSellPremierByPuids"),array(),'',true);
		
		$arrConds = array ( 0 => 'puid in ( 97988974,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,97985672,96103723,96103723,96103723,96103723,96103723 )', );
		$obj->expects($this->any())
		->method('getHouseWhere')
		->with($whereConds)
		->will($this->returnValue($arrConds));
		
		$resPageList = array ( 0 => array ( 0 => '98058759', ), 1 => array ( 98058759 => array ( 'house_id' => '69171927', 'puid' => '98058759', 'user_code' => 'a3', 'history_count' => '0', 'yesterday_count' => '0', ), ), );
		$obj->expects($this->any())
		->method('getPageListByRes')
		->with($whereConds['page'],$whereConds['pageSize'],$resList)
		->will($this->returnValue($resPageList));
		
		$resPremier = array ( 'data' => array ( 0 => array ( 'house_id' => '69171927', 'title' => '正规一居室出售', 'type' => '5', 'puid' => '98058759', 'account_id' => '1002790', 'user_id' => '0', 'xiaoqu' => '华严北里', 'xiaoqu_id' => '2119', 'district_id' => '1', 'district_name' => '朝阳', 'street_id' => '72', 'street_name' => '健翔桥', 'image_count' => '4', 'price' => '5000000', 'fiveyears' => '1', 'only_house' => '1', 'elevator' => '2', 'huxing_shi' => '1', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '6', 'area' => '39.00', ), ), 'errorno' => '0', 'errormsg' => '[数据返回成功]', );
		$obj->expects($this->any())
		->method('getHouseSourceSellPremierByPuids')
		->with($resPageList[0])
		->will($this->returnValue($resPremier));
		
		$objPremier = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("select"));
		$objPremier->expects($this->any())
		->method('select')
		->with($this->arrListFields, $arrConds)
		->will($this->returnValue($resList));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objPremier);
		
		$res = $obj->getHouseSourceList($whereConds);
		$this->data['count'] = count($resList);
		$this->data['data'][0] = array_merge($resList[0],$resPremier['data'][0]);
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseSourceListByPuids(){
		$obj = new Service_Data_Source_HouseRealQuery;
		$puidArr = array(98056202);
		$puids = implode(',',$puidArr);
		$arrPuidConds[] = "puid in ( $puids )";
		$resList = array ( 0 => array ( 'house_id' => '69171927', 'puid' => '98058759', 'user_code' => 'a3', 'history_count' => '0', 'yesterday_count' => '0', ), );
		$objList = $this->genObjectMock("Dao_Housepremier_HouseSourceList", array("select"));
		$objList->expects($this->any())
		->method('select')
		->with($this->arrListFields, $arrPuidConds)
		->will($this->returnValue($resList));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList", $objList);
		$this->data['data'] = $resList;
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$res = $obj->getHouseSourceListByPuids($puidArr);
		$this->assertEquals($this->data,$res);
		
		
		$puidArr = array();
		$res = $obj->getHouseSourceListByPuids($puidArr);
		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseWhere(){
		$obj = new Service_Data_Source_HouseRealQuery;
		$whereConds = array(
				'puid'=>98058759,
				'district_id'=>98058759,
				'street_id'=>98058759,
				'xiaoqu_id'=>98058759,
				'huxing_shi'=>98058759,
				'huxing_ting'=>98058759,
				'huxing_wei'=>98058759,
				'niandai_s'=>98058759,
				'niandai_e'=>98058759,
				'user_code'=>98058759,
		);
		$arrConds = array (
				'puid =' => 98058759,
				'district_id =' => 98058759,
				'street_id =' => 98058759,
				'xiaoqu_id =' => 98058759,
				'huxing_shi =' => 98058759,
				'huxing_ting =' => 98058759,
				'huxing_wei =' => 98058759,
				'niandai >=' => 98058759,
				'niandai <=' => 98058759,
				'user_code =' => 98058759,
		);
		$res = $obj->getHouseWhere($whereConds);
		$this->assertEquals($arrConds,$res);
		
		$whereConds = array(
				'puid'=>array(98058759),
				'district_id'=>98058759,
				'street_id'=>98058759,
				'xiaoqu_id'=>98058759,
				'huxing_shi'=>98058759,
				'huxing_ting'=>98058759,
				'huxing_wei'=>98058759,
				'niandai_s'=>98058759,
				'niandai_e'=>98058759,
				'user_code'=>98058759,
		);
		$arrConds = array (
				0 => 'puid in ( 98058759 )',
				'district_id =' => 98058759,
				'street_id =' => 98058759,
				'xiaoqu_id =' => 98058759,
				'huxing_shi =' => 98058759,
				'huxing_ting =' => 98058759,
				'huxing_wei =' => 98058759,
				'niandai >=' => 98058759,
				'niandai <=' => 98058759,
				'user_code =' => 98058759,
		);
		$res = $obj->getHouseWhere($whereConds);
		$this->assertEquals($arrConds,$res);
	}
	public function testGetPageListByRes(){
		$obj = new Service_Data_Source_HouseRealQuery;
		$page=1;
		$pageSize=10;
		$resPremier = array ( 0 => array ( 'house_id' => '69171452', 'title' => '100%真房源帖子1', 'type' => '5', 'puid' => '96103723', 'account_id' => '123328', 'user_id' => '0', 'xiaoqu' => '潍坊八村', 'xiaoqu_id' => '0', 'district_id' => '-1', 'district_name' => '', 'street_id' => '-1', 'street_name' => '', 'image_count' => '8', 'price' => '1300000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => '0', 'huxing_shi' => '1', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '6', 'area' => '35.00', ),);
		$resPageList = array ( 0 => array ( 0 => '96103723', ), 1 => array ( 96103723 => array ( 'house_id' => '69171452', 'title' => '100%真房源帖子1', 'type' => '5', 'puid' => '96103723', 'account_id' => '123328', 'user_id' => '0', 'xiaoqu' => '潍坊八村', 'xiaoqu_id' => '0', 'district_id' => '-1', 'district_name' => '', 'street_id' => '-1', 'street_name' => '', 'image_count' => '8', 'price' => '1300000', 'fiveyears' => '0', 'only_house' => '0', 'elevator' => '0', 'huxing_shi' => '1', 'huxing_ting' => '1', 'huxing_wei' => '1', 'chaoxiang' => '6', 'area' => '35.00', ), ), );
		$res = $obj->getPageListByRes($page=1, $pageSize=10 ,$resPremier);
		$this->assertEquals($resPageList,$resPageList);
		
		$resPremier = array();
		$res = $obj->getPageListByRes($page=1, $pageSize=10 ,$resPremier);
		$this->assertEquals($resPremier,$resPremier);
	}
	public function testGetYesterdayPvByHouseId(){
		$account_id = 123456;
		$houseIdArr = array(69171914);
		$whereConds = array(
				'account_id'=>$account_id,
				'house_id'=>$houseIdArr,
				'house_type'=>5,
				'ReportDate'=>strtotime('yesterday'),
		);
		$arrFields = array('ClickCount, HouseSourceId as house_id');
		$this->data['data'] = array('ClickCount'=>10,'house_id'=>69171914);
		$obj = $this->genObjectMock("Service_Data_HouseReport_HouseSourceReport", array("getReportByWhere"));
		$obj->expects($this->any())
		->method('getReportByWhere')
		->with($whereConds,$arrFields)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_HouseReport_HouseSourceReport", $obj);
		
		$objSer = new Service_Data_Source_HouseRealQuery;
		$res = $objSer->getYesterdayPvByHouseId($account_id, $houseIdArr);
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseModifyRecordByPuid(){
		$puid = 98056202;
		$arrConds = array(
				'puid'=>$puid,
				'fieldname'=>'price',
		);
		$arrFields = array('puid', 'newvalue', 'oldvalue');
		$orderArr = array('post_at'=>'DESC');
		$this->data['data'] = array('puid'=>98056202, 'newvalue'=>5000000, 'oldvalue'=>4000000);
		$obj = $this->genObjectMock("Service_Data_Source_HouseModifyRecord", array("getModifyRecordListByWhere"));
		$obj->expects($this->any())
		->method('getModifyRecordListByWhere')
		->with($arrConds, $arrFields, 1, 1, $orderArr)
		->will($this->returnValue($this->data));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseModifyRecord", $obj);
		
		$objSer = new Service_Data_Source_HouseRealQuery;
		$res = $objSer->getHouseModifyRecordByPuid($puid);
		$this->assertEquals($this->data,$res);
	}
	public function testGetHouseCommentPrivilegeInfo(){
		$puidArr = array(98061317,98068012,98109684,1372515535);
		$arrFields = array('puid','customer_id');
		$customerId = '3187';
		$returnData['data'] = array(array('puid'=>1372515535,'customer_id'=>1234),array('puid'=>98109684,'customer_id'=>3187));
		$returnData['errorno'] = ErrorConst::SUCCESS_CODE;
		$returnData['errormsg'] = ErrorConst::SUCCESS_MSG;
		$obj = $this->genObjectMock("Service_Data_Source_HouseCommentPrivilege", array("getHouseCommentPrivilegeInfo"));
		$obj->expects($this->any())
		->method('getHouseCommentPrivilegeInfo')
		->with($puidArr, $arrFields)
		->will($this->returnValue($returnData));
		Gj_LayerProxy::registerProxy("Service_Data_Source_HouseCommentPrivilege", $obj);
		$this->data['data'] = array(98061317,98068012,98109684);
		$objCom = new Service_Data_Source_HouseRealQuery();
		$res = $objCom->getHouseCommentPrivilegeInfo($puidArr, $customerId);
		$this->assertEquals($this->data,$res);
	}
}
