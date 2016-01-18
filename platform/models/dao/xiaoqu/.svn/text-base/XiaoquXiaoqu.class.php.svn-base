<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: liuzhen1 <liuzhen1@ganji.com>$ 
 * @author       $Author: renyajing <renyajing@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 */ 

class Dao_Xiaoqu_XiaoquXiaoqu extends Gj_Base_MysqlDao
{
    //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xiaoqu_xiaoqu";
    
    public function getXiaoquInfoById($id, $fileds){
        $result = array();
        if (is_numeric($id) && is_array($fileds)) {
            $arrConds = array('id = ' => $id, 'status = ' => 0);
            $result = $this->select($fileds, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result[0];
    }

    public function getXiaoquInfoByCityByRandom($city, $fileds, $maxSize=1000){
       $result = array();
       if(is_array($fileds) && is_numeric($maxSize)){
           $arrConds = array('city = ' => $city); 
           $options = array('ORDER BY rand()', 'LIMIT 0,' . $maxSize);
           $result = $this->dbHandler->select($this->tableName, $fileds, $arrConds, null, $options);
           $countResult = $this->selectByCount($arrConds);
           if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
           } 
       } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
       }
       return array('result' =>$result, 'count' =>$countResult);
    }

    public function getXiaoquInfoByCity($city, $fileds) {
       $result = array();
       if(is_array($fileds)){
           $arrConds = array('city = ' => $city); 
           $result = $this->select($fileds, $arrConds);
           if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
           } 
       } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
       }
       return $result;
    }
    public function getXiaoquInfoByCityDistrict($city, $district_id, $fileds) {
        $result = array();
        if(is_array($fileds)){
            $arrConds = array('city = ' => $city, 'district_id = ' => $district_id);
            $result = $this->select($fileds, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByCityDistrictStreet($city, $district_id, $street_id,  $fileds) {
        $result = array();
        if(is_array($fileds)){
            $arrConds = array('city = ' => $city, 'district_id = ' => $district_id, 'street_id = ' => $street_id);
            $result = $this->select($fileds, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByIds($ids, $fileds){
        $result = array();
        if (is_array($ids) && count($ids) > 0 && is_array($fileds)) {
            $ids = implode(',', $ids);
            $arrConds = array('id in (' . $ids . ')', 'status = ' => 0);
            $result = $this->select($fileds, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByCityName($domain, $name, $fileds){
        $result = array();
        if (is_array($fileds) && count($fileds) > 0) {
            $domain = DBMysqlNamespace::mysqlEscapeMimic($domain);
            $name = DBMysqlNamespace::mysqlEscapeMimic($name);
            $arrConds = array('city = ' => $domain, 'name = ' => $name, 'status = ' => 0);
            $result = $this->select($fileds, $arrConds);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByCityPinyin($city, $pinyin) {
        $city = DBMysqlNamespace::mysqlEscapeMimic($city);
        $pinyin = DBMysqlNamespace::mysqlEscapeMimic($pinyin);

        if (empty($city) || empty($pinyin)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        
        $arrConds = array('pinyin = ' => $pinyin, 'city = ' => $city, 'status = ' => 0);
        $result = $this->select('*', $arrConds);
        if ($result === FALSE) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$result", ErrorConst::E_SQL_FAILED_CODE);
        }
        $result = $result[0];
        //之前均价是读用xiaoqu_xiaoqu表里的，现在读xiaoqu_stat里的
        unset($result['avg_price']);
        return $result;
    }
}
