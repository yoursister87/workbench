<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
if(!defined('CODE_BASE2')){
    define('CODE_BASE2', dirname(__FILE__) . '/../../../../../../code_base2');
}
require_once(CODE_BASE2 . '/app/post/adapter/housing/include/Ganji58Relation.class.php');

class Service_Data_SourceList_HouseFrom58List
{
    protected static $sourceUrl= "http://api.fang.58.com/ganji/api/ershoufang?cityid=CITY_ID&localid=LOCAL_ID&shangquanid=SHANGQUAN_ID&price=PRICE&num=NUM";

    protected static $defaultCityId = 2;
    protected static $successCode = 200;

    public function getSearchResult($paramList){
        //local id
        if(isset($paramList['localid']) && !empty($paramList['localid'])){
            $paramList['localid'] = Ganji58Relation::get58LocalIdFromGanji($paramList['localid']);
        }else{
            $paramList['localid'] = "";
        }
        //shangquan id
        if(isset($paramList['shangquanid']) && !empty($paramList['shangquanid'])){
            $paramList['shangquanid'] = Ganji58Relation::get58ShangquanIdFromGanji($paramList['shangquanid']);
        }else{
            $paramList['shangquanid'] = "";
        }
        //price
        if(!isset($paramList['price']) || empty($paramList['price'])){
            $paramList['price'] = ""; 
        }
        //cityid
        if(isset($paramList['cityid']) && !empty($paramList['cityid'])){
            $paramList['cityid'] = Ganji58Relation::get58CityIdFromGanji($paramList['cityid']);
        }else{
            $paramList['cityid'] = self::$defaultCityId;
        }
        $houseList = $this->getHouseList($paramList);
        $data= array(
                'errorno'   => ErrorConst::SUCCESS_CODE,
                'errormsg'  => ErrorConst::SUCCESS_MSG,
                'data'      => $houseList, 
                );
        return $data;
    }

    private function getHouseList($paramList){
        $searchArr = array('CITY_ID', 'LOCAL_ID', 'SHANGQUAN_ID', 'PRICE', 'NUM');
        $replaceArr = array($paramList['cityid'], $paramList['localid'], $paramList['shangquanid'], $paramList['price'], $paramList['num']);
        $url = str_replace($searchArr, $replaceArr, self::$sourceUrl);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 200);
        $data = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($data, true);
        if($result['status'] == self::$successCode && !empty($result['resultlist'])){
            return $result;
        }else{
            return null;
        }
    }

}
