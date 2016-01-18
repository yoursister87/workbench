<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */

class Dao_Xiaoqu_XiaoquAutoIncrement extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName    = 'xiaoqu_auto_increment';
    protected $table_fields = array(
            'id',
            'name',
            'motivation',
            'city_id',
            'district_id',
            'street_id',
            'address',
            'creator_id',
            'creator_user_id',
            'created_time',
            'modified_time',
            'audit_status'
    );


    public function addXiaoquAutoIncrement($xiaoquInfo){
        if (empty($xiaoquInfo) || !is_array($xiaoquInfo)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        $arrRows = array(
            'name'    => $xiaoquInfo['xiaoqu_name'],
            'motivation' => 1,
            'city_id' => $xiaoquInfo['city_id'],
            'district_id' => $xiaoquInfo['district_id'],
            'street_id' => $xiaoquInfo['street_id'],
            'address' => $xiaoquInfo['xiaoqu_address'],
            'creator_id' => isset($xiaoquInfo['account_id']) ? $xiaoquInfo['account_id'] : -1,
            'creator_user_id' => isset($xiaoquInfo['user_id']) ? $xiaoquInfo['user_id'] : -1,
            'created_time' => $now,
            'modified_time' => $now
        );
        $ret = $this->insert($arrRows);

        if (FALSE === $ret) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $ret;
    }

} 
