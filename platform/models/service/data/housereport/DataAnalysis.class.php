<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   lukang$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 *  SELECT  SUM(${click_field}),  SUM(${premier_count_field}),  ${report_date_field},  ${count_type_field}  
 *  FROM  ${TABLE_NAME}
 *  WHERE ${org_id_field} = org_id1 
 *      AND  ${report_date_field} IN (date1, date2) 
 *      AND  ${house_type_field} IN (type1 [, ...]) 
 *      AND  ${count_type_field} IN (count_type1[, ...])
 *  GROUP BY ${report_date_field} , ${count_type_field} 
 *  ORDER BY NULL;
 */
class Service_Data_HouseReport_DataAnalysis
{
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    protected function mergeData($data){
        $listData = array();
        $tmp = reset($data);
        if (is_array($data)) {
            foreach ($data as $list) {
                if (is_array($list))
                $listData = array_merge($listData,$list);
            }
        } 
        return $listData;
    }

    public function getAccountData($queryConds){
        try{
            $conds = $this->processQueryConds($queryConds);
            $preDaoObj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
            $assDaoObj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountReportV2');

            $params = $conds['data'];
            $params['stime'] = strtotime($params['sDate']);
            $params['etime'] = strtotime($params['eDate']);
            if ($conds['errorno']) {
                throw new Gj_Exception($conds['errorno'],$conds['errormsg']);
            }
            foreach ($params['month'] as $month) {
                $ret[] = $preDaoObj->getClickAndAmountByReportDate($params,$month);
            }
            $ret[] = $assDaoObj->getClickAndAmountByReportDate($params);
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }

        //获得精品数据
        $result = $this->mergeData($ret);
        $this->data['data'] = $result;
        return $this->data;
    }
    
    /*
     *@codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    public function getOrgData($queryConds){
        try{
            $conds = $this->processQueryConds($queryConds);
            $preDaoObj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseCompanyOrgReport');
            $assDaoObj = Gj_LayerProxy::getProxy('Dao_Housereport_HouseOrgReportV2');

            if ($conds['errorno']) {
                 throw new Gj_Exception($conds['errorno'],$conds['errormsg']);
            }
            $params = $conds['data'];
            $params['stime'] = strtotime($params['sDate']);
            $params['etime'] = strtotime($params['eDate']);


            $ret[] = $assDaoObj->getClickAndAmountByReportDate($params);
            $ret[] = $preDaoObj->getClickAndAmountByReportDate($params);
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }

        $result = $this->mergeData($ret);
        $this->data['data'] = $result;
        return $this->data;
    }
    /*
     * 输入格式
     *  array(
     *       'accountId'=>1234,
     *       'sDate'=>'2015-01-30',
     *       'eDate'=>'2015-03-01',
     *       'houseType'=>array(1,3,5),
     *       'countType'=>array(1,2,4,7,8),
     *   );  
     * 返回格式:
     *   array(
     *       'accountId'=>1234,
     *       'sDate'=>'2015-01-30',
     *       'eDate'=>'2015-03-01',
     *       'month'=>array('2015-01-01','2015-02-01','2015-03-01'),
     *       'houseType'=>array(1,3,5),
     *       'countType'=>array(1,2,4,7,8),
     *   );
     */
    protected function processQueryConds($queryConds){
        $checkObj = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
        try{
            $params = $checkObj->setDate($queryConds,false);
            $params['accountId'] = $queryConds['accountId'];
            $params['orgId'] = $queryConds['orgId'];
            $params['month'] = $queryConds['month'];
            $params['houseType'] = $checkObj->setHouseType($queryConds['houseType']);
            $params['countType'] = $checkObj->setCountType($queryConds['countType']);
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return $this->data;
        }
        $stime = strtotime($params['sDate']);
        $etime = strtotime($params['eDate']);
        $tmp = array();
        $days = array();
        for ($i = $stime;$i <= $etime;$i+=86400) {
            $tmp[date("Y-m",$i)] = true;
            $days[date("Y-m-d",$i)] = $i;
        }
        foreach ($tmp as $key=>$time) {
            //得到每月的一日
            $month[] = date('Y-m-1',strtotime("{$key}-1"));
        }
        $params['month'] = $month;
        $params['days'] = $days;
        $this->data['data'] = $params;
        return $this->data;
    }
}
