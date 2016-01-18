<?php
/*
 * File Name:GroupOrgDownloadData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
class Service_Data_HouseReport_GroupOrgDownloadData extends Service_Data_HouseReport_GroupOrgData
{
  public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
    }


    protected $productData = array(
        Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME => array(
            'title'=>'时间',
            'title_data'=>array(
                'report_start_time'=>'统计开始时间',
                'report_end_time'=>'统计结束时间',
            ),
        ),
        Service_Data_HouseReport_GroupData::FRAME_ORG_DATA => array(
            'title'=>'组织数据',
            'title_data' => array(
                'area_name'=>'区域名称',
                'plate_count'=>'板块数',
                'customer_count'=>'门店数',
                'account_count'=>'使用人数', 
            ),
        ),
        Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT => array(
            'title'=>'账号信息',
            'title_data' => array(
                'login_name'=>'登录账号', #登录账号
                'business_scope'=>'房源类别',
                'login_count'=>'登录次数',
				'account_count'   => '经纪人数'
            //    'newly_login_time'=>'最近登录时间',
            ),
        ),
         Service_Data_HouseReport_GroupData::PREMIER => array(
            'title'=>'精品',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                'refresh_count'=>'刷新','account_pv'=>'点击量',
            ),
           
        ),    
        Service_Data_HouseReport_GroupData::ASSURE => array(
            'title'=>'放心房',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                'refresh_count'=>'刷新','account_pv'=>'点击量',
            ),
            
        ),
    	Service_Data_HouseReport_GroupData::BID => array(
    		'title'=>'竞价',
    		'title_data'=>array(
	    		'bid_house_count'=>'房源数',
	    		'bid_account_pv'=>'点击量',
	    		'bid_click_price'=>'消费',
    		),
    		'group'=>1  #组别
    		),
    	Service_Data_HouseReport_GroupData::STICK => array(
    		'title'=>'置顶',
    		'title_data'=>array(
	    		'stick_house_count'=>'房源数',
	    		'stick_account_pv'=>'点击量',
	    		'stick_click_price'=>'消费',
    		),
    		'group'=>1  #组别
    		),
    	Service_Data_HouseReport_GroupData::DXTG => array(
    		'title'=>'智能推广',
    		'title_data'=>array(
	    		'dxtg_house_count'=>'房源数',
	    		'dxtg_account_pv'=>'点击量',
	    		'dxtg_click_price'=>'消费',
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
            'group'=>2  #组别
        ),
    );

   
     /*{{{得到排序字段*/
      /** 
     *@codeCoverageIgnore
     */
    protected function getSortCategory(){
        return array(
            Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME,
            Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
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
    /*{{{getReportDate 得到统计时间*/
    /** 返回的数据格式
     * return  array(
     *       'data'=>array(
     *           $this->indexFieldName=>array(
     *               $this->indexFieldName=>value
     *               otherKey=>otherValue,
     *               ....
     *           )
     *       )
     *   )
     * 
     */
    protected function getReportDate($params){
        $res = array();
        foreach ($params['orgIds'] as $key => $value) {
            $res['data'][$value][$this->indexFieldName] = $value;
            $res['data'][$value]['report_start_time'] = $params['date']['sDate'];
            $res['data'][$value]['report_end_time'] = $params['date']['eDate'];
        }

        return $res;
    }
    /*}}}*/
    /*{{{getAccountData 得到账号信息*/
    /** 
     * 
     */
    protected function getAccountData($params,$data){
        $res = array();
        if ($data[Service_Data_HouseReport_GroupData::PREMIER]) {
            $newList =  $data[Service_Data_HouseReport_GroupData::PREMIER];
        } else {
            //组织数据存储到 house_company_org_report中
            $newList =  $this->getPremierData($params);
        }

        
        foreach ($params['orgIds'] as $key => $value) {
            $res['data'][$value][$this->indexFieldName] = $value;
            $res['data'][$value]['login_name'] = $params['account'][$value];
            $res['data'][$value]['business_scope'] = $params['houseTypeText'];
            $res['data'][$value]['login_count'] = isset($newList['data'][$value]['login_count'])?$newList['data'][$value]['login_count']:0;
			$res['data'][$value]['account_count'] = isset($newList['data'][$value]['account_count'])?$newList['data'][$value]['account_count']:0;

        }

        return $res;
	}
	protected function changeTitleBylevel($params){
		if(1 == $params['level']){
			$this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['area_name'] = '区域名称';		
		}elseif(2 == $params['level']){
			$this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['area_name'] = '板块名称'; 

		}elseif(3 == $params['level']){
			$this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['area_name'] = '门店名称';
		}elseif(4 == $params['level']){
			$this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['area_name'] = '经纪人名称';	
		}
	}
    /*}}}*/
   
    /*{{{groupDownLoadData 合并下载数据*/
    /** 
     *
     * @param unknown $tags 
     * @param unknown $params    
     */
    public function groupDownLoadData($tags,$params){
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
		$this->changeTitleBylevel($params);
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
                    case Service_Data_HouseReport_GroupData::PREMIER:
                        $data[Service_Data_HouseReport_GroupData::PREMIER] = $this->getPremierData($params);
                        break;
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
            //添加下载时间
            $data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_TIME] = $this->getReportDate($params);
            //添加账号信息
            $data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT] = $this->getAccountData($params,$data);
            $getSortCategory = $this->getSortCategory();
            $res = $groupdataService->mergeData($data,$getSortCategory,$this->productData,$this->indexFieldName);
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_TAGS_ERROR_NUM,"没有响应的tag配置");
        }
        return $res;
    }
    /*}}}*/
}
