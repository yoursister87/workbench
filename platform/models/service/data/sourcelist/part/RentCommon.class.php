<?php 
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_SourceList_Part_RentCommon
{

    protected $timeObj;
    public function __construct(){
        $this->timeObj = new Gj_Util_TimeMock();
    }
    // {{{ just for phpunittest
    /**
     * @brief 
     *
     * @param $name
     * @param $value
     *
     * @returns   
     * @codeCoverageIgnore
     */
    public function __set($name, $value) {
        if (Gj_LayerProxy::$is_ut === true) {
            $this->$name = $value;
        }
    }//}}}
    /* {{{isPremier*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function isPremier(){
        return false;
    }//}}}
    /* {{{preSearch*/
    /**
     * @brief 
     *
     * @param $group
     * @param $count
     * @param $queryFilterArr
     *
     * @returns   
     */
    public function preSearch($group, $count, $queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
        $queryConfigArr['orderField'] = $this->setOrderField($group, $queryConfigArr);
        $model = $this->getModelInstance();
        return $model->preSearch($queryConfigArr);
    }//}}}
    /* {{{getSearchResult*/
    /**
     * @brief 
     *
     * @param $searchId
     *
     * @returns   
     */
    public function getSearchResult($searchId, $configArr){
        $model = $this->getModelInstance();
        $result = $model->getSearchResult($searchId);
        if($configArr['groupFilter']){
            $result[0] = $result[0][0];//groupby后返回的数据多了一个维度
        } else if (isset($configArr['group']) && in_array($configArr['group'], HousingVars::$validListType)) {
            return $this->resultSpecialProcess($result, $configArr['group']);
        }
        return $result;
    }//}}}
    /* {{{getExactPostTotalCount*/
    /**
     * @brief 
     *
     * @param $group
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function getExactPostTotalCount($group, $queryConfigArr){
        $queryConfigArr['queryFilter']['get_total_count'] = true;
        $queryConfigArr['queryFilter']['offset_limit'] = array(100, 1);
        $searchId = $this->preSearch($group, 1, $queryConfigArr);
        $resultArr = $this->getSearchResult($searchId, array($group, $queryConfigArr['queryFilter']));
        return $resultArr[1];
    }//}}} 
    /* {{{getExactCommPost*/
    /**
     * @brief 
     *
     * @param $group
     * @param $queryConfigArr
     *
     * @returns   
     */
    public function getExactCommonPost($group,$count, $queryConfigArr){
        /*$queryConfigArr['queryFilter']['get_total_count'] = true;
        $queryConfigArr['queryFilter']['offset_limit'] = array(100, 1);*/
        $searchId = $this->preSearch($group, $count, $queryConfigArr);
        $resultArr = $this->getSearchResult($searchId, array($group, $queryConfigArr['queryFilter']));
        //return $resultArr[1];
        if(isset($queryConfigArr['groupFilter'])){
            $resultArr[0] = $resultArr[0][0];//groupby后返回的数据多了一个维度
        }
        return $resultArr;

    }//}}}
    /* {{{getMixQueryConfigArr*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @param $type
     *
     * @returns   
     */
    public function getMixQueryConfigArr($queryConfigArr, $type){
        $postListPageInfo    = HousingVars::getPostListPageInfo($queryConfigArr['queryFilter']['city_domain']);
        $currTime = $this->timeObj->getTime();
        $mixQueryConfig = array(
            'NDayMsPerson' => array(
                    'post_at'=> array(strtotime(date('Y-m-d', $currTime)) - 86400 * $postListPageInfo['day'], $currTime),
                    'agent' =>1,
                ), 
            'MsAgent'=>array(
                    'agent' =>2,
                    'post_at'=> array(strtotime(date('Y-m-d', $currTime)) - 86400 * 90, $currTime),
                ),
            'NDayAgoMsPerson' => array(
                    'post_at' => array(strtotime(date('Y-m-d', $currTime)) - 86400 * 90, $currTime - 86400 * $postListPageInfo['day']),
                    'agent' => 1,
                ),            
            'Ms' => array(
                    //'agent'=>null,
                    'post_at'=> array(strtotime(date('Y-m-d', $currTime)) - 86400 * 90, $currTime),
                ),
        );
        return isset($mixQueryConfig[$type]) ? $mixQueryConfig[$type] : array();
    }//}}}
    /* {{{ getMixPostList */
    /**
     * 原理：初始端口贴$premierResult，依次按序补充$mixPostList规定条件的帖子到$premierResult，循环进行
     * $queryConfigArr 原始的请求参数
     * $mixPostList 用来补贴的
     * $premierSize 每页要补贴的条数
     * $premierResult 被补贴的帖子数组，如初始的端口贴
     */
    public function getMixPostList($queryConfigArr,$mixPostList,$premierSize,$premierResultArr){
        $premierResult = $premierResultArr[0];
        $premierTotalNum = $premierResultArr[1];
        //$mixQueryConfigArr = $queryConfigArr;
        foreach($mixPostList as $type => $v_result){
            $premierPageCount = count($premierResult);
            if($type == 'NDayAgoMsPerson' && isset($queryConfigArr['queryFilter']['post_at'])){//不补贴也不补总数
                $premierPageCount = $premierSize+9999999;//
                $v_result = array(array(),0);
            }
            $mixQueryConfigArr = $this->getQuerySetting($queryConfigArr, $type);
            if(empty($v_result)){
                //先默认取第一页数据，目的有2个：1可以取总数，2若恰好是补数据那页可以用来补数据
                $mixQueryConfigArr['queryFilter']['offset_limit'] = array(0, $premierSize);//第一页
                $searchId = $this->preSearch(HousingVars::MAIN_BLOCK_LIST, $premierSize, $mixQueryConfigArr);
                $result = $this->getSearchResult($searchId,
                    array('group' => HousingVars::MAIN_BLOCK_LIST, 'queryFilter'=> $mixQueryConfigArr['queryFilter']));
            } else {
                $result = $v_result;
            }      
            if(($queryConfigArr['queryFilter']['page_no'] -1)* $premierSize >= ($premierTotalNum + $result[1])){//只需要补总数
                $premierTotalNum = $premierTotalNum + $result[1];
                $mixPostList[$type] = array(0=>array(),1=>$result[1]);
                continue;
            }
            if($premierPageCount == 0){ //补全部
                $wantPremierOffset = ($queryConfigArr['queryFilter']['page_no']-1)*$premierSize;
                $wantLimit = $premierSize;
                $mixQueryConfigArr['queryFilter']['offset_limit'] = array($wantPremierOffset - $premierTotalNum, $wantLimit);
                $searchId = $this->preSearch(HousingVars::MAIN_BLOCK_LIST, $premierSize, $mixQueryConfigArr);
                $mixPostList[$type] = $this->getSearchResult($searchId,
                    array('group' => HousingVars::MAIN_BLOCK_LIST, 'queryFilter'=> $mixQueryConfigArr['queryFilter']));

            } elseif($premierPageCount > 0 && $premierPageCount < $premierSize) { //不足一页时，补一部分
                $wantLimit = $premierSize - $premierPageCount;
                $mixPostList[$type] = array(0=>array_slice((array)$result[0], 0, $wantLimit), 1=>$result[1]);
            } else {//只补总数
                $mixPostList[$type] = array(0=>array(),1=>$result[1]);
            }
            
            $premierResult = array_merge($premierResult, $mixPostList[$type][0]); 
        
        }
        return $mixPostList;
    }
    /* }}}*/
    /*{{{ getQuerySetting */
    public function getQuerySetting ($queryConfigArr, $type){
        $configArr = $this->getMixQueryConfigArr($queryConfigArr['queryFilter'], $type);
        foreach($configArr as $k => $v){
            if($k == 'post_at' && isset($queryConfigArr['queryFilter'][$k] )) {
                continue;
            } else {
                $queryConfigArr['queryFilter'][$k] = $v;
            }
        }
        return $queryConfigArr;
    }/*}}}*/
    /*{{{ getNDaysAgoCommonAgentPostList */
    public function getNDaysAgoCommonAgentPostList($queryConfigArr, $premierResultArr){
        $nDaysAgoCommonQueryConfigArr = $this->getNDaysAgoCommonQueryConfigArr($queryConfigArr);
        $nDaysAgoCommonSearchId = $this->preSearch(HousingVars::MAIN_BLOCK_LIST, $count, $nDaysAgoCommonQueryConfigArr);
        $nDaysAgoCommonPostArr = $this->getSearchResult($nDaysAgoCommonSearchId, array('group' => HousingVars::MAIN_BLOCK_LIST,                              'queryFilter'=>$queryFilterArr));
    }/*}}}*/
    /* {{{formatQueryFilter*/
    /**
     * @brief 
     *
     * @param $group
     * @param $count
     * @param $queryFilterArr
     *
     * @returns   
     */
    protected function formatQueryFilter($group, $count, $queryFilterArr){
        if($queryFilterArr['major_category_script_index'] == 3){
            $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];
        }
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setOffset($group, $count, $queryFilterArr);
        //$queryFilterArr = $this->setPrice($queryFilterArr);
        //$queryFilterArr = $this->setArea($queryFilterArr);
        $queryFilterArr = $this->setPostAt($group, $queryFilterArr);
        $queryFilterArr = $this->setZufangType($queryFilterArr);
        //$queryFilterArr = $this->setGroupBy($queryFilterArr);
        return $queryFilterArr;
    }//}}}
    /*{{{setDisplay*/
    protected function setDisplay($group, $queryFilterArr){
        /*if (true === $queryFilterArr['get_total_count']) { --------------------------
            $displayFilter = array('listing_status' => array(5, 50));
        } else */if (HousingVars::STICKY_LIST == $group) {
            $displayFilter = array('listing_status' => array(6, 50), 'post_type' => array(1, 10), );
        } else if(HousingVars::MAIN_BLOCK_LIST == $group){
            $displayFilter = array('listing_status' => array(5, 50), 'post_type' => array(0,1,10));
        } else {
            $displayFilter = array('listing_status' => array(5, 50), 'post_type' => 0); 
        }   
        $queryFilterArr = array_merge($queryFilterArr, $displayFilter); 
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setOffset*/
    protected function setOffset($group, $count, $queryFilterArr){
        // offset,limit
        if(!isset($queryFilterArr['offset_limit']) ){
            if (HousingVars::STICKY_LIST == $group && -1 == $count) {
                $queryFilterArr['offset_limit'] = array(0, 150);
            } else if($count > 0) {
                $queryFilterArr['offset_limit'] = array(($queryFilterArr['page_no'] - 1) * $count, $count);
            } 
        }
        return $queryFilterArr;
    }
    /*}}}*/
    /* {{{setZufangType*/
    /**
     * @brief 
     *
     * @param $queryFilterArr
     *
     * @returns   
     */
    protected function setZufangType($queryFilterArr){
        if (isset($queryFilterArr['zufang']) && 1 == $queryFilterArr['zufang']) {
            $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];
        }
        return $queryFilterArr;
    }//}}}
    /*{{{setPrice*/
    /*
    protected function setPrice($queryFilterArr){
        return $queryFilterArr;
    }
    */
    /*}}}*/
    /*{{{setArea*/
    /*
    protected function setArea($queryFilterArr){
        return $queryFilterArr;
    }*/
    /*}}}*/
    /*{{{setPostAt*/
    protected function setPostAt($group, $queryFilterArr){
        // post_at
        if (isset($queryFilterArr['date'])) {
            $date = HousingVars::$DATE_VALUES[$queryFilterArr['date']][1];
            $timeStart = strtotime(date('Y-m-d') . ' 00:00:00 -' . $date .' day');
            $queryFilterArr['post_at'] = array($timeStart, $_SERVER['REQUEST_TIME']);
        }
        if($group == HousingVars::MAIN_BLOCK_LIST && !isset($queryFilterArr['post_at'] )){
            $currTime = $this->timeObj->getTime();
            $queryFilterArr['post_at'] = array(strtotime(date('Y-m-d', $currTime)) - 86400 * 90, $currTime); 
        }
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{ setOrderField */
    protected function setOrderField($group, $queryConfigArr){
       
        if(!empty($queryConfigArr['orderField'])){
            return $queryConfigArr['orderField'];
        } else if(!empty($queryConfigArr['_orderField'][$group])){
            return $queryConfigArr['_orderField'][$group];//在HouseList的queryConfigArrFormat里定义
        }
        if(HousingVars::MAIN_BLOCK_LIST == $group){
            if(in_array($queryConfigArr['queryFilter']['list_type'], array('ditie','daxue','bus'))){
               $orderField =  array('listing_status'=>'Desc', 'post_at'=>'Desc');
            }
        }
        return $orderField;
    }/*}}}*/
    /* {{{getModelInstance*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getModelInstance(){
        return  Gj_LayerProxy::getProxy('Dao_Xapian_Zufang');
    }//}}}
    /* {{{resultSpecialProcess*/
    /**
     * @brief 
     *
     * @param $postList
     * @param $queryFilterArr
     *
     * @returns   
     */
    protected function resultSpecialProcess($result, $group){
        if (empty($result) || !is_array($result) || !in_array($group, HousingVars::$validListType)) {
            return $result;
        }
        $postList = $result[0];
        $count = $result[1];
        foreach ((array)$postList as $index => $post) {
            if(!is_array($post)){
                continue;
            }           
            // @codeCoverageIgnoreStart
            if (($post['post_type'] == 1 || $post['post_type'] == 10) 
                && 9 == $post['listing_status']
                && StickyNamespace::getStickyTypeByPostInfo($post['top_info'], $post['listing_status'], $post['post_type']) 
            ) {
                if (HousingVars::STICKY_LIST != $group) {
                    unset($postList[$index]);
                    --$count;
                }
            } 
            //@codeCoverageIgnoreEnd  
            else {
                if (HousingVars::STICKY_LIST == $group) {
                    unset($postList[$index]);
                    --$count;
                } else if(HousingVars::MAIN_BLOCK_LIST == $group){//不在置顶地区的listing_status>=9的帖子将来放到MainBlock最后显示FANG-7936
                    $fakeStickyOfmainBlock[] = $postList[$index];
                    unset($postList[$index]); 
                }
            }
        }
        
        if(!empty($fakeStickyOfmainBlock)){
            $postList = array_merge((array)$postList, (array)$fakeStickyOfmainBlock);
        }
        return array($postList, $count);
    }//}}}
    /*{{{ getFilterList */
    public function getFilterList($queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
        $queryConfigArr['queryFilter']['major_category'] = $queryConfigArr['queryFilter']['major_category_script_index'];
        $model = $this->getModelInstance();
        return $model->getFilterList($queryConfigArr);
    }/*}}}*/
    /*{{{ getBuilder */
    public function getBuilder($queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
        $queryConfigArr['queryFilter']['major_category'] = $queryConfigArr['queryFilter']['major_category_script_index'];
        $model = $this->getModelInstance();
        return $model->getBuilder($queryConfigArr);
    }/*}}}*/
}
