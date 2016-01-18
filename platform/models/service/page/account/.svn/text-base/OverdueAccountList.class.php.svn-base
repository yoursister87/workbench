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
class Service_Page_Account_OverdueAccountList{
    public function __construct(){

    }
    // @codeCoverageIgnoreStart
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    // @codeCoverageIgnoreEnd
    //{{{execute
    /**
     * 获取7天内到期并且有推广房源的经纪人
     * @param $arrInput
     */
    public function execute($arrInput){
        if(!is_numeric($arrInput['CityId'])){
            $data['data'] = array();
            $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
            return $data;
        }
        //查询出一批即将到期的经纪人
        $resAccount = $this->getOverdueAccountByCityId($arrInput);
        if($resAccount['errorno'] && !is_array($resAccount['data'])){
            return $resAccount;
        }
        //查询经纪人是否有房源
        $accountArr = $this->getIsHouseListByAccount($resAccount['data']);
        if(count($accountArr) <= 0){
            $data['data'] = array();
            $data['errorno'] = ErrorConst::SUCCESS_CODE;
            $data['errormsg'] =  ErrorConst::SUCCESS_MSG;
            return $data;
        }
        //获取公司门店信息
        $accountInfoArr = $this->getGroupInfoByAccount($accountArr);
        return $accountInfoArr;

    }//}}}
    //{{{executeByCache
    /**
     * 获取7天内到期并且有推广房源的经纪人
     * @param $arrInput
     */
    // @codeCoverageIgnoreStart
    public function executeByCache($arrInput){
        $obj = Gj_LayerProxy::getProxy('Service_Page_Account_OverdueAccountList');
        return $obj->execute($arrInput);
    }
    // @codeCoverageIgnoreEnd
    //}}}
    //{{{getKey
    /**
     * $func string 当前调用的函数名
     * $args mixed  调用这个函数，传递的参数
     */
    // @codeCoverageIgnoreStart
    public function getKey($func, $args){
        if ($func === 'execute') {
            return 'executeByCache_' . $args[0]['CityId'];
        }
    }
    // @codeCoverageIgnoreEnd
    //}}}
    //{{{getOverdueAccountByCityId
    /**
     * 获取指定城市下7天内到期的经纪人
     * @param $arrInput
     * @return mixed
     */
    protected function getOverdueAccountByCityId($arrInput){
        $arrFields = array('AccountId','UserId',"GroupId",'AccountName','Picture',"CellPhone");
        $objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $arrConds = array(
            'CityId'=> $arrInput['CityId'],
            's_premier_expire'=>$arrInput['s_premier_expire'],
            'e_premier_expire'=>$arrInput['e_premier_expire'],
        );
        $pageSize = 100;
        if(in_array($arrInput['CityId'],array(0,100,400,401))){
            $pageSize = 60;
        }
        try {
            $res = $objServiceCustomerAccount->getAccountListByCustomerId($arrConds, $arrFields, 1, $pageSize);
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return  $this->data;
        }
        return $res;
    }//}}}
    //{{{getIsHouseListByAccount
    /**
     * 获取指定经纪人1条推广中的房源
     * @param $accountList
     * @return array
     */
    protected function getIsHouseListByAccount($accountList){
        $arrConds = array(
            'premier_status'=>array(2,102),
            //'s_post_at'=> strtotime('-1 week'),
        );
        $pageSize = 1;
        $objServicePremierQuery = Gj_LayerProxy::getProxy('Service_Data_Source_PremierQuery');
        $accountArr = array();
        try {
            foreach ($accountList as $row) {
                $arrConds['account_id'] = $row['AccountId'];
                $resHouse = $objServicePremierQuery->getTuiguangHouseByAccountId($arrConds, array(), 1, $pageSize);
                if (!$resHouse['errorno'] && count($resHouse['data']) <= 0) {
                    continue;
                } else {
                    $accountArr[] = $row;
                }
                if (count($accountArr) == 8) {
                    break;
                }
            }
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return  $this->data;
        }
        return $accountArr;
    }//}}}
    //{{{getGroupInfoByAccount
    /**
     * 获取指定经纪人的公司信息
     * @param $accountArr
     * @return mixed
     */
    protected function getGroupInfoByAccount($accountArr){
        $groupIdArr = array();
        if(count($accountArr) > 0 && function_exists(array_column)){
            $groupIdArr = array_column($accountArr,'GroupId');
        } else if (!empty($accountArr)) {
            foreach ($accountArr as $row){
                $groupIdArr[] = $row['GroupId'];
            }
        }
        $whereConds = array('GroupId'=>$groupIdArr);
        $objServiceCustomer = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
        try {
            $resCustomer = $objServiceCustomer->getCustomerInfoListByCompanyId($whereConds);
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
            return  $this->data;
        }
        if($resCustomer['errorno']){
            return $resCustomer;
        }
        $groupInfoArr = array();
        if(count($resCustomer['data']) > 0 && function_exists(array_column)){
            $groupInfoArr = array_column($resCustomer['data'],NULL,'id');
        } else if (!empty($resCustomer['data'])) {
            foreach ($accountArr as $row){
                $groupInfoArr[$row['id']] = $row;
            }
        }
        $accountInfoArr['data'] = array();
        $accountInfoArr['errorno'] = ErrorConst::SUCCESS_CODE;
        $accountInfoArr['errormsg'] =  ErrorConst::SUCCESS_MSG;
        foreach($accountArr as $row){
            if(is_array($groupInfoArr[$row['GroupId']])) {
                $accountInfoArr['data'][] = array_merge($row, $groupInfoArr[$row['GroupId']]);
            }else{
                $accountInfoArr['data'][] = $row;
            }
        }
        return $accountInfoArr;
    }//}}}
}
