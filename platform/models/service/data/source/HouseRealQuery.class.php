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
class Service_Data_Source_HouseRealQuery{
    protected $data;
    protected $arrListFields;
    protected $arrPremierFields;
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->arrListFields = array('house_id', 'puid', 'user_code', 'history_count', 'yesterday_count');
        $this->arrPremierFields = array('house_id','title', 'type', 'puid', 'account_id', 'user_id', 'xiaoqu', 'xiaoqu_id', 'district_id', 'district_name', 'street_id', 'street_name', 'image_count', 'price', 'fiveyears', 'only_house', 'elevator', 'huxing_shi', 'huxing_ting', 'huxing_wei','chaoxiang','area');
    }
    //{{{__call
    public function __call($name,$args){
    	if (Gj_LayerProxy::$is_ut === true) {
    		return  call_user_func_array(array($this,$name),$args);
    	}
    }//}}}
    //{{{getHouseRealCommentByAccountId
    /**
     * 获取评论过的房源
     * @param $user_id   当前登录帮帮后台的user_id
     * @return mixed
     */
    protected function getHouseRealCommentByUserId($user_id, $owner_user_id){
        $whereConds = array(
            'user_id'=>$user_id,
        	'owner_user_id'=>$owner_user_id,
        );
        $arrFields = array('puid');
        try {
            $objDaoHouseComment = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objDaoHouseComment->getCommentListByWhere($whereConds,$arrFields,1,NULL);
            if($res['errorno']){
                return $res;
            }
            $puid_ids = array();
            if(!empty($res['data']) && function_exists(array_column)){
                $puid_ids = array_column($res['data'],'puid');
            } else if (!empty($res['data'])) {
                foreach ($res['data'] as $row){
                    $puid_ids[] = $row['puid'];
                }
            }
            $this->data['data'] = $puid_ids;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseListByWhere
    /**
     * 获取该公司上架或下架的房源列表
     * @param $account_id   当前登录帮帮后台所属公司的account_id
     * @return mixed
     */
    protected function getHouseListByWhere($account_id,$premier_status,$listing_status=NULL,$conds=NULL){
        $arrConds = array(
            'premier_status'=>$premier_status,
            'account_id'=> $account_id,
        );
        if(!empty($listing_status)){
        	$arrConds['listing_status'] = $listing_status;
        }
        if (!empty($conds)) {
        	$arrConds = array(
        			'conds'=>$conds,
        			'account_id'=> $account_id
        	);
        }
        $arrConds['type'] = 5;
        try {
            $objServicePremierQuery = Gj_LayerProxy::getProxy('Service_Data_Source_PremierQuery');
            $resHouse = $objServicePremierQuery->getTuiguangHouseByAccountId($arrConds, array(), 1, NULL);
            if($resHouse['errorno']){
                return $resHouse;
            }
            $puid_ids = array();
            if(!empty($resHouse['data']) && function_exists(array_column)){
                $puid_ids = array_column($resHouse['data'],'puid');
            } else if (!empty($resHouse['data'])) {
                foreach ($resHouse['data'] as $row){
                    $puid_ids[] = $row['puid'];
                }
            }
            $this->data['data'] = $puid_ids;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    
    //{{{getNoCommentHouseList
    /**
     * 未评论房源列表
     * @param $whereConds
     */
    public function getNoCommentHouseList($whereConds){
        $commentRes = $this->getHouseRealCommentByUserId($whereConds['user_id'], $whereConds['owner_user_id']);
        if($commentRes['errorno']){
            return $commentRes;
        }
        $premier_status = array(111,112);
        $listing_status = 1;
        $houseRes = $this->getHouseListByWhere($whereConds['owner_account_id'],$premier_status,$listing_status);
        if($houseRes['errorno']){
            return $houseRes;
        }
        $puidArr = array();
        if(empty($commentRes['data']) && !empty($houseRes['data'])){
            $puidArr = $houseRes['data'];
        }else if(empty($houseRes['data'])){
            return $houseRes;
        }else{
            $puidArr = array_diff($houseRes['data'], $commentRes['data']);
        }
        if(count($puidArr) <= 0){
            $this->data['data'] = array();
        }else{
        	//获取允许评论的房源
        	$resPuidArr = $this->getHouseCommentPrivilegeInfo($puidArr,$whereConds['customer_id']);
        	if($resPuidArr['errorno']){
        		return $resPuidArr;
        	}
        	
            $whereConds['puid'] = $resPuidArr['data'];
            $this->data =  $this->getHouseListByPuid($whereConds);
            $this->data['data']['count'] = count($puidArr);
        }
        return $this->data;
    }//}}}
    //{{{getCommentHouseList
    /**
     * 评论房源列表
     * @param $whereConds
     */
    public function getCommentHouseList($whereConds){
        $commentRes = $this->getHouseRealCommentByUserId($whereConds['user_id'], $whereConds['owner_user_id']);
        if($commentRes['errorno'] || count($commentRes['data']) <= 0){
            return $commentRes;
        }
        $premier_status = 110;
        $conds = "premier_status = {$premier_status} or listing_status != 1";
        $houseRes = $this->getHouseListByWhere($whereConds['owner_account_id'],NULL,NULL,$conds);
        if($houseRes['errorno']){
        	return $houseRes;
        }
    	$puidArr = array();
        if(!empty($commentRes['data']) && empty($houseRes['data'])){
            $puidArr = $commentRes['data'];
        }else{
            $puidArr = array_diff($commentRes['data'], $houseRes['data']);
        }
        if(count($puidArr) <= 0){
            $this->data['data'] = array();
        }else{
            $whereConds['puid'] = $puidArr;
            $this->data =  $this->getHouseListByPuid($whereConds);
            $this->data['data']['count'] = count($puidArr);
        }
        /* $whereConds['puid'] = $commentRes['data'];
        $this->data =  $this->getHouseListByPuid($whereConds);
        $this->data['data']['count'] = count($commentRes['data']); */
        return $this->data;
    }//}}}
    //{{{getDelCommentHouseList
    /**
     * 已评论下架的房源
     * @param $whereConds
     */
    public function getDelCommentHouseList($whereConds){
        $commentRes = $this->getHouseRealCommentByUserId($whereConds['user_id'], $whereConds['owner_user_id']);
        if($commentRes['errorno'] || empty($commentRes['data'])){
            return $commentRes;
        }
        $premier_status = 110;
        $conds = "premier_status = {$premier_status} or listing_status != 1";
        $houseRes = $this->getHouseListByWhere($whereConds['owner_account_id'],NULL,NULL,$conds);
        //$houseRes = $this->getHouseListByWhere($whereConds['owner_account_id'], $premier_status);
        if($houseRes['errorno'] || empty($houseRes['data'])){
            return $houseRes;
        }
        $puidArr = array();
        $puidArr = array_intersect($houseRes['data'], $commentRes['data']);
        if(count($puidArr) <= 0){
            $this->data['data'] = array();
        }else{
            $whereConds['puid'] = $puidArr;
            $this->data = $this->getHouseListByPuid($whereConds);
            $this->data['data']['count'] = count($puidArr);
        }
        return $this->data;
    }//}}}
    //{{{getHouseListByPuid
    /**
     * 获取房源列表
     * @param unknown $whereConds
     * @return Ambigous <string, unknown, multitype:, number, multitype:unknown >|Ambigous <multitype:, string, number, unknown, multitype:unknown >
     */
    protected function getHouseListByPuid($whereConds){
        if(!empty($whereConds['user_code'])){
            $resList = $this->getHouseSourceList($whereConds);
        }else{
            $resList = $this->getHouseSourceSellPremier($whereConds);
        }
        if($resList['errorno'] || empty($resList['data'])){
            return $resList;
        }
        $houseIdArr = array();
        $resNewList = array();
        if(!empty($resList['data']) && function_exists(array_column)){
            $houseIdArr = array_column($resList['data'], 'house_id');
            $resNewList = array_column($resList['data'], NULL, 'house_id');
        } else if (!empty($resList['data'])) {
            foreach ($resList['data'] as $row){
                $houseIdArr[] = $row['house_id'];
                $resNewList[$row['house_id']] = $row;
            }
        }
        //房源价格修改
        foreach($resList['data'] as $key=>$row){
            $upRes = $this->getHouseModifyRecordByPuid($row['puid']);
            if($upRes['errorno'] || empty($upRes['data'])){
                continue;
            }
            $resList['data'][$key]['oldprice'] = $upRes['data'][0]['oldvalue'];
            $resNewList[$row['house_id']]['oldprice'] = $upRes['data'][0]['oldvalue'];
        }
        //获取昨日点击量
        $resPv = $this->getYesterdayPvByHouseId($whereConds['owner_account_id'], $houseIdArr);
        //$resPv['data'] = array(array('house_id'=>69171852,'ClickCount'=>10));
        if($resPv['errorno'] || empty($resPv['data'])){
            $this->data['data']['list'] = $resList['data'];
            return $this->data;
        }
        $pvArr = array();
        if(!empty($resPv['data']) && function_exists(array_column)){
        	$pvArr = array_column($resPv['data'], NULL, 'house_id');
        } else if (!empty($resPv['data'])) {
        	foreach ($resPv['data'] as $row){
        		$pvArr[$row['house_id']] = $row;
        	}
        }
        foreach($resNewList as $key=>$row){
            if(is_array($pvArr[$key])) {
                $this->data['data']['list'][] = array_merge($row, $pvArr[$key]);
            }else{
                $this->data['data']['list'][] = $row;
            }
        }
        return $this->data;
    }//}}}
    //{{{getHouseSourceSellPremier
    /**
     * 获取详情表数据
     * @param unknown $whereConds
     * @return Ambigous <multitype:, string, number, Ambigous, unknown, multitype:unknown >|unknown|Ambigous <string, multitype:, number, Ambigous, unknown, multitype:unknown >
     */
    protected function getHouseSourceSellPremier($whereConds){
        $arrConds = $this->getHouseWhere($whereConds);
        if(!count($arrConds)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try {
            /**
             * @var Dao_Housepremier_HouseSourceSellPremier
             */
            $objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceSellPremier");
            $res = $objDao->select($this->arrPremierFields, $arrConds);
            if(empty($res)){
                return $res;
            }
            $resPageList = $this->getPageListByRes($whereConds['page'],$whereConds['pageSize'],$res);
            $puidArr = $resPageList[0];
            $resNewList = $resPageList[1];
            $resList = $this->getHouseSourceListByPuids($puidArr);
            if($resList['errorno']){
                return $resList;
            }
            $this->data['data'] = array();
            $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
            $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
            foreach($resList['data'] as $row){
                if(is_array($resNewList[$row['puid']])) {
                    $this->data['data'][] = array_merge($row, $resNewList[$row['puid']]);
                }else{
                	$this->data['data'][] = $resNewList[$row['puid']];
                }
            }
            $this->data['count'] = count($res);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseSourceSellPremierByPuids
    /**
     * 根据puid获取详细信息
     * @param unknown $puidArr
     * @return string|Ambigous <multitype:, string, number, Ambigous, unknown, multitype:unknown >
     */
    protected function getHouseSourceSellPremierByPuids($puidArr){
        if(!is_array($puidArr) || count($puidArr)<=0){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        $puids = implode(',',$puidArr);
        $arrPuidConds[] = "puid in ( $puids )";
        try {
            //获取房源详细信息
            /**
             * @var Dao_Housepremier_HouseSourceSellPremier
             */
            $objDaoPremier = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceSellPremier");
            $resPremier = $objDaoPremier->select($this->arrPremierFields, $arrPuidConds);
            $this->data['data'] = $resPremier;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseSourceList
    /**
     * 获取列表中的房源信息，主要用于搜索user_code
     * @param unknown $whereConds
     * @return Ambigous <multitype:, string, number, Ambigous, unknown, multitype:unknown >|unknown|Ambigous <string, Ambigous, multitype:, number, unknown, multitype:unknown >
     */
    protected function getHouseSourceList($whereConds){
        $arrConds = $this->getHouseWhere($whereConds);
        if(!count($arrConds)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try {
            /**
             * @var Dao_Housepremier_HouseSourceList
             */
            $objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
            $res = $objDao->select($this->arrListFields, $arrConds);
            if(empty($res)){
                return $res;
            }
            $resPageList = $this->getPageListByRes($whereConds['page'],$whereConds['pageSize'],$res);
            $puidArr = $resPageList[0];
            $resNewList = $resPageList[1];
            $resPremier = $this->getHouseSourceSellPremierByPuids($puidArr);
            if($resPremier['errorno']){
                return $resPremier;
            }
            $this->data['data'] = array();
            $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
            $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
            foreach($resPremier['data'] as $row){
                if(is_array($resNewList[$row['puid']])) {
                    $this->data['data'][] = array_merge($row, $resNewList[$row['puid']]);
                }
            }
            $this->data['count'] = count($res);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseSourceListByPuids
    /**
     * 根据puid获取列表中的总点击量
     * @param unknown $puidArr
     * @return string|Ambigous <multitype:, string, number, Ambigous, unknown, multitype:unknown >
     */
    protected function getHouseSourceListByPuids($puidArr){
        if(!is_array($puidArr) || count($puidArr)<=0){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try {
            $puids = implode(',', $puidArr);
            $arrPuidConds[] = "puid in ( $puids )";
            /**
             * @var Dao_Housepremier_HouseSourceList
             */
            $objListDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
            $resList = $objListDao->select($this->arrListFields, $arrPuidConds);
            $this->data['data'] = $resList;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseWhere
    /**
     * 组装查询条件
     * @param $whereConds
     * @return array
     */
    protected function getHouseWhere($whereConds){
        $arrConds = array();
        if(!empty($whereConds['puid'])){
            if(is_array($whereConds['puid'])){
                $puids = implode(',',$whereConds['puid']);
                $arrConds[] = "puid in ( $puids )";
            }else{
                $arrConds['puid ='] = $whereConds['puid'];
            }
        }
        if(isset($whereConds['district_id']) && intval($whereConds['district_id'])>=0){
            $arrConds['district_id ='] = $whereConds['district_id'];
        }
        if(isset($whereConds['street_id']) && intval($whereConds['street_id'])>=0){
            $arrConds['street_id ='] = $whereConds['street_id'];
        }
        if(!empty($whereConds['xiaoqu_id'])){
            $arrConds['xiaoqu_id ='] = $whereConds['xiaoqu_id'];
        }
        if(!empty($whereConds['huxing_shi'])){
            $arrConds['huxing_shi ='] = $whereConds['huxing_shi'];
        }
        if(!empty($whereConds['huxing_ting'])){
            $arrConds['huxing_ting ='] = $whereConds['huxing_ting'];
        }
        if(!empty($whereConds['huxing_wei'])){
            $arrConds['huxing_wei ='] = $whereConds['huxing_wei'];
        }
        if(!empty($whereConds['niandai_s'])){
            $arrConds['niandai >='] = $whereConds['niandai_s'];
        }
        if(!empty($whereConds['niandai_e'])){
            $arrConds['niandai <='] = $whereConds['niandai_e'];
        }
        if(!empty($whereConds['land_tenure'])){
        	$arrConds['land_tenure ='] = $whereConds['land_tenure'];
        }
        if(!empty($whereConds['user_code'])){
            $arrConds['user_code ='] = $whereConds['user_code'];
        }
        return $arrConds;
    }//}}}
    //{{{getPageListByRes
    /**
     * 获取分页条件
     * @param int $page
     * @param int $pageSize
     * @param $res
     * @return array
     */
    protected function getPageListByRes($page=1, $pageSize=10 ,$res){
        if(empty($res)){
            return $res;
        }
        $currentPage = ($page - 1 <= 0)?0:$page - 1;
        $offset = $currentPage * $pageSize;
        $resPageList = array_slice($res, $offset, $pageSize);
        $puidArr = array();
        $resNewList = array();
        if(!empty($resPageList) && function_exists(array_column)){
        	$puidArr = array_column($resPageList, 'puid');
        	$resNewList = array_column($resPageList, NULL, 'puid');
        } else if (!empty($resPageList)) {
        	foreach ($resPageList as $row){
        		$puidArr[] = $row['puid'];
        		$resNewList[$row['puid']] = $row;
        	}
        }
        return array($puidArr,$resNewList);
    }//}}}
    //{{{getYesterdayPvByHouseId
    /**
     * 获取昨天上架房源的昨日点击量
     * @param $account_id
     * @param $houseIdArr
     * @return mixed
     */
    protected function getYesterdayPvByHouseId($account_id, $houseIdArr){
        $whereConds = array(
            'account_id'=>$account_id,
            'house_id'=>$houseIdArr,
            'house_type'=>5,
            'ReportDate'=>strtotime('yesterday'),
        );
        $arrFields = array('ClickCount, HouseSourceId as house_id');
        try {
            /**
             * @var Service_Data_HouseReport_HouseSourceReport
             */
            $objServiceHouseSourceReport = Gj_LayerProxy::getProxy('Service_Data_HouseReport_HouseSourceReport');
            $res = $objServiceHouseSourceReport->getReportByWhere($whereConds,$arrFields);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseModifyRecordByPuid
    /**
     * 获取修改最后一次修改房源价格
     * @param $puid
     * @return mixed
     */
    protected function getHouseModifyRecordByPuid($puid){
        try {
            $arrConds = array(
                'puid'=>$puid,
                'fieldname'=>'price',
            );
            $arrFields = array('puid', 'newvalue', 'oldvalue');
            $orderArr = array('post_at'=>'DESC');
            /**
             * @var Service_Data_Source_HouseModifyRecord
             */
            $objService = Gj_LayerProxy::getProxy("Service_Data_Source_HouseModifyRecord");
            $res = $objService->getModifyRecordListByWhere($arrConds, $arrFields, 1, 1, $orderArr);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseCommentPrivilegeInfo
    /**
     * 获取能够评论的房源
     * @param unknown $puidArr
     * @param unknown $customerId
     * @return Ambigous <multitype:, unknown, string, number, Ambigous, multitype:unknown >
     */
    protected function getHouseCommentPrivilegeInfo($puidArr,$customerId){
    	try {
    		$arrFields = array('puid','customer_id');
            /**
    		 * @var Service_Data_Source_HouseCommentPrivilege
    		*/
    		$objService = Gj_LayerProxy::getProxy("Service_Data_Source_HouseCommentPrivilege");
    		$res = $objService->getHouseCommentPrivilegeInfo($puidArr, $arrFields);
            $resPuidArr = array();
    		if(!$res['errorno'] && !empty($res['data'])){
	    		foreach ($res['data'] as $row){
	    			if ($row['customer_id'] != $customerId) {
	    				$resPuidArr[] = $row['puid'];
	    			}
	    		}
    		}
    		$this->data['data'] = $puidArr;
    		if (!empty($resPuidArr)) {
    			$this->data['data'] = array_diff($puidArr, $resPuidArr);
    		}
    	}catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	return $this->data;
    }//}}}
}
