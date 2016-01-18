<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: zhangrong3 <zhangrong3@ganji.com>$
 * @author               $Author: zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */

class Dao_Fang_HouseSourceModelroom extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'fang';
    protected $dbHandler;
    protected $tableName = 'house_source_modelroom';

    protected $table_fields = array(
        'id',
        'puid',
        'user_id',
        'city',
        'district_id',
        'district_name',
        'street_id',
        'street_name',
        'title',
        'description',
        'image',
        'huxing_image',
        'image_count',
        'price',
        'apartment_id',
        'apartment_name',
        'apartment_pinyin',
        'address',
        'latlng',
        'area',
        'ceng',
        'ceng_total',
        'chaoxiang',
        'huxing_shi',
        'huxing_ting',
        'huxing_wei',
        'peizhi',
        'tab_system',
        'tab_personality',
        'status',
        'post_at',
        'modified_at',
        'weight',
        'model_name',
    );
    protected static $table_basic_fields_string = "*";
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
    public function getModelroomByApartmentId($apartmentId, $num, $pageNo){
        if (!is_numeric($apartmentId) || $apartmentId <= 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (!is_numeric($num) || $num <= 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $ret = $this->selectByPage(self::$table_basic_fields_string, "apartment_id={$apartmentId}", $pageNo, $num);
        return $ret;
    }//}}}
    /* {{{getModelroomByParams*/
    /**
     * @brief 
     *
     * @param $params
     * @param $num
     *
     * @returns   
     */
    public function getModelroomByParams($params, $num, $pageNo){
        if (!is_array($params)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        if (!is_numeric($num) || $num <= 0) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $this->selectByPage(self::$table_basic_fields_string, $params, $pageNo, $num);
    }//}}
    /*{{{getModelroomBySqlParams*/
    /**
     * @param string $domain
     * @param string $fields
     * @param array  $filter
     * @param string $appends
     * @return array
     */
    public function getModelroomBySqlParams($fields = null, $conds = null, $options = null, $appends = null){
        if (empty($fields)){
            $fields = "*";
        }
        $data = $this->dbHandler->select($this->tableName, $fields, $conds, $options, $appends);
        return $data;
    }
    /*}}}*/

}
?>
