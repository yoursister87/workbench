<?php 
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   zhangrong3 <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_SourceList_Part_SellPremierAndCommon
{
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
        if (6 == $group) {
            $queryConfigArr['groupFilter'] = 'company_id';
        }
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
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
        if (isset($configArr['group']) && in_array($configArr['group'], HousingVars::$validListType)) {
            return $this->resultSpecialProcess($result, $configArr['group']);
        }
        return $result;
    }//}}}
    /* {{{getExactPostTotalCount*/
    /**
     * @brief 暂且调用不到，先提供，以后可能会用到
     *
     * @param $group
     * @param $queryConfigArr
     *
     * @returns   
     */
    /*
    public function getExactPostTotalCount($group, $queryConfigArr){
        $queryConfigArr['queryFilter']['get_total_count'] = true;
        $queryConfigArr['queryFilter']['offset_limit'] = array(100, 1);
        $searchId = $this->preSearch($group, 1, $queryConfigArr);
        $resultArr = $this->getSearchResult($searchId, array($group, $queryConfigArr['queryFilter']));
        return $resultArr[1];
    }*/
    //}}}
    /* {{{formatQueryFilter*/
    /**
     * @brief 
     *
     * @param $group
     * @param $count
     * @param $queryFilterArr
     * @codeCoverageIgnore
     *
     * @returns   
     */
    protected function formatQueryFilter($group, $count, $queryFilterArr){
        $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setOffset($group, $count, $queryFilterArr);
        $queryFilterArr = $this->setPrice($queryFilterArr);
        $queryFilterArr = $this->setHuxing($queryFilterArr);
        $queryFilterArr = $this->setArea($queryFilterArr);
        $queryFilterArr = $this->setBudget($queryFilterArr);
        //$queryFilterArr = $this->setSpeedUp($group, $queryFilterArr);
        return $queryFilterArr;
    }//}}}
    /*{{{setDisplay*/
    protected function setDisplay($group, $queryFilterArr){
        if (true === $queryFilterArr['get_total_count']) {
            $displayFilter = array('listing_status' => array(5, 50));
        } else if (HousingVars::STICKY_LIST == $group) {
            $displayFilter = array('listing_status' => array(9, 50), 'post_type' => array(1, 10));
        } else if (HousingVars::TRUE_HOUSE_LIST == $group) {
            $displayFilter = array('premier_status' => array(102, 102));
            unset($queryFilterArr['agent']);
        } else if (HousingVars::MAIN_BLOCK_LIST == $group) {
            $displayFilter = array('listing_status' => array(5, 8), 'premier_status' => array(0, 100));
        } else if (HousingVars::XIAOQU_BAO_LIST == $group) {
            if (!empty($queryFilterArr['tag_type']) && $queryFilterArr['tag_type']>0) {
                require_once CODE_BASE2 . '/app/housing/tag/__init__.php';
                $tagUtil = new HouseTagInterface();
                $tags = $tagUtil->tagFlagDecode((int)HousingListPage::$REQUEST_PARAMS['tag_type']);
                $tags[] = 8;
                $displayFilter = array('listing_status' => array(5, 50), 'tag_type' => array('and' => $tags));
            } else {
                $displayFilter = array('listing_status' => array(5, 50), 'tag_type' => 8);
            }
        } else if (HousingVars::ZHENFANGYUAN_LIST == $group) {
            $displayFilter = array('premier_status' => array(112, 112));
        }
        $queryFilterArr = array_merge($queryFilterArr, (array)$displayFilter); 
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setOffset*/
    protected function setOffset($group, $count, $queryFilterArr){
        // offset,limit
        if (HousingVars::STICKY_LIST == $group && -1 == $count) {
            $queryFilterArr['offset_limit'] = array(0, 150);
        } else if (!isset($queryFilterArr['offset_limit'])) {
            $queryFilterArr['offset_limit'] = array(($queryFilterArr['page_no'] - 1) * $count, $count);
        } 
        return $queryFilterArr;
    }
    /*}}}*/
    //{{{setPrice
    protected function setPrice($queryFilterArr){
        if (isset($queryFilterArr['price_b']) && isset($queryFilterArr['price_e'])) {
            $queryFilterArr['price_b'] *= 10000;
            $queryFilterArr['price_e'] *= 10000;
        }
        return $queryFilterArr;
    }//}}}
    //{{{setHuxing
    protected function setHuxing($queryFilterArr){
        if (isset($queryFilterArr['huxing_shi2'])) {
            $queryFilterArr['huxing_shi'] =$queryFilterArr['huxing_shi2'];
            unset($queryFilterArr['huxing_shi2']);
        }
        return $queryFilterArr;
    }//}}}
    /*{{{setArea*/
    protected function setArea($queryFilterArr){
        if (isset($queryFilterArr['area'])) {
            $value = HousingVars::$AREA_TYPE_VALUES[$queryFilterArr['area']];
            $queryFilterArr['area_b'] = $value[0];
            $queryFilterArr['area_e'] = $value[1];
            unset($queryFilterArr['area']);
        }
        return $queryFilterArr;
    }//}}}
    //{{{setBudget
    protected function setBudget($queryFilterArr){
        if(isset($queryFilterArr['budget']) && $queryFilterArr['budget'] === '0') {
            unset($queryFilterArr['budget']);
        }
        //自定义首付预算
        if(!isset($queryFilterArr['downpayment']) && !empty($queryFilterArr['budget'])) {
            $queryFilterArr['downpayment'] = array(10000, $queryFilterArr['budget'] * 10000);
            unset($queryFilterArr['budget']);
        }
        return $queryFilterArr;
    }//}}}
    /*{{{setSpeedUp*/
    /*protected function setSpeedUp($group, $queryFilterArr){
        // 为了解决检索服务压力，在北京没有筛选条件的情况下，我们只搜索前3天(第一页除外)
        if (HousingVars::MAIN_BLOCK_LIST == $group && 'bj' == $queryFilterArr['city_domain'] 
            && (empty($queryFilterArr['agent']) || 3 == $queryFilterArr['agent'])
            && 1 != $queryFilterArr['page_no'] 
        ) {
            $mustHave = array('category', 'page', 'limit', 'agent');
            foreach ($queryFilterArr as $key => $value) {
                if (!in_array($key, $mustHave) && $value != null) {
                    return $queryFilterArr;
                }
            }
            $queryFilterArr['postat_b'] = time() - 3*86400;
            $queryFilterArr['postat_e'] = time();
        }
        return $queryFilterArr;
    }*/
    //}}}
    /* {{{getModelInstance*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getModelInstance(){
        return PlatformSingleton::getInstance('Dao_Xapian_Sell');
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
        foreach ($postList as $index => $post) {
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
    /* {{{getFilterList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @codeCoverageIgnore
     *
     * @returns   
     */
    public function getFilterList($queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter(HousingVars::MAIN_BLOCK_LIST, 1, $queryConfigArr['queryFilter']);
        $model = $this->getModelInstance();
        return $model->getFilterList($queryConfigArr);
    }//}}}
    /* {{{getBuilder*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @codeCoverageIgnore
     *
     * @returns   
     */
    public function getBuilder($queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter(HousingVars::MAIN_BLOCK_LIST, 1, $queryConfigArr['queryFilter']);
        $model = $this->getModelInstance();
        return $model->getBuilder($queryConfigArr);
    }//}}}
}
