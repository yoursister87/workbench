<?php
/**
 * @package              
 * @subpackage           
 * @brief                详情页:100%房源经纪人评论        
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Page_Source_Api_QueryAgentCommentByPuid{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * @param $arrParam
     * @return array
     */
    public function execute($arrParams){
        $arrRet = $this->arrRet;
        try{
            $this->checkInput($arrParams);
            $puid = $arrParams['puid'];
            $obj = Gj_LayerProxy::getProxy("Service_Data_Source_HouseRealComment");
            //user_id === ucenterid
            $arrSourceInfo= $obj->getCommentListByWhere(array("puid" =>$puid ), array("user_id", "user_phone", "post_at", "user_name", "modified_at", "content", "title"), 1, 10, array("modified_at" => "desc"));
            if($arrSourceInfo['errorno'] != ErrorConst::SUCCESS_CODE){
                 throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,  "查询失败");
            }
            //补经纪人数据
            $userIds = array();
            foreach ($arrSourceInfo['data'] as $item) {
                $userIds[] = $item['user_id'];
            }
            $objAccount = Gj_LayerProxy::getProxy("Service_Data_Gcrm_CustomerAccount");
            if(!empty($userIds)){
                $accounts = $objAccount->getAccountInfoByUserId($userIds);
                $accounts = $accounts['data'];
            }
            $accountInfo= array();
            if(is_array($accounts) &&!empty($accounts)){
                foreach($accounts as $item){
                    $accountInfo[$item['UserId']] = array('account_id'=> $item['AccountId'], 'Picture'=>$item['Picture'], 'city_id'=> $item['CityId'], 'user_phone'=>$item['CellPhone'], 'user_name'=> $item['AccountName']);
                }
            }
            $bangyears = $this->getUserBangbangYears($userIds);

            foreach($arrSourceInfo['data'] as &$item ){
                $userId= $item['user_id'];
                if(!empty($accountInfo[$userId])) {
                    $item['account_id']= $accountInfo[$userId]['account_id'];
                    $item['user_phone']= $accountInfo[$userId]['user_phone'];
                    $item['user_name']= $accountInfo[$userId]['user_name'];
                    $item['photo'] = $this->getPhotoUrl($accountInfo[$userId]['Picture']);
                }
                $city = GeoNamespace::getCityByCityCode($accountInfo[$userId]['city_id']);
                $item['domain']= $city['domain'];
                $item['modified_at'] = empty($item['modified_at'])? $item['post_at']: $item['modified_at'];
                $item['modified_at'] = date("Y-m-d", $item['modified_at']);
                $item['phone'] = $item['user_phone'];
                //添加帮帮信息
                if(!empty($bangyears[$userId][0]) && is_array($bangyears[$userId][0])) {
                     $bang = $bangyears[$userId][0];
                } else {
                     $bang = $bangyears[$userId];
                }
                if($bang['is_active'] == 1 && $bang['years'] > 0) {
                    $item['bangyears'] = $bang['years'];
                }
            }
            $arrRet['data'] = $arrSourceInfo['data'];
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();

        }
        return $arrRet;

    }

    protected function getPhotoUrl($path){
         if (0 === strpos($path, 'http://')) {
             return $path;
         } else if ($path) {
             if ('/' == substr($path, 0, 1)) {
                  $path = substr($path, 1);
             }
                  return "http://image.ganjistatic1.com/" . $path;
        } else {
            return 'http://stacdn201.ganjistatic1.com/src/image/house/fangvip/noimg_head.gif';
        }
    }
    /* {{{getUserBangbangYears*/
    /**
     * @brief 
     *
     * @param $userIdArr
     *
     * @returns   
     */
    protected function getUserBangbangYears($userIds){
        if (is_array($userIds) && !empty($userIds)) {
            return BangNamespace::getUserCooperationYear($userIds, 7);
        } else {
            return array();
        }
    }//}}}

    protected function checkInput($arrParams){
       if(!is_numeric($arrParams['puid'])){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE,'post param is wrong');
       }
    }
}
