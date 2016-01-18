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
class Service_Page_HouseReport_Org_RootNode{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_Customer
	 */
	protected $objServiceCustomer;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	//{{{execute
	/**
	 * 获取账号信息
	 * @param array $arrInput				参数数组
	 * @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	 */
	public function execute($arrInput){
		if(!is_numeric($arrInput['id'])){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
		}else{
			try{
				$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
				$whereConds['id'] = $arrInput['id'];
				$res = $this->objServiceHouseManagerAccount->getOrgInfoByIdOrAccount($whereConds);
				if($res['errorno']){
					return $res;
				}
				//生成请求子节点的URL
				if($res['data']['level']!=4){
					$urlParams = array(
                                'c' => 'org',
								'a'=>'getChilds',
								'level' =>$res['data']['level'],
								'pid' =>$arrInput['id'],
						);
				}else{
					$urlParams = array(
                            'c' => 'org',
							'a'=>'getAgentList',
							'customer_id' =>$res['data']['customer_id'],
					);
				}
				$this->data['data'] = $res['data'];
				$this->data['data']['url'] = Util_CommonUrl::createUrl($urlParams);
				$whereConds = array(
						'company_id' =>$res['data']['company_id'],
						'pid' =>$arrInput['id'],
						'level' =>$res['data']['level']+1,
				);
				//子节点的数量
				$orgTotal = $this->objServiceHouseManagerAccount->getOrgCountByPid($whereConds);
				$this->data['data']['num'] = isset($orgTotal["data"]) ? $orgTotal["data"] : 0;
				//未分配的门店数据
				$this->objServiceCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
				$outLetTotal = $this->objServiceCustomer->getOutlet($res['data']['company_id']);
				$outLetCount = count($outLetTotal['data']);
				$this->data['data']['outletNum'] = $outLetCount;
			}catch (Exception $e){
				$this->data['errorno'] = $e->getCode();
		    	$this->data['errormsg'] = $e->getMessage();
			}
		}
		return $this->data;
	}//}}}
}
