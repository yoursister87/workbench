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

class Service_Data_Xiaoqu_Xinloupan
{ 
    /** {{{ getXinloupanCityConfig 获取新楼盘开通城市配置
     * 
     */
    public function getXinloupanCityConfig(){
        //key为开通城市 value为要显示城市的房源
        return array(
            'sh' => array('sh', 'su'),
            'su' => array('sh', 'su'),
        );
    } //}}}
    /**readCacheData(){{{*/
    public function readCacheData($cacheKey){
        $cache = Gj_Cache_CacheClient::getInstance('Memcache');
        $cacheData = $cache->read($cacheKey);
        if (false == $cacheData || !is_array($cacheData)) {
            $cacheData = array();
        }
        return $cacheData;
    }//}}}
    /**writeCacheData(){{{*/
    protected function writeCacheData($cacheKey, $cacheData){
        $cacheTime = 3600 * 12;
        $cache = Gj_Cache_CacheClient::getInstance('Memcache');
        $cache->write($cacheKey, $cacheData, $cacheTime);
    }//}}}
    /**getXinloupanDataByCity{{{*/
    /**
     * @brief 获取指定城市列表下的楼盘列表
     * @param $cityConfig  array()  城市列表
     * @param $start int  
     * @param $limit int
     * @return array('list' => array(), 'total' => int)
     */
    protected function getXinloupanDataByCity($cityConfig, $start = 0, $limit = 10){
        $data = array();
        $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_Xinloupan');
        $data['list'] = $model->getXinloupanDataByCityArr($cityConfig, $start, $limit);
        $data['total'] = $model->getXinloupanCountByCityArr($cityConfig);
        return $data;
    }//}}}
    /**getXiaoquInfoByIds{{{*/
    /**
     * @brief 批量获取小区信息 
     * @param $xiaoquIds array()
     * @return array()
     */
    protected function getXiaoquInfoByIds($xiaoquIds){
        $loupanInfoList = array();
        if (true === $this->validatorArray($xiaoquIds)) {
            $fields = $this->getXiaoquXiaoquFieldsForList();
            $dataObj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');
            $data = $dataObj->getXiaoquInfoByIds($xiaoquIds, $fields);
            if ('0' === $data['errorno']) {
                $loupanInfoList = $data['data'];
            }
        }
        return $loupanInfoList;
    }//}}}
    /**formataXinloupanData{{{*/
    /**
     * @brief 
     * @param $xinloupan array()
     * @param array(array(楼盘列表), array(xiaoquIds))
     */
    protected function formataXinloupanData($xinloupan){
        $loupanList = $xiaoquIds = array();
        if (true === $this->validatorArray($xinloupan)) {
            foreach ($xinloupan as $key => $val) {
                $val['huxing_price'] = json_decode($val['huxing_price'], true);
                $loupanList[$val['xiaoqu_id']] = $val;
                $xiaoquIds[] = $val['xiaoqu_id'];
            }
        }
        return array($loupanList, $xiaoquIds);
    }//}}}
    /**mergeXiaoquXinloupan{{{*/
    /**
     * @brief  批量merge
     * @param $loupanList 新楼盘列表
     * @param $xiaoquInfo 小区信息列表
     * @param $unsetXiaoquId 需要排重的小区id
     * @return array()
     */
    protected function mergeXiaoquXinloupan($loupanList, $xiaoquInfo, $unsetXiaoquId = 0){
        $loupanInfo = array();
        if (is_array($loupanList) && is_array($xiaoquInfo)) {
            foreach ($xiaoquInfo as $info) {
                if (isset($loupanList[$info['id']]) && $info['id'] != $unsetXiaoquId) {
                    $loupanInfo[$info['id']] = array_merge($info, $loupanList[$info['id']]);
                }
            }
        }
        return $loupanInfo;
    }//}}}
    /** {{{ getXinloupanListByCity 通过域名获取新楼盘列表
     * 
     * @param $city domain
     * @param $limit 获取多少条记录
     * @return array()
     */
    public function getXinloupanListByCity($city, $start = 0, $limit=10){
        try {
            $loupanInfo = $loupanInfoList = $xiaoquIds = array();
            //根据配置检查city是否合法，合法后调用model 
            $cityConfig = $this->checkCityValid($city);
            if (empty($cityConfig) || !is_array($cityConfig)) {
            } else {
                $cacheKey = 'fang_platform_xinloupan_list_by_city_' . $city . '_' . $start;
                $loupanInfo = $this->readCacheData($cacheKey);
                if (!(FALSE !== $loupanInfo && true === $this->validatorArray($loupanInfo))) {
                    $xinloupan = $this->getXinloupanDataByCity($cityConfig, $start, $limit);
                    list($loupanList, $xiaoquIds) = $this->formataXinloupanData($xinloupan['list']);
                    $xiaoquInfo = $this->getXiaoquInfoByIds($xiaoquIds);
                    $loupanInfo = $this->mergeXiaoquXinloupan($loupanList, $xiaoquInfo);
                    $loupanInfo = array('item' => $loupanInfo, 'total' => $xinloupan['total']);
                    $this->writeCacheData($cacheKey, $loupanInfo);
                }
            }
            $data = array(
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $loupanInfo,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getXiaoquInfoById(){{{*/
    /**
     * @brief 根据xiaoquId获取小区信息
     * @param $xiaoquId int 
     * @return array()
     */
    protected function getXiaoquInfoById($xiaoquId){
        $xiaoquInfo = array();
        if ((int)$xiaoquId > 0) {
            $obj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');
            $data = $obj->getXiaoquInfoById($xiaoquId, array()) ;
            if ('0' === $data['errorno']) {
                $xiaoquInfo = $data['data'];
            }
        }
        return $xiaoquInfo;
    }//}}}
    /**getXinloupanDataByXiaoquId(){{{*/
    /**
     * @brief 根据xiaoquId获取楼盘信息
     * @param $xiaoquId int 
     * @return array()
     */
    protected function getXinloupanDataByXiaoquId($xiaoquId){
        $loupanInfo = array();
        if ((int)$xiaoquId > 0) {
            $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_Xinloupan');
            $loupanInfo = $model->getXinloupanInfoByXiaoquId($xiaoquId);
            if (isset($loupanInfo['huxing_price'])) {
                $loupanInfo['huxing_price'] = json_decode($loupanInfo['huxing_price'], true);
            }
        }
        return $loupanInfo;
    }//}}}
    /** {{{ getXinloupanInfoByXiaoquId 通过xiaoquId获取楼盘信息
     * @param $xiaoquId 
     * @return array()
     */
    public function getXinloupanInfoByXiaoquId($xiaoquId){
        try {
            $cacheKey = 'fang_platform_xinloupan_info_by_id_' . $xiaoquId;
            $loupanInfo = $this->readCacheData($cacheKey);
            if (!(FALSE !== $loupanInfo && true === $this->validatorArray($loupanInfo))) {
                $xiaoquInfo = $this->getXiaoquInfoById($xiaoquId);
                $loupanInfo = $this->getXinloupanDataByXiaoquId($xiaoquId);
                if (true === $this->validatorArray($xiaoquInfo) && true === $this->validatorArray($loupanInfo)) {
                    $loupanInfo = array_merge($xiaoquInfo, $loupanInfo);
                } else {
                    $loupanInfo = array();
                }
                $this->writeCacheData($cacheKey, $loupanInfo);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'     => $loupanInfo
            );
        }catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getXiaoquBaseInfoByCityPinyin{{{*/
    /**
     * @brief 通过cityDomain, xiaoquPinyin 获取小区信息
     * @param $city  cityDomain
     * @param $pinyin  xiaoquPinyin
     * @return array() 小区基本信息
     */
    protected function getXiaoquBaseInfoByCityPinyin($city, $pinyin){
        $xiaoquInfo = array();
        $obj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');
        $data = $obj->getXiaoquBaseInfoByCityPinyin($city, $pinyin) ;
        if ('0' === $data['errorno']) {
            $xiaoquInfo = $data['data'];
        }
        return $xiaoquInfo;
    }//}}}
    /** {{{ getXinloupanInfoByCityPinyin 通过city pinyin获取楼盘信息
     * @param $city 城市domain 
     * @param $pinyin    楼盘拼音
     * @return array()
     */
    public function getXinloupanInfoByCityPinyin($city, $pinyin){
        try {
            $loupanInfo = array();
            $cacheKey = 'fang_platform_xinloupan_info_by_citypinyin_' . $city . '_' . $pinyin;
            $loupanInfo = $this->readCacheData($cacheKey);
            if (!(FALSE !== $loupanInfo && true === $this->validatorArray($loupanInfo))) {
                $loupanInfo = $this->getXiaoquBaseInfoByCityPinyin($city, $pinyin);
                $info = $this->getXinloupanDataByXiaoquId($loupanInfo['id']);
                if (true === $this->validatorArray($loupanInfo) && true === $this->validatorArray($info)) {
                    $loupanInfo = array_merge($loupanInfo, $info);
                } else {
                    $loupanInfo = array();
                }
                $this->writeCacheData($cacheKey, $loupanInfo);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'     => $loupanInfo
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getXiaoquBaseInfoByCityName{{{*/
    /**
     * @brief 通过cityDomain, xiaoquName 获取小区信息
     * @param $city  cityDomain
     * @param $name  xiaoquName
     * @return array() 小区基本信息
     */
    protected function getXiaoquBaseInfoByCityName($city, $name){
        $xiaoquInfo = array();
        $queryFields = $this->getXiaoquXiaoquFieldsForList();
        $obj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');
        $data = $obj->getXiaoquBaseInfoByCityName($city, $name, $queryFields) ;
        if ('0' === $data['errorno']) {
            $xiaoquInfo = $data['data'];
        }
        return $xiaoquInfo;
    }//}}}
    /**patchLoupanInfo{{{*/
    /**
     * @brief 批量补充新楼盘信息
     * @param $xiaoquList   小区信息列表
     * @return array()
     */
    protected function patchLoupanInfo($xiaoquList){
        $loupanInfo = array();
        if (true === $this->validatorArray($xiaoquList)) {
            foreach ($xiaoquList as $item) {
                $info = $this->getXinloupanDataByXiaoquId($item['id']);
                if (!empty($info) && is_array($info)) {
                    $loupanInfo[] = array_merge($item, $info);
                }
            }
        }
        return $loupanInfo;
    }//}}}
    /** {{{ getXinloupanInfoByCityName 通过city name获取楼盘信息
     * @param $city 城市domain 
     * @param $name    楼盘名字
     * @return array()
     */
    public function getXinloupanInfoByCityName($city, $name){
        try {
            $cacheKey = 'fang_platform_xinloupan_info_by_cityname_' . $city . '_' . $name;
            $loupanInfo = $this->readCacheData($cacheKey);
            if (!(FALSE !== $loupanInfo && true === $this->validatorArray($loupanInfo))) {
                $xiaoquInfo = $this->getXiaoquBaseInfoByCityName($city, $name);
                $loupanInfo = $this->patchLoupanInfo($xiaoquInfo);
                $this->writeCacheData($cacheKey, $loupanInfo);
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'     => $loupanInfo
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /** {{{ getXinloupanRecommendByCity 通过city获得新楼盘的推荐 
     * @brief 这个方法跟getXinloupanListByCity几乎雷同，但是考虑到以后可能会有变化，暂时占位
     * @param $city   cityDomain
     * @param  $unsetId   需要排重的小区id 即当前小区详情页的id
     * @param  $limit  取几条数据
     * @retrun array()
     */
    public function getXinloupanRecommendByCity($city, $unsetId = 0, $limit = 6){
        try {
            $loupanInfo = $loupanInfoList = $xiaoquIds = array();
            //根据配置检查city是否合法，合法后调用model 
            $cityConfig = $this->checkCityValid($city);
            if (empty($cityConfig) || !is_array($cityConfig)) {
            } else {
                $cacheKey = 'fang_platform_xinloupan_recommend_by_city_' . $city;
                $loupanInfo = $this->readCacheData($cacheKey);
                if (!(FALSE !== $loupanInfo && true === $this->validatorArray($loupanInfo))) {
                    $xinloupan = $this->getXinloupanDataByCity($cityConfig, 0, $limit);
                    list($loupanList, $xiaoquIds) = $this->formataXinloupanData($xinloupan['list']);
                    $xiaoquInfo = $this->getXiaoquInfoByIds($xiaoquIds);
                    $loupanInfo = $this->mergeXiaoquXinloupan($loupanList, $xiaoquInfo, $unsetId);
                    $this->writeCacheData($cacheKey, $loupanInfo);
                }
            }
            $data = array(
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $loupanInfo,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getXiaoquHuxingPhoto{{{*/
    /**
     * @brief 获取小区户型图
     * @param $xiaoquId int
     * @return array()
     */
    protected function getXiaoquHuxingPhoto($xiaoquId){
        $huxingPhoto = array();
        if ((int)$xiaoquId > 0) {
            $xiaoquObj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Photo');
            $data = $xiaoquObj->getXiaoquHuxingPhoto($xiaoquId);
            if ('0' === $data['errorno']) {
                $huxingPhoto = $data['data']['items'];
            }
        }
        return $huxingPhoto;
    }//}}}
    /** {{{ getXinloupanHuxingPriceByXiaoquId*/
    /**
     * @brief  通过xiaoquId获取该小区所有的户型及总价信息
     * @param $xiaoquId int
     * @return array()
     */
    public function getXinloupanHuxingPriceByXiaoquId($xiaoquId){
        try {
            $cacheKey = 'fang_platform_xinloupan_huxinglist_info_by_xiaoquid_' . $xiaoquId;
            $huxingList = $this->readCacheData($cacheKey);
            if (!(FALSE !== $huxingList && true === $this->validatorArray($huxingList))) {
                $info = $this->getXinloupanDataByXiaoquId($xiaoquId);
                if (!empty($info['huxing_price'])) {
                    $huxingList = $this->getAndformataXiaoquHuxingPhoto($xiaoquId, $info['huxing_price']);
                }
                $this->writeCacheData($cacheKey, $huxingList);
            }
            $data = array(
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $huxingList,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /**getAndformataXiaoquHuxingPhoto{{{*/
    /**
     * @brief 获取并格式化户型价格信息
     * @param $xiaoquId int
     * @param $huxingInfo array()
     * @return array()
     */
    protected function getAndformataXiaoquHuxingPhoto($xiaoquId, $huxingInfo){
        $huxingList = array();
        if ((int)$xiaoquId > 0 && true === $this->validatorArray($huxingInfo)) {
            $info = $this->getXiaoquHuxingPhoto($xiaoquId);
            if (true === $this->validatorArray($info)) {
                foreach ($info as $item) {
                    if (isset($huxingInfo[$item['title']]) && !empty($huxingInfo[$item['title']])) {
                        $item['price'] = $huxingInfo[$item['title']];
                        $huxingList[$item['title']] = $item;
                    }
                }
            }
        }
        return $huxingList;
    }//}}}
    /**checkCityValid{{{*/
    public function checkCityValid($city){
        $cityConfig = $this->getXinloupanCityConfig();
        if (!isset($cityConfig[$city])) {
            throw new Exception();
        }
        return $cityConfig[$city];
    }//}}}
    /**getXiaoquXiaoquFieldsForList{{{*/
    protected function getXiaoquXiaoquFieldsForList(){
        return array('id', 'pinyin', 'city', 'name', 'latlng', 'thumb_image', 'district_id', 'street_id', 'address', 'developer');

    }//}}}
    /**validatorArray{{{*/
    protected function validatorArray($data){
        $ret = false;
        if (is_array($data) && count($data) >0) {
            $ret = true;
        }
        return $ret;
    }//}}}
    // {{{ just for test
    /**
     * @brief 
     * @param $name
     * @param $value
     * @codeCoverageIgnore
     */
    public function __set($name, $value) {
        if (Gj_LayerProxy::$is_ut === true) {
            $this->$name = $value;
        }
    }
    /**
     * @brief 
     * @param $func
     * @param $args
     * @codeCoverageIgnore
     */
    public function __call($func, $args) {
        if (Gj_LayerProxy::$is_ut === true) {
            switch (count($args)) {
                case 1:
                    return $this->$func($args[0]);
                case 2:
                    return $this->$func($args[0], $args[1]);
                default :
                    return $this->$func();
            }
        }
    }
    //}}}
}
