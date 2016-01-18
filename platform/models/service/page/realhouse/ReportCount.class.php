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
class Service_Page_RealHouse_ReportCount{
    protected $data;
    //{{{__construct
    /**
     * 默认构造方法
     * @codeCoverageIgnore
     */
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }//}}}
    //{{{__call
    /**
     * @codeCoverageIgnore
     * @param $name
     * @param $args
     * @return mixed
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }//}}}
    //{{{execute
    /**
     * 获取100%真房源统计信息
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput){
        if(!isset($arrInput['account_id']) || !is_numeric($arrInput['account_id'])){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            //获取今日上架的房源puid
            $resTodayArr = $this->getOneDayByWhere($arrInput['owner_account_id']);
            if($resTodayArr['errorno']){
                return $resTodayArr;
            }
            $newHouseCount = empty($resTodayArr['data']['puids'])?0:count($resTodayArr['data']['puids']);
            //获取已经评论的房源
            $resHouseCommentArr = $this->getHouseCommentArr($resTodayArr['data']['puids'], $arrInput['user_id']);
            if($resHouseCommentArr['errorno']){
            	return $resHouseCommentArr;
            }
            $commentHouse = empty($resHouseCommentArr['data']['puid_ids'])?0:count($resHouseCommentArr['data']['puid_ids']);
            //获取待评论房源puid
            $noCommentHousePuidArr = array();
            $noCommentHouse = 0;
            if(empty($resTodayArr['data']['puids'])){
            	$noCommentHouse = 0;
            }else if(empty($resHouseCommentArr['data']['puid_ids'])){
            	$noCommentHousePuidArr = $resTodayArr['data']['puids'];
            }else if(!empty($resTodayArr['data']['puids']) && !empty($resHouseCommentArr['data']['puid_ids'])){
            	$noCommentHousePuidArr = array_diff($resTodayArr['data']['puids'], $resHouseCommentArr['data']['puid_ids']);
            }
            //根据评论房源puid，查看房源是否处于上架状态
            if(count($noCommentHousePuidArr)){
            	$resNoComment = $this->getHouseListArr($noCommentHousePuidArr);
            	$noCommentHouse = empty($resNoComment['data']['puid_ids'])?0:count($resNoComment['data']['puid_ids']);
            }
            $this->data['data'] = array(
                'noCommentHouse'   => $noCommentHouse,
                'commentHouse'     => $commentHouse,
                'newHouse'          =>$newHouseCount,
            );
            //获取昨日评论房源的点击量
            $housePv = $this->getHousePvByAccountId($arrInput['owner_account_id']);
            if($housePv['errorno']){
            	return $housePv;
            }
            $this->data['data']['yesterdayPv'] = $housePv['data']['yesterdayPv'];
            $this->data['data']['houseTotalPv'] = $housePv['data']['houseTotalPv'];
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseCommentArr
    /**
     * 获取已经评论的房源
     * @param $resPuidArr
     * @return mixed
     */
    protected function getHouseCommentArr($resPuidArr, $user_id){
        /**
         * @var Service_Data_Source_HouseRealComment
         */
        $whereConds = array(
            'puid'=>$resPuidArr,
        	'user_id'=>$user_id,
        	's_post_at'=>strtotime(today),
        	'e_post_at'=>strtotime(Tomorrow)-1,
        );
        $arrFields = array("house_id","puid");
        try{
            $objServiceHouseRealComment = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objServiceHouseRealComment->getCommentListByWhere($whereConds, $arrFields, 1, NULL);
            $this->data['data'] = $this->getHouseArrByRes($res);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getOneDayByWhere
    /**
     * 获取今日上架的房源puid
     * @param $user_post_num_id
     * @return mixed
     */
    protected function getOneDayByWhere($owner_account_id){
        //获取今日上架的房源puid
        $whereConds = array(
            'account_id'=>$owner_account_id,
            's_post_at'=>strtotime('today'),
            'e_post_at'=>time(),
        );
        try{
            $objDaoFangHistoryPv = Gj_LayerProxy::getProxy('Service_Data_Source_FangHistoryPv');
            $resTodayArr = $objDaoFangHistoryPv->getOneDayByWhere($whereConds);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        return $resTodayArr;
    }//}}}
    //{{{getHousePvByAccountId
    /**
     * 获取昨日评论房源的点击量
     * @param $account_id
     * @return mixed
     */
    protected function getHousePvByAccountId($owner_account_id){
        try{
            $objDaoFangHistoryPv = Gj_LayerProxy::getProxy('Service_Data_Source_FangHistoryPv');
            $housePv = $objDaoFangHistoryPv->getHousePvByAccountIdByCache($owner_account_id);
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        return $housePv;
    }//}}}
    //{{{getHouseListArr
    /**
     * 获取待评论房源
     * @param unknown $puidArr
     * @return Ambigous <multitype:, string, multitype:number , unknown, multitype:multitype: NULL >
     */
    protected function getHouseListArr($puidArr){
    	/**
    	 * @var Service_Data_Source_PremierQuery
    	 */
    	$arrFields = array("house_id","puid");
    	/* $puids = implode(',', $puidArr);
    	$premier_status = array(111,112);
    	$listing_status = 1; */
    	$whereConds = array(
    			'listing_status'=>1,
    			'premier_status'=>array(111,112),
    			'puid'=>$puidArr,
    	);

    	$objService = Gj_LayerProxy::getProxy('Service_Data_Source_PremierQuery');
    	try{
    		//$res = $objService->getHouseInfoByPuid($puidArr, $premier_status, $listing_status, $arrFields);
    		$res = $objService->getTuiguangHouseByAccountId($whereConds, $arrFields);
    		$this->data['data'] = $this->getHouseArrByRes($res);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	return $this->data;
    }//}}}
    //{{{getHouseArrByRes
    /**
     * 数据归类
     * @param unknown $res
     * @return unknown|Ambigous <multitype:multitype: NULL , unknown>
     * @codeCoverageIgnore 
     */
    protected function getHouseArrByRes($res){
    	$houseArr['puid_ids'] = array();
    	$houseArr['house_ids'] = array();
    	if(!empty($res['data']) && function_exists(array_column)){
    		$houseArr['puid_ids'] = array_column($res['data'],'puid');
    		$houseArr['house_ids'] = array_column($res['data'],'house_id');
    	} else if (!empty($res['data'])) {
    		foreach ($res['data'] as $row){
    			$houseArr['puid_ids'][] = $row['puid'];
    			$houseArr['house_ids'][] = $row['house_id'];
    		}
    	}
    	return $houseArr;
    }//}}}
}
