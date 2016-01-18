<?php
/*
 * File Name:Service_Data_HouseReprot_Report.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Data_HouseReport_OrgReport
{
    //注意这个pageSize 必须 大于 orgId 的数量  Service_Page_HouseReport_Report_OrgAjax::PAGE_SIZE 有调用
    protected  $pager = array('currentPage'=>1,'pageSize'=>20);
    protected  $sDate = null;
    protected  $eDate = null;

    protected  $houseType = array(0);

    protected  $order = array();

    protected  $fields = array();
    protected  $countType = array(1);
    
  /**
     *{{{__construct 
     *@codeCoverageIgnore
     */
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->sDate = date('Y-m-d',strtotime('yesterday'));
        $this->eDate = date('Y-m-d',strtotime('yesterday'));
        //默认按照统计时间倒叙
        $this->order['order'] = 'DESC';
        $this->order['orderField'] = 'report_Date';
    }
    /*}}}*/
    /*
     *{{{__set
     *@codeCoverageIgnore
     */

     public function setVal($property_name,$value){
            $this->{$property_name} = $value;
    }
    /*}}}*/
    private function mergeDataByOrgId($data1,$data2){
        $tmpData = array();
        $result = array();
        if (empty($data1)) {
            return $data1;
        }
        foreach ($data1 as $item){
            $tmpData[$item['org_id']] = $item;
        }

        foreach ($data2 as $item) {
            if (isset($tmpData[$item['org_id']])) {
                $result[] = array_merge($tmpData[$item['org_id']], $item);
            }
        }
        return $result;
    }
    /*
     * {{{ public getOrgPremierReportById
     * @params orgId int
     * @desc 得到某个账号按照时间分组的精品或者竞价信息
     */
   
    public function getOrgPremierReportById($orgId,$is_count=false){
        if (!is_numeric($orgId)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $houseType = implode(',',$this->houseType);
        $countType = implode(',',$this->countType);
        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseCompanyOrgReport');
        $arrConds = array('org_id ='=>$orgId,'report_date >='=>$stime,'report_date <='=>$etime,"house_type IN ({$houseType}) " ,"count_type IN ({$countType})");
        if (empty($this->fields)) {
            //字段定义查看
            //http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
            $fields = array('sum(customer_count)','sum(account_count)','sum(house_total_count)','sum(premier_count)',
                'sum(refresh_count)','sum(login_count)','sum(account_pv)','sum(tuiguang_count)');
        } else {
            $fields = $this->fields;
        }
        try {
            $ret = $objDao->selectByPage($fields,$arrConds,$this->pager['currentPage'],
                $this->pager['pageSize'],array($this->order['orderField']=>$this->order['order']));
            //echo $objDao->getLastSQL();
            $this->data['data']['list'] = $ret;
            if($is_count){
                $count = $objDao->selectByCount($arrConds);
                $this->data['data']['count'] = $count;
            }
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }

    
    /*}}}*/
    /**
     * {{{ public getOrgPremierReportList
     * @params orgIds array
     * @desc 得到orgIds的列表按照时间分组的精品或者竞价求和的统计
     */
    public function getOrgPremierReportList($orgIds){
        if (!is_array($orgIds) || empty($orgIds)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        if (is_array($orgIds)) {
            $orgIds = implode(',',$orgIds);
        }    
        $houseType = implode(',',$this->houseType);
        $countType = implode(',',$this->countType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseCompanyOrgReport');
        $arrConds = array("org_id IN ({$orgIds})",'report_date >='=>$stime,'report_date <='=>$etime,"house_type IN ({$houseType})" ,"count_type IN ({$countType})");
        try {
            $reportData = $objDao->getSumList($arrConds);
            //echo $objDao->getLastSQL();
            if (count($this->countType)>1 || !in_array($countType, array(7,8,9))) {
	            //这几个数据不需要求和 单独查询
	            $appends = 'group by org_id';
	            $arrConds['report_date >='] = $etime;
	            $arrFields = array('org_id','customer_count as customer_count','account_count as account_count','SUM(house_total_count) as house_total_count');
	            //这几个字段求最后一天的数据
	            $ret = $objDao->selectByPage($arrFields, $arrConds,null,null,array(),$appends);
	            //echo '<hr/>',$objDao->getLastSQL();exit;
	            //$ret = $objDao->select(array('org_id','SUM(customer_count) as customer_count','SUM(account_count) as account_count','SUM(house_total_count) as house_total_count'),$arrConds);
	            $reportData['list'] = $this->mergeDataByOrgId($reportData['list'],$ret);
            }
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
    /*}}}*/
    /**
     * {{{ public getOrgAssureReportById
     * @param array $orgId 组织结构ID
     * @param boole $is_page  是否分页
     * @return array(array())
     */
    public function getOrgAssureReportById($orgId,$is_count = false){
        if (!is_numeric($orgId)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        
        $houseType = implode(',',$this->houseType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseOrgReportV2');
        //条件拼接
        $arrConds = array('org_id ='=>$orgId,'report_date >='=>$stime,'report_date <='=>$etime,"house_type IN ({$houseType})" ,'report_type =' => 4);
        if (empty($this->fields)) {
            //字段定义查看
            //http://wiki.corp.ganji.com/%E6%88%BF%E4%BA%A7/%E6%96%87%E6%A1%A3/%E6%95%B0%E5%AD%97%E5%AE%9A%E4%B9%89/house_report
            $fields = array('customer_count','account_count','house_total_count','house_count','login_count',
            'click_count','refresh_count','promote_count','comment_count');
            
        } else {
            $fields = $this->fields;
        }
    
        try {
            $ret = $objDao->selectByPage($fields,$arrConds,$this->pager['currentPage'],
                $this->pager['pageSize'],array($this->order['orderField']=>$this->order['order']));
            $this->data['data']['list'] = $ret; 
            if($is_count){
                $count = $objDao->selectByCount($arrConds);
                $this->data['data']['count'] = $count;
            }
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
    /*}}}*/
    /**
     * {{{ public getOrgAssureReportList
     * @params orgIds array
     * @desc 得到orgIds的列表按照时间分组的放心房求和的统计
     */
    public function getOrgAssureReportList($orgIds){
        if (!is_array($orgIds) || empty($orgIds)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
    
        if (is_array($orgIds)) {
            $orgIds = implode(',',$orgIds);
        }    
        $houseType = implode(',',$this->houseType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseOrgReportV2');
        $arrConds = array("org_id IN ({$orgIds})",'report_date >='=>$stime,'report_date <='=>$etime,"house_type IN ({$houseType})" ,'report_type = 4' );

         try {
            $reportData = $objDao->getSumList($arrConds);
             $appends = 'group by org_id';
             $arrConds['report_date >='] = $etime;
             $arrFields = array('org_id','customer_count as customer_count','account_count as account_count','SUM(house_total_count) as house_total_count');
             //这几个字段求最后一天的数据
             $ret = $objDao->selectByPage($arrFields, $arrConds,null,null,array(),$appends);
            $reportData['list'] = $this->mergeDataByOrgId($reportData['list'],$ret);
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
    /*}}}*/
    
    /*
     * int $companyId 公司ID
     * array $customerIds 门店数组
     */
    public function getOrgHoursReport($companyId,$customerIds= array()){
         if (!is_numeric($companyId)){
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
        $arrFields = array('SUM(h0) as h0,SUM(h1) as h1,SUM(h2) as h2,SUM(h3) as h3,SUM(h4) as h4,SUM(h5) as h5,SUM(h6) as h6,SUM(h7) as h7,SUM(h8) as h8,SUM(h9) as h9,SUM(h10) as h10,SUM(h11) as h11,SUM(h12) as h12,SUM(h13) as h13,SUM(h14) as h14,SUM(h15) as h15,SUM(16) as h16,SUM(h17) as h17,SUM(h18) as h18,SUM(h19) as h19,SUM(h20) as h20,SUM(h21) as h21,SUM(h22) as h22,SUM(h23) as h23'
            . ',SUM(h0+h1+h2+h3+h4+h5+h6+h7+h8+h9+h10+h11+h12+h13+h14+h15+h16+h17+h18+h19+h20+h21+h22+h23) as total');
     
        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        $countType = implode(',',$this->countType);
        
        $arrConds = array(
            "report_date >="=>$stime,
            "report_date <="=>$etime,
            "count_type IN ({$countType})",
        );
        if (!empty($customerIds) && is_array($customerIds)) {
            $customerId = implode(',',$customerIds);
            $arrConds[] = "customer_id in ({$customerId})";
        }
        
        try{
            $this->data['data'] = $objDao->select($arrFields,$arrConds);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
   
        return $this->data;
    } 
}
