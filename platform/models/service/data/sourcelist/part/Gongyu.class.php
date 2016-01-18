<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Data_SourceList_Part_Gongyu{
    protected $timeObj;
    public function __construct(){
        $this->timeObj = new Gj_Util_TimeMock();
    }
    public function __set($name, $value){
        if (Gj_LayerProxy::$is_ut === true){
            $this->name = $value;
        }
    }
    public function isPremier(){
        return false;
    }
    //{{{preSearch
    public function preSearch($group, $count, $queryConfigArr){
        $queryConfigArr['queryFilter'] = $this->formatQueryFilter($group, $count, $queryConfigArr['queryFilter']);
        $queryConfigArr['orderField'] = $this->setOrderField();
        $queryConfigArr['queryFilter']['status'] = 1;
        $model = $this->getModelInstance();
        return $model->preSearch($queryConfigArr);
    }
    //}}}
    //{{{getSearchResult
    public function getSearchResult($searchId, $configArr){
        $model = $this->getModelInstance();
        $result = $model->getSearchResult($searchId);
        return $result;
    }
    //}}}
    //{{{formatQueryFilter
    protected function formatQueryFilter($group, $count, $queryFilter){
        $queryFilter = $this->setOffset($queryFilter, $count);
        $queryFilter = $this->setPrice($queryFilter);
        $queryFilter = $this->setArea($queryFilter);
        return $queryFilter; 
    }
    //}}}
    /*{{{ setOffset*/
    protected function setOffset($queryFilter, $count = -1){
        if (-1 == $count){
            $queryFilter['offset_limit'] = array(0, 50); 
        } else {
            $queryFilter['offset_limit'] = array(($queryFilter['page_no'] - 1) * $count, $count);
        }
        return $queryFilter;
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

    protected function setOrderField(){
        return array(
            'weight' => 'desc',
        );
        return $queryConfigArr;     
    }
    protected function getModelInstance(){
        return Gj_LayerProxy::getProxy('Dao_Xapian_Gongyu'); 
    }
}
