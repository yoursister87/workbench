<?php

/**
 * @package
 * @subpackage
 * @brief                $微信收藏接口$
 * @file                 WeixinCollectionInfo.class.php$
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         上午9:25
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Weixin_WeixinCollectionInfo
{
    protected $uuid;
    protected $fangQueryObj;
    protected $collectionObj;
	protected $formatInfoObj;
    protected $BigImageWidth = 360;
    protected $heightOrSmallWidth = 200;
    protected static $WEIXIN_WAP_HOST = 'http://fangweixin.3g.ganji.com';
    protected static $URL_ANTI_FLAG = 'fV0x40R2Gk9V';
    protected static $Ganji_Wap_Default_Image = 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png';
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function __construct()
    {
        //验证openid
        $this->collectionObj = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinCollection');
        $this->fangQueryObj =  Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery');
		//获取格式化obj
		$this->formatInfoObj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfo');
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
     * @brief 根据puid取出格式化的帖子信息
     * @param null $puid
     * @param null $uuid
     * @return array
     */
    public function getPostInfoByPuid($puid = null, $openid = null, $uuid = null)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($puid, $openid));
            $this->uuid = $uuid;
            $resultFromQuery = $this->fangQueryObj->getHouseSourceByPuidInfo($puid);
            $postInfo = $resultFromQuery['data'];
            if(empty($postInfo['major_category']) && !empty($postInfo['type']))
			{
				//房11 特殊处理
				if($postInfo['type'] >= 11001)
				{
					$postInfo['major_category'] ='11';
				}
			}
            $postInfo['major_category'] = $postInfo['major_category']?$postInfo['major_category']:$postInfo['type'];
			$postInfo = $this->setDomainMajorCategory($postInfo);
            //$arrRet['data'] = $this->formatPostInfo($postInfo);
			$arrRet['data'] = $this->formatInfoObj->formatNews($postInfo,$postInfo['major_category']);
            $arrRet['data']['major_category'] = $postInfo['major_category'];
			//$arrRet['major_category'] = $postInfo['major_category'];
            //保存时用的postInfo是未格式化前的postInfo,而推出时的postInfo是格式化过的
            $this->saveCollectionInfo($postInfo, array(
                'openid' => $openid,
                'majorCategory' => $postInfo['major_category'],
                'url' => $arrRet['data']['url']));
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 获取收藏列表接口
     * @param null $openid
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getCollectedPostsByWeixinOpenid($openid = null, $majorcategory, $limit = 10, $page = 1)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid, $majorcategory));
            $collectionList = $this->getFormatCollectionList($this->collectionObj->selectCollections($openid,
                array('puid', 'title', 'thumb_img', 'url'), $limit, $page, $majorcategory));
            $arrRet['data'] = $collectionList;
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 删除该用户的所有收藏数据
     * @param null $openid
     * @return array
     */
    public function clearCollectionInfoByWeixinOpenid($openid = null, $major_category = 1)
    {
        try {
            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));
            if (!$this->collectionObj->deleteAllCollectionsByOpenid($openid, $major_category)) {
                $arrRet['errorno'] = ErrorConst::E_INNER_FAILED_CODE;
                $arrRet['errormsg'] = ErrorConst::E_INNER_FAILED_MSG;
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 必须参数为空检查
     * @param $paramsReal
     * @return bool
     */
    protected function checkParams($paramsReal = array())
    {
        foreach($paramsReal as $key => $value){
            if ($value === null) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
        }
        return true;
    }

    /**
     * @格式单条收藏帖子数据
     * @param $postInfo
     */
    public function formatPostInfo($postInfo, $ifid="gjwx_gj_sc", $ca_n="scan")
    {
        $ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $ifid, $ca_n);
        $agent = $postInfo['agent'] === '0' ? '房东' : '经纪人';
        $ret['phone'] = $this->uuid ? $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】' : ($this->isPersonPercent($postInfo['auth_status'], $postInfo['domain'])
            ? '100%个人房源请点击登录查看' : $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】');
        $ret['xiaoqu'] = $postInfo['xiaoqu'];
        $ret['address'] = $postInfo['xiaoqu_address'];
        $ret['ceng'] = $postInfo['ceng']. '/'. $postInfo['ceng_total'];

        if($postInfo['major_category'] == 5){
            $ret['huxing'] =  $postInfo['huxing_shi'] . '室' . $postInfo['huxing_ting'] . '厅'
                . $postInfo['huxing_wei'] . '卫-'. $postInfo['area'] . '㎡';
            $ret['price'] = $postInfo['price']?round($postInfo['price']/10000, 2). '万元('. intval($postInfo['price']/$postInfo['area']). '元/㎡)':'面议';
        }else{
            $ret['price'] = $postInfo['price'] ? $postInfo['price'] . '元/月':'面议';
            $type = $postInfo['house_type'] ? '合租' : '整租';
            $housetype = array('主卧', '次卧', '隔断', '床位');
			//合租信息不全,改为单间合租
			if($postInfo['major_category'] == 3 && $postInfo['house_type'] == 0)
			{
				if(empty($postInfo['huxing_shi']) || empty($postInfo['huxing_ting']))
				{
					$share_mode_name = $postInfo['share_mode'] == 1 ? '单间出租' : '床位出租';
					$ret['huxing'] = $share_mode_name.'-' . $postInfo['area'] . '㎡';
				}
			}else{
				$ret['huxing'] =  $postInfo['house_type']?($housetype[$postInfo['house_type']-1]):($postInfo['huxing_shi'] . '室' . $postInfo['huxing_ting'] . '厅'
					. $postInfo['huxing_wei'] . '卫-' . $type . '-' . $postInfo['area'] . '㎡');				
			}
        }
        return $ret;
    }

    /**
     * @brief 是否是100%个人房源
     * @param null $auth_status
     * @return bool
     */
    protected function isPersonPercent($auth_status = null, $domain)
    {
        if ($auth_status == 3 && $domain == 'bj') {
            return true;
        }
        return false;
    }

    /**
     * @brief 设置城市街道分类
     * @param array $postInfo
     */
    protected function setDomainMajorCategory($postInfo = array())
    {
        $this->checkParams(array($postInfo['city']));
        $cityInfo = GeoNamespace::getCityByCityCode($postInfo['city']);
        $postInfo['domain'] = $cityInfo['domain'];
        $postInfo['cityName'] = $cityInfo['name'];
        return $postInfo;
    }

    /**
     * @brief 图片大小矫正
     * @param $url
     * @param $width
     * @param $height
     * @param null $type c/f
     * @param int $quality [0-9]
     * @param int $version [0..]
     * @return string
     */
    public function getImageUrlBySize($url, $width, $height, $type = 'c', $quality = 6, $version = 0)
    {
        $pattern = '/_(\d+)-(\d+)(.*)_[0-9]-[0-9]/i';
        $replaceStr = '_' . $width . '-' . $height . $type . '_' . $quality . '-' . $version;
        if(strpos($url, 'tuiguang')){
            $imageUrl =  'http://image.ganjistatic1.com/'. $url;
        }else if( $url == null || $url == self::$Ganji_Wap_Default_Image || strpos($url, 'list_default_pic') || strpos($url, 'default_weixin_logo') ){
            $imageUrl = self::$Ganji_Wap_Default_Image;
        }else{
            $imageUrl = 'http://image.ganjistatic1.com/'. preg_replace($pattern, $replaceStr, $url);
        }
        return $imageUrl;
    }

    /**
     * @brief 获取帖子wap访问地址
     * @param $city
     * @param $majorCategory
     * @param $puid
     * @return string
     */
    protected function getWapAccessUrl($domain, $majorCategory, $puid)
    {
        $accessUrl = self::$WEIXIN_WAP_HOST.'/' . $domain . '_fang' . $majorCategory . '/' . $puid . 'x';
        return $accessUrl;
    }

    /**
     * @brief 增加ifid
     * @param $url
     * @param int $from
     * @return string
     */
    protected function setWapAccessUrlIfid($url, $from = 1)
    {
        $urlWithIfid = str_replace('?ifid=gjwx_gj_sc&_gjassid=fV0x40R2Gk9V', '',$url);
        if(!strpos($urlWithIfid, 'fangweixin')){
            $urlWithIfid = str_replace('3g', 'fangweixin.3g', $urlWithIfid);
        }
        if ($from == 1) {
            $urlWithIfid .= '?ifid=gjwx_gj_sc&ca_n=scan';
        } else {
            $urlWithIfid .= '?ifid=gjwx_user_sc&ca_n=menu';
        }
        return $urlWithIfid . '&_gjassid=fV0x40R2Gk9V&ca_s=other_weixin';
    }


    /**
     * @brief add ifid ca_n ca_s for a detail page url
     * @params ifid ca_n ca_s
     * @return string
     */
    public function setDetailUrlWithCountParams($url, $ifid, $ca_n, $ca_s = 'other_weixin'){

        $url = explode('?', $url)[0];
        if(!strpos($url, 'fangweixin')){
            $url = str_replace('3g', 'fangweixin.3g', $url);
        }
       return $url."?ifid=".$ifid."&ca_n=".$ca_n."&ca_s=".$ca_s.'&_gjassid=fV0x40R2Gk9V'; 
    }
    /**
     * @brief 格式化列表字段
     * @param $list
     * @return array
     */
    protected function getFormatCollectionList($list)
    {
        $ret = array();
        foreach ($list as $key => $value) {
            $ret[$key]['puid'] = $value['puid'];
            $ret[$key]['title'] = $value['title'];
            $ret[$key]['thumb_img'] = $this->getImageUrlBySize($value['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
            $ret[$key]['big_img'] = $this->getImageUrlBySize($value['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
            //$ret[$key]['url'] = $this->setWapAccessUrlIfid($value['url'], 2);
            $ret[$key]['url'] = $this->setDetailUrlWithCountParams($value['url'], 'gjwx_user_sc', 'menu');
        }
        return $ret;
    }

    /**
     * @brief 保存收藏信息到收藏表
     * @param
     * @param null $postInfo
     * @param array $additionalData
     */
    protected function saveCollectionInfo($postInfo = null, $additionalData = array())
    {
        $data = array(
            'openid' => $additionalData['openid'],
            'title' => $this->getNewFormatListTitle($postInfo),
            'puid' => $postInfo['puid'],
            'thumb_img' => $postInfo['thumb_img']?$postInfo['thumb_img']:self::$Ganji_Wap_Default_Image,
            'url' => $additionalData['url'],
            'major_category' => $additionalData['majorCategory'],
            'create_time' => time()
        );

        //1,3订阅条件写队列写缓存。
        // @codeCoverageIgnoreStart
        try {
            if (in_array($additionalData['majorCategory'], array(1, 3))) {
                $redisSubscribeObj = Gj_LayerProxy::getProxy("Dao_Redis_Weixin_SubscribeRequestQueue");
                $cacheObj = Gj_Cache_CacheClient::getInstance('Memcache');
                $preTimeDataKey = "weixin_collection_cache" . $data['openid'];

                $subData = serialize(array(
                    'openid' => $data['openid'],
                    'domain' => $postInfo['domain'],
                    'district_id' => $postInfo['district_id'],
                    'street_id' => $postInfo['street_id'] ? $postInfo['street_id'] : -1,
                    'price' => $postInfo['price'] ?
                            HousingVars::getPriceRangeIdByPrice($postInfo['price'], $postInfo['domain'], $additionalData['majorCategory'])
                            : '-1',
                    'share_mode' => $postInfo['share_mode'] ? $postInfo['share_mode']: '1',
                    'huxing' => $postInfo['huxing_shi'] ? $postInfo['huxing_shi'] : -1,
                    'major_category' => $additionalData['majorCategory'],
                    'subType' => '10' . $additionalData['majorCategory'],
                ));
                $preTimeData = $cacheObj->read($preTimeDataKey);
                $redisSubscribeObj->pushSubscribeRequest($subData, true, $preTimeData);
                $cacheObj->write($preTimeDataKey, $subData, strtotime(date("Y-m-d")) + 86400 - time());
            }

        } catch (Exception $e) {
            $note_path = "/data/waplog/mobilelog/fang";
            if (is_dir($note_path) && is_writable($note_path)) {
                file_put_contents($note_path . "/wx_exception_" . date("Ymd") . ".log", date("Y-m-d H:i:s") . ":" . $e->getMessage() . "\n\n", FILE_APPEND);
            }
        }
        // @codeCoverageIgnoreEnd
        //insert data
        $this->collectionObj->insertOneCollection($data);
    }

    /**
     * @brief 格式化入库的title字段
     * @param $postInfo
     * @return string
     */
    protected function getFormatListTitle($postInfo)
    {
        $housetype = array('主卧', '次卧', '隔断', '床位');

        $startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
        $midTitle = $postInfo['house_type'] ? $housetype[$postInfo['house_type'] - 1] : $postInfo['huxing_shi'] . '室';
        if($postInfo['major_category'] == 5){
            $endTitle =  $postInfo['price']?round($postInfo['price']/10000, 2). '万元('. intval($postInfo['price']/$postInfo['area']). '元/㎡)':'面议';
        }else{
			if($postInfo['major_category'] == 3 && $postInfo['house_type'] == 0)
			{
				if(empty($postInfo['huxing_shi']) || empty($postInfo['huxing_ting']))
				{
					$share_mode_name = $postInfo['share_mode'] == 1 ? '单间出租' : '床位出租';
					$midTitle = $share_mode_name;
				}
			}
            $endTitle = $postInfo['price']?$postInfo['price'] . '元/月':'面议';
        }
        return implode(' ', array($startTitle, $midTitle, $endTitle));
    }
	/**
	 * 格式化入库的title字段 - 新格式
	*/
	protected function getNewFormatListTitle($postInfo)
	{
		$new_title = $this->formatInfoObj->formatSaveData($postInfo,$postInfo['major_category']);
		return $new_title;
	}
		
	/**
	 * 获取用户不同帖子分类下用户的收藏总数
	 * @params $open_id        微信openid 
	 * @params $major_category 帖子类别id
	 * @return array()
	*/
	public function getCollectionsCountNum($open_id,$major_category_list)
	{
		if(empty($open_id) || empty($major_category_list)) return array();
		$count_collection_list = array();
		//循环帖子类别
		foreach($major_category_list as $key =>$value)
		{
			if($value !='xiaoqu')
			{
				$major_category = str_replace('FANG','',$value);
				$count_num = $this->collectionObj->getCollectionsCountNumByCategory($open_id,$major_category);
			}else{
				$count_num = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinXiaoquCollection')->getXiaoquCollectionsCountNum($open_id);
			}
			$count_num = $count_num >=10 ? 10 : $count_num;
			
			$count_collection_list[$value] = intval($count_num);
		}
		return $count_collection_list;
	}
}
