<?php
class Hk_Service_FudaoSpam{
    /**
     * @param $content
     * @return bool
     */
    public static function spamJudge($content = '', $commandNo = 500002, $cuid = '', $uid = 0){
        /*$component = "Component_Homework_WordsConfilter::confilter";
        $command = "200031";
        $pid = "homework";
        $params = array();
        $params[] = $command;
        $params[] = $content;
        $params[] = null;
        $postdata['pid'] = $pid;
        $postdata['component'] = $component;
        $postdata['params'] = $params;
        $header = array(
            'pathinfo' => "spamftp/potm",
        );*/
        //新参数
        //command_no  评论500001 讨论500002
        $path =  'spamftp/main';
        $querystring = 'pid=zhibo&token=' . $commandNo . '&mode=strategy&layer=first&unique_key=zhibo_' . $commandNo . '_' . $uid . '_' . intval(Bd_Log::genLogID());
        // $path = $path . '?'. $querystring;
        $header = array(
            'pathinfo' => $path,
            'querystring'=> $querystring,
        );
        $data = array(
            //'pid'     => 'zhibo',
            'content' => $content,
            //'pids'    => '图片pid',  //只可以放一张图片
            'cuid'    => $cuid,
            'uid'     => $uid,
            'command_no' =>$commandNo,
            'uip'     => Bd_Ip::getClientIp(),
        );
        $postdata = array();
        $postdata['data']['fields'] = $data;
        $ret = ral('Spam_Fudao', 'POST', $postdata, 123, $header);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service fudao_spam connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
        }
        //$ret = mc_pack_pack2array($ret);
        $judge = 0;
        if($ret['errno'] == 0){
            $result = $ret['result'];
            $result = json_decode($result, true);
            //1 号码      del命中  default未命中
            //2 过滤词
            //3 url
            /**
             * @des 统一处理线上报警 start
             * @author jiahaijun<jiahaijun@zuoyebang.com>
             * @time 2018/04/09
             */
            if(is_array($result) && !empty($result)){
                foreach($result as $val){
                    if('del' === $val){
                        $judge = 1;
                        break;
                    }
                }
            }

            /**
             * end
             */
        }
        if( (0 === $ret['errno'] && 0 === $judge) || false === $ret ) {
            //spam服务出错时默认返回true 不影响正常聊天功能
            return true;//可用
        } else {
            Bd_Log::warning("Error:[spam match], Detail:[content:$content]");
            return false;
        }

    }


    /**
     * @param $content
     * @param $bitSkip bit位标记（序号从0开始），第1位(DEX=2)表示跳过qq命中；DEX=10（第1位+第3位）表示跳过qq和url命中；
     * @return bool
     */
    public static function spamJudgeForDayiEvaluate($content = '', $commandNo = 600001, $cuid = '', $uid = 0, $bitSkip = 0) {
        /*$component = "Component_Homework_WordsConfilter::confilter";
        $command = "200031";
        $pid = "homework";
        $params = array();
        $params[] = $command;
        $params[] = $content;
        $params[] = null;
        $postdata['pid'] = $pid;
        $postdata['component'] = $component;
        $postdata['params'] = $params;
        $header = array(
            'pathinfo' => "spamftp/potm",
        );*/
        //新参数
        //command_no  学生评价600001
        $path =  'spamftp/main';
        $querystring = 'pid=dayifudao&token=' . $commandNo . '&mode=strategy&layer=first&unique_key=dayifudao_' . $commandNo . '_' . $uid . '_' . intval(Bd_Log::genLogID());
        // $path = $path . '?'. $querystring;
        $header = array(
            'pathinfo' => $path,
            'querystring'=> $querystring,
        );
        $data = array(
            //'pid'     => 'zhibo',
            'content' => $content,
            //'pids'    => '图片pid', //只可以放一张图片
            'cuid'    => $cuid,
            'uid'     => $uid,
            'command_no' =>$commandNo,
            'uip'     => Bd_Ip::getClientIp(),
        );
        $postdata = array();
        $postdata['data']['fields'] = $data;
        $ret = ral('Spam_Fudao', 'POST', $postdata, 123, $header);
        if(false === $ret) {
            $errno           = ral_get_errno();
            $errmsg          = ral_get_error();
            $protocol_status = ral_get_protocol_code();
            Bd_Log::warning("Error:[service fudao_spam connect error], Detail:[errno:$errno errmsg:$errmsg protocol_status:$protocol_status]");
        }
        //$ret = mc_pack_pack2array($ret);
        $judge = 0;
        if(0 === $ret['errno']){
            $result = $ret['result']; //例子：{"1":"del","2":"default","3":"del"}
            $result = json_decode($result, true);
            //del命中  default未命中
            //1 评价里有QQ、手机号
            //2 命中过滤词
            //3 评价里包含url
            foreach($result as $key => $val){
                $intIdx = is_numeric($key)? intval($key) : -1;
                if( (0 <= $intIdx) && (64 >= $intIdx) && (($bitSkip>>$intIdx)&1) ) {
                    continue;
                }
                if('del' === $val){
                    Bd_Log::addNotice("SpamFtpMatch", $key);
                    $judge = 1;
                    break;
                }
            }
        }
        if( (0 === $ret['errno'] && 0 === $judge) || false === $ret ) {
            //spam服务出错时默认返回true 不影响正常聊天功能
            return true;//可用
        } else {
            //Bd_Log::warning("Error:[spam match], Detail:[content:$content]");
            return false;
        }
    }
}
