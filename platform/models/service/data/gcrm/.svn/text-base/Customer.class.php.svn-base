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
class Service_Data_Gcrm_Customer{
	/**
	 * @var Dao_Gcrm_Customer
	 */
	protected $objDaoCustomer;
	/**
	 * @var Dao_Gcrm_ChannelCustomerExtend
	 */
	protected $objDaoChannelCustomerExtend;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
    protected $arrFields = array("id","full_name","city_id","district_id","street_id","company_id","company_name");
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objDaoCustomer = Gj_LayerProxy::getProxy('Dao_Gcrm_Customer');
		$this->objDaoChannelCustomerExtend = Gj_LayerProxy::getProxy('Dao_Gcrm_ChannelCustomerExtend');
		$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
	}
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}
	}
	//{{{getCustomerInfoListByCompanyId
	/**
	 * 获取门店列表
	 * @param unknown $company_id			公司id
	 * @param string $FullName					公司名称
	 * @param unknown $arrFields				查询字段数组
	 * @param number $page						页数
	 * @param number $pageSize					每页条数
	 * @return Ambigous <multitype:, string, $ret, number, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getCustomerInfoListByCompanyId($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        $arrFields = $this->fieldNameRevert($arrFields);
		$arrConds = $this->getCustomerWhere($whereConds);
		 $this->objDaoCustomer = Gj_LayerProxy::getProxy('Dao_Gcrm_Customer');
    	try{
    		$res = $this->objDaoCustomer->selectCustomerByPage($arrFields, $arrConds, $page, $pageSize, $orderArr);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoCustomer->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
    		$this->data['data'] = $res;
    	}
		$this->data['data'] = $res;
		return $this->data;
	}//}}}
	//{{{getCustomerCountByCompanyId
	/**
	 * 获取门店数量
	 * @param unknown $company_id
	 * @param string $FullName
	 * @return Ambigous <multitype:, string, $ret, number, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getCustomerCountByCompanyId($whereConds){
		$arrConds = $this->getCustomerWhere($whereConds);
		try{
			$res = $this->objDaoCustomer->selectCustomerByCount($arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		$this->data['data'] = $res;
		return $this->data;
	}//}}}
	//{{{getCustomerInfoByCustomerId
	/**
	 * 根据customer_id,获取门店信息
	 * @param unknown $customer_id		门店id
	 * @param unknown $arrFields			需要获取的字段 目前获取信息都只包含id full_name company_name company_id 
	 */
	public function getCustomerInfoByCustomerId($customer_id, $arrFields=array()){
        $arrFields = $this->fieldNameRevert($arrFields);
		$arrConds = array(
				'id =' => $customer_id,
		);
		try {
			$res = $this->objDaoCustomer->select($arrFields, $arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			Gj_Log::warning($this->objDaoCustomer->getLastSQL());
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}else{
			$this->data['data'] = $res[0];
		}
		return $this->data;
	}//}}}
	//{{{getOutlet
	/**
	 * 获取某个公司未分配门店
	 * @param unknown $company_id		公司id
	 * @return Ambigous <Ambigous, multitype:, unknown, string, $ret, number, boolean, 结果数组>
	 */
	public function getOutlet($company_id, $customer_name=NULL){
		$arrFields = array("id as CustomerId","company_id as CompanyId","full_name as FullName");
		try{
			//获取公司下面的所有门店
			$whereConds=array('CompanyId'=>$company_id);
			if(!empty($customer_name)){
				$whereConds['FullName'] = $customer_name;
			}
			$res = $this->getCustomerInfoListByCompanyId($whereConds, $arrFields, 1, NULL);
			$customerIdArr = array();
			$newCompanyCustomer = array();
			if(!empty($res['data']) && function_exists(array_column)){
				$customerIdArr = array_column($res['data'],'CustomerId');
				$newCompanyCustomer = array_column($res['data'], NULL, 'CustomerId');
			} else if (!empty($res['data'])) {
				foreach ($res['data'] as $row){
					$customerIdArr[] = $row['CustomerId'];
					$newCompanyCustomer[$row['CustomerId']] = $row;
				}
			}
			//获取组织架构下面已经分配的门店
			$whereCondsOrg = array(
					'company_id' => $company_id,
					'level' => 4
			);
			if(!empty($customer_name)){
				$whereCondsOrg['title'] = $customer_name;
			}
			$arrFields = array('customer_id');
			$customerInfo = $this->objServiceHouseManagerAccount->getOrgInfoListByPid($whereCondsOrg, $arrFields, 1, NULL);
			$OrgCustomerIdArr = array();
			$OrgCompanyCustomer = array();
			if(function_exists(array_column)){
				$OrgCustomerIdArr = array_column($customerInfo['data'],'customer_id');
				$OrgCompanyCustomer = array_column($customerInfo['data'], NULL, 'customer_id');
				
			}else{
				foreach ($customerInfo['data'] as $row){
					$OrgCustomerIdArr[] = $row['customer_id'];
					$OrgCompanyCustomer[$row['customer_id']] = $row;
				}
			}
			//去除已分配的门店
			$outLetCompanyCustomerIdArr = array_diff_key($newCompanyCustomer, $OrgCompanyCustomer);
			$this->data['data'] = array_values($outLetCompanyCustomerIdArr);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}//}}}
	//{{{getChannelCoustomerInfo
	/**
	 * 获取未分配门店信息
	 * @param unknown $customer_id,数组或者id
	 * @return Ambigous <multitype:, string, $ret, number, boolean, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function getChannelCoustomerInfo($customer_id){
		if (is_array($customer_id)) {
			$arrConds = array(
					'customer_id in (' . implode(',', $customer_id) . ')',
			);
		}else{
			$arrConds = array(
					'customer_id =' => $customer_id,
			);
		}
		try{
			$res = $this->objDaoChannelCustomerExtend->selectInfoByCustomerId($arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		if ($res === false) {
			Gj_Log::warning($this->objDaoChannelCustomerExtend->getLastSQL());
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}else{
			$this->data['data'] = $res;
		}
		$this->data['data'] = $res;
		return $this->data;
	}//}}}
    /* {{{ fieldNameRevert*/
    /**
     * @brief 当前dataservice 原来是为了管理customer表，后来crm表迁移工作字段名有修改，该函数用于保证出参格式
     *
     * @param $arrFields array 要查询的字段
     *
     * @returns  array 
     */
    protected function fieldNameRevert($arrFields){
        $ret = array();
        $revertRelation = array(
            'CustomerId' => 'id as CustomerId',
            'FullName' => 'full_name as FullName',
            'CompanyId' => 'company_id as CompanyId',  
            'CompanyName' => 'company_name as CompanyName'
        );
        //if (!is_array($arrFields) || !in_array($arrFields[0], array_keys($revertRelation))) {
        if (count($arrFields)<=0) {
            $arrFields = $this->arrFields;
        }
        if (!is_array($arrFields) || !count(array_intersect($arrFields, array_keys($revertRelation)))) {
            $ret = $arrFields;
        } else {
            foreach ($arrFields as $key) {
                if (isset($revertRelation[$key])) {
                    $ret[] = $revertRelation[$key];
                }else{
                    $ret[] = $key;
                }
            }
        }
        return $ret;
    }
    /* }}} */
	//{{{getOrgWhere
	/**
	 * 组装查询条件
	 * @codeCoverageIgnore
	 * @param unknown $whereConds
	 * @return multitype:number unknown
	 */
	protected function getCustomerWhere($whereConds){
        $arrConds = array();
		if(!empty( $whereConds['CompanyId'])){
			$arrConds['company_id ='] = $whereConds['CompanyId'];
		}
		if (!empty($whereConds['FullName'])) {
			array_push($arrConds, "full_name like '%".$whereConds['FullName']."%'");
		}
		if(!empty($whereConds['CustomerId'])){
				if(is_array($whereConds['CustomerId'])){
					$CustomerIds = implode(',',$whereConds['CustomerId']);
                    array_push($arrConds, "id in ( $CustomerIds )");
				}else{
					$arrConds['id ='] = $whereConds['CustomerId'];    
       		    }		   
        }
        if(!empty($whereConds['GroupId'])){
            if(is_array($whereConds['GroupId'])){
                $GroupIds = implode(',',$whereConds['GroupId']);
                array_push($arrConds, "id in ( $GroupIds )");
            }else{
                $arrConds['id ='] = $whereConds['GroupId'];
            }
        }
		return $arrConds;
	}//}}}
    //{{{getGroupInfoByUserIdOrAccountId
    /**获取经纪人所在组信息,最多支持30个
     * @param null $user_id         用户user_id
     * @param null $account_id      用户account_id
     * @param array $arrFields      组信息+user_id+account_id
     * @return array
     */
    public function getGroupInfoByUserIdOrAccountId($user_id = NULL, $account_id = NULL, $arrFields=array()){
        try {
            $this->objCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
            $arrAccountFields = array('GroupId', 'UserId', 'AccountId');

            if (empty($user_id) && empty($account_id)) {
                $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->data;
            } else if (!empty($user_id)) {
                $resGroupIdArr = $this->objCustomerAccount->getAccountInfoByUserId($user_id, $arrAccountFields);
            } else if (!empty($account_id)) {
                $resGroupIdArr = $this->objCustomerAccount->getAccountInfoById($account_id, $arrAccountFields);
            }
            $groupIdArr = array();//存放查询出来的所属组id
            $accountInfoArr = array();//存放用户信息
            $groupInfoArr = array();//存放用户组信息
            //组装CustomerAccount信息，用于查询所属组信息以及合并所属组信息
            if (!$resGroupIdArr['errorno'] && !empty($resGroupIdArr['data'])) {
                if (!empty($resGroupIdArr['data']) && function_exists(array_column)) {
                    $groupIdArr = array_column($resGroupIdArr['data'], 'GroupId');
                    $accountInfoArr = array_column($resGroupIdArr['data'], NULL, 'GroupId');
                } else if (!empty($resGroupIdArr['data'])) {
                    foreach ($resGroupIdArr['data'] as $row) {
                        $groupIdArr[] = $row['GroupId'];
                        $accountInfoArr[$row['GroupId']] = $row;
                    }
                }
            }else{
                return $resGroupIdArr;
            }
            //获取所属组信息
            if (count($groupIdArr) > 0) {
                $whereConds = array(
                    'GroupId' => $groupIdArr,
                );
                $arrFields = $this->fieldNameRevert($arrFields);
                $resGroup = $this->getCustomerInfoListByCompanyId($whereConds, $arrFields);
            }
            //组装Customer信息，用于查询所属组信息以及合并所属组信息
            if(!$resGroup['errorno'] && !empty($resGroup['data'])){
                if (!empty($resGroup['data']) && function_exists(array_column)) {
                    $groupInfoArr = array_column($resGroup['data'], NULL, 'id');
                } else if (!empty($res['data'])) {
                    foreach ($res['data'] as $row) {
                        $groupInfoArr[$row['id']] = $row;
                    }
                }
            }else{
                return $resGroupIdArr;
            }
            $returnData = array();
            //根据GroupID合并用户和组信息
            foreach($accountInfoArr as $key=>$row){
                if(array_key_exists($key,$groupInfoArr)){
                    $returnData[] = array_merge($groupInfoArr[$key],$row);
                }else{
                    $returnData[] = $row;
                }
            }
            $this->data['data'] = array();
            $this->data['data'] = $returnData;
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }//}}}

	/**
	 * 根据GroupId获取商户门店组信息
	 * @param $groupId
	 * @param array $arrFields
	 * @return array
	 * @author 刘海鹏 <liuhaipeng1@ganji.com>
	 * @create 2015-07-16
	 */
	public function getCustomerInfoByGroupId($groupId, $arrFields = array()) {
		$arrFields = $this->fieldNameRevert($arrFields);
		$arrConds = array('id =' => $groupId);
		try {
			$res = $this->objDaoCustomer->select($arrFields, $arrConds);
		} catch (Exception $e) {
			$this->data['errorno']	= $e->getCode();
			$this->data['errormsg']	= $e->getMessage();
		}
		if ($res === false) {
			Gj_Log::warning($this->objDaoCustomer->getLastSQL());
			$this->data['errorno']	= ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg']	= ErrorConst::E_SQL_FAILED_MSG;
		} else {
			$this->data['data'] = $res[0];
		}
		return $this->data;
	}
}
