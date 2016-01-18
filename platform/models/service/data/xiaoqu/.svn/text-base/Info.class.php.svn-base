<?php 
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: renyajing <renyajing@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 */ 

class Service_Data_Xiaoqu_Info
{
    /* {{{ getXiaoquBaseInfoByCityPinyin 获取小区基本信息 */
    /**
     * @param  string $city    城市domain
     * @param  string $pinyin  小区拼音
     * @return array           小区信息数组
     */
    public function getXiaoquBaseInfoByCityPinyin($city, $pinyin) {
        try {
            //xiaoqu_xiaoqu
            $xiaoquModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $result = $xiaoquModel->getXiaoquInfoByCityPinyin($city, $pinyin);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /* getXiaoquBaseInfoByCityName 获取小区基本信息 {{{ */ 
    /**
     * @param  string $city    城市domain
     * @param  string $name  小区名字
     * @param  array $queryFileds   查询字段
     * @return array           小区信息数组
     */
    public function getXiaoquBaseInfoByCityName($city, $name,  $queryFields = array()) {
        try {
            //xiaoqu_xiaoqu
            $queryFields = array('id', 'name', 'pinyin', 'latlng', 'thumb_image', 'city', 'district_id', 'street_id', 'address', 'photo_num', 'huxing_num', 'sell_num', 'rent_num');
            $xiaoquModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $xiaoquData = $xiaoquModel->getXiaoquInfoByCityName($city, $name, $queryFields);
            $xiaoquData = $this->patchXiaoquGeoInfo($xiaoquData);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $xiaoquData,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /* getXiaoquInfoById 根据xiaoqu_id获取小区相关信息 {{{ */
    /**
     * @param  string $id        小区id
     * @param  array  $fileds    要查询的数组
     * @param  bool   $needsAvgPrice  是否需要均价
     * @return array             
     */
    public function getXiaoquInfoById($id, $fileds = array(), $needsAvgPrice = false){
        try {
            $xiaoquModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $xiaoquInfo = $xiaoquModel->getXiaoquInfoById($id, $fileds);
            if (!empty($xiaoquInfo) && true == $needsAvgPrice) {
                $statObj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
                $statInfo = $statObj->getXiaoquStatInfoByXiaoquId(array($id), array('avg_price', 'avg_price_change'));
                $xiaoquInfo['avg'] = $statInfo[0]['avg_price'];
                $xiaoquInfo['avg_price_change'] = $statInfo[0]['avg_price_change'];
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $xiaoquInfo,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /** getXiaoquInfoByIds 根据xiaoquId批量获取小区信息 {{{ */
    /** 
     * @param array $xiaoquIds  小区id
     * @param array $queryFields  查询字段
     * @return array()
     */
    public function getXiaoquInfoByIds($xiaoquIds, $queryFields, $needsAvgPrice = false){
        try {
            $xqModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $xqInfoItems = $xqModel->getXiaoquInfoByIds($xiaoquIds, $queryFields);
            if (true === $needsAvgPrice && !empty($xqInfoItems)) {
                $statObj = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
                $statInfo = $statObj->getXiaoquStatInfoByXiaoquId($xiaoquIds, array('xiaoqu_id', 'avg_price', 'avg_price_change'));
                if (!empty($statInfo) && is_array($statInfo)) {
                    $xqInfoItems = $this->patchXiaoquStatInfo($xqInfoItems, $statInfo);
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $xqInfoItems,
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    protected function patchXiaoquStatInfo($xiaoquList, $statList){
        $info = array();
        if (count($xiaoquList) > 0 && count($statList) > 0) {
            foreach ($statList as $item) {
                $info[$item['xiaoqu_id']] = $item;
            }
            foreach ($xiaoquList as $xiaoqu) {
                if (isset($xiaoqu['id']) && isset($info[$xiaoqu['id']])) {
                    $info[$xiaoqu['id']] = array_merge($xiaoqu, $info[$xiaoqu['id']]);
                } 
            }
        }
        return $info;
    }
    /** getXiaoquStatInfoByXiaoquId 获取小区统计信息 {{{ */ 
    /**
     * @param  array    $xiaoquId 小区id
     * @param  array  $fields   要查询的列
     * @return array            
     */
    public function getXiaoquStatInfoByXiaoquId($xiaoquIds, $fields=array()) {
        try {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
            $result = $model->getXiaoquStatInfoByXiaoquId($xiaoquIds, $fields);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;        
    } //}}}
    /* getXiaoquPeitaoV2ByXiaoquId 获取小区配套信息 {{{ */
    /** 
     * 获取小区锁定信息
     * @param int $xiaoquId  小区id
     * @param int $majorType  大类 1:学校 2:购物 3:金融 4:交通 5:医疗 6:餐饮 7:周边 8:厌恶设施
     * @param int $type 小类 2:幼儿园 6:大学 3:邮局 4:银行 8:医院 9:餐饮 10:商场 13:公交 14:地铁 15:周边小区 19:加油站 20:殡仪馆 21:火葬场 22:中学 23:小学 24:超市
     * @return array()
     */     
    public function getXiaoquPeitaoV2ByXiaoquId($xiaoquId, $majorType = null, $type = null) {
        try {
            $model =  Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPeitaoV2');
            $info = $model->getXiaoquPeitaoByXiaoquId($xiaoquId, $majorType, $type);
            if (!empty($info) && is_array($info)) {
                foreach ($info as &$item) {
                    $item['content'] = json_decode($item['content'], true);
                }           
            }           
            $data = array(
                    'errorno'  => ErrorConst::SUCCESS_CODE,
                    'errormsg' => ErrorConst::SUCCESS_MSG,
                    'data'     => $info 
                    );  
        } catch(Exception $e) {
            $data = array(
                    'errorno'  => $e->getCode(),
                    'errormsg' => $e->getMessage(),
                    );  
        }       
        return $data;               
    }//}}}      
    /* getXiaoquLockInfoByXiaoquId 获取小区锁定信息 {{{ */
    /** 
     * 获取小区锁定信息
     * @param int $xiaoquId  小区id
     * @param int $tableType  待锁定表 1:xiaoqu_xiaoqu 2:xiaoqu_peitao
     * @param  string $filed  待锁定字段,如district_id
     * @return array()
     */
    public function getXiaoquLockInfoByXiaoquId($xiaoquId, $tableType = 1, $filed = null) {
        try {
            $lockModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquLock');
            $lockInfo = $lockModel->getXiaoquLockInfoByXiaoquId($xiaoquId, $tableType, $filed);
            $data = array(
                    'errorno'  => ErrorConst::SUCCESS_CODE,
                    'errormsg' => ErrorConst::SUCCESS_MSG,
                    'data'     => $lockInfo
                    );      
        } catch(Exception $e) {
            $data = array(
                    'errorno'  => $e->getCode(),
                    'errormsg' => $e->getMessage(),
                    );  
        }       
        return $data;
    }//}}}
    /**patchXiaoquGeoInfo{{{*/
    protected function patchXiaoquGeoInfo($info){
        if (empty($info) || !is_array($info)) {
            return $info;
        }
        foreach ($info as $key => &$item) {
            $city = GeoNamespace::getCityByDomain($item['city']);
            list($p_index, $c_index) = GeoNamespace::cityCodeDecode($city['city_code']);
            $item['district_info'] = GeoNamespace::getDistrictByScriptIndex($p_index, $c_index, $item['district_id']);
            $item['street_info'] = GeoNamespace::getStreetByScriptIndex($p_index, $c_index, $item['district_id'], $item['street_id']);
        }
        return $info;
    }//}}}

     /* getXiaoquInfoByCityDistrictStreet 获取小区基本信息 {{{ */
    /**
     * @param  string $city    城市domain
     * @param  array $queryFileds   查询字段
     * @return array           小区信息数组
     */
    public function getXiaoquInfoByCityDistrictStreet($city, $district_id, $street_id) {
        try {
            //xiaoqu_xiaoqu
            $queryFields = array('id', 'name');
            $xiaoquModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $xiaoquData = $xiaoquModel->getXiaoquInfoByCityDistrictStreet($city,$district_id,$street_id,$queryFields);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $xiaoquData,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /* {{{exchangeXiaoquGeoInfo*/
    /**
     * @brief 
     *
     * @param $list
     *
     * @returns   
     */
    public function exchangeXiaoquGeoInfo($list){
        if (is_array($list) && count($list) > 0) {
            foreach ($list as &$info) {
                $city = GeoNamespace::getCityByDomain($info['city']);
                $mapArray = GeoNamespace::getMapByCitycodeScriptIndex($city['city_code'],$info['district_id'],$info['street_id']);
                if (is_array($mapArray)) {
                    extract($mapArray,EXTR_OVERWRITE);
                    list($province_script_index,$city_script_index) = GeoNamespace::cityCodeDecode($city_code);
                    if($district_script_index > -1) {
                        $info['district_info'] = GeoNamespace::getDistrictByScriptIndex($province_script_index,$city_script_index,$district_script_index);
                    } else {
                        $info['district_info'] = null;
                    }
                    if($street_script_index > -1) {
                        $info['street_info'] = GeoNamespace::getStreetByScriptIndex($province_script_index,$city_script_index,$district_script_index,$street_script_index);
                    } else {
                        $info['street_info'] = null;
                    }
                }
            }
        }
        return $list;
    }//}}}
    /* {{{getAndExchangeXiaoquGeoInfo*/
    /**
     * @brief 
     *
     * @param $id
     * @param $name
     * @param $pinyin
     * @param $queryFields
     * @param $oldDomain
     * @param $newDomain
     *
     * @returns   
     */
    public function getAndExchangeXiaoquGeoInfo($id, $name, $pinyin = '', $queryFields = array(), $oldDomain = '', $newDomain = ''){
        try {
            $xiaoquInfo = array();
            $isOldCity = true;
            if (!empty($id) && $id > 0) {
                $info = $this->getXiaoquInfoById($id, $queryFields);
                if (!empty($info['data'])) {
                    $info['data'] = array($info['data']);
                }
            } else {
                if (!empty($name)) {
                    $info = $this->getXiaoquBaseInfoByCityName($oldDomain, $name, $queryFields);
                    if (0 == $info['errorno'] && empty($info['data']) && $oldDomian != $newDomain)  {
                        $isOldCity = false;
                        $info = $this->getXiaoquBaseInfoByCityName($newDomain, $name, $queryFields);
                    }
                } 
                if (!empty($pinyin) && (empty($info['data']) || count($info['data']) > 1)) {
                    $info = $this->getXiaoquBaseInfoByCityPinyin($oldDomain, $pinyin);
                    if (0 == $info['errorno'] && empty($info['data']) && $oldDomian != $newDomain)  {
                        $isOldCity = false;
                        $info = $this->getXiaoquBaseInfoByCityPinyin($newDomain, $pinyin);
                    }
                    $info['data'] = array($info['data']);
                }
            }
            if (0 === (int)$info['errorno'] && 1 == count($info['data'])) {
                $xiaoquInfo = $info['data'];
                if ($oldDomain != $xiaoquInfo[0]['city'] || $isOldCity == false) {
                    $xiaoquInfo = $this->exchangeXiaoquGeoInfo($xiaoquInfo);
                } else {
                    $xiaoquInfo = $this->patchXiaoquGeoInfo($xiaoquInfo);
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $xiaoquInfo,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
}
