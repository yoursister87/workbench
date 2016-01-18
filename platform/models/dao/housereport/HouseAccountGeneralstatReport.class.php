<?php
/*
 * File Name:houseAccountGeneralstatReport.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Dao_Housereport_HouseAccountGeneralstatReport extends Gj_Base_MysqlDao
{
    protected $util = null;
    protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    protected $table_fields = array("ReportId","ReportDate","HouseBiddingMode","AccountSourceType","AccountId","AccountName","AccountEmail","AccountCityId","AccountCityName","AccountCreatedTime","AccountRenewalsTime","AccountStatus","AccountFreezedTime","HouseAccountBussinessScopeId","HouseAccountBussinessScopeName","HouseAccountServicePlanId","CustomerId","CustomerName","CompanyId","CompanyName","EmployeeId","EmployeeName","HouseCount","BiddingCount","PremierCount","MaxPremierCount","AmountCount","RefreshCount","MaxRefreshCount","ClickCount","HouseTotalCount","TuiguangCount","LoginCount","LastLoginTime","PremierExpireTime","CellPhone","MultImageHouseCount","ValidClick","ConsumeHouseCount","ClickPrice","ConsumePrice","UnvalidClick","RefundClick","RefundConsume","DisplayCount","CpcLocationCount","CpcConsumeLocationCount","HouseType","RecentTag","PremierTag","CountType","RecentTagConsume","PremierTagConsume","PostMultImageHouseCount","ComplainHouseCount","SimilarHouseCount","OfflineHouseCount","CreditScore");   
    public function __construct(){
        $this->util = new Util_HouseReportUtil();
        parent::__construct();
    }
    //因为是按照houseType 0 搜索 需要SUM
    public function getAccountPremierReportDetail($arrFields,$date,$pageArr = null,$arrConds = NULL, $orderField = null ,$order = "DESC"){
        if (empty($arrFields)) {
        $arrFields = array(
            "AccountId AS account_id",
            "SUM(HouseTotalCount) AS house_total_count",
            "SUM(HouseCount) AS house_count",
            "SUM(PremierCount) AS premier_count",
            "SUM(BiddingCount) AS bid_count",
            "SUM(MaxPremierCount) AS tuiguang_max_count",
            "SUM(RefreshCount) refresh_count",
            "SUM(MaxRefreshCount) refresh_max_count",
            "SUM(LoginCount) AS login_count",
            "SUM(ClickCount) AS account_pv",
        	"SUM(AmountCount) AS amount_count",
            "SUM(PremierTag) AS premier_tag",
            "SUM(RecentTag) AS recent_tag",
            "SUM(MultImageHouseCount) AS mult_img_house_count",  #优质房源
            "SUM(ComplainHouseCount) AS complain_house_count",
            "SUM(TuiguangCount) AS tuiguang_count",
            "SUM(SimilarHouseCount) as similar_house_count",#重复房源数
            "SUM(OfflineHouseCount) as illegal_house_count",#违规房源数
            "0 as comment_count",#差评数房源数  
            "ReportDate AS report_date"
            );
        }
        $appends = "GROUP BY ReportDate";
        $tableDate = $this->util->getTableName($date);
        $this->tableName = 'house_account_generalstat_report'.$tableDate;

        $count = $this->selectByCount($arrConds,$appends);

        $ret = $this->selectByPage($arrFields, $arrConds,$pageArr['currentPage'],$pageArr['pageSize'], array($orderField=>$order),$appends);

        $result['list'] = $ret;
        $result['count'] = $count;

        if($ret === false){
              throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;

    }

    public function getAccountPremierReportList($arrConds, $date,$pageArr = null){
        //字段定义查看
        //http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
        $arrFields = array(
            "AccountId AS account_id",
            "SUM(HouseCount) AS house_count",
            "SUM(PremierCount) AS premier_count",
            "SUM(BiddingCount) AS bid_count",
            "SUM(RefreshCount) AS refresh_count",
            "SUM(LoginCount) AS login_count",
            "SUM(ClickCount) AS account_pv",
        	"SUM(AmountCount) AS amount_count",
            "SUM(PremierTag) AS premier_tag",
            "SUM(RecentTag) AS recent_tag",
            "MaxPremierCount AS tuiguang_max_count",
            "MaxRefreshCount AS refresh_max_count",
            "sum(MultImageHouseCount) AS mult_img_house_count",
            "SUM(ComplainHouseCount) AS complain_house_count",
            "SUM(TuiguangCount) AS tuiguang_count",
            "SUM(SimilarHouseCount) as similar_house_count",#重复房源数
            "SUM(OfflineHouseCount) as illegal_house_count",#违规房源数
            "0 as comment_count",#差评数房源数  
        );
        $tableDate = $this->util->getTableName($date);
        $this->tableName = 'house_account_generalstat_report'.$tableDate;

        //得到总数
        $appends = " GROUP BY AccountId ORDER BY NULL";
        $count = $this->selectByCount($arrConds,$appends);

        if (isset($pageArr)) {
            $ret = $this->selectByPage($arrFields, $arrConds,$pageArr['currentPage'],$pageArr['pageSize'],array(),$appends);
        } else {
            $ret = $this->selectByPage($arrFields, $arrConds,null,null,array(),$appends);
        }
        if($ret === false){
             throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
        }
        $result['list'] = $ret;
        $result['count'] = $count;
        return $result;
    }
    
    public function getAccountPremierReportListBySum($arrConds, $date,$pageArr = null){
    	//字段定义查看
    	//http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
    	$arrFields = array(
    			"AccountId AS account_id",
    			"HouseCount AS house_count",
    			"PremierCount AS premier_count",
    			"BiddingCount AS bid_count",
    			"RefreshCount AS refresh_count",
    			"LoginCount AS login_count",
    			"ClickCount AS account_pv",
    			"AmountCount AS amount_count",
    			"PremierTag AS premier_tag",
    			"RecentTag AS recent_tag",
    			"MaxPremierCount AS tuiguang_max_count",
    			"MaxRefreshCount AS refresh_max_count",
    			"MultImageHouseCount AS mult_img_house_count",
    			"ComplainHouseCount AS complain_house_count",
    			"TuiguangCount AS tuiguang_count",
    			"SimilarHouseCount AS similar_house_count",#重复房源数
    			"OfflineHouseCount AS illegal_house_count",#违规房源数
    			'HouseTotalCount AS house_total_count',
    			'LastLoginTime AS last_login_time',
    			"ReportDate AS report_date",
    			"HouseType AS house_type",
    			"CountType AS count_type",
    			"0 as comment_count",#差评数房源数
    	);
    	$tableDate = $this->util->getTableName($date);
    	$this->tableName = 'house_account_generalstat_report'.$tableDate;
    	$ret = $this->selectByPage($arrFields, $arrConds,null,null,array());
    	if($ret === false){
    		throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
    	}
    	return $ret;
    }
    public function selectOverdueAccountByPage($arrFields,$arrConds,$currentPage,$pageSize, $orderArr,$appends, $date){
    	$tableDate = $this->util->getTableName($date);
    	$this->tableName = 'house_account_generalstat_report'.$tableDate;
    	return $this->selectByPage($arrFields,$arrConds,$currentPage,$pageSize, $orderArr, $appends);
    }

    public function getClickAndAmountByReportDate($arrConds,$month){
        $arrFields = array(
            'AccountId as account_id',
            "SUM(PremierCount) AS house_count",
            'SUM(clickPrice) AS click_price',
            'SUM(ClickCount) AS click_count',
            "ReportDate AS report_date",
            "CountType AS count_type",
        );

        if (empty($arrConds['days'])) {
            $reportDate[] = strtotime('yesterday');
        } else {
            $reportDate = $arrConds['days'];
        }

        //得到下月的日期开始
        $m = date('m',strtotime($month));
        $y = date('Y',strtotime($month));
        if ($m < 12) {
            $m++;
            $monthTime = strtotime("{$y}-{$m}-1");
        } else {
            $y++;
            $monthTime = strtotime("{$y}-1-1");
        }
        $thisMonthTime = strtotime($month);
        foreach ($reportDate as $key=>$item) {
            if($monthTime > $item && $thisMonthTime <= $item) {
                continue;
            }
            unset($reportDate[$key]);
        }

        $reportDateStr = implode(',',$reportDate);

        $houseTypeStr = implode(',',$arrConds['houseType']);
        $countTypeStr = implode(',',$arrConds['countType']);

        $arrConds = array(
            "AccountId ="  =>$arrConds['accountId'],
            "ReportDate  IN ({$reportDateStr})",
            "HouseType in( $houseTypeStr )",
            "CountType in($countTypeStr)"
        );
        
        $appends = "GROUP BY ReportDate,CountType ORDER BY NULL";

        $tableDate = $this->util->getTableName($month);
        $this->tableName = 'house_account_generalstat_report'.$tableDate;

        $ret = $this->selectByPage($arrFields,$arrConds,1,null, array(), $appends);

        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,$this->getLastSQL());
        }

        return $ret;
    }
}
