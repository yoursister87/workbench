<?php
class Dao_Weixin_WeixinUser extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'weixin_user';
    protected $table_fields = array('id', 'openid', 'uid' , 'nickname' , 'sex' , 'language' , 'city' , 'province' , 'country' , 'headimgurl' , 'subscribe_time' , 'unionid' , 'subscribe_status');

    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = 'house_weixin';
        parent::__construct();
    }

    public function getUserInfo($openid, $fields = array('id', 'nickname')){

        $Conds = array("openid = " => $openid );
        $userInfo = $this->select($fields , $Conds );
        if( !empty( $userInfo ) ){
            return $userInfo[0];
        }else{
            return false;
        }
    }
    /**
     * @brief 判断用户是否存在
     * @param $openid
     */
    public function existUser( $openid ){
        if ($openid == null) {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }

        $fields = array('id');
        $Conds = array("openid = " => $openid );
        $hasData = $this->select($fields , $Conds );

        if( !empty( $hasData ) ){
            return true;
        }else{
            return false;
        }
    }

    public function addUser ( $data ){
        if( $data['openid'] == null ){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }

        if( !isset($data['unionid']) ){
            $data['unionid'] = '';
        }

        if ($this->insert($data)) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
    }

    public function updateUser( $data ){
        if( $data['openid'] == null ){
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }
        if (!$this->existUser($data['openid'])) {
            throw new Gj_Exception(ErrorConst::E_DATA_NOT_EXIST_CODE, ErrorConst::E_DATA_NOT_EXIST_MSG);
        }

        if ($this->update($data, array('openid = ' => $data['openid'] ))) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_INNER_FAILED_CODE, ErrorConst::E_INNER_FAILED_MSG);
        }
    }
}
