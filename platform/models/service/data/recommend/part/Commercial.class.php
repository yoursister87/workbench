<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangrong3$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class Service_Data_Recommend_Part_Commercial
{
    protected static $validParams = 
        array(
            'city_code',
            'major_category',
            'agent',
            'district_id',
            'street_id',
            'huxing_shi',
            'price_from',
            'price_to',
            'house_type',
        );
    /* {{{getRecommendResult*/
    /**
     * @brief 
     *
     * @param $queryFilterArr
     * @param $num
     *
     * @returns   
     */
    public function getRecommendResult($queryFilterArr, $num){
        $day = 5;
        $info = $this->fillRecommendParams($queryFilterArr);
        $recommendObj = Gj_LayerProxy::getProxy('Dao_Recommend_House');
        if ($info['agent'] != -1) {
            $list = $recommendObj->getListSimilarHousing($info, $num, $day, $info['agent']);
        } else {
            //common
            unset($info['agent']);
            $common = $recommendObj->getListSimilarHousing($info, $num, $day, 0);
            //premier
            $premier = $recommendObj->getListSimilarHousing($info, $num, $day, 1);
            $list = array_merge((array)$common, (array)$premier);
        }
        if (empty($list) || !is_array($list)) {
            return array();
        }
        $result = $this->resultSpecialProcess($list);
        return array_slice($result, 0, $num);
    }//}}}
    /* {{{fillRecommendParams*/
    /**
     * @brief 
     *
     * @param $queryFilterArr
     *
     * @returns   
     */
    protected function fillRecommendParams($queryFilterArr){
        $info = $queryFilterArr;
        $info['major_category'] = $queryFilterArr['major_category_script_index'];
        if (isset($info['price'])) {
            $range = HousingVars::getPriceRangeByKey($info['price'], $info['city_domain'], 
                $info['major_category_script_index']
            );
            if ($range) {
                $info['price_from'] = $range[0];
                $info['price_to'] = $range[1];
            }
        }
        if (isset($info['agent']) && in_array($info['agent'], array(1, 2))) {
            $info['agent'] -= 1;
        } else {
            $info['agent'] = -1;
        }
        foreach ($info as $key => $value) {
            if (!in_array($key, self::$validParams)) {
                unset($info[$key]);
            }
        } 
        return $info;
    }//}}}
    /* {{{resultSpecialProcess*/
    /**
     * @brief 
     *
     * @param $list
     *
     * @returns   
     */
    protected function resultSpecialProcess(&$list){
        foreach ($list as $key => $item) {
            if(isset( $item['price_type'] ) && empty($item['price_type'])) {
                unset($item['price_type']);
            }
            if ($item['listing_status'] == 5) {
                // common
                unset($item['house_id']);
                $list[$item['post_at']. rand(100, 999) ] = $item;
            } else {
                // premier
                $list[$item['refresh_at']. rand(100, 999) ] = $item;
            }
            unset($list[$key]);
        }
        krsort($list);
        return $list;
    }//}}}
}
