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
class Service_Data_Gcrm_CustomerAccountLoginEvent{
	protected $data;
	/**
	 * @var Dao_Housereport_CustomerLogin
	 */
	protected $objDaoCustomerLogin;
	/**
	 * @var Dao_Gcrm_CustomerAccountLoginEvent
	 */
	protected $objDaoCustomerAccountLoginEvent;
	protected $arrFields = array("EventId","AccountId","Email","IsSuccess","LoginTime","Ip","Message","City");
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    	$this->objDaoCustomerAccountLoginEvent = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccountLoginEvent');
    	$this->objDaoCustomerLogin = Gj_LayerProxy::getProxy('Dao_Housereport_CustomerLogin');
    }
    //{{{getCustomerAccountLoginInfo
    /**
     * 获取经纪人登陆列表
     * @param unknown $account_id		用户id
     * @param unknown $arrFields		查询字段
     * @param number $page				页数
     * @param number $pageSize			每页条数
     * @return Ambigous <multitype:, string, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getCustomerAccountLoginList($account_id, $sTime, $eTime, $arrFields=array(), $page=1, $pageSize=30){
    	if (count($arrFields)) {
    		$this->arrFields = $arrFields;
    	}
    	$arrConds = array(
    		'AccountId =' =>$account_id,
    		'LoginTime >' =>$sTime,
    		'LoginTime <' =>$eTime,
    	);
    	$orderArr = array('LoginTime'=>'DESC');
    	try{
    		$res = $this->objDaoCustomerAccountLoginEvent->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoCustomerAccountLoginEvent->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
    		$this->data['data'] = $res;
    	}
    	return $this->data;
    }//}}}
    //{{{getCustomerAccountLoginCount
    /**
     * 获取经纪人登录总数
     * @param unknown $account_id					账号id
     * @param unknown $sTime						开始时间
     * @param unknown $eTime						结束时间
     * @return Ambigous <multitype:, string, $ret, number, boolean, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getCustomerAccountLoginCount($account_id, $sTime, $eTime){
    	$arrConds = array(
    			'AccountId =' =>$account_id,
    			'LoginTime >' =>$sTime,
    			'LoginTime <' =>$eTime,
    	);
    	try{
    		$res = $this->objDaoCustomerAccountLoginEvent->selectByCount($arrConds);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	$this->data['data'] = $res;
    	return $this->data;
    }//}}}
    //{{{getCustomerAccountLoginInfo
    /**
     * 获取经纪公司下，门店、板块登陆列表
     * @param unknown $account_id		用户id
     * @param unknown $arrFields		查询字段
     * @param number $page				页数
     * @param number $pageSize			每页条数
     * @return Ambigous <multitype:, string, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getCustomerLoginList($account_id, $sTime, $eTime, $arrFields=array(), $page=1, $pageSize=30){
    	if (count($arrFields)) {
    		$this->arrFields = $arrFields;
    	}
    	$arrConds = array(
    			'account_id =' =>$account_id,
    			'loging_time >' =>$sTime,
    			'loging_time <' =>$eTime,
    	);
    	$orderArr = array('loging_time'=>'DESC');
    	try{
    		$res = $this->objDaoCustomerLogin->selectByPage($this->arrFields, $arrConds, $page, $pageSize, $orderArr);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoCustomerLogin->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
    		$this->data['data'] = $res;
    	}
    	return $this->data;
    }//}}}
    //{{{getCustomerAccountLoginCount
    /**
     * 获取经纪公司下，门店、板块登录总数
     * @param unknown $account_id					账号id
     * @param unknown $sTime						开始时间
     * @param unknown $eTime						结束时间
     * @return Ambigous <multitype:, string, $ret, number, boolean, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function getCustomerLoginCount($account_id, $sTime, $eTime){
    	$arrConds = array(
    			'account_id =' =>$account_id,
    			'loging_time >' =>$sTime,
    			'loging_time <' =>$eTime,
    	);
    	try{
    		$res = $this->objDaoCustomerLogin->selectByCount($arrConds);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	$this->data['data'] = $res;
    	return $this->data;
    }//}}}
    //{{{ addCustomerAccountLoginLog
    /**
     * 添加登录信息
     * @param unknown $arrRows
     * @return Ambigous <multitype:, string, unknown, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
     */
    public function addCustomerLoginLog($arrRows){
		if (!is_array($arrRows)) {
    		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;;
    	}else{
    		try{
				$res = $this->objDaoCustomerLogin->insert($arrRows);
    		} catch(Exception $e) {
    			$this->data['errorno'] = $e->getCode();
    			$this->data['errormsg'] = $e->getMessage();
    		}
    		if (!$res) {
    			Gj_Log::warning($this->objDaoCustomerLogin->getLastSQL());
    			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    			return $this->data;
    		}else{
    			$this->data['data'] = $res;
    		}
		}
    	return $this->data;
    }//}}}
/*
 * @获取最后一次登陆记录信息
 * @int $account_id :登陆账号
 * @array $arrFields :获取字段
 * @return: array
 */
	 public function getLastedCustomerLoginLog($account_id, $arrFields){
            $arrConds = array('account =' => $account_id);
        try{
            $res = $this->objDaoCustomerLogin->selectLastLog($arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }   
        if ($res['errorno']) {
            Gj_Log::warning($this->objDaoCustomerLogin->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res[0];
        }   
        return $this->data;
    }//}}}
	public function getLoginCountByAccountId($arrInput,$whereConds){
		if(empty($arrInput)){
			return $this->data;
		}   
		$objDaoLoginEvent= Gj_LayerProxy::getProxy("Dao_Gcrm_CustomerAccountLoginEvent");    
		$fields = array('COUNT(1) AS count','AccountId');
		try{
			$this->data= $objDaoLoginEvent->selectGroupbyAccountId($fields,$whereConds);    
		}catch(Exception $e){
			$this->data = array(
				'errorno' =>ErrorConst::E_DB_FAILED_CODE,
				'errormsg' => $e->getMessage(),
			);  
		}   
		return $this->data;
	}   

}
