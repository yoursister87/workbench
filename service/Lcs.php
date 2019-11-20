<?php
/***************************************************************************
 *
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/



/**
 * @file Lcs.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/03/06 13:41:57
 * @brief lcs长连接服务接口类
 *
 **/

class Hk_Service_Lcs {
    //产品信息
    const NAPI_APP_KEY   = 'napi'; //APP唯一标识，接入时分配
    const ZHIBO_APP_KEY  = 'airclass';
	const APP_TOKEN = 'napi'; //APP推送密钥，接入时分配
	static $APP_TOKEN = array(
		self::NAPI_APP_KEY  => 'napi',
		self::ZHIBO_APP_KEY => 'airclass',
	);

    //提交给服务的命令号(1,2已废弃)
    const USER_MSG_SEND_COMMAND_NO           = 3;   //单个用户消息发送,针对用户维度
    const PUBLIC_MSG_SEND_COMMAND_NO         = 4;   //广播消息发送，针对bit维度
    const BATCH_CUID_MSG_SEND_COMMAND_NO     = 5;   //批量设备消息推送
    const SET_GROUP_MSG_TASK_COMMAND_NO      = 17;
    const ANDROID_GROUP_MSG_SEND_COMMAND_NO  = 18;
    const IOS_GROUP_MSG_SEND_COMMAND_NO      = 19;
    const IOS_ALL_MSG_SEND_COMMAND_NO        = 30;
    const ANDROID_ALL_MSG_SEND_COMMAND_NO    = 31;
    const XIAOMI_ALL_MSG_SEND_COMMAND_NO     = 32;//小米推送发广播消息
    const YOUMENG_ALL_MSG_SEND_COMMAND_NO    = 33;//友盟推送发广播消息

    //其他
    const APNS_MAX_LENGRH           = 2048;    //ios apns发送允许最大长度
    const DEFAULT_MESSAGE_SAVE_TIME = 259200;  //86400 * 3

    //ios注册lcs 相关
    const IOS_REGISTER_URL    = "/uregister/api/bind";

	private $lastError = null;		
	const ERROR_TYPE_PARAMS = 1;//参数错误，没有和pushproxy交互，需要debug，或用户请求失败
	const ERROR_TYPE_TALK   = 2;//ral交互失败，错误号是ral返回的交互协议的错误号
	const ERROR_TYPE_SERVER = 3; //后端服务业务逻辑失败，返回的是后端业务端定义的错误号

	public function pushMulti($arrCommand, $appKey = self::NAPI_APP_KEY){
		$arrReq = array();
		$id = 1;
		foreach($arrCommand as $key => $command){
			$commandNo = $command[0];
			$data = $command[1];
			$ret = $this->_check($commandNo, $data);
			if(false === $ret){
				 Bd_Log::warning("Error:[lcs push], Abstract:[param check failed], Detail:[commandNo[$commandNo] data[". json_encode($data). "]]");
				$this->lastError = array(
						'errtype' => self::ERROR_TYPE_PARAMS,
						'errno' => 0,
						'errmsg' => 'param check failed',
						);
				 return false;
			}
			$rpack = $this->_pack($commandNo, $data, $appKey);   // 拼装数据
			$reqId = "req".$id;
			$id++;
			$arrReq[$reqId] = array(
				'pushproxy-homework', 'post', $rpack, LOG_ID		
			);
			if(Hk_Util_Tools::isTestRequest()){
				$this->lastError = null;
				return true;
			}
		}
		if(count($arrReq) > 0){
			//与lcs交互  线下测试改成  lcs-zuoye
			$arrRet = ral_multi($arrReq);
			$errno = ral_get_errno();
			$msg   = ral_get_error();
			if(false === $ret){
				$this->lastError = array(
						'errtype' => self::ERROR_TYPE_TALK,
						'errno' => $errno,
						'errmsg' => $msg,
						);
				Bd_Log::warning("Error:[lcs push], Abstract:[service lcs connect error], Detail:[errno[$errno] msg[$msg]]]");
				return false;
			}
			foreach($arrRet as $reqId => $ret)
			$errno = intVal($ret['err_no']);
			$msg   = $ret['err'];
			if($errno !== 0){
				$this->lastError = array(
						'errtype' => self::ERROR_TYPE_SERVER,
						'errno' => $errno,
						'errmsg' => $msg,
						);
				Bd_Log::warning("Error:[lcs push], Abstract:[push msg failed], Detail:[reqId[$reqId] errno[$errno] msg[$msg]]]");
				return false;
			}
			$this->lastError = null;

			return true;

		}

	}
    //消息推送接口
    public function push($commandNo, $data, $appKey = self::NAPI_APP_KEY){
        //参数检查
        $ret = $this->_check($commandNo, $data);
        if(false === $ret){
            Bd_Log::warning("Error:[lcs push], Abstract:[param check failed], Detail:[commandNo[$commandNo] data[". json_encode($data). "]]");
			$this->lastError = array(
				'errtype' => self::ERROR_TYPE_PARAMS,
				'errno' => 0,
				'errmsg' => 'param check failed',
			);
            return false;
        }

        if(Hk_Util_Tools::isTestRequest()){
				$this->lastError = null;
                return true;
        }

        $rpack = $this->_pack($commandNo, $data, $appKey);   // 拼装数据

        //与lcs交互  线下测试改成  lcs-zuoye
        $ret   = ral('pushproxy-homework', 'post', $rpack, LOG_ID);
        $errno = ral_get_errno();
        $msg   = ral_get_error();
        if(false === $ret){
			$this->lastError = array(
					'errtype' => self::ERROR_TYPE_TALK,
					'errno' => $errno,
					'errmsg' => $msg,
					);
			Bd_Log::warning("Error:[lcs push], Abstract:[service lcs connect error], Detail:[errno[$errno] msg[$msg]]]");
			return false;
		}
		$errno = intVal($ret['err_no']);
        $msg   = $ret['err'];
        if($errno !== 0){
			$this->lastError = array(
					'errtype' => self::ERROR_TYPE_SERVER,
					'errno' => $errno,
					'errmsg' => $msg,
					);
            Bd_Log::warning("Error:[lcs push], Abstract:[push msg failed], Detail:[errno[$errno] msg[$msg]]]");
            return false;
        }
		$this->lastError = null;
        return true;
    }

    //参数检查
    private function _check($commandNo, $data){
        switch($commandNo){
            case self::USER_MSG_SEND_COMMAND_NO:
            case self::PUBLIC_MSG_SEND_COMMAND_NO:
                if(empty($data['apns_short_content']) || strlen($data['apns_short_content']) > self::APNS_MAX_LENGRH){
                    Bd_Log::warning("Error:[lcs push],  Abstract:[invalid param], Detail:[apns_short_content too long]");
                    return false;
                }
                break;
            case self::BATCH_CUID_MSG_SEND_COMMAND_NO:
                if(empty($data['cuids']) || empty($data['content'])){
                    Bd_Log::warning("Error:[lcs push],  Abstract:[invalid param], Detail:[request parameter error]");
                    return false;
                }

                if(isset($data['seconds']) && intval($data['seconds']) < 0){
                    Bd_Log::warning("Error:[lcs push],  Abstract:[invalid param], Detail:[param seconds invalid]");
                    return false;
                }
                break;
            case self::SET_GROUP_MSG_TASK_COMMAND_NO:
                break;
            case self::ANDROID_GROUP_MSG_SEND_COMMAND_NO:
                break;
            case self::IOS_GROUP_MSG_SEND_COMMAND_NO:
                break;
            case self::IOS_ALL_MSG_SEND_COMMAND_NO:
            case self::ANDROID_ALL_MSG_SEND_COMMAND_NO:
			case self::XIAOMI_ALL_MSG_SEND_COMMAND_NO:
			case self::YOUMENG_ALL_MSG_SEND_COMMAND_NO:
                break;
            default:
                Bd_Log::warning("Error:[lcs push],  Abstract:[invalid param], Detail:[param commandNo invalid]");
                return false;
        }

        return true;
    }

    //拼装参数
    private function _pack($commandNo, $data, $appKey){
        $rpack = array();

        //拼装通用参数
        $rpack['app_key']   = $appKey;
        $rpack['app_token'] = self::$APP_TOKEN[$appKey];
        $rpack['seconds']   = isset($data['seconds']) ? intVal($data['seconds']) : self::DEFAULT_MESSAGE_SAVE_TIME;
        $rpack['flag']      = 0; //lcs服务历史原因，固定字段

        //拼装命令参数
        switch($commandNo){
            case self::USER_MSG_SEND_COMMAND_NO:
                $rpack['command_no']         = self::USER_MSG_SEND_COMMAND_NO;
                $rpack['uid']                = $data['ruid'];
                $rpack['chat_flag']          = 0; //固定参数
                $rpack['apns_short_content'] = $data['apns_short_content']; //ios消息体
                $rpack['bit_map_content']    = array(
                    array(
                        //版本控制 -1:all, 12(0011):代表3,4版本推送，1,2版本不推送 后续将改成字符串
                        'bit_map' => empty($data['bit_map']) ? -1 : intval($data['bit_map']),
                        'bit_msg' => $data['content'], //android 消息体
                    ),
                );
                if(isset($data['uids'])){
                    $rpack['uids'] = $data['uids']; //不要超过50个
                }
                break;

            case self::PUBLIC_MSG_SEND_COMMAND_NO:
                $rpack['command_no']         = self::PUBLIC_MSG_SEND_COMMAND_NO;
                $rpack['start_time']         = empty($data['start_time']) ? time() : $data['start_time'];
                $rpack['apns_short_content'] = $data['apns_short_content'];
                $rpack['bit_map_content']    = array(
                    array(
                        'bit_map' => empty($data['bit_map']) ? -1 : intval($data['bit_map']),
                        'bit_msg' => $data['content'],
                    ),
                );
                break;
            case self::BATCH_CUID_MSG_SEND_COMMAND_NO:
                $rpack['command_no']         = self::BATCH_CUID_MSG_SEND_COMMAND_NO;
                $rpack['cuids']              = $data['cuids'];
                $rpack['apns_short_content'] = $data['apns_short_content'];
				if(isset($data['passage'])) {
					$rpack['passage']    = $data['passage']; 
				}
                $rpack['bit_map_content']    = array(
                    array(
                        'bit_map' => empty($data['bit_map']) ? -1 : intval($data['bit_map']),
                        'bit_msg' => $data['content'],
                    ),
                );
                break;
            case self::SET_GROUP_MSG_TASK_COMMAND_NO:
                $rpack['command_no'] = self::SET_GROUP_MSG_TASK_COMMAND_NO;
                $rpack['content'] = $data['content'];
                break;
            case self::ANDROID_GROUP_MSG_SEND_COMMAND_NO:
                $rpack['command_no'] = self::ANDROID_GROUP_MSG_SEND_COMMAND_NO;
                $rpack['content'] = $data['content'];
                $rpack['pushid']  = intval($data['pushid']);
                $rpack['cuids']   = $data['cuids'];
                break;
            case self::IOS_GROUP_MSG_SEND_COMMAND_NO:
                $rpack['command_no'] = self::IOS_GROUP_MSG_SEND_COMMAND_NO;
                $rpack['content'] = $data['content'];
                $rpack['cuids']   = $data['cuids'];
                break;

            case self::IOS_ALL_MSG_SEND_COMMAND_NO:
                $rpack['command_no'] = $commandNo;
                $rpack['pushid']     = intval($data['pushid']);
                $rpack['cuids']      = array();
                $rpack['start_time'] = empty($data['start_time']) ? time() : $data['start_time'];   # 新加日期字段
                $rpack['apns_short_content'] = $data['apns_short_content'];
                break;
            case self::ANDROID_ALL_MSG_SEND_COMMAND_NO:
			case self::XIAOMI_ALL_MSG_SEND_COMMAND_NO:
			case self::YOUMENG_ALL_MSG_SEND_COMMAND_NO:
                $rpack['command_no'] = $commandNo;
                $rpack['pushid']     = intval($data['pushid']);
                $rpack['cuids']      = array();
                $rpack['start_time'] = empty($data['start_time']) ? time() : $data['start_time'];   # 新加日期字段
                $rpack['bit_map_content'] = array(
                    array(
                        'bit_map' => empty($data['bit_map']) ? -1 : intval($data['bit_map']),
                        'bit_msg' => $data['content'],
                    ),
                );
                break;
            default:
                break;
        }
        return $rpack;
    }

    public function registerForIos($data, $appKey = self::NAPI_APP_KEY){
        $cuid       = trim(strVal($data['cuid']));
        $appVersion = trim(strVal($data['appversion']));
        $appVerCode = intVal($data['appvercode']);
        $deviceId   = trim(strVal($data['deviceid']));

        if(strlen($cuid) <= 0 || strlen($appVersion) <= 0 || $appVerCode <= 0 || strlen($deviceId) <= 0){
            Bd_Log::warning("Error:[ios register], Abstract:[param error], Detail:[cuid[$cuid] appversion[$appVersion] appvercode[$appVerCode] deviceId[$deviceId]]");
            return false;
        }
        if(Hk_Util_Tools::isTestRequest()){
                return true;
        }

        $bduss = strVal($_COOKIE['BDUSS']);
        $zybuss = strVal($_COOKIE['ZYBUSS']);

        $service = 'uregister';
        $arrHeader = array(
            'pathinfo' => self::IOS_REGISTER_URL,
            'cookie'   => array('BDUSS' => $bduss,'ZYBUSS' => $zybuss),
        );
        $arrParams = array(
            'appkey'     => $appKey, //产品先唯一标识
            'cuid'       => $cuid, //设备id
            'conntype'   => 'pbcompush', //长连接类型，请填写pbcompush
            'devicetype' => 0, // 设备类型，请填写1
            'appversion' => $appVersion, //当前app版本
            'appvercode' => $appVerCode, //当前版本占据的版本掩码位置，用于消息过滤，从1开始，以后每发一次版本，请加上1
            'token'      => $deviceId, //设备token
        );

        $ret = ral($service, 'POST', $arrParams, rand(), $arrHeader);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service ios $service connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
            return false;
        }

        $arrResult = json_decode($ret, true);
        $errno     = intval($arrResult['err_no']);
        $errmsg    = $arrResult['err_msg'];
        if(intval($errno) !== 0) {
            Bd_Log::warning("Error:[service ios $service rcs process error], Detail:[errno:$errno errmsg:$errmsg]");
            return false;
        }

        return true;
    }

	//获取上一次与pushproxy服务交互的失败日志
	public function getLastError(){
		return $this->lastError;
	}
}
