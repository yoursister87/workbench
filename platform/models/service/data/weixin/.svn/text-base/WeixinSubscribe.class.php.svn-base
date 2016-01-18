<?php

/**
 * @package
 * @subpackage
 * @brief                $微信订阅接口$
 * @file                 WeixinSubscribe.class.php
 * @author               $Author:    wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-28
 * @lastmodified         上午10:29
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Weixin_WeixinSubscribe
{
    protected static $MINUTE_SECONDS = 60;
    protected static $HOUR_SECONDS = 3600;
    protected static $DAY_SECONDS = 86400;
    protected static $GANJI_USER_PIC_DEFAULT = 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png';
    protected static $WAPHOST = 'http://3g.ganji.com/';
    protected static $WEIXIN_WAP_HOST = 'http://fangweixin.3g.ganji.com';
    protected static $RENT_MAJOR_CATEGORY = 1;
    protected static $SHARE_MAJOR_CATEGORY = 3;
    protected static $SELL_MAJOR_CATEGORY = 5;

    protected $subscribeObj;
    protected $collectObj;
    protected $fangByAccountObj;
    protected $sourceListObj;
    protected $cache;
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    protected $subType = array(
        1 => 'domain,district_id,street_id,price,huxing',
        101 => 'domain,district_id,street_id,price,huxing', //收藏来源用
        3 => 'domain,district_id,street_id,price,share_mode',
        103 => 'domain,district_id,street_id,price,share_mode',//收藏来源用
        5 => 'domain,xiaoqu_pinyin,price', //二手房小区
    );

    protected $currentAgent = array();
    protected $currentAccountIds = array();

    public function __construct()
    {
        //验证openid
        $this->subscribeObj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinSubscribe');
        $this->collectObj = Gj_LayerProxy::getProxy("Service_Data_Weixin_WeixinCollectionInfo");
        $this->fangByAccountObj = Gj_LayerProxy::getProxy('Service_Data_Source_FangByAccount');
        $this->sourceListObj = Gj_LayerProxy::getProxy("Service_Data_SourceList_HouseList");
        $this->xiaoInfoObj = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');

    }

    /**
     * @brief 单测注入用
     * @param $obj
     */
    public function setInnerObj($obj, $protype)
    {
        $this->$protype = $obj;
    }

    /**
     * @brief 安全验证openid
     * @param $openid
     */
    public function validateOpenid($openid)
    {
        if (strlen($openid) == 28 && preg_match('/([^a-zA-Z0-9_\-|\+\=])/i', $openid) === 0) {
            return true;
        } else {
            throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
        }

    }

    /**
     * @brief 根据subtype准备入库的conditions字段
     * @param $data
     * @return bool
     */
    protected function getReadyPatternBySubType($data)
    {
        try {
            $subType = empty($data['subType']) ? 1 : $data['subType'];
            $condsPattern = explode(",", $this->subType[$subType]);
            foreach ($condsPattern as $key => $value) {
                $data['conditions'][$value] = $data[$value];
            }
            return $data;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @brief 必须参数为空检查
     * @param $paramsReal
     * @return bool
     */
    protected function checkParams($paramsReal = array())
    {
        foreach ($paramsReal as $key => $value) {
            if ($value === null) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
        }
        return true;
    }

    /**
     * @brief 实时的fang5检索数据
     * @param $openid
     */
    public function getSellSubscrbiePostsByOpenid($openid = null, $page = 1, $pagesize = 20){

        try{
            $this->checkParams(array($openid));
            $arrRet = $this->arrRet;
            $pagesize = $pagesize > 20 ? 20 : $pagesize;
            $subscribe_limit = 5;
            $conditionsArr = $this->getSubscribeConditions($openid, self::$SELL_MAJOR_CATEGORY, null, $subscribe_limit);
            $result = array();
            foreach($conditionsArr['data'] as $key => $value){
                //获取不同的帖子
                if($tmp =  $this->getSellPostsByParams($value['conditions'], $page, $pagesize)){
                    //format
                    foreach($tmp['data'] as $k => $v){
                        $tmp['data'][$k] = $this->formatSellPostsInfo($v, $value['conditions']['domain']);
                    }
                    $tmp['conditions'] = $value['conditions'];
                    $tmp['id'] = $value['id'];
                }
                $result[$key] = $tmp;
            }
            $arrRet['data'] = $result;
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }


    /**
     * @brief 根据订阅id获取帖子
     * @param null $sid
     * @param int $page
     * @param int $pagesize
     * @return array
     */
    public function getSellSubscrbiePostsBySubscribeId($sid = null, $page = 1 , $pagesize =1 ){
        try{
            $this->checkParams(array($sid));
            $conditionsArr = $this->subscribeObj->getSubscribeConditionBySubscribeId($sid);
            $result = $this->getSellPostsByParams($conditionsArr['conditions'], $page, $pagesize);
            foreach($result['data'] as $key => $value){
                $result['data'][$key] = $this->formatSellPostsInfo($value, $conditionsArr['conditions']['domain']);
            }
            $arrRet['data'] = $result;
        }catch (Exception $e){
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }
    /**
     * @brief 格式化fang5的帖子关键数据
     * @param array $postInfo
     * @return array
     */
    public function formatSellPostsInfo($postInfo = array(), $domain = 'bj'){

        return $ret = array(
            'title' => $postInfo['title'],
            'huxing' => $postInfo['huxing_shi'].'室'.$postInfo['huxing_ting'].'厅'.$postInfo['huxing_wei'].'卫',
            'area' => $postInfo['area'],
            'url' => self::$WEIXIN_WAP_HOST.'/'.$domain.'_fang5/'.$postInfo['puid'].'x?'."ifid=gjwx_gj_dy&ca_s=other_weixin&ca_n=push",
            'thumb_img' => $this->collectObj->getImageUrlBySize($postInfo['thumb_img'],200,200)
        );
    }
    /**
     * @brief fang5检索接口
     * @param array $conditions
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getSellPostsByParams($params = array(), $page = 1, $limit = 20){

        $this->checkParams($params);
        $xiaoquInfoRet = $this->xiaoInfoObj->getXiaoquBaseInfoByCityPinyin($params['domain'], $params['xiaoqu_pinyin']);

        if($xiaoquInfoRet['errorno'] != 0 || !$xiaoquInfoRet['data']){
            return false;
        }
        $groupConfigArr = array(
            HousingVars::MAIN_BLOCK_LIST => array(
                'count' => $limit,
                'fromCache' => false,
            ),
        );
        $queryConfigArr = array(
            'queryField' => array(),
            'queryFilter' => array(
                'major_category_script_index' => self::$SELL_MAJOR_CATEGORY,
                'city_domain' => $params['domain'],
                'page_no' => $page,
                'xiaoqu_id' => $xiaoquInfoRet['data']['id'],
                'price' => $params['price'],
                'show_time' => array(time() - self::$DAY_SECONDS, time()),
            ),
            'orderField' => array(
                'show_time' => 'desc'
            ),
        );

        $preSearchRet = $this->sourceListObj->preSearch($groupConfigArr, $queryConfigArr);
        $result = $this->sourceListObj->getSearchResult($preSearchRet['data']);

        $readyData = array();
        if(is_array($result['data'][HousingVars::MAIN_BLOCK_LIST])){
            $readyData['data'] = $result['data'][HousingVars::MAIN_BLOCK_LIST][0];
            $readyData['count'] = $result['data'][HousingVars::MAIN_BLOCK_LIST][1];
            $sellPriceOption = HousingVars::getUrlPriceRange($params['domain'], self::$SELL_MAJOR_CATEGORY);
            $readyData['xiaoquInfo'] = array(
                'name' => $xiaoquInfoRet['data']['name'],
                'display_price' => $sellPriceOption[$params['price']]
            );
        }
        return $readyData;
    }
    /**
     * @brief 根据openid获取10条推送房源，当日发布无重复，fang1,3订阅用
     * @param $openid
     * @return array
     */
    public function getPushPostsByOpenid($openid)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            $data = $this->subscribeObj->getSubscribeConditionByOpenid($openid, self::$RENT_MAJOR_CATEGORY)[0];
            if ($data['major_category']) {
                $key = $this->getPushCacheKeyByConditions($data['conditions'], $data['major_category']);
				$new_key = $key.'_conditions_null';
				//数据筛选后没有,直接返回,
				$ret_ct = $this->cacheReadOrWrite('read', $new_key);
				if($ret_ct)
				{
					$arrRet['errorno'] = '0';
					$arrRet['errormsg'] = '条件对应房源为空';
					$arrRet['data'] = array();
					$arrRet['errortype'] = 'null';
					return $arrRet;
				}
                $ret = $this->cacheReadOrWrite('read', $key);
                if (!$ret) {
                    $data['conditions']['post_at'] = array(time() - self::$DAY_SECONDS, time());
                    $ret = $this->getPostsByParams($data['conditions'], $data['major_category']);
					if(!empty($ret))
					{
						$write_type = $this->cacheReadOrWrite('write', $key, $ret, (strtotime(date("Y-m-d")) + self::$DAY_SECONDS) - time());
						$arrRet['cache_write_type'] = $write_type;
						$arrRet['cache_write_key'] =  $key;
						$arrRet['ret_type'] =  'not null';
					}else{
						//通过条件没有获取到房源数据,存储key,防止下次再次查询接口。
						$write_type = $this->cacheReadOrWrite('write',$new_key,true, (strtotime(date("Y-m-d")) + self::$DAY_SECONDS) - time());
						$arrRet['cache_write_type'] = $write_type;
						$arrRet['cache_write_new_key'] =  $new_key;
						$arrRet['ret_type'] =  'null';
					}
                }
				if($ret)
				{
					foreach ($ret as $k => $v) {
						$arrRet['data'][$k] = $this->formatPostInfo($v, 2, $data['conditions']['domain']);
					}					
				}
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 根据经纬度和城市domain获取帖子
     * @param array $params
     * @param int $majorcategory
     * @return array
     */
    public function getPostsByLatLng($params = array(), $majorcategory = 1, $limit=10){
        try{
            $arrRet = $this->arrRet;
            $this->checkParams($params);
            $result = $this->getPostsByParams($params, $majorcategory);
            foreach ($result as $k => $v) {
                $arrRet['data'][$k] = $this->formatPostInfo($v, 2, $params['domain']);
            }
        }catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }
    /**
     * @brief 暂时msapi取帖\
     * @param $params
     * @param $majorcategory
     * @return array
     */
    public function getPostsByParams($params, $majorcategory, $limit = 10)
    {
        $result = array();
        //以后扩展其他频道，此处要十分注意，参数处理当前没有适配
        $params = $this->getQueryParams($params, $majorcategory, $limit);
        return $this->getPostFromMsapi(intval($majorcategory), $params);
    }

    /*
     * @brrief 补充： 此时更替为新接口，函数名不变。 2015-1-22 wanyang
     */
    protected function getPostFromMsapi($housingVarId, $params)
    {
        $this->checkParams(array($housingVarId));
        $returnPostsNums = $params['limit'];
        $queryPostsNums = 50; //取50条帖子排重留下limit条
        $groupConfigArr = array(
            HousingVars::MAIN_BLOCK_LIST => array(
                'count' => $queryPostsNums,
                'fromCache' => false,
            ),
        );
        $queryConfigArr = array(
            'queryField' => array(),
            'queryFilter' => array(
                'major_category_script_index' => $housingVarId,
                'city_domain' => $params['domain'],
                'page_no' => 1,
                'district_id' => $params['district_id'],
                'street_id' => $params['street_id'] == '-1' ? null : $params['street_id'],
            ),
            'orderField' => array(
                'post_at' => 'desc'
            ),
        );
        //FANG1,FANG3区别整租和合租的帖子
        if(!empty($housingVarId) && $housingVarId == 1){
            $queryConfigArr['queryFilter']['zufang'] = 1;
        }

        $otherAllowArray = array('price', 'huxing_shi', 'share_mode', 'latlng', 'post_at');
        foreach ($otherAllowArray as $key => $value) {
            if ($params[$value]) {
                $queryConfigArr['queryFilter'][$value] = $params[$value];
            }
        }
        $preSearchRet = $this->sourceListObj->preSearch($groupConfigArr, $queryConfigArr);
        /*
         * .....
         *
         */
        $result = $this->sourceListObj->getSearchResult($preSearchRet['data']);
        $readyData = array();
        if (is_array($result['data'][HousingVars::MAIN_BLOCK_LIST][0])) {
            $dataFromInterface = $result['data'][HousingVars::MAIN_BLOCK_LIST][0];
            //根据userid, title排重取出limit条帖子
            $currentTitleList = array();
            foreach ($dataFromInterface as $key => $value){
                if(count($currentTitleList) == $returnPostsNums){
                    break;
                }
                if(!in_array($value['title'], $currentTitleList)){
                    $currentTitleList[] = $value['title'];
                    $readyData[] = $value;
                }
            }
            usort($readyData, array($this, 'sortDyadicArrayByThumbimg'));
        };
        return $readyData;
    }

    /**
     * @brief 对数组按照thumb_img是否为空进行排序，作为usort的回调函数
     * @param $first
     * @param $second
     * @return int
     */
    protected function sortDyadicArrayByThumbimg($first, $second)
    {
        if ($first['thumb_img'] && $second['thumb_img']) {
            return 0;
        }
        return $first['thumb_img'] ? -1 : 1;
    }

    /**
     * @brief 生成查询参数,目前是固定的，针对fang1,3.扩展需要重写方法适配
     * @param $params
     * @return array
     */
    protected function getQueryParams($params, $category = 1, $limit = 10)
    {
        if ($params['huxing']) {
            $params['huxing_shi'] = $params['huxing'];
            unset($params['huxing']);
        }
        $fparams = array(
            'limit' => $limit,
            'domain' => $params['domain'],
            'price' => $params['price'],
        );

        $ignoredFields = array();
        foreach ($params as $key => $value) {
            if (!in_array($key, $ignoredFields) && $value !== null && !$fparams[$key]) {
                $fparams[$key] = $value;
            }
        }
        return $fparams;
    }

    /**
     * @biref 根据domain district_id street_id 获取district_street的url
     * @param $domain
     * @param $district_id
     * @param $street_id
     * @return string
     */
    public function getAreaInfo($domain, $district_id, $street_id, $type = 'pinyin')
    {
        $cityInfo = GeoNamespace::getCityByDomain($domain);
        //获取区域街道
        list($province_script_index, $city_script_index) = GeoNamespace::cityCodeDecode($cityInfo['city_code']);
        $districtInfo = GeoNamespace::getDistrictByScriptIndex($province_script_index, $city_script_index, $district_id);
        $streetInfo = GeoNamespace::getStreetByScriptIndex($province_script_index, $city_script_index, $district_id, $street_id);
        if ($type == 'pinyin') {
            $district_street = $streetInfo ? $streetInfo['url'] : ($districtInfo['url'] ? $districtInfo['url'] : '');
            return $district_street;
        } else {
            $district_street = $streetInfo ? $streetInfo['name'] : ($districtInfo['name'] ? $districtInfo['name'] : '');
            return $cityInfo['name'] . '-' . $district_street;
        }
    }

    /**
     * @brief 生成推送消息的cacheKey
     * @param $cons
     * @param int $majorCategory
     * @return string
     */
    public function getPushCacheKeyByConditions($cons, $majorCategory = 1)
    {
        $this->checkParams($cons);
        $cachekey = '3g_housing_weixin_' . $majorCategory . '_';
        foreach ($cons as $key => $value) {
            $cachekey .= $key . $value;
        }
        return $cachekey;
    }

    /**
     * @brief 根据openid获取推送池中第一条推送房源
     * @param $openid
     * @return array
     */
    public function getTopOneByOpenid($openid)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            $pushPosts = $this->getPushPostsByOpenid($openid);
            $cursorKey = 'housing_weixin_cursor_' . $openid;
            $cursor = $this->cacheReadOrWrite('read', $cursorKey);
            $cursor = $cursor ? $cursor : 4;
            $this->cacheReadOrWrite('write', $cursorKey, $cursor + 1, self::$HOUR_SECONDS * 3);
            if ($pushPosts['data'][$cursor - 1]) {
                $arrRet['data'] = array(
                    'postInfo' => $this->formatPostInfo($pushPosts['data'][$cursor - 1], 1),
                    'cursor' => $cursor
                );
            } else {
                $arrRet['data'] = array('cursor' => $cursor);
                $this->cacheReadOrWrite('delete', $cursorKey);
                throw new Gj_Exception(ErrorConst::E_DATA_NOT_EXIST_CODE, ErrorConst::E_DATA_NOT_EXIST_MSG);
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief $data中需要openid 添加订阅
     * @param $data
     * @return array
     */
    public function addSubscribe($data)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams($data);
            $this->validateOpenid($data['openid']);
            $fdata = $this->getReadyPatternBySubType($data);
            $ret = $this->subscribeObj->insertSubscribeCondition($fdata);
            $arrRet['data'] = $ret;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 根据openid获取订阅条件
     * @param $openid
     * @return array
     */
    public function getSubscribeConditions($openid = null, $major_category = 1, $subType = null, $limit = 1)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            $ret = $this->subscribeObj->getSubscribeConditionByOpenid($openid, $major_category, $subType);
            if($limit == 1){
                $arrRet['data'] = $ret[0];
            }else{
                $arrRet['data'] = is_array($ret) ? array_slice($ret, 0, $limit) : array();
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
        }

    /**
     * @brief fang5中文显示条件
     * @param $conditions
     */
    public function getSellSubscribeChineseConditions($conditions, $return_string = true){
        try{
            $arrRet = $this->arrRet;
            $cityInfo = GeoNamespace::getCityByDomain($conditions['domain']);
            $xiaoquInfo = $this->xiaoInfoObj->getXiaoquBaseInfoByCityPinyin($conditions['domain'], $conditions['xiaoqu_pinyin']);
            $priceInfo = HousingVars::getUrlPriceRange($conditions['domain'], self::$SELL_MAJOR_CATEGORY);

            if($return_string){
                $arrRet['data'] = "小区：".$cityInfo['name']."-".$xiaoquInfo['data']['name']."\n价格：".$priceInfo[$conditions['price']]."\n\n";
            }else{
                $arrRet['data'] =  array(
                    'city_name' => $cityInfo['name'],
                    'xiaoqu_name' => $xiaoquInfo['data']['name'],
                    'display_price' => $priceInfo[$conditions['price']]
                );
            }
        }catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;

    }

    /**
     * @brief 根据domain district_id street_id 获取中文字符串
     * @param $conditions
     * @return array
     */
    public function getChineseNameByConditions($conditions, $majorcatgory)
    {
        try {
            $arrRet = $this->arrRet;
            $areaStr = $this->getAreaInfo($conditions['domain'], $conditions['district_id'], $conditions['street_id'], 'name');
            $priceOption = HousingVars::getUrlPriceRange($conditions['domain'], $majorcatgory);
            $retStr = '区域：' . $areaStr . "\n价格：" . $priceOption[$conditions['price']];
            switch ($majorcatgory) {
                case 1:
                    $huxingOption = HousingVars::$HUXING_SHI_SEARCH;
                    $retStr .= "\n户型：" . $huxingOption[$conditions['huxing']] . "\n";
                    break;
                case 3:
                    $sharemodeOption = HousingVars::$SHARE_MODE_LIST;
                    $retStr .= "\n方式：" . $sharemodeOption[$conditions['share_mode']] . "\n";
                    break;
                default:
                    break;
            }
            $arrRet['data'] = $retStr;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 根据openid取消订阅
     * @param $openid
     * @return bool
     */
    public function cancelSubscribeByOpenid($openid = null, $major_category = 1)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            $this->subscribeObj->deleteSubscribeCondition($openid, array('major_category' => $major_category));
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 单条订阅的删除
     * @param null $subscribeId
     * @return array
     */

    public function cancelSubscribeBySubscribeId($subscribeId = null, $openid = null){
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($subscribeId, $openid));
            $this->subscribeObj->deleteSubscribeBySubscribeId($subscribeId, $openid);
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 根据openid与新的参数更新订阅条件,$updateBySubscribeId表示是否根据subscribeId更新
     * @param $openid
     * @param $conditions
     * @return bool
     */
    public function updateSubscribeConditionsByOpenid($data, $updateBySubscribeId = false)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams($data);
            if($updateBySubscribeId){
                $this->subscribeObj->updateSubscribeByScribeId($this->getReadyPatternBySubType($data));
            }else{
                $this->subscribeObj->updateSubscribeCondition($this->getReadyPatternBySubType($data));
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 随机返回一条房产资讯内容
     * @return array
     */
    public function getOneArtilceByRand()
    {
        try {
            $arrRet = $this->arrRet;
            $key = 'housing_weixin_articles';
            $ret = $this->cacheReadOrWrite('read', $key);
            if (!$ret) {
                $categoryId = 'mobile';
                $majorCategoryId = 'mobile_fang';
                $aData = ArticleNamespace::getArticleByType($categoryId, $majorCategoryId, '', 0, 30);
                $ret = $aData['result'];
                $this->cacheReadOrWrite('write', $key, $ret, self::$DAY_SECONDS * 3);
            }
            do {
                $newsData = $ret[rand(0, 29)];
            } while (!$newsData);
            $newsData['url'] = self::$WEIXIN_WAP_HOST.'/bj_news/housing/?type=detail&id=' . $newsData['id'] . '&ifid=gjwx_user_news' . '&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=menu';
            $arrRet['data'] = $newsData;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 由于gj提供的cacheProxy中使用cache的配置会制定在当前方法的执行下,
     *        此处独立使用cache句柄，
     */
    protected function cacheReadOrWrite($method = 'read', $key = null, $value = null, $timeout = 60)
    {
        $this->checkParams(array($key));
        $this->cache = Gj_Cache_CacheClient::getInstance('Memcache');
        if ($method == 'read') {
            $result = $this->cache->read($key);
        } else if ($method == 'delete') {
            $result = $this->cache->delete($key);
        } else {
            $result = $this->cache->write($key, $value, $timeout);

        }
        return $result;
    }

    /**
     * @格式单条帖子数据
     * @param $postInfo
     */
    public function formatPostInfo($postInfo, $ifid = 2, $domain = 'bj')
    {
        $ifidarr = array(1 => 'gjwx_user_dy', 2 => 'gjwx_gj_dy');
        $postInfo['domain'] = $domain;
        $postInfo['major_category'] = $postInfo['major_category'] ? $postInfo['major_category'] : $postInfo['type'];
        if ($ifid == 2) {
            $postInfo = $this->collectObj->formatPostInfo($postInfo);
        }
        $postInfo['url'] = preg_replace('/ifid(.*)/i', 'ifid=' . $ifidarr[$ifid], $postInfo['url']);
        $postInfo['url'] .= '&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V';
        $postInfo['url'] .= $ifid == 2 ? '&ca_n=push': '&ca_n=menu';
        return $postInfo;
    }

    public function getAgentsByOpenid($openid)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            $data = $this->subscribeObj->getSubscribeConditionByOpenid($openid, self::$RENT_MAJOR_CATEGORY)[0];
            $agentsKey = 'housing_weixin_agents';
            foreach ($data['conditions'] as $key => $value) {
                $agentsKey .= $key . $value;
            }
            $ret = $this->cacheReadOrWrite('read', $agentsKey);
            if (!$ret) {
                $this->setAgentsByOpenidFromInterface($data);
                //$this->setAgentsByOpenidFromMsapi($data);
                $ret = $this->currentAgent;
                $this->cacheReadOrWrite('write', $agentsKey, $ret, self::$DAY_SECONDS);
            }
            $arrRet['data'] = $this->formatAgents($ret);
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRett['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 从app/housing/interface中获取N条推荐经纪人,默认3条
     * @param $city_id
     * @param $district_id
     * @param $street_id
     * @param $limit
     */
    public function setAgentsByOpenidFromInterface($data, $limit = 5)
    {
        //huxing,price -1是由于参数必传。
        $cityInfo = GeoNamespace::getCityByDomain($data['conditions']['domain']);
        $params = array(
            'apiType' => 'near',
            'type' => $data['major_category'],
            'city' => $cityInfo['city_code'],
            'district_id' => $data['conditions']['district_id'] ? $data['conditions']['district_id'] : 0,
            'street_id' => $data['conditions']['street_id'] ? $data['conditions']['street_id'] : 0,
            'huxing' => -1,
            'price' => -1,
            'limit' => $limit
        );
        $ret = HouseBrokerListInfoInterface::GetBrokerList($params);
        foreach ($ret as $key => $value) {
            if ($key == $limit) {
                break;
            }
            array_push($this->currentAgent, $value);
            array_push($this->currentAccountIds, $value['account_id']);
        }
    }

    /**
     * @brief 从msapi获取时间最新的N条经纪人，默认2条
     * 如有疑问，查询对应的需求规则
     * @param $city_id
     * @param $district_id
     * @param $street_id
     * @param $limit
     */
    protected function setAgentsByOpenidFromMsapi($data, $limit = 2)
    {
        $data['conditions']['agent'] = 2;
        $postLists = $this->getPostsByParams($data['conditions'], $data['major_category'], 20);
        $count = 0;
        foreach ($postLists as $key => $value) {
            if ($count == $limit) {
                break;
            }
            if ($value['account_id'] !== null && !in_array($value['account_id'], $this->currentAccountIds)) {
                $count++;
                array_push($this->currentAgent, $value);
                array_push($this->currentAccountIds, $value['account_id']);
            }
        }
    }

    /**
     * @brief 根据经纪人列表，格式化输出字段
     * @param $agents
     */
    protected function formatAgents($agents)
    {
        $this->checkParams($agents);
        $ret = array();
        foreach ($agents as $key => $value) {
            $agentPosts = $this->fangByAccountObj->getCountHouseTypeByAccount($value['account_id']);
            $userInfo = $this->getUserFromUcInterfaceByAccountid($value['account_id']);
            $postsnum = 0;
            foreach ($agentPosts['data'] as $k => $v) {
                if (in_array($v['type'], array(1, 3))) {
                    $postsnum += $v['num'];
                }
            }
            $ret[$key] = array(
                'person' => $value['person'] ? $value['person'] : $value['name'],
                'account_id' => $value['account_id'],
                'company' => $value['CompanySimpleName'] ? $value['CompanySimpleName'] : '个人经纪人',
                'postsnum' => $postsnum,
                'user_picture' => $userInfo['Picture'] ? UploadConfig::getImageServer() . '/' . $userInfo['Picture']
                        : self::$GANJI_USER_PIC_DEFAULT,
                'url' => self::$WEIXIN_WAP_HOST . '/bj_fang/ag' . $value['account_id'] . '/?ifid=gjwx_gj_jjr' . ($key + 1) . '&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin&ca_n=push',
            );
        }
        return $ret;
    }

    /**
     * @brief 从UcInterface获取UserInfo.异常返回false
     * @param $userid
     * @return bool|返回user信息
     */
    protected function getUserFromUcInterfaceByAccountid($account_id)
    {
        $customerAccountObj = Gj_LayerProxy::getProxy("Service_Data_Gcrm_CustomerAccount");
        $userInfo = $customerAccountObj->getAccountInfoById($account_id, array('Picture'));
        if ($userInfo['errorno'] == 0) {
            return $userInfo['data'][0];
        }
        return false;
    }

    /**
     * @brief 从队列pop一条数据
     */
    public function getSubscribeRequestFromRedisQueue(){
        $redisSubscribeObj = Gj_LayerProxy::getProxy("Dao_Redis_Weixin_SubscribeRequestQueue");
        return $redisSubscribeObj->popSubscribeRequest();
    }
}
