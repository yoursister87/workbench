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
class Service_Data_Gcrm_AccountChange{
	protected $data;
	/**
	 * @var Dao_Gcrm_AccountChange
	 */
	protected $objDaoAccountChange;
	protected $arrFields = array("account_id","user_id","action","value","create_at","author");
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	//{{{addAccountChange
	/**
	 * 处理转端口成功后，插入队列修改帖子的account_id
	 * @param unknown $arrRows
	 * @return Ambigous <multitype:, string, boolean, unknown>
	 */
	public function addAccountChange($arrRows){
		if (!is_array($arrRows)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		try{
			$this->objDaoAccountChange = Gj_LayerProxy::getProxy('Dao_Tgqe_AccountChangeQueue');
			$res = $this->objDaoAccountChange->insert($arrRows);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		$this->data['data'] = $res;
		return $this->data;
	}//}}}
}