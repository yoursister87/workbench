<?php

/**
 * @package
 * @subpackage
 * @brief      			 $格式化微信收藏数据,根据不同的帖子分类$
 * @file                 WeixinCollectionNewsInfo.class.php$
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         2015-05-29
 * @lastmodified         上午17:00
 * @copyright            Copyright (c) 2014,fangweixin.3g.ganji.com
 */
//error_reporting(E_WARNING);
class Service_Data_Weixin_WeixinCollectionNewsInfo
{
	
	/**
	 * 格式化房源数据,推送微信图文消息 - 入口
	 * 
	 * @return array();
	*/
	public function getNews($data,$major_category=1)
	{
		if(empty($data)) return array();
		$method_name = __FUNCTION__.'Fang'.$major_category.ucfirst('info');
		if(method_exists($this,$method_name))
		{
			return call_user_func(array($this,$method_name),$data);
		}else{
			return array();
		}
	}
	/**
	 * fang1 出租房源（FANG1/3）
	*/
	protected function getNewsFang1Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "户型：" . $postInfo['huxing'] . "\n";
        $desciption .= "楼层：" . $postInfo['ceng'] . "\n";
        $desciption .= "小区：" . $postInfo['xiaoqu'] . "\n";
        $postInfo['address'] = empty($postInfo['address']) ? "暂无信息" : $postInfo['address'];
        $desciption .= "位置：" . $postInfo['address'] . "\n";
        $desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang3 出租房源（FANG1/3）
	*/
	protected function getNewsFang3Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "户型：" . $postInfo['huxing'] . "\n";
        $desciption .= "楼层：" . $postInfo['ceng'] . "\n";
		if(!empty($postInfo['xiaoqu']))
		{
			$desciption .= "小区：" . $postInfo['xiaoqu'] . "\n";
		}
		
        $postInfo['address'] = empty($postInfo['address']) ? "" : $postInfo['address'];
		if(!empty($postInfo['address']))
		{
			$desciption .= "位置：" . $postInfo['address'] . "\n";
		}
        $desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang5 二手房出售（FANG5）
	*/
	protected function getNewsFang5Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "户型：" . $postInfo['huxing'] . "\n";
        $desciption .= "楼层：" . $postInfo['ceng'] . "\n";
        $desciption .= "小区：" . $postInfo['xiaoqu'] . "\n";
        $postInfo['address'] = empty($postInfo['address']) ? "" : $postInfo['address'];
		if(!empty($postInfo['address']))
		{
			$desciption .= "位置：" . $postInfo['address'] . "\n";
		}
        $desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang2 - 求租信息（FANG2）
	*/
	protected function getNewsFang2Info($postInfo)
    {
        $desciption = "期望租金：" . $postInfo['price'] . "\n";
        $desciption .= "期望户型：" . $postInfo['huxing'] . "\n";
        //$desciption .= "楼层：" . $postInfo['ceng'] . "\n";
		if(!empty($postInfo['xiaoqu']))
		{
			$desciption .= "期望小区：" . $postInfo['xiaoqu'] . "\n";
		}
        $desciption .= "期望区域：" . $postInfo['quyu'] . "\n";
		if(!empty($postInfo['address']))
		{
			$desciption .= "期望地点：" . $postInfo['address'] . "\n";
		}
        $desciption .= "联系方式：" . $postInfo['fangshi'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang4 - 	二手房求购频道（FANG4）
	*/
	protected function getNewsFang4Info($postInfo)
    {
        $desciption = "期望售价：" . $postInfo['price'] . "\n";
        $desciption .= "期望户型：" . $postInfo['huxing'] . "\n";
        //$desciption .= "楼层：" . $postInfo['ceng'] . "\n";
		if($postInfo['area'] > 0)
		{
			$desciption .= "期望面积：" . $postInfo['area'] . "\n";
		}
		if(!empty($postInfo['xiaoqu']))
		{
			$desciption .= "期望小区：" . $postInfo['xiaoqu'] . "\n";
		}
		$desciption .= "期望区域：" . $postInfo['quyu'] . "\n";
		if(!empty($postInfo['address']))
		{
			$desciption .= "期望地点：" . $postInfo['address'] . "\n";
		}
		$desciption .= "联系方式：" . $postInfo['fangshi'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang6 - 	商铺出租频道（FANG6）
	*/
	protected function getNewsFang6Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
		if($postInfo['area'] > 0)
		{
			$desciption .= "面积：" . $postInfo['area'] . "\n";
		}
        if(!empty($postInfo['gaikuang']))
		{
			$desciption .= "概况：" . $postInfo['gaikuang'] . "\n";
		}
		if(!empty($postInfo['store_name']))
		{
			$desciption .= "状态：" . $postInfo['store_name'] . "\n";
		}
		$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		if($postInfo['loupan_name'])
		{
			$desciption .= "名称：" . $postInfo['loupan_name'] . "\n";
		}
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang7 - 	商铺出租频道（FANG7）
	*/
	protected function getNewsFang7Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "面积：" . $postInfo['area'] . "\n";
		if(!empty($postInfo['gaikuang']))
		{
			$desciption .= "概况：" . $postInfo['gaikuang'] . "\n";
		}
		$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		if(!empty($postInfo['house_name']))
		{
			$desciption .= "名称：" . $postInfo['house_name'] . "\n";
		}
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang8 - 	写字楼出租频道（FANG8）
	*/
	protected function getNewsFang8Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "面积：" . $postInfo['area'] . "\n";
		if(!empty($postInfo['gaikuang']))
		{
			$desciption .= "概况：" . $postInfo['gaikuang'] . "\n";
		}
		$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		if(!empty($postInfo['house_name']))
		{
			$desciption .= "名称：" . $postInfo['house_name'] . "\n";
		}
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang9 - 	写字楼出租频道（FANG9）
	*/
	protected function getNewsFang9Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
        $desciption .= "面积：" . $postInfo['area'] . "\n";
		if(!empty($postInfo['gaikuang']))
		{
			$desciption .= "概况：" . $postInfo['gaikuang'] . "\n";
		}
		$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		if(!empty($postInfo['house_name']))
		{
			$desciption .= "名称：" . $postInfo['house_name'] . "\n";
		}
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang10 - 	写字楼出租频道（FANG10）
	*/
	protected function getNewsFang10Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
		$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang11 - 	厂房/仓库/土地频道（FANG11）
	*/
	protected function getNewsFang11Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
		$desciption .= "面积：" . $postInfo['area'] . "\n";
		if(!empty($postInfo['house_type']))
		{
			$desciption .= "类型：" . $postInfo['house_type'] . "\n";
		}
		$desciption .= "区域：" . $postInfo['quyu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "方式：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
	/**
	 * fang12 - 	新房出售频道（FANG12）
	*/
	protected function getNewsFang12Info($postInfo)
    {
        $desciption = "价格：" . $postInfo['price'] . "\n";
		$desciption .= "户型：" . $postInfo['huxing'] . "\n";
		if(!empty($postInfo['gaikuang']))
		{
			$desciption .= "概况：" . $postInfo['gaikuang'] . "\n";
		}
		$desciption .= "楼层：" . $postInfo['lou_ceng'] . "\n";
		$desciption .= "小区：" . $postInfo['xiaoqu'] . "\n";
		$desciption .= "地址：" . $postInfo['address'] . "\n";
		$desciption .= "电话：" . $postInfo['phone'] . "\n";
		$desciption .= "\n";
		$desciption .= "\n";
		$desciption .='（查看所有收藏，请回复“我的收藏”）';
		
        $news[] = array(
            "Title" => $postInfo['title'],
            "Description" => $desciption,
            "PicUrl" => $postInfo['big_img'] ? $postInfo['big_img'] : $postInfo['thumb_img'],
            "Url" => $postInfo['url'],
        );

        return $news;
    }
}
