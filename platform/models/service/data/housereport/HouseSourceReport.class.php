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
class Service_Data_HouseReport_HouseSourceReport{
    protected $data;
    protected $arrFields = array("ReportId","ReportDate","HouseBiddingMode","AccountId","AccountName","AccountEmail","AccountCityId","AccountCityName","CustomerId","CustomerName","CompanyId","CompanyName","EmployeeId","EmployeeName","BiddingOrder","AmountCount","RefreshCount","ClickCount","HouseSourceId","HouseSourceName","HouseSourcePublishTime","HouseSourceCategoryId","HouseSourceCategoryName","HouseSourceType","HouseSourceCityId","HouseSourceCityName","HouseSourceDistrictId","HouseSourceDistrictName","HouseSourceStreetId","HouseSourceStreetName","HouseSourceXiaoQuId","HouseSourceXiaoQuName","HouseBiddingType","HouseBiddingCityId","HouseBiddingCityName","HouseBiddingDistrictId","HouseBiddingDistrictName","HouseBiddingStreetId","HouseBiddingStreetName","HouseBiddingXiaoQuId","HouseBiddingXiaoQuName","HouseImageCount","feeType");
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    //{{{getReportByWhere
    /**获取指定昨天房源的点击量
     * @param $whereConds
     * @param array $arrFields
     * @return mixed
     */
    public function getReportByWhere($whereConds,$arrFields=array()){
        if (count($arrFields)) {
            $this->arrFields = $arrFields;
        }else{
            $this->arrFields = array(
                "SUM(ClickCount) AS account_pv",
            );
        }
        $arrConds = $this->getReportWhere($whereConds);
        if(count($arrConds) <=0 ){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        try{
            /**
             * @var Dao_Housereport_HouseSourceReport
             */
            $objDaoHouseSourceReport = Gj_LayerProxy::getProxy('Dao_Housereport_HouseSourceReport');
            $res = $objDaoHouseSourceReport->select($this->arrFields, $arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($objDaoHouseSourceReport->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }else{
            $this->data['data'] = $res;
        }
        return $this->data;
    }//}}}
    //{{{getReportWhere
    /**组装查询条件
     * @param $whereConds
     * @return array
     */
    protected function getReportWhere($whereConds){
        $arrConds = array();
        if(!empty($whereConds['house_id'])){
            if(is_array($whereConds['house_id'])){
                $house_ids = implode(',',$whereConds['house_id']);
                $arrConds[] = "HouseSourceId in ( $house_ids )";
            }else{
                $arrConds['HouseSourceId ='] = $whereConds['house_id'];
            }
        }
        if(!empty($whereConds['house_type'])){
            if(is_array($whereConds['house_type'])){
                $house_types = implode(',',$whereConds['house_type']);
                $arrConds[] = "HouseSourceCategoryId in ( $house_types )";
            }else{
                $arrConds['HouseSourceCategoryId ='] = $whereConds['house_type'];
            }
        }
        if(!empty($whereConds['account_id'])){
            $arrConds['AccountId ='] = $whereConds['account_id'];
        }
        if(!empty($whereConds['HouseBiddingMode'])){
        	$arrConds['HouseBiddingMode ='] = $whereConds['HouseBiddingMode'];
        }
        if(!empty($whereConds['ReportDate'])){
            $arrConds['ReportDate ='] = $whereConds['ReportDate'];
        }
		if(!empty($whereConds['HouseSourceCityId']) || 0 === $whereConds['HouseSourceCityId']){
			$arrConds['HouseSourceCityId= '] = $whereConds['HouseSourceCityId'];
		}
		if(!empty($whereConds['HouseSourceDistrictId'])){
			$arrConds['HouseSourceDistrictId = '] = $whereConds['HouseSourceDistrictId'];
		}
		if(!empty($whereConds['CompanyId'])){
			$arrConds['CompanyId = '] = $whereConds['CompanyId'];
		}
		if(!empty($whereConds['HouseSourceStreetId'])){
			 $arrConds['HouseSourceStreetId = '] = $whereConds['HouseSourceStreetId'];
		}
        return $arrConds;
    }//}}}


	public function getHouseCountGroupBy($whereConds){
		$fields = "SUM(ClickCount) AS total_click,COUNT(1) AS houseCount,HouseSourceDistrictName,HouseSourceStreetName,CompanyId ,HouseSourceCityId,HouseSourceDistrictId";
		$arrConds = $this->getReportWhere($whereConds);
		$objDaoHouseSourceReport = Gj_LayerProxy::getProxy('Dao_Housereport_HouseSourceReport');
		try{
			$this->data['data'] = $objDaoHouseSourceReport->selectGroupbyConds($fields,$arrConds);    
		}catch(Exception $e){
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}   
		return $this->data;
	} 
	public function getHouseCountStreetGroupBy($whereConds){
		$fields = "SUM(ClickCount) AS total_click,COUNT(1) AS houseCount,HouseSourceDistrictName,HouseSourceStreetName,CompanyId ,HouseSourceCityId,HouseSourceDistrictId,HouseSourceStreetId";
		$arrConds = $this->getReportWhere($whereConds);
		$objDaoHouseSourceReport = Gj_LayerProxy::getProxy('Dao_Housereport_HouseSourceReport');
		try{
			$this->data['data'] = $objDaoHouseSourceReport->selectGroupbyStreetConds($fields,$arrConds);    
		}catch(Exception $e){
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}   
		return $this->data;
	} 
	public function getHouseCountXiaoqu($whereConds){
		$fields = "SUM(ClickCount) AS total_click,COUNT(1) AS houseCount,HouseSourceDistrictName,HouseSourceStreetName,CompanyId ,HouseSourceCityId,HouseSourceDistrictId,HouseSourceStreetId,HouseSourceXiaoQuId,HouseSourceXiaoQuName";
		$arrConds = $this->getReportWhere($whereConds);
		$objDaoHouseSourceReport = Gj_LayerProxy::getProxy('Dao_Housereport_HouseSourceReport');
		try{
			$this->data['data'] = $objDaoHouseSourceReport->selectGroupbyXiaoquConds($fields,$arrConds);    
		}catch(Exception $e){
			$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
			$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
		}   
		return $this->data;
	} 
}
