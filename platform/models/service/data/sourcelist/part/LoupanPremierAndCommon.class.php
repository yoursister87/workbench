<?php 
class Service_Data_SourceList_Part_LoupanPremierAndCommon 
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
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setOffset($group, $count, $queryFilterArr);
        $queryFilterArr = $this->setPrice($queryFilterArr);
        $queryFilterArr = $this->setArea($queryFilterArr);
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
            $displayFilter = array('listing_status' => array(5, 8));
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
    /* {{{getModelInstance*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getModelInstance(){
        return PlatformSingleton::getInstance('Dao_Xapian_Loupan');
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
                } else if(HousingVars::MAIN_BLOCK_LIST == $group){
                    //不在置顶地区的listing_status>=9的帖子将来放到MainBlock最后显示FANG-7936
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
        $queryConfigArr['queryFilter']['major_category'] = $queryConfigArr['queryFilter']['major_category_script_index'];
        $model = $this->getModelInstance();
        return $model->getBuilder($queryConfigArr);
    }//}}}
}
?>
