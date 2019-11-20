<?php
/**
 * message 用到的相关配置
 * phplib和message模块都会使用
 *
 * @author yanruitao<yanruitao@zuoyebang.com>
 * @version 1.0
 * @package Zybang
 */

final class Hk_Service_Message_Const
{
    /**
     * 产品名称
     */
    const NAPI_PRODUCT_NAME = "napi";
    const DAYI_PRODUCT_NAME = "dayiteacher";
    const YIKE_PRODUCT_NAME = "airclass";
    const TEACHER_PRODUCT_NAME = "airteacher";
    const PRACTICE_PRODUCT_NAME = "practice";

    /************************************************* 多产品配置 start ***************************************/

    public static $projectClsMaps = [
        "napi" => [
            "switch" => "Hk_Service_Message_Switch_Napi",  #开关实例
            "uniqid" => "Msg_Uniqid_Napi",  #唯一id实例，对应message中的类
            "proxycls" => "Msg_Push_Cmd", # pushproxy类库的前缀
            "store" => [# 存储实例
                "question" => "Hk_Service_Message_Store_NapiQuestion", # Napi问题类的消息存储
                "system" => "Hk_Service_Message_Store_NapiSystem", # Napi系统类的消息存储
                "article" => "Hk_Service_Message_Store_NapiArticle", # Napi文章类的消息存储
                "chat" => "Hk_Service_Message_Store_NapiChat", # Napi 聊天消息
            ]
        ],
        "dayiteacher" => [
            "switch" => "Hk_Service_Message_Switch_Dayi", #开关实例
            "uniqid" => "Msg_Uniqid_Napi", #唯一id实例，对应message中的类
            "proxycls" => "Msg_Push_Cmd", # pushproxy类库的前缀
            "store" => [# 存储实例
//                "question" => "Hk_Service_Message_Store_NapiQuestion", # Napi问题类的消息存储
//                "system" => "Hk_Service_Message_Store_NapiSystem", # Napi系统类的消息存储
//                "article" => "Hk_Service_Message_Store_NapiArticle", # Napi文章类的消息存储
//                "chat" => "Hk_Service_Message_Store_NapiChat", # Napi 聊天消息
            ]
        ],
        "airclass" => [
            "switch" => "Hk_Service_Message_Switch_Yike",
            "uniqid" => "Msg_Uniqid_Napi",
            "proxycls" => "Msg_Push_Cmd",
            "store" => [
                "system" => "Hk_Service_Message_Store_YikeSystem",
            ]
        ],
        "airteacher" => [
            "switch" => "Hk_Service_Message_Switch_Teacher",
            "uniqid" => "Msg_Uniqid_Napi",
            "proxycls" => "Msg_Push_Cmd",
            "store" => [
//                "system" => "Hk_Service_Message_Store_TeacherCheck",
            ]
        ],
        "practice" => [
            "switch" => "Hk_Service_Message_Switch_Practice",
            "uniqid" => "Msg_Uniqid_Napi",
            "proxycls" => "Msg_Push_Cmd",
            "store" => [
//                "system" => "Hk_Service_Message_Store_YikeSystem",
            ]
        ],
    ];

    /************************************************* 多产品配置 end ***************************************/

    /****************************************** 业务命令号 start ***********************/
    /**
     * 支持的命令号列表以及所属的产品，校验参数的时候用
     */
    public static $supportCmdNo = [
        self::DEFINE_REPLYASK           => self::NAPI_PRODUCT_NAME,
        self::DEFINE_NEWREPLY           => self::NAPI_PRODUCT_NAME,
        self::DEFINE_EVALUATE_GOOD      => self::NAPI_PRODUCT_NAME,
        self::DEFINE_INVITE             => self::NAPI_PRODUCT_NAME,
        self::COMMON_SYS_MSG            => self::NAPI_PRODUCT_NAME,
        self::URL_PUSH                  => self::NAPI_PRODUCT_NAME,
        self::HOT_PUSH                  => self::NAPI_PRODUCT_NAME,
        self::BIND_USER_SYSMSG          => self::NAPI_PRODUCT_NAME,
        self::DELETE_QUESTION_SYSMSG    => self::NAPI_PRODUCT_NAME,
        self::DELETE_ARTICLE_SYSMSG     => self::NAPI_PRODUCT_NAME,
        self::DELETE_REPLY_SYSMSG       => self::NAPI_PRODUCT_NAME,
        self::SYS_NOTICE                => self::NAPI_PRODUCT_NAME,
        self::INVITE_EVALUATE           => self::NAPI_PRODUCT_NAME,
        self::THANKS                    => self::NAPI_PRODUCT_NAME,
        self::ARTICLE_REPLY             => self::NAPI_PRODUCT_NAME,
        self::ARTICLE_FLOOR             => self::NAPI_PRODUCT_NAME,
        self::FRIEND_APPLY              => self::NAPI_PRODUCT_NAME,
        self::FRIEND_CHAT               => self::NAPI_PRODUCT_NAME,
        self::NANTIBANGQQ               => self::NAPI_PRODUCT_NAME,
        self::CHARGE                    => self::NAPI_PRODUCT_NAME,
        self::GLOBAL_SYS_MSG            => self::NAPI_PRODUCT_NAME,
        self::ARTICLE_REPLY_JUDGE       => self::NAPI_PRODUCT_NAME,
        self::TASK_FINISH               => self::NAPI_PRODUCT_NAME,
        self::APP_PROTOCOL_JUMP         => self::NAPI_PRODUCT_NAME,
        self::ASK_TEACHER_CHAT_MESSAGE  => self::NAPI_PRODUCT_NAME,
        self::APP_IM_PUSH_UIDS          => self::NAPI_PRODUCT_NAME,
        self::APP_IM_PUSH_CUIDS         => self::NAPI_PRODUCT_NAME,
        self::TEACHER_BOOK_CALENDAR     => self::NAPI_PRODUCT_NAME,
        self::STUDENT_URL_PUSH          => self::NAPI_PRODUCT_NAME,
        self::USER_CENTER               => self::NAPI_PRODUCT_NAME,
        self::WEIKE_COURSE_PUSH         => self::NAPI_PRODUCT_NAME,
        self::WEIKE_TASK_PUSH           => self::NAPI_PRODUCT_NAME,

        self::APP_TEACHER_ONCLASS_PUSH         => self::DAYI_PRODUCT_NAME,
        self::APP_TEACHER_CHAT_MESSAGE_PUSH    => self::DAYI_PRODUCT_NAME,
	    self::CARD_ACTIVITY_PUSH               =>self::NAPI_PRODUCT_NAME,
	    self::STUDENT_PACKAGE_REMIND_PUSH      =>self::NAPI_PRODUCT_NAME,

        self::TEACHER_APP_COURSE_CHECK          => self::TEACHER_PRODUCT_NAME,
        self::TEACHER_APP_ON_CLASS              => self::TEACHER_PRODUCT_NAME,
        self::TEACHER_APP_UP_LECTURE            => self::TEACHER_PRODUCT_NAME,
        self::TEACHER_APP_LECTURE_NOT_PASS      => self::TEACHER_PRODUCT_NAME,
        self::TEACHER_APP_RESERVE_LIVE_ROOM     => self::TEACHER_PRODUCT_NAME,

        self::YILIAN_APP_WEIKE_TASK_PUSH        => self::PRACTICE_PRODUCT_NAME,

    ];

    //之前版本的系统消息
    const DEFINE_REPLYASK = 1;      # Napi问题追问消息
    const DEFINE_NEWREPLY = 2;      # Napi问题回答消息
    const DEFINE_INVITE   = 3;      # Napi问题邀请回答
    const DEFINE_EVALUATE_GOOD = 5;     # Napi回答被采纳——好评
    const COMMON_SYS_MSG = 8;       # Napi通用系统消息
    const URL_PUSH = 10;        # Napi url推送 ===> 改为多app通用url推送
    const HOT_PUSH = 12;        # Napi 帖子推送（根据qid）
    const BIND_USER_SYSMSG = 14;        # Napi 封禁用户
    const DELETE_QUESTION_SYSMSG = 15;       # Napi 管理员删除用户提问
    const DELETE_ARTICLE_SYSMSG = 16;       # Napi 帖子（朋友圈）被管理员删除
    const DELETE_REPLY_SYSMSG = 17;     # Napi回答被删除
    const SYS_NOTICE = 20;      # Napi系统消息（✉️里面，当app在后台的时候会弹出任务栏push）===> 改为多app通用url推送
    const INVITE_EVALUATE = 21;     # Napi邀请回答评价
    const THANKS = 22;      # Napi回答被感谢
    const ARTICLE_REPLY = 25;       # Napi发帖人收到新回复
    const ARTICLE_FLOOR = 26;      # Napi回帖人收到回复
    const FRIEND_APPLY = 28;        # Napi 好友申请
    const FRIEND_CHAT = 29;     # Napi 聊天相关（好友请求，私信）
    const NANTIBANGQQ = 33;     # Napi 难题榜Q币活动
    const CHARGE = 37;      # Napi 收费服务消息（Ucloud模块）
    const GLOBAL_SYS_MSG = 39;  # Napi 全局系统消息 ===> 改为多app通用全局系统消息
    const ARTICLE_REPLY_JUDGE = 40;     # Napi 帖子回复点赞消息

    //新建的系统消息消息号从100开始
    const TASK_FINISH = 100;    # Napi 完成任务
    const APP_PROTOCOL_JUMP = 101;   # Napi 端内跳转 ====> 改为多app通用推送
    const ASK_TEACHER_CHAT_MESSAGE = 102;   # Napi答疑老师端发给学生端消息推送
    const APP_IM_PUSH_CUIDS = 103;   #直播课IM消息push 批量cuid ===> 改为一课、zyb通用
    const APP_IM_PUSH_UIDS = 104;   #直播课IM消息push 批量uid ====> 改为一课、zyb通用
    const TEACHER_BOOK_CALENDAR = 105;   #答疑学生端老师日历页消息推送 批量uids
    const STUDENT_URL_PUSH      = 106;   #答疑学生端URL连接推送      批量uids
    const USER_CENTER           = 107;   #答疑学生端1对1用户中心     批量uids
    const WEIKE_COURSE_PUSH     = 108;   #微课课程推送
    const WEIKE_TASK_PUSH       = 109;   #微课任务推送

    /***************答疑老师app 110 ~ 130 start******************/
    const APP_TEACHER_ONCLASS_PUSH      = 110;   #答疑老师端上课提醒
    const APP_TEACHER_CHAT_MESSAGE_PUSH = 111;   #答疑老师端留言消息
    /***************答疑老师app 110 ~ 130 end******************/
	const CARD_ACTIVITY_PUSH       =112;   #打卡活动未打卡提醒
    const STUDENT_PACKAGE_REMIND_PUSH   = 113;#答疑学生套餐到期push


    /***************一课老师app 131 ~ 150 start******************/
    const TEACHER_APP_COURSE_CHECK        = 131;  #一课老师app端 质检提醒
    const TEACHER_APP_ON_CLASS            = 132;  #一课老师app端 上课提醒
    const TEACHER_APP_UP_LECTURE          = 133;  #一课老师app端 上传讲义提醒
    const TEACHER_APP_LECTURE_NOT_PASS    = 134;  #一课老师app端 讲义审核不通过提醒
    const TEACHER_APP_RESERVE_LIVE_ROOM   = 135;  #一课老师app端 预定直播间提醒

    /***************一练app 151-180******************/
    const YILIAN_APP_WEIKE_TASK_PUSH      = 151;  #微练推送

    /****************************************** 业务命令号 end ***********************/



    /************************************* pushproxy 配置 start **************************/
    // 支持的pushproxy命令号列表
    public static $supportProxyCmdNo = [
        self::PROXY_USER_SEND,
        self::PROXY_BROADCAST,
        self::PROXY_BATCH_CUID_SEND
    ];

    const PROXY_USER_SEND = 3;  // 单个用户消息发送
    const PROXY_BROADCAST = 4;  //广播消息发送，针对bit维度
    const PROXY_BATCH_CUID_SEND = 5;    // 批量设备消息推送


    /**
     * ios apns允许发送的最大长度 
     */
    const APNS_MAX_LEN =2048;

    /**
     * 消息在pushproxy中默认的保存时间
     * 3天
     */
    const DEFAULT_MSG_SAVE_TIME = 259200;

    /**
     * 支持的产品
     */
    public static $products = [
        "napi",
        "dayiteacher",
        "airclass",
        "airteacher",
        "practice",
    ];

    /**
     * 支持的产品 => token 对
     */
    public static $productTokens = [
        "napi" => "napi",
        "dayiteacher" => "dayiteacher",
        "airclass" => "airclass",
        "airteacher" => "airteacher",
        "practice" => "practice",
    ];
    /************************************* pushproxy 配置 end **************************/
}
