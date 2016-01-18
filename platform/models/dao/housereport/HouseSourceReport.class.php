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
 * @codeCoverageIgnore
 */
class Dao_Housereport_HouseSourceReport extends Gj_Base_MysqlDao{
    protected $util = null;
    protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    //protected $table_fields = array("ReportId","ReportDate","HouseBiddingMode","AccountId","AccountName","AccountEmail","AccountCityId","AccountCityName","CustomerId","CustomerName","CompanyId","CompanyName","EmployeeId","EmployeeName","BiddingOrder","AmountCount","RefreshCount","ClickCount","HouseSourceId","HouseSourceName","HouseSourcePublishTime","HouseSourceCategoryId","HouseSourceCategoryName","HouseSourceType","HouseSourceCityId","HouseSourceCityName","HouseSourceDistrictId","HouseSourceDistrictName","HouseSourceStreetId","HouseSourceStreetName","HouseSourceXiaoQuId","HouseSourceXiaoQuName","HouseBiddingType","HouseBiddingCityId","HouseBiddingCityName","HouseBiddingDistrictId","HouseBiddingDistrictName","HouseBiddingStreetId","HouseBiddingStreetName","HouseBiddingXiaoQuId","HouseBiddingXiaoQuName","HouseImageCount","feeType");
    public function __construct(){
        $this->util = new Util_HouseReportUtil();
        $this->setTableName();
        parent::__construct();
    }
    public function setTableName($date=NULL){
        $tableDate = $this->util->getTableName($date);
        $this->tableName = 'house_source_report'.$tableDate;
    }
	public function selectGroupbyConds($arrFields,$arrConds){
		$strGroupby = "group by HouseSourceDistrictId  order by total_click DESC limit 10";
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	} 
	public function selectGroupbyStreetConds($arrFields,$arrConds){
		$strGroupby = "group by HouseSourceStreetId order by total_click DESC ";
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	} 
	public function selectGroupbyXiaoquConds($arrFields,$arrConds){
		$strGroupby = "group by HouseSourceXiaoQuId order by total_click DESC";
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	}	
}
