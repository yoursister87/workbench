<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: zhangrong3 <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */

class Dao_Fang_HouseImageModelroom extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'fang';
    protected $dbHandler;
    protected $tableName = 'house_image_modelroom';

    protected $table_fields = array(
        'id',            
        'post_id',
        'is_cover',
        'category',
        'image',
        'status',
    );
    public function __construct($dbName){
        $this->dbName = $dbName;
        parent::__construct();
    }
    /* {{{getModelroomByApartmentId*/
    /**
     * @brief 
     *
     * @param $apartmentId
     * @param $num
     *
     * @returns   
     */
    public function getModelroomImageByApartmentId($apartmentId, $num, $offset){
        if (!is_numeric($apartmentId) || $apartmentId <= 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (!is_numeric($num) || $num <= 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $limitOffset = "";
        if ($offset>0) {
            $limitOffset = " limit {$offset}, {$num}";
        } else {
            $limitOffset = " limit {$num}";
        }
        $ret = $this->select(self::$table_basic_fields_string, "apartment_id={$apartmentId}{$limitOffset}");
        return $ret;
    }//}}}
    public function selectOrderByInd($arrFields, $arrConds){
        $appends = ' ORDER BY id asc';
        $ret = $this->dbHandler->select($this->tableName, $arrFields, $arrConds, null, $appends);
        return $ret;
    }
}
?>
