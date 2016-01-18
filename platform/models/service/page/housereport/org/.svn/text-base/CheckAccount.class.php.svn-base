<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangyulong$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Page_HouseReport_Org_CheckAccount{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
		$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
	}
	//{{{execute
	/**
	* 获取树状结构
	* @param array $arrInput
	* @return Ambigous <unknown, multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >|Ambigous <multitype:, string>
	*/
	public function execute($arrInput){
		$arrFields =array("id","company_id","status");
		try{
			if (empty($arrInput['account'])) {
				$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
				$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			}else{
				$whereConds['account'] = $arrInput['account'];
				$res = $this->objServiceHouseManagerAccount->getOrgInfoByIdOrAccount($whereConds, $arrFields);
				if (isset($res['data']['id']) && $res['data']['id'] != $arrInput['id']) {
					$this->data['data'] = true;
				}else{
					$this->data['data'] = false;
				}
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}