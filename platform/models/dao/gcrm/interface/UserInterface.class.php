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
class Dao_Gcrm_Interface_UserInterface{
	protected $arrMap;
	const UCCENTER_TYPE = 1;// type包含  #1-会员中心账户/10-交友账户/38-PPD专用账户/41-二手交易担保账户
	const INTERFACE_TYPE ="get_account";//$interfaceType 调用会员中心接口的类型账户类型或者批量获取账户信息接口等等 get_account表示会员中心账户信息 
	const SECRETKEY = "909fekfioifef77feflkefef77a74ai";   //根据频道的不同，会有不通的秘钥 这个是type类型为1的秘钥	
	public function __construct(){
		$this->arrMap = array(
				'UserInterface'=>CODE_BASE2 . '/interface/uc/UserInterface.class.php',
				'LoginNamespace'=>CODE_BASE2 . '/interface/login/LoginNamespace.class.php',
				'UserBizInterface'=>CODE_BASE2 . '/interface/uc/UserBizInterface.class.php',
				'UserAuthInterface'=>CODE_BASE2 . '/interface/uc/UserAuthInterface.class.php',
				'UserNamespace'=>CODE_BASE2 . '/app/user2/UserNamespace.class.php',
				'PayCenterInterface' =>CODE_BASE2 .'/interface/pay/PayCenterInterface.class.php',
			//	'SelfBiddingFangAccount' =>CODE_BASE2 . '/app/self_bidding/module/SelfBiddingFangAccount.class.php',
		);
		foreach ($this->arrMap as $class_name=>$class_path){
			if(!class_exists($class_name)){
				Gj\Gj_Autoloader::addClassMap(array($class_name=>$class_path));
			}
		}
	}
	//{{{getUid
	/**
	 * 获取用户uid
	 * @param unknown $name   为email、phone、username三者之一
	 * @return Ambigous 失败<0, 不存在的情况下返回-1
	 */
	public function getUid($name){
		return UserInterface::getUid($name);
	}//}}}
	//{{{getUser
	/**
	 * @brief 根据use_id返回user信息
	 *
	 * @param $use_id
	 * @param bool $forbidcache 禁止静态内存缓存(只建议某些连接时间较长脚本true,其他默认false)
	 *
	 * @return 返回user信息, passwd为空
	 */
	public function getUser($use_id, $forbidcache=false){
		return UserInterface::getUser($use_id, $forbidcache);
	}//}}}
	//{{{batchGetUser
	/**
	 * @brief 根据uid数组返回user简要信息
	 * @param $uids array 查询的uid列表(每次最多500个uid)
	 * @return users array 返回user简要信息列表
	 */
	public function batchGetUser($user_ids){
		return UserInterface::batchGetUser($user_ids);
	}//}}}
	//{{{getBizTypeList
	/**
	 * 根据用户uid，获取房产端口列表
	 * @param unknown $uid
	 * @return Ambigous <$data, multitype:, unknown, BizTypes, string, uc_BizTypes>
	 */
	public function getBizTypeList($uid){
		return UserBizInterface::getBizTypeList($uid);
	}//}}}
	//{{{createEmailAuthCode
	/**
	 * 获取邮箱校验码
	 * @param unknown $uid
	 * @param unknown $email
	 * @return Ambigous <该邮箱对应的校验码，失败为负数, unknown, string>
	 */
	public function createEmailAuthCode($uid, $email){
		return UserAuthInterface::createEmailAuthCode($uid, $email);
	}//}}}
	//{{{unauthEmail
	/**
	 * 解绑邮箱，如果客户端key不对，不能正确执行该方法
	 * @param unknown $unauthUid
	 * @param unknown $oldemail
	 * @param unknown $unauthCode
	 * @return Ambigous <成功返回true, boolean>
	 */
	public function unauthEmail($unauthUid, $oldemail, $unauthCode){
		return UserAuthInterface::unauthEmail($unauthUid, $oldemail, $unauthCode);
	}//}}}
	//{{{updateUser
	/**
	 * 更新用户信息，只能对未认证的phone、email，和除了passwd的以外的其他字段进行更新
	 * @param unknown $user
	 * @return Ambigous <成功返回true, boolean, string>
	 */
	public function updateUser($user){
		return UserInterface::updateUser($user);
	}//}}}
	//{{{setPassword
	/**
	 * 没有用户原始密码的情况下，强制重设用户密码
	 * @param unknown $uid
	 * @param unknown $passwd
	 * @param string $desc				功能描述
	 * @return Ambigous <返回用户新的sscode信息,, mixed>
	 */
	public function setPassword($uid, $passwd, $desc = '房产经纪公司转端口'){
		return UserAuthInterface::setTuiguangPassword($uid, $passwd, $desc);
	}//}}}
	//{{{login
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
	 *    返回值 user.uid的值：密码错误 -1，没有这个用户，填入信息有误 -2，用户被锁定-3，强制用户修改密码-4，正常返回 uid
	 */
	public function login($username, $password){
		return UserNamespace::isValidPassword($username, $password);
		//return LoginNamespace::login($username, $password,array());
	}//}}}
	/***
	 **主要获取用户中心的余额    
	 ** type包含  #0-会员中心账户/10-交友账户/38-PPD专用账户/41-二手交易担保账户
	 **ucenterId 用户中心user_id
	 ** $interfaceType 调用会员中心接口的类型账户类型或者批量获取账户信息接口等等
	 ** $secretKey根据频道的不同，会有不通的秘钥
	 **http://wiki.corp.ganji.com/%E6%94%AF%E4%BB%98%E6%8E%A5%E5%8F%A3#.E8.8E.B7.E5.8F.96.E8.B4.A6.E6.88.B7.E4.BF.A1.E6.81.AF.E6.8E.A5.E5.8F.A3
	 **返回值为会员中心的余额
	 *获取会员中心余额
	 */
	public function getUserCenterBalanceMoney($ucenterId){
		$centerInfo = array();
		$userCenterBalance = 0;
		$params = new stdClass();
		$key = self::SECRETKEY;
		$params->op = self::INTERFACE_TYPE ;
		$params->user_id = $ucenterId;
		$params->channel  = self::UCCENTER_TYPE;
		$sign = md5(json_encode($params) . $key);    
		$centerInfo= PayCenterInterface::getAccount($params, $sign);    
		if(is_object($centerInfo)){
			if($centerInfo->is_success != F){ 
				$userCenterBalance = ($centerInfo->balance)/100;
			}   
		}   
		return  $userCenterBalance; 
	}   
	/** 
	 * @brief获得房产竞价余额
	 * **/
	public function getBalance($ucenterId,$cityId = 0){ 
		$objgetBalance = new SelfBiddingFangAccount($ucenterId);    
		$balanceMoney = $objgetBalance->getBalance($cityId);
		return $balanceMoney;
	}  
}
