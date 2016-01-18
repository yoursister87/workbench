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
class Service_Data_Gcrm_CustomerAccount{
	/**
	 * @var Dao_Gcrm_CustomerAccount
	 */
	protected $objDaoCustomerAccount;
	/**
	 * @var Dao_Gcrm_Interface_UserInterface
	 */
	protected $objInterfaceUser;
	protected $arrFields = array("UserType","AccountId","BussinessScope","ServicePlanId","CityId","DistrictId","AreaId","Email","AccountName","AliasName","ICNo","ICImage","BusinessCardImage","Gender","OfficePhone","CellPhone","IM","Fax","DepositAmount","AwardAmount","Balance","IsCPC","LastestRecharge","PremierExpire","RestExpire","RegistrationDate","PersonalIntroduction","HasLicence","Picture","Status","PremierUnfreezeTime","LoginTime","ModifierId","CreatedTime","CreatorName","CreatorId","ModifierName","ModifiedTime","ShopName","ShopNotice","ShopService","AuditAt","AuditId","AuditName","AuditResult","ModifiedAt","UserId","RecentTag","PremierTag",'OwnerType','GroupId as CustomerId','CompanyIdOfZhenFangYuan');
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objDaoCustomerAccount = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccount');
		$this->objInterfaceUser = Gj_LayerProxy::getProxy('Dao_Gcrm_Interface_UserInterface');
	}
	 public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
	 }
	//{{{ getAccountListByCustomerId 
	/**
	 * 获取账号列表
	 * @param unknown $arrFields			查询字段
	 * @param unknown $customer_id		门店id
	 * @param number $page					页数
	 * @param number $pageSize				每页条数
	 * @return Ambigous <multitype:, string, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getAccountListByCustomerId($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
		if (count($arrFields)) {
			$this->arrFields = $arrFields;
		}
		$arrConds = $this->getCustomerAccountWhere($whereConds);
        $wantFields = $this->revertFields($this->arrFields);
		//$orderArr = array('DESC'=>'CreatedTime');
		try{
			$res = $this->objDaoCustomerAccount->selectByPage($wantFields, $arrConds, $page, $pageSize, $orderArr);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			Gj_Log::warning($this->objDaoCustomerAccount->getLastSQL());
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{getAccountCountByCustomerId
	/**
	 * 获取账号数量
	 * @param unknown $customer_id 门店id
	 * @return Ambigous <multitype:, string, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getAccountCountByCustomerId($whereConds){
		$arrConds = $this->getCustomerAccountWhere($whereConds);
		try{
			$res = $this->objDaoCustomerAccount->selectByCount($arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if (false === $res) {
			Gj_Log::warning($this->objDaoCustomerAccount->getLastSQL());
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
			$this->data['data'] = 0;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{getOrgWhere
	/**
	 * 组装查询条件
	 * @codeCoverageIgnore
	 * @param unknown $whereConds
	 * @return multitype:number unknown
	 */
	protected function getCustomerAccountWhere($whereConds){
		$arrConds = array();
		if(isset($whereConds['customer_id']) && is_numeric($whereConds['customer_id'])){
			$arrConds['GroupId ='] = $whereConds['customer_id'];
		}
		if(!empty($whereConds['account_id'])){
			if(is_array($whereConds['account_id'])){
				$account_ids = implode(',',$whereConds['account_id']);
				$arrConds[] = "AccountId IN ({$account_ids})" ;
			} else {
				$arrConds['AccountId ='] = $whereConds['account_id'];
			}
		}
		if(!empty($whereConds['cell_phone'])){
			$arrConds['CellPhone ='] = $whereConds['cell_phone'];
		}
		if(!empty($whereConds['account_name'])){
			$arrConds['AccountName ='] = $whereConds['account_name'];
		}
        if (!empty($whereConds['s_premier_expire'])) {
            $arrConds['PremierExpire >='] = $whereConds['s_premier_expire'];
        }
        if(!empty($whereConds['e_premier_expire'])){
            $arrConds['PremierExpire <='] = $whereConds['e_premier_expire'];
        }
        if(!empty($whereConds['premier_expire'])){
            $arrConds['PremierExpire ='] = $whereConds['premier_expire'];
        }
        if(isset($whereConds['CityId']) && intval($whereConds['CityId'])>=0){
        	$arrConds['CityId ='] = $whereConds['CityId'];
        }
		/**
		 * @author 刘海鹏 <liuhaipeng1@ganji.com>
		 * @create 2015-07-13
		 */
		if (isset($whereConds['Status']) && intval($whereConds['Status']) > 0) {
			$arrConds['Status ='] = $whereConds['Status'];
		}
        if(isset($whereConds['MerchantType']) && intval($whereConds['MerchantType'])>=0){
            $arrConds['MerchantType ='] = $whereConds['MerchantType'];
        }
		return $arrConds;
	}//}}}
	//{{{getAccountInfoById
	/**
	 * 根据account_id 获取经纪人信息  支持传入数组
	 * @param array $account_ids									经纪人id
	 * @param array $arrFileds									获取字段数组
	 * @return Ambigous <multitype:, string, unknown>
	 */
	public function getAccountInfoById($account_id, $arrFields=array()){
        $arrConds = array();
        if (is_array($account_id)){
            $account_ids = implode(',',$account_id);
            $arrConds[] = "AccountId IN ({$account_ids})";
        } else {
            $arrConds[] = "AccountId = ({$account_id})";
        }
        return $this->getAccountInfoByConds($arrConds, $arrFields);
	}//}}}
	//{{{getAccountInfoByUserId
	/**
	 * 根据user_id 获取经纪人信息  支持传入数组
	 * @param array $user_ids									经纪人id
	 * @param array $arrFileds									获取字段数组
	 * @return Ambigous <multitype:, string, unknown>
	 */
	public function getAccountInfoByUserId($user_id, $arrFields=array()){
        $arrConds = array();
        if (is_array($user_id)){
            $user_ids = implode(',',$user_id);
            $arrConds[] = "UserId IN ({$user_ids})";
        } else {
            $arrConds[] = "UserId = ({$user_id})";
        }
        return $this->getAccountInfoByConds($arrConds, $arrFields);
	}//}}}
    /* {{{ protected getAccountInfoByConds*/
    /**
     * @brief 通过拼接好的条件进行dao数据查询
     *
     * @param $arrConds 查询条件
     * @param $arrFields 查询字段
     *
     * @returns   
     */
    public function getAccountInfoByConds($arrConds, $arrFields=array()){
        $res = false;
        if (count($arrFields)) {
			$this->arrFields = $arrFields;
		}
        $wantFields = $this->revertFields($this->arrFields);
         try{
             $res = $this->objDaoCustomerAccount->select($wantFields, $arrConds);
         } catch(Exception $e) {
             $this->data['errorno'] = $e->getCode();
             $this->data['errormsg'] = $e->getMessage();
         }
         if ($res === false) {
             Gj_Log::warning($this->objDaoCustomerAccount->getLastSQL());
             $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
             $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
         } else {
             $this->data['data'] = $res;
         }
         return $this->data;
    }
    /* }}} */
	//{{{UpdateCustomer
	/**
	 * 经纪人转门店
	 * @param unknown $arrChangeRow
	 * @param arrChangeRow = array ('AccountIds' => '经济人id，多人以逗号分隔','CreatorId' => 'house_manage_account中的id','CreatorName' => 'house_manage_account中的account','CustomerId' => 'customer表中的CustomerId','CustomerName' => 'customer表中FullName')
	 * @return $res = array('succeed'=>1,'data'=>array('result'=>''))
	 */
	public function UpdateCustomer($arrChangeRow){
		try {
			$tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=UpdateGroup';
			$objCurl = new Gj_Util_Curl();
			$arrChangeRow['source'] = '52cbbc55a24545b4bf3c273dad02b91c';
			$postData = "data=".json_encode($arrChangeRow);
			$objCurl = new Gj_Util_Curl();
			$res = $objCurl->post($tgApiUrl, $postData, true);
			Gj_Log::warning("crm:UpdateCustomer;conds:".json_encode($arrChangeRow).';return:'.$res);
			$resArr = json_decode($res,true);
			if($resArr['succeed']){
				$this->data['data'] = $resArr['data']['result'];
			}else{
				$this->data['errorno'] = 2112;
				$this->data['errormsg'] = $resArr['error'];
			}
		} catch (Exception $e) {
			$this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
			$this->data['errormsg'] = ErrorConst::E_DB_FAILED_MSG;
		}
		return $this->data;
	}//}}}
	//{{{UpdateProfile
	/**
	 * 修改经纪人资料
	 * @param unknown $arrChangeRow
	 * @return $res = array('succeed'=>1,'data'=>array('result'=>''))
	 */
	 public function UpdateProfile($arrChangeRow){
	 	try {
			$tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=UpdateProfile';
			//$tgApiUrl = "http://crmapi.dns.ganji.com:8035/service.ashx?interface=IAccountService&method=UpdateProfile";
			$arrChangeRow['source'] = '52cbbc55a24545b4bf3c273dad02b91c';
			$postData = "data=".json_encode($arrChangeRow);
			$objCurl = new Gj_Util_Curl();
			$res = $objCurl->post($tgApiUrl, $postData, true);
			Gj_Log::warning("crm:UpdateProfile;conds:".json_encode($arrChangeRow).';return:'.$res);
			$resArr = json_decode($res,true);
			if($resArr['succeed']){
				$this->data['data'] = $resArr['data']['message'];
			}else{
				$this->data['errorno'] = 2111;
				$this->data['errormsg'] = $resArr['error'];
			}
		} catch (Exception $e) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] = ErrorConst::E_INTERFACE_FAILED_MSG;
		}
		return $this->data;
	}//}}}
	//{{{TransferBalance
	/**
	 * 经纪人转端口
	 * @param unknown $arrChangeRow
	 * @return $res = array('succeed'=>1,'data'=>array('result'=>''))
	 */
	public function TransferBalance($arrChangeRow){
		try {
			$tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=TransferBalance';
			$arrChangeRow['source'] = '52cbbc55a24545b4bf3c273dad02b91c';
			$postData = "data=".json_encode($arrChangeRow);
			$objCurl = new Gj_Util_Curl();
			$res = $objCurl->post($tgApiUrl, $postData, true);
			$resArr = json_decode($res,true);
			Gj_Log::warning("crm:TransferBalance;conds:".json_encode($arrChangeRow).';return:'.$res);
			if($resArr['succeed'] && $resArr['data']['result']['Succeed']){
				$this->data['data'] = $resArr['data']['result']['Message'];
			}else{
				$this->data['errorno'] = 2110;
				$this->data['errormsg'] = $resArr['data']['result']['Message'];
			}
		} catch (Exception $e) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] = ErrorConst::E_INTERFACE_FAILED_MSG;
		}
		return $this->data;
	}//}}}
	//{{{AddEmailModifyRecord
	/**
	 * 添加Email修改记录
	 * @param unknown $arrChangeRow
	 * @return Ambigous <multitype:, number, boolean, string, mixed, $ret, multitype:string , Ambigous, 成功返回true, unknown, 该邮箱对应的校验码，失败为负数, $data, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 失败<0,, 返回user信息,, NULL, multitype:NULL , User, uc_User>
	 */
	 public function AddEmailModifyRecord($arrChangeRow){
		try {
			$tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=AddEmailModifyRecord';
	 		$arrChangeRow['source'] = '52cbbc55a24545b4bf3c273dad02b91c';
			$postData = "data=".json_encode($arrChangeRow);
			$objCurl = new Gj_Util_Curl();
			$res = $objCurl->post($tgApiUrl, $postData, true);
			Gj_Log::warning("crm:AddEmailModifyRecord;conds:".json_encode($arrChangeRow).';return:'.$res);
			$resArr = json_decode($res,true);
			if($resArr['succeed']){
				$this->data['data'] = $resArr['data']['message'];
			}else{
				$this->data['errorno'] = 2113;
				$this->data['errormsg'] = $resArr['error'];
			}
		} catch (Exception $e) {
			$this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
			$this->data['errormsg'] = ErrorConst::E_DB_FAILED_MSG;
		}
		return $this->data;
	}//}}}
	//{{{getUid
	/**
	 * 获取用户uid
	 * @param unknown $name   为email、phone、username三者之一
	 * @return Ambigous 失败<0, 不存在的情况下返回-1
	 */
	public function getUid($name){
		try{
			$res = $this->objInterfaceUser->getUid($name);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{getUser
	/**
	 * 根据use_id返回user信息
	 * @param unknown $user_id
	 * @return Ambigous 失败<0, 不存在的情况下返回-1
	 */
	 public function getUser($user_id){
		try{
			$res = $this->objInterfaceUser->getUser($user_id);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{getUser
	/**
	 * 根据use_id返回user信息
	 * @param unknown $user_id
	 * @return Ambigous 失败<0, 不存在的情况下返回-1
	 */
	public function batchGetUser($user_ids){
		try{
			$res = $this->objInterfaceUser->batchGetUser($user_ids);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
			return $this->data;
	}//}}}
	//{{{getBizTypeList
	/**
	 * 根据用户user_id，获取房产端口列表
	 * @param unknown $user_id
	 * @return Ambigous <multitype:, number, string, mixed, $ret, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getBizTypeList($user_id){
		try{
			$res = $this->objInterfaceUser->getBizTypeList($user_id);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{createEmailAuthCode
	/**
	 * 获取邮箱校验码
	 * @param unknown $user_id
	 * @param unknown $old_email
	 * @return Ambigous <multitype:, number, string, mixed, $ret, Ambigous, $data, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 失败<0,, unknown, 该邮箱对应的校验码，失败为负数>
	 */
	public function createEmailAuthCode($user_id, $old_email){
		try{
			$res = $this->objInterfaceUser->createEmailAuthCode($user_id, $old_email);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res <= 0) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{unauthEmail
	/**
	 * 解绑邮箱，如果客户端key不对，不能正确执行该方法
	 * @param unknown $user_id
	 * @param unknown $old_email
	 * @param unknown $code
	 * @return 成功返回true
	 */
	public function unauthEmail($user_id, $old_email, $code){
		try{
			$res = $this->objInterfaceUser->unauthEmail($user_id, $old_email, $code);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//{{{updateUser
	/**
	 * 更新用户信息，只能对未认证的phone、email，和除了passwd的以外的其他字段进行更新
	 * @param unknown $user,数组内必须包含user_id
	 * @return Ambigous <multitype:, number, string, mixed, Ambigous, $ret, $data, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 失败<0,, unknown, 该邮箱对应的校验码，失败为负数, 成功返回true>
	 */
	public function updateUser($user){
		try{
			$res = $this->objInterfaceUser->updateUser($user);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			$this->data['errorno'] = ErrorConst::E_INTERFACE_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_INTERFACE_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		return $this->data;
	}//}}}
	//addQueueAccountChange
	/**
	 * 把转端口之前的account_id和转端口之后的user_id入库，通过account_id获取user对象判断user_id是否发生改变，如果user_id不同则修改帖子的更新时间
	 * @param unknown $arrRows
	 * @return Ambigous <number, multitype:, mixed, string, Ambigous, $ret, unknown, $data, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 失败<0,, 该邮箱对应的校验码，失败为负数, 成功返回true>
	 */
	public function addQueueAccountChange($arrRows){
		if (!is_array($arrRows)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		}else{
			try{
				$accountObj = Gj_LayerProxy::getProxy('Dao_Tgqe_AccountChangeQueue');
				$res = $accountObj->insert($arrRows);
			} catch(Exception $e) {
				$this->data['errorno'] = $e->getCode();
				$this->data['errormsg'] = $e->getMessage();
			}
			if (!$res) {
				Gj_Log::warning($accountObj->getLastSQL());
				$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
				$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
			}else{
				$this->data['data'] = $res;
			}
		}
		return $this->data;
	}//}}}
	//{{{setPassword
	/**
	 * 没有用户原始密码的情况下，强制重设用户密码
	 * @param unknown $uid
	 * @param unknown $passwd
	 * @param string $desc			功能描述
	 * @return Ambigous <number, multitype:, string, mixed, Ambigous, $ret, unknown, 返回用户新的sscode信息,, $data, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 失败<0,, 该邮箱对应的校验码，失败为负数, 成功返回true>
	 */
	 public function setPassword($uid, $passwd, $desc = '房产经纪公司转端口'){
		try{
			$res = $this->objInterfaceUser->setPassword($uid, $passwd, $desc);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if (strstr($res, 'UC错误码') || $res == false || $res < 0) {
			if ($res == -2){
				$this->data = ErrorCode::returnData(2101);
			} else {
				$this->data = ErrorCode::returnData(2102);
			}
		}else{
			$this->data['data'] = true;
		}
		return $this->data;
	}//}}}
	/**
	 * 登陆
	 * @param string $username
	 * @param string $password
	 * @param array $privacy
	 *   -sessionid wap|唯一的ID, web|ganji_uuid, 客户端|customid
	 *   -captcha   验证码
	 *   -ua        useragent
	 * @return
	 *    false 参数格式错误
	 *    array 成功 arr[uid]>0 用户信息, 参考UserInterface::login
	 *    array 失败 arr[code], arr[desc]
	 */
	public function login($username, $password){
		try{
			$res = $this->objInterfaceUser->login($username, $password);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if(!$res){
			$this->data = ErrorCode::returnData(2120);
		}
		return $this->data;
	}
	//{{{getUserStatusFromCrm
	/**
	 * 获取经纪人修改资料的状态
	 * @param unknown $arrChangeRow
	 * @param arrChangeRow = array ('AccountIds' => '经济人id)
	 * @return $res = array('succeed'=>1,'data'=>array('result'=>''))
	 */
	public function getUserStatusFromCrm($arrChangeRow){
        if (empty($arrChangeRow['AccountIds'])) {
            $this->data['errorno'] = 2121;
            $this->data['errormsg'] = '参数错误';
            return $this->data;
        }
		try {
			$tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=BatchGetLastUpdateRecord';
			$objCurl = new Gj_Util_Curl();
			$arrChangeRow['source'] = '52cbbc55a24545b4bf3c273dad02b91c';
			$postData = "data=".json_encode($arrChangeRow);
			$objCurl = new Gj_Util_Curl();
			$res = $objCurl->post($tgApiUrl, $postData, true);
			Gj_Log::warning("crm:BatchGetLastUpdateRecord;conds:".json_encode($arrChangeRow).';return:'.$res);
			$resArr = json_decode($res,true);
			if($resArr['succeed']){
				$this->data['data'] = $resArr['data'];
			}else{
				$this->data['errorno'] = 2121;
				$this->data['errormsg'] = $resArr['error'];
			}
		} catch (Exception $e) {
			$this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
			$this->data['errormsg'] = ErrorConst::E_DB_FAILED_MSG;
		}
		return $this->data;
	}//}}}
    /* {{{ revertFields*/
    /**
     * @brief  转换字段名，CustomerId 转换为GroupId as CustomerId
     *
     * @param $arrFields 要查询的字段
     *
     * @returns  转换后的查询字段
     *
     */
    protected function revertFields($arrFields){
        $revertRelation = array(
            'CustomerId' => 'GroupId as CustomerId',        
        );
        foreach ($revertRelation as $key => $val) {
            $tmpKey = array_search($key, $arrFields);
            if (!(false===$tmpKey)) {
                $arrFields[$tmpKey] = $val;
            }
        }
        return $arrFields;
    }
    /* }}} */
}
