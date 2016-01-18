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

class Service_Data_SourceList_Part_RentPremier
{
    /* {{{isPremier*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function isPremier(){
        return true;
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
        if(isset($configArr['groupFilter'])){
            $result[0] = $result[0][0];//groupby后返回的数据多了一个维度
        } elseif (isset($configArr['group']) && in_array($configArr['group'], HousingVars::$validListType)) {
            return $this->resultSpecialProcess($result);
        }
        return $result;
    }//}}}
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
        // category
        if($queryFilterArr['major_category_script_index'] == 3){
            $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];
        }
        $queryFilterArr = $this->setOffset($group, $count, $queryFilterArr);
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setPrice($queryFilterArr);
        $queryFilterArr = $this->setArea($queryFilterArr);
        $queryFilterArr = $this->setPostAt($queryFilterArr);
        $queryFilterArr = $this->setZufangType($queryFilterArr);
        //$queryFilterArr = $this->setDealType($queryFilterArr);
        $queryFilterArr = $this->setLatlng($queryFilterArr);
        return $queryFilterArr;
    }//}}}
    /*{{{setOffset*/
    protected function setOffset($group, $count, $queryFilterArr) {
        $majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$queryFilterArr['major_category_script_index']];
        //fang1 fang3 
        if (in_array($queryFilterArr['agent'], array(2,3))
            && isset( HousingVars::$MIXED_CATEGORY_ID[ $majorCategoryId ]) 
            && HousingVars::MAIN_BLOCK_LIST == $group
        ) {
            if(is_array($queryFilterArr['premier_common_num'])){
                
                $premierPageSize = $queryFilterArr['premier_common_num'][0];
            } 
        }
        if(isset($premierPageSize) && $premierPageSize < $count){
            $count = $premierPageSize;
        }
        if (!isset($queryFilterArr['offset_limit'])) {            
            $queryFilterArr['offset_limit'] = array(($queryFilterArr['page_no'] - 1) * $count, $count);
        } 
        return $queryFilterArr;
     }
    /*}}}*/
    /*{{{setDisplay*/
    protected function setDisplay($group, $queryFilterArr){
        // display
        if (HousingVars::TRUE_HOUSE_LIST == $group) {
            $displayFilter = array('premier_status' => array(102, 102));
        } else if(HousingVars::GONGYU_LIST == $group) {
            $displayFilter = array('premier_status' => array(2, 2), 'tag_type' =>32 );
        } else {        
            $displayFilter = array('premier_status' => array(2, 2));
        }
        $queryFilterArr = array_merge($queryFilterArr, $displayFilter); 
        return $queryFilterArr;
     }
    /*}}}*/
    /*{{{setPrice*/
    protected function setPrice($queryFilterArr){
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setArea*/
    protected function setArea($queryFilterArr){
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setPostAt*/
    protected function setPostAt($queryFilterArr){
        // post_at
        if (isset($queryFilterArr['date'])) {
            $date = HousingVars::$DATE_VALUES[$queryFilterArr['date']][1];
            $timeStart = strtotime(date('Y-m-d') . ' 00:00:00 -' . $date .' day');
            $queryFilterArr['refresh_at'] = array($timeStart, $_SERVER['REQUEST_TIME']);
        }
            return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setLatlng*/
    protected function setLatlng($queryFilterArr){
        // latlng
        if (isset($queryFilterArr['latlng'])) {
            if (count($queryFilterArr['latlng'])>1) {
                $i = 0;
                foreach ($queryFilterArr['latlng'] as $lrange) {
                    $textFilter[$i++]['latlng'] = $lrange;
                }
            } else {
                $textFilter[0]['latlng'] = $queryFilterArr['latlng'];
            }
            $queryFilterArr['textFilter'] = $textFilter;
            unset($queryFilterArr['latlng']);
        }
        return $queryFilterArr;
    }
    /*}}}*/
    /* {{{setOrderField*/
    /**
     * @brief 
     *
     * @param $group
     * @param $queryConfigArr
     *
     * @returns   
     */
    protected function setOrderField($group, $queryConfigArr){
        if(!empty($queryConfigArr['orderField'])){
            return $queryConfigArr['orderField'];
        } else if(!empty($queryConfigArr['_orderField'][$group])){
            return $queryConfigArr['_orderField'][$group];//在HouseList的queryConfigArrFormat里定义
        }
        if(HousingVars::TRUE_HOUSE_LIST == $group){
            return array('refresh_at' => 'Desc');
        }
    }//}}}
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
    /* {{{getModelInstance*/
    /**
     * @brief 
     *
     * @param $group
     * @param $extra
     *
     * @returns   
     */
    protected function getModelInstance(){
        return Gj_LayerProxy::getProxy('Dao_Xapian_Zufangtg');
    }//}}}
    /* {{{resultSpecialProcess*/
    /**
     * @brief 
     *
     * @param $postList
     *
     * @returns   
     */
    protected function resultSpecialProcess($result){
        if (empty($result) || !is_array($result)) {
            return $result;
        }
        $postList = $result[0];
        $count = $result[1];
        $now = time();
        foreach ($postList as &$post) {
            $post['old_refresh_at'] = $post['refresh_at'];
            $post['refresh_at'] = (string)max(strtotime(date('Y-m-d 0:0:0', $now)), max($post['refresh_at'], (int)($now-3600-(($now - $post['post_at'])/86400)*1800)));
        }
        return array($postList, $count);
    }//}}}
}

