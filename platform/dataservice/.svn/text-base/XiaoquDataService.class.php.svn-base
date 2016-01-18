<?php

class XiaoquDataService {
    
    /* getXiaoquSellPriceTrend 小区二手房均价走势 {{{ */ 
    public function getXiaoquSellPriceTrend($geoParam, $dateBEArr) {
         try {
            $model = PlatformSingleton::getInstance('HouseSellAvgPriceXiaoquModel');
            $items = $model->getXiaoquSellPriceTrend($geoParam, $dateBEArr);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$items),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}}
    /*【旧接口，等待wap和app修改】 getXiaoquList 获取小区列表 {{{ */
    /**
    * 获取小区列表 
    * @param  array  $listParam  
    * @param  aray  $pagerParam 
    * @return array           
    */
    public function getXiaoquList($listParam, $pagerParam){
        try {
            $xiaoquModel = PlatformSingleton::getInstance('XiaoquXapianModel');
            $searchIndex = $xiaoquModel->preGetXiaoquList($listParam, $pagerParam);
            list($postList, $postCount) = $xiaoquModel->getSearchResult($searchIndex);
            if(!$postList) $postList = array();
            if(!$postCount) $postCount = 0;
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => array(
                                'items' => $postList,
                                'total' => $postCount
                            )
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
        
    } //}}} 
    /* preGetXiaoquList 获取小区列表 {{{ */
    /**
    * 获取小区列表 
    * @param  array  $listParam  
    * @param  aray  $pagerParam 
    * @return array           
    */
    public function preGetXiaoquList($listParam, $pagerParam){
        try {
            $xiaoquModel = PlatformSingleton::getInstance('XiaoquXapianModel');
            $searchIndex = $xiaoquModel->preGetXiaoquList($listParam, $pagerParam);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => $searchIndex,
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
        
    } //}}} 
    /* preGetHotXiaoquList 预查询热门小区二手房均价涨幅 {{{ */
    /**
    * 预查小区列表 
    * @param  array  $listParam  
    * @return array           
    */
    public function preGetHotXiaoquList($listParam){
        try {
            $xiaoquModel = PlatformSingleton::getInstance('XiaoquXapianModel');
            $searchIndex = $xiaoquModel->preGetHotXiaoquList($listParam);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => $searchIndex,
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
        
    } //}}}
    /* 【旧接口,wap,app在用】 getXiaoquHouseList 提供小区房源(二手房和租房）{{{ */
    /**
    * 提供小区房源(二手房和租房） 
    * @param  array  $listParam  
    * @param  aray  $pagerParam 
    * @return array           
    */
    public function getXiaoquHouseList($listParam, $pagerParam){
        try {            
            $xiaoquModel = PlatformSingleton::getInstance('XiaoquHouseXapianModel');
            $searchIndex = $xiaoquModel->preGetXiaoquHouseList($listParam, $pagerParam);
            list($postList, $postCount) = $xiaoquModel->getSearchResult($searchIndex);
            if(!$postList) $postList = array();
            if(!$postCount) $postCount = 0;
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => array(
                                'items' => $postList,
                                'total' => $postCount
                            )
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
        
    } 
    //}}} 
    /*  preGetXiaoquHouseList 提供小区房源(二手房和租房）{{{ */
    /**
    * 提供小区房源(二手房和租房） 
    * @param  array  $listParam  
    * @param  aray  $pagerParam 
    * @return array           
    */
    public function preGetXiaoquHouseList($listParam, $pagerParam){
        try {            
            $xiaoquModel = PlatformSingleton::getInstance('XiaoquHouseXapianModel');
            $searchIndex = $xiaoquModel->preGetXiaoquHouseList($listParam, $pagerParam);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => $searchIndex,
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
        
    } //}}} 
     /* getSearchResult 根据searchIndex获取xapian结果 {{{ */
    /** 
     * 根据searchIndex获取xapian结果
     * @param int $searchIndex
     * @return array()
     */
    public function getSearchResult($searchIndex) {
        try {            
            $baseModel = PlatformSingleton::getInstance('BaseXapianModel');              
            list($postList, $postCount) = $baseModel->getSearchResult($searchIndex);
            if(!$postList) $postList = array();
            if(!postCount) $postCount = 0;
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'    => array(
                                'items' => $postList,
                                'total' => $postCount
                            )
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;                    
    }//}}}
    /* getXiaoquRelationshipByXiaoquId 获取小区云图/周边小区 {{{ */
    /** 
     * @param int $xiaoquId  小区id
     * @param int $type  1.代表基于小区属性进行推荐  2.代表调用看了还看了推荐接口
     * @return array()
     */
    public function getXiaoquRelationshipByXiaoquId($xiaoquId, $type = null) {
        try {
            $relaModel = PlatformSingleton::getInstance('XiaoquRelationshipXiaoquModel');
            $relaInfo = $relaModel->getXiaoquRelationshipByXiaoquId($xiaoquId, $type);
            $data = array();
            if (!empty($relaInfo) && is_array($relaInfo)) {
                foreach ($relaInfo as $key => &$item) {
                    $item['similar_xiaoqu'] = json_decode($item['similar_xiaoqu'], true);
                    if (!empty($item['similar_xiaoqu']) && is_array($item['similar_xiaoqu'])) {
                        foreach ($item['similar_xiaoqu'] as $id => $val) {
                            $ids[] = $id;
                        }
                    }
                }
                $fields = array('id','name','pinyin','city','avg_price','thumb_image');
                //从小区表中获取数据
                $xqModel = PlatformSingleton::getInstance('XiaoquXiaoquXiaoquModel');
                $xqInfoItems = $xqModel->getXiaoquInfoByIds($ids, $fields);
                //从小区stat表中获取数据
                $statModel = PlatformSingleton::getInstance('XiaoquStatXiaoquModel');
                $fields = array('xiaoqu_id', 'avg_price', 'rent_cnt', 'sell_cnt', 'share_cnt');
                $statItems = $statModel->getXiaoquStatInfoByXiaoquId($ids, $fields);
                foreach ($statItems as $val) {
                    $avgInfo[$val['xiaoqu_id']] = $val;
                }
                foreach ($xqInfoItems as $xqVal) {
                    if (isset($avgInfo[$xqVal['id']])) {
                        $info = array_merge($xqVal, $avgInfo[$xqVal['id']]);
                        unset($info['id']);
                    }
                    $tmpXqInfo[$info['xiaoqu_id']] = $info;
                }
                foreach ($relaInfo as $key => $item) {
                    if (!empty($item['similar_xiaoqu']) && is_array($item['similar_xiaoqu'])) {
                        foreach ($item['similar_xiaoqu'] as $id => &$val) {
                            if (isset($tmpXqInfo[$id])) {
                                $val = array_merge($val, $tmpXqInfo[$id]);
                            }
                        }
                    }
                    $newRelaInfo[$item['type']] = $item;
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data'     => $newRelaInfo
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;               
    }//}}}
    /* getXiaoquInfoByIds 根据xiaoquId批量获取小区信息 {{{ */
    /** 
     * 根据xiaoquId批量获取小区信息
     * @param array $xiaoquIds  小区id
     * @param array $queryFields  查询字段
     * @return array()
     */
    public function getXiaoquInfoByIds($xiaoquIds, $queryFields, $needsAvgPrice=false){
        try {
            $xqModel = PlatformSingleton::getInstance('XiaoquXiaoquXiaoquModel');
            $xqInfoItems = $xqModel->getXiaoquInfoByIds($xiaoquIds, $queryFields);
            if($needsAvgPrice == true && isset($xqInfoItems[0]['id']) && $xqInfoItems[0]['id']>0) {
                $statObj = PlatformSingleton::getInstance('XiaoquStatXiaoquModel');
                $fields = array('xiaoqu_id', 'avg_price', 'avg_price_change');
                $statInfoItems = $statObj->getXiaoquStatInfoByXiaoquId($xiaoquIds, $fields);
                foreach($xqInfoItems as &$xqInfo) {
                    if(!isset($xqInfo['id'])) break;
                    foreach($statInfoItems as $statInfo) {
                        if($xqInfo['id'] == $statInfo['xiaoqu_id']) {
                            $xqInfo['avg_price'] = $statInfo['avg_price'];
                            $xqInfo['avg_price_change'] = $statInfo['avg_price_change'];
                            break;
                        }
                    }
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



}
