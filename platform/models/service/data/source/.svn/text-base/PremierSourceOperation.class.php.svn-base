<?php
/**
 * @package
 * @subpackage
 * @author               $Author:zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_Source_PremierSourceOperation {
    const OP_TYPE_USER_DELETE         = 'user-delete';         //删除房源
    const OP_TYPE_USER_RESTORE        = 'user-restore';        //已删除的帖子恢复
    const OP_TYPE_USER_START_PREMIER  = 'user-start-premier';  //开始精品推广
    const OP_TYPE_USER_START_PREMIER_IS_MAX  = 'user-premier-is-max';  //用户推广数已经到上限
    const OP_TYPE_USER_CANCEL_PREMIER = 'user-cancel-premier'; //取消精品推广
    const OP_TYPE_USER_ADD            = 'user-add';            //新增房源
    const OP_TYPE_USER_EDIT           = 'user-edit';           //修改房源
    const OP_TYPE_REFRESH_HOUSE_NOT_FOUND     = 'user-refresh-house-notfound';      //房源没找到
    const OP_TYPE_USER_ADD_REFRESH = 'user-add-refresh';  ///<  添加精品刷新
    const OP_TYPE_USER_DEL_REFRESH = 'user-del-refresh';  ///<  删除精品刷新

    const OP_TYPE_USER_START_ASSURE = 'user-start-assure'; ///< 开始放心房推广
    const OP_TYPE_USER_CANCEL_ASSURE = 'user-cancel-assure'; ///< 取消放心房推广
    const OP_TYPE_USER_ADD_ASSURE_REFRESH = 'user-add-refresh-assure'; ///< 添加放心房刷新
    const OP_TYPE_USER_DEL_ASSURE_REFRESH = 'user-del-refresh-assure'; ///< 删除放心房刷新
    const OP_TYPE_USER_ADD_REPEAT_ASSURE_REFRESH = 'user-add-repeat-assure-house'; ///< 用户添加 推广期间重复预约放心房
    const OP_TYPE_USER_DEL_REPEAT_ASSURE_REFRESH = 'user-del-repeat-assure-house'; ///< 用户删除 推广期间期间预约放心房

    const OP_TYPE_USER_ADD_REPEAT_HOUSE = 'user-add-repeat-house';  ///<   用户添加‘推广期内重复预约的房源’
    const OP_TYPE_USER_DEL_REPEAT_HOUSE = 'user-del-repeat-house';  ///<   用户取消‘在推广期内重复’

    const OP_TYPE_USER_ADD_RECOVER_EXPIRE_HOUSE = 'user-add-recover-expire-house';  ///<   恢复过期归档贴
    
    const OP_TYPE_USER_START_REAL = 'user-start-real'; ///< 用户上架真房源
    const OP_TYPE_USER_CANCEL_REAL = 'user-cancel-real'; ///< 用户下架真房源
    const OP_TYPE_USER_SELL_REAL = 'user-sell-real';///<100%真房源售出


    /**
     * 导入会员中心房源
     * 用户添加-梯子搬家主站贴
     * @var string
     */
    const OP_TYPE_USER_ADD_POST_MOVE_MS_HOUSE = 'user-add-post-move-ms-house';
    /**
     * 帖子搬家
     * 用户添加-帖子搬家其他账号
     * @var string
     */
    const OP_TYPE_USER_ADD_POST_MOVE_OTHER_HOUSE = 'user-add-post-move-other-house';
    /**
     * 系统删除-归档
     * @var string
     */
    const OP_TYPE_SYSTEM_DEL_PIGEONHOLE = 'system-pigeonhole';

    /**
     * @var Dao_Housepremier_HouseSourceOperation
     * @codeCoverageIgnore
     */
    private $objDao;
    public function __construct() {


        $this->objDao = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceOperation");
    }
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * 发表帖子操作记录
     * @param array $arrFileds 插入记录帖子
     * @return bool 是否成功
     */
    public function addSourceOperation($intHouseId, $intType, $intUser, $strOp, $strMsg = '',$intCity =0){
        $arrRow = array(
            'HouseId'	    => $intHouseId,
            'Type'		    => $intType,
            'OperationType' => $strOp,
            'Status'		=> 0,
            'Message'		=> $strMsg,
            'CreatorId'		=> $intUser,
            'CityId'        => $intCity,
            'CreatedTime' => date('Y-m-d H:i:s'),
        );
        $ret = $this->objDao->insert($arrRow);
        //开始日志双写
        $this->addSourceOperationNew($arrRow);
        return $ret;
    }

    /**
     * 新的操作日志双写的表
     * @param $intHouseId
     * @param $intType
     * @param $intUser
     * @param $strOp
     * @param string $strMsg
     * @param int $intCity
     * @return mixed
     */
    public function addSourceOperationNew($arrRow){


        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        $tried = 0;
        do {
            try{
                if (True == $objDao->insertOp($arrRow)) {
                    break;
                } else {
                    $tried += 1;
                    usleep(10000);
                }
            }catch (Exception $e){
                $tried += 1;
                usleep(10000);

            }
        } while (5 >= $tried);

        return 5 >= $tried;
    }

	public function getOPCountByAccountId($accountIds,$arrConds){

        $curTime = date('Y-m-d');
        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        $field = array('COUNT(1) AS count','CreatorId','Type');
        try{
            $this->arrRet = $objDao->getSelectBySplit($field,$arrConds,$curTime,$curTime,'group by CreatorId order by null');

        }catch(Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }
	public function getOPHouseList($accountIds,$arrConds){
		$objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceOperation');	
		if(empty($accountIds)){
			return $this->arrRet;
		}
		$field = array('CreatorId','Type','HouseId');
		try{
			$this->arrRet = $objDao->select($field,$arrConds);
		}catch(Exception $e){
			$this->arrRet = array(
				'errorno' =>ErrorConst::E_DB_FAILED_CODE,
				'errormsg' => $e->getMessage(),
			);  	
		}	
		return $this->arrRet;

	}


    public function getOperationStat($arrFields,$arrConds,$strBeginTime,$strEndTime){
        $arrRet = $this->arrRet;
        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        $arrDaoRet = $objDao->getOPStatByOperationType($arrFields,$arrConds,$strBeginTime,$strEndTime);
        $arrRet['data'] = $arrDaoRet;
            return $arrRet;
    }

    public function getOperationList($strFields,$strConds,$strBeginTime,$strEndTime,$strLimit){
        $arrRet = $this->arrRet;
        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        $arrDaoRet = $objDao->getOpList($strFields,$strConds,$strBeginTime,$strEndTime,$strLimit);

        $arrRet['data'] = $arrDaoRet;
        return $arrRet;
    }

    public function getOperationCount($arrConds,$strBeginTime,$strEndTime){
        $arrRet = $this->arrRet;
        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        $arrDaoRet = $objDao->getTotalCount($arrConds,$strBeginTime,$strEndTime);
        $arrRet['data'] = $arrDaoRet;
        return $arrRet;
    }

    public function getSelectBySplit($arrFields,$arrConds,$strBeginTime,$strEndTime,$strAppends = null){
        $arrRet = $this->arrRet;
        $objDao = Gj_LayerProxy::getProxy("Dao_HousepremierOperation_HouseSourceOperation");
        try{
            $arrDaoRet = $objDao->getSelectBySplit($arrFields,$arrConds,$strBeginTime,$strEndTime,$strAppends = null);
            $arrRet['data'] = $arrDaoRet;
        }catch(Exception $e){
            $this->arrRet = array(
            'errorno' =>ErrorConst::E_DB_FAILED_CODE,
            'errormsg' => $e->getMessage(),
            );
        }
        return $arrRet;
    }
}
