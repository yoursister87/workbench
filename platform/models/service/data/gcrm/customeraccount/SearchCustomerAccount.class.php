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
class Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount{
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceHouseCustomerAccount;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objDaoCustomerAccount = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccount');
		$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
	}
    //{{{SearchAgent
    /**
     * 
     * @param unknown $whereConds 必选参数 search_keyword(关键字) search_type(搜索类型) id (orgid)  可选参数 isShowParent  是否显示父节点
     * @return Ambigous <Ambigous, number, multitype:, string, mixed, $ret, 成功返回true, boolean, unknown, multitype:string , 返回user信息,, NULL, multitype:NULL , $data, 失败<0,, 结果数组, 结果数组：成功；false：失败, multitype:unknown , 该邮箱对应的校验码，失败为负数>|Ambigous <multitype:, string>
     */
    public function SearchAgent($whereConds){
		try{
			$arrConds = array();
			if ($whereConds['search_type']==2) {//account_id
				$arrConds = array('account_id'=>$whereConds['search_keyword']);
			}else if($whereConds['search_type']==3) {//account_name
				$arrConds = array('account_name'=>$whereConds['search_keyword']);
			}else if($whereConds['search_type']==4) {//cell_phone
				$arrConds = array('cell_phone'=>$whereConds['search_keyword']);
			}else if($whereConds['search_type']==5){//注册时间
				$arrConds = array(
						'sTime'=>$whereConds['sTime'],
						'eTime'=>$whereConds['eTime'],
				);
			//是否显示不过期账号
            } 
            if ($whereConds['induration'] === true) {
                $today = strtotime('today');
                $arrConds['s_premier_expire'] = $today;
            }
			//获取满足搜索条件的经纪人
			$arrFields = array("AccountId","CustomerId","AccountName","CreatedTime","UserId","CellPhone","Email","Status","OwnerType","PremierExpire");
			$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
			$agentList = $this->objServiceCustomerAccount->getAccountListByCustomerId($arrConds, $arrFields, 1, NULL);
			if($agentList['errorno']){
				return $agentList;
			}
			//获取当前搜索公司下的所有门店
			$customerList = $this->getCustomerListByOrgIdLevel($whereConds['id']);
			$customerIdArr = $customerList[0];
			$newOrgList = $customerList[1];
			//遍历搜索出来满足条件的经纪人
			foreach ($agentList['data'] as $row){
				if (in_array($row['CustomerId'], $customerIdArr)) {
					$id = $newOrgList[$row['CustomerId']]['id'];
					//父节点路径
					if($whereConds['isShowParent']){
						$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy("Service_Data_Gcrm_HouseManagerAccount");
						$parentNode = $this->objServiceHouseManagerAccount->getTreeByOrgId($id, false);
						if (is_array($parentNode['data'])) {
							$row['FullName'] = $parentNode['data'][2]['activeList']['title']."=>".$parentNode['data'][3]['activeList']['title']."=>".$parentNode['data'][4]['activeList']['title'];
							$row['company_id'] = $newOrgList[$row['CustomerId']]['company_id'];
							$row['id'] = $newOrgList[$row['CustomerId']]['id'];
						}
					}
					$this->data['data'][] = $row;
				}
			}
			if($whereConds['isShowEamil']){
				$resToEmail = $this->getCrmEmail($this->data['data']);
				$this->data['data'] = $resToEmail['data'];
			}
		}catch (Exception $e){
			$data['errorno'] = $e->getCode();
			$data['errormsg'] = $e->getMessage();
		}
		return $this->data;
    }
    //}}}
	//{{{getCustomerListByOrgIdLevel
	/**
	 * 获取组织架构下的所有门店
	 * @param unknown $orgId
	 * @return unknown|multitype:multitype:unknown
	 */
	public function getCustomerListByOrgIdLevel($orgId){
		$arrFields = array("id","pid","company_id","customer_id","level","title");
		$orgList = $this->objServiceHouseManagerAccount->getChildTreeByOrgId($orgId, 4, array(), 1, NULL);
		if($orgList['errorno']){
			return $orgList;
		}
		//把不是当前公司下的经纪人剔除
		//遍历该公司下所有门店
		$customerIdArr = array();
		$newOrgList = array();
		foreach ($orgList['data']['list'] as $row){
			$customerIdArr[] = $row['customer_id'];
			$newOrgList[$row['customer_id']] = $row;
		}
		return $customerList = array(
				$customerIdArr,
				$newOrgList
		);
	}//}}}
	
	//从crm获取邮箱
	public function getCrmEmail($res){
		$newRes = array();
		$userIdArr = array();
		$AccountIdArr = array();
		$newResult = array();
		$arrFields = array("CustomerId","FullName","CompanyId");
		$this->objServiceHouseCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
		$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
		foreach ($res as $row){
			if($row['PremierExpire']>time()){
				$row['PremierExpire'] = "生效中";
			}else{
				$row['PremierExpire'] = "已过期";
			}
			$row['key'] = md5($row['UserId'].'_'.$row['Email']);
			$customerInfo = $this->objServiceHouseCustomer->getCustomerInfoByCustomerId($row['CustomerId'], $arrFields);
			$row['FullName'] = empty($customerInfo['data']["FullName"]) ? '' : $customerInfo['data']["FullName"];
			$newRes[$row['UserId']] = $row;
			$newResult[] = $row;
			$userIdArr[] = $row['UserId'];
			$AccountIdArr[] = $row['AccountId'];
		}
		//$userIdArr = array('500008706','8014','500008706','8014','500008706','8014','500008706');
		$crmEmail = $this->objServiceCustomerAccount->batchGetUser($userIdArr);
		/* 		if (count($userIdArr) !== count($crmEmail['data'])) {
		 return $newResult;
		} */
		$newCrmEmail = array();
		if(is_array($crmEmail['data'])){
			foreach ($crmEmail['data'] as $row){
				$row['Email'] = $row['email'];
				unset($row['email']);
				$row['key'] = md5($row['user_id'].'_'.$row['Email']);
				$newCrmEmail[$row['user_id']] = $row;
			}
		}
		$arrChangeRow = array('AccountIds'=>implode(',', $AccountIdArr));
		$crmStatus = $this->objServiceCustomerAccount->getUserStatusFromCrm($arrChangeRow);
		$newCrmRes = array();
		foreach ($newRes as $key=>$row){
			if($crmStatus['errorno']){
				$row['crmStatus'] = 1;
			}else if(array_key_exists($row['AccountId'], $crmStatus['data'])){
				$row['crmStatus'] = $crmStatus['data'][$row['AccountId']]['status'];
				if (isset($crmStatus['data'][$row['AccountId']]['refusedReason'])) {
					$row['refusedReason'] = $crmStatus['data'][$row['AccountId']]['refusedReason'];
				}
			}else{
				$row['crmStatus'] = 1;
			}
			if(is_array($newCrmEmail[$key])){
				$newCrmRes[] = array_merge($row, $newCrmEmail[$key]);
			}else{
				$newCrmRes[] = $row;
			}
		}
		$this->data['data'] = $newCrmRes;
		return $this->data;
	}
}
