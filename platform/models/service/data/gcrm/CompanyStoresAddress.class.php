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
class Service_Data_Gcrm_CompanyStoresAddress {
	/**
	 *
	 * @var Dao_Gcrm_CompanyStoresAddress
	 */
	protected $objDaoCompanyStoresAddress;
	protected $arrFields = array("id","city_id","district_id","street_id","district_name","street_name","address","company_id","company_name","customer_id","customer_name","add_type","admin_id","creator_id","creator_user_id","created_time");
	public function __construct() {
		$this->data ['data'] = array ();
		$this->data ['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data ['errormsg'] = ErrorConst::SUCCESS_MSG;
		$this->objDaoCompanyStoresAddress = Gj_LayerProxy::getProxy ( 'Dao_Gcrm_CompanyStoresAddress' );
	}
	// {{{addCompanyStoresAddress
	/**
	 * 新增门店地址，成功返回插入id
	 * 
	 * @param unknown $arrRows        	
	 * @return Ambigous <multitype:, string, boolean, 结果数组, unknown, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function addCompanyStoresAddress($arrRows) {
		if (! is_array ( $arrRows )) {
			$this->data ['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data ['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
		} else {
			try {
				$res = $this->objDaoCompanyStoresAddress->insert ( $arrRows );
			} catch ( Exception $e ) {
				$this->data ['errorno'] = $e->getCode ();
				$this->data ['errormsg'] = $e->getMessage ();
			}
			if (! $res) {
				Gj_Log::warning ($this->objDaoCompanyStoresAddress->getLastSQL());
				$this->data ['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
				$this->data ['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
			} else {
				$this->data ['data'] = $res;
			}
		}
		return $this->data;
	} // }}}
	// {{{ updateCompanyStoresAddressById
	/**
	 * 根据id，修改门店地址
	 * @param unknown $id        	
	 * @param unknown $arrChangeRow        	
	 * @return Ambigous <multitype:, string, number, boolean, $ret, unknown, 结果数组, 结果数组：成功；false：失败, multitype:unknown >
	 */
	public function updateCompanyStoresAddressById($id, $arrChangeRow) {
		$arrConds = array (
				'id =' => $id 
		);
		try {
			$res = $this->objDaoCompanyStoresAddress->update ( $arrChangeRow, $arrConds );
		} catch ( Exception $e ) {
			$this->data ['errorno'] = $e->getCode ();
			$this->data ['errormsg'] = $e->getMessage ();
		}
		$this->data ['data'] = $res;
		return $this->data;
	} // }}}
	//{{{getStoreInfoByUserId
	/**
	 * 根据条件获取门店地址
	 * @param string $id						门店地址id
	 * @param string $customer_id			门店id
	 * @param string $admin_id				经纪人管理后管理员id
	 * @param string $creator_id			经纪人id
	 * @param unknown $arrFileds
	 * @return Ambigous <multitype:, string, number, boolean, unknown>
	 */
	public function getStoreInfoByUserId($id=null, $customer_id=null, $admin_id=null, $creator_id=null, $arrFileds=array()) {
		if (count($arrFileds)) {
    		$this->arrFields = $arrFileds;
    	}
    	if (!empty($id)) {
    		$arrConds['id =']=$id;
    	}
		if (!empty($customer_id)) {
			$arrConds['customer_id =']=$customer_id;
		}
		if(!empty($admin_id)){
			$arrConds['admin_id =']=$admin_id;
		}
		if(!empty($creator_id)){
			$arrConds['creator_id =']=$creator_id;
		}
		try {
    		$res = $this->objDaoCompanyStoresAddress->select($this->arrFields, $arrConds);
    	} catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
    	if ($res === false) {
    		Gj_Log::warning($this->objDaoCompanyStoresAddress->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
	    	$this->data['data'] = $res[0];
        }
    	return $this->data;
	}//}}}
}
