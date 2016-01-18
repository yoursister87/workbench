<?php

/**
 * @package
 * @subpackage
 * @brief      			 $格式化微信收藏数据,根据不同的帖子分类$
 * @file                 WeixinCollectionFormatInfo.class.php$
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         2015-05-29
 * @lastmodified         上午17:00
 * @copyright            Copyright (c) 2014,fangweixin.3g.ganji.com
 */
//error_reporting(E_WARNING);
class Service_Data_Weixin_WeixinCollectionFormatInfo
{
	
    protected $uuid;
    //protected $fangQueryObj;
    protected $collectionInfoObj;
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
	
	protected $ifid = 'gjwx_gj_sc';
	
	protected $ca_n = 'scan';
	
	
    public function __construct()
    {
        //初始化对象obj
        //$this->collectionInfoObj =  Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionInfo');
    }
	/**
	 * 格式化房源数据,推送微信图文消息 - 入口
	 * 
	 * @return array();
	*/
	public function formatNews($data,$major_category=1)
	{
		if(empty($data)) return array();
		$method_name = __FUNCTION__.'Fang'.$major_category.ucfirst('info');
		if(method_exists($this,$method_name))
		{
			//error_reporting(-1);
		 return call_user_func(array($this,$method_name),$data);
		}else{
			return array();
		}
	}
	/**
	 * 格式化房源入库数据 - 调用入口
	 * 
	 * @return array();
	*/
	public function formatSaveData($data,$major_category=1)
	{
		if(empty($data)) return array();
		
		$method_name = __FUNCTION__.'Fang'.$major_category.ucfirst('title');
		//formatSaveDataFang1Title
		if(method_exists($this,$method_name))
		{
			//error_reporting(-1);
			return call_user_func(array($this,$method_name),$data);
		}else{
			return array();
		}
	}
	/**
     * @格式化房1,推送到微信端回复图文
	 * 
     * @return $postInfo
    */
    protected function formatNewsFang1Info($postInfo)
    {
        $ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
        $agent = $postInfo['agent'] === '0' ? '房东' : '经纪人';
        $ret['phone'] = $this->uuid ? $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】' : ($this->isPersonPercent($postInfo['auth_status'], $postInfo['domain'])
            ? '100%个人房源请点击登录查看' : $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】');
        $ret['xiaoqu'] = $postInfo['xiaoqu'];
        $ret['address'] = $postInfo['xiaoqu_address'];
        $ret['ceng'] = $postInfo['ceng']. '/'. $postInfo['ceng_total'];
		
		$ret['price'] = $postInfo['price'] ? $postInfo['price'] . '元/月':'面议';
        $type = $postInfo['house_type'] ? '合租' : '整租';
        $housetype = array('主卧', '次卧', '隔断', '床位');
		
		$ret['huxing'] =  $postInfo['house_type']?($housetype[$postInfo['house_type']-1]):($postInfo['huxing_shi'] . '室' . $postInfo['huxing_ting'] . '厅'
						  . $postInfo['huxing_wei'] . '卫-' . $type . '-' . $postInfo['area'] . '㎡');
		
        return $ret;
    }
	/**
     * @格式化房5,推送到微信端回复图文
	 * 
     * @return $postInfo
    */
    protected function formatNewsFang5Info($postInfo)
    {
        $ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
        $agent = $postInfo['agent'] === '0' ? '房东' : '经纪人';
        $ret['phone'] = $this->uuid ? $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】' : ($this->isPersonPercent($postInfo['auth_status'], $postInfo['domain'])
            ? '100%个人房源请点击登录查看' : $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】');
        $ret['xiaoqu'] = $postInfo['xiaoqu'];
        $ret['address'] = $postInfo['xiaoqu_address'];
        $ret['ceng'] = $postInfo['ceng']. '/'. $postInfo['ceng_total'];
		$ret['huxing'] = $postInfo['huxing_shi'] . '室' . $postInfo['huxing_ting'] . '厅'
						. $postInfo['huxing_wei'] . '卫-'. $postInfo['area'] . '㎡';
        $ret['price'] = $postInfo['price']?round($postInfo['price']/10000, 2). '万元' :'面议';
       
        return $ret;
    }
	/**
     * @格式化房3,推送到微信端回复图文
	 * 
     * @return $postInfo
    */
    protected function formatNewsFang3Info($postInfo)
    {
        $ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
        $agent = $postInfo['agent'] === '0' ? '房东' : '经纪人';
        $ret['phone'] = $this->uuid ? $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】' : ($this->isPersonPercent($postInfo['auth_status'], $postInfo['domain'])
            ? '100%个人房源请点击登录查看' : $postInfo['phone'] . '【' . $postInfo['person'] . '|' . $agent . '】');
        $ret['xiaoqu'] = $postInfo['xiaoqu'];
        $ret['address'] = $postInfo['xiaoqu_address'];
        $ret['ceng'] = $postInfo['ceng']. '/'. $postInfo['ceng_total'];
		$ret['price'] = $postInfo['price'] ? $postInfo['price'] . '元/月':'面议';
		$type = $postInfo['house_type'] ? '合租' : '整租';
		$housetype = array('主卧', '次卧', '隔断', '床位');
		//合租信息不全,改为单间合租
		if($postInfo['house_type'] == 0)
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
        return $ret;
    }
	/**
     * @格式化房2,推送到微信端回复图文 - 求租房频道
	 * 
     * @return $postInfo
    */
    protected function formatNewsFang2Info($postInfo)
    {
        $ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		
		//租金需要根据区域和城市获取价格-----price
		$prize_list = HousingVars::getUrlPriceRange($postInfo['domain'],$postInfo['major_category']);
		
		$price = !empty($prize_list[$postInfo['price']]) ? $prize_list[$postInfo['price']].'/月' : '面议';
		$ret['price'] = $price;
		//户型
		$ret['huxing'] = $postInfo['huxing_shi'].'室'.(!empty($postInfo['huxing_ting']) ? $postInfo['huxing_ting'].'厅' : '').(!empty($postInfo['huxing_wei']) ? $postInfo['huxing_wei'].'卫' : '');
		$ret['xiaoqu'] = $postInfo['xiaoqu'];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].'-'.$postInfo['street_name'];
		//地址
		$ret['address'] = !empty($postInfo['xiaoqu_address']) ? $postInfo['xiaoqu_address'] : '';
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['fangshi'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
        return $ret;
    }
	/**
     * @格式化房4,推送到微信端回复图文 - 二手房求购频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang4Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
		//租金需要根据区域和城市获取价格-----price
		$prize_list = HousingVars::getUrlPriceRange($postInfo['domain'],$postInfo['major_category']);
		$ret['price'] = !empty($prize_list[$postInfo['price']]) ? $prize_list[$postInfo['price']].'元' : '面议';
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		$ret['huxing'] = $postInfo['huxing_shi'].'室'.(!empty($postInfo['huxing_ting']) ? $postInfo['huxing_ting'].'厅' : '').(!empty($postInfo['huxing_wei']) ? $postInfo['huxing_wei'].'卫' : '');
		$ret['area'] = $postInfo['area'].'㎡';
		$ret['xiaoqu'] = $postInfo['xiaoqu'];
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].'-'.$postInfo['street_name'];
		$ret['address'] = !empty($postInfo['xiaoqu_address']) ? $postInfo['xiaoqu_address'] : '';
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['fangshi'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';

		return $ret;
	}
	
	/**
     * @格式化房6,推送到微信端回复图文 - 商铺出租频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang6Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
		//租金需要根据区域和城市获取价格-----price
		//$prize_list = HousingVars::getUrlPriceRange($postInfo['domain'],$postInfo['major_category']);
		$priceUnit = HousingVars::$STORE_PRICE_TYPE[$postInfo['price_type']];
		//价格
		if($postInfo['price_type'] == 1)
		{
			$prize_avg_time = round($postInfo['price']*30*$postInfo['area'],2);
			$ret['price'] = $postInfo['price'] > 0 ? $prize_avg_time.' 元/月'."（ {$postInfo['price']} 元/㎡·天）" : '面议';			
		}elseif($postInfo['price_type'] == 2)
		{
			$prize_avg_time = round($postInfo['price']/(30*$postInfo['area']),2);
			$ret['price'] = $postInfo['price'] > 0 ? $postInfo['price'].' 元/月'."（约 {$prize_avg_time} 元/㎡·天）" : '面议';			
		}elseif($postInfo['price_type'] == 3)
		{
			$prize_avg_time = round($postInfo['price']*$postInfo['area'],2);
			$ret['price'] = $postInfo['price'] > 0 ? $prize_avg_time.' 元/月'."（约 {$prize_avg_time} 元/㎡·月）" : '面议';			
		}
		
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		//面积
		$ret['area'] = $postInfo['area'].'㎡';
		
		if($postInfo['store_rent_type'] ==1)
		{
			$store_rent_type_name = '转让';
		}elseif($postInfo['store_rent_type'] ==2)
		{
			$store_rent_type_name = '出租';
		}
		switch($postInfo['store_stat'])
		{
			case 1:
				$store_stat_name ='新铺';
			break;
			case 2:
				$store_stat_name='空铺';
			break;
			case 3:
				$store_stat_name='营业中';
			break;
		}
		if($postInfo['zhuangxiu'])
		{
			$zhuangxiu_type = HousingVars::$ZX_TYPE;
			//概况
			$ret['gaikuang'] =$zhuangxiu_type[$postInfo['zhuangxiu']].(!empty($postInfo['ceng']) ? ' - '.'第'.$postInfo['ceng'].'层/' : '').(!empty($postInfo['ceng_total']) ? '共'.$postInfo['ceng_total'].'层' : '');
		}
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];
        $houseType = $houseTypeArr[$postInfo['house_type']];
		//商铺状态
		if($store_stat_name)
		{
			$ret['store_name'] = $store_stat_name.' '.$store_rent_type_name;
		}
		//商铺类型
		$ret['house_type'] = $houseType;
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		$ret['loupan_name'] =$postInfo['loupan_name'];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
		
		return $ret;
	}
	
	/**
     * @格式化房7,推送到微信端回复图文 - 商铺出售频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang7Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
		//租金需要根据区域和城市获取价格-----price
		//价格
		$ret['price'] = $postInfo['price'] > 0  ? $postInfo['price'].'万元' : '面议';
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		//面积
		$ret['area'] = $postInfo['area'].'㎡';
		if($postInfo['zhuangxiu'])
		{
			$zhuangxiu_type = HousingVars::$ZX_TYPE;
			//概况
			$ret['gaikuang'] =$zhuangxiu_type[$postInfo['zhuangxiu']].(!empty($postInfo['ceng']) ? ' - '.'第'.$postInfo['ceng'].'层/' : '').(!empty($postInfo['ceng_total']) ? '共'.$postInfo['ceng_total'].'层' : '');
		}
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];
        $houseType = $houseTypeArr[$postInfo['house_type']];
		
		//商铺类型
		$ret['house_type'] = $houseType;
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		//名称
		$ret['house_name'] = !empty($postInfo['house_name']) ? $postInfo['house_name'] : $postInfo['loupan_name'];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
		
		return $ret;
	}
	/**
     * @格式化房8,推送到微信端回复图文 - 写字楼出租频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang8Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
		//租金需要根据区域和城市获取价格-----price
		//价格
		//	7.0 元/㎡·天（合计82110元/月）
		if($postInfo['price_type'] == 1)
		{
			$prize_hj = $postInfo['price']*$postInfo['area']*30;
			$ret['price'] = $postInfo['price'] > 0 ? $postInfo['price'].'元/㎡·天'."（合计{$prize_hj}元/月）" : '面议';
		}elseif($postInfo['price_type'] == 2)
		{
			$prize_hj = round($postInfo['price']/(30*$postInfo['area']),2);
			$ret['price'] = $postInfo['price'] > 0 ? $prize_hj.'元/㎡·天'."（合计{$postInfo['price']}元/月）" : '面议';			
		}
		$ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		
		//面积
		$ret['area'] = $postInfo['area'].'㎡';
		if($postInfo['zhuangxiu'])
		{
			$zhuangxiu_type = HousingVars::$ZX_TYPE;
			//概况
			$ret['gaikuang'] =$zhuangxiu_type[$postInfo['zhuangxiu']].(!empty($postInfo['ceng']) ? ' - '.'第'.$postInfo['ceng'].'层/' : '').(!empty($postInfo['ceng_total']) ? '共'.$postInfo['ceng_total'].'层' : '');
		}
		
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];
        $houseType = $houseTypeArr[$postInfo['house_type']];
		
		//商铺类型
		$ret['house_type'] = $houseType;
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		//名称
		$ret['house_name'] =$postInfo['house_name'];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';

		return $ret;
	}
	/**
     * @格式化房9,推送到微信端回复图文 - 写字楼出售频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang9Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		//价格
		$ret['price'] = $postInfo['price'] >0 ? $postInfo['price'].'万元' : '面议';
		//面积
		$ret['area'] = $postInfo['area'].'㎡';
		//概况
		if(!empty($postInfo['zhuangxiu']))
		{
			$zhuangxiu_type = HousingVars::$ZX_TYPE;
			
			$ret['gaikuang'] =$zhuangxiu_type[$postInfo['zhuangxiu']].(!empty($postInfo['ceng']) ? ' - '.'第'.$postInfo['ceng'].'层/' : '').(!empty($postInfo['ceng_total']) ? '共'.$postInfo['ceng_total'].'层' : '');
		}	
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];
        $houseType = $houseTypeArr[$postInfo['house_type']];
		//商铺类型
		$ret['house_type'] = $houseType;
		//名称
		$ret['house_name'] =$postInfo['house_name'];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';

		return $ret;
	}
	
	/**
     * @格式化房10,推送到微信端回复图文 - 日租房/短租房频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang10Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		//价格
		$ret['price'] = $postInfo['price'] > 0 ? $postInfo['price'].' 元/天'.(!empty($postInfo['rent_date']) ? '(最短租期'.$postInfo['rent_date'].'天)' : ''): '面议';
		
		
		$fang_xing_type = HousingVars::$SHORTRENT_FANG_XING ;//短租求租类型 
		$rent_type_arr = array('0'=>'出租','1'=>'求租');
		//类型
		$ret['house_type'] = $fang_xing_type[$postInfo['fang_xing']].'-'.$rent_type_arr[$postInfo['rent_type']];
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|房东';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
		return $ret;
	}
	/**
     * @格式化房11,推送到微信端回复图文 - 厂房/仓库/土地频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang11Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		
		/* if($postInfo['house_type'] >=0)
		{
			if($postInfo['house_type'] == 0)
			{
				$price_house_type_name = ' 万元';
				$postInfo['price'] = intval($postInfo['price']);
				
			}elseif($postInfo['house_type'] == 1)
			{
				$price_house_type_name = ' 元/㎡·天';
			}elseif($postInfo['house_type'] ==3)
			{	
				$price_house_type_name = ' 元/月';
			}else{
				$price_house_type_name = ' 元/㎡·天';
			}
		}else{
			$price_house_type_name = ' 元/㎡·天';
		} */
		if($postInfo['deal_type'] == 3 || $postInfo['deal_type'] == 4 )
		{
			$price_house_type_name = ' 万元';
			$postInfo['price'] = intval($postInfo['price']);
		}else{
			if($postInfo['price_type'] == 1)
			{
				$price_house_type_name = ' 元/㎡·天';
			}elseif($postInfo['price_type'] == 2)
			{
				$price_house_type_name = ' 元/月';
			}elseif($postInfo['price_type'] ==0 )
			{
				$price_house_type_name = ' 万元';
			}
		}
		//价格
		$ret['price'] = $postInfo['price'] > 0 ? $postInfo['price'].$price_house_type_name :'面议';
		//面积
		$ret['area'] = $postInfo['area'].'㎡';
		
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];//房屋类型
        $houseType = $houseTypeArr[$postInfo['house_type']];
		$dealTypeArr = HousingVars::$DEAL_TYPE[$majorCategoryId];//房屋类型
		$dealType = $dealTypeArr[$postInfo['deal_type']];
		//根据 deal_type 获取供需类型 
		//类型
		$ret['house_type'] = $houseType.'-'.$dealType;
		
		//区域
		$ret['quyu'] = $postInfo['cityName'].'-'.$postInfo['district_name'].(!empty($postInfo['street_name']) ? '-'.$postInfo['street_name'] : '');
		//商铺地址
		$ret['address'] = !empty($postInfo['address']) ? $postInfo['address'] : '暂无信息';
		
		if(!empty($postInfo['house_id']))
		{
			$agent_name = '|经纪人';
		}else{
			$agent_name = $postInfo['agent'] ==1 ? '|经纪人' : '|个人';
		}
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
		
		return $ret;
	}
	/**
     * @格式化房12,推送到微信端回复图文 - 新房出售频道
	 * 
     * @return $postInfo
    */
	protected function formatNewsFang12Info($postInfo)
	{
		$ret['puid'] = $postInfo['puid'];
        $ret['title'] = $postInfo['title'];
        $ret['thumb_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($postInfo['thumb_img'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['url'] = $this->setDetailUrlWithCountParams($this->getWapAccessUrl($postInfo['domain'], $postInfo['major_category'], $postInfo['puid']), $this->ifid, $this->ca_n);
		//价格
		$ret['price'] = $postInfo['price'] > 0 ? round($postInfo['price']/10000, 2) .' 万元(10000.0元/㎡)':'面议';
		//面积
		$ret['huxing'] = $postInfo['huxing_shi'].'室'.$postInfo['huxing_ting'].'厅'.$postInfo['huxing_wei'].'卫'.' - '.$postInfo['area'].'㎡';
		//概况
		if(!empty($postInfo['zhuangxiu']))
		{
			$zhuangxiu_type = HousingVars::$ZX_TYPE;//装修type
			$chaoxiang_type = HousingVars::$CX_TYPE;//朝向type
			$fang_xing_type = HousingVars::$FANG_XING_TYPE;//房型type
			$ret['gaikuang'] =$zhuangxiu_type[$postInfo['zhuangxiu']].' - '.$chaoxiang_type[$postInfo['chaoxiang']].' - '.$fang_xing_type[$postInfo['fang_xing']];
		}
		//楼层
		$ret['lou_ceng'] = '第'.$postInfo['ceng'].'层/'.'共'.$postInfo['ceng_total'].'层';
		$ret['xiaoqu'] = $postInfo['xiaoqu'];
		//地址
		$ret['address'] = !empty($postInfo['xiaoqu_address']) ? $postInfo['xiaoqu_address'] : '暂无信息';

		$agent_name = $postInfo['agent'] == 0 ? ' |个人' : ' |经纪人';
		$ret['phone'] = $postInfo['phone'].'【'.$postInfo['person'].$agent_name.'】';
		
		//var_dump(Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionNewsInfo')->getNews($ret,$postInfo['major_category']));	
		//var_dump($ret);exit;
		return $ret;
	}
	
	//---------------------------------------------收藏数据入库前格式title---------------------------------------------------
	/**
     * @brief 格式化入库的title字段-fang1出租房源（FANG1/3）
     * @param $postInfo
     * @return string
     */
    protected function formatSaveDataFang1Title($postInfo)
    {
        $housetype = array('主卧', '次卧', '隔断', '床位');

        $startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
        $midTitle = $postInfo['house_type'] ? $housetype[$postInfo['house_type'] - 1] : $postInfo['huxing_shi'] . '室';
		$endTitle = $postInfo['price']?$postInfo['price'] . '元/月':'面议';
		
        return implode('|', array($startTitle, $midTitle, $endTitle));
    }
	/**
     * @brief 格式化入库的title字段-fang3出租房源（FANG1/3）
     * @param $postInfo
     * @return string
     */
    protected function formatSaveDataFang3Title($postInfo)
    {
        $housetype = array('主卧', '次卧', '隔断', '床位');

        $startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$midTitle =   $postInfo['house_type'] ? $housetype[$postInfo['house_type'] - 1] : $postInfo['huxing_shi'] . '室';
		if($postInfo['major_category'] == 3 && $postInfo['house_type'] == 0)
		{
			if(empty($postInfo['huxing_shi']) || empty($postInfo['huxing_ting']))
			{
				$share_mode_name = $postInfo['share_mode'] == 1 ? '单间出租' : '床位出租';
				$midTitle = $share_mode_name;
			}
		}
        $endTitle = $postInfo['price'] > 0 ?$postInfo['price'] . '元/月':'面议';
		
        return implode('|', array($startTitle, $midTitle, $endTitle));
    }
	/**
     * @brief 格式化入库的title字段-fang5二手房出售
     * @param $postInfo
     * @return string
    */
    protected function formatSaveDataFang5Title($postInfo)
    {
        $housetype = array('主卧', '次卧', '隔断', '床位');

        $startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
        $midTitle = $postInfo['house_type'] ? $housetype[$postInfo['house_type'] - 1] : $postInfo['huxing_shi'] . '室';
		$endTitle =  $postInfo['price'] > 0 ?round($postInfo['price']/10000, 2). '万元' : '面议';
        
        return implode('|', array($startTitle, $midTitle, $endTitle));
    }
	/**
     * @brief 格式化入库的title字段- FANG2——求租信息
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang2Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		
		//租金需要根据区域和城市获取价格-----price
		$prize_list = HousingVars::getUrlPriceRange($postInfo['domain'],$postInfo['major_category']);
		$price = !empty($prize_list[$postInfo['price']]) ? $prize_list[$postInfo['price']].'/月' : '面议';
		return implode('|', array($startTitle, $price));
	}
	/**
     * @brief 格式化入库的title字段- FANG4——二手房求购信息
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang4Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$prize_list = HousingVars::getUrlPriceRange($postInfo['domain'],$postInfo['major_category']);
		$price = !empty($prize_list[$postInfo['price']]) ? $prize_list[$postInfo['price']].'/月' : '面议';
		return implode('|', array($startTitle, $price));
	}
	/**
     * @brief 格式化入库的title字段- FANG6——门面旺铺出租
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang6Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$area_value = round($postInfo['area']).'㎡';
		
		//(price_type 1:元/m²·天 、2:元/月 、3:元/m².月)
		if($postInfo['price_type'] == 1)
		{
			$prize_avg_time = round($postInfo['price']*30*$postInfo['area'],2);
			$price = $postInfo['price'] > 0 ? $prize_avg_time.' 元/月' : '面议';			
		}elseif($postInfo['price_type'] == 2)
		{
			$price = $postInfo['price'] > 0 ? $postInfo['price'].' 元/月' : '面议';			
		}elseif($postInfo['price_type'] == 3)
		{
			$prize_avg_time = round($postInfo['price']*$postInfo['area']);
			$price = $postInfo['price'] > 0 ? $prize_avg_time.' 元/月' : '面议';			
		}else{
			$prize_zong = round($postInfo['price']*30*$postInfo['area'],2);
			$price = $postInfo['price'] >0 ? $prize_zong.'元/月' : '面议';
		}
		
		return implode('|', array($startTitle, $area_value,$price));
	}
	/**
     * @brief 格式化入库的title字段- FANG7——门面旺铺出售
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang7Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$area_value = round($postInfo['area']).'㎡';
		$price = $postInfo['price'] > 0 ? round($postInfo['price']).'万元' : '面议';
		return implode('|', array($startTitle, $area_value,$price));
	}
	/**
     * @brief 格式化入库的title字段- FANG8——写字楼出租
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang8Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$area_value = round($postInfo['area']).'㎡';
		
		//price_type (1:元/㎡·天、2:元/月)
		if($postInfo['price_type'] == 1)
		{
			$price = $postInfo['price'] > 0 ? round($postInfo['price'],1).' 元/㎡·天' : '面议';
		}elseif($postInfo['price_type'] == 2)
		{
			$prize_hj = round($postInfo['price']/(30*$postInfo['area']),1);
			$price = $postInfo['price'] > 0 ? $prize_hj.'元/㎡·天' : '面议';			
		}else{
			$price = $postInfo['price'] >0 ? round($postInfo['price'],1).' 元/㎡·天' : '面议';
		}
		
		return implode('|', array($startTitle, $area_value,$price));
	}
	/**
     * @brief 格式化入库的title字段- FANG9——写字楼出售
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang9Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$area_value = round($postInfo['area']).'㎡';
		$price = $postInfo['price'] > 0 ? round($postInfo['price']).' 万元' : '面议';
		return implode('|', array($startTitle, $area_value,$price));
	}
	/**
     * @brief 格式化入库的title字段- FANG10——日租房/短租房
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang10Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		
		$fang_xing_type = HousingVars::$SHORTRENT_FANG_XING ;//短租求租类型 
		//类型
		$house_type = $fang_xing_type[$postInfo['fang_xing']];
		$price = $postInfo['price'] > 0 ? $postInfo['price'].' 元/天' : '面议';
		return implode('|', array($startTitle,$house_type,$price));
	}
	/**
     * @brief 格式化入库的title字段 - FANG11——厂房/仓库/车位/土地等
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang11Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		
		//major_category 类别 获取商铺类型
		$majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$postInfo['major_category']];
        $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];//房屋类型
        $house_type = $houseTypeArr[$postInfo['house_type']];
		//类型
		/*
		if($postInfo['house_type'] >=0)
		{
			if($postInfo['house_type'] == 0)
			{
				$price_house_type_name = ' 万元';
				$postInfo['price'] = intval($postInfo['price']);
				
			}elseif($postInfo['house_type'] == 1)
			{
				$price_house_type_name = ' 元/㎡·天';
			}elseif($postInfo['house_type'] ==3)
			{
				$price_house_type_name = ' 元/月';
			}else{
				$price_house_type_name = ' 元/㎡·天';
			}
		}else{
			$price_house_type_name = ' 元/㎡·天';
		}
		*/
		if($postInfo['deal_type'] == 3 || $postInfo['deal_type'] == 4 )
		{
			$price_house_type_name = ' 万元';
			$postInfo['price'] = intval($postInfo['price']);
		}else{
			if($postInfo['price_type'] == 1)
			{
				$price_house_type_name = ' 元/㎡·天';
			}elseif($postInfo['price_type'] == 2)
			{
				$price_house_type_name = ' 元/月';
			}elseif($postInfo['price_type'] ==0 )
			{
				$price_house_type_name = ' 万元';
			}
		}
		$price = $postInfo['price'] > 0 ? $postInfo['price'].$price_house_type_name : '面议';
		return implode('|', array($startTitle,$house_type,$price));
	}
	/**
     * @brief 格式化入库的title字段- FANG12——新房出售
     * @param $postInfo
     * @return string
    */
	protected function formatSaveDataFang12Title($postInfo)
	{
		$startTitle = $postInfo['street_name'] ? $postInfo['street_name'] : ($postInfo['district_name'] ? $postInfo['district_name'] : $postInfo['cityName']);
		$midTitle =   $postInfo['huxing_shi'] . '室';
		
		$price = !empty($postInfo['price']) ? round($postInfo['price']/10000, 2).' 万元' : '面议';
		return implode('|', array($startTitle,$midTitle,$price));
	}
	
	//---------------------------------------------收藏数据入库前格式title---------------------------------------------------
	
	
	//----------------------------------以下是处理图片和拼装url 的function----------------------------------------------
	
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
     * @brief add ifid ca_n ca_s for a detail page url
     * @params ifid ca_n ca_s
     * @return string
     */
    public function setDetailUrlWithCountParams($url, $ifid, $ca_n, $ca_s = 'other_weixin')
	{

        $url = explode('?', $url)[0];
        if(!strpos($url, 'fangweixin')){
            $url = str_replace('3g', 'fangweixin.3g', $url);
        }
       return $url."?ifid=".$ifid."&ca_n=".$ca_n."&ca_s=".$ca_s.'&_gjassid=fV0x40R2Gk9V'; 
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
}
