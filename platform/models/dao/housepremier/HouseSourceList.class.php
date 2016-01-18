<?php

/**
 * HouseSourceRent model
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-14
 * Time: 下午1:56
 * @codeCoverageIgnore
 */
class Dao_Housepremier_HouseSourceList extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $tableName = 'house_source_list';
    protected $table_fields = array('count(1)','id','house_id','puid','type','account_id','user_code','city','title','history_count','yesterday_count','district_id','district_name','street_id','street_name','xiaoqu_id','xiaoqu_name','pinyin','post_at','premier_status','bid_status','listing_status','is_similar','audit_time','auditor_id','auditor_name','modified_time','delete_time','reason','tag_type','tag_create_at','cpc_quality_auto','cpc_quality_manual','cpc_ranking_manual','biz_type', 'ad_types', 'ad_status','user_id','cookie_id');
    /**
     * 帖子状态
     *
     */
    const STATUS_OK = 1;
    const STATUS_DELETE = 11;
    const STATUS_PERMANENT_DELETE = 12;//永久删除
    const STATUS_MANUAL_LOCK = 15;
    const STATUS_COMPLAIN_LOCK = 16;
    const STATUS_APPROVAL_LOCK = 17; //待审核

    public function selectGroupbyXiaoquId($arrFields,$arrConds){
        $strGroupby = ' group by xiaoqu_id order by null';

        return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
    }
	 /** 
     *@codeCoverageIgnore
     **/
	public function selectGroupbyAccountId($arrFields,$arrConds){
		$strGroupby = ' group by account_id order by null';	
		 return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
	}
    public function selectGroupbyHouseType($arrFields,$arrConds){
        $strGroupby = ' group by type order by null';
        return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strGroupby);
    }

    public function selectAllInfo($arrFields,$arrConds){
        $strOrderby = ' order by id desc';
        return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strOrderby);
    }
	public function selectSumPvByPuids($arrFields,$arrConds){
		return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$strOrderby);
	}

    public function selectAllList($arrFields,$arrConds,$appends){
        return $this->dbHandler->select($this->tableName,$arrFields,$arrConds,null,$appends);
    }
}
    
