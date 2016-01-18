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
class Service_Page_HouseReport_Org_OutLet{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_Customer
	 */
	protected $objServiceCustomer;
	/**
	 * @var Service_Data_Gcrm_CustomerAccount
	 */
	protected $objServiceCustomerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objServiceCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
		$this->objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
	}
	//{{{execute
	/**
	* 获取账号信息
	* @param array $arrInput				参数数组
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		if(!is_numeric($arrInput['company_id'])){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		if (empty($arrInput['page'])){
			$arrInput['page'] = 1;
		}
		if (empty($arrInput['pageSize'])){
			$arrInput['pageSize'] = 20;
		}
		if ($arrInput['search_keyword'] == '输入门店名称') {
			$arrInput['search_keyword'] = NULL;
		}
		try{
			$res = $this->objServiceCustomer->getOutlet($arrInput['company_id'],$arrInput['search_keyword']);
			if($res['errorno']){
				return $res;
			}
			$pageConfig = array(
					'total_rows' =>count($res['data']),
					'list_rows' => $arrInput['pageSize'],
					'now_page' =>$arrInput['page'],
					'method' =>'ajax',
					'func_name'=>'pagination',
					'parameter' =>"'outLet'",
			);
			$pageUtil = new Util_HouseReport_Page($pageConfig);
			$pageStr = $pageUtil->show();
			$this->data['data']['pageStr'] = $pageStr;
			$this->data['data']['totalNum'] = count($res['data']);
			$this->data['data']['type'] = "outLet";
			$currentPage = ($arrInput['page'] - 1) * $arrInput['pageSize'];
			$this->data['data']['data'] = array_slice($res['data'], $currentPage, $arrInput['pageSize']);
			foreach ($this->data['data']['data'] as $key=>$row){
				//经纪人的数量
				$whereConds = array('customer_id'=>$row['CustomerId']);
				$this->data['data']['data'][$key]['customer_id'] = $row['CustomerId'];
				unset($this->data['data']['data'][$key]['CustomerId']);
				$orgTotal = $this->objServiceCustomerAccount->getAccountCountByCustomerId($whereConds);
				$this->data['data']['data'][$key]['num'] = empty($orgTotal["data"]) ? 0 : $orgTotal["data"];
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}//}}}
}
