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
class Service_Page_HouseReport_Org_AgentToBiz{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  '转端口成功';
		$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
	}
	 public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }   
    }  
	//{{{execute
	/**
	* 获取树状结构
	* @param array $arrInput
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		try{
			if (empty($arrInput['newemail']) || empty($arrInput['oldemail'])) {
				$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
				$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
				return $this->data;
			}
			if($arrInput['status']==2){
				$res = $this->authEmail($arrInput);
				if($res['errorno']){
					return $res;
				}
				/* if($res['errorno']){//转端口失败
					$this->data = $res;
				}else{
					$accountChange = $this->addQueueAccountChange($arrInput['AccountId'], $res['data']);
					if($res['errorno']){
						return $res;
					}
					$this->data = $res;
				} */
			}else{
				$res = $this->unauthEmail($arrInput);
				if($res['errorno']){
					return $res;
				}
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
	//未注册过会员中心
	protected function unauthEmail($arrInput){
		$user = array(
				'user_id' => $arrInput['UserId'],
				'email' => $arrInput['newemail'],
		);
		//如果邮箱已经认证，解绑认账邮箱
		$res = $this->objServiceCustomerAccount->getUser($arrInput['UserId']);
		if ($res['data']['email_auth_time'] > 10000) {
			$res = $this->objServiceCustomerAccount->createEmailAuthCode($arrInput['UserId'], $arrInput['oldemail']);
			if ($res['errorno']) {
				return $res;
			}
			$res = $this->objServiceCustomerAccount->unauthEmail($arrInput['UserId'], $arrInput['oldemail'], $res['data']);
			if($res['errorno']){
				return $res;
			}
		}
		$res = $this->objServiceCustomerAccount->updateUser($user);
		$log_msg = "unauthEmail:{$arrInput['UserId']}_{$arrInput['oldemail']}->".$arrInput['newemail'];
		Gj_Log::trace($log_msg);
		return $res;
	}
	//已经注册过会员中心
	protected function authEmail($arrInput){
		//验证新的用户密码、密码是否正确
		$resLogin = $this->objServiceCustomerAccount->login($arrInput['newemail'], $arrInput['passwd']);
		if($resLogin['errorno']){
			return $resLogin;
		}
		$userData=$this->objServiceCustomerAccount->getUid($arrInput['newemail']);
		if($userData['errorno']){
			return $userData;
		} 
		$user_id = $userData['data'];
		/* $arrChangeRow = array (
				'AccountId' => $arrInput['AccountId'],
				'CreatorId' => $userData['data']['user_id'],
				'CreatorName' => $userData['data']['user_name'],
				'NewUserId' => $res['data']['user_id'],
				'Key' =>  $arrInput['AccountId']."_".time(),
		); */
		/* $arrChangeRow = array (
				'AccountId' => $arrInput['AccountId'],
				'CreatorId' => $arrInput['CreatorId'],
				'CreatorName' => $arrInput['CreatorName'],
				'NewUserId' => $user_id,
				'Key' =>  $arrInput['AccountId']."_".time(),
		);
		$res = $this->objServiceCustomerAccount->UpdateProfile($arrChangeRow); */
		$arrChangeRow = array (
				'fromUserId' => $arrInput['UserId'],
				'operatorId' => $arrInput['CreatorId'],
				'operatorName' => $arrInput['CreatorName'],
				'toUserId' => $user_id,
		);
		$resBiz = $this->objServiceCustomerAccount->TransferBalance($arrChangeRow);
		$log_msg = $arrInput['CreatorId']."_".json_encode($arrChangeRow)."_".json_encode($resBiz);
		Gj_Log::warning($log_msg);
		if($resBiz['errorno']){
			return $resBiz;
		}
		//基本资料修改成功，请您用新邮箱重新登录！
		/* $arrChangeRow = array (
				'AccountId' => $arrInput['AccountId'],
				'CreatorId' => $userData['user_id'],
				'CreatorName' => $userData['user_name'],
				'OldEmail' => $arrInput['oldemail'],
				'Email' => $arrInput['newemail'],
		); */
		$arrChangeRow = array (
				'AccountId' => $arrInput['AccountId'],
				'CreatorId' => $arrInput['CreatorId'],
				'CreatorName' => $arrInput['CreatorName'],
				'OldEmail' => $arrInput['oldemail'],
				'Email' => $arrInput['newemail'],
		);
		$resRecord = $this->objServiceCustomerAccount->AddEmailModifyRecord($arrChangeRow);
		if($resRecord['errorno']){
			$mailbody = "ChangeEmail Failed!{$arrInput['CreatorId']}_".json_encode($arrChangeRow)."_".json_encode($resRecord);
			$edm = array('Mail'=>'zhangliming@ganji.com', 'Sid'=>'fang-alert', 'Type'=>1, 'Subject'=>"修改邮箱调用method:AddEmailModifyRecord接口失败", 'mailbody' => $mailbody);
			$edm2 = array('Mail'=>'fanlinchong@ganji.com', 'Sid'=>'fang-alert', 'Type'=>1, 'Subject'=>"修改邮箱调用method:AddEmailModifyRecord接口失败", 'mailbody' => $mailbody);
			if(!class_exists("EdmMailNamespace")){
				Gj\Gj_Autoloader::addClassMap(array("EdmMailNamespace"=>CODE_BASE2 . '/interface/edm/EdmMailNamespace.class.php'));
			}
			EdmMailNamespace::sendMail($edm);
			EdmMailNamespace::sendMail($edm2);
		}
		$log_msg = "ChangeEmail success!{$arrInput['CreatorId']}_".json_encode($arrInput)."_newUserId:_".$user_id;
		Gj_Log::warning($log_msg);
		$this->data['data'] = $user_id;
		return $this->data;
	}
	//{{{addQueueAccountChange
	/**
	 * 转端口成功，插入到队列，处理帖子的最后发表时间
	 * @param unknown $account_id				转端口之前的account_id
	 * @param unknown $user_id					转端口之后的user_id
	 */
	protected function addQueueAccountChange($account_id, $user_id){
		$arrFileds = array(
				'account_id' => $account_id,
				'user_id' => $user_id,
				'action' => 7,
				'value' => 108,
				'create_at' => time(),
				'author' => 'EMP',
		);
		$accountChange = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountChange');
		$res = $accountChange->addAccountChange($arrFileds);
		return $res;
	}//}}}
}
