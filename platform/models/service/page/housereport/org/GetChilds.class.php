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
class Service_Page_HouseReport_Org_GetChilds{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceHouseCustomerAccount;
	/**
	 * @var Service_Data_Gcrm_CompanyStoresAddress
	 */
	protected $objServiceCompanyStoresAddress;
	/**
	 * @var Service_Data_Gcrm_CustomerAccountLoginEvent
	 */
	protected $objServiceCustomerAccountLoginEvent;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	//{{{execute
	/**
	 * 获取树状结构
	 * @param array $arrInput
	 * @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	 */
	public function execute($arrInput){
		$arrFields = array("id","pid","company_id","customer_id","level", "title","name","account","phone");
		if (empty($arrInput['sTime'])){
			//$arrInput['sTime'] = strtotime('yesterday');
			$arrInput['sTime'] = strtotime('today');
		}else{
			$arrInput['sTime'] = strtotime($arrInput['sTime']);
		}
		if (empty($arrInput['eTime'])) {
			//$arrInput['eTime'] = strtotime('today')-1;
			$arrInput['eTime'] = strtotime('Tomorrow')-1;
		}else{
			$arrInput['eTime'] = strtotime($arrInput['eTime'])+24*3600-1;
		}
		if(!is_numeric($arrInput['company_id']) || !is_numeric($arrInput['pid']) || !is_numeric($arrInput['level']) || $arrInput['sTime']==0 || $arrInput['eTime']==0){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		$level = $arrInput['level'] + 1;
		try{
			$whereConds = array(
					'company_id' =>$arrInput['company_id'],
					'pid' =>$arrInput['pid'],
					'level' =>$level,
			);
			$orderArr = array('create_time'=>'DESC');
			$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
			$res = $this->objServiceHouseManagerAccount->getOrgInfoListByPid($whereConds, $arrFields, 1, NULL, $orderArr);
			if($res['errorno']){
				return $res;
			}
			foreach ($res['data'] as $key=>$row){
				//生成请求子节点的URL
				$urlParams = array(
						'a'=>'getChilds',
						'level' =>$row['level'],
						'pid' =>$row['id'],
				);
				if ($level==4) {
					$urlParams['a'] = 'getAgentList';
					$urlParams['customer_id'] = $row['customer_id'];
				}
				$this->data['data']['data'][$key] = $row;
				$this->data['data']['data'][$key]['url'] = Util_CommonUrl::createUrl($urlParams);
				//子节点数量
				if($level!=4){
					$whereConds = array(
							'company_id' =>$arrInput['company_id'],
							'pid' =>$row['id'],
							'level' =>$row['level'] + 1,
					);
					$orgTotal = $this->objServiceHouseManagerAccount->getOrgCountByPid($whereConds);
				}else if($level==4){
					//经纪人的数量
					$whereConds = array(
							'customer_id'=>$row['customer_id'],
							'sTime'=>$arrInput['sTime'],
							'eTime'=>$arrInput['eTime'],
					);
					$this->objServiceHouseCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
					$orgTotal = $this->objServiceHouseCustomerAccount->getAccountCountByCustomerId($whereConds);
					//获取门店地址
					$arrFileds = array('id','district_id','district_name','street_id','street_name','address');
					$this->objServiceCompanyStoresAddress = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CompanyStoresAddress');
					$addressInfo = $this->objServiceCompanyStoresAddress->getStoreInfoByUserId(NULL, $row['customer_id'], NULL, NULL, $arrFileds);
					$this->data['data']['data'][$key]['address_id'] = $addressInfo['data']['id'];
					$this->data['data']['data'][$key]['district_id'] = $addressInfo['data']['district_id'];
					$this->data['data']['data'][$key]['street_id'] = $addressInfo['data']['street_id'];
					$this->data['data']['data'][$key]['address'] = $addressInfo['data']['address'];
				}
				$this->data['data']['data'][$key]['num'] = empty($orgTotal["data"]) ? 0 : $orgTotal["data"];
				//子节点的登录数
				if($arrInput['level']!=4){
					$this->objServiceCustomerAccountLoginEvent = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccountLoginEvent');
					$loginTotal = $this->objServiceCustomerAccountLoginEvent->getCustomerLoginCount($row['id'], $arrInput['sTime'], $arrInput['eTime']);
					$this->data['data']['data'][$key]['loginNum'] = empty($loginTotal["data"]) ? 0 : $loginTotal["data"];
				}
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}//}}}
}
