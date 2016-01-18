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
class Service_Page_RealHouse_AddOrUpdateComment
{
    protected $data;
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
     * 添加、修改评论
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput)
    {
        //新增评论
        if(empty($arrInput['comment_id'])){
            //获取评论数量
            $resCommentCount = $this->getCommentCountByWhere($arrInput['user_id']);
            if($resCommentCount['errorno']){
                return $resCommentCount;
            }
            //如果大于300条评论则不能再发布评论
            if($resCommentCount['data'] >= 300){
                return ErrorCode::returnData(2124);
            }
            //判断是否评论过该房源
            $resCommentInfo = $this->getCommentInfoByPuid($arrInput);
            if($resCommentInfo['errorno']){
                return $resCommentInfo;
            }
            if(!empty($resCommentInfo['data'])){
                return ErrorCode::returnData(2125);
            }
            //判断该条房源是否是第一次评论
            $resHouse = $this->getHouseInfoByHouseId($arrInput['house_id'],$arrInput['house_type']);
            $arrChangeRow = array();
            if( isset($resHouse['data']['puid']) && $resHouse['data']['premier_status'] == 111 ) {
                if ( empty($resHouse['data']['title']) ) {
                    //如果是第一次评论，则把评论标题当做帖子标题并且设置成上架为展示,此处必须为已上架状态才能更新premier_status为112
                    $arrChangeRow = array(
                        'title' => $arrInput['title'],
                        //'person'=>$arrInput['user_name'],
                        //'phone'=>$arrInput['user_phone'],
                    );
                }
                $arrChangeRow['premier_status'] = 112;
                $resUpdate = $this->updateHouseInfoByPuid($arrInput['puid'], $arrChangeRow);
            } elseif( $resHouse['data']['premier_status'] == 110 ) { //因为我们发现有经纪人评论非真房源的问题，所以这里加此限制，评论的房源必须是真房源已上架未展示状态，否则直接return。
                return  array('errorno'=>1009,'errormsg' => "该真房源未上架!");
            }elseif ( $resHouse['data']['premier_status'] === 0 ) {
                return array('errorno'=>1009,'errormsg' => "该房源不是真房源!");
            }

            if($resUpdate['errorno']){
            	return $resUpdate;
            }
            //插入评论
            $resComment = $this->insertHouseComment($arrInput);
            return $resComment;
        }else if(isset($arrInput['comment_id'])){//更新评论
            //修改评论
            $resComment = $this->updateHouseCommentByCommentId($arrInput);
            return $resComment;
        }
    }
    //{{{getCommentCountByWhere
    /**
     * 获取用户评论数量
     * @param $user_id
     * @return mixed
     */
    protected function getCommentCountByWhere($user_id=NULL, $puid=NULL){
    	$whereConds = array();
        if(!empty($user_id)) {
            $whereConds['user_id'] = $user_id;
        }
        if(!empty($puid)){
            $whereConds['puid'] = $puid;
        }
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objService->getCommentCountByWhere($whereConds);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getCommentInfoByPuid
    /**
     * 判断是否评论过该房源
     * @param $puid
     * @return mixed
     */
    protected function getCommentInfoByPuid($arrInput){
        $whereConds = array(
            'puid'=>$arrInput['puid'],
            'user_id'=>$arrInput['user_id']
        );
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objService->getCommentInfoByWhere($whereConds);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{getHouseInfoByHouseId
    /**
     * 获取该条房源的信息
     * @param $puid
     * @return mixed
     */
    protected function getHouseInfoByHouseId($house_id,$house_type){
        $arrFields = array('puid','title','premier_status','phone');
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_PremierQuery');
            $res = $objService->getRowByHouseId($house_id,$house_type, $arrFields);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{updateHouseInfoByPuid
    /**
     * 修改房源标题、手机号码、状态
     * @param $arrInput
     * @return mixed
     */
    protected function updateHouseInfoByPuid($puid, $arrChangeRow){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangSubmit');
            $arrChangePremier = $arrChangeRow;
            $arrChangePremier['modified_at'] = time();
            $resPremier = $objService->updateHouseSourceByPuid($arrChangePremier, $puid, 'house_source_sell_premier');
            if($resPremier['errorno']){
                return $resPremier;
            }
            $arrChangeList = $arrChangeRow;
            $arrChangeList['modified_time'] = time();
            $resList = $objService->updateHouseSourceListByPuid($arrChangeList, $puid);
            if($resList['errorno']){
                return $resList;
            }
            $this->data = $resList;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{insertHouseComment
    /**
     * 新增评论
     * @param $arrInput
     * @return mixed
     */
    protected function insertHouseComment($arrInput){
        $arrRows = array(
            'puid'  =>  $arrInput['puid'],
            'title'  =>  $arrInput['title'],
            'content'  =>  $arrInput['content'],
        	'house_id' => $arrInput['house_id'],
        	'house_type' => $arrInput['house_type'],
            'post_at'  =>  time(),
            'ip'  =>  $arrInput['ip'],
        	'stat' => 1,
            'user_id'  =>  $arrInput['user_id'],
            'user_name'  =>  $arrInput['user_name'],
            'user_phone'  =>  $arrInput['user_phone'],
            'owner_user_id'  =>  $arrInput['owner_user_id'],
        );
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objService->insertHouseComment($arrRows);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{updateHouseCommentByCommentId
    /**
     * 修改评论
     * @param $arrInput
     * @return mixed
     */
    protected function updateHouseCommentByCommentId($arrInput){
    	$arrRows = array();
        if(!isset($arrInput['stat'])){
            $arrRows = array(
                'title'  =>  $arrInput['title'],
                'content'  =>  $arrInput['content'],
                'modified_at'  =>  time(),
            );
        }else{
        	//获取房源状态
        	$resHouse = $this->getHouseInfoByHouseId($arrInput['house_id'],$arrInput['house_type']);
            //获取评论数量
            $resCommentCount = $this->getCommentCountByWhere(NULL, $arrInput['puid']);
            if($resCommentCount['errorno']){
                return $resCommentCount;
            }else if(!$resHouse['errorno'] && $resHouse['data']['premier_status'] == 112 && $resCommentCount['data']==1 && empty($resHouse['data']['phone'])){
                //如果只剩下一条评论，删除评论之前先把房源状态修改成上架未展示
                $arrChangeRow = array(
                    'premier_status'=>111
                );
                $resUpdate = $this->updateHouseInfoByPuid($arrInput['puid'], $arrChangeRow);
                if($resUpdate['errorno']){
                    return $resUpdate;
                }
                //记录操作记录
                $resOperation = $this->addSourceOperation($arrInput);
                if($resOperation['errorno']){
                	return $resOperation;
                }
            }
            $arrRows = array(
                'stat'=>$arrInput['stat'],
                'modified_at'  =>  time(),
            );
        }
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objService->updateHouseCommentByCommentId($arrInput['comment_id'],$arrRows);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}
    //{{{addSourceOperation
    /**
     * 记录操作记录
     * @param unknown $arrInput
     * @return Ambigous <multitype:, string, unknown>
     */
    protected function addSourceOperation($arrInput){
    	try {
    		$objService = Gj_LayerProxy::getProxy('Service_Data_Source_PremierSourceOperation');
    		$res = $objService->addSourceOperation($arrInput['house_id'],$arrInput['house_type'],$arrInput['account_id'],$arrInput['strOp'],'',$arrInput['city_id']);
    		if($res===false){
    			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    			$this->data['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
    		}else{
    			$this->data['data'] = $res;
    		}
    	}catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	return $this->data;
    }//}}}
}
