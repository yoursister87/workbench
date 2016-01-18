<?php 
class Service_Data_SourceList_Part_CommercialCommon
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
        $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setOffset($group, $count, $queryFilterArr);
        $queryFilterArr = $this->setPrice($queryFilterArr);
        $queryFilterArr = $this->setArea($queryFilterArr);
        $queryFilterArr = $this->setPostAt($queryFilterArr);
        return $queryFilterArr;
    }//}}}
    /*{{{setDisplay*/
    protected function setDisplay($group, $queryFilterArr){
        if (true === $queryFilterArr['get_total_count']) {
            $displayFilter = array('listing_status' => array(5, 50));
        } else if (HousingVars::STICKY_LIST == $group) {
            $displayFilter = array('listing_status' => array(6, 50), 'post_type' => array(1, 10));
        } else {
            $displayFilter = array('listing_status' => array(5, 50), 'post_type' => array(0,1,10));
        }
        $queryFilterArr = array_merge($queryFilterArr, $displayFilter); 
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
    /*{{{setPrice*/
    protected function setPrice($queryFilterArr){
        // price 
        if (6 == $queryFilterArr['major_category_script_index'] && !empty($queryFilterArr['price'])) {
            $tmp = HousingVars::getPriceRange($queryFilterArr['city_domain'], 6);
            $queryFilterArr['price_month'] = $tmp[$queryFilterArr['price']];
            unset($queryFilterArr['price']);
        } 
        if (!isset($queryFilterArr['price']) && isset($queryFilterArr['price_b']) && isset($queryFilterArr['price_e'])) {
            if (6 == $queryFilterArr['major_category_script_index']) {
                $queryFilterArr['price_type'] = 2;
            }
            $priceRange = array($queryFilterArr['price_b'], $queryFilterArr['price_e']);
            if (isset($queryFilterArr['price_type']) && 1 == $queryFilterArr['price_type']) {
                $queryFilterArr['price_day'] = $priceRange;
            } else if (isset($queryFilterArr['price_type']) && 2 == $queryFilterArr['price_type']) {
                $queryFilterArr['price_month'] = $priceRange;
            } else {
                return $queryFilterArr;
            }
            unset($queryFilterArr['price_type']);
            unset($queryFilterArr['price_b']);
            unset($queryFilterArr['price_e']);
        } 
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setArea*/
    protected function setArea($queryFilterArr){
        // area
        if (isset($queryFilterArr['area'])) {
            if (6 == $queryFilterArr['major_category_script_index'] && 1 == $queryFilterArr['deal_type']) {
                $value = HousingVars::$AREA_TYPE_STORE_VALUES[$queryFilterArr['area']];
            } else {
                $value = HousingVars::$AREA_TYPE_VALUES[$queryFilterArr['area']];
            }
            $queryFilterArr['area_b'] = $value[0];
            $queryFilterArr['area_e'] = $value[1];
            unset($queryFilterArr['area']);
        }
        return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setPostAt*/
    protected function setPostAt($queryFilterArr){
        // post_at
        if (isset($queryFilterArr['date'])) {
            $date = HousingVars::$DATE_VALUES[$queryFilterArr['date']][1];
            $timeStart = strtotime(date('Y-m-d') . ' 00:00:00 -' . $date .' day');
            $queryFilterArr['post_at'] = array($timeStart, $_SERVER['REQUEST_TIME']);
        }
        return $queryFilterArr;
    }
    /*}}}*/
    /* {{{getModelInstance*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getModelInstance(){
        return PlatformSingleton::getInstance('Dao_Xapian_Shangpu');
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
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
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
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
        $model = $this->getModelInstance();
        return $model->getBuilder($queryConfigArr);
    }//}}}
}
