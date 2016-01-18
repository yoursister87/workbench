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
class Util_HouseXapian
{
    /* {{{createQueryBuilder*/
    /**
     * @brief 
     *
     * @param $xapianId
     * @param $queryConfigArr
     * @param $xapianFieldArr
     * @param $defaultQueryFieldArr
     * @param $defaultOrderFieldArr
     *
     * @returns   
     */
    public function createQueryBuilder($xapianId, $queryConfigArr, $xapianFieldArr, 
        $defaultQueryFieldArr=array(), $defaultOrderFieldArr=array()){
        // {{{params check
        if (!is_array($queryConfigArr) || empty($queryConfigArr)) {
            return false;
        }
        $queryFilterArr = $queryConfigArr['queryFilter'];
        //}}}
        $builder = $this->getPostListBuilder();
        // xapian 
        $builder->setEqualFilter('category', $xapianId);
        // fields fang1,3,5,12有公交地铁找房，要增加'bus_line', 'subway_line', 'college_line'字段
        if (isset($queryConfigArr['queryField']) && !empty($queryConfigArr['queryField'])) {
            $queryFieldArr = array_unique(array_merge($defaultQueryFieldArr, $queryConfigArr['queryField'])); 
        } else {
            $queryFieldArr = $defaultQueryFieldArr; 
        }
        $builder->setFields($queryFieldArr);
        // city
        $builder->setEqualFilter('city', $queryFilterArr['city_code']);
        // limit
        $builder->setLimit($queryFilterArr['offset_limit'][0], $queryFilterArr['offset_limit'][1]);
        // group by
        $builder->setGroupBy($queryConfigArr['groupFilter']);
        
        // order
        if (!isset($queryConfigArr['orderField']) || null == $queryConfigArr['orderField']) {
            $orderFieldArr = $defaultOrderFieldArr;
        } else {
            $orderFieldArr = $queryConfigArr['orderField'];
        }
        foreach ($orderFieldArr as $field => $descOrAsc) {
            $descOrAsc = ucfirst($descOrAsc);
            $functionName = "set{$descOrAsc}OrderBy";
            call_user_func_array(array($builder, $functionName), array($field));
        }
        // text/keyword 
        $textFilter = isset($queryFilterArr['textFilter']) ? $queryFilterArr['textFilter'] : array(array(), array());
        if (isset($queryFilterArr['latlng']) && is_string($queryFilterArr['latlng']) && empty($textFilter[0]['latlng'])) {
            $textFilter[0]['latlng'] = $queryFilterArr['latlng'];
            unset($queryFilterArr['latlng']);
        }
        $keyword = isset($queryFilterArr['keyword']) ? $queryFilterArr['keyword'] : '';
        $builder->setTextFilter($keyword, $textFilter);
        // filters 
        $queryFilterArr = $this->formatQueryFilter($queryFilterArr);
        $builder = $this->transToQueryParams($builder, $queryFilterArr, $xapianFieldArr);
        
        return $builder;
    }//}}}
    /* {{{formatQueryFilter*/
    /**
     * @brief 
     *
     * @param $queryFilterArr
     *
     * @returns   
     */
    protected function formatQueryFilter($queryFilterArr){
        // agent
        if (1 == $queryFilterArr['agent'] || 2 == $queryFilterArr['agent']) {
            $queryFilterArr['agent'] -= 1;
        } else if (in_array($queryFilterArr['agent'], array(3, 4, 5))) {
            unset($queryFilterArr['agent']);
        }
        // district & street
        if (!empty($queryFilterArr['street_list'])) {
            $multiStreetId = array();
            foreach ($queryFilterArr['street_list'] as $street) {
                $multiStreetId[] = $street['script_index'];
            }
            $queryFilterArr['street_id'] = $multiStreetId;
        } 
        // post_at
        if (isset($queryFilterArr['postat_b']) && isset($queryFilterArr['postat_e'])) {
            $queryFilterArr['post_at'] = array($queryFilterArr['postat_b'], $queryFilterArr['postat_e']);
        }
        if (isset($queryFilterArr['post_at'])) {
            list($postat_b, $postat_e) = $queryFilterArr['post_at'];
            $postat_b = floor($postat_b/100)*100;
            $postat_e = strtotime(date('Y-m-d 00:00:00', $postat_e))+86400;
            $queryFilterArr['post_at'] = array($postat_b, $postat_e);
        }
        // price 
        if (isset($queryFilterArr['price'])) {
            $priceRange = HousingVars::getPriceRange($queryFilterArr['city_domain'], $queryFilterArr['major_category_script_index']);
            $queryFilterArr['price'] = $priceRange[$queryFilterArr['price']]; 
        } else if (isset($queryFilterArr['price_b']) && isset($queryFilterArr['price_e'])) {
            $queryFilterArr['price'] = array($queryFilterArr['price_b'], $queryFilterArr['price_e']);
        }
        if (isset($queryFilterArr['price'])) {
            list($price_b, $price_e) = $queryFilterArr['price'];
            $price_b = ($price_b>1000000000) ? 1000000000 : $price_b;
            $price_e = ($price_e>1000000000) ? 1000000000 : $price_e;
            $queryFilterArr['price'] = array($price_b, $price_e);
        }
        // 面积 
        if (isset($queryFilterArr['area_b']) && isset($queryFilterArr['area_e'])) {
            $queryFilterArr['area'] = array($queryFilterArr['area_b'], $queryFilterArr['area_e']);
        }
        // 房龄
        if (isset($queryFilterArr['niandai'])) {
            $value = HousingVars::$AGE_VALUES[$queryFilterArr['niandai']];
            $year = (int)date('Y', $_SERVER['REQUEST_TIME']);
            $range = array($year-$value[1], $year-$value[0]);
            $queryFilterArr['niandai'] = $range;
        }
        // 楼层
        if (isset($queryFilterArr['ceng'])) {
            if ($queryFilterArr['ceng'] >= 1 && $queryFilterArr['ceng'] <= 3) {
                $queryFilterArr['ceng'] = array($queryFilterArr['ceng'], $queryFilterArr['ceng']);
            } else if (4 == $queryFilterArr['ceng']) {
                $queryFilterArr['ceng'] = array(4, 6);
            } else {
                $queryFilterArr['ceng'] = array(7, 100);
            }
        }
        // shopping
        if (isset($queryFilterArr['shopping'])) {
            $queryFilterArr['shopping'] 
                = BusinessDistrictNamespace::getBusinessDistrictIdByUrl($queryFilterArr['city_domain'], $queryFilterArr['shopping']);
        }
        // multi image
        if (isset($queryFilterArr['image_count'])) {
            $queryFilterArr['image_count'] = array(1, 999);
        }
        return $queryFilterArr;
    }//}}}
    /* {{{transToQueryParams*/
    /**
     * @brief 
     *
     * @param $builder
     * @param $queryFilterArr
     * @param $xapianFieldArr
     *
     * @returns   
     */
    protected function transToQueryParams($builder, $queryFilterArr, $xapianFieldArr){
        $intXapianFieldArr = $xapianFieldArr['n'];
        $strXapianFieldArr = $xapianFieldArr['f'];
        foreach ($queryFilterArr as $fieldName => $value) {
            if (array_key_exists($fieldName, $intXapianFieldArr)) {
                $fieldProperty = $intXapianFieldArr[$fieldName];
                if (1 != $fieldProperty[0]) {
                    continue;
                }
                if (!is_array($value) && is_numeric($value)) {
                    $value = array($value, $value);
                } 
                if (!is_array($value)) {
                    continue;
                }
                $builder->setBetweenFilter($fieldName, $value);
            } else if (array_key_exists($fieldName, $strXapianFieldArr)) {
                $fieldProperty = $strXapianFieldArr[$fieldName];
                if (1 != $fieldProperty[0] && 2 != $fieldProperty[0]) {
                    continue;
                } 
                if (2 == $fieldProperty[1]) {
                    if (is_array($value) && 1 == count($value)
                        && (array_key_exists('in', $value) || array_key_exists('and', $value))
                    ) {
                        $type = array_keys($value);
                        $type = $type[0];
                        $typeF = ucfirst(strtolower($type));
                        $functionName = "set{$typeF}Filter";
                        call_user_func_array(array($builder, $functionName), array($fieldName, (array)$value[$type]));
                    } else {
                        $builder->setInFilter($fieldName, (array)$value);
                    }
                } else {
                    if (is_array($value)) {
                        $builder->setInFilter($fieldName, $value);
                    } else if (is_numeric($value) || is_string($value)) { 
                        $builder->setEqualFilter($fieldName, $value);
                    }
                }
            } else {
                continue;
            }
        }
        return $builder;
    }//}}}

    protected function getPostListBuilder(){
        return new PostListQueryBuilder();
    }
}
