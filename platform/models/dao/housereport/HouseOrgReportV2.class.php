<?php
/*
 * File Name:HouseOrgReportV2.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Dao_Housereport_HouseOrgReportV2 extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_report';
    protected $dbNameAlias = 'house_report';
    protected $tableName = 'house_org_report_v2';

    protected $table_fields = array("report_date","report_type","org_id","org_type","org_level","org_pid","location_id","account_status","account_freezed_time","customer_count","account_count","login_count","house_total_count","house_count","promote_count","bidding_count","premier_count","premier_max_count","refresh_count","comment_count","refresh_max_count","display_count","click_count","click_user_count","valid_click","click_price","refund_click_count","refund_consume","mult_img_house_count","complain_house_count","similar_house_count","illegal_house_count","consume_house_count","cpc_location_count","cpc_consume_location_count","recent_tag","premier_tag","credit_score","consume_price","bid_account_count","premier_account_count","bid_house_count","premier_house_count","house_type");


    public function getSumList($arrConds,$pageArr = null){
        $arrFields = array(
            "org_id AS org_id",
            "SUM(house_count) AS house_count",
            "SUM(refresh_count) AS refresh_count",
            "SUM(login_count) AS login_count",
            "SUM(click_count) AS account_pv ",
            "SUM(complain_house_count) AS complain_house_count",
            "SUM(illegal_house_count) AS offline_house_count",
            "SUM(promote_count) AS premier_count",
            "SUM(comment_count) AS comment_count",
            '0 AS similar_house_count', #重复图片房数数
            'SUM(illegal_house_count) as illegal_house_count', #违规房源数量
            'SUM(comment_count) as comment_count',  #差评论数
        );
        
        $appends = " GROUP BY org_id";

        $count = $this->selectByCount($arrConds,$appends);
        if ($pageArr === null) {
            $ret = $this->selectByPage($arrFields, $arrConds,null,null,array(),$appends);
        } else {
            $ret = $this->selectByPage($arrFields, $arrConds,$pageArr['currentPage'],$pageArr['pageSize'],array(),$appends);
        }

        $result['list'] = $ret;
        $result['count'] = $count;

        if($ret === false){
            throw new Exception($this->getLastSQL(),ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }

    public function getClickAndAmountByReportDate($arrConds){
        $arrFields = array(
            'org_id as org_id',
            "SUM(house_count) AS house_count",
            "SUM(click_price) AS click_price",
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
            "org_id = ({$arrConds['orgId']})",
            "report_date  IN ({$reportDateStr})",
            "house_type in( $houseTypeStr )",
            "report_type = 4",
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
