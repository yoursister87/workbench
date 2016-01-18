<?php
/*
 * File Name:HouseAccountReportV2.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Dao_Housereport_HouseAccountReportV2 extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    protected $tableName = 'house_account_report_v2';


    protected $table_fields = array("report_id","report_date","report_type","source_type","account_id","email","cell_phone","city_id","created_time","renewals_time","account_status","freezed_time","premier_expire_time","last_login_time","biz_scope_id","service_plan_id","customer_id","company_id","employee_id","login_count","house_count","house_total_count","promote_count","bidding_count","premier_count","premier_max_count","refresh_count","comment_count","refresh_max_count","display_count","click_count","valid_click_count","click_price","refund_click_count","refund_consume","mult_img_house_count","mult_img_house_total_count","complain_house_count","similar_house_count","illegal_house_count","consume_house_count","cpc_location_count","cpc_consume_location_count","cpc_amount_count","recent_tag","premier_tag","recent_tag_consume","premier_tag_consume","credit_score","consume_price","house_type");

    //因为是按照houseType 0 搜索 需要SUM
     public function getAccountAssureReportDetail($arrFields,$pageArr = null,$arrConds = NULL, $orderField = null ,$order = "DESC"){
        if (empty($arrFields)) {
        $arrFields = array(
            "account_id AS account_id ",
            "SUM(house_total_count) AS house_total_count",
            "SUM(house_count) AS house_count",
            "SUM(premier_count) AS premier_count",
            "SUM(premier_max_count) AS tuiguang_max_count",
            "SUM(bidding_count) AS bid_count",
            "SUM(refresh_count) AS refresh_count",
            "SUM(refresh_max_count) AS refresh_max_count",
            "SUM(login_count) AS login_count",
            "SUM(click_count) AS account_pv",
            "SUM(premier_tag) AS premier_tag",
            "SUM(recent_tag) AS recent_tag",
            "SUM(mult_img_house_count) AS mult_img_house_count", #优质房源
            "SUM(complain_house_count) AS complain_house_count",
            "SUM(illegal_house_count) AS illegal_house_count", #违规房源数
            "SUM(similar_house_count) AS similar_house_count",#重复图片房源数
            "SUM(mult_img_house_total_count) as mult_img_house_total_count",
            "SUM(promote_count) AS tuiguang_count",
            "SUM(comment_count) AS comment_count",  #差评数
            "report_date AS report_date",         
            );
        }
        $appends = "GROUP BY report_date";
        $count = $this->selectByCount($arrConds,$appends);
         if (isset($pageArr)) {
             $ret = $this->selectByPage($arrFields, $arrConds,$pageArr['currentPage'],$pageArr['pageSize'],array($orderField=>$order),$appends);
         } else {
             $ret = $this->selectByPage($arrFields, $arrConds,1,null,array($orderField=>$order),$appends);
         }
        $result['list'] = $ret;
        $result['count'] = $count;

        if($ret === false){
              throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;

    }
    public function getAccountAssureReportList($arrConds,$pageArr = null){
        //字段定义查看
        //http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
        $arrFields = array(
            "account_id AS account_id ",
            "SUM(house_count) AS house_count",
            "SUM(premier_count) AS premier_count",
            "SUM(bidding_count) AS bid_count",
            "SUM(refresh_count) AS refresh_count",
            "SUM(login_count) AS login_count",
            "SUM(click_count) AS account_pv",
            "SUM(premier_tag) AS premier_tag",
            "SUM(recent_tag) AS recent_tag",
            "premier_max_count AS tuiguang_max_count",
            "refresh_max_count AS refresh_max_count",
            "SUM(mult_img_house_count) as mult_img_house_count",#优质房源
            "SUM(mult_img_house_total_count) as mult_img_house_total_count",
            "SUM(complain_house_count) AS complain_house_count",
            "SUM(illegal_house_count) AS illegal_house_count", #违规房源数
            "SUM(promote_count) AS tuiguang_count",
            "SUM(similar_house_count) as similar_house_count",#重复图片房源数
            "SUM(comment_count) AS comment_count",  #差评数
        );
        $appends = " GROUP BY account_id ORDER BY NULL";

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
    
    public function getAccountAssureReportListBySum($arrConds,$pageArr = null){
    	//字段定义查看
    	//http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
    	$arrFields = array(
    			"account_id AS account_id ",
        		"max(house_total_count) as house_total_count",
    			"sum(house_count) AS house_count",
    			"sum(premier_count) AS premier_count",
    			"sum(bidding_count) AS bid_count",
    			"sum(refresh_count) AS refresh_count",
    			"sum(login_count) AS login_count",
    			"sum(click_count) AS account_pv",
    			"sum(premier_tag) AS premier_tag",
    			"sum(recent_tag) AS recent_tag",
    			"premier_max_count AS tuiguang_max_count",
    			"refresh_max_count AS refresh_max_count",
    			"sum(mult_img_house_count) as mult_img_house_count",#优质房源
    			"sum(mult_img_house_total_count) as mult_img_house_total_count",
    			"sum(complain_house_count) AS complain_house_count",
    			"sum(illegal_house_count) AS illegal_house_count", #违规房源数
    			"sum(promote_count) AS tuiguang_count",
    			"sum(similar_house_count) as similar_house_count",#重复图片房源数
    			"sum(comment_count) AS comment_count",  #差评数
    			"report_date",
    	);
    	$appends = " GROUP BY account_id ORDER BY NULL";
    	$ret = $this->selectByPage($arrFields, $arrConds,null,null,array(),$appends);
    	if($ret === false){
    		throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
    	}
        //var_dump($ret);exit;
    	$result['list'] = $ret;
    	$result['count'] = count($result['list']);
    	return $result;
    }

    public function getClickAndAmountByReportDate($arrConds){
        $arrFields = array(
            'account_id as account_id',
            "SUM(promote_count) AS house_count",
            'SUM(click_price) AS click_price',
            'SUM(click_count) AS click_count',
            "report_date AS report_date",
        );

        if (empty($arrConds['days'])) {
            $reportDate[] = strtotime('yesterday');
        } else {
            $reportDate = $arrConds['days'];
        }
        $reportDateStr = implode(',',$reportDate);
        $houseTypeStr = implode(',',$arrConds['houseType']);
        $countTypeStr = implode(',',$arrConds['countType']);

        $arrConds = array(
            "account_id ="  =>$arrConds['accountId'],
            "report_date IN ({$reportDateStr})",
            "house_type in( $houseTypeStr )",
        );

        $appends = "GROUP BY report_date ORDER BY NULL";

        $ret = $this->selectByPage($arrFields,$arrConds,1,null, array(), $appends);

        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,$this->getLastSQL());
        }

        if (!empty($ret) && is_array($ret)) {
            foreach ($ret as &$item) {
                //添加count_type字段等于4
                $item['count_type'] = 4;
            }
        }

        return $ret;
    }

}
