<?php
/**
 * @file RecommandStrategy.php
 * @author sunxiancan@zuoyebang.com
 * @date 2018-03-16
 * @brief 检索课程推荐策略
 *  会有多个策略共存，他们有一个优先级，直到命中一个策略为准
 *  具体策略优先级控制在Hk_Service_CourseCommand中定义，这里只提供基本的判断函数
 **/

class Hk_Service_RecommandStrategy {

    /**
     * 一元班课导流
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    public static function yiyuanBanke($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

        //以下手机强制命中>>>>
        $arrForce = array(
            'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 101,  // 老逻辑   红米 4x
            '84C543C1F68DC839B47F8C9CB277A41C|0'                => 101,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
            '0AC7AFFCEECE57F4D74637106641EA03|0'                => 101,  // 老推荐逻辑  xiancan
        );

        if (0 < $arrForce[$cuid]) {
            $gradeTmp = [0 => 13, 1=> 16, 2 => 2];
            $modIdx   = time() % 3;

            $arrExtend['recGrade']   = $gradeTmp[$modIdx];
            $arrExtend['recSubject'] = 2;
            $intMod = 66;
        }
        //以下手机强制命中<<<<
        Bd_Log::addNotice('RecTiyanIntMod-1banke', $intMod);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市
        $recSubject   = intval($arrExtend['recSubject']);
        $recGrade     = intval($arrExtend['recGrade']);

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        $midUrl     = ''; // 跳转中间页
        $disFrom    = ''; // from值
        do {
            if ($intMod < 50) {
                Bd_Log::addNotice('RecTiyanAB-1banke', 'fenliu');
                break;
            }

            // 小三 小6 初1
            if (16 != $recGrade && 13 != $recGrade && 2 != $recGrade) {
                Bd_Log::addNotice('RecTiyanAB-1banke', 'notMatchGrade');
                break;
            }
            // 小三小6数学
            if ((13 == $recGrade || 16 == $recGrade) && $recSubject != 2 ) {
                Bd_Log::addNotice('RecTiyanAB-1banke', 'notMatchShu');
                break;
            }
            // 初一物理 （语、数、外）
            $arrSubject = [1, 2, 3];
            if (2 == $recGrade && !in_array($recSubject, $arrSubject) ) {
                Bd_Log::addNotice('RecTiyanAB-1banke', 'notMatchWu');
                break;
            }

            $ctime = time();
            if (13 == $recGrade && 2 == $recSubject) {
                $style   = 12;
                $pictype = 20001;
                $midUrl  = 'https://www.zybang.com/course/favorable/salecoursedetail?grade=13&subject=2&from=n>soutiResult|g>13|s>2';
                $disFrom = 'souti_market_pm_20001';
                break;
            }
            if (16 == $recGrade && 2 == $recSubject) {
                $style   = 12;
                $pictype = 20002;
                $midUrl  = 'https://www.zybang.com/course/favorable/salecoursedetail?grade=16&subject=2&from=n>soutiResult|g>16|s>2';
                $disFrom = 'souti_market_pm_20002';
                break;
            }
            if (2 == $recGrade && in_array($recSubject, $arrSubject)) {
                $style   = 12;
                $pictype = 20003;
                $midUrl  = 'https://www.zybang.com/course/favorable/salecoursedetail?grade=2&subject=4&from=n>soutiResult|g>2|s>4';
                $disFrom = 'souti_market_pm_20003';
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-1banke', 'skip');
        } while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
            'midUrl'     => $midUrl,
            'disFrom'    => $disFrom,
        ];
        return $ret;
    }
    /**
     * 社群长期导流
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    public static function shequnDaoliu($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

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
            $gradeTmp = [0 => 13, 1=> 16, 2 => 2];
            $modIdx   = time() % 3;
            $intMod   = 66;
            $arrExtend['recSubject'] = 2;
            $arrExtend['recGrade'] = $gradeTmp[$modIdx];
        }
        //以下手机强制命中<<<<
        Bd_Log::addNotice('RecTiyanIntMod-xiangqing', $intMod);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市
        $recSubject   = intval($arrExtend['recSubject']);
        $recGrade     = intval($arrExtend['recGrade']);

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        do {
            // 下线
            break;
            if ($intMod < 60) {
                Bd_Log::addNotice('RecTiyanAB-shequn', 'fenliu');
                break;
            }
            // 数学
            if ($recSubject != 2) {
                Bd_Log::addNotice('RecTiyanAB-shequn', 'notMatchSubject');
                break;
            }
            // 小三 小6 初1
            if (16 != $recGrade && 13 != $recGrade && 2 != $recGrade) {
                Bd_Log::addNotice('RecTiyanAB-shequn', 'notMatchGrade');
                break;
            }
            // 4成流量做实验
            if (13 == $recGrade) {
                $style   = 12;
                $pictype = 1002;
                break;
            }
            if (16 == $recGrade) {
                $style   = 12;
                $pictype = 1004;
                break;
            }
            if (2 == $recGrade) {
                $style   = 12;
                $pictype = 1006;
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-shequn', 'skip');
        } while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }
    /**
     * 市场1.5需求 - 大卡片
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    public static function shichangXiangqing($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

        //以下手机强制命中>>>>
        $arrForce = array(
            'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 101,  // 老逻辑   红米 4x
            '84C543C1F68DC839B47F8C9CB277A41C|0'                => 101,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
            '4AFE54F10686541E8AE51563717C81E9|854909930478168'  => 101,  // 新推荐逻辑 oppo r9 真详情页    百分数
            '0AC7AFFCEECE57F4D74637106641EA03|0'                => 101,  // 老推荐逻辑  xiancan
            //'7827EB8E0A8B9332AE40EDED440F8953|DDB1ABA600000A'   => 1,  // 新推荐逻辑 ZTE V0721_8299 非命中tid
            '0d963bb26ba2ec6225d8bdf286fbc999da610b21'          => 101,  // 白燕 ios
        );

        if (0 < $arrForce[$cuid]) {
            $gradeTmp = [0 => 13, 1=> 16];
            $modIdx   = time() % 2;
            $intMod   = 33;
            $arrExtend['recSubject'] = 2;
            $arrExtend['recGrade'] = $gradeTmp[$modIdx];
        }
        //以下手机强制命中<<<<
        Bd_Log::addNotice('RecTiyanIntMod-xiangqing', $intMod);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市
        $recSubject   = intval($arrExtend['recSubject']);
        $recGrade     = intval($arrExtend['recGrade']);

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        do {
            // 数学
            if ($recSubject != 2) {
                Bd_Log::addNotice('RecTiyanAB-xaingqing', 'GCNotMatch');
                break;
            }
            // 小三 小6
            if (16 != $recGrade && 13 != $recGrade) {
                Bd_Log::addNotice('RecTiyanAB-xaingqing', 'notMatchGrade');
                break;
            }
            // 4成流量做实验，6层流量做对照组
            if (13 == $recGrade) {
                if ($intMod < 40) {
                    $style   = 13;
                    $pictype = 101;
                    $toCourseId = 73102;
                    break;
                } else {
                    $style   = 5;
                    $pictype = 103;
                    break;
                }
                break;
            }
            if (16 == $recGrade) {
                if ($intMod < 40) {
                    $style   = 13;
                    $pictype = 102;
                    $toCourseId = 72921;
                    break;
                } else {
                    $style   = 5;
                    $pictype = 104;
                    break;
                }
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-xaingqing', 'skip');
        } while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }
    /**
     * 市场1.4需求 - 大卡片
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    public static function shichangDakapian($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

        //以下手机强制命中>>>>
        $arrForce = array(
            'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 101,  // 老逻辑   红米 4x
            '84C543C1F68DC839B47F8C9CB277A41C|0'                => 101,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
            '4AFE54F10686541E8AE51563717C81E9|854909930478168'  => 101,  // 新推荐逻辑 oppo r9 真详情页    百分数
            '0AC7AFFCEECE57F4D74637106641EA03|0'                => 101,  // 老推荐逻辑  xiancan
            //'7827EB8E0A8B9332AE40EDED440F8953|DDB1ABA600000A'   => 1,  // 新推荐逻辑 ZTE V0721_8299 非命中tid
            '0d963bb26ba2ec6225d8bdf286fbc999da610b21'          => 101,  // 白燕 ios
        );

        if (0 < $arrForce[$cuid]) {
            $modTmp = [0 => 33, 1=> 88];
            $modIdx = time() % 2;
            $intMod = $modTmp[$modIdx];
        }
        //以下手机强制命中<<<<
        Bd_Log::addNotice('RecTiyanIntMod-dakapian', $intMod);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市
        $recSubject   = intval($arrExtend['recSubject']);
        $recGrade     = intval($arrExtend['recGrade']);

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        do {
            // 数学
            if ($recSubject != 2) {
                Bd_Log::addNotice('RecTiyanAB-dakapian', 'GCNotMatch');
                break;
            }
            // 小三 小6
            if (16 != $recGrade && 13 != $recGrade) {
                Bd_Log::addNotice('RecTiyanAB-dakapian', 'notMatchGrade');
                break;
            }
            // 4成流量做实验，6层流量做对照组
            if (13 == $recGrade) {
                if ($intMod < 40) {
                    $style   = 13;
                    $pictype = 91;
                    $toCourseId = 69971;
                    break;
                } else {
                    $style   = 5;
                    $pictype = 93;
                    break;
                }
                break;
            }
            if (16 == $recGrade) {
                if ($intMod < 40) {
                    $style   = 13;
                    $pictype = 92;
                    $toCourseId = 69983;
                    break;
                } else {
                    $style   = 5;
                    $pictype = 94;
                    break;
                }
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-dakapian', 'skip');
        } while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }
    /**
     * 市场1.3需求 - 素材
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    public static function shichangSucai($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intCrcCuid = crc32($cuid);
        $intMod = $intCrcCuid % 1000;

        //以下手机强制命中>>>>
        $arrForce = array(
            'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 101,  // 老逻辑   红米 4x
            //'C99F84579FF22C4DEC13340C4643E3BC|777288430229668'  => 1,  // 老逻辑   oppo A57
            '84C543C1F68DC839B47F8C9CB277A41C|0'                => 101,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
            '4AFE54F10686541E8AE51563717C81E9|854909930478168'  => 101,  // 新推荐逻辑 oppo r9 真详情页    百分数
            '0AC7AFFCEECE57F4D74637106641EA03|0'                => 101,  // 老推荐逻辑  xiancan
            //'7827EB8E0A8B9332AE40EDED440F8953|DDB1ABA600000A'   => 1,  // 新推荐逻辑 ZTE V0721_8299 非命中tid
            '0d963bb26ba2ec6225d8bdf286fbc999da610b21'          => 101, // 白燕 ios
        );

        if (0 < $arrForce[$cuid]) {
            $modTmp = [0 => 1, 1 => 200, 2 => 400, 3 => 600, 4 => 800];
            $modIdx = time() % 5;
            $intMod = $modTmp[$modIdx];
        }
        //以下手机强制命中<<<<
        Bd_Log::addNotice('RecTiyanIntMod-sucai', $intMod);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情
        do {
            // 下线
            break;
            // 数学
            if ($tsubject != 2) {
                Bd_Log::addNotice('RecTiyanAB-sucai', 'GCNotMatch');
                break;
            }
            if (0 < $uid) {
                $objUcloud = new Hk_Ds_User_Ucloud();
                $userInfo  = $objUcloud->getUserInfo($uid);
                $uGrade    = intval($userInfo['grade']);
            } else {
                $uGrade    = $cuidGrade;
            }
            // 小三 小6
            if (16 != $uGrade && 13 != $uGrade) {
                Bd_Log::addNotice('RecTiyanAB-sucai', 'notMatchGrade');
                break;
            }
            // 7成流量做实验，3层流量做对照组
            if (13 == $uGrade) {
                if ($intMod < 175) {
                    $style   = 13;
                    $pictype = 61;
                    $toCourseId = 53629;
                    break;
                }
                if ($intMod < 350) {
                    $style   = 13;
                    $pictype = 62;
                    $toCourseId = 53632;
                    break;
                }
                if ($intMod < 525) {
                    $style   = 13;
                    $pictype = 63;
                    $toCourseId = 53629;
                    break;
                }
                if ($intMod < 700) {
                    $style   = 13;
                    $pictype = 64;
                    $toCourseId = 53632;
                    break;
                }
                // 课程卡片
                $style   = 5;
                $pictype = 71;
                break;
            }
            if (16 == $uGrade) {
                if ($intMod < 175) {
                    $style   = 13;
                    $pictype = 65;
                    $toCourseId = 53660;
                    break;
                }
                if ($intMod < 350) {
                    $style   = 13;
                    $pictype = 66;
                    $toCourseId = 53646;
                    break;
                }
                if ($intMod < 525) {
                    $style   = 13;
                    $pictype = 67;
                    $toCourseId = 53660;
                    break;
                }
                if ($intMod < 700) {
                    $style   = 13;
                    $pictype = 68;
                    $toCourseId = 53646;
                    break;
                }
                // 课程卡片
                $style   = 5;
                $pictype = 72;
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-sucai', 'skip');
        } while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }

    /**
     * 市场1.2需求 - 地域
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    static public function shichangDiyu($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情

        do {
            // 2018-03-19 停止
            if (time() > 1521388800) {
                Bd_Log::addNotice('RecTiyanAB-diyu', 'overtime');
                break;
            }
            // 8成流量
            if ($intMod > 80) {
                break;
            }
            // 数学
            if ($tsubject != 2) {
                Bd_Log::addNotice('RecTiyanAB-diyu', 'GCNotMatch');
                break;
            }

            //以下手机强制命中>>>>
            $arrForceTmp = array(
                'D7620C3944874EE8A14303E0B3B9EC9D|551135530235768'  => 13,  // 老逻辑   红米 4x
                '84C543C1F68DC839B47F8C9CB277A41C|0'                => 16,  // 新推荐逻辑 honor 6x 假详情页 长方形面积
                '0d963bb26ba2ec6225d8bdf286fbc999da610b21'          => 13, // 白燕 ios
            );
            //以下手机强制命中<<<<
            // 用户的登录状态
            if (0 < $arrForceTmp[$cuid]) {
                $uGrade = $arrForceTmp[$cuid];
                $tmpIdx = intval((time() % 1000) / 100);
                $tmpArrProvMap = array(0=>'北京', 1=>'广东', 2=>'湖南', 3=>'安徽', 4=>'湖北', 5=>'江苏', 6=>'河北', 7=>'浙江', 8=>'四川', 9=>'广西');
                $ipProvince = $tmpArrProvMap[$tmpIdx];
            } elseif (0 < $uid) {
                $objUcloud = new Hk_Ds_User_Ucloud();
                $userInfo  = $objUcloud->getUserInfo($uid);
                $uGrade    = intval($userInfo['grade']);
            } else {
                $uGrade    = $cuidGrade;
            }
            // 小三 小6
            if (16 != $uGrade && 13 != $uGrade) {
                Bd_Log::addNotice('RecTiyanAB-diyu', 'notMatchGrade');
                break;
            }
            Bd_Log::addNotice('RecTiyanAB-diyu', 'MatchGrade');
            if (13 == $uGrade) {
                // 3年级推一个课
                $toCourseId = 53628;
                // 市场部第二版
                if (('北京市' === $ipProvince) || ('北京' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 21;
                    break;
                }
                if (('广东省' === $ipProvince) || ('广东' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 22;
                    break;
                }
                if (('湖南省' === $ipProvince) || ('湖南' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 23;
                    break;
                }
                if (('安徽省' === $ipProvince) || ('安徽' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 24;
                    break;
                }
                if (('湖北省' === $ipProvince) || ('湖北' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 25;
                    break;
                }
                if (('江苏省' === $ipProvince) || ('江苏' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 26;
                    break;
                }
                if (('河北省' === $ipProvince) || ('河北' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 27;
                    break;
                }
                if (('浙江省' === $ipProvince) || ('浙江' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 28;
                    break;
                }
                if (('四川省' === $ipProvince) || ('四川' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 29;
                    break;
                }
                // 其他
                $style   = 13;
                $pictype = 30;
                break;
            }
            if (16 == $uGrade) {
                // 6年级推一个课
                $toCourseId = 53659;
                // 市场部第二版
                if (('北京市' == $ipProvince) || ('北京' == $ipProvince)) {
                    $style   = 13;
                    $pictype = 31;
                    break;
                }
                if (('广东省' === $ipProvince) || ('广东' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 32;
                    break;
                }
                if (('湖南省' === $ipProvince) || ('湖南' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 33;
                    break;
                }
                if (('安徽省' === $ipProvince) || ('安徽' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 34;
                    break;
                }
                if (('湖北省' === $ipProvince) || ('湖北' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 35;
                    break;
                }
                if (('江苏省' === $ipProvince) || ('江苏' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 36;
                    break;
                }
                if (('河北省' === $ipProvince) || ('河北' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 37;
                    break;
                }
                if (('浙江省' === $ipProvince) || ('浙江' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 38;
                    break;
                }
                if (('四川省' === $ipProvince) || ('四川' === $ipProvince)) {
                    $style   = 13;
                    $pictype = 39;
                    break;
                }
                // 其他
                $style   = 13;
                $pictype = 40;
                break;
            }
        }while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }

    /**
     * app学生短信导流
     * @param $courseId 课程id
     * @param $arrExtend 其他参数
     */
    static public function appStuDuanxin($courseId, $arrExtend) {
        $arrExtend = is_array($arrExtend)? $arrExtend : [];
        $cuid      = strval($arrExtend['cuid']);
        $intMod    = intval($arrExtend['intMod']);

        $tid          = intval($arrExtend['tid']); // 检索答案
        $tsubject     = intval($arrExtend['tsubject']); // 检索答案 对应的学科
        $tgrade       = intval($arrExtend['tgrade']);   // 检索答案 对应年级
        $cuidGrade    = intval($arrExtend['cuidGrade']);
        $teacherid    = intval($arrExtend['courseTeacher']); // 老师uid
        $uid          = intval($arrExtend['uid']);
        $ipCity       = strval($arrExtend['ipCity']); // 用户城市
        $ipProvince   = strval($arrExtend['ipProvince']); // 用户城市

        $style     = 0; //5为原默认，12为一个课程，13为图片展示
        $distype   = -1; //0默认推荐策略，不跳转中间页；其余都跳中间页，只是方式不一样
        $pictype   = -1; //都跳中间页，0课程卡片，其余为图片
        $toCourseId = -1; // 跳转指定课程详情

        do {
            // 8成流量
            if ($intMod >= 80) {
                // 下线短信
                break;
                // 要登录
                if (0 >= $uid) {
                    Bd_Log::addNotice('RecTiyanAB-appstusms', 'notlogin');
                    break;
                }
                // 过滤北上广深
                $arrFilterProv = array(
                    '北京'   => 1,
                    '北京市' => 1,
                    '上海'   => 1,
                    '上海市' => 1,
                    '广州'   => 1,
                    '广州市' => 1,
                    '深圳'   => 1,
                    '深圳市' => 1,
                );
                // 内网不收限制
                $inner = Hk_Util_Ip::isInnerIp();
                if (!$inner) {
                    if (0 < $arrFilterProv[$ipCity]) {
                        Bd_Log::addNotice('RecTiyanAB-appstusms', 'bsgsCity');
                        break;
                    }
                }
                $objUcloud = new Hk_Ds_User_Ucloud();
                $userInfo  = $objUcloud->getUserInfoAll($uid);
                $urole  = intval($userInfo['ext']['playRole']);
                $uidGrade  = intval($userInfo['grade']);
                // 学生身份
                if (1 != $urole) {
                    Bd_Log::addNotice('RecTiyanAB-appstusms', 'notMatchGrade');
                    break;
                }
                // 4年级
                if (14 != $uidGrade) {
                    Bd_Log::addNotice('RecTiyanAB-appstusms', 'notMatchGrade');
                    break;
                }
                if ($intMod < 70) {
                    // 老模式
                    $style   = 12;
                    $pictype = 51;
                    break;
                } else {
                    // 新模式
                    $style   = 12;
                    $pictype = 52;
                    break;
                }
                Bd_Log::addNotice('RecTiyanAB-appstusms', 'cuidModFenliu');
                break;
            }
        }while(false);

        $ret = [
            'style'   => intval($style),
            'distype' => intval($distype),
            'pictype' => intval($pictype),
            'toCourseId' => intval($toCourseId),
        ];
        return $ret;
    }
}
