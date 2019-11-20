<?php
/**
 * @category    library
 * @package     napi
 * @author      com<jiangyingjie@baidu.com>
 * @version     2014/12/1 19:35:09
 * @copyright   Copyright (c) 2014 Baidu.com, Inc. All Rights Reserved
 **/

/**
 * 与Nmq通信的服务类
 */

class Hk_Service_Nmq {

    private $_product = 'zuoye';
    private $_topic   = 'core';

    //检索命令拆分
    private $_maxSearchCommand = 4; //目前拆分为8个命令
    private $_arrSearchConf = array(
        0 => array(
            'command_no' => 0,//Hk_Const_Command::CMD_QUESTION_SEARCH, 
            'topic'      => 'search1',    
        ),
        1 => array(
            'command_no' => 1, //Hk_Const_Command::CMD_QUESTION_SEARCH_1,
            'topic'      => 'search1',    
        ),
        2 => array(
            'command_no' => 2,//Hk_Const_Command::CMD_QUESTION_SEARCH_2,
            'topic'      => 'search2',    
        ),
        3 => array(
            'command_no' => 3,//Hk_Const_Command::CMD_QUESTION_SEARCH_3,
            'topic'      => 'search2',    
        ),
        /*
        4 => array(
            'command_no' => 4,//Hk_Const_Command::CMD_QUESTION_SEARCH_4,
            'topic'      => 'search3',    
        ),
        5 => array(
            'command_no' => 5,//Hk_Const_Command::CMD_QUESTION_SEARCH_5,
            'topic'      => 'search3',    
        ),
        6 => array(
            'command_no' => 6,//Hk_Const_Command::CMD_QUESTION_SEARCH_6,
            'topic'      => 'search4',    
        ),
        7 => array(
            'command_no' => 7,//Hk_Const_Command::CMD_QUESTION_SEARCH_7,
            'topic'      => 'search4',    
        ),*/
    );
    

    //发送命令
    public function talkToQcm($command_no, $data, $topic = "core") {
		/*
		 * 100001 -- 199999 检索
		 * 300001 -- 399999 问答
		 * 400001 -- 499999 圈子
		 * 500001 -- 599999 用户
		 * 600001 -- 699999 其它
		 */ 
		/*
        if(intVal($command_no) < 220001 || intVal($command_no) >= 229999){
            Bd_Log::warning("command_no valid");    
		}*/

        switch($command_no){
            case Hk_Const_Command::CMD_DEL_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_DELETE_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_MDELETE_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_MSET_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_INCR_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_DECR_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_SET_REDIS:
            case Hk_Const_Command::CMD_DOUBLE_SET_CACHE:
            case Hk_Const_Command::CMD_DOUBLE_SET_CACHE_BATCH:
                $topic = 'dw';
                //return true;
                break;
            default:
                break;
        }

        //设置默认topic
        $this->_topic = $topic;

        //检索命令单独处理
        if(Hk_Const_Command::CMD_QUESTION_SEARCH == $command_no || Hk_Const_Command::CMD_QUESTION_SEARCH_NEW == $command_no || Hk_Const_Command::CMD_SEARCH_PICASK_ADD_0 == $command_no){
            //降级 直接丢流量
            $randkey = rand(1,100);
            if($randkey <= 18){
                //    return true;
            }

            //根据sid取模拆分检索命令
            $sid = intval($data['sid']);
            if($sid <= 0){
                Bd_Log::warning("data valid. command_no[$command_no] sid[$sid]");
                return false;
            }

            $searchIndex        = $sid % $this->_maxSearchCommand;
            $searchInfo         = $this->_arrSearchConf[$searchIndex];
            $command_no         = $command_no + $searchInfo['command_no'];
            $data['command_no'] = $command_no;
            $this->_topic       = $searchInfo['topic'];
        }

        $data['command_no'] = $command_no;

        //log filter
        $logData = $data;
        foreach($logData as $logKey => $logVal) {
            if(is_string($logVal)) {
                if(strlen($logVal) > 50 && $logKey != 'resultContent') {
                    $logData[$logKey] = "LongString";
                }
            }elseif(is_array($logVal)){
                if(strlen(json_encode($logVal)) > 50){
                    $logData[$logKey] = "LongString";
                }
            }
        }
        Bd_Log::addNotice ( 'nmq_input'.$command_no, json_encode ( $logData ) );
        $arrRes = $this->sendCommand($command_no, $data);
        if ($arrRes['errno'] != 0) {
            Bd_Log::warning ( "command[$command_no] process failed.input=".var_export($data,true).",result=".var_export($arrRes,true));
            return false;
        }
        Bd_Log::addNotice("command_".$command_no, "ok");
        
        return $arrRes;
    }

    //访问nmq服务
    protected function sendCommand($command_no, $data){
        //设置nmq_proxy接口参数
        $data['_product'] = $this->_product;
        $data['_topic']   = $this->_topic;
        $data['_cmd']     = strval($command_no);
		$strServiceName = 'proxy-zuoye';
		if(Hk_Util_Tools::isTestRequest()){
			$data['skip'] = Hk_Util_Tools::TEST_TAG;
		}

        //设置当前logid
        if(defined('LOG_ID')){
            ral_set_logid(LOG_ID);
        }

        //调用nmq服务
        $intStartTime = microtime(true);
		$arrServiceName = Bd_Conf::getConf("/hk/nmq/service");
		if(is_array($arrServiceName)){
			if(isset($arrServiceName[$this->_topic])){
				$strServiceName = $arrServiceName[$this->_topic]['name'];
				$data['_product'] = $arrServiceName[$this->_topic]['product'];
			}
		}
        $ret = ral($strServiceName, 'post', $data, 1);

        Bd_Log::addNotice('call_nmq_cost', 1000 * (microtime(true) - $intStartTime));
        if(false === $ret){
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("send nmq failed. errno[$errno] errmsg[$errmsg] protocol_status[$protocol_status]");
            return false;
        }

        $ret['errno']     = intVal($ret['_error_no']);
        $ret['error_msg'] = strVal($ret['_error_msg']);
        return $ret;
    }
}

