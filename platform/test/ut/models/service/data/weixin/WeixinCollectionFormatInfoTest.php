<?php

/**
 * @package
 * @subpackage
 * @brief                $微信格式化微信收藏数据单元测试$
 * @file                 WeixinCollectionInfoTest.php
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         14-11-26
 * @lastmodified         上午10:55
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Weixin_WeixinCollectionFormatInfoMock extends Service_Data_Weixin_WeixinCollectionFormatInfo 
{
	public function formatNewsFangMajorCategoryInfo($postInfo)
	{
		if($postInfo['major_category'])
		{
			$func = 'formatNewsFang'.$postInfo['major_category'].'Info';
			return parent::$func($postInfo);
		}
	}
	public function formatSaveData($data,$major_category=1)
	{
		return parent::formatSaveData($data,$major_category);
	}
	public function getImageUrlBySize($url, $width, $height, $type = 'c', $quality = 6, $version = 0)
    {
        return parent::getImageUrlBySize($url, $width, $height, $type, $quality, $version);
    }
	public function getWapAccessUrl($domain, $majorCategory, $puid)
    {
        return parent::getWapAccessUrl($domain, $majorCategory, $puid);
    }
	public function isPersonPercent($auth_status = null, $domain)
    {
        return parent::isPersonPercent($auth_status, $domain);
    }
}

class WeixinCollectionFormatInfoTest extends Testcase_PTest
{
	protected $obj; 
	
	public function testformatNews()
	{
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals(array(), $this->obj->formatNews(array('123'),101));
	}
	public function testformatSaveData()
	{
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals(array(), $this->obj->formatSaveData(array('123'),101));
	}
	public function testformatNewsFang1Info()
	{
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		
		$postInfo = array(
			'house_id' => '1404877818',
			'puid' => '98425063',
			'city' => '0',
			'district_id' => '5',
			'district_name' => '宣武',
			'street_id' => '12',
			'street_name' => '陶然亭',
			'title' => '东贸国际花园东贸国际花园东贸国际花园',
			'description' => '',
			'thumb_img' => '',
			'image_count' => '0',
			'type' => '1',
			'priority' => '18',
			'price' => '5000',
			'person' => 'bjfang',
			'phone' => '15236363630',
			'xiaoqu' => '一瓶兰亭',
			'xiaoqu_id' => '100951',
			'xiaoqu_address' => '陶然亭路2号',
			'pinyin' => 'yipinglanting',
			'fang_xing' => '3',
			'area' => '50.00',
			'ceng' => '12',
			'ceng_total' => '21',
			'chaoxiang' => '5',
			'zhuangxiu' => '3',
			'pay_type' => '押一付三',
			'huxing_shi' => '1',
			'huxing_ting' => '1',
			'huxing_wei' => '1',
			'peizhi' => 'chuang',
			'major_category' => '1',
			'domain' => 'bj',
			'cityName' => '北京',
		);
		$equals_arr = array(
			'puid' => '98425063',
			'title' => '东贸国际花园东贸国际花园东贸国际花园',
			'thumb_img' => 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png',
			'big_img' => 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png',
			'url' => 'http://fangweixin.3g.ganji.com/bj_fang1/98425063x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V',
			'phone' => '15236363630【bjfang|经纪人】',
			'xiaoqu' => '一瓶兰亭',
			'address' => '陶然亭路2号',
			'ceng' => '12/21',
			'price' => '5000元/月',
			'huxing' => '1室1厅1卫-整租-50.00㎡',
		);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang5Info()
	{
		$json_str ='{"house_id":"69172218","puid":"98238316","account_id":"1003192","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"70","street_name":"\u897f\u4e8c\u65d7","title":"\u6d77\u6dc0\u897f\u4e8c\u65d7\u94ed\u79d1\u82d1\u597d\u623f","description":"","ip":"3232247043","thumb_img":"gjfstmp2\/M00\/00\/02\/wKgCzFU0km2IZDuKAAG,xoWbm5YAAAA9wHfGk8AAb,e32_120-100_9-0.jpeg","image_count":"4","type":"5","premier_status":"100","bid_status":"0","listing_status":"1","is_similar":"0","post_at":"1429508739","refresh_at":"1429508739","modified_at":"1429508739","rand_refresh_at":"0","priority":"4010","price":"2100000","minprice_guide":"0","maxprice_guide":"0","price_bought":"0","downpayments_require":"0","downpayments_calculate":"0","person":"\u623f\u4e94\u6d4b\u8bd5","phone":"13609874532","xiaoqu":"\u94ed\u79d1\u82d1","xiaoqu_id":"864","xiaoqu_address":"\u5b89\u5b81\u5e84\u897f\u8def29\u53f7","pinyin":"mingkeyuan","fang_xing":"3","house_property":"1","fiveyears":"1","only_house":"1","land_tenure":"1","bid_structure":"1","elevator":null,"latlng":"b116.32078089176,40.05826311691","area":"80.00","area_inside":"75.00","ceng":"5","ceng_total":"6","chaoxiang":"6","niandai":"1999","zhuangxiu":"4","huxing_shi":"2","huxing_ting":"1","huxing_wei":"1","subway":"","bus_station":"","tag_type":"0","tag_create_at":"0","tab_system":"15","tab_personality":"","loan_require":"0","monthly_payments":null,"ad_types":"4194304","ad_status":"4194304","user_id":"1500359327","cookie_id":"1537228189529584836054-849533791","major_category":"5","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"98238316","title":"\u6d77\u6dc0\u897f\u4e8c\u65d7\u94ed\u79d1\u82d1\u597d\u623f","thumb_img":"http:\/\/image.ganjistatic1.com\/gjfstmp2\/M00\/00\/02\/wKgCzFU0km2IZDuKAAG,xoWbm5YAAAA9wHfGk8AAb,e32_200-200c_6-0.jpeg","big_img":"http:\/\/image.ganjistatic1.com\/gjfstmp2\/M00\/00\/02\/wKgCzFU0km2IZDuKAAG,xoWbm5YAAAA9wHfGk8AAb,e32_360-200c_6-0.jpeg","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang5\/98238316x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","phone":"13609874532\u3010\u623f\u4e94\u6d4b\u8bd5|\u7ecf\u7eaa\u4eba\u3011","xiaoqu":"\u94ed\u79d1\u82d1","address":"\u5b89\u5b81\u5e84\u897f\u8def29\u53f7","ceng":"5\/6","huxing":"2\u5ba41\u53851\u536b-80.00\u33a1","price":"210\u4e07\u5143"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang3Info()
	{
		$json_str ='{"house_id":"933265","puid":"97964956","account_id":"1000275","city":"100","district_id":"0","district_name":"\u95f5\u884c","street_id":"10","street_name":"\u5434\u6cfe","title":"\u3010\u95f5\u884c\u30115\u53f7\u7ebf \u5251\u5ddd\u8def \u5434\u6cfe \u5609\u6021\u6c34\u5cb8 \u5b9e\u4f53\u4e3b\u5367","description":"","ip":"3232236252","thumb_img":"","image_count":"0","type":"3","premier_status":"0","bid_status":"0","listing_status":"1","is_similar":"0","post_at":"1415954149","refresh_at":"1415954149","modified_at":"1423561188","rand_refresh_at":"0","priority":"24","price":"1310","person":"\u7cbe\u54c1\u516c\u5bd3","phone":"13562489742","xiaoqu_id":"219078","xiaoqu":"\u5609\u6021\u6c34\u5cb8","xiaoqu_address":"\u9f99\u5434\u8def 5899\u53f7\n","pinyin":"jiayishuian","fang_xing":"4","latlng":"b121.46721992426,31.048526576534","area":"0.00","ceng":"1","ceng_total":"12","chaoxiang":"2","zhuangxiu":"2","pay_type":"","peizhi":"","subway":"","college":"","bus_station":"","share_mode":"1","house_type":"1","rent_sex_request":"0","tag_type":"32","tag_create_at":"0","tab_system":"","tab_personality":"\u72ec\u7acb\u98d8\u7a97","ad_types":"2097152","ad_status":"2097152","user_id":"500009378","cookie_id":"","major_category":"3","domain":"sh","cityName":"\u4e0a\u6d77"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"97964956","title":"\u3010\u95f5\u884c\u30115\u53f7\u7ebf \u5251\u5ddd\u8def \u5434\u6cfe \u5609\u6021\u6c34\u5cb8 \u5b9e\u4f53\u4e3b\u5367","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/sh_fang3\/97964956x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","phone":"13562489742\u3010\u7cbe\u54c1\u516c\u5bd3|\u7ecf\u7eaa\u4eba\u3011","xiaoqu":"\u5609\u6021\u6c34\u5cb8","address":"\u9f99\u5434\u8def 5899\u53f7\n","ceng":"1\/12","price":"1310\u5143\/\u6708","huxing":"\u4e3b\u5367"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang2Info()
	{
		$json_str ='{"id":"1204531","user_id":"1000136710","username":"limingfeng","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"11","street_name":"\u4e94\u9053\u53e3","title":"\u6211\u60f3\u6709\u4e00\u4e2a\u5bb6","description":"\u66f4\u597d\u66f4\u5927\u66f4\u5f3a\u3002\u4e00\u5b9a\u8981\u52aa\u529b\u54e6\uff01","ip":"3232249551","thumb_img":"","image_count":null,"post_at":"1419234762","refresh_at":"1419234871","show_time":"1419234761","price":"4","person":"mr\u674e","phone":"18210138336","major_category":"2","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"1","show_before_audit":"0","show_before_audit_reason":"\u6ce8\u518c\u5bc6\u7801\u4e3a\u7a7a|\u514d\u5ba1\u7c7b\u522b|\u6ce8\u518cIP\u4e3a\u7a7a|","post_type":"0","cookie_id":"2957354285652226515381-266295231","xiaoqu_id":"0","xiaoqu":"\u7d2b\u7981\u57ce","xiaoqu_address":"\u60fa\u60fa\u60dc\u60fa\u60fa","latlng":"","area":"0.0","pinyin":"","fang_xing":"0","huxing_shi":"3","huxing_ting":"2","huxing_wei":"2","peizhi":"","jichu":"","source_type":"0","source_desc":"55883","puid":"97999663","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"97999663","title":"\u6211\u60f3\u6709\u4e00\u4e2a\u5bb6","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang2\/97999663x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","price":"1500-2000\u5143\/\u6708","huxing":"3\u5ba42\u53852\u536b","xiaoqu":"\u7d2b\u7981\u57ce","quyu":"\u5317\u4eac-\u6d77\u6dc0-\u4e94\u9053\u53e3","address":"\u60fa\u60fa\u60dc\u60fa\u60fa","fangshi":"18210138336\u3010mr\u674e|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang4Info()
	{
		$json_str ='{"id":"783377","user_id":"10204684","username":"lajidx","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"0","street_name":"\u4e2d\u5173\u6751","title":"\u60f3\u4e70\u4e2a\u623f\u5b50\u4e3a\u5b69\u5b50\u4e0a\u5b66","description":"\u80fd\u7ed9\u5b69\u5b50\u4e0a\u5b66\u7684\u623f\u5b50\u3002\u81ea\u5df1\u4e5f\u5f97\u4f4f\u3002","ip":"2071229723","thumb_img":"","image_count":"0","post_at":"1279349233","refresh_at":"1326857400","show_time":"1279349233","price":"3","person":"\u5218\u4e70\u5356","phone":"13520776348","major_category":"4","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"","post_type":"0","cookie_id":"cc9718e8-916e-11df-b9be-0024e86a8b8f","xiaoqu_id":"0","xiaoqu":"","xiaoqu_address":"","latlng":"","area":"60.0","pinyin":"","fang_xing":"3","huxing_shi":"2","huxing_ting":"1","huxing_wei":"1","source_type":"0","source_desc":"","puid":"922975","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"922975","title":"\u60f3\u4e70\u4e2a\u623f\u5b50\u4e3a\u5b69\u5b50\u4e0a\u5b66","price":"80-100\u4e07\u5143","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang4\/922975x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","huxing":"2\u5ba41\u53851\u536b","area":"60.0\u33a1","xiaoqu":"","quyu":"\u5317\u4eac-\u6d77\u6dc0-\u4e2d\u5173\u6751","address":"","fangshi":"13520776348\u3010\u5218\u4e70\u5356|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang6Info()
	{
		$json_str ='{"id":"1672535","user_id":"50020564","username":"sweep","password":"","city":"0","district_id":"0","street_id":"3","district_name":"\u6d77\u6dc0","street_name":"\u4e0a\u5730","title":"\u5546\u94fa\u6d4b\u8bd5\u6d4b\u8bd5\u9760\u5927\u5bb6\u5206\u5f00","description":"\u5206\u9760\u5927\u5bb6\u5f00\u98de\u673a\u5feb\u70b9\u98de\u673a\u9760\u5927\u5bb6\u5206\u5f00\u7684\u4e86\u65af\u67ef\u8fbe\u5c06\u9644\u8fd1\u7684\u5f00\u98de\u673a\u75af\u72c2\u5927\u5bb6\u5206\u5f00\u7684","ip":"3232247200","thumb_img":"","image_count":"0","post_at":"1351480503","refresh_at":"1351491233","show_time":"1351480503","price":"1000.0","person":"\u5f20\u5148\u751f","phone":"15810443144","major_category":"6","deal_type":"1","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u5f02\u5730ip|","post_type":"0","cookie_id":"8854796621806775777536-758851971","address":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00\u7684\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u3002","latlng":"b116.40540253696,39.915686699931","price_type":"1","area":"100","house_type":"4","shopping":"30","trade":"1","source_type":"0","source_desc":"","puid":"90356463","loupan_id":"9116","loupan_name":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00","top_info":"","ad_types":"0","ad_status":"0","store_rent_type":"0","store_stat":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"90356463","title":"\u5546\u94fa\u6d4b\u8bd5\u6d4b\u8bd5\u9760\u5927\u5bb6\u5206\u5f00","price":"3000000 \u5143\/\u6708\uff08 1000.0 \u5143\/\u33a1\u00b7\u5929\uff09","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang6\/90356463x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","area":"100\u33a1","house_type":"\u5546\u4e1a\u8857\u5546\u94fa","address":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00\u7684\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u3002","loupan_name":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00","quyu":"\u5317\u4eac-\u6d77\u6dc0-\u4e0a\u5730","phone":"15810443144\u3010\u5f20\u5148\u751f|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang7Info()
	{
		$json_str ='{"id":"822922","user_id":"50004326","username":"jl1986","password":"","city":"0","district_id":"1","street_id":"11","district_name":"\u671d\u9633","street_name":"\u6f58\u5bb6\u56ed","title":"\u5546\u94fa\u6c42\u8d2d\uff0c\u67aa<\/span>lt;\/spangt;\u67aa<\/span>lt;\/spangt;","description":"\u5546\u94fa\u6c42\u8d2d\uff0c\u67aa<\/span><\/span>\u67aa<\/span><\/span>\u5546\u94fa\u6c42\u8d2d\u4f60\u597d\u4f60\u597d\u4f60\u597d\u4f60\u597d\u4f60\u8bf4\u64e6\u5f00\u6d3b\u52a8\u5f00\u597d\u623fnsihwsgfuiwghiwghquidbh","ip":"1920015644","thumb_img":"gjfstmp1\/M00\/0A\/30\/wKhwI07-nRnyRIUcAABxq5cyi00177_90-75c_6-0.jpg","image_count":"1","post_at":"1325309221","refresh_at":"1326857490","show_time":"1325309221","price":"111.0","person":"\u8d75\u6625\u71d5","phone":"15172456789","major_category":"7","deal_type":"1","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"2","show_before_audit":"1","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u6807\u9898\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa,\u5185\u5bb9\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa|\u5f02\u5730\u7535\u8bdd|","post_type":"0","cookie_id":"7724313722519323619962","address":"","latlng":"0,0","area":"111","house_type":"7","shopping":"30","trade":"1","source_type":"0","source_desc":"","puid":"90032061","loupan_id":"3390","loupan_name":"\u7fcc\u666f\u5bb6\u56ed","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"90032061","title":"\u5546\u94fa\u6c42\u8d2d\uff0c\u67aa<\/span>lt;\/spangt;\u67aa<\/span>lt;\/spangt;","price":"111.0\u4e07\u5143","thumb_img":"http:\/\/image.ganjistatic1.com\/gjfstmp1\/M00\/0A\/30\/wKhwI07-nRnyRIUcAABxq5cyi00177_200-200c_6-0.jpg","big_img":"http:\/\/image.ganjistatic1.com\/gjfstmp1\/M00\/0A\/30\/wKhwI07-nRnyRIUcAABxq5cyi00177_360-200c_6-0.jpg","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang7\/90032061x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","area":"111\u33a1","house_type":"\u5199\u5b57\u697c\u914d\u5957","address":"\u6682\u65e0\u4fe1\u606f","house_name":"\u7fcc\u666f\u5bb6\u56ed","quyu":"\u5317\u4eac-\u671d\u9633-\u6f58\u5bb6\u56ed","phone":"15172456789\u3010\u8d75\u6625\u71d5|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang8Info()
	{
		$json_str ='{"id":"2003822","user_id":"28500003","username":"nibudong28","password":"","city":"0","district_id":"0","street_id":"13","district_name":"\u6d77\u6dc0","street_name":"\u897f\u4e09\u65d7","title":"\u6025\u79df,\u514d\u4e2d\u4ecb\u8d39,\u4e0a\u596574\u5e73","description":"\u6025\u79df,\u514d\u4e2d\u4ecb\u8d39,\u4e0a\u596574\u5e73","ip":"3054634076","thumb_img":"housing\/20100719\/0939\/1279503570-3634s.jpg","image_count":"8","post_at":"1279503585","refresh_at":"1279503720","show_time":"1279503585","price":"3000.0","person":"\u4edd\u88d5\u6770","phone":"15101131857","major_category":"8","deal_type":"1","agent":"1","listing_status":"0","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"","post_type":"0","cookie_id":"8d350bc8-864d-11df-8123-0024e86a8b8f","address":"\u897f\u4e09\u65d7\u5927\u6865","latlng":"","price_type":"2","area":"74","house_type":"0","shopping":"29","house_name":"\u4e0a\u5965\u4e16\u7eaa\u4e2d\u5fc3","source_type":"0","source_desc":"","puid":"4980446","loupan_id":"0","loupan_name":"","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"4980446","title":"\u6025\u79df,\u514d\u4e2d\u4ecb\u8d39,\u4e0a\u596574\u5e73","price":"1.35\u5143\/\u33a1\u00b7\u5929\uff08\u5408\u8ba13000.0\u5143\/\u6708\uff09","thumb_img":"http:\/\/image.ganjistatic1.com\/housing\/20100719\/0939\/1279503570-3634s.jpg","big_img":"http:\/\/image.ganjistatic1.com\/housing\/20100719\/0939\/1279503570-3634s.jpg","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang8\/4980446x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","area":"74\u33a1","house_type":"\u5199\u5b57\u697c","address":"\u897f\u4e09\u65d7\u5927\u6865","house_name":"\u4e0a\u5965\u4e16\u7eaa\u4e2d\u5fc3","quyu":"\u5317\u4eac-\u6d77\u6dc0-\u897f\u4e09\u65d7","phone":"15101131857\u3010\u4edd\u88d5\u6770|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang9Info()
	{
		$json_str ='{"id":"1177594","user_id":"28359457","username":"xu_yangyang","password":"","city":"0","district_id":"1","street_id":"1","district_name":"\u671d\u9633","street_name":"\u4e09\u5143\u6865","title":"\u65f6\u95f4\u56fd\u9645\u5199\u5b57\u697c135\u5e73\u65b9\u7c73\u4e1a\u4e3b\u6025\u552e","description":"\u65f6\u95f4\u56fd\u9645\u5927\u53a6 \u5730\u5904\u4e09\u5143\u6865\u4e1c\u5317\u89d2 135\u5e73\u65b9\u7c73 \u4e1a\u4e3b\u6025\u552e","ip":"2093968366","thumb_img":"","image_count":"0","post_at":"1279005599","refresh_at":"1279005686","show_time":"1279005599","price":"331.0","person":"\u5218\u5efa\u68ee","phone":"15210116209","major_category":"9","deal_type":"1","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u76f8\u4f3c\u8d34|","post_type":"0","cookie_id":"7fd8dc3c-896a-11df-a77b-a4badb2a7b83","address":"\u4e09\u5143\u6865","latlng":"","area":"135","house_type":"0","shopping":"32","house_name":"\u65f6\u95f4\u56fd\u9645","source_type":"0","source_desc":"","puid":"1050398","loupan_id":"0","loupan_name":"","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"1050398","title":"\u65f6\u95f4\u56fd\u9645\u5199\u5b57\u697c135\u5e73\u65b9\u7c73\u4e1a\u4e3b\u6025\u552e","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang9\/1050398x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","price":"331.0\u4e07\u5143","area":"135\u33a1","house_type":"\u5199\u5b57\u697c","house_name":"\u65f6\u95f4\u56fd\u9645","quyu":"\u5317\u4eac-\u671d\u9633-\u4e09\u5143\u6865","address":"\u4e09\u5143\u6865","phone":"15210116209\u3010\u5218\u5efa\u68ee|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang10Info()
	{
		$json_str ='{"id":"983257","user_id":"31667906","username":"yxdlw","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"0","street_name":"\u4e2d\u5173\u6751","title":"\u6d77\u6dc0-\u4e2d\u5173\u6751-55885","description":"\uff08\u8be5\u4fe1\u606f\u7531\u7528\u6237\u53d1\u81ea\u624b\u673a\uff09","ip":"0","thumb_img":"","image_count":"0","post_at":"1295059176","refresh_at":"1326857676","show_time":"0","price":"55885","person":"\u5a1c\u624b\u673a","phone":"15810413118","major_category":"10","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u76f8\u4f3c\u5e16|","post_type":"0","cookie_id":"8DF3BE229EE12568DD94AE23F3853CFC","address":"\u4e00\u4e2a\u65b9\u6cd5\u56de\u5bb6\u91cc\u8fb9\u5728\u8fd9","latlng":"","area":"0.0","rent_type":"0","rent_date":"0","fang_xing":"0","source_type":"0","source_desc":"","puid":"1305105","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"1305105","title":"\u6d77\u6dc0-\u4e2d\u5173\u6751-55885","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang10\/1305105x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","price":"55885 \u5143\/\u5929","house_type":"\u5bb6\u5ead\u65c5\u9986-\u51fa\u79df","quyu":"\u5317\u4eac-\u6d77\u6dc0-\u4e2d\u5173\u6751","address":"\u4e00\u4e2a\u65b9\u6cd5\u56de\u5bb6\u91cc\u8fb9\u5728\u8fd9","phone":"15810413118\u3010\u5a1c\u624b\u673a|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang11Info()
	{
		$json_str ='{"id":"1111253","user_id":"50012441","username":"zzk2129","password":"","city":"0","district_id":"10","street_id":"5","district_name":"\u901a\u5dde","street_name":"\u68a8\u56ed","title":"\u4ed3\u5e93\u5927\u849cchao\u849c\u56e2","description":"\u849c\u7092\u849c\u56e2\u4e13\u7528\u4ed3\u5e93\u7ffb\u4e00\u756a","ip":"3232247225","thumb_img":"","image_count":"0","post_at":"1346134045","refresh_at":"1346134074","show_time":"1346134045","price":"2000.00","person":"erere","phone":"13699440822","major_category":"11","deal_type":"1","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"1","show_before_audit":"0","show_before_audit_reason":"IP\u88ab\u5c4f\u853d:{113845}192.168.45.185|\u5f02\u5730\u7535\u8bdd|\u5f02\u5730ip|","post_type":"0","cookie_id":"1428806345640346642972","address":"\u5927\u849c\u5927\u849c","latlng":"","price_type":"1","area":"1000","house_type":"1","source_type":"0","source_desc":"","puid":"90062190","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"90062190","title":"\u4ed3\u5e93\u5927\u849cchao\u849c\u56e2","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang11\/90062190x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","price":"2000.00 \u5143\/\u33a1\u00b7\u5929","area":"1000\u33a1","house_type":"\u4ed3\u5e93-\u51fa\u79df","quyu":"\u5317\u4eac-\u901a\u5dde-\u68a8\u56ed","address":"\u5927\u849c\u5927\u849c","phone":"13699440822\u3010erere|\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	public function testformatNewsFang12Info()
	{
		$json_str ='{"id":"6315471","user_id":"50005828","username":"cy1989","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"14","street_name":"\u6e05\u6cb3","title":"\u4f60\u963f\u65af\u8fbe \u7231\u4e0a \u67aa","description":"2\u963f\u65af\u8fbe\u82cf\u6253\u82cf\u6253\u82cf\u6253\u963f\u65af\u8fbe\u7231\u4e0a\u7231\u4e0a \u662f\u963f\u65af\u8fbe","ip":"3232244618","thumb_img":"","image_count":"0","post_at":"1331888587","refresh_at":"1373343101","show_time":"1331888587","price":"20000","person":"\u7231\u4e0a\u7684","phone":"13488443914","major_category":"12","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u6807\u9898\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa|\u5f02\u5730\u7535\u8bdd|","post_type":"0","cookie_id":"3544480317006694694835","xiaoqu_id":"0","xiaoqu":"\u963f\u65af\u8fbe\u963f\u65af\u8fbe","xiaoqu_address":"\u963f\u8fbe\u963f\u65af\u8fbe\u554a","pinyin":"","fang_xing":"5","latlng":"","area":"2.00","ceng":"2","ceng_total":"2","chaoxiang":"9","niandai":"","zhuangxiu":"1","huxing_shi":"2","huxing_ting":"2","huxing_wei":"2","subway":"","subway_line":"","college":"","bus_station":"","bus_line":"","user_code":"","source_type":"0","source_desc":"","puid":"90037747","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$equals_str ='{"puid":"90037747","title":"\u4f60\u963f\u65af\u8fbe \u7231\u4e0a \u67aa","thumb_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","big_img":"http:\/\/sta.ganjistatic1.com\/src\/image\/mobile\/touch\/weixin\/default_ganji_logo.png","url":"http:\/\/fangweixin.3g.ganji.com\/bj_fang12\/90037747x?ifid=gjwx_gj_sc&ca_n=scan&ca_s=other_weixin&_gjassid=fV0x40R2Gk9V","price":"2 \u4e07\u5143(10000.0\u5143\/\u33a1)","huxing":"2\u5ba42\u53852\u536b - 2.00\u33a1","gaikuang":"\u8c6a\u534e\u88c5\u4fee - \u897f\u5357 - \u5546\u4f4f\u697c","lou_ceng":"\u7b2c2\u5c42\/\u51712\u5c42","xiaoqu":"\u963f\u65af\u8fbe\u963f\u65af\u8fbe","address":"\u963f\u8fbe\u963f\u65af\u8fbe\u554a","phone":"13488443914\u3010\u7231\u4e0a\u7684 |\u7ecf\u7eaa\u4eba\u3011"}';
		$equals_arr = json_decode($equals_str,true);			
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$this->assertEquals($equals_arr, $this->obj->formatNewsFangMajorCategoryInfo($postInfo));
	}
	
	//-------------------------title -----------------------------------------------
	
	public  function testformatSaveDataFang1Title()
	{
		$postInfo = array(
			'house_id' => '1404877818',
			'puid' => '98425063',
			'city' => '0',
			'district_id' => '5',
			'district_name' => '宣武',
			'street_id' => '12',
			'street_name' => '陶然亭',
			'title' => '东贸国际花园东贸国际花园东贸国际花园',
			'description' => '',
			'thumb_img' => '',
			'image_count' => '0',
			'type' => '1',
			'priority' => '18',
			'price' => '5000',
			'person' => 'bjfang',
			'phone' => '15236363630',
			'xiaoqu' => '一瓶兰亭',
			'xiaoqu_id' => '100951',
			'xiaoqu_address' => '陶然亭路2号',
			'pinyin' => 'yipinglanting',
			'fang_xing' => '3',
			'area' => '50.00',
			'ceng' => '12',
			'ceng_total' => '21',
			'chaoxiang' => '5',
			'zhuangxiu' => '3',
			'pay_type' => '押一付三',
			'huxing_shi' => '1',
			'huxing_ting' => '1',
			'huxing_wei' => '1',
			'peizhi' => 'chuang',
			'major_category' => '1',
			'domain' => 'bj',
			'cityName' => '北京',
		);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='陶然亭|1室|5000元/月';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang3Title()
	{
		$json_str ='{"house_id":"933265","puid":"97964956","account_id":"1000275","city":"100","district_id":"0","district_name":"\u95f5\u884c","street_id":"10","street_name":"\u5434\u6cfe","title":"\u3010\u95f5\u884c\u30115\u53f7\u7ebf \u5251\u5ddd\u8def \u5434\u6cfe \u5609\u6021\u6c34\u5cb8 \u5b9e\u4f53\u4e3b\u5367","description":"","ip":"3232236252","thumb_img":"","image_count":"0","type":"3","premier_status":"0","bid_status":"0","listing_status":"1","is_similar":"0","post_at":"1415954149","refresh_at":"1415954149","modified_at":"1423561188","rand_refresh_at":"0","priority":"24","price":"1310","person":"\u7cbe\u54c1\u516c\u5bd3","phone":"13562489742","xiaoqu_id":"219078","xiaoqu":"\u5609\u6021\u6c34\u5cb8","xiaoqu_address":"\u9f99\u5434\u8def 5899\u53f7\n","pinyin":"jiayishuian","fang_xing":"4","latlng":"b121.46721992426,31.048526576534","area":"0.00","ceng":"1","ceng_total":"12","chaoxiang":"2","zhuangxiu":"2","pay_type":"","peizhi":"","subway":"","college":"","bus_station":"","share_mode":"1","house_type":"1","rent_sex_request":"0","tag_type":"32","tag_create_at":"0","tab_system":"","tab_personality":"\u72ec\u7acb\u98d8\u7a97","ad_types":"2097152","ad_status":"2097152","user_id":"500009378","cookie_id":"","major_category":"3","domain":"sh","cityName":"\u4e0a\u6d77"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='吴泾|主卧|1310元/月';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang5Title()
	{
		$json_str ='{"house_id":"69172218","puid":"98238316","account_id":"1003192","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"70","street_name":"\u897f\u4e8c\u65d7","title":"\u6d77\u6dc0\u897f\u4e8c\u65d7\u94ed\u79d1\u82d1\u597d\u623f","description":"","ip":"3232247043","thumb_img":"gjfstmp2\/M00\/00\/02\/wKgCzFU0km2IZDuKAAG,xoWbm5YAAAA9wHfGk8AAb,e32_120-100_9-0.jpeg","image_count":"4","type":"5","premier_status":"100","bid_status":"0","listing_status":"1","is_similar":"0","post_at":"1429508739","refresh_at":"1429508739","modified_at":"1429508739","rand_refresh_at":"0","priority":"4010","price":"2100000","minprice_guide":"0","maxprice_guide":"0","price_bought":"0","downpayments_require":"0","downpayments_calculate":"0","person":"\u623f\u4e94\u6d4b\u8bd5","phone":"13609874532","xiaoqu":"\u94ed\u79d1\u82d1","xiaoqu_id":"864","xiaoqu_address":"\u5b89\u5b81\u5e84\u897f\u8def29\u53f7","pinyin":"mingkeyuan","fang_xing":"3","house_property":"1","fiveyears":"1","only_house":"1","land_tenure":"1","bid_structure":"1","elevator":null,"latlng":"b116.32078089176,40.05826311691","area":"80.00","area_inside":"75.00","ceng":"5","ceng_total":"6","chaoxiang":"6","niandai":"1999","zhuangxiu":"4","huxing_shi":"2","huxing_ting":"1","huxing_wei":"1","subway":"","bus_station":"","tag_type":"0","tag_create_at":"0","tab_system":"15","tab_personality":"","loan_require":"0","monthly_payments":null,"ad_types":"4194304","ad_status":"4194304","user_id":"1500359327","cookie_id":"1537228189529584836054-849533791","major_category":"5","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='西二旗|2室|210万元';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	 public  function testformatSaveDataFang2Title()
	{
		$json_str ='{"id":"1204531","user_id":"1000136710","username":"limingfeng","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"11","street_name":"\u4e94\u9053\u53e3","title":"\u6211\u60f3\u6709\u4e00\u4e2a\u5bb6","description":"\u66f4\u597d\u66f4\u5927\u66f4\u5f3a\u3002\u4e00\u5b9a\u8981\u52aa\u529b\u54e6\uff01","ip":"3232249551","thumb_img":"","image_count":null,"post_at":"1419234762","refresh_at":"1419234871","show_time":"1419234761","price":"4","person":"mr\u674e","phone":"18210138336","major_category":"2","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"1","show_before_audit":"0","show_before_audit_reason":"\u6ce8\u518c\u5bc6\u7801\u4e3a\u7a7a|\u514d\u5ba1\u7c7b\u522b|\u6ce8\u518cIP\u4e3a\u7a7a|","post_type":"0","cookie_id":"2957354285652226515381-266295231","xiaoqu_id":"0","xiaoqu":"\u7d2b\u7981\u57ce","xiaoqu_address":"\u60fa\u60fa\u60dc\u60fa\u60fa","latlng":"","area":"0.0","pinyin":"","fang_xing":"0","huxing_shi":"3","huxing_ting":"2","huxing_wei":"2","peizhi":"","jichu":"","source_type":"0","source_desc":"55883","puid":"97999663","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='五道口|1500-2000元/月';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang4Title()
	{
		$json_str ='{"id":"783377","user_id":"10204684","username":"lajidx","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"0","street_name":"\u4e2d\u5173\u6751","title":"\u60f3\u4e70\u4e2a\u623f\u5b50\u4e3a\u5b69\u5b50\u4e0a\u5b66","description":"\u80fd\u7ed9\u5b69\u5b50\u4e0a\u5b66\u7684\u623f\u5b50\u3002\u81ea\u5df1\u4e5f\u5f97\u4f4f\u3002","ip":"2071229723","thumb_img":"","image_count":"0","post_at":"1279349233","refresh_at":"1326857400","show_time":"1279349233","price":"3","person":"\u5218\u4e70\u5356","phone":"13520776348","major_category":"4","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"","post_type":"0","cookie_id":"cc9718e8-916e-11df-b9be-0024e86a8b8f","xiaoqu_id":"0","xiaoqu":"","xiaoqu_address":"","latlng":"","area":"60.0","pinyin":"","fang_xing":"3","huxing_shi":"2","huxing_ting":"1","huxing_wei":"1","source_type":"0","source_desc":"","puid":"922975","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='中关村|80-100万/月';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang6Title()
	{
		$json_str ='{"id":"1672535","user_id":"50020564","username":"sweep","password":"","city":"0","district_id":"0","street_id":"3","district_name":"\u6d77\u6dc0","street_name":"\u4e0a\u5730","title":"\u5546\u94fa\u6d4b\u8bd5\u6d4b\u8bd5\u9760\u5927\u5bb6\u5206\u5f00","description":"\u5206\u9760\u5927\u5bb6\u5f00\u98de\u673a\u5feb\u70b9\u98de\u673a\u9760\u5927\u5bb6\u5206\u5f00\u7684\u4e86\u65af\u67ef\u8fbe\u5c06\u9644\u8fd1\u7684\u5f00\u98de\u673a\u75af\u72c2\u5927\u5bb6\u5206\u5f00\u7684","ip":"3232247200","thumb_img":"","image_count":"0","post_at":"1351480503","refresh_at":"1351491233","show_time":"1351480503","price":"1000.0","person":"\u5f20\u5148\u751f","phone":"15810443144","major_category":"6","deal_type":"1","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u5f02\u5730ip|","post_type":"0","cookie_id":"8854796621806775777536-758851971","address":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00\u7684\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u5730\u5740\u3002","latlng":"b116.40540253696,39.915686699931","price_type":"1","area":"100","house_type":"4","shopping":"30","trade":"1","source_type":"0","source_desc":"","puid":"90356463","loupan_id":"9116","loupan_name":"\u5317\u4eac\u65b0\u697c\u76d8\u5f20\u5b87\u535a\u4e00","top_info":"","ad_types":"0","ad_status":"0","store_rent_type":"0","store_stat":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='上地|100㎡|3000000 元/月';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang7Title()
	{
		$json_str ='{"id":"822922","user_id":"50004326","username":"jl1986","password":"","city":"0","district_id":"1","street_id":"11","district_name":"\u671d\u9633","street_name":"\u6f58\u5bb6\u56ed","title":"\u5546\u94fa\u6c42\u8d2d\uff0c\u67aa<\/span>lt;\/spangt;\u67aa<\/span>lt;\/spangt;","description":"\u5546\u94fa\u6c42\u8d2d\uff0c\u67aa<\/span><\/span>\u67aa<\/span><\/span>\u5546\u94fa\u6c42\u8d2d\u4f60\u597d\u4f60\u597d\u4f60\u597d\u4f60\u597d\u4f60\u8bf4\u64e6\u5f00\u6d3b\u52a8\u5f00\u597d\u623fnsihwsgfuiwghiwghquidbh","ip":"1920015644","thumb_img":"gjfstmp1\/M00\/0A\/30\/wKhwI07-nRnyRIUcAABxq5cyi00177_90-75c_6-0.jpg","image_count":"1","post_at":"1325309221","refresh_at":"1326857490","show_time":"1325309221","price":"111.0","person":"\u8d75\u6625\u71d5","phone":"15172456789","major_category":"7","deal_type":"1","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"2","show_before_audit":"1","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u6807\u9898\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa,\u5185\u5bb9\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa|\u5f02\u5730\u7535\u8bdd|","post_type":"0","cookie_id":"7724313722519323619962","address":"","latlng":"0,0","area":"111","house_type":"7","shopping":"30","trade":"1","source_type":"0","source_desc":"","puid":"90032061","loupan_id":"3390","loupan_name":"\u7fcc\u666f\u5bb6\u56ed","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='潘家园|111㎡|111万元';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang8Title()
	{
		$json_str ='{"id":"2003822","user_id":"28500003","username":"nibudong28","password":"","city":"0","district_id":"0","street_id":"13","district_name":"\u6d77\u6dc0","street_name":"\u897f\u4e09\u65d7","title":"\u6025\u79df,\u514d\u4e2d\u4ecb\u8d39,\u4e0a\u596574\u5e73","description":"\u6025\u79df,\u514d\u4e2d\u4ecb\u8d39,\u4e0a\u596574\u5e73","ip":"3054634076","thumb_img":"housing\/20100719\/0939\/1279503570-3634s.jpg","image_count":"8","post_at":"1279503585","refresh_at":"1279503720","show_time":"1279503585","price":"3000.0","person":"\u4edd\u88d5\u6770","phone":"15101131857","major_category":"8","deal_type":"1","agent":"1","listing_status":"0","display_status":"3","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"","post_type":"0","cookie_id":"8d350bc8-864d-11df-8123-0024e86a8b8f","address":"\u897f\u4e09\u65d7\u5927\u6865","latlng":"","price_type":"2","area":"74","house_type":"0","shopping":"29","house_name":"\u4e0a\u5965\u4e16\u7eaa\u4e2d\u5fc3","source_type":"0","source_desc":"","puid":"4980446","loupan_id":"0","loupan_name":"","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		//var_dump($this->obj->formatSaveData($postInfo,$postInfo['major_category']));
		//exit;
		$equals_str ='西三旗|74㎡|1.4元/㎡·天';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang9Title()
	{
		$json_str ='{"id":"1177594","user_id":"28359457","username":"xu_yangyang","password":"","city":"0","district_id":"1","street_id":"1","district_name":"\u671d\u9633","street_name":"\u4e09\u5143\u6865","title":"\u65f6\u95f4\u56fd\u9645\u5199\u5b57\u697c135\u5e73\u65b9\u7c73\u4e1a\u4e3b\u6025\u552e","description":"\u65f6\u95f4\u56fd\u9645\u5927\u53a6 \u5730\u5904\u4e09\u5143\u6865\u4e1c\u5317\u89d2 135\u5e73\u65b9\u7c73 \u4e1a\u4e3b\u6025\u552e","ip":"2093968366","thumb_img":"","image_count":"0","post_at":"1279005599","refresh_at":"1279005686","show_time":"1279005599","price":"331.0","person":"\u5218\u5efa\u68ee","phone":"15210116209","major_category":"9","deal_type":"1","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u76f8\u4f3c\u8d34|","post_type":"0","cookie_id":"7fd8dc3c-896a-11df-a77b-a4badb2a7b83","address":"\u4e09\u5143\u6865","latlng":"","area":"135","house_type":"0","shopping":"32","house_name":"\u65f6\u95f4\u56fd\u9645","source_type":"0","source_desc":"","puid":"1050398","loupan_id":"0","loupan_name":"","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		//var_dump($this->obj->formatSaveData($postInfo,$postInfo['major_category']));
		//exit;
		$equals_str ='三元桥|135㎡|331 万元';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang10Title()
	{
		$json_str ='{"id":"983257","user_id":"31667906","username":"yxdlw","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"0","street_name":"\u4e2d\u5173\u6751","title":"\u6d77\u6dc0-\u4e2d\u5173\u6751-55885","description":"\uff08\u8be5\u4fe1\u606f\u7531\u7528\u6237\u53d1\u81ea\u624b\u673a\uff09","ip":"0","thumb_img":"","image_count":"0","post_at":"1295059176","refresh_at":"1326857676","show_time":"0","price":"55885","person":"\u5a1c\u624b\u673a","phone":"15810413118","major_category":"10","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u76f8\u4f3c\u5e16|","post_type":"0","cookie_id":"8DF3BE229EE12568DD94AE23F3853CFC","address":"\u4e00\u4e2a\u65b9\u6cd5\u56de\u5bb6\u91cc\u8fb9\u5728\u8fd9","latlng":"","area":"0.0","rent_type":"0","rent_date":"0","fang_xing":"0","source_type":"0","source_desc":"","puid":"1305105","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='中关村|家庭旅馆|55885 元/天';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	public  function testformatSaveDataFang11Title()
	{
		$json_str ='{"id":"1111253","user_id":"50012441","username":"zzk2129","password":"","city":"0","district_id":"10","street_id":"5","district_name":"\u901a\u5dde","street_name":"\u68a8\u56ed","title":"\u4ed3\u5e93\u5927\u849cchao\u849c\u56e2","description":"\u849c\u7092\u849c\u56e2\u4e13\u7528\u4ed3\u5e93\u7ffb\u4e00\u756a","ip":"3232247225","thumb_img":"","image_count":"0","post_at":"1346134045","refresh_at":"1346134074","show_time":"1346134045","price":"2000.00","person":"erere","phone":"13699440822","major_category":"11","deal_type":"1","agent":"1","listing_status":"5","display_status":"3","editor_audit_status":"1","show_before_audit":"0","show_before_audit_reason":"IP\u88ab\u5c4f\u853d:{113845}192.168.45.185|\u5f02\u5730\u7535\u8bdd|\u5f02\u5730ip|","post_type":"0","cookie_id":"1428806345640346642972","address":"\u5927\u849c\u5927\u849c","latlng":"","price_type":"1","area":"1000","house_type":"1","source_type":"0","source_desc":"","puid":"90062190","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='梨园|仓库|2000.00 元/㎡·天';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	} 
	public  function testformatSaveDataFang12Title()
	{
		$json_str ='{"id":"6315471","user_id":"50005828","username":"cy1989","password":"","city":"0","district_id":"0","district_name":"\u6d77\u6dc0","street_id":"14","street_name":"\u6e05\u6cb3","title":"\u4f60\u963f\u65af\u8fbe \u7231\u4e0a \u67aa","description":"2\u963f\u65af\u8fbe\u82cf\u6253\u82cf\u6253\u82cf\u6253\u963f\u65af\u8fbe\u7231\u4e0a\u7231\u4e0a \u662f\u963f\u65af\u8fbe","ip":"3232244618","thumb_img":"","image_count":"0","post_at":"1331888587","refresh_at":"1373343101","show_time":"1331888587","price":"20000","person":"\u7231\u4e0a\u7684","phone":"13488443914","major_category":"12","agent":"1","listing_status":"4","display_status":"1","editor_audit_status":"0","show_before_audit":"0","show_before_audit_reason":"\u53d1\u5e16\u8d85\u8fc7\u4e0a\u9650|\u6807\u9898\u542b\u6709\u8fc7\u6ee4\u8bcd \u67aa|\u5f02\u5730\u7535\u8bdd|","post_type":"0","cookie_id":"3544480317006694694835","xiaoqu_id":"0","xiaoqu":"\u963f\u65af\u8fbe\u963f\u65af\u8fbe","xiaoqu_address":"\u963f\u8fbe\u963f\u65af\u8fbe\u554a","pinyin":"","fang_xing":"5","latlng":"","area":"2.00","ceng":"2","ceng_total":"2","chaoxiang":"9","niandai":"","zhuangxiu":"1","huxing_shi":"2","huxing_ting":"2","huxing_wei":"2","subway":"","subway_line":"","college":"","bus_station":"","bus_line":"","user_code":"","source_type":"0","source_desc":"","puid":"90037747","top_info":"","ad_types":"0","ad_status":"0","domain":"bj","cityName":"\u5317\u4eac"}';
		$postInfo =  json_decode($json_str,true);
		$this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
		$equals_str ='清河|2室|2 万元';
		$this->assertEquals($equals_str, $this->obj->formatSaveData($postInfo,$postInfo['major_category']));
	}
	
	protected static $default_img = 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png';
	protected $postNotFromDb = array(
        'puid' => 855239,
        'city' => 0,
        'district_name' => '宣武',
        'street_name' => '菜市口',
        'thumb_img' => 'gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_90-75c_6-0.jpg',
        'price' => '900',
        'person' => '小伟',
        'phone' => '13829760518',
        'major_category' => 1,
        'agent' => 1,
        'title' => '出租菜市口中信城,高档小区,新房,南北通透户型,地铁边,精装',
        'xiaoqu' => '中信城',
        'xiaoqu_address' => '宣武区菜市口大街',
        'area' => '96.0',
        'huxing_shi' => 2,
        'huxing_ting' => 2,
        'huxing_wei' => 1,
        'auth_status' => '0',
    );
	public function testgetImageUrlBySize()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
        $expect = 'http://image.ganjistatic1.com/gjfstmp1/M00/03/55/wKhwI09F0emr2ZNvAACsGPc5jPU556_300-200f_5-0.jpg';
        $expect2 = self::$default_img;
        $expect2 = self::$default_img;

        $ret = $this->obj->getImageUrlBySize($this->postNotFromDb['thumb_img'], 300, 200, 'f', 5, 0);
        $ret2 = $this->obj->getImageUrlBySize(self::$default_img, 30, 20, 'c', 6, 0);
        $this->assertEquals($expect, $ret);
        $this->assertEquals($expect2, $ret2);
    }
	 public function testgetWapAccessUrl()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
        $expect = 'http://fangweixin.3g.ganji.com/bj_fang1/855239x';
        $ret = $this->obj->getWapAccessUrl('bj', 1, 855239);
        $this->assertEquals($expect, $ret);
    }
	public function testisPersonPercent()
    {
        $this->obj = Gj_LayerProxy::getProxy('Service_Data_Weixin_WeixinCollectionFormatInfoMock');
        $ret1 = $this->obj->isPersonPercent($this->postNotFromDb['auth_status'], 'sh');
        $ret2 = $this->obj->isPersonPercent(3, 'bj');
        $this->assertEquals(false, $ret1);
        $this->assertEquals(true, $ret2);
    }
}
