<?php
/***************************************************************************
 * 
 * Copyright (c) 2016 Zuoyebang.com, Inc. All Rights Reserved
 * 
 **************************************************************************/

/**
 * @file SmsBaidu.php
 * @author liujinghui(liujinghui@zuoyebang.com)
 * @date 2016/02/23
 * @brief 已经下线，不再使用
 *  
 **/

class Hk_Service_Sms_SmsBaidu {
    /**
     * 发送短信验证码
     *
     * @param  int  $phone         手机号
     * @param  int  $randToken     随机验证码
     * @param  int  $availableTime 有效时间（百度暂无）
     * @return bool true/false
     */
    public static function sendRandToken($phone, $randToken, $availableTime = 5) {
        Bd_Log::warning("Error:[smsSend error], Detail:[service unuseful, pls use ral]");
        return fasle;
    }

    /**
     * 按模板ID发送短信-通用接口
     *
     * @param  array  $arrPhone 手机号，多个手机号则用半角逗号分隔
     * @param  array  $arrNode  内容数据
     * @param  string $smsTpl   模板Id
     * @param  bool   $skipCtrl 是否忽略频率控制
     * @return bool true/false
     */
    public static function sendSmsByTemplateId($arrPhone, $arrNode, $smsTpl, $skipCtrl = false) {
        Bd_Log::warning("Error:[smsSend error], Detail:[service unuseful, pls use ral]");
        return fasle;
    }
}
