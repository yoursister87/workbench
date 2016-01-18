<?php
/**
 * File Name:HouseAccountHours.class.php
 * @author              $Author:lukang$
 * @file                $HeadUrl$
 * @version             $Rev$
 * @lastChangeBy        $LastChangedBy$
 * @lastmodified        $LastChangedDate$
 * @copyright           Copyright (c) 2015, www.ganji.com
 */
class Service_Data_HouseReport_HouseAccountHours
{
    protected $data = array();

    protected $arrFields = array(
        'report_date as report_date',
        'count_type as count_type',
        'opt_type as opt_type',
        'IFNULL(SUM(h0),0) as h0',
        'IFNULL(SUM(h1),0) as h1',
        'IFNULL(SUM(h2),0) as h2',
        'IFNULL(SUM(h3),0) as h3',
        'IFNULL(SUM(h4),0) as h4',
        'IFNULL(SUM(h5),0) as h5',
        'IFNULL(SUM(h6),0) as h6',
        'IFNULL(SUM(h7),0) as h7',
        'IFNULL(SUM(h8),0) as h8',
        'IFNULL(SUM(h9),0) as h9',
        'IFNULL(SUM(h10),0) as h10',
        'IFNULL(SUM(h11),0) as h11',
        'IFNULL(SUM(h12),0) as h12',
        'IFNULL(SUM(h13),0) as h13',
        'IFNULL(SUM(h14),0) as h14',
        'IFNULL(SUM(h15),0) as h15',
        'IFNULL(SUM(h16),0) as h16',
        'IFNULL(SUM(h17),0) as h17',
        'IFNULL(SUM(h18),0) as h18',
        'IFNULL(SUM(h19),0) as h19',
        'IFNULL(SUM(h20),0) as h20',
        'IFNULL(SUM(h21),0) as h21',
        'IFNULL(SUM(h22),0) as h22',
        'IFNULL(SUM(h23),0) as h23',
    );

    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->hourDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
    }
    
    public function hoursClickByCity($cityId,$houseType,$countType = 1,$reportDate = null){
        try {
            if (!is_numeric($cityId) || (!is_array($houseType) || empty($houseType))) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }

            if (!isset($reportDate)) {
                $reportDate = date('Y-m-d',strtotime('yesterday'));
            }

            $dao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
            $arrConds = null;
            $arrConds[] = "house_type IN (".implode(',',$houseType).")";
            $arrConds['city_id ='] = $cityId;
            $arrConds['account_id ='] = -1;
            //报表日期
            $arrConds['report_date ='] = strtotime($reportDate);
            //这个是点击类别
            $arrConds['opt_type ='] = 2;
            $arrConds['count_type = '] = $countType;
            $res = $this->hourDao->select($this->arrFields, $arrConds);

            $this->data['data'] = $res;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }

    public function hoursClickByCustomerId($customerId, $houseType, $optType, $countType = 1, $reportDate = null){
        try {
            if ((!is_array($customerId) || empty($customerId)) || (!is_array($houseType) || empty($houseType)) ||
                !is_numeric($optType)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }

            if (!isset($reportDate)) {
                $reportDate = date('Y-m-d',strtotime('yesterday'));
            }
            
            
            $arrConds[] = "house_type IN (".implode(',',$houseType).")";
            $arrConds[] = "customer_id IN (".implode(',',$customerId).")";
            //报表日期
            $arrConds['report_date ='] = strtotime($reportDate);
            //这个是点击类别
            $arrConds['opt_type ='] = $optType;
            $arrConds['count_type = '] = $countType;
            $res = $this->hourDao->select($this->arrFields, $arrConds);
            $this->data['data'] = $res;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }

    public function hoursClickByAccountId($accountId,$houseType,$optType,$countType = 1,$reportDate = null){
        try {
            if (!is_numeric($accountId) || (!is_array($houseType) || empty($houseType))  ||
                !is_numeric($optType)
            ) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }

            if (!isset($reportDate)) {
                $reportDate = date('Y-m-d',strtotime('yesterday'));
            }

            $arrConds[] = "house_type IN (".implode(',',$houseType).")";
            $arrConds['account_id ='] = $accountId;
            //报表日期
            $arrConds['report_date ='] = strtotime($reportDate);
            //这个是点击类别
            $arrConds['opt_type ='] = $optType;
            $arrConds['count_type = '] = $countType;
            $res = $this->hourDao->select($this->arrFields, $arrConds);
            $this->data['data'] = $res;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
}
