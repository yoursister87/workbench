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
class Service_Data_Source_HouseRealComment{
    protected $data;
    /**
     * @var Dao_Housepremier_HouseRealComment
     */
    protected $objDaoHouseComment;
    protected $arrFields = array("comment_id","house_id","house_type","puid","title","content","puid","post_at","modified_at","ip","user_id","user_name","user_phone","owner_user_id","stat");
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->objDaoHouseComment = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseRealComment');
    }
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    //{{{getCommentListByWhere
    /**获取评论列表
     * @param $whereConds           获取评论条件
     * @param array $arrFields      需要获取的字段
     * @param int $page             页数
     * @param int $pageSize         每页获取多少个
     * @param array $orderArr       排序字段
     * @return mixed
     */
    public function getCommentListByWhere($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        if (count($arrFields)) {
            $this->arrFields = $arrFields;
        }
        $arrConds = $this->getCommentWhere($whereConds);
        if(count($arrConds) <=1 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $res = $this->objDaoHouseComment->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoHouseComment->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{getCommentCountByWhere
    /**获取评论数量
     * @param $whereConds           获取评论条件
     * @return mixed
     */
    public function getCommentCountByWhere($whereConds){
        $arrConds = $this->getCommentWhere($whereConds);
        if(count($arrConds) <=1 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $res = $this->objDaoHouseComment->selectByCount($arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoHouseComment->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{getCommentWhere

	public function getCommentCountByGroupBy($whereConds){
		$arrConds = $this->getCommentWhere($whereConds);
		$fields = 'count(1) as count,puid';  
		try{
			$res = $this->objDaoHouseComment->selectGroupbyPuid($fields,$arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}   
		if ($res === false) {
			Gj_Log::warning($this->objDaoHouseComment->getLastSQL());
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}   
		return $this->data;
	}//}}}
    /**组装查询条件
     * @param $whereConds
     * @return array
     */
    protected function getCommentWhere($whereConds){
        $arrConds['stat ='] = 1;
        if(!empty($whereConds['comment_id'])){
            $arrConds['comment_id ='] = $whereConds['comment_id'];
        }
        if(!empty($whereConds['puid'])){
            if(is_array($whereConds['puid'])){
                $puids = implode(',',$whereConds['puid']);
                $arrConds[] = "puid in ( $puids )";
            }else{
                $arrConds['puid ='] = $whereConds['puid'];
            }
        }
        if(!empty($whereConds['user_id'])){
            if(is_array($whereConds['user_id'])){
                $user_ids = implode(',',$whereConds['user_id']);
                $arrConds[] = "user_id in ( $user_ids )";
            }else{
                $arrConds['user_id ='] = $whereConds['user_id'];
            }
        }
        if(!empty($whereConds['owner_user_id'])){
            $arrConds['owner_user_id ='] = $whereConds['owner_user_id'];
        }
        if(!empty($whereConds['s_post_at'])){
            $arrConds['post_at >='] = $whereConds['s_post_at'];
        }
        if(!empty($whereConds['e_post_at'])){
            $arrConds['post_at <='] = $whereConds['e_post_at'];
        }
		if(!empty($whereConds['length'])){
			$arrConds[]	 = $whereConds['length'];
		}
        return $arrConds;
    }//}}}
    //{{{getCommentInfoByWhere
    /**
     * 获取评论详情
     * @param unknown $whereConds
     * @param unknown $arrFields
     * @return Ambigous <multitype:, string, $ret, number, boolean, 结果数组, unknown, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getCommentInfoByWhere($whereConds, $arrFields=array()){
        if (count($arrFields)) {
            $this->arrFields = $arrFields;
        }
        $arrConds = $this->getCommentWhere($whereConds);
        if(count($arrConds) <=1 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            $res = $this->objDaoHouseComment->select($this->arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoHouseComment->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{insertHouseComment
    /**
     * 插入评论
     * @param $arrRows
     * @return mixed
     */
    public function insertHouseComment($arrRows){
        if (!is_array($arrRows)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
        	$res = $this->objDaoHouseComment->insert($arrRows);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
        	Gj_Log::warning($this->objDaoHouseComment->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
           $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{updateHouseCommentByCommentId
    /**
     * 更新评论
     * @param $comment_id
     * @param $arrChangeRow
     * @return mixed
     */
    public function updateHouseCommentByCommentId($comment_id, $arrChangeRow){
        $arrConds = array(
            'comment_id =' => $comment_id,
        );
        if (!is_array($arrChangeRow)) {
        	$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        	$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        	return $this->data;
        }
        try{
            $res = $this->objDaoHouseComment->update($arrChangeRow,$arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        Gj_Log::trace("update:comment_id=".$comment_id.":".json_encode($arrChangeRow));
        $this->data['data'] = $res;
        return $this->data;
    }//}}}
    //{{{delHouseCommentByCommentID
    /**
     * 删除评论
     * @param $comment_ids
     * @return mixed
     */
    public function delHouseCommentByCommentId($comment_ids){
        try{
            if (!is_array($comment_ids)) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            }
            foreach ($comment_ids as $comment_id){
                if (!is_numeric($comment_id)) {
                    $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                    $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                    break;
                }
                $arrConds = array(
                    'comment_id =' => $comment_id,
                );
                $res = $this->objDaoHouseComment->deleteById($arrConds);
                $whereConds = array('comment_id'=>$comment_id);
                $delData = $this->getCommentInfoByWhere($whereConds);
                Gj_Log::trace("del:".json_encode($delData));
                $this->data['data'] = $res;
                if ($res === false) {
                    $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
                    $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
                    break;
                }
            }
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
}
