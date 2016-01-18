<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏数据$
 * @file                 WeixinCollection.class.php
 * @author               $Author:    wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         下午5:55
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Weixin_WeixinCollection extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_collection';
    protected $table_fields = array('id', 'openid', 'puid', 'title', 'thumb_img', 'url', 'major_category', 'create_time','del_status');

    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = 'house_weixin';
        parent::__construct();
    }

    /**
     * @brief 根据openid取多条收藏数据
     * @param string $orderby
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function selectCollections($openid, $fields, $limit = 10, $page = 1, $majorcategory = 1)
    {
        if (is_array($fields) && $openid !== null) {
            if (!$arrFields = $this->objMysqlUtil->checkSelectFields($fields, $this->table_fields)) {
                throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, " field is error");
            }
            if ($majorcategory == 1 || $majorcategory == 3) {
                $conds = " `openid` = '{$openid}' and ( `major_category` = 1 or `major_category` = 3 ) ";
            } else {
                $conds = " `openid` = '{$openid}' and `major_category` = '{$majorcategory}' ";
            }
			$conds .='and `del_status` = 1 ';
            $ret = $this->selectByPage($fields, $conds, $page, $limit, array('create_time' => 'DESC'), null);
        } else {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        return $ret;
    }

    /**
     * @brief 插入一条收藏数据
     * @param $data
     */
    public function insertOneCollection($data)
    {
        $fields = array('id');
        $conds = array( 'openid =' => $data['openid'], 'puid =' => $data['puid']);
		$result = $this->selectByPage($fields, $conds, 1, 1, array(), null);
        if($result[0])
		{
            //如果数据存在更新update_time,重新排序数据
            $data['del_status'] = '1';
			$update_result = $this->updateCollectionsInfoById($result[0]['id'],$data);
			if(!$update_result)
			{
				throw new Gj_Exception( ErrorConst::E_DATA_DUPLICATE_CODE, ErrorConst::E_DATA_DUPLICATE_MSG);
			}else{
				return true;
			}
        }else{
            if ($this->insert($data)) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @brief 删除该openid下的所有收藏数据
     * @param $openid
     * @return array
     */
    public function deleteAllCollectionsByOpenid($openid, $major_category = 1)
    {
        //delete($table, $conds = NULL, $options = NULL, $appends = NULL)
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }
        if($major_category == 1 || $major_category == 3){
            $sql_category = ' `major_category` in (1,3) ';
        }else{
            $sql_category = ' `major_category` = '.$major_category;
        }
        $sql = " `openid` = '{$openid}' and {$sql_category} ";
        //if (!$this->dbHandler->delete($this->tableName, $sql, null, null)) {
        //    return false;
        //}
        $data['del_status'] = 0;
        $status = $this->update($data, $sql);
        if ($status) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE, ErrorConst::E_SQL_FAILED_MSG);
        }

        return true;
    }

    /**
     * @brief 更新基础的数据信息
     * @param $openid
     * @return array
     */
    public function updateCollectionsInfoById($id, $value_list)
    {
        if ($id == null)
        {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }
        $status = $this->update($value_list , array('id = ' => intval($id)));
         if(!$status) {
            Gj_Log::warning($this->getLastSQL().'status:'.var_export($status).'id='.$id.'del_status='.$del_status);
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE ,ErrorConst::E_SQL_FAILED_MSG);
            return false;
        }
        return true;
    }
	/**
	 * 增加根据openid和帖子分类统计帖子总数的接口
	*/
	public function getCollectionsCountNumByCategory($open_id,$major_category=1)
	{
		if(empty($open_id))
        {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE ,ErrorConst::E_PARAM_INVALID_MSG);
        }
		//$arrFields = array('count(1) as count');

		$arrConds_where = " `openid` = '{$open_id}'";		
		if($major_category =='1/3')
		{
			//房1和房3汇总
			$arrConds_where .= ' and `major_category` in (1,3) ';
		}else{
			$arrConds_where .= ' and `major_category` ='.$major_category;
		}
		$arrConds_where .=' and `del_status` = 1 ';
		
        $ret = $this->selectByCount($arrConds_where);
		//echo $this->getLastSQL()."<br/>";
        if($ret === false){
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE,$this->getLastSQL());
        }
		return $ret;
	}
}
