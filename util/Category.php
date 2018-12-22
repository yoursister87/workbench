<?php
/**
 * @file Category.php
 * @author chenchu01(com@baidu.com)
 * @date 2013-12-18
 * @brief 年级 科目 分类
 *
 **/
class Hk_Util_Category {

    // 学段定义
    const LEARNING_STAGE_PRIMARY   = 1;
    const LEARNING_STAGE_JUNIOR    = 2;
    const LEARNING_STAGE_SENIOR    = 3;
    const LEARNING_STAGE_PRESCHOOL = 4;     # 学前学段，2018-03-06

    // 学段定义与年级匹配
    const GRADE_STAGE_PRIMARY   = 1;
    const GRADE_STAGE_JUNIOR    = 20;
    const GRADE_STAGE_SENIOR    = 30;
    const GRADE_STAGE_PRESCHOOL = 60;       # 学前，2018-03-06

    /**
     * 用户角色定义，用户使用作业帮的角色<br>
     * 作为用户基础信息存在
     *
     * @date 2018-01-16
     * @var hash
     */
    public static $playRoles = array(
        0 => "未设置",
        1 => "学生",
        2 => "老师",
        3 => "家长",
        4 => "爸爸",
        5 => "妈妈",
        6 => "其他亲属",
    );

    /*
     * 年级
     */
    public static $GRADE = array (
        1   => '小学',
        11  => '一年级',
        12  => '二年级',
        13  => '三年级',
        14  => '四年级',
        15  => '五年级',
        16  => '六年级',
        2   => '初一',
        3   => '初二',
        4   => '初三',
        20  => '初中',
        5   => '高一',
        6   => '高二',
        7   => '高三',
        30  => '高中', //答疑，直播
        50  => '高中', //题库
        # 学前学段，60 ~ 69
        60  => '学前',
        61  => '学前班',
        255 => '其他',
    );

    /*
     * 年级关联所有年级
     */
    static public $GRADEMAP = array(
        1   => array(1,11,12,13,14,15,16),
        10  => array(1,10),
        11  => array(1,11),
        12  => array(1,12),
        13  => array(1,13),
        14  => array(1,14),
        15  => array(1,15),
        16  => array(1,16),
        2   => array(20,2),
        3   => array(20,3),
        4   => array(20,4),
        20  => array(20,2,3,4),
        5   => array(30,5),
        6   => array(30,6),
        7   => array(30,7),
        30  => array(30,5,6,7),
        60  => array(60, 61),
        61  => array(60, 61),
    );

    /*
     * 年级关联的学部
     */
    static public $GRADEMAPXB = array(
        1   => 1,
        11  => 1,
        12  => 1,
        13  => 1,
        14  => 1,
        15  => 1,
        16  => 1,
        2   => 20,
        3   => 20,
        4   => 20,
        20  => 20,
        5   => 30,
        6   => 30,
        7   => 30,
        30  => 30,
        60  => 60,
        61  => 60,
    );
    /*
     * 科目
    */
    static public $COURSE = array (
        1 => '语文',
        2 => '数学',
        3 => '英语',
        4 => '物理',
        5 => '化学',
        6 => '生物',
        7 => '政治',
        8 => '历史',
        9 => '地理',
        10=> '兴趣课',//直播课使用
        11=> '思想品德',//直播课使用
        12=> '讲座',//直播课使用
        13=> '理综',//试卷用
        14=> '文综',//试卷用
        15=> '奥数',
        16=> '科学',
    );

    /**
     * 学期查询条件面向检索层面
     * 配合 $SEASONSMAP
     * @var array
     */
    static public $SEASONCONDS = array(
        1   => '"春_1"',
        11  => '"春_2"',
        12  => '"春_3"',
        13  => '"春_4"',
        2   => '"暑_1"',
        21  => '"暑_2"',
        22  => '"暑_3"',
        23  => '"暑_4"',
        3   => '"秋_1"',
        31  => '"秋_2"',
        32  => '"秋_3"',
        33  => '"秋_4"',
        4   => '"寒_1"',
        41  => '"寒_2"',
        42  => '"寒_3"',
        43  => '"寒_4"',
    );

    /**
     * 通过年级gradeId获取学段id
     * @param  integer $gradeId
     * @return integer
     */
    static public function getLearningStageIdByGradeId($gradeId) {
        $stageId = 0;
        if ( (1 == $gradeId) || ((11 <= $gradeId) && (16 >= $gradeId)) ) {
            $stageId = self::LEARNING_STAGE_PRIMARY;
        } elseif ( (20 == $gradeId) || ((2 <= $gradeId) && (4 >= $gradeId)) ) {
            $stageId = self::LEARNING_STAGE_JUNIOR;
        } elseif ( (30 == $gradeId) || ((5 <= $gradeId) && (7 >= $gradeId)) ) {
            $stageId = self::LEARNING_STAGE_SENIOR;
        } elseif ( (60 == $gradeId) || ((61 <= $gradeId) && (69 >= $gradeId)) ) {         # 学前
            $stageId = self::LEARNING_STAGE_PRESCHOOL;
        }
        return $stageId;
    }

    //同步练习科目的学段bitmap
    public static $pracStage = [
        1 => [0, 1, 1],//语文
        2 => [1, 1, 1],//数学
        3 => [0, 1, 1],//英语
        4 => [0, 1, 1],//物理
        5 => [0, 1, 1],//化学
        6 => [0, 1, 1],//生物
    ];

    //同步练习展现的科目
    public static $pracSubject = [
        1  => [2],// '小学',
        11 => [2],// '一年级',
        12 => [2],// '二年级',
        13 => [2],// '三年级',
        14 => [2],// '四年级',
        15 => [2],// '五年级',
        16 => [2],// '六年级',
        2  => [1, 2, 3, 4, 5, 6],// '初一',
        3  => [1, 2, 3, 4, 5, 6],// '初二',
        4  => [1, 2, 3, 4, 5, 6],// '初三',
        5  => [1, 2, 3, 4, 5, 6],// '高一',
        6  => [1, 2, 3, 4, 5, 6],// '高二',
        7  => [1, 2, 3, 4, 5, 6],// '高三',
    ];

    //微练展现科目
    public static $wlTaskSubject = [
        11 => [1],// '一年级',
        12 => [1],// '二年级',
        13 => [1, 2],// '三年级',
        14 => [1, 2, 3],// '四年级',
        15 => [1, 2, 3],// '五年级',
        16 => [1, 2, 3],// '六年级',
        2  => [1, 2, 3],// '初一',
        3  => [1, 2, 3, 4, 5],// '初二',
        4  => [1, 2, 3, 4, 5],// '初三',
        5  => [1, 2, 3, 4, 5, 6, 8],// '高一',
        6  => [1, 2, 3, 4, 5, 6, 8],// '高二',
        7  => [1, 2, 3, 4, 5, 6, 8],// '高三',
    ];

    public static function arrToBit($arr)
    {
        $bitset = 0;
        if (empty($arr)) return $bitset;
        $arrSize = count($arr);
        $binBase = 1;
        for ($i = 0; $i < $arrSize; $i++) {
            if ($arr[$i] > 1 || $arr[$i] < 0) {
                return 0;
            }
            $bitset += $binBase * $arr[$i];
            $binBase *= 2;
        }
        return $bitset;
    }

    //系统定义
    const SYS_TYPE_TUTORMANAGESYS       = 1;
    const SYS_TYPE_TEACHERROLE          = 2;
    static $SYS_TYPE_ARRAY = array(
        self::SYS_TYPE_TUTORMANAGESYS     => '辅导后台系统',
        self::SYS_TYPE_TEACHERROLE        => '主讲端系统',
    );

}