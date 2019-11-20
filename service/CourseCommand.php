<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file CourseCommand.php
 * @author jiangyingjie(com@baidu.com)
 * @date 2015/04/13 21:07:18
 * @brief 直播课课程推荐
 *  
 **/

class Hk_Service_CourseCommand {

    const COURSE_COMMAND_TIMES = 40; //年级学科key少且流量不均匀，复制多份使memcached实例流量会相对均匀。
    const COURSE_COMMAND_KEY   = 'course_recommend_%d_%d_%d';
    const COURSE_COMMAND_ACTIVITY_KEY   = 'course_recommend_activity_%d_%d';
    const COURSE_COMMAND_COURSE_KEY   = 'course_recommend_course_%d';
    const COURSE_COMMAND_COURSE_STYLE   = 'course_recommend_course_%d_%d';
    const COURSE_COMMAND_GONGZHONGHAO_TIMES = 10; //年级学科key少且流量不均匀，复制多份使memcached实例流量会相对均匀。
    const COURSE_COMMAND_GONGZHONGHAO_KEY   = 'course_recommend_gongzhonghao_%d';
    const ACT_COMMAND_STYLE_MID_KEY   = 'act_recommend_mid_%d';
    const ACT_COMMAND_STYLE_MID_TIMES = 10; // 中间页流量较大，复制多份使memcached实例流量会相对均匀。

    /**
     * 根据courseId推荐
     *
     * @param  int courseId 课程id
     * @param  array arrExtend 附加参数
     *           cuid : cuid
     *           recFrom : 推荐方式，laxin_rec为拉新推荐
     *           courseTeacher : 推荐课程对应的老师uid
     * @return string
     */
    public static function getRecommandByAct($courseId, $arrExtend=array()) {
        // 展示策略
        $disType       = intval($arrExtend['disType']);   // 展示方式  1 中间页 2课程详情页 3 弹窗
        $disCardType   = intval($arrExtend['disCardType']); // 卡片样式 1 图片 2 课程卡片
        $disCardUrl    = strval($arrExtend['disCardUrl']); // 展示图片url
        $disMidPageUrl = strval($arrExtend['disMidPageUrl']); // 中间页url
        $disPictype    = intval($arrExtend['disPictype']); // 打点pictype值
        $disFrom       = strval($arrExtend['disFrom']); // 打点from值
        $recSubject    = intval($arrExtend['recSubject']);
        $recGrade      = intval($arrExtend['recGrade']);
        $utype         = intval($arrExtend['utype']);   // 用户类型
        $urole         = intval($arrExtend['urole']);   // 用户身份
        $disExt        = $arrExtend['disExt']; // 展示扩展，包含弹窗逻辑
        $uid           = intval($arrExtend['uid']);
        $ucep          = intval($arrExtend['ucep']); // 是否是定向投放
    
        //从cache获取推荐数据，cache数据由直播课维护
        $objMemcached = Hk_Service_Memcached::getInstance("zhiboke");
    
        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        $cardUrl    = ''; // 卡片url
        $from       = ''; // 详情页from
        $midUrl     = ''; // 中间页url
        
        // 弹窗
        $popPic     = '';   // 弹窗图片
        $popBtn     = '';   // 弹窗文案
        $popClick   = '';   // 弹窗条状
        
        // 特殊的html
        $midFlag = false;
    
        do {
            if ($disPictype > 0) {
                $pictype = $disPictype;
            }
            if ($disFrom) {
                $from = $disFrom;
            }
            // 换图片 12
            // 换图片 跳转到中间页 13
            // 课程卡片 只改变打点 5
            
            // 图片
            if (1 == $disCardType) {
                $cardUrl = $disCardUrl;
                if (1 == $disType) {
                    // 中间页
                    $midFlag = true;
                    $style   = 12;
                    $midUrl  = $disMidPageUrl;
                    break;
                }
                if (2 == $disType) {
                    // 详情页
                    $style  = 13;
                    break;
                }
                if (3 == $disType) {
                    // 弹窗
                    $style    = 14;
                    $popPic   = strval($disExt['popInfo']['popPic']);
                    $popBtn   = strval($disExt['popInfo']['popBtn']);
                    $popClick = strval($disExt['popInfo']['popClick']);
                    break;
                }
                break;
            }
            // 课程卡片
            if (2 == $disCardType) {
                if (1 == $disType) {
                    // 中间页
                    Bd_Log::addNotice('RecTiyanExcept', 'kapian_mid');
                    break;
                }
                if (2 == $disType) {
                    // 详情页
                    $style = 5;
                    break;
                }
                if (3 == $disType) {
                    // 弹窗
                    Bd_Log::addNotice('RecTiyanExcept', 'kapian_pop');
                    break;
                }
                break;
            }
            break;
        } while(0);
    
    
        if ($midFlag) {
            $idx      = rand(1, Hk_Service_CourseCommand::ACT_COMMAND_STYLE_MID_TIMES); //分流
            $cacheKey = sprintf(Hk_Service_CourseCommand::ACT_COMMAND_STYLE_MID_KEY, $idx);
        }elseif (14 == $style){
            // 弹窗
            $idx      = rand(1, Hk_Service_CourseCommand::COURSE_COMMAND_GONGZHONGHAO_TIMES); //分流
            $cacheKey = sprintf(Hk_Service_CourseCommand::COURSE_COMMAND_GONGZHONGHAO_KEY, $idx);
        } else {
            //默认style都是5，而且cachekey是不带后缀的
            $cacheKey = sprintf(self::COURSE_COMMAND_COURSE_KEY, $courseId);
            if ( (12 == $style) || (13 == $style) ) {
                $cacheKey = sprintf(self::COURSE_COMMAND_COURSE_STYLE, $courseId, $style);
            }
        }
        $strValue = $objMemcached->get($cacheKey);
        if(empty($strValue)) {
            Hk_Util_Log::setLog('CourseCommandCached', 0);
            return array();
        }
        $arrValue = json_decode($strValue, true);
        Hk_Util_Log::setLog('CourseCommandCached', 1);
    
        Bd_Log::addNotice('RecTiyanStyle', $style);
        Bd_Log::addNotice('RecTiyanPictype', $pictype);
        Bd_Log::addNotice('RecTiyanToCourseId', $toCourseId);
        Bd_Log::addNotice('RecTiyanCardUrl', $cardUrl);
        Bd_Log::addNotice('RecTiyanMidUrl', $midUrl);
        Bd_Log::addNotice('RecTiyanFrom', $from);
    
        if ($arrValue && $arrValue['html']) {
            if (0 < $style) { //5和12和13有distype的替换
                if (0 <= $pictype) { //对于style=5/13的默认模版，需要替换
                    $arrValue['html'] = str_replace('data-display-pictype="pictype"', "data-display-pictype=\"{$pictype}\"", $arrValue['html']);
                }
                // from值
                if ($from) {
                    $arrValue['html'] = str_replace('data-from="search-fd-default"', "data-from=\"{$from}\"", $arrValue['html']);
                    // 打点规范  暂时和from一样
                    $arrValue['html'] = str_replace('data-lastfrom="lastfrom"', "data-lastfrom=\"{$from}\"", $arrValue['html']);
                    $arrValue['html'] = str_replace('data-orifrom="orifrom"', "data-orifrom=\"{$from}\"", $arrValue['html']);
                    
                }
                // 中间页url
                if ($midUrl) {
                    $arrValue['html'] = str_replace('data-url="jumpurl"', "data-url=\"{$midUrl}\"", $arrValue['html']);
                }
                // 图片卡片url
                if ($cardUrl) {
                    // 有地址
                    $arrValue['html'] = str_replace('<img class="tpl_card no-padding" src="imageurl"/>', "<img class=\"tpl_card no-padding\" src=\"{$cardUrl}\"/>", $arrValue['html']);
                    $arrValue['html'] = str_replace('<span class="tpl_card"></span>', '', $arrValue['html']);
                }
                // 弹窗
                if ($popPic) {
                    $arrValue['html'] = str_replace('data-popuppic="popup-picture"', "data-popuppic=\"{$popPic}\"", $arrValue['html']);
                }
                if ($popBtn) {
                    $arrValue['html'] = str_replace('data-btn-copywriting="button-copywriting"', "data-btn-copywriting=\"{$popBtn}\"", $arrValue['html']);
                }
                if ($popClick) {
                    $arrValue['html'] = str_replace('data-btnurl="btn-jumpurl"', "data-btnurl=\"{$popClick}\"", $arrValue['html']);
                }
            }
            // 指定跳转课程id
            if ( 0 < $toCourseId ) {
                $arrValue['html'] = str_replace("data-courseid=\"{$courseId}\"", "data-courseid=\"{$toCourseId}\"", $arrValue['html']);
            }
            // 推荐年级学科
            $arrValue['html'] = str_replace('data-recgradeid="recgradeId"', "data-recgradeid=\"{$recGrade}\"", $arrValue['html']);
            $arrValue['html'] = str_replace('data-recsubjectid="recsubjectId"', "data-recsubjectid=\"{$recSubject}\"", $arrValue['html']);
            // 用户身份 用户类型
            $arrValue['html'] = str_replace('data-utype="utype"', "data-utype=\"{$utype}\"", $arrValue['html']);
            $arrValue['html'] = str_replace('data-urole="urole"', "data-urole=\"{$urole}\"", $arrValue['html']);
            // 是否定向投放
            if ($ucep > 0) {
                $arrValue['html'] = str_replace('data-ucep="0"', "data-ucep=\"{$ucep}\"", $arrValue['html']);
            }
            // 用户uid
            if ($uid > 0) {
                $arrValue['html'] = str_replace('data-suid="suid"', "data-suid=\"{$uid}\"", $arrValue['html']);
            }
        }
    
        return $arrValue;
    }
    
    public static function getSpecial() {
        $ret = [
            'disType'       => 1, //中间页
            'disCardType'   => 1,// 图片
            'disCardUrl'    => 'https://img.zuoyebang.cc/zyb_84e3137901a49d440117e46e53460bd0.jpg', //图片url
            'disMidPageUrl' => 'http://www.zybang.com/course/activity/recordlesson?type=3&grade=2',// 中间页url
            'disPictype'    => 40022,
            'disFrom'       => 'souti_pm_40022',
        ];
        return $ret;
    }
    
    /**
     * 根据年级学科推荐
     *
     * @param  int gradeId   年级
     * @param  int subjectId 学科
     * @param  int courseId  课程id
     * @return string
     */
    public static function getCourseCommand($gradeId, $subjectId, $courseId, $arrExtend=array()) {
        if($gradeId <= 0 || $subjectId <= 0 || $courseId <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[gradeId:$gradeId subjectId:$subjectId courseId:$courseId]");
            return false;
        }

        //活动期间展现活动卡片
        $activityFlag = false;
        if(time() > strtotime('2016-12-25') && time() < strtotime('2016-12-31')) {
            $index = rand(1, self::COURSE_COMMAND_TIMES);
            $cacheKey = sprintf(self::COURSE_COMMAND_ACTIVITY_KEY, $gradeId, $index);
            $activityFlag = true;
        }

        if(false === $activityFlag) {
            return self::getCourseCommandByCourseId($courseId, $arrExtend);
        }

        //从cache获取推荐数据，cache数据由直播课维护
        $objMemcached = Hk_Service_Memcached::getInstance("zhiboke");
        $strValue = $objMemcached->get($cacheKey);
        if(empty($strValue)) {
            Hk_Util_Log::setLog('CourseCommandCached', 0);
            return array();
        }

        $arrValue = json_decode($strValue, true);
        Hk_Util_Log::setLog('CourseCommandCached', 1);

        //过滤掉已结束的课程
        $arrCourseList = array();
        foreach($arrValue as $value) {
            if($value['invalidTime'] > time()) {
                $arrCourseList[] = $value;
            }
        }

        //任意取一个
        $count = count($arrCourseList);
        $randKey = rand(0, $count - 1);
        $result = $arrCourseList[$randKey];

        $stageId = Hk_Util_Category::getLearningStageIdByGradeId($gradeId);
        $result['html'] = str_replace('data-stageid="stageid"', "data-stageid=\"$stageId\"", $result['html']);

        return $result;
    }
    
    /**
     * 根据courseId推荐
     *
     * @param  int courseId 课程id
     * @param  array arrExtend 附加参数 
     *           cuid : cuid
     *           recFrom : 推荐方式，laxin_rec为拉新推荐
     *           courseTeacher : 推荐课程对应的老师uid
     * @return string
     */
    public static function getCourseCommandByCourseId($courseId, $arrExtend=array()) {
        if($courseId <= 0) {
            Bd_Log::warning("Error:[param error], Detail:[courseId:$courseId]");
            return false;
        }

        //判断是否命中拉新A/B流量
        if ($arrExtend['cuid']) {
            $cuid = strval($arrExtend['cuid']);
        } else {
            $arrTerminal = Hk_Util_Client::getTerminal();
            $cuid = $arrTerminal['terminal'];
            $arrExtend['cuid'] = $cuid;
        }
        $intCrcCuid = crc32($cuid);
        $intMod = $intCrcCuid % 100;
        $arrExtend['intMod'] = $intMod; // 往下传递
        
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $recUserType  = strval($arrExtend['recUserType']);
        
        //从cache获取推荐数据，cache数据由直播课维护
        $objMemcached = Hk_Service_Memcached::getInstance("zhiboke");
        
        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        $midUrl     = ''; // 跳转中间页 style 要为12
        $from       = ''; // from值
        $arrPicMap = array(
            1 => 'tpl12_three', // 4年级
            2 => 'tpl12_four',  // 初一
            4 => 'tpl15_img',   // 短信导流
            10 => 'tpl13_card1',    // 49497
            11 => 'tpl13_card2',    // 49413 
            12 => 'tpl13_card3',    // 49497
            13 => 'tpl13_card3',    // 49413
            14 => 'tpl13_card4',    // 49429
            15 => 'tpl13_card5',    // 49544
            16 => 'tpl13_card3',    // 49429
            17 => 'tpl13_card3',    // 49544 
            // 市场部v2
            21 => 'tpl13_card11',   // 小3北京
            22 => 'tpl13_card12',   // 小3广东
            23 => 'tpl13_card13',   // 小3湖南
            24 => 'tpl13_card14',   // 小3安徽
            25 => 'tpl13_card15',   // 小3湖北
            26 => 'tpl13_card16',   // 小3江苏
            27 => 'tpl13_card17',   // 小3河北
            28 => 'tpl13_card18',   // 小3浙江
            29 => 'tpl13_card19',   // 小3四川
            30 => 'tpl13_card20',   // 小3其他
            31 => 'tpl13_card11',   // 小6北京
            32 => 'tpl13_card12',   // 小6广东
            33 => 'tpl13_card13',   // 小6湖南
            34 => 'tpl13_card14',   // 小6安徽
            35 => 'tpl13_card15',   // 小6湖北
            36 => 'tpl13_card16',   // 小6江苏
            37 => 'tpl13_card17',   // 小6河北
            38 => 'tpl13_card18',   // 小6浙江
            39 => 'tpl13_card19',   // 小6四川
            40 => 'tpl13_card20',   // 小6其他
            // 短信导流v2
            51 => 'tpl12_second',   // 短信导流
            52 => 'tpl12_second',   // 短信导流
            // 市场部1.3 素材
            61 => 'tpl13_card1',
            62 => 'tpl13_card2',
            63 => 'tpl13_card3',
            64 => 'tpl13_card3',
            65 => 'tpl13_card4',
            66 => 'tpl13_card5',
            67 => 'tpl13_card3',
            68 => 'tpl13_card3',
            // 市场部1.4 大卡片
            91 => 'tpl13_cardthree', // 69971
            92 => 'tpl13_cardsix',   // 69983
            // 市场部1.5 详情页
            101 => 'tpl13_threeimg',
            102 => 'tpl13_six',
            // 社群长期导流
            1001 => 'tpl13_imgthree',
            1002 => 'tpl13_imgthree',
            1003 => 'tpl13_imgsix',
            1004 => 'tpl13_imgsix',
            1005 => 'tpl13_imgkelend',
            1006 => 'tpl13_imgkelend',
            // 一元班课
            20001 => 'tpl13_three_0201',
            20002 => 'tpl13_six_0201',
            20003 => 'tpl13_kalend_0201',
        );
        
        //以下手机强制命中>>>>
        $arrForce = array(
            'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 101,  // 老逻辑   红米 4x
            '84C543C1F68DC839B47F8C9CB277A41C|0'                => 101,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
            '4AFE54F10686541E8AE51563717C81E9|854909930478168'  => 101,  // 新推荐逻辑 oppo r9 真详情页    百分数
            '0AC7AFFCEECE57F4D74637106641EA03|0'                => 101,  // 老推荐逻辑  xiancan
            //'7827EB8E0A8B9332AE40EDED440F8953|DDB1ABA600000A'   => 1,  // 新推荐逻辑 ZTE V0721_8299 非命中tid
            '0d963bb26ba2ec6225d8bdf286fbc999da610b21'          => 101,  // 白燕 ios
            'bf32e42a7662c3da3c6189029489b8b71621d985'          => 101,  // 王建ios
        );
        
        if (0 < $arrForce[$cuid]) {
            $arrExtend['recUserType'] = 'laxin_user';
            $recUserType              = $arrExtend['recUserType'];
        }

        do {
            // 不再处理了，默认卡片样式
            //break;
            
            if ('laxin_user' !== $recUserType) {
                Bd_Log::addNotice('RecTiyanAB', 'notnew');
                // 老的推荐逻辑
                break;
            }
            
            // 控制策略
            // 返回 style
            // distype
            // pictype
            // toCourseId
            
            // 一元班课
            $strategyRet = Hk_Service_RecommandStrategy::yiyuanBanke($courseId, $arrExtend);
            if ($strategyRet['style'] > 0) {
                $style      = intval($strategyRet['style']);
                $pictype    = intval($strategyRet['pictype']);
                $toCourseId = intval($strategyRet['toCourseId']);
                $midUrl     = strval($strategyRet['midUrl']);
                $from       = strval($strategyRet['disFrom']);
                Bd_Log::addNotice('RecTiyanAB', 'match1banke');
                break;
            }
            
            // 社群长期导流
            $strategyRet = Hk_Service_RecommandStrategy::shequnDaoliu($courseId, $arrExtend);
            if ($strategyRet['style'] > 0) {
                $style      = intval($strategyRet['style']);
                $pictype    = intval($strategyRet['pictype']);
                $toCourseId = intval($strategyRet['toCourseId']);
                Bd_Log::addNotice('RecTiyanAB', 'matchshequndaoliu');
                break;
            }
            break;
        } while(0);
        
        
        //默认style都是5，而且cachekey是不带后缀的
        $cacheKey = sprintf(self::COURSE_COMMAND_COURSE_KEY, $courseId);
        if ( (12 == $style) || (13 == $style) ) {
            $cacheKey = sprintf(self::COURSE_COMMAND_COURSE_STYLE, $courseId, $style);
        }
        $strValue = $objMemcached->get($cacheKey);
        if(empty($strValue)) {
            Hk_Util_Log::setLog('CourseCommandCached', 0);
            return array();
        }

        $arrValue = json_decode($strValue, true);
        Bd_Log::addNotice('RecTiyanStyle', $style);
        Bd_Log::addNotice('RecTiyanPictype', $pictype);
        Bd_Log::addNotice('RecTiyanDistype', $distype);
        Bd_Log::addNotice('RecTiyanCourseType', $arrValue['courseType']);
        Bd_Log::addNotice('RecTiyanTeacherId', $teacherid);
        Bd_Log::addNotice('RecTiyanToCourseId', $toCourseId);
        Bd_Log::addNotice('RecTiyanMidUrl', $midUrl);
        
        Hk_Util_Log::setLog('CourseCommandCached', 1);

        if ($arrValue && $arrValue['html']) {
            if (0 < $style) { //5和12和13有distype的替换
                if (0 <= $distype) { //对于style=5/12的默认模版，需要替换
                    $arrValue['html'] = str_replace('data-display-type="distype"', "data-display-type=\"{$distype}\"", $arrValue['html']);
                }
                if (0 <= $pictype) { //对于style=5/13的默认模版，需要替换
                    $arrValue['html'] = str_replace('data-display-pictype="pictype"', "data-display-pictype=\"{$pictype}\"", $arrValue['html']);
                }
                if (0 < $teacherid) { 
                    $arrValue['html'] = str_replace('data-display-teacherid="teacherid"', "data-display-teacherid=\"${teacherid}\"", $arrValue['html']);
                }
                if ($arrPicMap[$pictype]) { //图片替换
                    $arrValue['html'] = str_replace('<span class="tpl_card"></span>', "<span class=\"tpl_card {$arrPicMap[$pictype]}\"></span>", $arrValue['html']);
                    $arrValue['html'] = str_replace('<img class="tpl_card no-padding" src="imageurl"/>', '', $arrValue['html']);
                }
                // 指定跳转课程id
                if ( 0 < $toCourseId ) {
                    $arrValue['html'] = str_replace("data-courseid=\"{$courseId}\"", "data-courseid=\"{$toCourseId}\"", $arrValue['html']);
                }
                // 中间页url
                if (12 == $style && $midUrl) {
                    $arrValue['html'] = str_replace('data-url="jumpurl"', "data-url=\"{$midUrl}\"", $arrValue['html']);
                }
                // from值
                if ($from) {
                    $arrValue['html'] = str_replace('data-from="search-fd-default"', "data-from=\"{$from}\"", $arrValue['html']);
                }
            }
        }

        return $arrValue;
    }

    /**
     * 根据ip/年级/学科获取微信公众号的展现数据
     *
     * @param  array ipInfo 用户地理信息，可通过Hk_Ds_User_IpInfo::getExtInfoByIp()获得
     * @param  int grade 年级
     * @param  int course  学科
     * @param  array $arrExtend 扩展字段
     * @return array
     */
    public static function getWeixinGzhTag($ipInfo, $grade, $course, $arrExtend = []) {

        return array(); //先关闭入口
        
        // 社群长期导流 先定死 100 > uid >= 60
        // 数学 
        // 三年级 六年级 初一 
        
        
        
        // 推广拉新微信号
        $arrWeixinTag = [];
        
        if ($arrExtend['cuid']) {
            $cuid = strval($arrExtend['cuid']);
        } else {
            $arrTerminal = Hk_Util_Client::getTerminal();
            $cuid = $arrTerminal['terminal'];
        }
        $intCrcCuid = crc32($cuid);
        $intMod = $intCrcCuid % 100;
        $recUserType = strval($arrExtend['recUserType']); // 用户类型
        
        $arrForce = [
            '0AC7AFFCEECE57F4D74637106641EA03|0' => 1, // 献灿
            '84C543C1F68DC839B47F8C9CB277A41C|0' => 1, // 新推荐逻辑 honor 6x 假详情页 长方形面积
            'bf32e42a7662c3da3c6189029489b8b71621d985' => 1,
        ];
        if ($arrForce[$cuid]) {
            $intMod = time() % 100;
            $intMod = ($intMod > 50) ? 100 : 100; // 50%几率命中不同的策略
            $gradeTmp = [0 => 13, 1=> 16, 2 => 2];
            $modIdx   = time() % 3;
            $grade    = $gradeTmp[$modIdx];
        }
        Bd_Log::addNotice('WxGzhCuidMod', $intMod);
        
        // 新用户
        if ('laxin_user' != $recUserType) {
            Bd_Log::addNotice('WxGzhExcept', 'notnew');
            return $arrWeixinTag;
        }
        // 切40%的流量
        if ($intMod < 60) {
            Bd_Log::addNotice('WxGzhExcept', 'fenliu');
            return $arrWeixinTag;
        }
        // 登录
        if (0 >= $arrExtend['uid']) {
            Bd_Log::addNotice('WxGzhExcept', 'login');
            return $arrWeixinTag;
        }
        
        // 数学
        if ($course != 2) {
            Bd_Log::addNotice('WxGzhExcept', 'CNotMatch');
            return $arrWeixinTag;
        }
        
        // 年级
        $arrGrade = [13 => 1, 16 => 1, 2 => 1];
        if (!isset($arrGrade[$grade])) {
            Bd_Log::addNotice('WxGzhExcept', 'GNotMatch');
            return $arrWeixinTag;
        }
        
        $objUcloud = new Hk_Ds_User_Ucloud();
        $userInfo  = $objUcloud->getUserInfoAll($arrExtend['uid']);
        // 家长身份、3年级
        $arrRole = [3 => 1, 4 => 1, 5 => 1, 6 => 1];
        $urole  = intval($userInfo['ext']['playRole']);
        if (!isset($arrRole[$urole])) {
            Bd_Log::addNotice('WxGzhExcept', 'playRoleOrGrade');
            return $arrWeixinTag;
        }
        // 符合条件的用户 推微信号 
        $pictype = -1;
        $tplcard = '';
        if (13 == $grade) {
            $pictype = 1001;
            $tplcard = 'tpl13_imgthree';
        }
        if (16 == $grade) {
            $pictype = 1003;
            $tplcard = 'tpl13_imgsix';
        }
        if (2 == $grade) {
            $pictype = 1005;
            $tplcard = 'tpl13_imgkelend';
        }
        // 其实没有什么用，前端都是写死的
        $arrWeixinTag = [
            'wxCodeSrc'    => 'https://teacher.zuoyebang.cc/newuserindex/fenlie_wechatv3.png',
            'wxCodeName'   => '一课小助手',
            'gradeId'      => $grade,
            'courseId'     => $course,
            'contentTitle' => '请添加我们的"一课小助手"为好友，领取免费课程',
            'pictype'      => $pictype,
            'tplcard'      => $tplcard,
        ];
        Bd_Log::addNotice('pictype', $arrWeixinTag['pictype']);
        Bd_Log::addNotice('tplcard', $arrWeixinTag['tplcard']);
        Bd_Log::addNotice('WxGzhExcept', 'pass');
        return $arrWeixinTag;
        
    }

    //根据年级，学科获取微信公众号的信息
    /**
     * 根据ip/年级/学科获取微信公众号的展现数据
     *
     * @param  array arrWeixinTag 必须信息，可通过Hk_Service_CourseCommand::getWeixinGzhTag()获得
     * @return array
     */
    static public function getWeixinGzhInfo($arrWeixinTag) {
        $wxInfo = array();
        if (!$arrWeixinTag || !is_array($arrWeixinTag) || !$arrWeixinTag['wxCodeSrc'] || !$arrWeixinTag['wxCodeName']) {
            return $wxInfo;
        }
        if (!$arrWeixinTag['gradeId'] || !$arrWeixinTag['courseId'] || !$arrWeixinTag['contentTitle']) {
            return $wxInfo;
        }

        $strWeixinHtml = '';

        $objMemZhibo = Hk_Service_Memcached::getInstance("zhiboke");
        $idx = rand(1, Hk_Service_CourseCommand::COURSE_COMMAND_GONGZHONGHAO_TIMES); //分流
        $myCacheKey = sprintf(Hk_Service_CourseCommand::COURSE_COMMAND_GONGZHONGHAO_KEY, $idx);
        $strWxGzhCache = $objMemZhibo->get($myCacheKey);
        if ($strWxGzhCache) {
            $arrWxGzhCanche = json_decode($strWxGzhCache, true);
            if ($arrWxGzhCanche && $arrWxGzhCanche['html']) {
                $strWeixinHtml = strval($arrWxGzhCanche['html']);
            }
        }

        if (!$strWeixinHtml) {
            return $wxInfo;
        }

        $hackTag = 'data-gradeid=""';
        $replaceTag = "data-gradeid=\"{$arrWeixinTag['gradeId']}\"";
        if(($intPos = strpos($strWeixinHtml, $hackTag)) !== false){
            $strWeixinHtml = substr_replace($strWeixinHtml, $replaceTag, $intPos, strlen($hackTag));
        }
        $hackTag = 'data-subjectid=""';
        $replaceTag = "data-subjectid=\"{$arrWeixinTag['courseId']}\"";
        if(($intPos = strpos($strWeixinHtml, $hackTag)) !== false){
            $strWeixinHtml = substr_replace($strWeixinHtml, $replaceTag, $intPos, strlen($hackTag));
        }
        $hackTag = 'data-wxcodesrc="wxcodesrc"';
        $replaceTag = "data-wxcodesrc=\"{$arrWeixinTag['wxCodeSrc']}\"";
        if(($intPos = strpos($strWeixinHtml, $hackTag)) !== false){
            $strWeixinHtml = substr_replace($strWeixinHtml, $replaceTag, $intPos, strlen($hackTag));
        }
        $hackTag = 'data-wxcodename="wxcodename"';
        $replaceTag = "data-wxcodename=\"{$arrWeixinTag['wxCodeName']}\"";
        if(($intPos = strpos($strWeixinHtml, $hackTag)) !== false){
            $strWeixinHtml = substr_replace($strWeixinHtml, $replaceTag, $intPos, strlen($hackTag));
        }
        $hackTag = '<div class="tpl14_text">学习资料，限量500套</div>';
        $replaceTag = "<div class=\"tpl14_text\">{$arrWeixinTag['contentTitle']}学习资料，限量500套</div>";
        if(($intPos = strpos($strWeixinHtml, $hackTag)) !== false){
            $strWeixinHtml = substr_replace($strWeixinHtml, $replaceTag, $intPos, strlen($hackTag));
        }
        // pictype 打点
        if (0 <= $arrWeixinTag['pictype']) { 
            $strWeixinHtml = str_replace('data-display-pictype="pictype"', "data-display-pictype=\"{$arrWeixinTag['pictype']}\"", $strWeixinHtml);
        }
        // card
        if ($arrWeixinTag['tplcard']) { //图片替换
            $strWeixinHtml = str_replace('<span class="tpl_card"></span>', "<span class=\"tpl_card {$arrWeixinTag['tplcard']}\"></span>", $strWeixinHtml);
        }

        $wxInfo['html'] = $strWeixinHtml;

        return $wxInfo;
    }
    
    // 城市的映射，课程推荐系统用
    static public $PROVINCE_MAP = [
        '北京' => 1,
        '北京市' => 1,
        '上海' => 2,
        '上海市' => 2,
        '天津' => 3,
        '天津市' => 3,
        '重庆' => 4,
        '天津市' => 3,
        '江苏' => 5,
        '江苏省' => 5,
        '河北' => 6,
        '河北省' => 6,
        '新疆' => 7,
        '新疆维吾尔自治区' => 7,
        '山东' => 8,
        '山东省' => 8,
        '青海' => 9,
        '青海省' => 9,
        '山西' => 10,
        '山西省' => 10,
        '浙江' => 11,
        '浙江省' => 11,
        '湖北' => 12,
        '湖北省' => 12,
        '河南' => 13,
        '河南省' => 13,
        '甘肃' => 14,
        '甘肃省' => 14,
        '宁夏' => 15,
        '宁夏回族自治区' => 15,
        '海南' => 16,
        '海南省' => 16,
        '内蒙古' => 17,
        '内蒙古自治区' => 17,
        '陕西' => 18,
        '陕西省' => 18,
        '湖南' => 19,
        '湖南省' => 19,
        '江西' => 20,
        '江西省' => 20,
        '黑龙江' => 21,
        '黑龙江省' => 21,
        '广东' => 22,
        '广东省' => 22,
        '广西' => 23,
        '广西壮族自治区' => 23,
        '贵州' => 24,
        '贵州省' => 24,
        '吉林' => 25,
        '吉林省' => 25,
        '云南' => 26,
        '云南省' => 26,
        '福建' => 27,
        '福建省' => 27,
        '安徽' => 28,
        '安徽省' => 28,
        '四川' => 29,
        '四川省' => 29,
        '西藏' => 30,
        '西藏自治区' => 30,
        '辽宁' => 31,
        '辽宁省' => 31,
        '香港' => 32,
        '香港特别行政区' => 32,
        '澳门' => 33,
        '澳门特别行政区' => 33,
        '台湾' => 34,
        '台湾省' => 34,
    ];
}
