<?php
/**
 * YikeSystem.php
 *
 * 一课独立app系统消息存储管理类
 * 实现方式与zyb一致，因此直接继承NapiSystem实现
 *
 * @author guobaoshan@zuoyebang.com
 * @version 1.0
 * @package Zybang
 */

class Hk_Service_Message_Store_YikeSystem extends Hk_Service_Message_Store_NapiSystem
{

    // 系统消息后缀key
    public $key = "YIKE_SYSMSG_USER_CONTENT_LIST";

    // 个人用户系统消息数上限
    public $limit = 10;
}
