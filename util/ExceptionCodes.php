<?php
/***************************************************************************
 *
 * Copyright (c) 2013 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * @brief 异常errno以及错误信息定义
 *
 * @filesource phplib/util/ExceptionCodes.php
 * @date 2013/12/06 13:16:27
 **/
class Hk_Util_ExceptionCodes
{


    const OTHER_ERROR = 99999; //其它错误(前端直接展现errstr内容，填坑专用)

    const PARAM_ERROR       = 1; //参数错误
    const NETWORK_ERROR     = 2; //网络错误
    const USER_NOT_LOGIN    = 3; //用户未登录
    const ACTION_CONF_ERROR = 4; //配置文件缺失
    const PARAM_NOT_EXIST   = 5; //参数不存在

    const ANTISPAM_SIGNERR        = 6; //反作弊模块-签名错误
    const ANTISPAM_DATAERR        = 7; //反作弊模块-握手数据错误
    const USER_BEEN_BANNED        = 8; //用户已经被封禁
    const VCODE_NEED              = 9; //需要验证码
    const VCODE_ERROR             = 10; //验证码错误
    const VCODE_GET               = 11; //验证码获取失败
    const IMPORT_INNER_ERROR      = 12; //内部入口权限问题
    const ERRNO_USER_NO_PRIVILEGE = 13; //用户无权限访问
    const INVALID_REQUEST         = 14; //无效请求 如重复请求
    const DB_ERROR                = 15; //数据库错误
    const LBS_API_ERROR           = 16; //LBS API接口错误
    const SUBMIT_REFUSE           = 17; //拒绝提交
    const SYSTEM_CRAZY            = 18; //系统内部错误
    const USER_NOT_PGC            = 19; //不是PGC用户
    const ACTSCTRL_CUID           = 20; //名字cuid频率控制策略
    const ACTSCTRL_UIP            = 21; //名字uip频率控制策略
    const ACTSCTRL_UID            = 22; //名字uid频率控制策略
    const IDALLOC_ERROR           = 23; //id分配器错误
    const UPDATE_ERROR            = 24; //通用update失败
    const SELECT_ERROR            = 25; //通用select失败
    const INSERT_ERROR            = 26; //通用insert失败
    const DELETE_ERROR            = 27; //通用delete失败
    const USER_SESSION_KICKED     = 99;   # 用户session被踢出
    const MULTIIDC_CACHER_WRITE_ERROR      = 100; //异步更新cache错误

    /*********************user 10000-10999*************************/
    const USERINFO_NOT_EXIST        = 10000; //用户尚未注册
    const USERINFO_EXIST            = 10001; //用户已经注册过
    const FAILED_ADD_USER           = 10002; //新增用户失败
    const INVITE_CODE_NOT_EXIST     = 10003; //邀请码不存在
    const UPDATE_AVATAR_ERROR       = 10004; //上传头像失败
    const UPDATE_SEX_ERROT          = 10005; //性别修改失败
    const UPDATE_NAME_ERROT         = 10006; //名字修改失败
    const UPDATE_GRADE_ERROR        = 10007; //修改年级失败
    const UPDATE_NAME_DAILY_ERROR   = 10008; //名字每天只能修改一次
    const GET_PHONE_VCODE_ERROR     = 10009; //获取手机验证码失败
    const BIND_PHONE_ERROR          = 10010; //绑定手机号失败
    const ERRNO_BINDING_NOT_MATCH   = 10011; //手机已经被绑定过或者帐号原有手机号错误
    const ERRNO_VCODE_INVALID       = 10012; //验证码已经失效
    const BAD_PHONE_NUM             = 10013; //无效手机号码
    const HAD_BIND_PHONE            = 10014; //已经绑定过手机号
    const FEED_BACK_ERROR           = 10015; //意见反馈失败
    const HAD_USE_INVITE_CODE       = 10016; //已经使用过邀请码
    const ERROR_BIND_INVITE_CODE    = 10017; //绑定邀请码失败
    const ADD_NEW_INVITE_CODE_ERROR = 10018; //生成邀请码失败
    const PHONE_HAD_BIND            = 10019; //该手机已经绑过其他账号
    const PID_ERROR                 = 10020; //上传的pid不合法
    const FEED_BACK_CONTENT_ERROR   = 10021; //意见反馈内容长度不合格
    const INVITE_CODE_IS_YOUR_SELF  = 10022; //不允许填写自己的邀请码
    const ERR_PHONE_VCODE_TOOMORE   = 10023; //获取验证码次数过多,每天5次
    const NAME_FORM_ERROT           = 10024; //名字格式有误
    const TERMINAL_HAD_INVITECODE   = 10025; //一个设备只能填一次邀请码（防作弊）
    const ACTQQ_SETQQ_ERROR         = 10026; //设置qq号失败
    const BIND_SCHOOL_ERROR         = 10027; //绑定学校失败
    const UNAME_ILLEGAL             = 10028; //用户名非法
    const ERRNO_U_NOT_ADMIN         = 10029; //不是管理员
    const NOT_BIND_SCHOOL           = 10030; //未绑定学校
    const HOMEWORK_ADMIN_ADDFAIL    = 10031; //作业帮管理员添加失败
    const USER_OFF_GPS              = 10032; //用户关闭了附近的人功能 无法获取附近的人
    const CHECKIN_HAS_DONE          = 10033; //签到已经完成
    const BZBIND_ERROR              = 10034; //账号迁移失败

    /*********************图片/问作业/学生圈11000-11999*************************/
    const PIC_IS_NOT_UPLOADED_FILE            = 11000; //非上传文件
    const ERROR_UPLOAD_ERR_INI_SIZE           = 11001; //超过php.ini限制
    const ERROR_UPLOAD_ERR_FORM_SIZE          = 11002; //超过form表单限制
    const ERROR_UPLOAD_ERR_PARTIAL            = 11003; //文件只有部分被上传
    const ERROR_UPLOAD_ERR_NO_FILE            = 11004; //没有文件被上传
    const ERROR_UPLOAD_ERR_NO_TMP_DIR         = 11005; //无临时文件目录
    const ERROR_UPLOAD_ERR_CANT_WRITE         = 11006; //写入失败
    const ERROR_UPLOAD_ERR_EXTENSION          = 11007; //扩展导致图片上传失败
    const ERROR_UPLOAD_EXCEED_MAX             = 11008; //图片大小超过限制
    const ERROR_IS_NOT_A_PICTURE              = 11009; //图片格式错误
    const ERROR_UPLOAD_PIC_FAILED             = 11010; //图片上传失败
    const DATA_EXCEED_MAX_LENGTH              = 11011; //提交内容超过长度限制
    const ASK_ERROR                           = 11012; //提问失败
    const FREE_COUNT_EXCEED_LIMIT             = 11013; //免费提问次数达到限制
    const SEND_MSG_ERROR                      = 11014; //回答提交失败
    const QUESTION_NOT_EXISTS                 = 11015; //问题不存在
    const QUESTION_HAS_SOLVED                 = 11016; //问题已解决
    const ANSWER_SET_GOOD                     = 11017; //问题已设置了好评
    const ERROR_ASK_REPEAT                    = 11018; //提问重复
    const QUALITY_LOW_EMAIL_INVALID           = 11019; //问题包含邮箱地址
    const ERRNO_Q_DELETED_UNCHANGE            = 11020; //问题删除状态不需要更改
    const ERRNO_R_DELETED_UNCHANGE            = 11021; //回答删除状态不需要更改
    const UPDATE_QUESTION_ERROR               = 11022; //更新问题失败
    const QUESTION_HAS_DELETED                = 11023; //问题已被删除
    const USER_HAS_DELETED_QUESTION           = 11024; //问题已被删除
    const ANSWER_HAD_THANKS                   = 11025; //问题已经被感谢过
    const ANSWER_HAD_INVITE                   = 11026; //已经邀请评价
    const EVALUATE_FAILED                     = 11027; //评价失败
    const USER_LEVEL_NOT_ENOUGH               = 11028; //用户等级不够啊
    const USER_WEALTH_NOT_ENOUGH              = 11029; //用户财富值不够啊
    const NOT_GOOD_QUESTION                   = 11030; //不是个好问题 反作弊拒绝
    const ERROR_SPECIAL_EXIST                 = 11031; //特殊帖子已存在
    const ERROR_SPECIAL_NO_EXIST              = 11032; //特殊帖子不存在
    const ERROR_SPECIAL_FULL                  = 11033; //圈子的置顶帖子数量达到上限
    const ERROR_SPECIAL_EXCELLENT             = 11034; //置顶的帖子已经是精华帖子
    const ERROR_SPECIAL_TOP                   = 11035; //设置精华的帖子已经是置顶帖
    const ERROR_CANCEL_SYSTEM_ARTICLE         = 11036; //取消系统置顶帖
    const ERROR_SPECIAL_SETEXCELLENT_ISSYSTOP = 11037; // 设置的精华帖已经是系统置顶贴
    const VOTE_OPTION_HAS_VOTED               = 11038; //投票选项已经投过票
    const VOTE_TYPE_IS_ONE                    = 11039; //投票是单选
    const ERROR_ARTICLE_DELETED               = 11040; //帖子已被删除
    const VOTE_IS_FINISHED                    = 11041; //投票已结束
    const ARTICLE_REPLY_DELETED               = 11042; //回帖已经被删除
    const REWARD_HAS_ADDED                    = 11043; //问题已经追加过悬赏
    const Q_HAS_REPLYCOUNT                    = 11044; //问题已经有回答
    const Q_STATUS_HAS_CHANGED                = 11045; //问老师问题的状态已经变化
    const TEACHER_INFO_NOT_EXISTS             = 11046; //问老师的老师信息不存在
    const ERROR_NOT_ALLOW_TRANSFER            = 11047; //帖子不允许转移
    const PGC_HOLD_QUESTION_OVERLOAD          = 11048; //pgc端用户领取问题超限
    const ERROR_PGC_HOLD_QUESTION             = 11049; //pgc端用户领取问题失败
    const PGC_HOLD_OTHER_QUESTION             = 11050; //pgc端用户领取别人的问题，或领取了非pgc问题
    const API_SEARCH_OVERFLOW                 = 11051; //提问次数超过限制

    /****************************mall 12000-12999***********************************/
    const USE_GIFT_FAILED          = 12000; //使用虚拟道具失败
    const CANCEL_GIFT_FAILED       = 12001; //取消使用虚拟道具失败
    const Mall_GIFT_NOT_EXIST      = 12002; //商城礼品不存在
    const EXCHANGE_LIMIT_NOT_MATCH = 12003; //兑换礼品限制不满足
    const ADD_USER_GIFT_FAILED     = 12004; //给用户添加礼品操作失败
    const MALL_OP_WEALTH_FAILED    = 12005; //商城更新用户财富失败
    const ERRNO_MALL_OTHER         = 12006; //商城操作失败
    const ERRNO_GIFT_NOT_ENOUGH    = 12007; //礼品数量不足
    const GIFT_NOT_EXIST           = 12008; //要设置收货地址的物品不存在
    const HAD_SET_ADDRESS          = 12009; //已经设置过地址
    const SET_ADDRESS_FAILED       = 12010; //设置收货地址失败
    const DEL_MYGIFT_FAILED        = 12011; //删除我的物品失败
    const HAD_SET_NUMBER           = 12012; //已经设置过qq或手机号了
    const MIS_SEND_USER_MORE       = 12013; //发送的用户数量太多了
    const SEND_WEALTH_FAIL         = 12014; //加财富值失败了
    const PHONE_SET_ERROR          = 12015; //手机号修改失败
    const PHONE_SET_HAS_REGISTER   = 12016; //修改的新手机号已经注册过作业帮账号

    /****************************photoshow 13100-13199***********************************/
    const PHOTO_SHOW_HAVE_UPLOADED = 13100;  //用户已经上传过萌图秀 就不能在上传了
    const PHOTO_SHOW_HAVE_BANNED   = 13101;  //用户上传的萌图秀被ban了 就不能在上传了
    const PHOTO_SHOW_NOT_IN_ACT    = 13102;  //不再活动期限内

    /****************************friend 14000-14999***********************************/
    const SYSTEM_USER_NOT_FRIEND     = 14000; //系统用户不能添加为好友
    const APPLY_MESSAGE_NOT_EXISTS   = 14001; //验证消息不存在
    const APPLY_MESSAGE_HAS_ACCEPTED = 14002; //验证消息已接受过了
    const NOT_FRIEND_ANY_MORE        = 14003; //不是好友了
    const CHAT_TO_NOT_FRIEND         = 14004; //聊天时候对方不是好友了
    const ALREADY_BEEN_FRIEND        = 14005; //已经是好友了
    const FRIEND_EXCEED_MAX_NUM      = 14006; //好友数超限
    const IN_YOUR_BLACKLIST          = 14007; //对方在你的黑名单里
    const FRIEND_ID_NOTEXIST         = 14008; //好友邀请暗号不存在
    const FRIEND_ID_YOURSELF         = 14009; //好友邀请暗号是自己的
    const FRIEND_ID_ERROR            = 14010; //好友邀请暗号错误（暗号和uid不匹配）
    const APPLY_ACCEPT_FAILE         = 14011; //添加好友失败

    /****************************activity 15000-15999***********************************/
    const NIUDAN_NOT_START   = 15000; //扭蛋活动没有开始
    const NIUDAN_NO_REMAIN   = 15001; //扭蛋活动没有开始
    const NANTIBANG_QQ_PRIZE = 15002; //难题榜送Q币活动非法兑奖
    const ACTIVITY_EXPIRE    = 15003; //活动已过期

    /****************************task 16000-16999***********************************/
    const TASK_NOT_NULL    = 16000; //任务不存在
    const TASK_NOT_PACKAGE = 16001; //任务新手包不存在
    const TASK_HAS_PACKAGE = 16002; //你已经领过该礼包啦！
    const TASK_NO_PACKAGE  = 16003; //你还没有可以领的礼包哦！

    /****************************voice 17000-17999***********************************/
    const ERRNO_VOICE_SIWTCH      = 17000; //禁用语音
    const ERRNO_VOICE_FILEERR     = 17001; //未找到文件
    const ERRNO_VOICE_SIZE        = 17002; //文件大小超限
    const ERRNO_VOICE_LEN         = 17003; //音频长度超限
    const ERRNO_VOICE_UPLOAD      = 17004; //音频上传失败
    const ERRNO_VOICE_KEYERR      = 17005; //音频Key错误
    const ERRNO_VOICE_DATAEMPTY   = 17006; //音频数据为空(或删除)
    const ERRNO_VOICE_FILETYPEERR = 17007; //音频文件类型错误
    const ERRNO_VOICE_OWNER       = 17008; //音频属主错误

    /***************************tiku 练习 拍题搜索 18000 ~ 18999**************************/
    const ERRNO_DEL_HOMEWORK        = 18000; //删除homework失败
    const ERRNO_UPDATE_PICSTRING    = 18001; //更新QuestionPicString失败
    const ERRNO_UPDATE_HOMEWORK     = 18002; //更新Homework失败
    const ERRNO_ADD_HOMEWORK        = 18003; //增加Homework失败
    const ERRNO_UPDATE_SEARCHRECORD = 18004; //更新SearchRecord失败
    const ERRNO_ADD_SEARCHRECORD    = 18005; //增加SearchRecord失败
    const EXERCISE_EMPTY            = 18006; // 没有练习题
    const ERRNO_SEARCH_FAILE        = 18007; //搜索异常
	const ERRNO_NOT_ENZUOWEN        = 18008; //非英语作文
    const ERRNO_BLUR_ENZUOWEN       = 18009; //错得很离谱，写得很潦草
    const ERRNO_FAILED_ENZUOWEN_OCR  = 18010; //英语作文批改服务失败-ocr
    const ERRNO_FAILED_ENZUOWEN_TEXT = 18011; //英语作文批改服务失败-ocr
    const ERRNO_NOTVALID_ENZUOWEN   = 18012; //不合法用户
	const ERRNO_ACTCTRL_ENZUOWEN    = 18013; //作文批改频率限制-系统级别
	const ERRNO_USERACTCTRL_ENZUOWEN    = 18014; //作文批改频率限制-用户级别

    // const OVER_SEARCH_LIMIT = 18000; //禁用语音

    /***************************答疑模块charge 19000-19999*********************************/
    const CHARGE_TEACHER_NOT_FREE    = 19001; //老师没空
    const CHARGE_TEACHER_NOT_EXIST   = 19002; //老师不存在或者未通过审核
    const CHARGE_QUEUE_NOT_EXIST     = 19003; //学生队列不存在
    const CHARGE_PAYAPI_ERROR        = 19004; //获取支付参数失败
    const CHARGE_LCSPUSH_ERROR       = 19005; //lcs push失败
    const CHARGE_7DAYORDER_ERROR     = 19006; //7天套餐失败
    const CHARGE_GEN_URL_ERROR       = 19007; //生成支付数据失败
    const CHARGE_ZERO_PAY            = 19008; //无需支付
    const CHARGE_COUPON_HAD_PICKUP   = 19009; //优惠券已经领取
    const CHARGE_COUPON_SHARE_EXPIRE = 19010; //优惠券已经领取
    const CHARGE_TEACHER_BANNED      = 19011; //老师被封禁，登录失败
    const DAYI_PACKAGE_INVALID       = 19012; //套餐数据异常
    const DAYI_SERVICE_HAS_STOP      = 19013; //答疑订单已经取消

    /***************************同步练习 20000-20999*********************************/
    const PRACTICE_EVALUATION_EXAM_NOT_EXIST = 20001; //试卷不存在
    const PRACTICE_BOOK_INVALID              = 20002; //书本不存在
    const PRACTICE_RESERVE_FAILED            = 20003; //预约失败
    const PRACTICE_ORDER_FAILED              = 20004; //报名失败
    const PRACTICE_EXAM_FAILED               = 20010; //考试错误
    const PRACTICE_EXAM_REG_FAILED           = 20011; //考试报名失败
    const PRACTICE_EXAM_SUBMIT_FAILED        = 20012; //考试提交失败
    const PRACTICE_NEWEXAM_ADDTEAM_FAILED    = 20020; //加入队伍失败
    const PRACTICE_RELATEBOOK_NOT_FOUND      = 20021; //未找到相关的bookId
    const PRACTICE_SULIAN_DTK_OCR_INCOMPLETE      = 20031; //答题卡拍题不全-ocr
    const PRACTICE_SULIAN_DTK_OCR_NOT_CARD      = 20032; //拍题无效，非答题卡-ocr

    //********************************作业帮session服务 21000-21999*************************
    const SESSION_PHONE_HAS_REGISTER   = 21000; //手机已注册
    const SESSION_PHONE_NOT_REGISTER   = 21001; //手机未注册
    const SESSION_TOKEN_SEND_ERROR     = 21002; //短信验证码发送失败
    const SESSION_TOKEN_CHECK_ERROR    = 21003; //短信验证码校验失败
    const SESSION_PASSWORD_CHECK_ERROR = 21004; //密码校验失败
    const SESSION_LOGIN_ERROR          = 21005; //session对话建立失败
    const SESSION_LOGOUT_ERROR         = 21006; //session对话删除失败
    const SESSION_USER_FORBIDDEN       = 21007; //用户已封禁
    const SESSION_PHONE_REGISTER_BAIDU = 21008; //手机已注册百度账号
    const SESSION_UIDMAP_ERROR         = 21009; //百度账号映射失败
    const SESSION_PHONE_FORMAT_ERROR   = 21010; //手机号码格式错误
    const SESSION_PHONE_BZBIND_ERROR   = 21011; //手机已经绑定过百度账号
    const SESSION_BAIDU_BZBIND_ERROR   = 21012; //百度账号已经绑定过手机
    const SESSION_NEED_REGISTER        = 21013; //请注册后再登陆
    const SESSION_PASSWORD_SET         = 21014; //点击忘记密码，修改密码后重新登录
    const SESSION_PASSWORD_SET_ERROR   = 21015;//密码重置错误
    const SESSION_PHONE_SET_ERROR      = 21016;//绑定手机错误
    const SESSION_EMAIL_NOTVALID       = 21017;//邮箱不合法

    //********************************作业帮sms短信服务 22000-22999*************************
    const SMS_PHONE_ACTS_CTRL         = 22000; //手机号频率控制错误
    const SMS_TEMPLATE_ID_UNAVAILABLE = 22001; //短信模板不可用

    /********************************黑板报 27000-27999 ***********************/
    const BLACK_BOARD_DELETE_ERROR      = 27000;
    const BLACK_BOARD_UPDATE_ERROR      = 27001;
    const BLACK_BOARD_INSERT_ERROR      = 27002;
    const BLACK_BOARD_ADD_FAVOR_ERROR   = 27003;
    const BLACK_BOARD_ADD_PV_ERROR      = 27004;
    const BLACK_BOARD_DANMAKU_SEND_ERROR= 27005;
    const BLACK_BOARD_DANMAKU_SEND_FREQUENTLY = 27006;
    const BLACK_BOARD_DANMAKU_LIST_ERROR = 27007;
    const BLACK_BOARD_DANMAKU_TRIGGER_ANTI = 27008;

    /************************************ mis 后台 30000 - 39999*************************************/
    // MIS相关
    const USER_INFO_NOT_EXIST = 31001;
    const UID_NULL            = 31002;
    const ADMIN_ADD_ERROR     = 31003;
    const ADMIN_EDIT_ERROR    = 31004;
    const ADMIN_EXISTS        = 31005;
    const GROUP_EDIT_ERROR    = 31006;
    const ADMIN_PARAM_ERROR   = 31007;
    const FILE_TYPE_EXIST     = 31008;
    const ERROR_UPLOAD_ERR_MAX_FILE_SIZE = 31009;
    const NO_WARNING_ONLY_SHOW_TIPS      = 31010;

    // 任务相关
    const TASK_FINISH         = 35001; //任务已完成，没有相关数据
    const TASK_NOT_EXIST      = 35002; //任务不存在
    const USER_TASK_NOT_EXIST = 35003; //你没有这个任务或任务已完成

    /************************************ spam占用 40000 - 40999*************************************/
    const SPAM_VCODE_PIC_NETWORK_DOWN    = 40001;   //图片验证码:服务不可用
    const SPAM_VCODE_PIC_GENERATE_FAILED = 40002;   //图片验证码:验证码生成失败
    const SPAM_VCODE_PIC_EXPIRED         = 40003;   //图片验证码:验证码过期
    const SPAM_VCODE_PIC_USED            = 40004;   //图片验证码:验证码已被使用
    const SPAM_VCODE_PIC_CHECK_FAILED    = 40005;   //图片验证码:验证码不正确

    /************************************ 辅导相关 50000 - 59999*************************************/
    //course 相关
    const COURSE_NOT_EXIST             = 50001; //课程不存在
    const COURSE_CHECK_ERROR           = 50002; //课程检查失败
    const LESSON_NOT_EXIST             = 50003; //课节不存在
    const EXERCISE_NOT_EXIST           = 50004; //习题不存在
    const COURSE_IS_START              = 50005; //课程已开始
    const EXERCISE_HAS_DONE            = 50006; //这个学生的习题已经被做过了,不能重复提交
    const EXERCISE_NOT_INCLASS         = 50007; //不是课间习题
    const EXERCISE_NOT_INTIME          = 50008; //这个时间不可以提交习题
    const SET_EXERCISE_ERROR           = 50009; //发布作业失败
    const YESNO_REPEAT                 = 50010; //是否卡重复提交
    const YESNO_TIMEOUT                = 50011; //是否卡超时
    const COURSE_NOT_ONLINE            = 50012; //课程未上线
    const COURSE_NOT_PUBLIC_FREE_PRICE = 50013; //课程非公开课且非免费课
    const COURSE_TIMEOUT               = 50014; //课程报名超时
    const COURSE_ACTIVITY_HAS_ORDER    = 50015; //活动已预约
    const COURSE_NOT_BEGIN             = 50016; //课程未开课
    const COURSE_HAS_END               = 50017; //报名时间结束啦
    const COURSE_HAS_LEAVE             = 50018; //课程已下课
    const COURSE_NOT_END               = 50019; //未到下课时间
    const COURSE_HAS_HOTCOURSE         = 50020; //课程包已是热门课程
    const COURSE_TYPE_FULL             = 50021; //该类型课程已填满
    const HOTCOURSE_NOT_TOONLINE       = 50022; //热门课程禁止下线
    const COURSE_NOT_ALLOW             = 50023; //课程类型不合法
    const COURSE_ORDER_VALID           = 50024; //课程订单校验失败
    const COURSE_HAS_INNER             = 50025; //课程为内部课程
    const FUDAO_PUSH_FAIL              = 50026; //消息推送失败
    const LESSON_HAS_LEAVE             = 50027; //课节已下课
    const CHANGE_CDN_ERR               = 50028; //切换CDN失败
    const EXERCISE_HAS_ADD             = 50029;//习题已添加
    const ORDER_FROZEN_FAILED          = 50030; //冻结处理失败
    const FUDAO_INCLASS_REPEAT_SUMIT   = 50031;//你的账号已有提交记录，请勿重复提交
    const FUDAO_INCLASS_REPEAT_PULL    = 50032;//你的账号已有领取记录，请勿重复领取
    const HOMEWORK_EXERCISE_TEACHER_ERROR = 50033; //作业提交失败，请联系你的班主任老师帮忙解决吧
    const HAS_COURSE_COUPON            = 50034; //用户该课程已发送过优惠券
    const NO_COURSE_COUPON             = 50035; //优惠券金额为零元
    const ADD_COURSE_COUPON_ERROR      = 50036; //优惠券发送失败
    const COURSE_COUPON_INVALID_PHONE  = 50037; //手机号未注册
    const COURSE_COUPON_DUPLICATE      = 50038;//优惠券重复
    const COURSE_CAN_NOT_BUY           = 50039;//无法购买此课程
    const VIDEO_CAN_NOT_RENEWAL        = 50040; //不能重复续期
    const NO_BIND_PAPER                 = 50051; //没有绑定试卷
    const UN_BINDING                    = 50052; //解绑
    const SAVE_ERROR                   = 50053; //保存失败
    const RESUBMIT_ERROR                = 50054; //重复提交

    //teacher 相关
    const TEACHER_NOT_EXIST    = 50100; //教师信息不存在
    const TEACHER_INSERT_ERROR = 50101; //编辑教师信息失败
    const TEACHER_EDIT_ERROR   = 50102; //新增教师信息失败
    const TEACHER_DEL_ERROR    = 50103; //删除教师信息失败
    const TEACHER_CHECK_ERROR  = 50104; //老师检查失败
    const TEACHER_RTMP_ERROR   = 50105; //百度推流开启失败
    const TEACHER_HAS_SIGNIN   = 50106; //主讲端每节课只能签到一次
    const TEACHER_MAC_MATCH    = 50107; //主讲端mac匹配

    //student 相关
    const STUDENT_CHECK_ERROR          = 50200; //学生检查失败
    const STUDENT_NOT_EXIST            = 50201; //学生不存在
    const STUDENT_CAN_NOT_TALK         = 50202; //学生被禁言
    const STUDENT_CLASS_NOT_EXIST      = 50203; //学生不在本班级
    const STUDENT_LEARN_REPORT_NOT_GEN = 50204; //学生的学习报告尚未生成
    const STUDENT_HAD_BUY_COURSE       = 50205; //学生已经购买本课程
    const STUDENT_IS_TEACHER           = 50206; //该账号为老师账号,禁止报名

    //assistant 相关
    const ASSISTANT_NOT_IN_COURSE               = 50300; //班主任没有这个课程
    const ASSISTANT_NOT_IN_CLASS                = 50301; //班主任没有这个课程班级
    const ASSISTANT_NOT_EXIST                   = 50302; //班主任信息不存在
    const TEACHER_NOT_ASSISTANT                 = 50303; //老师不是班主任
    const ASSISTANT_COURSE_NOT_EXIST            = 50304; //班主任课程不存在
    const ASSISTANT_CLASS_NO_STUDENT            = 50305; //班主任班级没有学生
    const ASSISTANT_NO_PRIVILEGE_THIS_STUDENT   = 50306; //班主任没有当前学生的权限
    const ASSISTANT_NO_PRIVILEGE_THIS_INTERVIEW = 50307; //班主任没有当前访谈的修改权限
    const ASSISTANT_NO_PRIVILEGE_THIS_SCORE     = 50308; //班主任没有当前成绩的修改权限
    const ASSISTANT_HAS_NO_STUDENT              = 50309; //班主任没有学生
    const ASSISTANT_STUDENT_EXEED               = 50310; //班主任名额已报满

    //LCS
    const LCS_SEND_MSG_ERROR = 50401; //课间习题推送失败
    //LECTURE 相关
    const LECTURE_CREATE_ERROR    = 50501; //讲义创建失败
    const LECTURE_GETINFO_ERROR   = 50502; //讲义信息获取失败
    const LECTURE_GETLIST_ERROR   = 50503; //讲义列表获取失败
    const LECTURE_CANT_EDIT_ERROR = 50504; //老师操作非自己的讲义
    const LECTURE_DELETE_ERROR    = 50505; //讲义删除失败
    const LECTURE_SAVE_ERROR      = 50506; //讲义保存失败
    const LECTURE_SAVE_AS_ERROR   = 50507; //讲义另存失败
    const LECTURE_ID_ALLOC_ERROR  = 50508; //讲义id生成失败
    //OTHERPOINT 相关
    const OTHERPOINT_GET_CNT_ERROR       = 50601;//获取学而思讲义数量失败
    const OTHERPOINT_ADD_TID_ERROR       = 50602;//添加学而思讲义tid失败
    const OTHERPOINT_GET_INFO_ERROR      = 50603;//获取学而思讲义信息失败
    const OTHERPOINT_CONFIRM_CHECK_ERROR = 50604;//获取学而思讲义复查提交失败
    //选课清单
    const SHOPPING_CART_IS_FULL  = 50701;//选课单已满
    const SHOPPING_CART_IS_LIMIT = 50702;//选课单冲突(优惠课)

    //活动相关
    const ACTIVITY_ACCEPT_INVITE_ERROR     = 50801;
    const ACTIVITY_EXCHANGE_COUPON_ERROR   = 50802;
    const ACTIVITY_HAS_ACCEPT_COUPON_ERROR = 50803;
    const ACTIVITY_DATI_TIME_EXPIRE        = 50804;
    const ACTIVITY_DATI_USER_INVALID       = 50805;
    const ACTIVITY_DATI_USER_NO_PRIVILEGE  = 50806;
    const ACTIVITY_DATI_HAS_APPOINT        = 50807;
    const ACTIVITY_DATI_CUL_BONUS_ERROR    = 50808;

    //业务订单相关
    const TRADE_NOT_PAYED_STATUS = 50901; //非支付成功状态
    //端内答疑
    const MENTORING_COURSE_ASK_MAX_ERROR    = 51000;//课程提问数量达到上限
    const MENTORING_STUDENT_IS_BANNED_ERROR = 51001;//学生被拉黑
    const MENTORING_STUDENT_ASK_MAX_ERROR   = 51992;//学生提问数量达到上限
    //学分相关
    const SCORE_COURSE_CHECK_ERROR            = 52000;//课程检查失败，该课程不计分
    const SCORE_NO_VALID_RED_ENVELOPE         = 52001;//无可领取的学分红包
    const SCORE_RED_ENVELOPE_RECEIVE_ERROR    = 52002;//学分红包领取失败
    const SCORE_SEND_RED_ENVELOPE_ERROR       = 52003;//课程结束，不可发学分红包
    const SCORE_SEND_RED_ENVELOPE_MAX_ERROR   = 52004;//学分红包发送数量已达到上线
    const SCORE_EX_RED_ENVELOPE_NOT_END_ERROR = 52005;//上一个学分红包还未结束
    const SCORE_ADD_SCORE_ERROR               = 52006;//添加学分失败
    const SCORE_HAS_ALREADY_ADD_ERROR         = 52007;//已添加的学分不能重复添加
    const SCORE_PRODUCT_TYPE_NOT_EXIST_ERROR  = 52008;//学分商品不存在
    const SCORE_NOT_SUFFICIENT_FUNDS          = 52009;//学分余额不足
    const SCORE_EXCHANGE_PRODUCT_ERROR        = 52010;//兑换失败
    const SCORE_NO_VALID_INCLASSSIGN          = 52011;//无可领取的积分
    const SCORE_NO_SCORE_PRODUCT_INSERT_FUCTION = 52012;//商品添加失败
    //视频回放相关
    const VIDEO_SIGN_EXCEED_LIMIT = 52901;//标记添加超过限制
    //kunpeng相关
    const KP_STAFF_NOT_EXIST              = 53001; //抱歉，此账号未开通权限
    const KP_STAFF_WEIXIN_NOT_EXIST       = 53002; //抱歉，此账号没有关联的微信
    const KP_GET_STAFFINFO_ERROR          = 53003; //账号信息获取失败，请退出重试
    const KP_GET_STAFFWEIXIN_ERROR        = 53004; //微信信息获取失败，请退出重试
    const KP_GET_STUDENTWEIXIN_ERROR      = 53005; //学生微信获取失败，请重试
    const KP_NOT_INTERNAL_NETWORK_ERROR   = 53006; //内部页面，禁止使用外网访问
    const KP_GET_STAFF_WXFRIEND_ERROR     = 53007; //好友列表获取失败，请重试
    const KP_GET_STAFF_WXFRIENDINFO_ERROR = 53008; //好友详细资料获取失败，请重试
    const KP_GET_MSGTASK_TOSENDTIME_ERROR = 53009; //获取群发任务发送时间失败，请重试

    //biz相关
    const BIZ_GET_BIZ_INFO_ERROR         = 54000;//获取营销信息失败
    const BIZ_SKU_CAN_NOT_BUY            = 54001;//商品不可购买
    const SKU_NOT_EXIST                  = 54002;//商品不存在
    const SKU_ALREADY_DEPOSIT            = 54003;//商品已预订

    /************************************ IM相关 59001 - 59599*************************************/
    const IM_USER_STATUS_ERROR          = 59001; //IM用户状态异常
    const IM_GROUP_STATUS_ERROR         = 59002; //IM群组状态异常
    const IM_GROUP_USER_STATUS_ERROR    = 59003; //IM群组用户关系异常
    const IM_GROUP_USER_PRIVILEGE_ERROR = 59004; //IM群组用户权限异常
    const IM_MESSAGE_TYPE_ERROR         = 59005; //IM消息发送类型错误
    const IM_MESSAGE_UIDLIST_ERROR      = 59006; //IM消息发送用户列表错误
    const IM_MESSAGE_CONTENT_ERROR      = 59007; //IM消息发送内容错误
    const IM_MESSAGE_SEND_ERROR         = 59008; //IM消息发送异常
    const IM_GROUP_NOT_EXIST            = 59009; //群不存在
    const IM_HAS_IN_GROUP               = 59010; //已经在群里
    const IM_HAS_NOT_IN_GROUP           = 59011; //已经不在群里
    const IM_JOIN_GROUP_ERROR           = 59012; //加入群失败
    const IM_OUT_GROUP_ERROR            = 59013; //退出群失败
    const IM_SPAM_CHAT_ERROR            = 59014; //内容命中SPAM
    const IM_GROUP_FULL_ERROR           = 59015; //群已满
    const IM_GROUP_HAS_KICKOFF          = 59016; //已被踢出群
    const IM_HAS_IN_OTHER_GROUP         = 59017; //已经加入老师的另外一个粉丝群
    const IM_NOT_BUY_NOT_JOIN           = 59018; //没有买过老师课不允许加入老师的粉丝群
    const IM_NOT_IN_GROUP               = 59019; //用户不在群内
    const IM_MESSAGE_DELETE_ERROR      = 59020; //撤回消息失败
    const IM_MESSAGE_DELETE_TIMEOUT    = 59021; //消息撤回超时
    const IM_GROUP_JOIN_OVER_FREQUENCY = 59022; //加群太频繁
    const IM_CANNOT_TALK_WITH_SELF     = 59023; //禁止和自己聊天
    const IM_NO_RIGHT_TO_OPEN_PRIVATE_CHAT = 59024; //无权开启私聊
    const IM_OPEN_PRIVATE_CHAT_FAILED = 59025; //开启私聊失败
    const IM_MEMBER_APP_VERSION_LOW     = 59026; //对方软件版本过低
    const IM_MEMBER_STATUS_NOT_SUITABLE = 59027; //成员状态不适合私聊
    const IM_MEMBER_NOT_IN_GROUP         = 59028; //该用户已退群，无法获取信息
    const IM_CLOSE_PRIVATE_CHAT_FAILED  = 59029; //关闭私聊失败
    const IM_VOICE_CONVERT_NOT_FINISHED = 59030; //转码未完成
    const IM_EXERCISE_GROUP_DELETE = 59031; //群作业被删除
    const IM_MESSAGE_TYPE_NOT_ALLOWED      = 59032; //消息类型不被支持
    const IM_SEND_MSG_FREQUENCY = 59033; //发送消息太频繁

    /************************************ santiao相关 59600 - 59999*************************************/
    const SANTIAO_IDCARD_CHECK_ERROR = 59600;       //身份证号校验算法校验失败
    const SANTIAO_IDCARD_CHECKAPI_ERROR = 59601;    //身份证API校验失败
    const SANTIAO_MONEY_NOT_ENOUGH = 59602;         //余额需要大于30元才能体现，请继续加油


    /***************************淘金项目 60000-60999*********************************/
    /*************************** 这块错误号，可以检查后回收了 *********************************/
    const BIZ_MERCHANT_BOOKING_FAILED = 60001; // 预约失败
    const BIZ_MERCHANT_BOOKING_EXIST  = 60002; // 重复预约

    /***************************答疑讨论区 61000-61999*********************************/
    const DISCUSS_NO_ADMIN_PERMISSION        = 61001; // 没有管理员权限
    const DISCUSS_POST_NOT_EXIST             = 61041; // 帖子不存在
    const DISCUSS_IMG_NOT_EXISTS             = 61042; // 图片不存在
    const DISCUSS_WORD_OVER_MAX_LEN          = 61043; // 字数超出上限
    const DISCUSS_POST_HASNOT_BUY_DAYI       = 61044; // 未购买答疑服务不能发帖
    const DISCUSS_POSTLIST_ALREADY_TOP       = 61101; // 帖子列表-已为置顶帖
    const DISCUSS_POSTLIST_ISNOT_TOP         = 61102; // 帖子列表-并非置顶帖
    const DISCUSS_POSTLIST_SETTOP_FAIL       = 61103; // 帖子列表-设置置顶失败
    const DISCUSS_POST_FAILED                = 61104; // 发帖-发帖失败
    const DISCUSS_DEL_FAILED                 = 61105; // 管理员-删帖失败
    const DISCUSS_POSTLIST_SETRECOMMEND_FAIL = 61106; // 帖子列表-设置推荐首页失败
    const DISCUSS_POST_TRANS_CATEGORY_FAIL   = 61107; // 帖子&列表-转移板块失败

    /***************************答疑约课62000-63999*********************************/
    const RESERVE_PERIOD_CAN_NOT_OPEN      = 62001; // 预约时段不能开放
    const RESERVE_DATE_ERROR               = 62003; // 预约日期错误
    const RESERVE_PERIOD_ERROR             = 62004; // 预约时段错误
    const RESERVE_PERIOD_HAD_OPEN          = 62005; // 时段已开放
    const RESERVE_PERIOD_NOT_FOUND         = 62006; // 时段记录没有找到
    const RESERVE_PERIOD_CAN_NOT_CLOSE     = 62007; // 时段不能被关闭
    const RESERVE_PERIOD_CAN_NOT_CANCEL    = 62008; // 时段不能被取消
    const RESERVE_ORDER_NOT_FOUND          = 62009; // 预约订单没有找到
    const RESERVE_PERIOD_ADJUST_ERROR      = 62010; // 调课时段错误
    const RESERVE_PRRIOD_ADJUST_CAN_NOT_OP = 62011; // 时段不能调整
    const RESERVE_SERIAL_ACT_NOT_FOUND     = 62012; // 系列课活动不存在
    const RESERVE_PERIOD_ADJUST_EXPIRE     = 62013; // 调课申请过期
    const RESERVE_OPEN_OPERID_FAILED       = 62014; // 开放时段失败
    const RESERVE_REJECT_ADJUST_FAILED     = 62015; // 拒绝调课失败

    const RESERVE_NOT_EXIST    = 62030; //预约不存在
    const RESERVE_DATA_INVALID = 62031; //预约不可用-数据错误
    const RESERVE_TIME_INVALID = 62032; //预约不可用-未达到开放时段
    const RESERVE_NOT_BELONG   = 62033; //预约并非归属用户

    const RESERVE_SERVICE_CREATE_FAIL       = 62101; //预约-房间创建失败
    const RESERVE_SERVICE_ARRANGE_FAIL      = 62102; //预约-房间安排失败
    const RESERVE_SERVICE_START_FAIL        = 62103; //预约-答疑开始失败
    const RESERVE_SERVICE_STUDENT_NOT_ENTRY = 62104; //预约-老师未进入房间
    const RESERVE_SERVICE_TEACHER_NOT_ENTRY = 62105; //预约-老师未进入房间

    /***************************答疑辅导64000-65999*********************************/
    const PROPER_NOT_EXIST    = 64101; //辅导课不存在
    const PROPER_DATA_INVALID = 64102; //辅导课不可用-数据错误
    const PROPER_NOT_BELONG   = 64103; //辅导课并非归属用户

    const PROPER_ORDER_NOT_FOUND = 64201; // 辅导课订单没有找到
    const PROPER_TIME_INVALID    = 64202; // 辅导课不可用-未达到开放时段

    const FILE_UPLOAD_ERROR      = 65001; //上传失败！请检查网络，重新上传
    const FILE_NOT_PDF           = 65002; //请上传PDF格式的讲义
    const FILE_PDF_PAGE_ERROR    = 65003; //讲义页数不能超过60页，请适当精减讲义内容
    const FILE_PDF_SIZE_ERROR    = 65004; //请上传大小不超过30M的文件
    const FILE_PDF_COACH_TIMEOUT = 65005; //上传失败！您已超过讲义上传规定时间，如有问题请联系管理员
    const FILE_NOT_EXIST         = 65006; //文件不存在
    const FILE_CNT_LIMIT         = 65007; //超过文件最大数量

    const PROPER_SERVICE_CREATE_FAIL       = 64301; //辅导课-房间创建失败
    const PROPER_SERVICE_ARRANGE_FAIL      = 64302; //辅导课-房间安排失败
    const PROPER_SERVICE_START_FAIL        = 64303; //辅导课-答疑开始失败
    const PROPER_SERVICE_STUDENT_NOT_ENTRY = 64304; //辅导课-老师未进入房间
    const PROPER_SERVICE_TEACHER_NOT_ENTRY = 64305; //辅导课-老师未进入房间
    const PROPER_SERVICE_CAN_NOT_CANCEL    = 64306; //辅导课-课程不可以取消

    /***************************支付相关 70000-70999*********************************/
    const PAY_TOKEN_ERROR       = 70001; //交互凭证错误
    const ORDER_NOT_EXIST       = 70002; //订单不存在
    const PAY_STATUS_INVALID    = 70003; //支付状态异常
    const PAY_PAY_ERROR         = 70004; //支付异常
    const PAY_REFUNDFEE_INVALID = 70005; //退款金额非法
    const PAY_REFUND_ERROR      = 70006; //退款失败
    const ORDER_HAS_FROZE       = 70007; //订单已冻结
    const ORDER_COURSE_END      = 70008;//课程已结束，不能冻结
    const ORDER_ISNOT_TOPAY     = 70009;//课程不是待支付状态
    const PRODUCT_NOTEXIST      = 70010; //商品不存在
    const PAY_REFUND_DUP        = 70011; //重复退款
    const CREATE_ORDER_FAILED   = 70012; //创建订单失败
    const PAY_URL_EXPIRED       = 70013; //支付链接过期
    const PAY_DECR_COIN_FAIL    = 70014; //扣余额失败
    const PAY_PAYCALLBACK_FAIL  = 70015; //支付成功回调产品线失败

    /*************************优惠券相关 80000-80999**********************************/
    const NEWCOUPON_TOKEN_ERROR        = 800001; //交互凭证错误
    const NEWCOUPON_CODE_TIMEOUT       = 800002; //优惠码过期
    const NEWCOUPON_TIMEOUT            = 800003; //优惠券过期
    const NEWCOUPON_CODE_UNUSED        = 800004; //优惠码已不可用
    const NEWCOUPON_CODE_USERUSED      = 800005; //同一用户户或设备不能重复领取
    const NEWCOUPON_CODE_CALLBACK_ERR  = 800006; //回调全部失败
    const NEWCOUPON_CODE_RECORD_ERR    = 800007; //优惠码兑换记录插入失败
    const NEWCOUPON_CODE_SUBSTRACT_ERR = 800008; //优惠码剩余数量更新失败

    /*************************作业帮天梯赛游戏占用 81000-81999**********************************/

    /*************************独立app啄木鸟查查占用 82000-84999**********************************/

    /*************************广告系统adx占用 85000-85999**********************************/

    /*************************平台增值服务占用 86000-87999**********************************/

    /*************************独立app快对作业占用 830001-839999**********************************/
    const CODESEARCH_UNLOGIN_ACTSCTRL   = 830001;

    /************************第三方登入相关 85000-85999*********************************/
    const SESSION_OUID_HAS_REGISTER  = 850001; //OUID已注册成功
    const SESSION_OUID_NOT_EXIST     = 850002; //ouid记录不存在
    const SESSION_PHONE_ALREADY_BIND = 850003; //该手机号已绑定此第三方
    const SESSION_OAUTH_TOO_LONG     = 850004; //注册超时
    const SESSION_OAUTH_TYPE_ERROR   = 850005;//第三方类型错误
    const BIND_USER_ERROR            = 850006;//绑定手机号失败

    /************************第三方用户服务相关 86000-86999*********************************/
    const PARTNER_READ_CONF_ERROR    = 860001; //读取配置失败
    const PARTNER_TOKEN_CHECK_ERROR  = 860002; //token校验失败
    const PARTNER_GET_USERINFO_ERROR = 860003; //获取用户信息失败
    const PARTNER_GENERATE_TOKEN_ERROR= 860004; //生成token失败

    /************************** 一课app相关   870000-879999********************************/
    const RESTART_TIME_EMPTY_ERROR    = 870001;
    const RESTART_TIME_LIMIT_ERROR    = 870002;
    const RESTART_REASON_LONGTH_LIMIT = 870003;
    const RESTART_REASON_EMPTY_ERROR  = 870004;

    /************************** app版本相关   880000-889999********************************/
    const APP_VERSION_OLD_ERROR     = 880001;

    /************************** 新项目相关   890000-899999*********************************/
    const FZ_PRODUCT_NOT_EXIST = 890001;

    /************************** 课程基础数据 900001-900999*********************************/
    const ADD_COURSESYSTEM_FAIL                = 900001;
    const ADD_CLASSTYPE_FAIL                   = 900002;
    const ADD_BOOKVER_FAIL                     = 900003;
    const ADD_COURSEMODULE_FAIL                = 900004;
    const COURSESYSTEM_REPEAT                  = 900005;
    const CLASSTYPE_REPEAT                     = 900006;
    const BOOKVER_REPAET                       = 900007;
    const COURSEMODULE_REPEAT                  = 900008;
    const COURSEMODULE_RELATION_REPEAT         = 900009;

    /************************** 老师说相关 901000-901099*********************************/
    const TEACHERSAY_MESSAGE_NOT_EXIST          = 901000;//动态已删除
    const CONTAINS_SENSITIVE_WORK                = 901001;//您发表内容包含敏感词汇
	const GET_STUDENT_XUEBU_FAIL                 = 901002;//获取学生的学部信息失败
	const TEACHERSAY_COMMENT_NOT_EXIST          = 901003;//评论信息已删除
	const COMMENT_NOT_EMPTY                      = 901004;//发布内容不能为空
	const COMMENT_EXCEED_LIMIT                  = 901005;//发布内容超出限定字数
	const COMMENT_NOT_CAN_REPLY                 = 901006;//您不能给自己的评论进行回复
	const COMMENT_UNAUTHORIZED_OPERATION       = 901007;//您无权进行当前操作
	const TOP_MSG_EXCEED_LIMIT                  = 9010078;//置顶动态已达到上限

    public static $errMsg = array(
        self::OTHER_ERROR         => '未知错误',
        self::PARAM_ERROR         => 'param error',
        self::NETWORK_ERROR       => 'network error',
        self::USER_NOT_LOGIN      => '登录过期，请重新登录',
        self::USER_NOT_PGC        => '不是PGC用户',
        self::ACTSCTRL_CUID       => '名字cuid频率控制策略',
        self::ACTSCTRL_UIP        => '名字uip频率控制策略',
        self::ACTSCTRL_UID        => '名字uid频率控制策略',
        self::IDALLOC_ERROR       => 'idalloc servic error',
        self::INSERT_ERROR        => '添加数据失败',
        self::UPDATE_ERROR        => '数据更新失败',
        self::SELECT_ERROR        => '获取数据失败',
        self::DELETE_ERROR        => '删除数据失败',
        self::USER_SESSION_KICKED => '你的帐号已在别处登录，点击重新登录后可继续使用',
        self::MULTIIDC_CACHER_WRITE_ERROR => '多机房异步更新cache失败',

        self::ACTION_CONF_ERROR                     => 'read action conf error',
        self::PARAM_NOT_EXIST                       => 'miss some params',
        self::ANTISPAM_SIGNERR                      => 'antispam sign error',
        self::ANTISPAM_DATAERR                      => 'antispam data error',
        self::USER_BEEN_BANNED                      => '您已被封禁,有疑问请到作业帮吧反馈',
        self::USERINFO_NOT_EXIST                    => 'user not exist,please register',
        self::USERINFO_EXIST                        => 'user had exist',
        self::FAILED_ADD_USER                       => 'failed add user',
        self::INVITE_CODE_NOT_EXIST                 => 'invitecode not exit',
        self::PIC_IS_NOT_UPLOADED_FILE              => 'pic is not uploaded file',
        self::ERROR_UPLOAD_ERR_INI_SIZE             => 'exceeds the upload_max_filesize',
        self::ERROR_UPLOAD_ERR_FORM_SIZE            => 'exceeds the MAX_FILE_SIZE',
        self::ERROR_UPLOAD_ERR_PARTIAL              => 'file was only partially uploaded',
        self::ERROR_UPLOAD_ERR_NO_FILE              => 'No file was uploaded',
        self::ERROR_UPLOAD_ERR_NO_TMP_DIR           => 'Missing a temporary folder',
        self::ERROR_UPLOAD_ERR_CANT_WRITE           => 'Failed to write file todisk',
        self::ERROR_UPLOAD_ERR_EXTENSION            => 'A PHP extension stopped the file upload.',
        self::ERROR_UPLOAD_EXCEED_MAX               => 'exceeds the pic size',
        self::ERROR_IS_NOT_A_PICTURE                => 'is not a picture',
        self::ERROR_UPLOAD_PIC_FAILED               => 'upload picture failed',
        self::UPDATE_AVATAR_ERROR                   => 'update avatar error',
        self::UPDATE_SEX_ERROT                      => 'sex update failed',
        self::UPDATE_NAME_ERROT                     => 'name update failed',
        self::DATA_EXCEED_MAX_LENGTH                => 'data exceed max length',
        self::ASK_ERROR                             => 'ask error',
        self::VCODE_NEED                            => 'need vcode',
        self::VCODE_ERROR                           => 'vcode error',
        self::NAME_FORM_ERROT                       => 'name form error',
        self::FREE_COUNT_EXCEED_LIMIT               => 'free count exceed limit',
        self::SEND_MSG_ERROR                        => 'add reply failed',
        self::UPDATE_GRADE_ERROR                    => 'update grade failed',
        self::QUESTION_NOT_EXISTS                   => 'question not exists',
        self::UPDATE_NAME_DAILY_ERROR               => 'the name can only be modified once a day',
        self::QUESTION_HAS_SOLVED                   => 'question has been solved',
        self::ANSWER_SET_GOOD                       => 'answer set good',
        self::ERROR_ASK_REPEAT                      => 'ask question repeat',
        self::QUALITY_LOW_EMAIL_INVALID             => 'content include email address',
        self::GET_PHONE_VCODE_ERROR                 => 'get phone vcode failed',
        self::BIND_PHONE_ERROR                      => 'bind phone failed',
        self::ERRNO_BINDING_NOT_MATCH               => 'phone num had binded',
        self::ERRNO_VCODE_INVALID                   => 'vcode had lose efficacy',
        self::BAD_PHONE_NUM                         => 'your phone num is invalid',
        self::HAD_BIND_PHONE                        => 'user had bind phone',
        self::FEED_BACK_ERROR                       => 'feed back failed',
        self::ERRNO_Q_DELETED_UNCHANGE              => 'question deleted no need change',
        self::ERRNO_R_DELETED_UNCHANGE              => 'reply deleted no need change',
        self::UPDATE_QUESTION_ERROR                 => 'update question failed',
        self::VCODE_GET                             => 'vcode get failed',
        self::IMPORT_INNER_ERROR                    => 'import not allow',
        self::HAD_USE_INVITE_CODE                   => 'user had use invitecode',
        self::ERROR_BIND_INVITE_CODE                => 'bind invite code failed',
        self::QUESTION_HAS_DELETED                  => 'question has deleted',
        self::ADD_NEW_INVITE_CODE_ERROR             => 'get new invite code failed',
        self::PHONE_HAD_BIND                        => 'your phone had bind other user',
        self::PID_ERROR                             => 'please check your pid',
        self::FEED_BACK_CONTENT_ERROR               => 'please check your content length',
        self::INVITE_CODE_IS_YOUR_SELF              => 'not allow set yourself invite code',
        self::ERR_PHONE_VCODE_TOOMORE               => 'get phone vcode too more , 5th a day',
        self::USER_HAS_DELETED_QUESTION             => 'user has deleted question',
        self::TERMINAL_HAD_INVITECODE               => 'a terminal just can set invite code once',
        self::USE_GIFT_FAILED                       => 'use virtual gift failed!',
        self::CANCEL_GIFT_FAILED                    => 'cancel use gift failed!',
        self::Mall_GIFT_NOT_EXIST                   => 'mall gift not exist',
        self::EXCHANGE_LIMIT_NOT_MATCH              => 'gift exchange limitation not match',
        self::ADD_USER_GIFT_FAILED                  => 'add user gift failed',
        self::MALL_OP_WEALTH_FAILED                 => 'mall operate wealth failed',
        self::ERRNO_MALL_OTHER                      => 'mall other failed',
        self::ERRNO_GIFT_NOT_ENOUGH                 => 'gift not enough',
        self::GIFT_NOT_EXIST                        => 'gift not exist',
        self::HAD_SET_ADDRESS                       => 'had already set address!',
        self::SET_ADDRESS_FAILED                    => 'set address failed!',
        self::DEL_MYGIFT_FAILED                     => 'del my gift failed!',
        self::ERRNO_USER_NO_PRIVILEGE               => '木有该权限哦!',
        self::ANSWER_HAD_THANKS                     => 'your answer had been thanks!',
        self::ANSWER_HAD_INVITE                     => 'you had invite evaluate!',
        self::ACTQQ_SETQQ_ERROR                     => 'set qq error!',
        self::EVALUATE_FAILED                       => 'failed to evaluate question.',
        self::INVALID_REQUEST                       => 'invalid request',
        self::DB_ERROR                              => 'db error',
        self::LBS_API_ERROR                         => 'lbs api error',
        self::UNAME_ILLEGAL                         => 'uname is illegal!',
        self::ERRNO_U_NOT_ADMIN                     => 'not admin',
        self::USER_LEVEL_NOT_ENOUGH                 => 'user level not enough',
        self::USER_WEALTH_NOT_ENOUGH                => '财富值不足',
        self::HAD_SET_NUMBER                        => '别调皮!大神你已经填过号码了...',
        self::NOT_GOOD_QUESTION                     => '又调皮了!问题里好像有秘密~',
        self::NOT_BIND_SCHOOL                       => '未设置学校信息...',
        self::ERROR_SPECIAL_EXIST                   => '该帖子已被设置',
        self::ERROR_SPECIAL_NO_EXIST                => '该帖子未被设置',
        self::ERROR_SPECIAL_FULL                    => '帖子设置数量达到上限',
        self::ERROR_SPECIAL_EXCELLENT               => '该帖已是精华帖',
        self::ERROR_SPECIAL_TOP                     => '该帖已是置顶帖',
        self::ERROR_CANCEL_SYSTEM_ARTICLE           => '不能取消系统置顶帖',
        self::ERROR_SPECIAL_SETEXCELLENT_ISSYSTOP   => '设置的精华帖已经是系统置顶贴',
        self::HOMEWORK_ADMIN_ADDFAIL                => '作业帮管理员添加失败',
        self::USER_OFF_GPS                          => 'this user turned off the gps.',
        self::PHOTO_SHOW_HAVE_UPLOADED              => 'photo show have uploaded',
        self::PHOTO_SHOW_HAVE_BANNED                => 'photo show have banned',
        self::PHOTO_SHOW_NOT_IN_ACT                 => 'photo show not in time limit',
        self::MIS_SEND_USER_MORE                    => '发送的用户数据太多',
        self::VOTE_OPTION_HAS_VOTED                 => '已经投过这个选项了~',
        self::VOTE_TYPE_IS_ONE                      => '单选投票不能重复投票哦~',
        self::ERROR_ARTICLE_DELETED                 => '帖子已删除',
        self::VOTE_IS_FINISHED                      => '投票已结束~ 萌萌哒',
        self::ARTICLE_REPLY_DELETED                 => '回帖已被删除  辛苦啦～',
        self::SYSTEM_CRAZY                          => '网络悲剧啦，再努力试试～',
        self::SUBMIT_REFUSE                         => '网络萌抽了～ 稍后再试吧',
        self::SYSTEM_USER_NOT_FRIEND                => '系统大人很忙哦～',
        self::APPLY_MESSAGE_NOT_EXISTS              => '好友验证消息找不到～ 晕死啦',
        self::APPLY_MESSAGE_HAS_ACCEPTED            => '验证消息已通过啦',
        self::NOT_FRIEND_ANY_MORE                   => '不是好友啦～',
        self::CHAT_TO_NOT_FRIEND                    => '你们已经不是好友了,暂时无法发送私信哦:(',
        self::ALREADY_BEEN_FRIEND                   => '我们已经是好友啦～',
        self::REWARD_HAS_ADDED                      => '问题已经追加过悬赏了',
        self::Q_HAS_REPLYCOUNT                      => '问题已经有人回答啦',
        self::FRIEND_EXCEED_MAX_NUM                 => '好友人数已达上限～',
        self::CHECKIN_HAS_DONE                      => '今天签到已完成～',
        self::BZBIND_ERROR                          => '账号迁移失败',
        self::Q_STATUS_HAS_CHANGED                  => '问题状态已改变...',
        self::TEACHER_INFO_NOT_EXISTS               => '老师信息不存在',
        self::IN_YOUR_BLACKLIST                     => '对方在你的黑名单里，请先解除～',
        self::FRIEND_ID_NOTEXIST                    => '好友暗号不存在',
        self::APPLY_ACCEPT_FAILE                    => '添加好友失败',
        self::FRIEND_ID_ERROR                       => '好友暗号错误',
        self::FRIEND_ID_YOURSELF                    => '好友暗号是自己的，请勿添加～',
        self::NIUDAN_NOT_START                      => '扭蛋活动已结束',
        self::ACTIVITY_EXPIRE                       => '活动已过期',
        self::NIUDAN_NO_REMAIN                      => '剩余扭蛋次数不够',
        self::NANTIBANG_QQ_PRIZE                    => '难题榜送Q币活动非法兑奖',
        self::ERROR_NOT_ALLOW_TRANSFER              => '本贴不允许被转移',
        self::TASK_NOT_NULL                         => '任务不存在',
        self::TASK_NOT_PACKAGE                      => '任务新手包不存在',
        self::TASK_HAS_PACKAGE                      => '你已经领过该礼包啦！',
        self::TASK_NO_PACKAGE                       => '你还没有可以领的礼包哦！',
        self::ERRNO_VOICE_SIWTCH                    => 'not allow voice',
        self::ERRNO_VOICE_FILEERR                   => '语音发送失败，请重新发送',
        self::ERRNO_VOICE_SIZE                      => '语音发送失败，请重新发送',
        self::ERRNO_VOICE_LEN                       => '语音发送失败，请重新发送',
        self::ERRNO_VOICE_UPLOAD                    => '语音发送失败，请重新发送',
        self::ERRNO_VOICE_KEYERR                    => '网络延迟，请稍后再试',
        self::ERRNO_VOICE_DATAEMPTY                 => 'voice data is empty',
        self::ERRNO_VOICE_FILETYPEERR               => '语音发送失败，请重新发送',
        self::ERRNO_VOICE_OWNER                     => 'user is not this voice\'s owner',
        self::SEND_WEALTH_FAIL                      => '增加财富值的操作失败了！',
        self::PHONE_SET_ERROR                       => '手机号修改失败',
        self::PHONE_SET_HAS_REGISTER                => '手机号已注册作业帮账号',
        self::PGC_HOLD_QUESTION_OVERLOAD            => '已认领而未答的题目过多',
        self::ERROR_PGC_HOLD_QUESTION               => '认领问题失败',
        self::PGC_HOLD_OTHER_QUESTION               => '此问题已被别人认领了',
        self::API_SEARCH_OVERFLOW                   => 'api search overflow',
        //self::OVER_SEARCH_LIMIT                     => '超过今天扫描次数限制',
        //--------------------tiku----------------------
        self::ERRNO_DEL_HOMEWORK                    => "delete homework failed",
        self::ERRNO_UPDATE_PICSTRING                => "update tblQuestionPicString failed",
        self::ERRNO_UPDATE_HOMEWORK                 => "update homework failed",
        self::ERRNO_ADD_HOMEWORK                    => "add homework failed", //增加Homework失败
        self::ERRNO_UPDATE_SEARCHRECORD             => "update SearchRecord failed",
        self::ERRNO_UPDATE_PICSTRING                => "update QuestionPicString failed",
        self::ERRNO_ADD_SEARCHRECORD                => "add SearchRecord failed", //增加SearchRecord失败
        self::EXERCISE_EMPTY                        => "There is not any exercise.", // 没有练习题
        self::ERRNO_SEARCH_FAILE                    => 'search service error',//检索服务异常
		self::ERRNO_NOT_ENZUOWEN                    => "调皮！这不是手写的英语作文吧，识别失败了，重拍吧~",
		self::ERRNO_BLUR_ENZUOWEN                   => "哎呦，图片可能有点小问题，导致识别结果不太准确呢，重拍吧～",
		self::ERRNO_FAILED_ENZUOWEN_OCR                 => "呀，系统错误，重新拍照试一下吧～",
		self::ERRNO_FAILED_ENZUOWEN_TEXT                 => "提交失败，清重试哦",
		self::ERRNO_NOTVALID_ENZUOWEN               => "咦，同学你不是班课用户吧，该功能当前仅供班课同学使用呦～",
		self::ERRNO_USERACTCTRL_ENZUOWEN            => '诶呀，今天已经智能批改好多篇作文了呢，学习要劳逸结合哟，歇会儿再来吧~',
		self::ERRNO_ACTCTRL_ENZUOWEN                => '系统繁忙，正疯狂加速中，给点耐心，稍后重试哟~',
        //--------------------同步练习----------------------
        self::PRACTICE_EVALUATION_EXAM_NOT_EXIST    => "试卷不存在", //试卷不存在
        self::PRACTICE_BOOK_INVALID                 => "书目不可用", //书本不存在
        self::PRACTICE_RESERVE_FAILED               => "暂时不可预约", //课程预约失败
        self::PRACTICE_ORDER_FAILED                 => "暂时不可报名", //课程报名失败
        self::PRACTICE_EXAM_FAILED                  => "无法参加考试",
        self::PRACTICE_EXAM_REG_FAILED              => "考试报名失败",
        self::PRACTICE_EXAM_SUBMIT_FAILED           => "考试提交失败",
        self::PRACTICE_NEWEXAM_ADDTEAM_FAILED       => "加入队伍失败",
        self::PRACTICE_RELATEBOOK_NOT_FOUND         => "未找到相关的bookId",
        self::PRACTICE_SULIAN_DTK_OCR_INCOMPLETE         => "答题卡不完整,拍题不全",
        self::PRACTICE_SULIAN_DTK_OCR_NOT_CARD         => "拍题无效，非答题卡",

        //----------------------答疑模块-------------------------
        self::CHARGE_TEACHER_NOT_FREE               => "当前老师不是空闲状态",
        self::CHARGE_TEACHER_NOT_EXIST              => "老师不存在或者未通过审核",
        self::CHARGE_QUEUE_NOT_EXIST                => "学生队列不存在",
        self::CHARGE_PAYAPI_ERROR                   => "获取支付参数失败",
        self::CHARGE_LCSPUSH_ERROR                  => "LCS push失败",
        self::CHARGE_7DAYORDER_ERROR                => "你已购买过7日套餐，每人享受1次喔",
        self::CHARGE_GEN_URL_ERROR                  => '生成支付数据失败',
        self::CHARGE_ZERO_PAY                       => '无需支付',
        self::CHARGE_COUPON_HAD_PICKUP              => '优惠券已经领取',
        self::CHARGE_COUPON_SHARE_EXPIRE            => '分享已过期',
        self::CHARGE_TEACHER_BANNED                 => '登录失败',
        self::DAYI_PACKAGE_INVALID                  => '套餐数据异常',
        self::DAYI_SERVICE_HAS_STOP                 => '答疑订单已经取消',

        //----------------------作业帮session服务-------------------------
        self::SESSION_PHONE_HAS_REGISTER            => "手机号已注册，请直接登录",
        self::SESSION_PHONE_NOT_REGISTER            => "手机未注册",
        self::SESSION_TOKEN_SEND_ERROR              => '短信验证码发送失败',
        self::SESSION_TOKEN_CHECK_ERROR             => '短信验证码校验失败',
        self::SESSION_PASSWORD_CHECK_ERROR          => '密码输入错误',
        self::SESSION_LOGIN_ERROR                   => '登录会话建立失败',
        self::SESSION_LOGOUT_ERROR                  => '登录会话清理失败',
        self::SESSION_USER_FORBIDDEN                => '账号开小差了，休息一会再试试吧',
        self::SESSION_PHONE_REGISTER_BAIDU          => '手机已注册百度账号',
        self::SESSION_UIDMAP_ERROR                  => '百度账号映射失败',
        self::SESSION_PHONE_FORMAT_ERROR            => '请输入正确的手机号码',
        self::SESSION_PHONE_BZBIND_ERROR            => '该手机号已被绑定',
        self::SESSION_BAIDU_BZBIND_ERROR            => '该账号已被关联',
        self::SESSION_NEED_REGISTER                 => '请注册后再登录喔',
        self::SESSION_PASSWORD_SET                  => '点击忘记密码，修改密码后重新登录吧',
        self::SESSION_PASSWORD_SET_ERROR            => '密码重置失败，请稍后再试',
        self::SESSION_PHONE_SET_ERROR               => '绑定手机号失败',
        self::SESSION_EMAIL_NOTVALID                => '员工姓名与邮箱不合法，新员工请晚18时再添加，重名员工姓名后面加数字后缀',

        //----------------------作业帮session服务-------------------------
        self::SMS_PHONE_ACTS_CTRL                   => 'phone number acts ctrl error',
        self::SMS_TEMPLATE_ID_UNAVAILABLE           => 'pls check your template is available',

        /************************************ mis 后台 30000 - 39999*************************************/
        // Mis 相关
        self::USER_INFO_NOT_EXIST                   => '用户数据不存在',
        self::UID_NULL                              => '非百度帐号',
        self::ADMIN_EDIT_ERROR                      => '用户信息编辑失败',
        self::ADMIN_ADD_ERROR                       => '用户添加失败',
        self::ADMIN_EXISTS                          => '用户已存在',
        self::GROUP_EDIT_ERROR                      => '组信息编辑失败',
        self::ADMIN_PARAM_ERROR                     => '用户信息不全（学段/学科为空）',
        self::FILE_TYPE_EXIST                       => '非法文件类型',
        self::ERROR_UPLOAD_ERR_MAX_FILE_SIZE        => '上传图片大小超过限制',
        self::NO_WARNING_ONLY_SHOW_TIPS             => '请关注',

        // 任务相关
        self::TASK_FINISH                           => '任务已完成',
        self::TASK_NOT_EXIST                        => '任务不存在',
        self::USER_TASK_NOT_EXIST                   => '你没有这个任务或此任务已完成',

        //spam相关
        self::SPAM_VCODE_PIC_NETWORK_DOWN           => '验证码获取失败', //图片验证码:服务不可用
        self::SPAM_VCODE_PIC_GENERATE_FAILED        => '验证码获取失败', //图片验证码:验证码生成失败
        self::SPAM_VCODE_PIC_EXPIRED                => '验证码过期',     //图片验证码:验证码过期
        self::SPAM_VCODE_PIC_USED                   => '验证码已被使用', //图片验证码:验证码已被使用
        self::SPAM_VCODE_PIC_CHECK_FAILED           => '验证码不正确',   //图片验证码:验证码不正确

        /************************************ 辅导相关 50000 - 59999*************************************/
        // course相关
        self::COURSE_NOT_EXIST                      => '课程不存在',
        self::COURSE_CHECK_ERROR                    => '课程检查失败',
        self::LESSON_NOT_EXIST                      => '课节不存在',
        self::EXERCISE_NOT_EXIST                    => '习题不存在',
        self::COURSE_IS_START                       => '课程已开始',
        self::EXERCISE_HAS_DONE                     => '习题已经练习',
        self::EXERCISE_HAS_ADD                      => '习题已经添加',
        self::EXERCISE_NOT_INCLASS                  => '习题不是课间练习',
        self::EXERCISE_NOT_INTIME                   => '这个时间不可以提交习题',
        self::SET_EXERCISE_ERROR                    => '发布作业失败',
        self::YESNO_REPEAT                          => '是否卡重复',
        self::YESNO_TIMEOUT                         => '是否卡超时',
        self::COURSE_NOT_ONLINE                     => '课程未上线',
        self::COURSE_NOT_PUBLIC_FREE_PRICE          => '课程非公开课且非免费课',
        self::COURSE_TIMEOUT                        => '课程报名超时',
        self::COURSE_ACTIVITY_HAS_ORDER             => '活动已预约',
        self::COURSE_NOT_BEGIN                      => '课程未开课',
        self::COURSE_HAS_END                        => '报名时间结束啦',
        self::COURSE_HAS_LEAVE                      => '课程已下课',
        self::COURSE_NOT_END                        => '未到下课时间',
        self::COURSE_HAS_HOTCOURSE                  => '课程包已是热门课程',
        self::COURSE_TYPE_FULL                      => '该类型课程已填满',
        self::HOTCOURSE_NOT_TOONLINE                => '热门课程禁止下线',
        self::COURSE_NOT_ALLOW                      => '课程类型不合法',
        self::COURSE_ORDER_VALID                    => '课程订单校验失败',
        self::COURSE_HAS_INNER                      => '课程为内部课程',
        self::FUDAO_PUSH_FAIL                       => '消息推送失败',
        self::LESSON_HAS_LEAVE                      => '课节已下课',
        self::CHANGE_CDN_ERR                        => '切换CDN失败',
        self::ORDER_FROZEN_FAILED                   => '冻结处理失败',
        self::FUDAO_INCLASS_REPEAT_SUMIT            => '你的账号已有提交记录，请勿重复提交',
        self::FUDAO_INCLASS_REPEAT_PULL             => '你的账号已有领取记录，请勿重复领取',
        self::HAS_COURSE_COUPON                     => '用户该课程已发送过优惠券',
        self::NO_COURSE_COUPON                      => '优惠券金额为零元',
        self::ADD_COURSE_COUPON_ERROR               => '优惠券发送失败',
        self::COURSE_COUPON_INVALID_PHONE           => '手机号未注册',
        self::HOMEWORK_EXERCISE_TEACHER_ERROR       => '作业提交失败，请联系你的班主任老师帮忙解决吧',
        self::COURSE_COUPON_DUPLICATE               => '订单中有重复优惠券，请修改后重试',
        self::COURSE_CAN_NOT_BUY                    => '无法购买此课程',
        self::VIDEO_CAN_NOT_RENEWAL                 => '不能重复续期回放视频',
        self::NO_BIND_PAPER                          =>'没有绑定试卷',
        self::UN_BINDING                             =>'已解绑',
        self::SAVE_ERROR                             =>'保存失败',


        //student相关
        self::STUDENT_CHECK_ERROR                   => '学生检查失败',
        self::STUDENT_NOT_EXIST                     => '学生不存在',
        self::STUDENT_CAN_NOT_TALK                  => '学生被禁言',
        self::STUDENT_CLASS_NOT_EXIST               => '学生不在本班级',
        self::STUDENT_LEARN_REPORT_NOT_GEN          => '学生的学习报告尚未生成',
        self::STUDENT_HAD_BUY_COURSE                => '学生已经购买本课程',
        self::STUDENT_IS_TEACHER                    => '该账号为老师账号,禁止报名',

        // teacher相关
        self::TEACHER_NOT_EXIST                     => '教师信息不存在',
        self::TEACHER_EDIT_ERROR                    => '编辑教师信息失败',
        self::TEACHER_INSERT_ERROR                  => '新增教师信息失败',
        self::TEACHER_DEL_ERROR                     => '删除教师信息失败',
        self::TEACHER_CHECK_ERROR                   => '老师检查失败',
        self::TEACHER_RTMP_ERROR                    => '百度推流开启失败',
        self::TEACHER_HAS_SIGNIN                    => '每节课只能签到一次',
        self::TEACHER_MAC_MATCH                     => '此账号禁止登录',


        //assistant 相关
        self::ASSISTANT_NOT_IN_COURSE               => '辅导老师没有这个课程',
        self::ASSISTANT_NOT_IN_CLASS                => '辅导老师没有这个课程班级',
        self::ASSISTANT_NOT_EXIST                   => '辅导老师信息不存在',
        self::TEACHER_NOT_ASSISTANT                 => '老师不是辅导老师',
        self::ASSISTANT_COURSE_NOT_EXIST            => '辅导老师课程不存在',
        self::ASSISTANT_CLASS_NO_STUDENT            => '辅导老师班级没有学生',
        self::ASSISTANT_NO_PRIVILEGE_THIS_STUDENT   => '辅导老师没有当前学生的权限',
        self::ASSISTANT_NO_PRIVILEGE_THIS_INTERVIEW => '辅导老师没有当前访谈的修改权限',
        self::ASSISTANT_NO_PRIVILEGE_THIS_SCORE     => '辅导老师没有当前成绩的修改权限',
        self::ASSISTANT_HAS_NO_STUDENT              => '辅导老师本课程没有学生',
        self::ASSISTANT_STUDENT_EXEED               => '辅导老师课程学生超出',

        //LCS
        self::LCS_SEND_MSG_ERROR                    => '课间习题推送失败',

        //LECTURE 相关
        self::LECTURE_CREATE_ERROR                  => '讲义创建失败',
        self::LECTURE_GETINFO_ERROR                 => '讲义信息获取失败',
        self::LECTURE_GETLIST_ERROR                 => '讲义列表获取失败',
        self::LECTURE_CANT_EDIT_ERROR               => '老师操作非自己的讲义',
        self::LECTURE_DELETE_ERROR                  => '讲义删除失败',
        self::LECTURE_SAVE_ERROR                    => '讲义保存失败',
        self::LECTURE_SAVE_AS_ERROR                 => '讲义另存失败',
        self::LECTURE_ID_ALLOC_ERROR                => '讲义id生成失败',
        //OTHERPOINT 相关
        self::OTHERPOINT_GET_CNT_ERROR              => '获取学而思讲义数量失败',
        self::OTHERPOINT_ADD_TID_ERROR              => '添加学而思讲义tid失败',
        self::OTHERPOINT_GET_INFO_ERROR             => '获取学而思讲义信息失败',
        self::OTHERPOINT_CONFIRM_CHECK_ERROR        => '获取学而思讲义复查提交失败',
        //选课单
        self::SHOPPING_CART_IS_FULL                 => '选课单已满',
        self::SHOPPING_CART_IS_LIMIT                => '选课单限购',

        //活动相关
        self::ACTIVITY_ACCEPT_INVITE_ERROR          => '您已接受邀请',
        self::ACTIVITY_EXCHANGE_COUPON_ERROR        => '兑换优惠券失败',
        self::ACTIVITY_HAS_ACCEPT_COUPON_ERROR      => '您已领取过优惠券',
        self::ACTIVITY_DATI_TIME_EXPIRE             => '不在答题预约活动期内',
        self::ACTIVITY_DATI_USER_INVALID            => '用户信息非法',
        self::ACTIVITY_DATI_USER_NO_PRIVILEGE       => '用户没有预约权限',
        self::ACTIVITY_DATI_HAS_APPOINT             => '用户已预约',
        self::ACTIVITY_DATI_CUL_BONUS_ERROR         => '奖金计算失败',


        //业务订单相关
        self::TRADE_NOT_PAYED_STATUS                => '订单非支付成功状态',
        //端内答疑
        self::MENTORING_COURSE_ASK_MAX_ERROR        => '本课程今日的提问数量达到上限',
        self::MENTORING_STUDENT_IS_BANNED_ERROR     => '你因为多次违反提问规则，已被禁止提问',
        self::MENTORING_STUDENT_ASK_MAX_ERROR       => '你今日的提问数量达到上限',
        self::SCORE_COURSE_CHECK_ERROR              => '该课程不计学分',
        self::SCORE_NO_VALID_RED_ENVELOPE           => '无可领取的红包',
        self::SCORE_RED_ENVELOPE_RECEIVE_ERROR      => '网络不好领取失败，重新领取吧~',
        self::SCORE_SEND_RED_ENVELOPE_ERROR         => '课程不在直播中，不可发送学分红包',
        self::SCORE_SEND_RED_ENVELOPE_MAX_ERROR     => '本课程发送的学分红包数量已达到上限',
        self::SCORE_EX_RED_ENVELOPE_NOT_END_ERROR   => '上一个学分红包未关闭，不可发送新的学分红包',
        self::SCORE_ADD_SCORE_ERROR                 => '添加学分失败',
        self::SCORE_HAS_ALREADY_ADD_ERROR           => '已添加过的学分，不能重复添加',
        self::SCORE_PRODUCT_TYPE_NOT_EXIST_ERROR    => '学分商品类型不存在',
        self::SCORE_NOT_SUFFICIENT_FUNDS            => '学分余额不足',
        self::SCORE_EXCHANGE_PRODUCT_ERROR          => '兑换失败',
        self::SCORE_NO_VALID_INCLASSSIGN            => '无可领取的签到积分',
        //视频回放相关
        self::VIDEO_SIGN_EXCEED_LIMIT            => '为提高复习效率，每章节课程标记上限为20次哦',
        //kunpeng相关
        self::KP_STAFF_NOT_EXIST                    => '抱歉，此账号未开通权限',
        self::KP_STAFF_WEIXIN_NOT_EXIST             => '抱歉，此账号没有关联的微信',
        self::KP_GET_STAFFINFO_ERROR                => '账号信息获取失败，请退出重试',
        self::KP_GET_STAFFWEIXIN_ERROR              => '微信信息获取失败，请退出重试',
        self::KP_GET_STUDENTWEIXIN_ERROR            => '学生微信获取失败，请重试',
        self::KP_NOT_INTERNAL_NETWORK_ERROR         => '内部页面，禁止使用外网访问',
        self::KP_GET_STAFF_WXFRIEND_ERROR           => '好友列表获取失败，请重试',
        self::KP_GET_STAFF_WXFRIENDINFO_ERROR       => '好友详细资料获取失败，请重试',
        self::KP_GET_MSGTASK_TOSENDTIME_ERROR       => '获取群发任务发送时间失败，请重试',
        //biz营销相关
        self::BIZ_GET_BIZ_INFO_ERROR                => '获取营销信息失败，请重试',
        self::BIZ_SKU_CAN_NOT_BUY                   => '抱歉，此商品不可购买',
        self::SKU_NOT_EXIST                         => '商品不存在',
        self::SKU_ALREADY_DEPOSIT                   => '商品已预订',

        /************************************ IM相关 59001 - 59599*************************************/
        self::IM_USER_STATUS_ERROR                  => '用户状态异常',
        self::IM_GROUP_STATUS_ERROR                 => '群组状态异常',
        self::IM_GROUP_USER_STATUS_ERROR            => '用户已不在群组或群组用户状态异常',
        self::IM_GROUP_USER_PRIVILEGE_ERROR         => '用户权限异常',
        self::IM_MESSAGE_TYPE_ERROR                 => '消息类型错误',
        self::IM_MESSAGE_UIDLIST_ERROR              => '消息发送列表错误',
        self::IM_MESSAGE_CONTENT_ERROR              => '消息内容错误',
        self::IM_MESSAGE_SEND_ERROR                 => '消息发送失败',
        self::IM_GROUP_NOT_EXIST                    => '群不存在',
        self::IM_HAS_IN_GROUP                       => '已经在群里',
        self::IM_HAS_NOT_IN_GROUP                   => '已经退出该群',
        self::IM_JOIN_GROUP_ERROR                   => '加入群失败',
        self::IM_OUT_GROUP_ERROR                    => '退出群失败',
        self::IM_SPAM_CHAT_ERROR                    => '对不起，您输入的内容不符合规定',
        self::IM_GROUP_FULL_ERROR                   => '群已满',
        self::IM_GROUP_HAS_KICKOFF                  => '已被踢出此群，不能再加入',
        self::IM_HAS_IN_OTHER_GROUP                 => '你已经加入老师的另一个粉丝群了，不能贪心哦',
        self::IM_NOT_BUY_NOT_JOIN                   => '只有上过老师课程的同学，才能加入老师的粉丝群哦',
        self::IM_NOT_IN_GROUP                       => '用户不在群内',
        self::IM_MESSAGE_DELETE_ERROR              => '撤回消息失败',
        self::IM_MESSAGE_DELETE_TIMEOUT            => '消息已经超过2分钟',
        self::IM_GROUP_JOIN_OVER_FREQUENCY  => '你进出本群太频繁啦，请稍后再试',
        self::IM_CANNOT_TALK_WITH_SELF => '对不起，您不能和自己发起临时会话！',
        self::IM_NO_RIGHT_TO_OPEN_PRIVATE_CHAT => '对不起，您没有开启临时会话的权限！',
        self::IM_OPEN_PRIVATE_CHAT_FAILED => '开启临时会话失败',
        self::IM_MEMBER_APP_VERSION_LOW => '对方APP版本过低',
        self::IM_MEMBER_STATUS_NOT_SUITABLE => '对方不在群中',
        self::IM_MEMBER_NOT_IN_GROUP         => '该用户已退群，无法获取信息',
        self::IM_CLOSE_PRIVATE_CHAT_FAILED  => '关闭临时会话失败',
        self::IM_VOICE_CONVERT_NOT_FINISHED => '此消息暂时不能播放，请稍后再试',
        self::IM_EXERCISE_GROUP_DELETE => '该作业已被删除',
        self::IM_MESSAGE_TYPE_NOT_ALLOWED => '该功能暂时不可用',
        self::IM_SEND_MSG_FREQUENCY => '您说话太快啦，请稍后重试',

        /************************************ IM相关 59001 - 59599*************************************/
        self::SANTIAO_IDCARD_CHECK_ERROR => '身份证账号不正确，请核对后提交',
        self::SANTIAO_IDCARD_CHECKAPI_ERROR => '身份证账号与姓名不匹配，请核对后提交',
        self::SANTIAO_MONEY_NOT_ENOUGH => '余额需要大于30元才能提现，请继续加油',

        /***************************淘金项目 60000-60999 *********************************/
        /*************************** 这块错误号，可以检查后回收了 *********************************/
        self::BIZ_MERCHANT_BOOKING_FAILED           => '预约失败',
        self::BIZ_MERCHANT_BOOKING_EXIST            => '已预约',

        /***************************答疑讨论区 61000-61999*********************************/
        self::DISCUSS_NO_ADMIN_PERMISSION           => '没有管理员权限',
        self::DISCUSS_POST_NOT_EXIST                => '帖子不存在',
        self::DISCUSS_POSTLIST_ALREADY_TOP          => '帖子已为置顶帖',
        self::DISCUSS_POSTLIST_ISNOT_TOP            => '帖子并非置顶帖',
        self::DISCUSS_POSTLIST_SETTOP_FAIL          => '帖子置顶设置失败',
        self::DISCUSS_WORD_OVER_MAX_LEN             => '字数超上限',
        self::DISCUSS_POST_HASNOT_BUY_DAYI          => '开通辅导套餐后才可以发帖哟~',
        self::DISCUSS_IMG_NOT_EXISTS                => '图片不存在',
        self::DISCUSS_POST_FAILED                   => '发帖失败',
        self::DISCUSS_DEL_FAILED                    => '管理员-删帖失败',
        self::DISCUSS_POSTLIST_SETRECOMMEND_FAIL    => '帖子列表-设置推荐首页失败',
        self::DISCUSS_POST_TRANS_CATEGORY_FAIL      => '帖子&列表-转移板块失败',

        /***************************答疑约课62000-63999*********************************/
        self::RESERVE_PERIOD_CAN_NOT_OPEN           => '预约时段不能开放',
        self::RESERVE_DATE_ERROR                    => '预约日期错误',
        self::RESERVE_PERIOD_ERROR                  => '预约时段错误',
        self::RESERVE_PERIOD_HAD_OPEN               => '时段已开放',
        self::RESERVE_PERIOD_NOT_FOUND              => '时段记录没有找到',
        self::RESERVE_PERIOD_CAN_NOT_CLOSE          => '时段不能被关闭',
        self::RESERVE_PERIOD_CAN_NOT_CANCEL         => '时段不能被取消',
        self::RESERVE_ORDER_NOT_FOUND               => '预约订单没有找到',
        self::RESERVE_PERIOD_ADJUST_ERROR           => '调课时段错误',
        self::RESERVE_PRRIOD_ADJUST_CAN_NOT_OP      => '调课时段不能操作',
        self::RESERVE_SERIAL_ACT_NOT_FOUND          => '系列课活动不存在',
        self::RESERVE_PERIOD_ADJUST_EXPIRE          => '调课申请过期',
        self::RESERVE_OPEN_OPERID_FAILED            => '开放时段失败',
        self::RESERVE_REJECT_ADJUST_FAILED          => '拒绝调课失败',


        self::RESERVE_NOT_EXIST                 => '预约不存在',
        self::RESERVE_DATA_INVALID              => '预约数据错误',
        self::RESERVE_TIME_INVALID              => '预约未到开放时段',
        self::RESERVE_NOT_BELONG                => '预约并非属于您',
        self::RESERVE_SERVICE_CREATE_FAIL       => '房间创建失败',
        self::RESERVE_SERVICE_ARRANGE_FAIL      => '房间配置失败',
        self::RESERVE_SERVICE_START_FAIL        => '未能开始答疑',
        self::RESERVE_SERVICE_STUDENT_NOT_ENTRY => '学生未进入房间',
        self::RESERVE_SERVICE_TEACHER_NOT_ENTRY => '老师未进入房间',

        /***************************答疑约课64000-65999*********************************/
        self::PROPER_NOT_EXIST                  => '辅导课不存在',
        self::PROPER_DATA_INVALID               => '辅导课数据错误',
        self::PROPER_NOT_BELONG                 => '辅导课并非属于您',

        self::PROPER_ORDER_NOT_FOUND => '辅导课订单没有找到',
        self::PROPER_TIME_INVALID    => '辅导课未到开放时段',

        self::FILE_UPLOAD_ERROR      => '上传失败！请检查网络，重新上传',
        self::FILE_NOT_PDF           => '请上传PDF格式的讲义',
        self::FILE_PDF_PAGE_ERROR    => '讲义页数不能超过60页，请适当精减讲义内容',
        self::FILE_PDF_SIZE_ERROR    => '请上传大小不超过30M的文件',
        self::FILE_PDF_COACH_TIMEOUT => '上传失败！您已超过讲义上传规定时间，如有问题请联系管理员',
        self::FILE_NOT_EXIST         => '文件已过期或不存在',
        self::FILE_CNT_LIMIT         => '超过文件最大数量',

        self::PROPER_SERVICE_CREATE_FAIL       => '房间创建失败',
        self::PROPER_SERVICE_ARRANGE_FAIL      => '房间配置失败',
        self::PROPER_SERVICE_START_FAIL        => '未能开始辅导课',
        self::PROPER_SERVICE_STUDENT_NOT_ENTRY => '学生未进入房间',
        self::PROPER_SERVICE_TEACHER_NOT_ENTRY => '老师未进入房间',
        self::PROPER_SERVICE_CAN_NOT_CANCEL    => '教室已开放，不支持取消',

        /**************************支付相关 70000-70999*********************************/
        self::PAY_TOKEN_ERROR                  => '交互凭证错误',
        self::ORDER_NOT_EXIST                  => '订单不存在',
        self::ORDER_HAS_FROZE                  => '该订单已冻结，不能再次冻结',
        self::ORDER_COURSE_END                 => '该课程已结束，不能冻结',
        self::ORDER_ISNOT_TOPAY                => '课程不是待支付状态',
        self::PAY_STATUS_INVALID               => '支付状态异常',
        self::PAY_PAY_ERROR                    => '支付异常',
        self::PAY_REFUNDFEE_INVALID            => '退款金额异常',
        self::PAY_REFUND_ERROR                 => '退款异常',
        self::PRODUCT_NOTEXIST                 => '商品不存在',
        self::PAY_REFUND_DUP                   => '重复退款',
        self::CREATE_ORDER_FAILED              => '创建订单失败',
        self::PAY_URL_EXPIRED                  => '支付链接已过期',
        self::PAY_DECR_COIN_FAIL               => '扣除余额失败',
        self::PAY_PAYCALLBACK_FAIL             => '支付成功回调产品线失败',

        /************************优惠券相关 80000-80999***********************************/
        self::NEWCOUPON_TOKEN_ERROR            => '交互凭证错误',
        self::NEWCOUPON_CODE_TIMEOUT           => '优惠码过期',
        self::NEWCOUPON_TIMEOUT                => '优惠券过期',
        self::NEWCOUPON_CODE_UNUSED            => '优惠码已不可用',
        self::NEWCOUPON_CODE_USERUSED          => '同一用户户或设备不能重复领取',
        self::NEWCOUPON_CODE_CALLBACK_ERR      => '回调失败',
        self::NEWCOUPON_CODE_RECORD_ERR        => '优惠码兑换记录插入失败',
        self::NEWCOUPON_CODE_SUBSTRACT_ERR     => '优惠码剩余数量更新失败',

        /*********************第三方登入相关 85000-85999*********************************/
        self::SESSION_OUID_HAS_REGISTER        => '该第三方账号已注册',
        self::SESSION_OUID_NOT_EXIST           => '未完成授权步骤',
        self::SESSION_PHONE_ALREADY_BIND       => '该手机号已绑定此类第三方账号',
        self::SESSION_OAUTH_TOO_LONG           => '注册超时',
        self::SESSION_OAUTH_TYPE_ERROR         => '登录类型错误',
        self::BIND_USER_ERROR                  => '绑定手机号失败',

        /************************第三方用户服务相关 86000-86999*********************************/
        self::PARTNER_READ_CONF_ERROR           => '读取配置失败', //读取配置失败
        self::PARTNER_TOKEN_CHECK_ERROR         => 'token校验失败', //token校验失败
        self::PARTNER_GET_USERINFO_ERROR        => '获取用户信息失败', //获取用户信息失败
        self::PARTNER_GENERATE_TOKEN_ERROR      => '生成token失败',//生成token失败

        /************************** 一课app相关   870000-879999********************************/
        self::RESTART_TIME_EMPTY_ERROR         => '重开时间不能为空',
        self::RESTART_TIME_LIMIT_ERROR         => '注意：该起止时间不在重开范围内',
        self::RESTART_REASON_LONGTH_LIMIT      => '重开理由最多200字',
        self::RESTART_REASON_EMPTY_ERROR       => '重开理由不能为空',

        /************************** 快对作业app相关   830001-839999********************************/
        self::CODESEARCH_UNLOGIN_ACTSCTRL       => '未登录用户频率限制',

        /************************** app版本相关   880000-889999********************************/
        self::APP_VERSION_OLD_ERROR            => '作业帮版本太低',

        /************************** 新项目相关   890000-899999*********************************/
        self::FZ_PRODUCT_NOT_EXIST              => '商品不存在',

        /************************** 课程基础数据 900001-901000*********************************/
        self::ADD_COURSESYSTEM_FAIL             =>  '添加课程体系失败',
        self::ADD_CLASSTYPE_FAIL                =>  '添加课程类型失败',
        self::ADD_BOOKVER_FAIL                  =>  '添加教材版本失败',
        self::ADD_COURSEMODULE_FAIL             =>  '添加课程模块失败',
        self::COURSESYSTEM_REPEAT               =>  '添加的体系在系统中已经存在',
        self::CLASSTYPE_REPEAT                  =>  '添加的班型在系统中已经存在',
        self::BOOKVER_REPAET                    =>  '添加的教材版本在系统中已经存在',
        self::COURSEMODULE_REPEAT               =>  '添加的课程模块在系统中已经存在',
        self::COURSEMODULE_RELATION_REPEAT      =>  '添加的课程模块关系在系统中已经存在',

        /************************** 老师说相关 901000-901099*********************************/
        self::TEACHERSAY_MESSAGE_NOT_EXIST      =>  '动态已删除',
        self::CONTAINS_SENSITIVE_WORK           => '您发表的内容包含敏感词汇',
	    self::GET_STUDENT_XUEBU_FAIL            => '获取学生的学部信息失败',
	    self::TEACHERSAY_COMMENT_NOT_EXIST      => '评论已删除',
	    self::COMMENT_NOT_EMPTY                 => '发布内容不能为空',
	    self::COMMENT_EXCEED_LIMIT              => '发布内容超出限定字数',
	    self::COMMENT_NOT_CAN_REPLY             => '您不能给自己的评论进行回复',
	    self::COMMENT_UNAUTHORIZED_OPERATION   => '您无权进行当前操作',
	    self::TOP_MSG_EXCEED_LIMIT              => '置顶动态已达到上限',


    );

    /**
     * 获取错误信息 utf8编码
     *
     * 2017-02-21 增加根据规则能获取app内部自定义的错误码
     *
     * @param $errno
     */
    public static function getErrMsg($errno)
    {
        if (isset(self::$errMsg[$errno])) {
            return self::$errMsg[$errno];
        } else {
            $appClass = sprintf("%s_ExceptionCodes", ucfirst(MAIN_APP));            # 获取app内部按照规则自订的错误码信息
            if (class_exists($appClass) && isset($appClass::$appErrMsg[$errno])) {
                return $appClass::$appErrMsg[$errno];
            }
        }

        return '未知错误';
    }
}
