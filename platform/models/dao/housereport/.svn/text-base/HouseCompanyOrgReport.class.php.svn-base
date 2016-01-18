<?php
/*
 * File Name:HouseCompanyOrgReportHousereportModel.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Dao_Housereport_HouseCompanyOrgReport extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    protected $tableName = 'house_company_org_report';
    //protected $table_fields = array("id","report_date","org_id","org_type","customer_count","account_count","house_count","tuiguang_count","house_total_count","bid_count","premier_count","max_premier_count","refresh_count","max_refresh_count","login_count","account_pv","mult_img_house_count","valid_click","consume_house_count","click_price","consume_price","unvalid_click","refund_click","refund_consume","display_count","cpc_location_count","cpc_consume_location_count","account_status","account_freezed_time","house_type","recent_tag","premier_tag","bid_account","premier_account","count_type","bid_house","premier_house","complain_house_count","similar_house_count","offline_house_count","credit_score");
    protected $table_fields = array();
    public function getSumList($arrConds,$pageArr = null){
        $arrFields = array(
            'org_id as org_id',
            'SUM(house_count) as house_count',
            'SUM(bid_count) as bid_house',  #竞价房源
        	'SUM(click_price) AS click_price',  #消费
            'SUM(refresh_count) as refresh_count',          #最大刷新
            'SUM(login_count) as login_count',            #登入次数
            'SUM(account_pv) as account_pv',
            'SUM(premier_count) AS premier_count',          #推广数
            'SUM(tuiguang_count) as tuiguang_count',
            '0 AS similar_house_count',  #重复图片房数数
            '0 AS comment_count',#房源差评数 表中是没有这个字段的 
            'SUM(offline_house_count) as illegal_house_count' #违规房源数量
        );
        $appends = " GROUP BY org_id";
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

    public function getClickAndAmountByReportDate($arrConds){
        $arrFields = array(
            'org_id as org_id',
            "SUM(premier_count) AS house_count",
            "SUM(click_price) AS click_price",
            'SUM(account_pv) AS click_count',
            "report_date AS report_date",
            "count_type AS count_type",
        );


        $houseTypeStr = implode(',',$arrConds['houseType']);
        $countTypeStr = implode(',',$arrConds['countType']);

        if (empty($arrConds['days'])) {
            $reportDate[] = strtotime('yesterday');
        } else {
            $reportDate = $arrConds['days'];
        }
        $reportDateStr = implode(',',$reportDate);
        $arrConds = array(
            "org_id IN ({$arrConds['orgId']})",
            "report_date  IN ({$reportDateStr})",
            "house_type in( $houseTypeStr )",
            "count_type in( $countTypeStr )"
        );

        $appends = "GROUP BY report_date,count_type ORDER BY NULL";

        $ret = $this->selectByPage($arrFields,$arrConds,1,null, array(), $appends);

        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,$this->getLastSQL());
        }

        return $ret;
    }

}
