<?php
/**
 *  @package
 *  @subpackage
 *  @brief                $微信用户激活数据$
 *  @file                 WeixinUserActive.class.php
 *  @author               $Author:    cuijianwen <cuijianwen@ganji.com>$
 *  @lastChangeBy         15-03-19
 *  @lastmodified         下午5:55
 *  @copyright            Copyright (c) 2015, www.ganji.com
 */
class Dao_Weixin_WeixinUserActive extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_user_active';
    protected $table_fields = array('id', 'openid', 'active_time');
    protected $note_path = "/data/waplog/mobilelog/fang";

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
     * @brief 更新用户的最后激活时间
     * @param $openid
     */
    public function updateActiveTime( $openid ){
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }

        $fields = array('id','active_time');
        $Conds = array("openid = " => $openid );
        $hasData = $this->select($fields , $Conds );
        if( !empty( $hasData ) ){
			//增加更新频率的限制
			if($hasData[0]['active_time'])
			{
				if( (time() > $hasData[0]['active_time']) && (time()- $hasData[0]['active_time'] > 5*60) )
				{
					$id = $hasData[0]['id'];
					$status = $this->update( array("active_time" => time()  ) , array('id = ' => intval($id) ) );
				}else{
					$status = 1;
				}
			}
        }else{
            $status = $this->insert( array("active_time" => time() , "openid" => $openid ) );
        }
        if(!$status) {
            Gj_Log::warning($this->getLastSQL().'status:'.var_export($status));
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE ,ErrorConst::E_SQL_FAILED_MSG);
            return false;
        }
        return true;

    }

    /**
     * 返回 大于 $maxTimeStamp 的 有订阅信息的 用户列表
     * @param int $maxTimeStamp  时间戳 10位数字
     * @return bool
     * @throws Gj_Exception
     */
    public function getUserList( $maxTimeStamp = 0 , $major_category = 1){
        if ( empty( $maxTimeStamp )) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }

        if(in_array($major_category,array(1, 3))){
            $major_category_sql = ' in (1,3) ';
        }else{
            $major_category_sql = ' = '.$major_category;
        }

        //默认的$this 没有联表查询 ， 调用 $this->dbHandler 的 query 实现。
        $hasData = $this->dbHandler->query(
            "SELECT subscribe.openid , active.active_time ,subscribe.conditions , subscribe.subType, count(distinct subscribe.openid)
             FROM house_weixin.weixin_subscribe as subscribe
             inner join house_weixin.weixin_user as user on subscribe.openid = user.openid and user.subscribe_status = 1
             inner join house_weixin.weixin_user_active as active on user.openid = active.openid
             and active.active_time > {$maxTimeStamp} and subscribe.major_category {$major_category_sql}   group by subscribe.openid ORDER BY active.active_time ASC;");

        if( !empty( $hasData ) ){
            return $hasData;
        }else{
            throw new Gj_Exception(ErrorConst::E_SQL_FAILED_CODE ,ErrorConst::E_SQL_FAILED_MSG);
            return false;
        }
    }
}
