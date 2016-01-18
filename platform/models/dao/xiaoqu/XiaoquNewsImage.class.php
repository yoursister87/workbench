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

class Dao_Xiaoqu_XiaoquNewsImage extends Gj_Base_MysqlDao{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xiaoqu_news_image";
    protected $table_fields = array(
        'id',
        'news_id',
        'xiaoqu_id',
        'image',
        'status',
        'ctime',
        'audit_time'
    );
    /**getXiaoquNewsImageListByNewsId{{{
     */
    public function getXiaoquNewsImageListByNewsId($newsId, $fields = array('id', 'image')){
        $result = array();
        if (false === $this->validatorParameters($newsId) || !is_array($fields) || 0 >= count($fields)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $arrConds = array('news_id=' => $newsId);
        $result = $this->select($fields, $arrConds);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**addXiaoquNewsImage(){{{
     */
    public function addXiaoquNewsImage($newsId, $xiaoquId, $imageUrl){
        if (false === $this->validatorParameters($newsId) || false === $this->validatorParameters($xiaoquId) || empty($imageUrl)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
        }
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        $info = array(
            'news_id' => $newsId,
            'xiaoqu_id' => $xiaoquId,
            'image' => $imageUrl,
            'status' => 1,
            'ctime' => $now,
            'audit_time' => $now
        );
        $result = $this->insert($info);
        if (FALSE === $result) {
            throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "addXiaoquNewsImage info : " . json_encode($info), ErrorConst::E_SQL_FAILED_CODE);
        }
        return $result;
    }//}}}
    /**validatorParameters{{{
     */
    public function validatorParameters($item){
        if (empty($item) || !is_numeric($item)) {
            return false;
        } 
        return true;
    }//}}}
}

