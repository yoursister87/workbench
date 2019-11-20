<?php

/**
 * Message对应的控制开关读写基类，所有的具体产品开关 
 * 类都必须继承此基类，并实现预定义的接口。
 *
 * @author guobaoshan@zuoyebang.com
 * @date 2017-05-18
 *
 **/

abstract class Hk_Service_Message_Switch_Base {

    // 查询产品（消息）支持的控制开关集合
    abstract public function getSwitchType($cmdNo = null);
    // 设置开关
    abstract public function setSwitch($uid, $type, $close = 1);
    // 查询开关
    abstract public function getSwitch($uid);
}

?>
