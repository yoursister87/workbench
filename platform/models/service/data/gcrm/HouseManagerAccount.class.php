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
class Service_Data_Gcrm_HouseManagerAccount
{
	protected $data;
	/**
	 * @var Dao_Gcrm_HouseManagerAccount
	 */
	protected $objDaoHouseManagerAccount;
	protected $arrFields = array("id","pid","company_id","customer_id","level","create_time","status","account","password","passwd","title","name","phone");
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    	$this->objDaoHouseManagerAccount = Gj_LayerProxy::getProxy('Dao_Gcrm_HouseManagerAccount');
    }
	 public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
    }  
    //{{{ getOrgInfoListByPid 获取区域，板块，门店列表
    /**
     * 获取区域，板块，门店列表
     * @param unknown $companyId   公司id
     * @param unknown $pid               父id
     * @param unknown $level             所在层级 1:公司;2:区域;3板块;4:门店
     * @param unknown $customer_id             门店id  level等于4才有门店id
     * @param unknown $page            页数
     * @param unknown $pageSize      每页条数，page 为1 $pageSize 为 null时不限制
     * @return unknown                       查询结果
     */
    public function getOrgInfoListByPid($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
    	if (count($arrFields)) {
    		$this->arrFields = $arrFields;
    	}
    	$arrConds = $this->getOrgWhere($whereConds);
    	//$orderArr = array('DESC'=>'create_time');
        try{
    		$res = $this->objDaoHouseManagerAccount->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoHouseManagerAccount->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
	    	$this->data['data'] = $res;
    	}
    	return $this->data;
    }//}}}
    //{{{ getOrgCountListByPid 获取区域，板块，门店条数
    /**
     * 获取区域，板块，门店数量
    * @param unknown $companyId   公司id
     * @param unknown $pid               父id
     * @param unknown $level             所在层级 1:公司;2:区域;3板块;4:门店
     * @return Ambigous <multitype:, string, number, boolean, $ret, 结果数组, unknown, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getOrgCountByPid($whereConds){
    	$arrConds = $this->getOrgWhere($whereConds);
    	try{
    		$res = $this->objDaoHouseManagerAccount->selectByCount($arrConds);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	$this->data['data'] = $res;
    	return $this->data;
    }//}}}
    //{{{getOrgWhere
    /**
     * 组装查询条件
     * @param unknown $whereConds
     * @return multitype:number unknown
     */
    protected function getOrgWhere($whereConds){
    	//if (empty($whereConds['company_id']) && empty($whereConds['customer_id'])) {
        //    throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE," error:".ErrorConst::E_PARAM_INVALID_MSG);
    	//}
        if(!empty($whereConds['company_id'])){
            $arrConds = array(
                'company_id =' => $whereConds['company_id']
            );
        }

        $arrConds['status ='] = 1;
    	if(!empty($whereConds['id'])){
    		$arrConds['id ='] = $whereConds['id'];
    	}
    	if(!empty($whereConds['pid'])){
    		$arrConds['pid ='] = $whereConds['pid'];
    	}
    	if(!empty($whereConds['level'])){
    		$arrConds['level ='] = $whereConds['level'];
    	}
        /*if(!empty($whereConds['customer_id'])){
            $arrConds['customer_id ='] = $whereConds['customer_id'];
		}*/
		if(!empty($whereConds['customer_id'])){
			if(is_array($whereConds['customer_id'])){
				$customer_ids = implode(',',$whereConds['customer_id']);
				  $arrConds[] = "customer_id in ( $customer_ids )";
			}else{
				 $arrConds['customer_id ='] = $whereConds['customer_id'];	
			}
		
		}
    	if(!empty($whereConds['title'])){
    		array_push($arrConds, "title like '%".$whereConds['title']."%'");
    	}
    	return $arrConds;
    }//}}}
    //{{{ getOrgInfoById 获取组织结构信息
    /**
     * 获取组织结构信息
     * @param unknown $id              主键id
     * @param unknown $arrFileds    获取字段
     * @return unknown
     */
    public function getOrgInfoByIdOrAccount($whereConds,$arrFields=array()){
    	if (count($arrFields)) {
    		$this->arrFields = $arrFields;
    	}
    	$arrConds = array();
    	if (!empty($whereConds['id'])) {
    		$arrConds['id ='] = $whereConds['id'];
    	}
    	if(!empty($whereConds['account'])){
    		$arrConds['account ='] = $whereConds['account'];
    	}
    	if(!empty($whereConds['customer_id'])){
    		$arrConds['customer_id ='] = $whereConds['customer_id'];
    	}
    	if(!empty($whereConds['title'])){
    		$arrConds['title ='] = $whereConds['title'];
    	}
    	if(!empty($whereConds['level'])){
    		$arrConds['level ='] = $whereConds['level'];
    	}
    	if(!empty($whereConds['pid'])){
    		$arrConds['pid ='] = $whereConds['pid'];
    	}
    	if(!empty($whereConds['company_id'])){
    		$arrConds['company_id ='] = $whereConds['company_id'];
    	}
    	try {
    		$arrConds['status ='] = 1;
    		$res = $this->objDaoHouseManagerAccount->select($this->arrFields, $arrConds);
    	} catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoHouseManagerAccount->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
	    	$this->data['data'] = $res[0];
        }
    	return $this->data;
    }//}}}
    //{{{addOrg
    /**
     * 新增组织架构，成功返回插入id
     * @param unknown $arrRows
     * @return Ambigous <multitype:, string, boolean, 结果数组, unknown, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function addOrg($arrRows){
    	if (!is_array($arrRows)) {
    		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;;
    	}else{
	    	try{
	    		$res = $this->objDaoHouseManagerAccount->insert($arrRows);
    		} catch(Exception $e) {
    			$this->data['errorno'] = $e->getCode();
    			$this->data['errormsg'] = $e->getMessage();
    		}
	    	if (!$res) {
	    		Gj_Log::warning($this->objDaoHouseManagerAccount->getLastSQL());
	    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
	    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
	    	}else{
	    		$this->data['data'] = $res;
	    	}
    	}
    	return $this->data;
    }//}}}
    //{{{ modifyPassword
    /**
     * 修改密码
     * @param unknown $id				唯一标示
     * @param unknown $old_pwd	旧密码
     * @param unknown $new_pwd	新密码
     * @return Ambigous <multitype:, string, boolean, 结果数组, number, unknown, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function modifyPassword($id, $new_pwd, $old_pwd=NULL){
    	$oldData = array();
    	if (!empty($old_pwd)) {
    		$oldData = $this->getOrgInfoById($id);
    	}
    	if (empty($old_pwd) || $oldData['data']['password'] == md5($old_pwd)) {
    		$arrConds = array(
    			'id =' => $id,
    		);
    		$arrRows = array(
    			'password' =>md5($new_pwd),
    			'passwd' => $new_pwd,
    		);
    		try{
    			$res = $this->objDaoHouseManagerAccount->update($arrRows, $arrConds);
    		} catch(Exception $e) {
    			$this->data['errorno'] = $e->getCode();
    			$this->data['errormsg'] = $e->getMessage();
    		}
    		$this->data['data'] = $res;
    	}else{
    		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	}
    	return $this->data;
    }//}}}
    //{{{deleteOrgById
    /**
     * 删除组织架构
     * @param unknown $orgId		唯一标示数组
     * @return Ambigous <multitype:, string, number, boolean, 结果数组, unknown, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function deleteOrgById($orgId){
    	try{
    		if (!is_array($orgId)) {
    			throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE," error:".ErrorConst::E_PARAM_INVALID_MSG);
    		}
    		foreach ($orgId as $id){
    			if (!is_numeric($id)) {
    				$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    				$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
    				break;
    			}
    			$arrConds = array(
    				'id =' => $id,
    			);
    			$res = $this->objDaoHouseManagerAccount->deleteById($arrConds);
    			$whereConds = array('id'=>$id);
    			$delData = $this->getOrgInfoByIdOrAccount($whereConds);
    			Gj_Log::trace("del:".json_encode($delData));
    			$this->data['data'] = $res;
    			if (!$res) {
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
    }//}}}
    //{{{ updateOrgById
    /**
     * 根据id，修改组织架构信息
     * @param unknown $id
     * @param unknown $arrChangeRow
     * @return Ambigous <multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function updateOrgById($id, $arrChangeRow){
    	$arrConds = array(
    			'id =' => $id,
    	);
    	try{
    		$res = $this->objDaoHouseManagerAccount->update($arrChangeRow,$arrConds);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	Gj_Log::trace("update:id=".$id.":".json_encode($arrChangeRow));
    	$this->data['data'] = $res;
    	return $this->data;
    }//}}}
    //{{{ 获得树数型结构 getTreeByOrgId
    /* 结构图
     *  params orgid int
     *  params isfamily boolean 默认获得亲属
     *  params nextRes 回调结果 (不可传值)
     *
     *  array(
     *     '1 (level)'=>array(
     *            activeList=>array('公司'),
     *     )
     *     '2 level'=>array(
     *           array(区域一)
     *           array(区域二)
     *           activeList=>array('选中区域'),
     *     )
     *     '3 level'=>array(
     *          array(板块一)
     *          array(板块二)
     *          array(板块三)
     *          activeList=>array('选中板块'),
     *     )
     *     '4 level'=>array(
     *          array('门店一')
     *          array('门店一')
     *          activeList=>array('选中门店')
     *     ) 
     *  )
     */
    public function getTreeByOrgId($id ,$isfamily = true,$nextRes = array(),$minLevel = 5){
        if (empty($id)) {
    		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        $whereConds['id'] = $id;
        $res = $this->getOrgInfoByIdOrAccount($whereConds);
        if (!$res['errorno']) {
            $data = $res['data'];
        } else {
            $this->data['errorno'] = $res['errorno'];
            $this->data['errormsg'] = $res['errormsg'];
            return $this->data;
        } 
        //数据为空
        if (empty($data)) {
            $this->data['errorno'] = ErrorConst::E_INNER_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_INNER_FAILED_MSG;
            return $this->data;
        }
        if ($isfamily === true) {
            $listParams['company_id'] = $data['company_id'];
            $listParams['pid'] = $data['pid'];
            $listParams['level'] = $data['level'];
            $result = $this->getOrgInfoListByPid($listParams,
				array("id","pid","company_id","customer_id","level",'title'), 1, null);
            foreach ($result['data'] as $key=>$item) {
                if($item['id'] == $id){
                    $result['data']['activeList'] = $item;
                    unset($result['data'][$key]);
                    $pid = $item['pid'];
                } 
            }
        } else {
            $result['data']['activeList'] = $data;
            $pid = $data['pid'];
        }
        $minLevel = min($data['level'],$minLevel);
        $nextRes[$data['level']] = $result['data'];
        if ($data['pid'] != 0 && $data['level'] <= $minLevel) {
                return $this->getTreeByOrgId($pid,$isfamily,$nextRes,$minLevel);
        } else {
            $this->data['data'] = $nextRes;
            return $this->data;
        } 
    }
    /*}}}*/
     //{{{ 获得该id下面的子结构 getChildTreeByOrgId
    /* 结构图
     *  params orgid int
     *  params level 设置级别
     *  params params 传入参数  目前支持 title 关键字搜索
     *  params page 当前第几页  当传入 page 为1 limit 为 null时  则没有数量限制
     *  params pageSize 每页条数
     *  
     *  result array(
     *      data=>array(
     *          list=>array(记录)
     *          count=>array(总条数)
     *      )
     *      errorno=> 错误号
     *      errormsg=> 错误记录
     *  )
     */

    public function getChildTreeByOrgId($orgId,$level = 2,$params = array(),$page = 1,$pageSize = 15){
        if (empty($orgId)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        $tmpPageSize = null;

        $totalCount = 1;
        $whereConds['id'] = $orgId;
        $res = $this->getOrgInfoByIdOrAccount($whereConds);
        $this->data['data'] = null;
        $data = $res['data'];
        if (empty($data)) {
            $this->data['data']['list'] = array();
            $this->data['data']['count'] = 0;
            return $this->data;
        } else {
            //如果这个恰好是公司级别的账号 就直接用level进行搜索
            if ($data['pid'] == 0) {
                $listParams['company_id'] = $data['company_id'];
                $listParams['title'] = $params['title'];
                $listParams['level'] = $level;
                $orgData = $this->getOrgInfoListByPid($listParams, $fields, $page, $pageSize);
                unset($this->data['data']);
                $countData = $this->getOrgCountByPid($listParams);              
                unset($this->data['data']);
                $this->data['data']['list'] = $orgData['data'];
                $this->data['data']['count'] = $countData['data'];
                return $this->data;
            }
            $arrConds["pid IN ({$data['id']}) AND level = "] = $data['level'] + 1;
            if ($data['level'] == $level) {
                $this->data['data']['list'][] = $data;
                $this->data['data']['count'] = $totalCount;
                return $this->data;
            }elseif ($data['level'] > $level) {
                return $this->data;
                //下级恰好是要取的等级
            } else if ($data['level'] + 1 == $level) {
                $tmpPageSize = $pageSize;
            }
        }
        $arrConds['status ='] = 1;
        $arrConds['company_id = '] = $data['company_id'];
        $fields = array('id','pid','title','company_id','customer_id','level','status');
        do {
            //支持title 关键字搜索
            if (isset($params['title']) && $params['title'] !='' && ($data['level'] == 3 || $dataLevel == 3)) {
                array_push($arrConds, "title like '%".$params['title']."%'");
            }
		    $this->objDaoHouseManagerAccount = Gj_LayerProxy::getProxy('Dao_Gcrm_HouseManagerAccount');
            $result = $this->objDaoHouseManagerAccount->selectByPage($fields, $arrConds, $page, $tmpPageSize);
            foreach ($result as $item) {
                $ids[] = $item['id'];
                $dataLevel = $item['level'];
            }
            //最后一次取结果
            if ($dataLevel == $level) {
                $count = $this->objDaoHouseManagerAccount->selectByCount($arrConds);
            }
            if (isset($ids)){
                $ids = implode(',',$ids);
            }
            $arrConds = array();
            $arrConds['status ='] = 1;
            $arrConds["pid IN ({$ids}) AND level = "] = $dataLevel + 1;
            if ($dataLevel + 1== $level) {
                $tmpPageSize = $pageSize;
            }
            unset($ids);
        } while ($dataLevel < $level && !empty($result) && $dataLevel <=4);
        $this->data['data']['list'] = $result;
        $this->data['data']['count'] = $count;
        return $this->data;
    }

}
