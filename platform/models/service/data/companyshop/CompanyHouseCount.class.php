<?php
/*
 * File Name:HouseReportCompanyReportDataService.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Data_CompanyShop_CompanyHouseCount
{

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }
    /* {{{protected getManagerAccountData */
    /**
     * @params 公司id
     *
     * @returns
     */
    protected function getManagerAccountData($companyId){
        $objDao = Gj_LayerProxy::getProxy('Dao_Gcrm_HouseManagerAccount');
        $arrConds = array('company_id =' => $companyId,'level = 1');
        $arrFileds = array('id','company_id');
        $managerData = $objDao->select($arrFileds,$arrConds);
        return $managerData[0];
    }
    /*}}}*/
    /* {{{protected getCompanyOrgReportByOrgData */
    /**
     * @params org_id
     * @params 查询日期  需要为整点时间点
     * @returns house_total_count 房源总数 account_count 经纪人总数
     */
    protected function getCompanyOrgReportByOrgData($orgId,$date){
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseCompanyOrgReport');
        $arrConds = array('report_date =' => $date,'org_id = '=>$orgId,'house_type = 0 AND count_type = 1');
        $arrFileds = array('id','house_total_count','report_date','account_count');
        $reportData = $objDao->select($arrFileds,$arrConds);
        return $reportData[0];
    }
    /*}}}*/
    /*{{{getCompanyHouseCountByCompanyIdByCache  外面可以使用call的方式调用 使使用缓存*/
    public function getCompanyHouseCountByCompanyIdByCache($companyId,$date = null){
        $obj = Gj_LayerProxy::getProxy("Service_Data_CompanyShop_CompanyHouseCount");
        $ret = $obj->getCompanyHouseCountByCompanyId($companyId,$date);
        return $ret;
    }
    /*}}}*/
 
    /* {{{public getCompanyHouseCountByCompanyId */
    /**
     * @params 公司id
     * @params 查询日期  需要为整点时间点
     * @returns house_total_count 房源总数 account_count 经纪人总数
     */
    public function getCompanyHouseCountByCompanyId($companyId,$date = null){
        $date = isset($date)?$date:strtotime('yesterday');
        try {
            if (!is_numeric($date)) {
                $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->data;
            }
            $mangerInfo = $this->getManagerAccountData($companyId);           
            if (empty($mangerInfo)) {
                throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
            }
            $reportData = $this->getCompanyOrgReportByOrgData($mangerInfo['id'],$date);
            $this->data['data'] = $reportData;
            if ($_GET[__METHOD__]){
                var_dump($this->data);
            }
        } catch(Exception $e) {
            $this->data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->data;
    } 
    //}}}
    public function getTimeout($func,$args) {
        if ($func == 'getCompanyHouseCountByCompanyId') {
        	$time = new TimeMock();
            //数据因为第二天的 5点之前跑完 所以数据 在这之前还是昨天的 保守选择6
            return (strtotime('tomorrow') + 3600 * 6) - $time->getTime();
        }
    }
}
