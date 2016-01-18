<?php
/*
 * File Name:AccountBussionessInfo.class.php
 * Author:lukang
 * account_business_info 获得用户订单
 * mail:lukang@ganji.com
*/
class Service_Data_Gcrm_AccountBusinessInfo
{
    protected $data = array();
    protected $objDao = null;

    protected $defaultFields = array(
        "Id","AccountId","UserId","CustomerId","CompanyId","CountType","BussinessScope","InDuration","MaxPremierCount","MaxFreeRefreshCount","MaxChargeRefreshCount","InDurationBeginTime","InDurationEndTime","NextDurationBeginTime","MinBeginTime","MaxEndTime","ModifiedTime"
    );

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo'); 
    }
	public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
    }  	
	 /** 
     *@codeCoverageIgnore
     **/
    public function setVal($name,$args){
        $this->$name = $args;
    }
    public function updateAccountBizInfo($accountId){
        $bussinessInfo = $this->getBussinessInfoByAccountId($accountId, true);
        $modifiedTime = time();
        foreach($bussinessInfo['data']['BussinessInfo'] as $tmpBussinessInfo) {
            $ret = array();
            $updateVal = array_merge($bussinessInfo['data'], $tmpBussinessInfo);
            $updateVal['ModifiedTime'] = $modifiedTime;
            try {
                $ret['msg'] = json_encode($updateVal);
                $ret['result'] = $this->objDao->insertBussinessInfoOnDuplicateKeyUpdate($updateVal);
                $this->data['data'][] = $ret;
            } catch (Exception $e) {
                $this->data = array(
                    'errorno' => $e->getCode(),
                    'errormsg' => $e->getMessage(),
                );
            }
        }
        if (is_numeric($accountId)){
            $delCond = array('AccountId=' => $accountId, 'ModifiedTime<' => $modifiedTime);
            $this->objDao->deleteAccountInfo($delCond);
        }
        return $this->data;
    }
    /* {{{ public function getBussinessInfoByAccountId*/
    /**
     * @brief  获取经纪人信息
     *
     * @param $accountId 经纪人customer_account表自增Id
     * @param $fromTc bool true 从tc接口取付费信息，false 从账户信息表获取付费信息
     *
     * @returns  array(
     *              'AccountId' => int, // 要查询的accountId，
     *              'UserId' => int, // 用户的会员中心id
     *              'CustomerId' => int, // 用户的门店Id
     *              'CompanyId' => int, // 用户的公司Id
     *              'BussinessInfo' => array(), // 用户的付费信息，若为空数组则表示没有付费信息
     *           )  
     */
    public function getBussinessInfoByAccountId($accountId, $fromTc = false){
        $retVal = array(
            'AccountId' => $accountId,      
            'BussinessInfo' => array()
        );
        $accountFields = array('CustomerId', 'UserId', 'AccountId', 'CityId');
        $customerFields = array('CustomerId','CompanyId');
        try{
            $objCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
            $objCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
            $accountInfo = $objCustomerAccount->getAccountInfoById($accountId, $accountFields);
            $tmpRetArr = array(
                'AccountId' => $accountId,
            );
            if (isset($accountInfo['data'][0]) && !empty($accountInfo['data'][0])) {
                $tmpRetArr = $accountInfo['data'][0];
                $customerId = $accountInfo['data'][0]['CustomerId'];
                $userId = $accountInfo['data'][0]['UserId'];
                $cityCode = $accountInfo['data'][0]['CityId'];
                $tmpRetArr['CityCode'] = $cityCode;
                $customerInfo = $objCustomer->getCustomerInfoByCustomerId($customerId, $customerFields);
                if (isset($customerInfo['data']) && is_array($customerInfo['data'])) {
                    $tmpRetArr = array_merge($tmpRetArr, $customerInfo['data']);
                }
                $ucInfo = $objCustomerAccount->getUser($userId);
                if (isset($ucInfo['data']['username'])) {
                    $tmpRetArr['LoginName'] = $ucInfo['data']['username'];
                    $tmpRetArr['Email'] = $ucInfo['data']['email'];
                }
            } else {
                throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,ErrorConst::E_SQL_FAILED_MSG);
            }
            if ($fromTc) {
                $objTradeCenter = Gj_LayerProxy::getProxy('Service_Data_Gcrm_TradeCenterInterface');
                $durationInfo = $objTradeCenter->getBussinessDurationInfo($userId);
            } else {
                $wantFields = array("CountType","BussinessScope","InDuration","MaxPremierCount","MaxFreeRefreshCount","MaxChargeRefreshCount","InDurationBeginTime","InDurationEndTime","NextDurationBeginTime","MinBeginTime","MaxEndTime");
                $arrConds = array('AccountId = ' => $accountId);
                $durationInfo['BussinessInfo'] = $this->objDao->select($wantFields, $arrConds);
            }
            $retVal = array_merge($tmpRetArr, $durationInfo);
        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        $this->data['data'] = $retVal;
        return $this->data;
    }
    /* }}} */
    //{{{getWhereArr
	/**
	* 组装查询条件
	* @param unknown $whereConds
	*/
    protected function getWhereArr($whereConds){
        if(!empty($whereConds['customerId'])){
            if (!is_array($whereConds['customerId'])) {
			    $arrConds['CustomerId ='] = $whereConds['customerId'];
            } else {
                $customerIds = implode(',',$whereConds['customerId']);

			    $arrConds[] = "CustomerId IN ({$customerIds})";
            }
        }
        if(!empty($whereConds['companyId'])){
        	if(is_array($whereConds['companyId'])){
        		$companyIds = implode(',',$whereConds['companyId']);
        		$arrConds[] = "CompanyId IN ({$companyIds})";
        	}else{
				$arrConds['CompanyId ='] = $whereConds['companyId'];
        	}
		}
        if(!empty($whereConds['accountId'])){
            if (!is_array($whereConds['accountId'])) {
			    $arrConds['AccountId ='] = $whereConds['accountId'];
            } else {
                $accountIds = implode(',',$whereConds['accountId']);
                $arrConds[] = "AccountId IN ({$accountIds})";
            }
		}
		if(isset($whereConds['inDuration'])){
			$arrConds['InDuration ='] = $whereConds['inDuration'];
		}
		if(!empty($whereConds['userId'])){
			$arrConds['UserId ='] = $whereConds['userId'];
		}
		if(!empty($whereConds['countType'])){
			$arrConds['CountType ='] = $whereConds['countType'];
        }
        if(!empty($whereConds['businessScope'])){
            if (!is_array($whereConds['businessScope'])) {
                $arrConds['BussinessScope ='] = $whereConds['businessScope'];
            } else {
                $businessScope = implode(',',$whereConds['businessScope']);
                $arrConds[] = "BussinessScope IN ({$businessScope})";
            }
        }
        if(!empty($whereConds['maxTime']) && is_array($whereConds['maxTime'])){
            if(isset($whereConds['maxTime']['>=minBeginTime'])) {
			    $arrConds['MinBeginTime >='] = $whereConds['maxTime']['>=minBeginTime'];
            }
            if(isset($whereConds['maxTime']['<=minBeginTime'])) {
                $arrConds['MinBeginTime <='] = $whereConds['maxTime']['<=minBeginTime'];
            }
            if(isset($whereConds['maxTime']['>=maxEndTime'])) {
                $arrConds['MaxEndTime >='] = $whereConds['maxTime']['>=maxEndTime'];
            }
            if(isset($whereConds['maxTime']['<=maxEndTime'])) {
                $arrConds['maxEndTime <='] = $whereConds['maxTime']['<=maxEndTime'];
            }
        }
        if(!empty($whereConds['inTime']) && is_array($whereConds['inTime'])){
            if(isset($whereConds['inTime']['>=minBeginTime'])) {
                $arrConds['InDurationBeginTime >='] = $whereConds['inTime']['>=minBeginTime'];
            }
            if(isset($whereConds['inTime']['<=minBeginTime'])) {
                $arrConds['InDurationBeginTime <='] = $whereConds['inTime']['<=minBeginTime'];
            }
            if(isset($whereConds['inTime']['>=maxEndTime'])) {
                $arrConds['InDurationEndTime >='] = $whereConds['inTime']['>=maxEndTime'];
            }
            if(isset($whereConds['inTime']['<=maxEndTime'])) {
                $arrConds['InDurationEndTime <='] = $whereConds['inTime']['<=maxEndTime'];
            }
        }
        return $arrConds;
    }
    //}}}
   /*{{{getAccountListByCompanyId
     * @brief 通过companyId，门店下面生效accountId
     * @param int customerId 需要获得这个公司指定门店id 经纪人
     * @param int businessScope = null
     * @param effective 获得生效订单
     * @param page 当前页
     * @param pagesize 分页页数
     */
    public function getAccountListByCompanyId($companyId,$arrConds,$page = 1,$pageSize = null){
        $ret = array();
        try {
            if (!is_numeric($page) ||
                (isset($arrConds['minBeginTime']) && !is_numeric($arrConds['minBeginTime'])) ||
                (isset($arrConds['maxEndTime']) && !is_numeric($arrConds['maxEndTime']))
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            }
            $appends = 'GROUP BY AccountId';
            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $arrConds['customerId'];
            $whereConds['businessScope'] = $arrConds['businessScope'];
            $whereConds['countType'] = $arrConds['countType'];
            if (isset($arrConds['maxTime'])) {
                $whereConds['maxTime'] =  $arrConds['maxTime'];
            }
            if (isset($arrConds['inTime'])) {
                $whereConds['inTime'] =  $arrConds['inTime'];
            }
            if (isset($arrConds['effective'])) {
                $whereConds['inDuration'] = ($arrConds['effective'] === true)?1:0;
            }
            $conds = $this->getWhereArr($whereConds);
            //查询字段可以 使用 setVal自定义
            $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->selectByPage($this->defaultFields, $conds,$page,$pageSize,array(),$appends);
        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/
   /*{{{getAccountListByCompanyIdCount
     * @brief 通过companyId，门店下面生效accountId数量
     * @param int customerId 需要获得这个公司指定门店id 经纪人  
    * $effective 生效中
     */
    public function getAccountListByCompanyIdCount($companyId,$arrConds){
        $ret = array();
        try {
            $appends = 'GROUP BY AccountId';
            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $arrConds['customerId'];
            $whereConds['businessScope'] = $arrConds['businessScope'];
            $whereConds['countType'] = $arrConds['countType'];
            if (isset($arrConds['maxTime'])) {
                $whereConds['maxTime'] =  $arrConds['maxTime'];
            }
            if (isset($arrConds['inTime'])) {
                $whereConds['inTime'] =  $arrConds['inTime'];
            }
            if (isset($arrConds['effective'])) {
                $whereConds['inDuration'] = ($arrConds['effective'] === true)?1:0;
            }

            $conds = $this->getWhereArr($whereConds);
            //查询字段可以 使用 setVal自定义           
			$this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->selectByCount($conds,$appends);
        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    //}}}
    /* {{{getAccountIdByCustomerId
     * @brief 通过customerId，门店下面生效accountId
     * @param int customerId  
     * @param int businessScope = null
     * @param effective 获得生效订单
     * @param page 当前页
     * @param pagesize 分页页数
     */
    public function getAccountIdByCustomerId($customerId,$businessScope = null,$effective = true,$page = 1,$pageSize = null){
        $ret = array();
        try {
            if (!is_numeric($customerId) || !is_numeric($page) || (isset($businessScope) && !is_numeric($businessScope))) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            } 

            $appends = 'GROUP BY AccountId';
            $whereConds['customerId'] = $customerId;
            $whereConds['businessScope'] = $businessScope;
            $whereConds['inDuration'] = ($effective === true)?1:0;

            $arrConds = $this->getWhereArr($whereConds);
            //查询字段可以 使用 setVal自定义           
			$this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->selectByPage($this->defaultFields, $arrConds,$page,$pageSize,array(),$appends);

        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/
    /* {{{getAccountIdByCustomerIdCount
     *
     * @brief 通过customerId，门店下面生效accountId 数量
     * @param int customerId  
     * @param int businessScope = null
     * @param effective 获得生效订单
     * @param page 当前页
     * @param pagesize 分页页数
     */
    public function getAccountIdByCustomerIdCount($customerId,$businessScope = null,$effective = true){
        $ret = array();
        try {
            if (!is_numeric($customerId) || (isset($businessScope) && !is_numeric($businessScope))) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            } 

            $appends = 'GROUP BY AccountId';
            $whereConds['customerId'] = $customerId;
            $whereConds['businessScope'] = $businessScope;
            $whereConds['inDuration'] = ($effective === true)?1:0;

            $arrConds = $this->getWhereArr($whereConds);
			$this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->selectByCount($arrConds,$appends);

        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/

    public function getBusinessInfoByAccountIds($accountIds,$arrConds = array()){
        $ret = array();

        try {
            if (empty($accountIds)) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            }

            $whereConds['accountId'] = $accountIds;
            $whereConds['businessScope'] = $arrConds['businessScope'];
            $whereConds['countType'] = $arrConds['countType'];

            if (isset($arrConds['inTime'])) {
                $whereConds['inTime'] =  $arrConds['inTime'];
            }

            if (isset($arrConds['maxTime'])) {
                $whereConds['maxTime'] =  $arrConds['maxTime'];
            }

            if (isset($arrConds['effective'])) {
                $whereConds['inDuration'] = ($arrConds['effective'] === true)?1:0;
            }


            $arrConds = $this->getWhereArr($whereConds);
			$this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->select($this->defaultFields,$arrConds);

        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;

        return $this->data;
    }
    
    /*{{{getBusinessList
     * 获取端口列表
     * @brief 通过companyId，门店下面生效accountId
     * @param int customerId 需要获得这个公司指定门店id 经纪人  
     * @param int businessScope = null
     * @param effective 获得生效订单
     * @param counttype 获得精品或者放心房
     * @param maxTime  订单最小的开始时间 和 最大的结束时间
     */
    public function getBusinessList($companyId,$customerIds = array(),$businessScope = null,$effective = true,$countType=null,$maxTime = array()){
        $ret = array();
        try {
            if (!is_numeric($companyId)
                || !is_array($customerIds)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            } 
      
            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $customerIds;
            $whereConds['businessScope'] = $businessScope;
            $whereConds['countType'] = $countType;
            if (isset($effective)) {
                $whereConds['inDuration'] = ($effective === true)?1:0;
            }
            $whereConds['maxTime'] = $maxTime;
            $arrConds = $this->getWhereArr($whereConds);
			 $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo'); 
            //查询字段可以 使用 setVal自定义                 
            $ret = $this->objDao->select($this->defaultFields, $arrConds);
            
        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/
    
     /*{{{getBusinessStatusCount
      * 获得某个端口已过期或者即将生效的订单数量
     * @int 通过companyId，门店下面生效accountId
     * @param array customerId 需要获得这个公司指定门店id 经纪人  
     * @param int businessScope = null
     * @param int flag 0：已过期,1:即将生效
     */
    public function getBusinessStatusCount($companyId,$customerIds = array(),$businessScope = null,$flag=0){
        $ret = array();
        try {
            if (!is_numeric($companyId) || 
                (isset($businessScope) && !is_numeric($businessScope))
                || !is_array($customerIds)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            } 
      
            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $customerIds;
            $whereConds['businessScope'] = $businessScope;
            $whereConds['inDuration'] = 0;
            $arrConds = $this->getWhereArr($whereConds);
            if($flag == 1){   
                $arrConds['NextDurationBeginTime >= '] = strtotime("+1 day midnight");
            }else{
                $arrConds['MaxEndTime < '] = strtotime("midnight");
            }
			 $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            
            //查询字段可以 使用 setVal自定义           
            $ret = $this->objDao->selectByCount($arrConds);
            
        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/
    /*{{{getBusinessStatusCount
      * 获得某个端口公司的开通的端口类型
     * @int 通过companyId，门店下面生效accountId
     * @int connttype 1 为精品  2为放心房
     * @boolean  true为当前生效中 false为不生效   null为有订单的状态
     *
     * return business=>对应 HousingVars::BIZ_*   count为订单数量
     */
    public function getOpenBizByCompanyId($companyId,$customerIds = array(),$countType = 1,$effective = null){
        try {
            if (!is_numeric($companyId) || !is_numeric($countType)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            }

            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $customerIds;
            $whereConds['countType'] = $countType;
            if (is_bool($effective)) {
                $whereConds['inDuration'] = ($effective === true)?1:0;
            }
            $arrConds = $this->getWhereArr($whereConds);
            $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $appends = "GROUP BY BussinessScope";
            $fields = "BussinessScope as BusinessScope,count(1) as count";
            $ret = $this->objDao->selectByPage($fields, $arrConds,1,null,array(),$appends);

        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
    }
    /*}}}*/
    /*{{{getBusinessStatusCount
      * 获得某个公司下面的用户按照 group 分组
     * @int 通过companyId，门店下面生效accountId
     * @int connttype 1 为精品  2为放心房
     * @boolean  true为当前生效中 false为不生效   null为有订单的状态
     */
    public function getAccountListGroupBusinessScopeByCompanyId($companyId,$customerIds = array(),$businessScope = null,$counttype = 1,$effective = null){
        $ret = array();
        try {
            if (!is_numeric($companyId)
                || !is_array($customerIds)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,ErrorConst::E_PARAM_INVALID_MSG);
            }
            
            $whereConds['companyId'] = $companyId;
            $whereConds['customerId'] = $customerIds;
            $whereConds['businessScope'] = $businessScope;
            $whereConds['countType'] = $countType;
            if (is_bool($effective)) {
                $whereConds['inDuration'] = ($effective === true)?1:0;
            }
            $arrConds = $this->getWhereArr($whereConds);
            //查询字段可以 使用 setVal自定义
            $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_AccountBusinessInfo');
            $ret = $this->objDao->selectByPage($this->defaultFields, $arrConds,$page,$pageSize,array(),$appends);

        } catch (Exception $e) {
            $this->data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }

        $this->data['data'] = $ret;
        return $this->data;
	}
	/** 
	 *@codeCoverageIgnore
	 **/
	public function getAccountBusinessInfolist($arrInput,$whereConds){
		if(empty($arrInput)){
			return $this->arrRet;
		}   
		$objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_AccountBusinessInfo");    
		$fields = array("AccountId","BussinessScope");
		try{
			$this->arrRet = $objDaoSourceList->select($fields,$whereConds);    
		}catch(Exception $e){
			$this->arrRet = array(
				'errorno' =>ErrorConst::E_DB_FAILED_CODE,
				'errormsg' => $e->getMessage(),
			);  
		}   
		return $this->arrRet;
	}
}
