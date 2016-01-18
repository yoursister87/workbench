<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Xiaoqu_XiaoquRelationship extends Gj_Base_MysqlDao
{
    //数据库别名&库
    protected $dbNameAlias = 'fang';
    protected $dbName = 'xiaoqu';
    protected $tableName = 'xiaoqu_relationship';
    protected $table_fields = array(
        'id',
        'xiaoqu_id',
        'type',
        'similar_xiaoqu',
        'mtime'
    );

    /*{{{getXiaoquRelationship*/
    /**
     * @param int $xiaoquId  小区id
     * @param int $type      类型    1.代表基于小区属性进行推荐  2.代表调用看了还看了推荐接口
     *
     * @return array()
     */
    public function getXiaoquRelationship($xiaoquId, $type = null){
        $isValidator = $this->validatorParameters(
            array(
                'xiaoquId' => $xiaoquId,
                'type' => $type
            )
        );
        if (!$isValidator) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $itemList = array();
        $fields = $this->table_fields;
        $conArrays = array();
        $conArrays['xiaoqu_id = '] = $xiaoquId;
        $conArrays['type = '] = $type;
        $itemList = $this->select($fields, $conArrays);
        if (FALSE === $itemList) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $itemList;
    }
    /*}}}*/
    /**validatorParameters{{{
     */
    public function validatorParameters($item){
        $result = true;
        if (in_array('xiaoquId', array_keys($item)) && !is_numeric($item['xiaoquId'])){
            $result = false;
        }
        if (in_array('type', array_keys($item)) && !empty($item['type']) && !is_numeric($item['type'])){
            $result = false;
        }
        return $result;
    }//}}}
}
