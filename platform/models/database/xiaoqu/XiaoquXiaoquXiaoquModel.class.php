<?php
class XiaoquXiaoquXiaoquModel extends BaseXiaoquModel
{
    protected $tableName = "xiaoqu_xiaoqu";
    
    public function getXiaoquInfoById($id, $fileds){
        $result = array();
        if (is_numeric($id) && is_array($fileds)) {
            $queryFileds = $this->parseFields($fileds);
            $sql = "select {$queryFileds} from {$this->tableName} where id = {$id} and `status` = 0";
            $dbHandle = $this->getSlaveDbHandle();
            $result = $dbHandle->getAll($sql);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByIds($ids, $fileds){
        $result = array();
        if (is_array($ids) && count($ids) > 0 && is_array($fileds)) {
            $queryFileds = $this->parseFields($fileds);
            $ids = implode(',', $ids);
            $sql = "select {$queryFileds} from {$this->tableName} where id IN ($ids) and `status` = 0";
            $dbHandle = $this->getSlaveDbHandle();
            $result = $dbHandle->getAll($sql);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByCityName($domain, $name, $fileds){
        $result = array();
        if (is_array($fileds)) {
            $queryFileds = $this->parseFields($fileds);
            $sql = "select {$queryFileds} from {$this->tableName} where `city` = '{$domain}' and `name` = '{$name}' and `status` = 0";
            $dbHandle = $this->getSlaveDbHandle();
            $result = $dbHandle->getAll($sql);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }

    public function getXiaoquInfoByCityPinyin($city, $pinyin) {
        $dbSlave = $this->getSlaveDbHandle();
        $city = $dbSlave->mysqli_real_escape_string($city);
        $pinyin = $dbSlave->mysqli_real_escape_string($pinyin);

        if (empty($city) || empty($pinyin)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        
        $sql = 'SELECT * FROM xiaoqu_xiaoqu WHERE pinyin="'.$pinyin.'" AND city="'.$city.'" and `status` = 0';
        $result = $dbSlave->getRow($sql);
        if ($result === FALSE) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        //之前均价是读用xiaoqu_xiaoqu表里的，现在读xiaoqu_stat里的
        unset($result['avg_price']);
        return $result;
    }
}
