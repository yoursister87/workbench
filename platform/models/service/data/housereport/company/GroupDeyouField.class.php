<?php
/*
 * File Name:GroupDeyouField.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 * 得到德佑下载需要的字段
 */
class Service_Data_HouseReport_Company_GroupDeyouField
{
    /**
     *@codeCoverageIgnore
     */
    public function getCommomPremierField(){
        return array(
            'title'=>'精品',
            'title_data'=>array(
                'last_deposit_time'=>'开户或续费时间',
                'premier_end_time'=>'精品到期时间',
                'biz_text'=>'套餐类型',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增',
				'add_premier_count'=>'新增并推广数',#新加字段
				'premier_count'=>'推广',
				'online_housetotal' => '单日在线房源数',
				'online_premier' => '单日最大推广数',
				'mult_img_house_count'=>'优质房源',
				'refresh_count'=>'刷新',
                'max_freerefresh_count'=>'最大刷新数',#新加字段
				'system_tag_count'=>'特色标签使用数',#新加字段
				'account_pv'=>'点击量',
            ),
        );
    }

    /**
     *@codeCoverageIgnore
     */
    public function getCommomAssureField(){
        return array(
            'title'=>'放心房',
            'title_data'=>array(
                'last_deposit_time'=>'开户或续费时间',
                'premier_end_time'=>'精品到期时间',
                'biz_text'=>'放心房类型',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增',
				'add_premier_count'=>'新增并展示房源数',#新加字段
				'premier_count'=>'展示',
				'online_housetotal' => '单日在线房源数',
				'online_premier' => '单日最大展示数',
				'mult_img_house_total_count'=>'优质房源',
				'refresh_count'=>'刷新',
				'max_freerefresh_count'=>'最大免费刷新数',#新加字段
				'max_refresh_count'=>'最大付费刷新数',#新加字段
				'system_tag_count'=>'特色标签使用数',#新加字段
				'account_pv'=>'点击量',
			),
        );
    }
}
