<?php
/*
 * File Name:GroupOutletDownloadData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
set_time_limit(3600);
class Service_Data_HouseReport_GroupOutletAvgDownloadData extends Service_Data_HouseReport_GroupOutletData
{
    protected $productData = array(
        Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME => array(
            'title'=>'时间',
            'title_data'=>array(
                'report_start_time'=>'统计开始时间',
                'report_end_time'=>'统计结束时间',
            ),
        ),
        Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT => array(
            'title'=>'员工信息',
            'title_data' => array(
                'account_id'=>'编号', #登录账号
                'account_name'=>'姓名', 
            	'account_email'=>'邮箱',
                'outlet_name'=>'门店',
                'plate_name'=>'板块', 
                'area_name'=>'区域', 
                'phone'=>'手机',
                'owner_type'=>'账号类型',
                'status'=>'端口状态',
                'login_count'=>'登录次数',
                'last_login_time'=>'最后登录时间',
                'login_count'=>'日均登录次数',
            ),
        ),
        Service_Data_HouseReport_GroupData::PREMIER => array(
            'title'=>'精品',
            'title_data'=>array(
                'last_deposit_time'=>'开户或续费时间',
                'premier_end_time'=>'精品到期时间',
                'biz_text'=>'套餐类型',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增',
                'mult_img_house_count'=>'优质房源',
                'premier_count'=>'推广',
               // 'premier_scale'=>'推广率',
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',
                'account_pv'=>'点击量',
            ),
        ),    
        Service_Data_HouseReport_GroupData::ASSURE => array(
            'title'=>'放心房',
            'title_data'=>array(
                'last_deposit_time'=>'开户或续费时间',
                'premier_end_time'=>'放心房到期时间',
                'biz_text'=>'放心房类型',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增',
                'mult_img_house_count'=>'优质房源',
                'premier_count'=>'展示',
               // 'premier_scale'=>'展示率',
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',
                'account_pv'=>'点击量',
            ),
        ),
       Service_Data_HouseReport_GroupData::BID => array(
            'title'=>'竞价',
            'title_data'=>array(
                'bid_house_count'=>'房源数',
		        'bid_account_pv'=>'点击量',
		        'bid_amount_count'=>'消费',
            ),
            'group'=>1  #组别
        ),
        Service_Data_HouseReport_GroupData::STICK => array(
	        'title'=>'置顶',
	        'title_data'=>array(
		        'stick_house_count'=>'房源数',
		        'stick_account_pv'=>'点击量',
		        'stick_amount_count'=>'消费',
	        ),
	        'group'=>1  #组别
        ),
        Service_Data_HouseReport_GroupData::DXTG => array(
	        'title'=>'智能推广',
	        'title_data'=>array(
		        'dxtg_house_count'=>'房源数',
		        'dxtg_account_pv'=>'点击量',
		        'dxtg_amount_count'=>'消费',
	        ),
	        'group'=>1  #组别
        ),
        Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA => array(
            'title'=>'审核数据',
            'title_data'=> array(
                'similar_house_count'=>'重复图片房源数',
                'illegal_house_count'=>'违规房源',#`offline_house_count`  house_company_org_report表
                'comment_count'=>'差评房源',#精品表中是没有这个字段   一起展示差评
            ),
        ),
    );

    //以后有公司有特殊字段处理，不建议这么写
    protected function addDeyouField(){
        $deyouField = Gj_LayerProxy::getProxy('Service_Data_HouseReport_Company_GroupDeyouField');
        $this->productData[Service_Data_HouseReport_GroupData::PREMIER] = $deyouField->getCommomPremierField();
        $this->productData[Service_Data_HouseReport_GroupData::ASSURE] = $deyouField->getCommomAssureField();
    }
    /*{{{得到排序字段*/
      /** 
     *@codeCoverageIgnore
     */
    protected function getSortCategory(){
        return array(
            Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME,
            Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT,
            Service_Data_HouseReport_GroupData::PREMIER,
            Service_Data_HouseReport_GroupData::ASSURE,
            Service_Data_HouseReport_GroupData::BID,
            Service_Data_HouseReport_GroupData::STICK,
            Service_Data_HouseReport_GroupData::DXTG,
            Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
        );
    }
    /*}}}*/

    /*{{{groupDownLoadData 合并下载数据*/
    /** 
     *
     * @param unknown $tags 
     * @param unknown $params    
     */
    public function groupDownLoadData($tags,$params){
        if ($params['companyId'] == 5800) {
            $this->addDeyouField();
        }
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $data[Service_Data_HouseReport_GroupData::PREMIER] = $this->getPremierData($params);
        if (is_array($tags) && !empty($tags)){
            foreach ($tags as $item) {
                if (!in_array($item,array(Service_Data_HouseReport_GroupData::TOTAL,
                    Service_Data_HouseReport_GroupData::PREMIER,Service_Data_HouseReport_GroupData::ASSURE,
                    Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
                    Service_Data_HouseReport_GroupData::FRAME_USE_DATA,
                    Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
                    Service_Data_HouseReport_GroupData::BID,
                	Service_Data_HouseReport_GroupData::STICK,
                	Service_Data_HouseReport_GroupData::DXTG,
                ))) {
                    throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_TAGS_ERROR_NUM,"没有相应的tag配置");
                }
                switch ($item) {
                    /* case Service_Data_HouseReport_GroupData::PREMIER:
                        $data[Service_Data_HouseReport_GroupData::PREMIER] = $this->getPremierData($params);
                        break; */
                    case Service_Data_HouseReport_GroupData::ASSURE:
                        $data[Service_Data_HouseReport_GroupData::ASSURE] = $this->getAssureData($params);
                        break;
                    case Service_Data_HouseReport_GroupData::BID:
                        $data[Service_Data_HouseReport_GroupData::BID] = $this->getBidData($params, 9, $this->bidFields);
                        break;
                    case Service_Data_HouseReport_GroupData::STICK:
                        $data[Service_Data_HouseReport_GroupData::STICK] = $this->getBidData($params, 7, $this->stickFields);
                        break;
                    case Service_Data_HouseReport_GroupData::DXTG:
                        $data[Service_Data_HouseReport_GroupData::DXTG] = $this->getBidData($params, 8, $this->dxtgFields);
                        break;
                    case Service_Data_HouseReport_GroupData::FRAME_ORG_DATA:
                        $data[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA] = $this->getFrameData($params,$data);
                        break;
                    case Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA:
                        $data[Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA] = $this->getFrameAuditingData($params,$data);
                        break;        
                }
            }
            $data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME] = $this->getReportDate($params,$data);
            //添加账号信息
            $accountInfoList = $this->getAccountData($params,$params['accountIds'],$data);
            //添加经纪人邮箱
            $accountEmailList = $this->getCrmAccountEmail($params,$data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
            $data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT] = array_merge_recursive($accountInfoList,$accountEmailList);
            if (!in_array(Service_Data_HouseReport_GroupData::PREMIER, $tags)) {
            	unset($data[Service_Data_HouseReport_GroupData::PREMIER]);
            }
            $getSortCategory = $this->getSortCategory();
            $data = $this->formartData($data);
            $res = $groupdataService->mergeData($data,$getSortCategory,$this->productData,$this->indexFieldName);
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_TAGS_ERROR_NUM,"没有响应的tag配置");
        }

        return $res;
    }
    /*}}}*/
}
