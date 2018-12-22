<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Filter.php
 * @date 2013/12/09 18:32:24
 * @brief
 *
 **/

class Hk_Util_Filter {

    const PARAM_TYPE = 'type';
    const PARAM_INT_MIN = 'min';
    const PARAM_INT_MAX = 'max';
    const PARAM_EQ = 'eq';
    const PARAM_FORCE = 'force';
    const PARAM_SKIP = 'skip';
    const PARAM_DEFAULT = 'default';
    const PARAM_STR_TRIM = 'trim';
    const PARAM_REG = 'pattern';
    const PARAM_OPTIONAL   = 'optional';

    const TYPE_INT = 'int';
    const TYPE_STRING = 'string';

    const TYPE_DOUBLE = 'double';
    const TYPE_MAP = 'map';
    const TYPE_LIST = 'list';
    const TYPE_REG = 'reg';
    private static $LOG_LEVEL = Hk_Util_Exception::WARNING;

    public static $FUNC = array(
    self::TYPE_INT => '_filterInteger',
    self::TYPE_STRING => '_filterString',
    self::TYPE_DOUBLE => '_filterDouble',
    self::TYPE_REG => '_filterReg',
    self::TYPE_MAP => '_filterMap',
    self::TYPE_LIST => '_filterList',
    );
   	private static $typeConversion = false;

   	public static function setLogLevel($level=Hk_Util_Exception::WARNING){
   	    self::$LOG_LEVEL = $level;
   	}

   	private static function _getMapDefaultValue($arrMap){
   	    $arrTmp = array();
   	    if(is_array($arrMap)){
   	        foreach ($arrMap as $key => $value) {
                if(!isset($value['skip']) || $value['skip'] == 0){
                    $arrTmp[$key] = self::_setDefaultValue($value);
                }
   	        }
   	    }
   	    return $arrTmp;
   	}

   	private static function _getListDefaultValue($arrList){
   	    $arrTmp = array();
   	    $arrTmp[] = self::_setDefaultValue($arrList);
   	    return $arrTmp;
   	}


    private static function _setDefaultValue($arrFilterConf) {
        switch ($arrFilterConf[self::PARAM_TYPE]) {
            case self::TYPE_INT:
                $defaultValue = intval($arrFilterConf[self::PARAM_DEFAULT]);
                break;
            case self::TYPE_STRING:
                $defaultValue = strval($arrFilterConf[self::PARAM_DEFAULT]);
                break;
            case self::TYPE_DOUBLE:
                $defaultValue = doubleval($arrFilterConf[self::PARAM_DEFAULT]);
                break;
            case self::TYPE_MAP:
                $defaultValue = self::_getMapDefaultValue($arrFilterConf[self::PARAM_DEFAULT]);
                break;
            case self::TYPE_LIST:
                $defaultValue = self::_getListDefaultValue($arrFilterConf[self::PARAM_DEFAULT]);
                break;
            default :
                break;
        }
        return $defaultValue;
    }

    public static function filter(&$arrParams, $arrFilterConfig, $typeConversion=false, $is_item=false, $skipParamsNum = false) {

        self::$typeConversion = $typeConversion;

        if (is_null($arrFilterConfig)) {
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "config is null");
        }

        if (is_null($arrParams)) {
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "input params or output params is null");
        }

        if($is_item){ //是否单个item
            $paramValue = $arrParams;
            $arrFilterConf = $arrFilterConfig;
            //参数详细检查
            if (isset(self::$FUNC[$arrFilterConf[self::PARAM_TYPE]])
            && (!isset($arrFilterConf[self::PARAM_SKIP]) || $arrFilterConf[self::PARAM_SKIP] == 0)
            ) {
                $func_name = self::$FUNC[$arrFilterConf[self::PARAM_TYPE]];
                $paramValue = self::$func_name('list item', $paramValue, $arrFilterConf);
            }
            $arrParams = $paramValue;

        }else{
            foreach ($arrFilterConfig as $strFilterName => $arrFilterConf) {

                if(isset($arrFilterConf[self::PARAM_SKIP]) && $arrFilterConf[self::PARAM_SKIP] == 1){
                    continue;
                }

                //检查是否存在
                if (!array_key_exists($strFilterName, $arrParams)) {
                    if(isset($arrFilterConf[self::PARAM_FORCE]) && $arrFilterConf[self::PARAM_FORCE] == 1) {
                        throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_NOT_EXIST, "param {$strFilterName}");
                    }
                    $paramValue = self::_setDefaultValue($arrFilterConf);
                } else {

                    //参数存在，但是为空
                    if(is_null($arrParams[$strFilterName])){
                        throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "param {$strFilterName} is null");
                    }
                    $paramValue = $arrParams[$strFilterName];
                }

                //参数详细检查
                if (isset(self::$FUNC[$arrFilterConf[self::PARAM_TYPE]])) {
                    $func_name = self::$FUNC[$arrFilterConf[self::PARAM_TYPE]];
                    $paramValue = self::$func_name($strFilterName, $paramValue, $arrFilterConf);
                }
                $arrParams[$strFilterName] = $paramValue;
            }//end foreach


            if($skipParamsNum === false){
                foreach ($arrParams as $key => $value) {
                    if(!array_key_exists($key, $arrFilterConfig) && $key != 'apiversion'){
                        unset ($arrParams[$key]);
                    }
                }
            }
        }

    }

    private static function _filterMap($strFilterName, $mapParamValue, $arrConf){
        
        if(is_array($mapParamValue)){
            self::filter($mapParamValue, $arrConf['default']);
            return $mapParamValue;

        }else{
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
            "{$strFilterName} is not map(array)", 
            self::_exportLogData($strFilterName, $mapParamValue, $arrConf), self::$LOG_LEVEL);
        }
    }

    private static function _filterList($strFilterName, $listParamValue, $arrConf){

        if(is_array($listParamValue)){
            foreach ($listParamValue as $key => &$value) {
            	   self::filter($value, $arrConf['default'], false, $is_item=true);
            }
        }else{
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
            "{$strFilterName} is not list(array)", 
            self::_exportLogData($strFilterName, $listParamValue, $arrConf), self::$LOG_LEVEL);
        }

        return $listParamValue;
    }



    private static function _filterInteger($strFilterName, $intParamValue, $arrConf) {

        //类型转换
        $intParamValue = self::$typeConversion ? intval($intParamValue) : $intParamValue;
        if(!is_integer($intParamValue)){
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "{$strFilterName} is not integer", 
            self::_exportLogData($strFilterName, $intParamValue, $arrConf), self::$LOG_LEVEL);
        }

        if (array_key_exists(self::PARAM_INT_MIN, $arrConf)) {
            if ($intParamValue < $arrConf[self::PARAM_INT_MIN]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "{$strFilterName} too small", 
                self::_exportLogData($strFilterName, $intParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }
        if (array_key_exists(self::PARAM_INT_MAX, $arrConf)) {
            if ($intParamValue > $arrConf[self::PARAM_INT_MAX]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "{$strFilterName} too big", 
                self::_exportLogData($strFilterName, $intParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        if (isset($arrConf[self::PARAM_EQ])) {
            if ($intParamValue != $arrConf[self::PARAM_EQ]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "{$strFilterName} eq error", 
                self::_exportLogData($strFilterName, $intParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        return $intParamValue;
    }

    private static function _filterReg($strFilterName, $regParamValue, $arrConf) {
        
        if (isset($arrConf[self::PARAM_REG])) {
            if (preg_match($arrConf[self::PARAM_REG], $regParamValue)) {
                return $regParamValue;
            } else {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is not match preg", 
                self::_exportLogData($strFilterName, $regParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        return $regParamValue;
    }

    private static function _filterString($strFilterName, $strParamValue, $arrConf) {
        
        if(!is_string($strParamValue)){
            throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR, "{$strFilterName} is not string", 
            self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
        }

        //trim
        if (isset($arrConf[self::PARAM_STR_TRIM])
        && intval($arrConf[self::PARAM_STR_TRIM]) === 1) {
            $strParamValue = trim($strParamValue);
        }

        //strlen
        if (isset($arrConf[self::PARAM_INT_MIN])) {
            if (strlen($strParamValue) < $arrConf[self::PARAM_INT_MIN]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is too short", 
                self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }
        if (isset($arrConf[self::PARAM_INT_MAX])) {
            if (strlen($strParamValue) > $arrConf[self::PARAM_INT_MAX]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is too long", 
                self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        if (isset($arrConf[self::PARAM_EQ])) {
            if ($strParamValue !== $arrConf[self::PARAM_EQ]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is not eq", 
                self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        return $strParamValue;
    }

    private static function _filterDouble($strFilterName, $strParamValue, $arrConf) {

        $strParamValue = self::$typeConversion ? floatval($strParamValue) : $strParamValue;

        if (isset($arrConf[self::PARAM_INT_MIN])) {
            if ($strParamValue < $arrConf[self::PARAM_INT_MIN]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is too small", 
                self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        if (isset($arrConf[self::PARAM_INT_MAX])) {
            if ($strParamValue > $arrConf[self::PARAM_INT_MAX]) {
                throw new Hk_Util_Exception(Hk_Util_ExceptionCodes::PARAM_ERROR,
                "{$strFilterName} is too big", 
                self::_exportLogData($strFilterName, $strParamValue, $arrConf), self::$LOG_LEVEL);
            }
        }

        return $strParamValue;
    }

    private static function _exportLogData($filterName, $paramValue, $arrConf) {

        $logData = array(
                "filterName"=>var_export($filterName,true),
                "paramValue"=>var_export($paramValue,true),
                "arrConf"=>var_export($arrConf,true),
        );
        return $logData;
    }

}
