<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class ErrorCode{
	//{{{ CODE2MSG
	protected static $CODE2MSG = array(
			'2101'  => '和其他密码一致，修改失败',
			'2102'  => '密码修改失败',
			'2103'  => '该邮箱没有注册，可以转端口',
			'2104'  => '该邮箱已经注册，可以输入密码后转端口',
			'2105'  => '该邮箱已经开通帮帮端口',
			'2106'  => '密码错误',
			'2107'  => '没有这个用户，填入信息有误',
			'2108'  => '用户被锁定',
			'2109'  => '强制用户修改密码',
			'2110'  => '转端口失败',
			'2111'  => '修改经纪人资料失败',
			'2112'  => '经纪人转门店失败',
			'2113'  => '添加Email修改记录失败',
			'2114'  => '验证码已成功发送',
			'2115'  => '验证码发送失败，请稍后重试',
			'2116'  => '已经达到今天的最大发送次数',
			'2117'  => '发送过于频繁，请稍后重试',
			'2118'  => '验证码校验成功!',
			'2119'  => '验证码校验失败!',
			'2120'  => '您输入的登录名或密码错误!',
			'2121'  => '获取经纪人状态失败',
            '2122'  => '获取Gcc失败',
            '2123'  =>  '评论包含敏感词，请重新输入！',
            '2124'  =>  '评论不能超过300条，请删除后再评论！',
            '2125'  =>  '一条房源只能评价一次！',
            '2126'  =>  '获取指定城市下的真房源公司失败！',
	);//}}}
	public static function returnData($code){
		$data['errorno'] = $code;
		$data['errormsg'] = self::$CODE2MSG[$code];
		return $data;
	}
}