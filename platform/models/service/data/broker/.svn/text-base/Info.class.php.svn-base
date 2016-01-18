<?php
class Service_Data_Broker_Info 
{
    /* {{{getAccountInfo*/
    /**
     * @brief 
     *
     * @param $accountids
     *
     * @returns   
     */
    public function getAccountInfo($accountIdArr) {
        try {
            $resultArr = array();
            $userIdArr = array();
            // get account info
            $accountInfoArr = $this->getAccountInfoByAccountId($accountIdArr);
            if (false === $accountInfoArr || !is_array($accountInfoArr) || empty($accountInfoArr)) { 
                throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
            }
            foreach ($accountInfoArr as $info) {
                $resultArr[$info['AccountId']] = array('user_id' => $info['UserId'], 'account_name' => $info['AccountName']);
                $userIdArr[] = $info['UserId'];
            }
            // get bangbang years
            if (!empty($userIdArr)) {
                $userBangYearsArr = $this->getUserBangbangYears($userIdArr);
                if (is_array($userBangYearsArr) && !empty($userBangYearsArr)) {
                    foreach ($resultArr as $accountId => $accountInfo) {
                        if (isset($userBangYearsArr[$accountInfo['user_id']])) {
                            $bangInfo = $userBangYearsArr[$accountInfo['user_id']];
                            $bangInfo = (isset($bangInfo[0])) ? $bangInfo[0] : $bangInfo;
                            $resultArr[$accountId]['bangbang_years'] = ($bangInfo['years'] > 0) ? $bangInfo['years'] : null;
                            $resultArr[$accountId]['bangbang_active'] = $bangInfo['is_active'];
                        }
                    }
                }
            }
            return $resultArr;
        } catch (Exception $e) {
            return false;
        }
    }/* }}} */
    /* {{{getAccountInfoByAccountId*/
    /**
     * @brief 
     *
     * @param $accountIdArr
     *
     * @returns   
     */
    protected function getAccountInfoByAccountId($accountIdArr){
        if (!is_array($accountIdArr) || empty($accountIdArr)) {
            throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
        }
        $accountIdArr = array_unique($accountIdArr);
        $modelInstance = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccount');
        return $modelInstance->getAccountInfoByAccountId($accountIdArr, array('AccountId', 'AccountName', 'UserId'));
    }//}}}
    /* {{{getUserBangbangYears*/
    /**
     * @brief 
     *
     * @param $userIdArr
     *
     * @returns   
     */
    protected function getUserBangbangYears($userIdArr){
        if (is_array($userIdArr) && !empty($userIdArr)) {
            return BangNamespace::getUserCooperationYear($userIdArr, 7);
        } else {
            throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
        }
    }//}}}
}
