<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * 
 * 所有platform下的资源都在这里类里面管理
 * 非platform下的资源，需要应用，通过getClassMap管理
 */
require_once dirname(__FILE__) . "/../../code_base2/config/config.inc.php";
require_once FANG . "/gj/autoloader.class.php";

class AutoLoad {

    private $loader = null;
	
    public function __construct(){
    }
    /* {{{ start */
    /**
     * @brief 
     *
     * @returns   
     * 
     */
    public function start() {
        Gj\Gj_Autoloader::setAppName('platform');
		Gj\Gj_Autoloader::addClassMap($this->getClassMap());
	}//}}}
    /* {{{ getClassMap */
    /**
     * @brief 
     *
     * @returns   
     */
	public function getClassMap() {
		$platformPath = dirname(__FILE__);
		return array(
            "SearchNamespace" => CODE_BASE2 . '/util/se/SearchNamespace.class.php',
            "SearchConfig"    => GANJI_CONF . '/SearchConfig.class.php',
            "DBConfig"      => GANJI_CONF . "/DBConfig.class.php",
			"RedisConfig"	=> GANJI_CONF . "/RedisConfig.class.php",
			"ErrorCode"    => CODE_BASE2 ."/app/housing/platform/config/housereport/ErrorCode.inc.php",
            "HousingVars"   => CODE_BASE2 . "/app/post/adapter/housing/include/HousingVars.class.php",
            "DBHandle"      => CODE_BASE2 . "/util/db/DBHandle.class.php",
            "InterfaceConfig"=>GANJI_CONF . "/InterfaceConfig.class.php",
            "DBMysqlNamespace" => CODE_BASE2 . "/util/db/DBMysqlNamespace.class.php",
            "StickyNamespace" => CODE_BASE2."/app/sticky/StickyNamespace.class.php",
            "PostListQueryBuilder" => FANG . "/fangpc/app/common/PostListQueryBuilder.class.php",
            "MemcacheConfig" => GANJI_CONF . "/MemcacheConfig.class.php",
            "MemCacheAdapter" => CODE_BASE2 . "/util/cache/adapter/MemCacheAdapter.class.php",
            "CacheNamespace" => CODE_BASE2 . "/util/cache/CacheNamespace.class.php",
            "Logger" => CODE_BASE2 . "/util/log/Logger.class.php",
            "PostListHelper" => FANG . "/fangpc/app/common/PostListHelper.class.php",
            "HttpNamespace" => CODE_BASE2 . "/util/http/HttpNamespace.class.php",
            "GeoNamespace" => CODE_BASE2 . "/app/geo/GeoNamespace.class.php",
            "BusSubwayCollegeNamespce" => CODE_BASE2 . '/app/bus_subway_college/BusSubwayCollegeNamespace.php',
            "BusinessDistrictNamespace" => CODE_BASE2 . '/app/business_district/BusinessDistrictNamespace.class.php',
            "BangNamespace"    => CODE_BASE2."/app/bang/BangNamespace.class.php",
            'Thrift' => $GLOBALS['THRIFT_ROOT'] . '/Thrift.php',
            'TBinaryProtocol' => $GLOBALS['THRIFT_ROOT'] . '/protocol/TBinaryProtocol.php',
            'TSocket' => $GLOBALS['THRIFT_ROOT'] . '/transport/TSocket.php',
            'TFramedTransport' => $GLOBALS['THRIFT_ROOT'] . '/transport/TFramedTransport.php',
            'TBufferedTransport' => $GLOBALS['THRIFT_ROOT'] . '/transport/TBufferedTransport.php',
            "ErrorConst" => $platformPath . "/config/ErrorConst.inc.php",
            "XiaoquXiaoquXiaoquModel" => $platformPath . "/models/database/xiaoqu/XiaoquXiaoquXiaoquModel.class.php",
            "HouseSellAvgPriceXiaoquModel" => $platformPath . "/models/database/xiaoqu/HouseSellAvgPriceXiaoquModel.class.php",
            "XiaoquNewsImageXiaoquModel" => $platformPath . "/models/database/xiaoqu/XiaoquNewsImageXiaoquModel.class.php",
            "BaseXiaoquModel" => $platformPath . "/models/database/xiaoqu/BaseXiaoquModel.class.php",
            "XiaoquRelationshipXiaoquModel" => $platformPath . "/models/database/xiaoqu/XiaoquRelationshipXiaoquModel.class.php",
            "XiaoquStatXiaoquModel" => $platformPath . "/models/database/xiaoqu/XiaoquStatXiaoquModel.class.php",
            "XiaoquNewsXiaoquModel" => $platformPath . "/models/database/xiaoqu/XiaoquNewsXiaoquModel.class.php",
            "CustomerAccountGcrmModel" => $platformPath . "/models/database/gcrm/CustomerAccountGcrmModel.class.php",
            "AccountInfoMemcacheModel" => $platformPath . "/models/memcache/AccountInfoMemcacheModel.class.php",
            "XiaoquHouseXapianModel" => $platformPath . "/models/xapian/XiaoquHouseXapianModel.class.php",
            "BaseXapianModel" => $platformPath . "/models/xapian/BaseXapianModel.class.php",
            "XiaoquXapianModel" => $platformPath . "/models/xapian/XiaoquXapianModel.class.php",
            "CacheStorageUtil" => $platformPath . "/util/CacheStorageUtil.class.php",
            //"XapianSearchHandleUtil" => $platformPath . "/util/XapianSearchHandleUtil.class.php",//等models/xapian/BaseXapianModel.class.php改完后可以注释掉
            "PlatformSingleton" => $platformPath . "/util/PlatformSingleton.class.php",
            "TimeMock" => $platformPath . "/util/TimeMock.class.php",
            "AccountInfoDataService" => $platformPath . "/dataservice/AccountInfoDataService.class.php",
            "XiaoquDataService" => $platformPath . "/dataservice/XiaoquDataService.class.php",
            "XinloupanDataService" => $platformPath . "/dataservice/XinloupanDataService.class.php",
            "HouseDataService" => $platformPath . "/dataservice/HouseDataService.class.php",
			"UserPhoneNamespace"  =>  CODE_BASE2 ."/app/user2/UserPhoneNamespace.class.php",
			//"HttpHandler" =>  CODE_BASE2 . "/app/housing/common/HttpHandler.class.php",
			"CheckCode" => CODE_BASE2 . '/util/check_code/CheckCode.class.php',
			"SessionNamespace" => CODE_BASE2 . '/util/session/SessionNamespace.class.php',
			"UploadConfig"		=>GANJI_CONF.'/UploadConfig.class.php',
            "PostViewAuthorNamespace" => CODE_BASE2 . '/app/post/PostViewAuthorNamespace.class.php',
            "SelfDirectionNamespace" => CODE_BASE2 . '/app/self_direction/SelfDirectionNamespace.class.php',
			"UserAuthInterface"		=>  CODE_BASE2 . '/interface/uc/UserAuthInterface.class.php',
            "ArticleNamespace"    => CODE_BASE2 . '/app/cms/ArticleNamespace.class.php',
            'MsPostListApi' => MSAPI . '/core/interface/MsPostListApi.class.php',
            'HouseBrokerListInfoInterface' => CODE_BASE2 . '/app/housing/interface/HouseBrokerListInterface.class.php',
            'UserInterface' => CODE_BASE2 . '/interface/uc/UserInterface.class.php',
            'MsapiSelfDirectionPage' => MSAPI . '/core/app/self_direction/MsapiSelfDirectionPage.class.php',
            'SelfDirectionNamespace' => CODE_BASE2 . '/app/self_direction/SelfDirectionNamespace.class.php',
            'SelfBiddingCache' => CODE_BASE2 . '/app/self_bidding/module/SelfBiddingCache.class.php',
            'LatlngNamespace' => CODE_BASE2 . '/app/latlng/LatlngNamespace.class.php',
            'SmsNamespace' => CODE_BASE2 . '/app/sms/SmsNamespace.class.php',
            'SmsConfig' => GANJI_CONF . '/SmsConfig.class.php',
            'Recommend' => CODE_BASE2 . '/app/housing/recommend/Recommend.class.php',
			'SelfBiddingFangAccount' => CODE_BASE2 . '/app/self_bidding/module/SelfBiddingFangAccount.class.php',
            'HousingForbiddenWords' => CODE_BASE2 . '/app/post/adapter/housing/include/HousingForbiddenWords.class.php',
        );
    }//}}}

	public function stop() {
		Gj\Gj_Autoloader::reset();
	}
}
/* {{{ Platform_AutoLoad */
/**
 * @brief 此类是为了odp跨子系统调用的时候用的
 * godp 要求这个类名，必须是appname_AutoLoad
 * 为切换子系统使用
 */
class Platform_AutoLoad extends AutoLoad
{
}
//}}}
