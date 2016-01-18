<?php
class Service_Data_SourceList_Part_CommercialPremier
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
        $result = $model = $this->getModelInstance();
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
        $queryFilterArr['major_category'] = $queryFilterArr['major_category_script_index'];

        $queryFilterArr = $this->setOffset($count, $queryFilterArr);
        $queryFilterArr = $this->setDisplay($group, $queryFilterArr);
        $queryFilterArr = $this->setPrice($queryFilterArr);
        $queryFilterArr = $this->setArea($queryFilterArr);
        $queryFilterArr = $this->setPostAt($queryFilterArr);
        $queryFilterArr = $this->setDealType($queryFilterArr);
        $queryFilterArr = $this->setLatlng($queryFilterArr);
        return $queryFilterArr;
    }//}}}
    /*{{{setOffset*/
    protected function setOffset($count, $queryFilterArr) {
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
        } else {
            $displayFilter = array('premier_status' => array(2, 2));
        }
        $queryFilterArr = array_merge($queryFilterArr, $displayFilter); 
        return $queryFilterArr;
     }
    /*}}}*/
    /*{{{setPrice*/
    protected function setPrice($queryFilterArr){
        // price 
        if (6 == $queryFilterArr['major_category_script_index'] && null !== $queryFilterArr['price']) {
            $tmp = HousingVars::getPriceRange($queryFilterArr['city_domain'], 6);
            $queryFilterArr['price_month'] = $tmp[$queryFilterArr['price']];
            unset($queryFilterArr['price']);
        } 
        if (!isset($queryFilterArr['price']) && isset($queryFilterArr['price_b']) && isset($queryFilterArr['price_e'])) {
            if (6 == $queryFilterArr['major_category_script_index']) {
                $queryFilterArr['price_type'] = 2;
            }
            $priceRange = array($queryFilterArr['price_b'], $queryFilterArr['price_e']);
            if (1 == $queryFilterArr['price_type']) {
                $queryFilterArr['price_day'] = $priceRange;
            } else if (2 == $queryFilterArr['price_type']) {
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
            $queryFilterArr['refresh_at'] = array($timeStart, $_SERVER['REQUEST_TIME']);
        }
            return $queryFilterArr;
    }
    /*}}}*/
    /*{{{setDealType*/
    protected function setDealType($queryFilterArr){
        // deal_type 
        if (11 == $queryFilterArr['major_category_script_index'] && isset($queryFilterArr['deal_type'])) {
            // fang11索引中type的值是数据库内deal_type的值
            $queryFilterArr['type'] = $queryFilterArr['deal_type'];
        }
        unset($queryFilterArr['deal_type']);
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
        }
        return $queryFilterArr;
    }
    /*}}}*/
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
        return PlatformSingleton::getInstance('Dao_Xapian_Shangputg');
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

