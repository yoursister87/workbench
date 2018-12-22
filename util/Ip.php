<?php


/**
 * @name Nik_Util_Ip
 */
class Hk_Util_Ip extends Bd_Ip {

    /**
     * @brief 判断ip是否在指定的网段内（比如判断是否是内网ip）
     *
     * @param string $clinetIp   : 客户端ip （192.168.0.1）
     * @return 是返回true，否则返回false
     * @author zhouzhaopeng
     * @date 2013/10/20 21:25:23
     *
     * 2016.09.03更新方法，改为全按照正则匹配进行IP判断，by ljh
    **/
    public static function isInnerIp($clinetIp = CLIENT_IP) {
        //内网IP判断（127.0.0.1、192.168网段、10网段、172.16网段，172.17网段，172.18网段）
        $strReg = '/(^127\.0\.0\.1)|(^192\.168\.(\d)+\.(\d)+)|(^10\.(\d)+\.(\d)+\.(\d)+)|(^172\.(16|17|18|20|21|22|23|24|25|26|27)\.(\d)+\.(\d)+)/';
        if(preg_match($strReg, $clinetIp)){
            return true;
        }
        // 百度云测试机IP判断
        if ($clinetIp == '180.76.163.240' || $clinetIp == '180.76.168.151' || $clinetIp == '180.76.150.168') {
            return true;
        }
        // 办公网出口IP判断（210.12.147.96/28）
        $strReg = '/(^123\.124\.152\.3[3-9])|(123\.124\.152\.40)|(^114\.255\.79\.6[6-9])|(^114\.255\.79\.7[0-4])/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        // 开拓直播室IP
        $strReg = '/^114\.251\.51\.(19[2-9]|2[0-1][0-9]|22[0-3])/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        // 华夏科技大厦出口
        $strReg = '/^220\.249\.52\.19/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        // 弘源首著大厦出口
        $strReg = '/^111\.200\.54\.19[4-8]/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        // 上地创新大厦出口
        $strReg = '/^123\.124\.16\.83/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        // 西安班主任出口
        $strReg = '/^123\.139\.46\.181/';
        if (preg_match($strReg, $clinetIp)) {
            return true;
        }
        return false;
    }

    /**
     * 将整数ip转换为字符串的形式，mask指定要隐藏后几段，例如mask=1时，显示为192.169.1.*
     * @param  integer $ip      长整型的ip
     * @param  integer $intMask 表示要将后几个字段显示为*
     * @return [type]           字符串形式的ip
     */
    public static function i2ip($ip, $intMask=0, $reverse=false) {
        $ipSec = explode('.', long2ip($ip));
        if ($reverse !== false) {
            $ipSec = array_reverse($ipSec);
        }
        $len = count($ipSec);
        $end = $len - intval($intMask);
        for ($i = $len-1; $i >= 0 && $i >= $end; $i--) {
            $ipSec[$i] = '*';
        }
        return implode('.', $ipSec);
    }

    /**
     * 将字符串ip转换为网络字节序的长整型格式
     * @param  [type] $ip [description]
     * @return [type]
     */
    public static function ip2ri($ip) {
        $n = sprintf("%u", ip2long($ip));

        /** convert to network order */
        $n = (($n & 0xFF) << 24) | ((($n >> 8) & 0xFF) << 16) | ((($n >> 16) & 0xFF) << 8) | (($n >> 24) & 0xFF);
        return $n;
    }

    /**
     * 生成随机IP
     * @param $ip 基准ip
     * @param $bit
     */
    public static function genRandIp($ip = '10.0.0.1', $bit = 24)
    {
        $value = mt_rand(0, pow(2, $bit));
        $int_ip = ip2long($ip);

        $int_ip = $int_ip >> $bit;
        $int_ip = $int_ip << $bit;
        $int_ip += $value;

        return $int_ip;
    }

    /**
     * @desc 判断ip是否为台湾IP
     * @param string $ip 字符串格式的ip
     * @return boolean   是台湾IP的话返回true，否则返回false
     */
    public static function isTaiwanIp($ip) {
      $longip = sprintf("%u", ip2long($ip));
      switch ($longip % 100000000) {
        case 0:
            if (($longip>=19005440 AND $longip<=19136511)
                OR ($longip>=27262976 AND $longip<=28311551)
            ) {
                return true;
            }
            break;
        case 4:
            if (($longip>=456327168 AND $longip<=456523775)
                OR ($longip>=462618624 AND $longip<=462635007)
                OR ($longip>=468713472 AND $longip<=469237759)
            ) {
                return true;
            }
            break;
        case 9:
            if (($longip>=978714624 AND $longip<=978780159)
                OR ($longip>=979566592 AND $longip<=979599359)
                OR ($longip>=980549632 AND $longip<=980680703)
                OR ($longip>=996671488 AND $longip<=996802559)
                OR ($longip>=997195776 AND $longip<=998244351)
            ) {
                return true;
            }
            break;
        case 10:
            if (($longip>=1019609088 AND $longip<=1019740159)
                OR ($longip>=1022623744 AND $longip<=1022722047)
                OR ($longip>=1022885888 AND $longip<=1023148031)
                OR ($longip>=1024361472 AND $longip<=1024361487)
                OR ($longip>=1024361728 AND $longip<=1024361759)
                OR ($longip>=1024361808 AND $longip<=1024361823)
                OR ($longip>=1024361832 AND $longip<=1024362239)
                OR ($longip>=1024366336 AND $longip<=1024366591)
                OR ($longip>=1024366976 AND $longip<=1024367039)
                OR ($longip>=1024369920 AND $longip<=1024370175)
                OR ($longip>=1024373824 AND $longip<=1024373887)
                OR ($longip>=1024374080 AND $longip<=1024374111)
                OR ($longip>=1024375808 AND $longip<=1024375999)
                OR ($longip>=1024376096 AND $longip<=1024376111)
                OR ($longip>=1024376160 AND $longip<=1024376191)
                OR ($longip>=1024376256 AND $longip<=1024376463)
                OR ($longip>=1024376472 AND $longip<=1024376479)
                OR ($longip>=1024376512 AND $longip<=1024376575)
                OR ($longip>=1024376768 AND $longip<=1024376799)
                OR ($longip>=1024720896 AND $longip<=1024786431)
                OR ($longip>=1025376256 AND $longip<=1025507327)
                OR ($longip>=1027080192 AND $longip<=1027866623)
                OR ($longip>=1027997696 AND $longip<=1028128767)
                OR ($longip>=1037565952 AND $longip<=1037591499)
                OR ($longip>=1037591504 AND $longip<=1038614527)
                OR ($longip>=1039638528 AND $longip<=1039642623)
                OR ($longip>=1072934880 AND $longip<=1072934911)
                OR ($longip>=1087495520 AND $longip<=1087495535)
            ) {
                return true;
            }
            break;
        case 11:
            if (($longip>=1120150032 AND $longip<=1120150039)
                OR ($longip>=1120151696 AND $longip<=1120151711)
                OR ($longip>=1125110928 AND $longip<=1125110943))
            {
                return true;
            }
            break;
        case 12:
            if (($longip>=1296258048 AND $longip<=1296258303)) {
                return true;
            }
            break;
        case 13:
            if (($longip>=1310248400 AND $longip<=1310248415)) {
                return true;
            }
            break;
        case 18:
            if (($longip>=1847066624 AND $longip<=1847590911)
                OR ($longip>=1848803328 AND $longip<=1848819711)
                OR ($longip>=1866674176 AND $longip<=1866678271)
                OR ($longip>=1866858496 AND $longip<=1866989567)
                OR ($longip>=1867513856 AND $longip<=1867775999)
                OR ($longip>=1870495744 AND $longip<=1870497791)
                OR ($longip>=1874329600 AND $longip<=1874460671)
                OR ($longip>=1877721088 AND $longip<=1877737471)
                OR ($longip>=1877999616 AND $longip<=1879048191)
                OR ($longip>=1884164096 AND $longip<=1884168191)
                OR ($longip>=1884176384 AND $longip<=1884184575)
                OR ($longip>=1884186624 AND $longip<=1884188671)
                OR ($longip>=1885863936 AND $longip<=1885995007)
                OR ($longip>=1886986240 AND $longip<=1886990335)
                OR ($longip>=1886994432 AND $longip<=1887005695)
                OR ($longip>=1897222144 AND $longip<=1897226239)
                OR ($longip>=1897242624 AND $longip<=1897250815)
                OR ($longip>=1899855872 AND $longip<=1899888639)
            ) {
                return true;
            }
            break;
        case 19:
            if (($longip>=1908670464 AND $longip<=1908735999)
                OR ($longip>=1914175488 AND $longip<=1914437631)
                OR ($longip>=1914576896 AND $longip<=1914580991)
                OR ($longip>=1914699776 AND $longip<=1915748351)
                OR ($longip>=1921515520 AND $longip<=1921646591)
                OR ($longip>=1921777664 AND $longip<=1921843199)
                OR ($longip>=1925619712 AND $longip<=1925627903)
                OR ($longip>=1931362304 AND $longip<=1931378687)
                OR ($longip>=1932161024 AND $longip<=1932163071)
                OR ($longip>=1932197888 AND $longip<=1932263423)
                OR ($longip>=1934622720 AND $longip<=1934884863)
                OR ($longip>=1934987264 AND $longip<=1934991359)
                OR ($longip>=1940242432 AND $longip<=1940258815)
                OR ($longip>=1946173680 AND $longip<=1946173687)
                OR ($longip>=1946173696 AND $longip<=1946173951)
                OR ($longip>=1949442048 AND $longip<=1949446143)
                OR ($longip>=1950023680 AND $longip<=1950089215)
                OR ($longip>=1952022528 AND $longip<=1952026623)
                OR ($longip>=1953923072 AND $longip<=1953939455)
                OR ($longip>=1960071168 AND $longip<=1960075263)
                OR ($longip>=1960181760 AND $longip<=1960185855)
                OR ($longip>=1964179456 AND $longip<=1964244991)
                OR ($longip>=1966596096 AND $longip<=1966600191)
                OR ($longip>=1966604288 AND $longip<=1966669823)
                OR ($longip>=1969709056 AND $longip<=1969713151)
                OR ($longip>=1986202624 AND $longip<=1986202879)
                OR ($longip>=1986232320 AND $longip<=1986265087)
                OR ($longip>=1989541888 AND $longip<=1989607423)
                OR ($longip>=1990197248 AND $longip<=1990983679)
                OR ($longip>=1994850304 AND $longip<=1995046911)
                OR ($longip>=1997406208 AND $longip<=1997471743)
                OR ($longip>=1997520896 AND $longip<=1997537279)
                OR ($longip>=1998458880 AND $longip<=1998462975)
                OR ($longip>=1998565376 AND $longip<=1998569471)
            ) {
                return true;
            }
            break;
        case 20:
            if (($longip>=2001465344 AND $longip<=2001469439)
                OR ($longip>=2001567744 AND $longip<=2001600511)
                OR ($longip>=2007035904 AND $longip<=2007039999)
                OR ($longip>=2019557376 AND $longip<=2021654527)
                OR ($longip>=2033356800 AND $longip<=2033358847)
                OR ($longip>=2033364992 AND $longip<=2033369087)
                OR ($longip>=2046705664 AND $longip<=2046722047)
                OR ($longip>=2053308416 AND $longip<=2053324799)
                OR ($longip>=2053390336 AND $longip<=2053406719)
                OR ($longip>=2054422528 AND $longip<=2054619135)
                OR ($longip>=2054684672 AND $longip<=2055208959)
                OR ($longip>=2055229440 AND $longip<=2055231487)
                OR ($longip>=2056265728 AND $longip<=2056273919)
                OR ($longip>=2056388608 AND $longip<=2056519679)
                OR ($longip>=2056816512 AND $longip<=2056816639)
                OR ($longip>=2059966464 AND $longip<=2059968511)
                OR ($longip>=2060025856 AND $longip<=2060058623)
                OR ($longip>=2063107664 AND $longip<=2063107671)
                OR ($longip>=2063376384 AND $longip<=2063380479)
                OR ($longip>=2063466496 AND $longip<=2063482879)
                OR ($longip>=2063552512 AND $longip<=2063556607)
                OR ($longip>=2063605760 AND $longip<=2063613951)
                OR ($longip>=2063646720 AND $longip<=2063663103)
                OR ($longip>=2066882560 AND $longip<=2066890751)
                OR ($longip>=2066972672 AND $longip<=2067005439)
                OR ($longip>=2070085632 AND $longip<=2070102015)
                OR ($longip>=2070806528 AND $longip<=2070872063)
                OR ($longip>=2076180480 AND $longip<=2076442623)
                OR ($longip>=2076966912 AND $longip<=2077097983)
                OR ($longip>=2079326208 AND $longip<=2079457279)
                OR ($longip>=2080112640 AND $longip<=2080145407)
                OR ($longip>=2080366592 AND $longip<=2080368639)
                OR ($longip>=2080768000 AND $longip<=2080776191)
                OR ($longip>=2080899072 AND $longip<=2081226751)
                OR ($longip>=2082308096 AND $longip<=2082324479)
                OR ($longip>=2087485440 AND $longip<=2087501823)
                OR ($longip>=2087546880 AND $longip<=2087550975)
                OR ($longip>=2090237952 AND $longip<=2090239999)
                OR ($longip>=2090565632 AND $longip<=2090582015)
                OR ($longip>=2093432832 AND $longip<=2093445119)
                OR ($longip>=2094661632 AND $longip<=2094759935)
            ) {
                return true;
            }
            break;
        case 21:
            if (($longip>=2101272576 AND $longip<=2101276671)
                OR ($longip>=2111832064 AND $longip<=2112487423)
                OR ($longip>=2113683488 AND $longip<=2113683519)
                OR ($longip>=2113683600 AND $longip<=2113683615)
                OR ($longip>=2113683680 AND $longip<=2113683775)
                OR ($longip>=2113684096 AND $longip<=2113684127)
                OR ($longip>=2113684224 AND $longip<=2113684239)
                OR ($longip>=2113684256 AND $longip<=2113684271)
                OR ($longip>=2113684432 AND $longip<=2113684439)
                OR ($longip>=2113684448 AND $longip<=2113684511)
                OR ($longip>=2113684736 AND $longip<=2113684991)
                OR ($longip>=2113685232 AND $longip<=2113685247)
                OR ($longip>=2113686080 AND $longip<=2113686207)
                OR ($longip>=2113686336 AND $longip<=2113686399)
                OR ($longip>=2113686528 AND $longip<=2113687039)
                OR ($longip>=2113688064 AND $longip<=2113688319)
            ) {
                return true;
            }
            break;
        case 22:
            if (($longip>=2258591840 AND $longip<=2258591847)
                OR ($longip>=2258591856 AND $longip<=2258591935)
                OR ($longip>=2258595008 AND $longip<=2258595023)
                OR ($longip>=2258595232 AND $longip<=2258595263)
                OR ($longip>=2258595336 AND $longip<=2258595343)
                OR ($longip>=2258595464 AND $longip<=2258595471)
                OR ($longip>=2258595584 AND $longip<=2258595591)
                OR ($longip>=2258597072 AND $longip<=2258597087)
                OR ($longip>=2258598080 AND $longip<=2258598111)
                OR ($longip>=2258598336 AND $longip<=2258598351)
                OR ($longip>=2258603744 AND $longip<=2258603751)
                OR ($longip>=2258614784 AND $longip<=2258614799)
                OR ($longip>=2261778432 AND $longip<=2261843967)
            ) {
                return true;
            }
            break;
        case 23:
            if (($longip>=2343501824 AND $longip<=2343567359)
                OR ($longip>=2346647552 AND $longip<=2346713087)
                OR ($longip>=2354839552 AND $longip<=2354905087)
                OR ($longip>=2355101696 AND $longip<=2355167231)
                OR ($longip>=2355953664 AND $longip<=2357919743)
            ) {
                return true;
            }
            break;
        case 27:
            if (($longip>=2735538176 AND $longip<=2736848895)) {
                return true;
            }
            break;
        case 28:
            if (($longip>=2824798208 AND $longip<=2824863743)
                OR ($longip>=2849178368 AND $longip<=2849178623)
            ) {
                return true;
            }
            break;
        case 29:
            if (($longip>=2905407744 AND $longip<=2905407999)
                OR ($longip>=2938712064 AND $longip<=2938716159)
                OR ($longip>=2942304256 AND $longip<=2942566399)
                OR ($longip>=2943295488 AND $longip<=2943303679)
                OR ($longip>=2943336448 AND $longip<=2943352831)
                OR ($longip>=2947809280 AND $longip<=2948071423)
                OR ($longip>=2948132864 AND $longip<=2948134911)
            ) {
                return true;
            }
            break;
        case 30:
            if (($longip>=3025928192 AND $longip<=3025932287)
                OR ($longip>=3031433216 AND $longip<=3031564287)
                OR ($longip>=3033268224 AND $longip<=3033530367)
                OR ($longip>=3033968640 AND $longip<=3033972735)
                OR ($longip>=3034120192 AND $longip<=3034251263)
                OR ($longip>=3034500096 AND $longip<=3034501119)
                OR ($longip>=3064791040 AND $longip<=3064807423)
                OR ($longip>=3064808448 AND $longip<=3064809471)
                OR ($longip>=3068723200 AND $longip<=3068919807)
                OR ($longip>=3081846784 AND $longip<=3081847807)
            ) {
                return true;
            }
            break;
        case 32:
            if (($longip>=3225944832 AND $longip<=3226008831)
                OR ($longip>=3226707456 AND $longip<=3226715391)
                OR ($longip>=3233590016 AND $longip<=3233590271)
                OR ($longip>=3233808384 AND $longip<=3233873919)
                OR ($longip>=3262473986 AND $longip<=3262473986)
                OR ($longip>=3262473994 AND $longip<=3262473995)
                OR ($longip>=3262473999 AND $longip<=3262473999)
                OR ($longip>=3262474001 AND $longip<=3262474001)
                OR ($longip>=3262474003 AND $longip<=3262474003)
                OR ($longip>=3262474005 AND $longip<=3262474005)
                OR ($longip>=3262474008 AND $longip<=3262474009)
                OR ($longip>=3262474039 AND $longip<=3262474039)
                OR ($longip>=3262474064 AND $longip<=3262474064)
                OR ($longip>=3262474071 AND $longip<=3262474071)
                OR ($longip>=3262474084 AND $longip<=3262474084)
                OR ($longip>=3262474116 AND $longip<=3262474116)
                OR ($longip>=3262474119 AND $longip<=3262474119)
                OR ($longip>=3262474121 AND $longip<=3262474121)
                OR ($longip>=3262474137 AND $longip<=3262474137)
                OR ($longip>=3262474140 AND $longip<=3262474140)
                OR ($longip>=3262474168 AND $longip<=3262474168)
                OR ($longip>=3262474174 AND $longip<=3262474174)
                OR ($longip>=3262474178 AND $longip<=3262474178)
                OR ($longip>=3262474210 AND $longip<=3262474210)
                OR ($longip>=3278939944 AND $longip<=3278939947)
                OR ($longip>=3278939968 AND $longip<=3278939971)
                OR ($longip>=3278940068 AND $longip<=3278940071)
                OR ($longip>=3278940128 AND $longip<=3278940131)
                OR ($longip>=3278942208 AND $longip<=3278942211)
                OR ($longip>=3278942540 AND $longip<=3278942543)
                OR ($longip>=3278942552 AND $longip<=3278942555)
                OR ($longip>=3278942580 AND $longip<=3278942583)
                OR ($longip>=3278942592 AND $longip<=3278942595)
                OR ($longip>=3278942604 AND $longip<=3278942607)
                OR ($longip>=3278942680 AND $longip<=3278942683)
                OR ($longip>=3278942700 AND $longip<=3278942703)
            ) {
                return true;
            }
            break;
        case 33:
            if (($longip>=3389142016 AND $longip<=3389143039)
                OR ($longip>=3389235200 AND $longip<=3389243391)
                OR ($longip>=3389303040 AND $longip<=3389303295)
                OR ($longip>=3389326336 AND $longip<=3389326847)
                OR ($longip>=3389327360 AND $longip<=3389329407)
                OR ($longip>=3389382656 AND $longip<=3389390847)
                OR ($longip>=3389417472 AND $longip<=3389417983)
                OR ($longip>=3389525504 AND $longip<=3389526015)
                OR ($longip>=3389917184 AND $longip<=3389919231)
                OR ($longip>=3391553536 AND $longip<=3391619071)
                OR ($longip>=3391721984 AND $longip<=3391722239)
                OR ($longip>=3391846400 AND $longip<=3391847423)
                OR ($longip>=3392430272 AND $longip<=3392430303)
                OR ($longip>=3392432512 AND $longip<=3392432543)
                OR ($longip>=3392659456 AND $longip<=3392660831)
                OR ($longip>=3392660848 AND $longip<=3392660911)
                OR ($longip>=3392660928 AND $longip<=3392667647)
                OR ($longip>=3394267136 AND $longip<=3394269183)
                OR ($longip>=3394861312 AND $longip<=3394862079)
                OR ($longip>=3397271552 AND $longip<=3397275647)
                OR ($longip>=3397566264 AND $longip<=3397566271)
                OR ($longip>=3397648384 AND $longip<=3397713919)
                OR ($longip>=3397771264 AND $longip<=3397775887)
                OR ($longip>=3397775920 AND $longip<=3397777535)
                OR ($longip>=3397777552 AND $longip<=3397777583)
                OR ($longip>=3397777600 AND $longip<=3397777791)
                OR ($longip>=3397777808 AND $longip<=3397777839)
                OR ($longip>=3397777856 AND $longip<=3397778175)
                OR ($longip>=3397778192 AND $longip<=3397779455)
                OR ($longip>=3398488064 AND $longip<=3398492159)
                OR ($longip>=3398508544 AND $longip<=3398565887)
                OR ($longip>=3398638176 AND $longip<=3398638191)
                OR ($longip>=3398638368 AND $longip<=3398638383)
                OR ($longip>=3398638400 AND $longip<=3398638415)
                OR ($longip>=3398638480 AND $longip<=3398638495)
                OR ($longip>=3398639264 AND $longip<=3398639271)
                OR ($longip>=3398647296 AND $longip<=3398647551)
                OR ($longip>=3398750208 AND $longip<=3398754303)
                OR ($longip>=3398905856 AND $longip<=3398909951)
                OR ($longip>=3399065600 AND $longip<=3399074495)
                OR ($longip>=3399074528 AND $longip<=3399075487)
                OR ($longip>=3399075504 AND $longip<=3399076431)
                OR ($longip>=3399076448 AND $longip<=3399076607)
                OR ($longip>=3399076640 AND $longip<=3399076687)
                OR ($longip>=3399076704 AND $longip<=3399077079)
                OR ($longip>=3399077088 AND $longip<=3399077359)
                OR ($longip>=3399077376 AND $longip<=3399077887)
                OR ($longip>=3399139328 AND $longip<=3399147519)
                OR ($longip>=3399499776 AND $longip<=3399507967)
                OR ($longip>=3399841792 AND $longip<=3399846399)
                OR ($longip>=3399846408 AND $longip<=3399846919)
                OR ($longip>=3399846936 AND $longip<=3399847247)
                OR ($longip>=3399847264 AND $longip<=3399852031)
            ) {
                return true;
            }
            break;
        case 34:
            if (($longip>=3400056832 AND $longip<=3400060927)
                OR ($longip>=3400114176 AND $longip<=3400118271)
                OR ($longip>=3400343552 AND $longip<=3400351743)
                OR ($longip>=3400401920 AND $longip<=3400402175)
                OR ($longip>=3400404992 AND $longip<=3400409087)
                OR ($longip>=3400695808 AND $longip<=3400728575)
                OR ($longip>=3401642496 AND $longip<=3401642751)
                OR ($longip>=3409969152 AND $longip<=3410755583)
                OR ($longip>=3410821120 AND $longip<=3410853887)
                OR ($longip>=3410886656 AND $longip<=3410887679)
                OR ($longip>=3410931712 AND $longip<=3410935807)
                OR ($longip>=3410984960 AND $longip<=3411017727)
                OR ($longip>=3411269632 AND $longip<=3411270143)
                OR ($longip>=3411313152 AND $longip<=3411313663)
                OR ($longip>=3411316736 AND $longip<=3411318783)
                OR ($longip>=3411738624 AND $longip<=3411746815)
                OR ($longip>=3411857408 AND $longip<=3411857663)
                OR ($longip>=3412049920 AND $longip<=3412058111)
                OR ($longip>=3412249472 AND $longip<=3412249599)
                OR ($longip>=3412250376 AND $longip<=3412250383)
                OR ($longip>=3412250512 AND $longip<=3412250527)
                OR ($longip>=3412251392 AND $longip<=3412251647)
                OR ($longip>=3412713472 AND $longip<=3412721663)
                OR ($longip>=3413102592 AND $longip<=3413106687)
                OR ($longip>=3413565440 AND $longip<=3413569535)
                OR ($longip>=3413574400 AND $longip<=3413574655)
                OR ($longip>=3413762048 AND $longip<=3413770239)
                OR ($longip>=3414491136 AND $longip<=3414523903)
                OR ($longip>=3414638592 AND $longip<=3414646783)
                OR ($longip>=3415326720 AND $longip<=3415334911)
                OR ($longip>=3415497728 AND $longip<=3415497983)
                OR ($longip>=3416297472 AND $longip<=3416301567)
                OR ($longip>=3416317952 AND $longip<=3416326143)
                OR ($longip>=3416475648 AND $longip<=3416475903)
                OR ($longip>=3416478464 AND $longip<=3416478479)
                OR ($longip>=3416478528 AND $longip<=3416478543)
                OR ($longip>=3416478672 AND $longip<=3416478687)
                OR ($longip>=3416478848 AND $longip<=3416478911)
                OR ($longip>=3416480256 AND $longip<=3416480383)
                OR ($longip>=3416488579 AND $longip<=3416488579)
                OR ($longip>=3416506368 AND $longip<=3416514559)
                OR ($longip>=3418030080 AND $longip<=3418062847)
                OR ($longip>=3418144768 AND $longip<=3418148863)
                OR ($longip>=3418230784 AND $longip<=3418232831)
                OR ($longip>=3418394368 AND $longip<=3418394623)
                OR ($longip>=3418396424 AND $longip<=3418396479)
                OR ($longip>=3418396544 AND $longip<=3418396575)
                OR ($longip>=3418396672 AND $longip<=3418396703)
                OR ($longip>=3418396720 AND $longip<=3418396735)
                OR ($longip>=3418396752 AND $longip<=3418396775)
                OR ($longip>=3418396784 AND $longip<=3418396799)
                OR ($longip>=3418396816 AND $longip<=3418396831)
                OR ($longip>=3418396840 AND $longip<=3418396863)
                OR ($longip>=3418396896 AND $longip<=3418396927)
                OR ($longip>=3418399472 AND $longip<=3418399487)
                OR ($longip>=3418401648 AND $longip<=3418401719)
                OR ($longip>=3418401728 AND $longip<=3418401799)
                OR ($longip>=3418401808 AND $longip<=3418401823)
                OR ($longip>=3418401904 AND $longip<=3418401919)
                OR ($longip>=3418401936 AND $longip<=3418401983)
                OR ($longip>=3418402016 AND $longip<=3418402031)
                OR ($longip>=3418404160 AND $longip<=3418404175)
                OR ($longip>=3418406712 AND $longip<=3418406719)
                OR ($longip>=3418406784 AND $longip<=3418406799)
                OR ($longip>=3418406832 AND $longip<=3418406847)
                OR ($longip>=3418509056 AND $longip<=3418509119)
                OR ($longip>=3418510272 AND $longip<=3418510279)
                OR ($longip>=3418510329 AND $longip<=3418510329)
                OR ($longip>=3418510720 AND $longip<=3418510847)
                OR ($longip>=3418511408 AND $longip<=3418511415)
                OR ($longip>=3418511424 AND $longip<=3418511439)
                OR ($longip>=3418513216 AND $longip<=3418513231)
                OR ($longip>=3418513248 AND $longip<=3418513279)
                OR ($longip>=3418644992 AND $longip<=3418645247)
                OR ($longip>=3418646784 AND $longip<=3418647039)
                OR ($longip>=3418955776 AND $longip<=3418959871)
                OR ($longip>=3419078656 AND $longip<=3419209727)
                OR ($longip>=3419340800 AND $longip<=3419344895)
                OR ($longip>=3419348992 AND $longip<=3419353087)
                OR ($longip>=3419518976 AND $longip<=3419519999)
                OR ($longip>=3419602944 AND $longip<=3419611135)
                OR ($longip>=3420020736 AND $longip<=3420028927)
                OR ($longip>=3420323840 AND $longip<=3420332031)
                OR ($longip>=3420365848 AND $longip<=3420365855)
                OR ($longip>=3420366048 AND $longip<=3420366055)
                OR ($longip>=3420366064 AND $longip<=3420366079)
                OR ($longip>=3420366640 AND $longip<=3420366647)
                OR ($longip>=3420367776 AND $longip<=3420367791)
                OR ($longip>=3420368936 AND $longip<=3420368943)
                OR ($longip>=3420369240 AND $longip<=3420369255)
                OR ($longip>=3420372736 AND $longip<=3420372767)
            ) {
                return true;
            }
            break;
        case 35:
            if (($longip>=3512011808 AND $longip<=3512011823)
                OR ($longip>=3512016576 AND $longip<=3512016591)
                OR ($longip>=3524329472 AND $longip<=3524362239)
                OR ($longip>=3527016448 AND $longip<=3527933951)
                OR ($longip>=3528474624 AND $longip<=3528482815)
                OR ($longip>=3528785920 AND $longip<=3528851455)
                OR ($longip>=3535798272 AND $longip<=3535814655)
                OR ($longip>=3535831040 AND $longip<=3535863807)
                OR ($longip>=3536322560 AND $longip<=3536551935)
                OR ($longip>=3536846848 AND $longip<=3536928767)
                OR ($longip>=3536945152 AND $longip<=3536977919)
                OR ($longip>=3538944000 AND $longip<=3539271679)
                OR ($longip>=3541303296 AND $longip<=3541565439)
                OR ($longip>=3544711168 AND $longip<=3545235455)
                OR ($longip>=3560944896 AND $longip<=3560944899)
                OR ($longip>=3560944952 AND $longip<=3560944955)
                OR ($longip>=3560945044 AND $longip<=3560945047)
                OR ($longip>=3560945108 AND $longip<=3560945111)
                OR ($longip>=3560945132 AND $longip<=3560945135)
                OR ($longip>=3560945411 AND $longip<=3560945411)
                OR ($longip>=3560945429 AND $longip<=3560945429)
                OR ($longip>=3560945434 AND $longip<=3560945434)
                OR ($longip>=3560945438 AND $longip<=3560945438)
                OR ($longip>=3560945459 AND $longip<=3560945459)
                OR ($longip>=3560945473 AND $longip<=3560945473)
                OR ($longip>=3560945477 AND $longip<=3560945477)
                OR ($longip>=3560945588 AND $longip<=3560945591)
            ) {
                return true;
            }
            break;
        case 36:
            if (($longip>=3632838400 AND $longip<=3632838431)
                OR ($longip>=3659530240 AND $longip<=3659595775)
                OR ($longip>=3659661312 AND $longip<=3659792383)
                OR ($longip>=3667918848 AND $longip<=3668967423)
                OR ($longip>=3669491712 AND $longip<=3669557247)
                OR ($longip>=3669688320 AND $longip<=3669753855)
                OR ($longip>=3671195648 AND $longip<=3671326719)
                OR ($longip>=3678666752 AND $longip<=3678928895)
                OR ($longip>=3679453184 AND $longip<=3679584255)
                OR ($longip>=3679715328 AND $longip<=3679977471)
                OR ($longip>=3680108544 AND $longip<=3680124927)
                OR ($longip>=3680174080 AND $longip<=3680206847)
                OR ($longip>=3699376128 AND $longip<=3700424703)
            ) {
                return true;
            }
            break;
        case 37:
            if (($longip>=3701305344 AND $longip<=3701309439)
                OR ($longip>=3705929728 AND $longip<=3706060799)
                OR ($longip>=3707207680 AND $longip<=3707208703)
                OR ($longip>=3715629056 AND $longip<=3715653631)
                OR ($longip>=3718840320 AND $longip<=3718905855)
                OR ($longip>=3734765568 AND $longip<=3734896639)
                OR ($longip>=3740925952 AND $longip<=3741024255)
            ) {
                return true;
            }
            break;
        default:
            return false;
      }
      return false;
    }

    /**
     * @brief 将点分10进制的ip地址转成二进制字符串
     *
     * @param string $ip   : ip地址 （192.168.0.1）
     * @return 成功返回 1100000101010000000000000000001,否则返回false
     * @author zhouzhaopeng
     * @date 2013/10/20 21:20:42
    **/
    public static function ip2bin($ip) {
        $arrIp = explode('.', $ip);
        if (false === $arrIp || count($arrIp) != 4) {
            # Bd_Log::warning('ip invalid, ip:', $ip); 放弃打日志，太频繁了
            return false;
        }

        //将ip分段转成二进制串
        $binIp = '';
        for ($i=0; $i<4; ++$i) {
            $bin = decbin(intval($arrIp[$i]));
            //如果不足8位，前面补0
            $len = 8 - strlen($bin);
            for($j=0; $j<$len; ++$j) {
                $binIp .= '0';
            }
            $binIp .= $bin;
        }
        return $binIp;
    }
}
