<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @author               $Author:   zhangrong <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * 
 */
class Service_Data_SourceList_HouseList
{
    protected $searchMiddleArr = array();

    /* {{{preSearch*/
    /**
     * @brief 
     *
     * @param $groupConfigArr array 请求的分组配置 like:
     *     array(
     *         $group =>  // 块类型，取值参见HousingVars::$validListType
     *              array(
     *                  'count' => 5, // 条数
     *                  'fromCache' => false // 是否从缓存取
     *              ),
     *         ...
     *     )
     *     $queryConfigArr array 请求的条件 like:
     *     array (
     *         'queryField' => $queryFieldArr, // 查询结果集字段集
     *         'queryFilter' => $queryFilterArr, // 查询条件, key使用下划线风格，例如:major_category_script_index, city_code, street_list
     *         'orderField' => $orderFieldArr, // 排序条件
     *     )
     *
     * @returns resultIndex 请求的编号 int 
     */

    public function preSearch($groupConfigArr, $queryConfigArr){
        try {
            // {{{param check
            if (!is_array($groupConfigArr) || empty($groupConfigArr)
                || !is_array($queryConfigArr) || empty($queryConfigArr)
                || !isset($queryConfigArr['queryFilter']) 
                || !isset($queryConfigArr['queryFilter']['major_category_script_index'])
                || (!isset($queryConfigArr['queryFilter']['city_code']) && !isset($queryConfigArr['queryFilter']['city_domain']))
                || !isset($queryConfigArr['queryFilter']['page_no'])
            ) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            //}}}
            if (!isset($queryConfigArr['queryFilter']['city_code']) || !isset($queryConfigArr['queryFilter']['city_domain'])) {
                $cityInfo = $this->getCityCodeAndDomain($queryConfigArr['queryFilter']);
                $queryConfigArr['queryFilter']['city_code'] = $cityInfo[0];
                $queryConfigArr['queryFilter']['city_domain'] = $cityInfo[1];
            }
            $searchIdArr = array();
            foreach ($groupConfigArr as $group => $config) {
                if (!in_array($group, HousingVars::$validListType) 
                    || !is_array($config) || !isset($config['count'])
                ) { 
                    throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
                }
                $_queryConfigArr = $this->queryConfigArrFormat($queryConfigArr,$group, $config);
                $subDataService = $this->getSubDataService($_queryConfigArr['queryFilter']['major_category_script_index'], 
                    $group, $_queryConfigArr['queryFilter']);

                $searchIdArr[$group] = $subDataService->preSearch($group, $config['count'], $_queryConfigArr); 
            }
            $this->searchMiddleArr[] = array('groupConfig' => $groupConfigArr, 'queryConfig' => $_queryConfigArr, 'searchIds' => $searchIdArr);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => count($this->searchMiddleArr)-1,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
        return $data;
    }//}}}
    /* {{{queryConfigArrFormat*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @param $group
     * @param $count
     *
     * @returns   
     */
    protected function queryConfigArrFormat($queryConfigArr,$group,$config){
        $count = $config['count'];
        if($queryConfigArr['queryFilter']['agent']  == ''){
            $queryConfigArr['queryFilter']['agent'] = 3;
        }
        $majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$queryConfigArr['queryFilter']['major_category_script_index']];
        if(in_array($queryConfigArr['queryFilter']['major_category_script_index'] , array(1, 3))
            && isset( HousingVars::$MIXED_CATEGORY_ID[ $majorCategoryId ])
            && $group == HousingVars::MAIN_BLOCK_LIST
            && !isset($queryConfigArr['queryFilter']['premier_common_num'])
        ){
            if($queryConfigArr['queryFilter']['agent'] == 3){
                $postListPageInfo    = HousingVars::getPostListPageInfo($queryConfigArr['queryFilter']['city_domain']);
                $preimerNum = ceil($postListPageInfo['premier_post_num']/($postListPageInfo['premier_post_num']+$postListPageInfo['person_post_num'])*$count);
                $personNum = ceil($postListPageInfo['person_post_num']/($postListPageInfo['premier_post_num']+$postListPageInfo['person_post_num'])*$count);
                $queryConfigArr['queryFilter']['premier_common_num'] = array($preimerNum, $personNum);
            } elseif($queryConfigArr['queryFilter']['agent'] == 2) {
                $queryConfigArr['queryFilter']['premier_common_num'] = array($count, 0);
            }
        }
       /* 
        $queryConfigArr['queryFilter']['list_type'] = 'ditie';
        $queryConfigArr['queryFilter']['subway_line'] = 11;
        $queryConfigArr['queryFilter']['station'] =4; 
        */
        //地铁 公交 大学
        $queryConfigArr['queryFilter'] = $this->dealSubwayBusCollogeFilter($queryConfigArr['queryFilter']);
        //处理自定义排序字段字段
        if(isset($config['orderField']) && !empty($config['orderField'])){
            $queryConfigArr['_orderField'][$group] = $config['orderField'];
        }
        return $queryConfigArr;
    }//}}}
    /* {{{ getSearchResultSync */
    /**
     *  根据传参直接同步获取Xapian房源列表信息
     * 参数见上
     */
    public function getSearchResultSync($groupConfigArr, $queryConfigArr){
        $data = $this->preSearch($groupConfigArr, $queryConfigArr);
        if($data['errorno'] == 0){ 
            $data = $this->getSearchResult($data['data']); 
        }
        return $data;
    } /* }}} */
    /* {{{getJingjiaList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function getJingjiaList($queryConfigArr){
        try {
            if (!is_array($queryConfigArr) || empty($queryConfigArr)
                || !isset($queryConfigArr['queryFilter'])  || !isset($queryConfigArr['queryFilter']['major_category_script_index'])
                || (!isset($queryConfigArr['queryFilter']['city_code']) && !isset($queryConfigArr['queryFilter']['city_domain']))
            ) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            if (!isset($queryConfigArr['queryFilter']['city_code']) || !isset($queryConfigArr['queryFilter']['city_domain'])) {
                $cityInfo = $this->getCityCodeAndDomain($queryConfigArr['queryFilter']);
                $queryConfigArr['queryFilter']['city_code'] = $cityInfo[0];
                $queryConfigArr['queryFilter']['city_domain'] = $cityInfo[1];
            }
            if(!isset($queryConfigArr['queryFilter']['source']) || !isset($queryConfigArr['queryFilter']['uuid'])){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG."source or uuid 不能为空", ErrorConst::E_PARAM_INVALID_CODE);
            }
            $queryConfigArr['queryFilter']['self_major_category'] = $queryConfigArr['queryFilter']['major_category_script_index'];
            $subDataService = $this->getSubDataService($queryConfigArr['queryFilter']['major_category_script_index'], true, $queryConfigArr['queryFilter']);
            $usePostListInfo = false;
            $source = $queryConfigArr['queryFilter']['source'];
            $uuid = $queryConfigArr['queryFilter']['uuid'];
            $result = SelfBiddingCache::getSelfBiddingPosts($subDataService->getFilterList($queryConfigArr),$usePostListInfo,$source, $uuid);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
        return $data;
    }//}}}
    /* {{{getSelfDirectionList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @param $postList
     *
     * @returns   
     */
    public function getSelfDirectionList($queryConfigArr, $postList=array()){
        try {
            if (!is_array($queryConfigArr) || empty($queryConfigArr)
                || !isset($queryConfigArr['queryFilter'])  || !isset($queryConfigArr['queryFilter']['major_category_script_index'])
                || (!isset($queryConfigArr['queryFilter']['city_code']) && !isset($queryConfigArr['queryFilter']['city_domain']))
                || !isset($queryConfigArr['queryFilter']['page_no'])
            ) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            if (!isset($queryConfigArr['queryFilter']['city_code']) || !isset($queryConfigArr['queryFilter']['city_domain'])) {
                $cityInfo = $this->getCityCodeAndDomain($queryConfigArr['queryFilter']);
                $queryConfigArr['queryFilter']['city_code'] = $cityInfo[0];
                $queryConfigArr['queryFilter']['city_domain'] = $cityInfo[1];
            }
            $subDataService = $this->getSubDataService($queryConfigArr['queryFilter']['major_category_script_index'], true, $queryConfigArr['queryFilter']);
            $selfDirectionDataService = Gj_LayerProxy::getProxy('Service_Data_Self_Direction');
            $result = $selfDirectionDataService->getList($postList, $queryConfigArr, $subDataService->getBuilder($queryConfigArr));
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage()
            );
        }
        return $data;
    }//}}}
    /* {{{getSearchResult*/
    /**
     * @brief 
     *
     * @param $resultIndex
     *
     * @returns array()  
     */
    public function getSearchResult($resultIndex){
        try {
            if ($resultIndex >= count($this->searchMiddleArr)
                || !isset($this->searchMiddleArr[$resultIndex]['queryConfig']) 
                || !isset($this->searchMiddleArr[$resultIndex]['queryConfig']['queryFilter'])
            ) {
                throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
            }
            $queryFilterArr = $this->searchMiddleArr[$resultIndex]['queryConfig']['queryFilter'];
            $finalResultArr = array();
            if (is_array($this->searchMiddleArr[$resultIndex]['searchIds'])
                && !empty($this->searchMiddleArr[$resultIndex]['searchIds'])
            ) {
                foreach ($this->searchMiddleArr[$resultIndex]['searchIds'] as $group => $searchId) {
                    if (!in_array($group, HousingVars::$validListType)) {
                        throw new Exception(ErrorConst::E_INNER_FAILED_MSG, ErrorConst::E_INNER_FAILED_CODE);
                    }
                    $subDataService = $this->getSubDataService($queryFilterArr['major_category_script_index'], $group, $queryFilterArr);
                    $resultArr = $subDataService->getSearchResult($searchId, array('group' => $group, 'queryFilter' => $queryFilterArr,'groupFilter'=>$this->searchMiddleArr[$resultIndex]['queryConfig']['groupFilter']));
                    if (HousingVars::MAIN_BLOCK_LIST == $group && true == $subDataService->isPremier()
                        && !isset($queryFilterArr['premier_only'])
                    ) {
                        $resultArr = $this->addMoreSearchResult($resultIndex, $group, $resultArr);
                    }
                    $finalResultArr[$group] = $resultArr;
                }
            }
            $finalResultArr = $this->getPostAccountInfo($finalResultArr);
            $result = $this->removeForbiddenWords($finalResultArr);
            if (1 == $_GET['a']) {
                var_dump($result[HousingVars::MAIN_BLOCK_LIST]);
            }
            //{{{
            foreach ($result[0] as $idx => $item) {
                if (!isset($item['puid']) || 0 == $item['puid'] || empty($item['item'])) {
                    unset($result[0][$idx]);
                }
            }
            //}}}
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => $result,
            );
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(), 
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}}
    /* {{{addMoreSearchResult*/
    /**
     * @brief 
     *
     * @param $resultIndex
     * @param $group
     * @param $resultArr
     *
     * @returns   
     */
    protected function addMoreSearchResult($resultIndex, $group, $resultArr){
        try {
            $categoryScriptIndex = $this->searchMiddleArr[$resultIndex]['queryConfig']['queryFilter']['major_category_script_index']; 
            $isGetExactTotal = $this->isGetExactTotal($resultIndex, $group);//是否只获取准确的总数
            if ((count($resultArr[0]) < $this->searchMiddleArr[$resultIndex]['groupConfig'][$group]['count'] && !$isGetExactTotal)
                 || $this->isGroupBy($resultIndex) //分组时要补普通帖的数据
                ) { 
                // 需要补贴
                if (in_array($categoryScriptIndex, array(6, 7, 8, 9, 11))) {
                    $extraArr = $this->getMoreCommonSearchResult($resultIndex, $resultArr);
                } else if (in_array($categoryScriptIndex, array(1,3))) {
                    $extraArr = $this->getMoreMixSearchResult($resultIndex, $resultArr);
                }
            } else {
                // 无需补贴，去取确切的普通帖总数         
                $queryConfigArr = $this->searchMiddleArr[$resultIndex]['queryConfig'];
                $queryConfigArr = $this->queryConfigArrFormat($queryConfigArr, $group, $this->searchMiddleArr[$resultIndex]['groupConfig'][$group]);
                $subDataService = $this->getSubDataService($categoryScriptIndex, true, null);
                $exactCommonCount = $subDataService->getExactPostTotalCount(HousingVars::MAIN_BLOCK_LIST, $queryConfigArr);
                $extraArr = array(array(), $exactCommonCount);
            }
            if (is_array($extraArr) && !empty($extraArr)) {
                $extraArr = json_decode(json_encode($extraArr),true);//为下边的数组相加取并集统一key的数据类型（都为数字索引);
                //分组时需要将端口贴和补充好的普通贴的数量求和
                if($this->isGroupBy($resultIndex)){
                    $newResult = array();
                    foreach($resultArr[0] as $k => $v){
                        $newResult[$k] = $v + $extraArr[0][$k]; 
                    }
                    $newResult = $newResult+(array)$extraArr[0];//防止在$extraArr里但不在$resultArr里的key漏掉
                    $returnArr = array( $newResult, $resultArr[1]+$extraArr[1]);
                } else {
                    $returnArr = $this->mergePostList($categoryScriptIndex, $resultArr, $extraArr);
                }
                return $returnArr;
            } else {
                return $resultArr;
            }
        } catch (Exception $e) {
            return $resultArr;
        }
    }//}}}
    /*{{{ getMoreMixSearchResult*/
     protected function getMoreMixSearchResult($resultIndex, $premierResultArr){
         $premierTotalNum = $premierResultArr[1];
         $count = $this->searchMiddleArr[$resultIndex]['groupConfig'][HousingVars::MAIN_BLOCK_LIST]['count'];
         $queryConfigArr = $this->searchMiddleArr[$resultIndex]['queryConfig'];
         $queryConfigArr = $this->queryConfigArrFormat($queryConfigArr,HousingVars::MAIN_BLOCK_LIST, $this->searchMiddleArr[$resultIndex]['groupConfig'][HousingVars::MAIN_BLOCK_LIST]);

         $queryFilterArr = $queryConfigArr['queryFilter'];
         $premierSize = $queryFilterArr['premier_common_num'][0];
         $commonSize = $queryFilterArr['premier_common_num'][1];
         $subDataService = $this->getSubDataService($queryFilterArr['major_category_script_index'], true, null);
         if($this->isGroupBy($resultIndex)){
             $extraArr = $subDataService->getExactCommonPost(HousingVars::MAIN_BLOCK_LIST, $count, $queryConfigArr);
             return $extraArr;
         }
         if($commonSize > 0){
             //N天内主站个人贴
             $NDayMsPersonQueryConfigArr = $subDataService->getQuerySetting($queryConfigArr, 'NDayMsPerson');
             $NDayMsPersonQueryConfigArr['queryFilter']['offset_limit'] = array(($queryFilterArr['page_no'] - 1) * $commonSize, $count); 
             $NDayMsPersonSearchId = $subDataService->preSearch(HousingVars::MAIN_BLOCK_LIST, $count, $NDayMsPersonQueryConfigArr);
             $NDayMsPersonPostArr = $subDataService->getSearchResult($NDayMsPersonSearchId, 
                 array('group' => HousingVars::MAIN_BLOCK_LIST, 'queryFilter' =>$NDayMsPersonQueryConfigArr['queryFilter']));
             $NDayMsPersonTotalNum = $NDayMsPersonPostArr[1];
             //n天内个人免费贴过多，启用JIRA中FANG-700的混排算法
             if(isset($premierSize) && $premierSize > $premierTotalNum
                 || $NDayMsPersonTotalNum > (floor($premierTotalNum / $premierSize) * $commonSize + $commonSize + $premierSize - $premierTotalNum % $premierSize )
             ){
                 $willMixPostList = array(
                     'NDayMsPerson'=> $NDayMsPersonPostArr,
                     'MsAgent' => array(),
                     'NDayAgoMsPerson'=>array(),
                 );
                 $postList = $subDataService->getMixPostList($queryConfigArr, $willMixPostList,$premierSize+$commonSize, $premierResultArr);
             } else {
                 $willMixPostList = array(
                     'MsAgent' => array(),
                     'NDayAgoMsPerson'=>array(),
                 );
                 $postList = $subDataService->getMixPostList($queryConfigArr, $willMixPostList, $premierSize, $premierResultArr);//补给$premierSize的帖子
                 $postList['NDayMsPerson'] = array(0=> array_slice((array)$NDayMsPersonPostArr[0],0, $commonSize),1=>$NDayMsPersonPostArr[1]);//补给$commonSize的帖子
             }
            
        } else { 
            $willMixPostList = array(
                'Ms' => array(),
            );
            $postList = $subDataService->getMixPostList($queryConfigArr, $willMixPostList, $premierSize, $premierResultArr);
  
        }

        return $postList; 
    }//}}}
    /*{{{ isGroupBy*/
    protected function isGroupBy($resultIndex){
        return !empty($this->searchMiddleArr[$resultIndex]['queryConfig']['groupFilter']) ? true : false;
    }/*}}}*/
    /*{{{ isGetExactTotal*/
    protected function isGetExactTotal($resultIndex, $group){
        //构成检索串里的[L:100:1],这种情况的视为取准确总数
        return $this->searchMiddleArr[$resultIndex]['groupConfig'][$group]['count'] == 1 && $this->searchMiddleArr[$resultIndex]['queryConfig']['queryFilter']['page_no'] == 101 ? true:false;
    }/*}}}*/
    /* {{{getMoreCommonSearchResult*/
    /**
     * @brief 
     *
     * @param $categoryIndex
     * @param $count
     * @param $premierPostInfo
     *
     * @returns   
     */
    protected function getMoreCommonSearchResult($resultIndex, $currentResultArr){
        try {
            $currentPageCount = count($currentResultArr[0]);
            $currentTotalCount = $currentResultArr[1];
            $count = $this->searchMiddleArr[$resultIndex]['groupConfig'][HousingVars::MAIN_BLOCK_LIST]['count'];
            $queryConfigArr = $this->searchMiddleArr[$resultIndex]['queryConfig'];
            $queryFilterArr = $queryConfigArr['queryFilter'];
            // 计算偏移量 
            $offset = ($queryFilterArr['page_no']-1) * $count - $currentTotalCount; 
            $offset = ($offset>0) ? $offset:0;
            $extraCount = $count - $currentPageCount;
            // 取补充贴
            $queryFilterArr['offset_limit'] = array($offset, $extraCount);
            $subDataService = $this->getSubDataService($queryFilterArr['major_category_script_index'], true, null);
            $queryConfigArr['queryFilter'] = $queryFilterArr;
            $searchId = $subDataService->preSearch(HousingVars::MAIN_BLOCK_LIST, null, $queryConfigArr);
            $resultArr = $subDataService->getSearchResult($searchId, array('group' => HousingVars::MAIN_BLOCK_LIST, 'queryFilter' => $queryFilterArr));
            // 取确切的帖子总数
            if ($offset < 100) {
                //$resultArr[1] = $subDataService->getExactPostTotalCount(HousingVars::MAIN_BLOCK_LIST, $queryConfigArr);
            }
            return $resultArr;
        } catch (Exception $e) {
            return array(array(), 0);
        }
    }//}}}
    /* {{{getSubDataService*/
    /**
     * @brief 
     *
     * @param $categoryScriptIndex
     * @param $group
     *
     * @returns   
     */
    protected function getSubDataService($majorCategoryScriptIndex, $group, $queryFilterArr){
        if (HousingVars::JINGJIA_LIST === $group) {
            return PlatformSingleton::getInstance('Service_Data_SourceList_Part_Jingjia');
        }
        switch ($majorCategoryScriptIndex) {
            case 1:
            case 3:
                if(true === $group ||HousingVars::STICKY_LIST == $group ||HousingVars::MAIN_SITE_LIST == $group
                     ||(HousingVars::MAIN_BLOCK_LIST == $group && 1 == $queryFilterArr['agent'])){
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_RentCommon');
                } else if(HousingVars::GONGYU_JIZHONG_LIST == $group){
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_Gongyu'); 
                }/*
                if(true === $group ||HousingVars::STICKY_LIST == $group) {
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_RentCommon');
                } elseif(!empty($queryFilterArr) && 1 == $queryFilterArr['agent'] && $group != HousingVars::TRUE_HOUSE_LIST){//中介类型为个人时
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_RentCommon');
                }*/ else {
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_RentPremier');
                }
            case 5:
                return PlatformSingleton::getInstance('Service_Data_SourceList_Part_SellPremierAndCommon');
            /*
            case 2:
            case 4:
            case 10:
                // return
            */
            case 12:
                return PlatformSingleton::getInstance('Service_Data_SourceList_Part_LoupanPremierAndCommon');
            case 6:
            case 7:
            case 8:
            case 9:
            case 11:
                if (true === $group || HousingVars::STICKY_LIST == $group || HousingVars::MAIN_SITE_LIST == $group) {
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_CommercialCommon');
                } else if (!empty($queryFilterArr) && (1 == $queryFilterArr['agent'] || in_array($queryFilterArr['deal_type'], array(2, 4)))) {
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_CommercialCommon');
                } else {
                    return PlatformSingleton::getInstance('Service_Data_SourceList_Part_CommercialPremier');
                }
            case 10:
                return PlatformSingleton::getInstance('Service_Data_SourceList_Part_Gongyu');
            default:
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
    }//}}}
    /* {{{getPostAccountInfo*/
    /**
     * @brief 
     *
     * @param $resultArr
     *
     * @returns   
     */
    protected function getPostAccountInfo($groupResultArr){
        if (!is_array($groupResultArr) || empty($groupResultArr)) { 
            return array(); 
        }
        $accountIdArr = array();
        foreach ($groupResultArr as $group => $resultArr) {
            foreach ($resultArr[0] as $index => $post) {
                if (!isset($post['account_id']) || empty($post['account_id'])) {
                    continue;
                }
                $accountIdArr[] = $post['account_id'];
            }       
        }
        // get account info
        if (!empty($accountIdArr)) { 
            $accountInfoDataService = Gj_LayerProxy::getProxy('Service_Data_Broker_Info');
            $accountInfoArr = $accountInfoDataService->getAccountInfo(array_unique($accountIdArr));
            if (false === $accountInfoArr) {
                return $groupResultArr;
            }
        }
        // fill account info
        foreach ($groupResultArr as $group => &$resultArr) {
            foreach ($resultArr[0] as $index => $post) {
                if (!isset($post['account_id']) || !isset($accountInfoArr[$post['account_id']])) {
                    continue;
                }
                $resultArr[0][$index] = array_merge($post, $accountInfoArr[$post['account_id']]);
            }
        }
        return $groupResultArr;
    }//}}}
    /* {{{getCityCodeAndDomain*/
    /**
     * @brief 
     *
     * @param $cityCodeOrDomain
     *
     * @returns   
     */
    protected function getCityCodeAndDomain($cityCodeOrDomain){
        if (isset($cityCodeOrDomain['city_code'])) {
            $cityInfo = GeoNamespace::getCityByCityCode($cityCodeOrDomain['city_code']);
        } else {
            $cityInfo = GeoNamespace::getCityByDomain($cityCodeOrDomain['city_domain']);
        }
        if (!is_array($cityInfo) || empty($cityInfo)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE); 
        }
        return array($cityInfo['city_code'], $cityInfo['domain']);
    }//}}}
    /*{{{ mergePostList */
    protected function mergePostList($majorCategoryScriptIndex, $premierPostList, $extraPostList){
        if(in_array($majorCategoryScriptIndex , array(1,3)) && !isset($extraPostList[0])){//$extraPostList[0]存在时表示extraPostList只补了总数没有补贴子
            $premier = $premierPostList[0];
            $personPostList = $extraPostList['NDayMsPerson'];
            $person = $personPostList[0];
            unset($extraPostList['NDayMsPerson']);
            $mix = array();
            $premier_len = count($premier);
            $premier_part_size = ceil($premier_len / 7);
            $premier_part = array();
            if ( 0 < $premier_len) {
                $premier_part = array_chunk($premier, $premier_part_size);
            }
            $len = count($person);
            $maxRand = ceil($premier_part_size/2) <= 2 ? 1:ceil($premier_part_size/2);//特殊处理
            for($i = 0; $i < $len;) {
                $count = rand(1, $maxRand);
                $person_part[] = array_slice($person, $i, $count);
                $i += $count;
            }
            $count = max(7, count($person_part));
            for ($i = 0; $i < $count; $i++) {
                $j = $k = 0;
                while ( isset($person_part[$i][$j]) || isset($premier_part[$i][$k])) {
                    if ( ! isset($person_part[$i][$j]) || $premier_part[$i][$k]['refresh_at'] / 60 > ($person_part[$i][$j]['post_at'] / 60 + 20)) {
                        $mix[] = $premier_part[$i][$k++];
                    } else {
                        $tmp = $person_part[$i][$j++];
                        $len = count($mix);
                        if (0 < $len) {
                            $post_at = isset($mix[$len - 1]['house_id']) ? $mix[$len - 1]['refresh_at'] : $mix[$len - 1]['post_at'];
                            $tmp['post_at'] = $post_at;
                        }
                        $mix[] = $tmp;
                    }
                }
            }
            $premierPersonPostList[0]= $mix;
            $premierPersonPostList[1]= $premierPostList[1] + $personPostList[1];
            $_mixPostList = array();
            foreach((array)$extraPostList as $k=>$v){
                $_mixPostList[0] = array_merge((array)$_mixPostList[0], (array)$v[0]);
                $_mixPostList[1] = $_mixPostList[1]+$v[1];
            }
           
            //$a = array(array_merge((array)$premierPostList[0], (array)$_mixPostList[0]), $premierPostList[1] + $_mixPostList[1]);
            return array(array_merge((array)$premierPersonPostList[0], (array)$_mixPostList[0]), $premierPersonPostList[1] + $_mixPostList[1]);
        } else {
            return array(array_merge((array)$premierPostList[0], $extraPostList[0]), $premierPostList[1] + $extraPostList[1]);
        }
    }/*}}}*/
    /*{{{ dealSubwayBusCollogeFilter */
    protected function dealSubwayBusCollogeFilter($queryFilterArr){
        switch($queryFilterArr['list_type']){
            case 'ditie':
                $search_distance_pre = 'subwaydistance';
                $search_line = $search_type = 'subway_line';
                $method = 'getSubwayStationInfoByLineIdAndStationNumber';
                break;
            case 'bus' :
                $search_line = $search_type = 'bus_line';
                $method = 'getBusStationInfoByLineIdAndStationNumber';
                break;
            case 'daxue':
                $search_distance_pre = 'collegedistance';
                $search_line = 'college_line';
                $search_type = 'college';
                $method = 'getCollegeInfoByCityIdAndCollegeId';
                break;
            default:
                $search_line = $search_type = '';
                $method = '';
        }
        
        if(!isset($queryFilterArr['latlng']) && $search_type != ''){
            $latlng = $this->getLatLng($queryFilterArr, $search_type, $method);
            if(!empty($latlng)){
                 $queryFilterArr['latlng'] = $latlng;
                 unset($queryFilterArr[$search_type]);
            }
        }

        if(empty($queryFilterArr['latlng']) && $search_type != ''){
            if($queryFilterArr[$search_type]){
                $queryFilterArr['textFilter'][0][$search_line] = $queryFilterArr[$search_type]; 
                unset($queryFilterArr[$search_type]);
            } else {
                $queryFilterArr['textFilter'][0][$search_line] =  $queryFilterArr['textFilter'][0][$search_line] ? $queryFilterArr['textFilter'][0][$search_line] : 'all';
            }
            //生成检索条件形如：[T:subwaydistance_35:3000]
            if($queryFilterArr['walk'] && isset($search_distance_pre)){
                $search_distance = $queryFilterArr['textFilter'][0][$search_line] == 'all' ? $search_distance_pre : $search_distance_pre.'_'.$queryFilterArr['textFilter'][0][$search_line];
                $queryFilterArr['textFilter'][0][$search_distance] = HousingVars::$WALK_TIME_VALUES[$queryFilterArr['walk']] ;
            }
        }

        return $queryFilterArr;
    }/*}}}*/
    /*{{{ getLatLng */
    protected function getLatLng($filterConfigArr, $search_type, $method){
        $client = Gj_LayerProxy::getProxy('Dao_Location_BusSubwayCollege');
        //$client = $this->objBusSubwayCollegeNamespce;
        //$client = Gj_LayerProxy::getProxy('Util_Source_BusSubwayCollegeNamespace');
        $latlng='';
        if (isset($filterConfigArr['station'])) {
            $multi_subway_station = explode('_',$filterConfigArr['station']);
            if (count($multi_subway_station) > 1) {
                $i = 0;
                foreach ($multi_subway_station as $v) {
                    $station_info[$i] = $client->$method($filterConfigArr['city_code'], $filterConfigArr[$search_type], $v);
                    if ($station_info) {
                        $distance = isset($filterConfigArr['walk']) ? HousingVars::$WALK_TIME_VALUES[$filterConfigArr['walk']] * 0.8 : 3000 * 0.8;
                        $ret[] = BusSubwayCollegeNamespce::getLatLngRange2($station_info[$i]['lat'], $station_info[$i]['lng'], $distance);
                    }
                    $i++;
                }       
                $latlng = $ret;
            } else {
                $station = $client->$method($filterConfigArr['city_code'], $filterConfigArr[$search_type], $filterConfigArr['station']);
                if ($station ) {
                    //由于是画方算法，乘以一个系数
                    $distance = isset($filterConfigArr['walk']) ? HousingVars::$WALK_TIME_VALUES[$filterConfigArr['walk']] * 0.8 : 3000 * 0.8;
                    $latlng = BusSubwayCollegeNamespce::getLatLngRange2($station['lat'], $station['lng'], $distance);
                }   
            } 
        } else {
            if($search_type == 'college'){
                //college
                $college = $client->$method($filterConfigArr['city_code'], $filterConfigArr[$search_type]);
                if ($college ) {
                    //由于是画方算法，乘以一个系数
                    $distance = isset($filterConfigArr['walk']) ? HousingVars::$WALK_TIME_VALUES[$filterConfigArr['walk']] * 0.8 : 3000 * 0.8;
                    $latlng = BusSubwayCollegeNamespce::getLatLngRange2($college['lat'], $college['lng'], $distance);
                }
            }
        }
       return $latlng;
    }/*}}}*/
    /* {{{removeForbiddenWords*/
    /**
     * @brief 房源列表中标题去除屏蔽字
     *
     * @param $groupResultArr
     *
     * @returns   
     */
    public function removeForbiddenWords($groupResultArr){
        if (!is_array($groupResultArr) || empty($groupResultArr)) { 
            return array(); 
        }
        foreach ($groupResultArr as $group => &$resultArr) {
            foreach ($resultArr[0] as $index => $post) {
                $resultArr[0][$index]['title'] = str_ireplace(HousingForbiddenWords::$WordsBox, '', $post['title']);
            }       
        }
        return $groupResultArr;
    }//}}} 
}
