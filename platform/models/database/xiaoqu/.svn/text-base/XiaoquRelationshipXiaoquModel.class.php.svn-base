<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class XiaoquRelationshipXiaoquModel extends BaseXiaoquModel
{
    protected $tableName = 'xiaoqu_relationship';
    /*getXiaoquRelationshipByXiaoquId{{{*/
    /**
     * @param int $xiaoquId  小区id
     * @param int $type  1.代表基于小区属性进行推荐  2.代表调用看了还看了推荐接口
     * @return array()
     */
    public function getXiaoquRelationshipByXiaoquId($xiaoquId, $type = null){
        $result = array();
        if (is_numeric($xiaoquId)) {
            $dbHandleSlaver = $this->getSlaveDbHandle();
            $sql = 'select * from ' . $this->tableName . ' where xiaoqu_id = ' . $xiaoquId;
            if (!empty($type)) {
                $sql .= ' and type = ' . intval($type);
            }
            $result = $dbHandleSlaver->getAll($sql);
            if (FALSE === $result) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
            }
        } else {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        return $result;
    }//}}}
}


