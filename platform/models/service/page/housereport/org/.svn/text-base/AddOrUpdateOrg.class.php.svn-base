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
class Service_Page_HouseReport_Org_AddOrUpdateOrg{
	protected $data;
	/**
	 * @var Service_Data_Gcrm_HouseManagerAccount
	 */
	protected $objServiceHouseManagerAccount;
	/**
	 * @var Service_Data_Gcrm_CompanyStoresAddress
	 */
	protected $objServiceCompanyStoresAddress;
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
		$arrChangeRow = array();
		try{
			$arrChangeRow['pid'] = $arrInput['pid'];
			$arrChangeRow['level'] = $arrInput['level'];
			$arrChangeRow['title'] = $arrInput['title'];
			$arrChangeRow['account'] = $arrInput['account'];
			if(!empty($arrInput['passwd'])){
				$arrChangeRow['password'] = md5($arrInput['passwd']);
				$arrChangeRow['passwd'] = $arrInput['passwd'];
			}
			$arrChangeRow['name'] = $arrInput['name'];
			$arrChangeRow['phone'] = $arrInput['phone'];
			$arrChangeRow['company_id'] = $arrInput['company_id'];
			$arrChangeRow['customer_id'] = $arrInput['customer_id'];
			if ($arrInput['level'] == 4) {
				$district_arr = explode(',', $arrInput['district_id']);
				if (is_array($district_arr)) {
					$district_id = $district_arr[0];
					$district_name = $district_arr[1];
				}
				$street_arr = explode(',', $arrInput['street_id']);
				if (is_array($street_arr)) {
					$street_id = $street_arr[0];
					$street_name = $street_arr[1];
				}else{
					$street_id = '-1';
					$street_name = '不限';
				}
				$data = array();
				if(!empty($district_id)&&!empty($arrInput['address'])){
					$data = array(
							'district_id'=>$district_id,
							'district_name'=>$district_name,
							'street_id'=>$street_id,
							'street_name'=>$street_name,
							'address'=>$arrInput['address'],
							'customer_name' => $arrInput['title'],
							'admin_id' => $arrInput['admin_id'],
							'modified_time'=>time()
					);
					$address_id = $arrInput['address_id'];
					$this->objServiceCompanyStoresAddress = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CompanyStoresAddress');
					if(empty($address_id)){
						$data['city_id'] = $arrInput['city_id'];
						$data['company_id'] =  $arrInput['company_id'];
						if(!empty($arrInput['customer_id'])){
							$data['customer_id'] = $arrInput['customer_id'];
						}
						$data['company_name'] = $arrInput['company_name'];
						$data['created_time'] =  time();
						//添加
						$this->objServiceCompanyStoresAddress->addCompanyStoresAddress($data);
					}else{
						//修改
						$this->objServiceCompanyStoresAddress->updateCompanyStoresAddressById($address_id, $data);
					}
				}
			}
			$res = 0;
			$this->objServiceHouseManagerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
			if ($arrInput['id']) {
				$res = $this->objServiceHouseManagerAccount->updateOrgById($arrInput['id'], $arrChangeRow);
			}else{
				$arrChangeRow['create_time'] = time();
				$res = $this->objServiceHouseManagerAccount->addOrg($arrChangeRow);
			}
			if ($res) {
				$this->data['data'] = $arrChangeRow;
			}else{
				$this->data['data'] = $res;
			}
		}catch (Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
}