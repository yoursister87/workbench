<?php
/***************************************************************************
 *
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file IdAlloc.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/04/13 21:07:18
 * @brief id分配器
 *
 **/

class Hk_Service_IdAllocDao extends Hk_Common_BaseDao {
    public function __construct($cluster) {
        $this->_dbName = $cluster;
        $this->_db     = Hk_Service_Db::getDB($this->_dbName);
        $this->_table  = "tblNewIdAlloc";
        $this->arrFieldsMap = array(
            'name' => 'name',
            'idx'  => 'idx',
            'id'   => 'id',
        );

        $this->arrTypesMap = array(
            'idx' => Hk_Service_Db::TYPE_INT,
            'id'  => Hk_Service_Db::TYPE_INT,
        );
    }
}

class Hk_Service_IdAlloc {

    const NAME_HOMEWORK            = 'homework_id';
    const NAME_SEARCHRECORD        = 'searchrecord_id';
    const NAME_ORDER               = 'order_id';
    const NAME_SERVICE             = 'service_id';
    const NAME_USER                = 'user_id';
    const NAME_PIC                 = 'pic_id';
    const NAME_FUDAO_COURSE        = 'fudao_course_id';
    const NAME_FUDAO_LESSON        = 'fudao_lesson_id';
    const NAME_FUDAO_EXERCISE      = 'fudao_exercise_id';
    const NAME_FUDAO_CLASS         = 'fudao_class_id';
    const NAME_FUDAO_TRADE         = 'fudao_trade_id';
    const NAME_PAY_REFUND_BATCH_ID = 'pay_refund_batch_id';
    const NAME_FUDAO_LECTURE       = 'fudao_lecture_id';
    const NAME_COURSE_ORDER        = 'course_order_id';
    const NAME_COURSE_LECTURE      = 'course_lecture_id';
    const NAME_DUIZHANG            = 'duizhang_id';
    const NAME_FUDAO_CHAT          = 'fudao_chat_id';
    const NAME_REFUND_ID           = 'pay_refund_id';
    const NAME_REFUND_REQUEST_NO   = 'refund_request_no';
    const NAME_PAY_NETPAY_AGRNO    = 'pay_netpay_argno';
    const NAME_PAY_NETPAY_MCSERNO  = 'pay_netpay_mcserno';
    const NAME_MATHS_SEARCH_SID    = 'maths_search_sid';
    const NAME_SKU_ID              = 'sku_id';
    const NAME_SC_FLOW_ID          = 'name_sc_flow_id';
    const NAME_IM_EXERCISE         = 'im_exercise_id';
    const NAME_DATI_TRANSTOACCOUNT = 'dati_transtoaccount_id';
    const NAME_ZB_TRADE_ID         = 'zb_trade_id';
    const NAME_ZB_SUB_TRADE_ID     = 'zb_sub_trade_id';
    const NAME_ZB_NEW_SUB_TRADE_ID = 'zb_new_sub_trade_id';
    const NAME_FZ_ORDER_ID         = 'fz_order_id';
    const NAME_FZ_PRODUCT_SKU_ID   = 'fz_product_sku_id';
    const NAME_INVITED_TRANSTOACCOUNT = 'invited_transtoaccount_id';
    const NAME_FZ_FANSCONTENT_ID   = 'fz_fanscontent_id';
    const NAME_FZ_USER_ID          = 'fz_user_id';
    const NAME_EXAM_ANSWER         = 'exam_answer_id';
    const NAME_PAY_REFUND_ORDER_ID = 'pay_refund_order_id';
    const NAME_ZHIBO_COMMENT_ID    = 'zhibo_comment_id';
    const NAME_ZB_REFUND_BATCH_ID  = 'zb_refund_batch_id';
    const NAME_PT_COMMENT_ID       = 'pt_comment_id';
    const NAME_PT_COMMENT_RID      = 'pt_comment_rid';
    const NAME_FZ_LIVE_ID          = 'fz_live_id';
    const NAME_PT_COMMENT_SCORE_ID = 'pt_comment_score_id';
    const NAME_ZB_TRANSACTION_ID   = 'zb_transaction_id';
    const NAME_PT_COUPON_CODE_ID   = 'pt_coupon_code_id';

    static public $_TotalIDXMap = array(
        self::NAME_HOMEWORK            => 20,
        self::NAME_SEARCHRECORD        => 4096,
        self::NAME_ORDER               => 4096,
        self::NAME_SERVICE             => 20,
        self::NAME_USER                => 1024,
        self::NAME_PIC                 => 1024,
        self::NAME_FUDAO_COURSE        => 1,
        self::NAME_FUDAO_LESSON        => 1,
        self::NAME_FUDAO_EXERCISE      => 1,
        self::NAME_FUDAO_CLASS         => 1,
        self::NAME_FUDAO_TRADE         => 1,
        self::NAME_PAY_REFUND_BATCH_ID => 1,
        self::NAME_FUDAO_LECTURE       => 1,
        self::NAME_COURSE_ORDER        => 1,
        self::NAME_COURSE_LECTURE      => 1,
        self::NAME_DUIZHANG            => 1,
        self::NAME_FUDAO_CHAT          => 20,
        self::NAME_REFUND_ID           => 1,
        self::NAME_REFUND_REQUEST_NO   => 1,
        self::NAME_PAY_NETPAY_AGRNO    => 1,
        self::NAME_PAY_NETPAY_MCSERNO  => 1,
        self::NAME_MATHS_SEARCH_SID    => 10,
        self::NAME_SKU_ID              => 1,
        self::NAME_SC_FLOW_ID          => 1,
        self::NAME_IM_EXERCISE         => 1,
        self::NAME_DATI_TRANSTOACCOUNT => 1,
        self::NAME_ZB_TRADE_ID         => 1,
        self::NAME_ZB_SUB_TRADE_ID     => 1,
        self::NAME_ZB_NEW_SUB_TRADE_ID => 4096,
        self::NAME_FZ_ORDER_ID         => 1,
        self::NAME_FZ_PRODUCT_SKU_ID   => 1,
        self::NAME_INVITED_TRANSTOACCOUNT => 1,
        self::NAME_FZ_FANSCONTENT_ID   => 1,
        self::NAME_FZ_USER_ID          => 20,
        self::NAME_EXAM_ANSWER         => 1,
        self::NAME_PAY_REFUND_ORDER_ID => 1,
        self::NAME_ZHIBO_COMMENT_ID    => 1,
        self::NAME_ZB_REFUND_BATCH_ID  => 1,
        self::NAME_PT_COMMENT_ID       => 1,
        self::NAME_PT_COMMENT_RID      => 1,
        self::NAME_FZ_LIVE_ID          => 1,
        self::NAME_PT_COMMENT_SCORE_ID => 1,
        self::NAME_ZB_TRANSACTION_ID   => 4096,
        self::NAME_PT_COUPON_CODE_ID   => 100,
    );

    private $_conf;
    private $_objDaoIdAlloc;
    private $_name;

    public function __construct($name) {
        $this->_conf = null;
        $this->_name = $name;
        $this->_objDaoIdAlloc = null;
        //$this->_init($name);
    }

    private function _init($name){
        if($this->_conf === null){
            $this->_conf = Bd_Conf::getConf("/hk/idalloc");
		}else{
			$name = $this->_name;
		}
        $this->_name = $name;
        if($this->_conf[$name] !== false){
            //检查配置合法性
            $total = self::$_TotalIDXMap[$name];
            if($total > $this->_conf[$name]['maxnum'] && $this->_conf[$name]['maxnum'] >= $this->_conf[$name]['minnum'] && $this->_conf[$name]['minnum'] >= 0){
                $cluster = $this->_conf[$name]['cluster'];
                $this->_objDaoIdAlloc = new Hk_Service_IdAllocDao($cluster);
            }
        }else{
            unset($this->_objDaoIdAlloc);
            $this->_objDaoIdAlloc = null;
        }
    }
    public function getIdAlloc($name = null) {
        if($name !== null && $name !== $this->_name){
			$this->_name = $name;
			if(!is_null($this->_objDaoIdAlloc)){
				$this->_objDaoIdAlloc->closeDB();
				$this->_objDaoIdAlloc = null;
			}
        }else{
            $name = $this->_name;
        }
        if(empty($this->_name)){
            Bd_Log::warning("Error:[param error], Detail:[name:$name]");
            return false;
        }
		Bd_Log::addNotice("idalloc_name", $name);
		if($name == self::NAME_SEARCHRECORD){
			$redisSwitch = 0;
			$arrConf = Bd_Conf::getAppConf("sid/idalloc", 'search');
			if($arrConf['redis_switch'] && isset($arrConf['server']) && count($arrConf['server']) > 0){//以redis为准
				$redisConf =  Bd_Conf::getAppConf('redis/idalloc', 'search');
				//$objIdallocRedis = new Hk_Service_Redis($redisConf['service']);
				$redisSwitch = true;//redis里面存储的switch开关暂且不用了。
				Bd_Log::addNotice('idalloc_idx', 'redis');
				if($redisSwitch){
					$key = $redisConf['keys']['searchrecord_id'];
					//$value = $objIdallocRedis->incr($key);
					$value = $this->getIdAllocFromRedis($key, $arrConf['server']);
					if($value === false){
						Bd_Log::warning("Error:[getRedis incr failed], Detail:[name:$name idx:redis]");
					}
					return $value; //如果请求redis失败也直接返回false
				}elseif($redisSwitch == false){
					Bd_Log::warning("Error:[getRedisSwitch failed], Detail:[name:$name idx:redis]");
					return false;
				}
				//还有一个分支是redisSwitch＝0,则表示以db为准
			}
		}
		$this->_init($name);
		$total = self::$_TotalIDXMap[$this->_name];
        if($total < 1) {
            Bd_Log::warning("Error:[total idx $total error], Detail:[name:$name]");
            return false;
        }
        $min = intval($this->_conf[$name]['minnum']);
        $max = intval($this->_conf[$name]['maxnum']);
        $idx = rand($min, $max);
		Bd_Log::addNotice("idalloc_idx", $idx);
        $this->_objDaoIdAlloc->startTransaction();
        $arrFields = array('id');
        $arrConds  = array(
            'name' => $name,
            'idx'  => $idx,
        );
        $arrAppends = array(
            'FOR UPDATE'
        );
        $ret = $this->_objDaoIdAlloc->getRecordByConds($arrConds, $arrFields, NULL, $arrAppends);
        if(empty($ret)) {
            Bd_Log::warning("Error:[getRecordByConds error], Detail:[name:$name idx:$idx]");
            $this->_objDaoIdAlloc->rollback();
			$this->_objDaoIdAlloc->closeDB();
            return false;
        }

        $id = intval($ret['id']) + $total;
        $arrFields = array(
            'id' => $id,
        );

        $ret = $this->_objDaoIdAlloc->updateByConds($arrConds, $arrFields);
        if(intVal($ret) !== 1) {
            Bd_Log::warning("Error:[updateByConds error], Detail:[name:$name idx:$idx]");
            $this->_objDaoIdAlloc->rollback();
			$this->_objDaoIdAlloc->closeDB();
            return false;
        }

        if($this->_objDaoIdAlloc->commit()) {
			$this->_objDaoIdAlloc->closeDB();
            return $id;
        }
		$this->_objDaoIdAlloc->closeDB();

        return false;
    }
	private function getIdAllocFromRedis($key, $server){
		$serverNum = count($server);
		try{
			$randomValue = mt_rand(0, 100);
			$idx = intval($randomValue % $serverNum);
			$realKey = $key."_".$idx;
			$host = $server[$idx]['host'];
			$port = $server[$idx]['port'];
			//$timeout = empty($server[$idx]['timeout']) ? 0.1 : intval($server[$idx]['timeout']);
			$objRedis = new Redis();
			$objRedis->connect($host, $port, 0.1);
			//$objRedis->ping();
			//$objRedis->select(0);
			$ret = $objRedis->incrBy($realKey, $serverNum);
			if($ret === false || $ret % $serverNum !== $idx){
				Bd_Log::warning($realKey.':'.$ret.' error!');
				$ret = false;
			}
			$objRedis->close();
		}catch(Exception $e){
			Bd_Log::warning('Caught exception: '.$e->getMessage());
			return false;
		}
		return $ret;
	}
}
