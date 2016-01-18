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
class Service_Page_HouseReport_Org_StoreToArea{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	public function __construct(){
		$this->data['data'] = 1;
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
		if (! is_numeric($arrInput['pid'] || ! is_numeric($arrInput['id']))) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
		}
		try {
			$arrChangeRow = array('pid' => $arrInput['pid']);
			/**
			 * 房产FANG-10009组织架构管理-增加批量转移门店功能
			 * @author 刘海鹏 <liuhaipeng1@ganji.com>
			 * @create 2015-08-11
			 */
			$ids = explode(',', $arrInput['id']);
			foreach ($ids as $id) {
				$whereConds['id'] = $id;
				$orgInfo = $this->objServiceHouseManagerAccount->getOrgInfoByIdOrAccount($whereConds);
				$res = $this->objServiceHouseManagerAccount->updateOrgById($id, $arrChangeRow);
				$msgLog = "HouseReport:门店id:{$id}, 转移之前:" . json_encode($orgInfo) . ", 新板块id:{$arrInput['pid']}, 操作人:{$arrInput['admin_id']}, 转移结果:" . json_encode($res);
				Gj_Log::trace($msgLog);
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}