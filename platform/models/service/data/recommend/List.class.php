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
class Service_Data_Recommend_List
{
    /* {{{getRecommendList*/
    /**
     * @brief 
     *
     * @param $queryConfigArr
     * @param $num
     *
     * @returns   
     */
    public function getRecommendList($queryFilterArr, $num){
        try {
            if (!is_array($queryFilterArr) || empty($queryFilterArr)
                || !isset($queryFilterArr['major_category_script_index'])
                || (!isset($queryFilterArr['city_code']) && !isset($queryFilterArr['city_domain']))
            ) {
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
            }
            if (!isset($queryFilterArr['city_code']) 
                || !isset($queryFilterArr['city_domain'])
            ) {
                $this->fillCityCodeAndDomain($queryFilterArr);
            }
            $subDataService = $this->getSubDataService($queryFilterArr['major_category_script_index']);
            $result = $subDataService->getRecommendResult($queryFilterArr, $num);
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
    /* {{{getSubDataService*/
    /**
     * @brief 
     *
     * @param $majorCategoryScriptIndex
     *
     * @returns   
     */
    protected function getSubDataService($majorCategoryScriptIndex){
        switch ($majorCategoryScriptIndex) {
            case 6:
            case 7:
            case 8:
            case 9:
            case 11:
                return Gj_LayerProxy::getProxy('Service_Data_Recommend_Part_Commercial');
            default:
                return false;
        }
    }//}}}
    /* {{{fillCityCodeAndDomain*/
    /**
     * @brief 
     *
     * @param $cityCodeOrDomain
     *
     * @returns   
     */
    protected function fillCityCodeAndDomain(&$data){
        if (isset($data['city_code'])) {
            $cityInfo = GeoNamespace::getCityByCityCode($data['city_code']);
        } else if (isset($data['city_domain'])) {
            $cityInfo = GeoNamespace::getCityByDomain($data['city_domain']);
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (!is_array($cityInfo) || empty($cityInfo)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE); 
        }
        $data['city_code'] = $cityInfo['city_code'];
        $data['city_domain'] = $cityInfo['domain'];
        return $data;
    }//}}}
}
