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
class Service_Data_Gcrm_Company{
	protected $data;
	/**
	 * @var Dao_Gcrm_Company
	 */
	protected $objDaoCompany;
	protected $arrFields = array(
            'CompanyId',
            'FullName',
            'ShortcutName',
            'CityId',
            'CityName'
		);
		public function __construct(){
			$this->data['data'] = array();
			$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
			$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
			$this->objDaoCompany = Gj_LayerProxy::getProxy('Dao_Gcrm_Company');
		}
		public function __call($name,$args){
			if (Gj_LayerProxy::$is_ut === true) {
				return  call_user_func_array(array($this,$name),$args);
			}
		}
		public function getBizCompanyInfoServiceInfo(){
				return false;
		} 
		public function getCompanyInfoById($company_id, $arrFields =  array()){
			if (!empty($arrFields)){
				$this->arrFields = $arrFields;
			}
			// 验证$company_id是否为空
			if (! isset($company_id) || ! is_numeric($company_id)) {
				$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
				$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
				return $this->data;
			}
            $arrFields = $this->fieldNameRevert($this->arrFields);
			$arrConds = array(
				'id =' => $company_id,
			);
			try {
				 $ret = $this->objDaoCompany->select($arrFields,$arrConds);
				
			} catch (Exception $e){
				 $this->data['errorno'] = $e->getCode();
				 $this->data['errormsg'] = $e->getMessage();
			}
			if ( false == $ret){
				 	Gj_Log::warning($this->objDaoCompany->getLastSQL());
				    $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
				    $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
			}else{
				$this->data['data'] = $ret[0]; 
			}
			return $this->data;
		}
		//{{{ getCompanyListByWhere
		/**
		 * 获取某一个城市下开通房产的公司信息
		 * @param unknown $whereConds	city_id城市id北京12
		 * @param unknown $arrFields		需要获取的字段
		 * @param number $page				页数
		 * @param number $pageSize			每页条数，page 为1 $pageSize 为 null时不限制
		 * @param unknown $orderArr		排序 array('CreatedTime'=>'DESC')
		 * @return Ambigous 						查询结果
		 */
		public function getCompanyListByWhere($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
			if (count($arrFields)) {
				$this->arrFields = $arrFields;
			}
            $arrFields = $this->fieldNameRevert($this->arrFields);
			$arrConds = $this->getWhere($whereConds);
			try{
				$res = $this->objDaoCompany->selectByPage($arrFields, $arrConds, $page, $pageSize, $orderArr);
			} catch(Exception $e) {
				$this->data['errorno'] = $e->getCode();
				$this->data['errormsg'] = $e->getMessage();
			}
			if ($res === false) {
				Gj_Log::warning($this->objDaoCompany->getLastSQL());
				$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
				$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
			}else{
				$this->data['data'] = $res;
			}
			return $this->data;
		}//}}}
		//{{{getWhere
		/**
		 * 组装查询条件
		 * @param unknown $whereConds
		 * @return multitype:number unknown
		 */
		protected function getWhere($whereConds){
            $arrConds = array();
			if(!empty($whereConds['CityId'])){
				$arrConds['CityId ='] = $whereConds['CityId'];
			}
			if(!empty($whereConds['CompanyId'])){
				if(is_array($whereConds['CompanyId'])){
					$companyIds = implode(',',$whereConds['CompanyId']);	
					 $arrConds[] = "CompanyId in ( $companyIds )";
				}else{
					$arrConds['CompanyId ='] = $whereConds['CompanyId'];	
				}	
			}
			return $arrConds;
		}//}}}
    /* {{{ fieldNameRevert*/
    /**
     * @brief 当前dataservice 原来是为了管理company表，后来crm表迁移工作字段名有修改，该函数用于保证出参格式
     *
     * @param $arrFields array 要查询的字段
     *
     * @returns  array 
     */
    protected function fieldNameRevert($arrFields){
        $ret = array();
        $revertRelation = array(
            'FullName' => 'full_name as FullName',
            'ShortcutName' => 'full_name as ShortcutName',
            'CompanyId' => 'id as CompanyId',  
            'CityId' => 'city_id as CityId',  
            'CityName' => 'city_name as CityName',  
        );
        if (!is_array($arrFields) || !count(array_intersect($arrFields, array_keys($revertRelation)))) {
            $ret = $arrFields;
        } else {
            foreach ($arrFields as $key) {
                if (isset($revertRelation[$key])) {
                    $ret[] = $revertRelation[$key];
                }
            }
        }
        return $ret;
    }
    /* }}} */
    public function getRealHouseCompanyByCityId($city_id){
        try {
            $arrChangeRow = array(
                'CityId'=>$city_id,
            );
            $tgApiUrl = InterfaceConfig::CRM_INTERFACE_URL . 'interface=IAccountService&method=GetZhenFangYuanCompanies';
            $objCurl = new Gj_Util_Curl();
            $postData = "data=".json_encode($arrChangeRow);
            $objCurl = new Gj_Util_Curl();
            $res = $objCurl->post($tgApiUrl, $postData, true);
            $resArr = json_decode($res,true);
            Gj_Log::trace("crm:GetZhenFangYuanCompanies=".$res);
            if($resArr['succeed']){
                $this->data['data'] = $resArr['data']['result'];
            }else{
                $this->data['errorno'] = 2126;
                $this->data['errormsg'] = $resArr['error'];
                Gj_Log::trace("crm:GetZhenFangYuanCompanies=".json_encode($this->data));
            }
        } catch (Exception $e) {
            $this->data['errorno'] = ErrorConst::E_DB_FAILED_CODE;
            $this->data['errormsg'] = ErrorConst::E_DB_FAILED_MSG;
            Gj_Log::trace("crm:GetZhenFangYuanCompanies=".json_encode($this->data));
        }
        return $this->data;
    }
}
