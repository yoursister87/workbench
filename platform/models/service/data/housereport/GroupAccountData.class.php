<?php
/*
 * File Name:GroupAccountData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 */
class Service_Data_HouseReport_GroupAccountData
{
    //需要指定一个唯一的索引字段
    protected $indexFieldName = 'report_date';
    //数据库中的值=>换换为新的键
    protected  $premierFields = array('account_id'=>'account_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv','login_count'=>'login_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count',
        'report_date'=>'report_date');
    protected  $assureFields = array('account_id'=>'account_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv','login_count'=>'login_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count',
        'report_date'=>'report_date'
    );
    /* protected  $bidFields = array('account_id'=>'account_id','account_pv'=>'account_pv','bid_count'=>'bid_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count',
        'report_date'=>'report_date'); */
    protected  $bidFields = array('account_id'=>'account_id','house_count'=>'bid_house_count','amount_count'=>'bid_amount_count',
    		'account_pv'=>'bid_account_pv','report_date'=>'report_date',
    );
    protected  $stickFields = array('account_id'=>'account_id','house_count'=>'stick_house_count','amount_count'=>'stick_amount_count',
    		'account_pv'=>'stick_account_pv','report_date'=>'report_date',
    );
    protected  $dxtgFields = array('account_id'=>'account_id','house_count'=>'dxtg_house_count','amount_count'=>'dxtg_amount_count',
    		'account_pv'=>'dxtg_account_pv','report_date'=>'report_date',
    );
   
    //组织结构数据返回结构
    protected $productData = array(
        Service_Data_HouseReport_GroupData::TOTAL => array(
            'title'=>'汇总数据',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增',
            	'premier_count'=>'推广',
                //'premier_scale'=>'推广率',
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',
                'account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ), 
        Service_Data_HouseReport_GroupData::PREMIER => array(
            'title'=>'精品',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                //'premier_scale'=>'推广率',
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',
                'account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ),    
        Service_Data_HouseReport_GroupData::ASSURE => array(
            'title'=>'放心房',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                //'premier_scale'=>'推广率',
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',
                'account_pv'=>'点击量',
            ),
            'group'=>1  #组别
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
        Service_Data_HouseReport_GroupData::FRAME_ORG_DATA => array(
            'title'=>'组织数据',
            'title_data'=> array(
                'login_count'=>'登录次数'
            ),
            'group'=>2
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
        Service_Data_HouseReport_GroupData::BUSINESS_SCOPE => array(
            'title'=>'端口类别',
            'title_data'=> array(
                'business_scope_str'=>'端口类别'
            ),
            'group'=>3
        ),
    );


    //获得求钧值字段 新增，推广，刷新，点击求均值
    protected $avgFields = array('house_count','premier_count','refresh_count','account_pv','login_count','premier_scale','refresh_scale');
    //求比例  除数=>被除数
    protected $scaleFields = array(
        'premier_scale'=> array('premier_count'=>'tuiguang_max_count'),
        'refresh_scale'=>array('refresh_count'=>'refresh_max_count')
    );

   public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        } 
    }
    //{{{ getAssureData data列表生成数据
    private function getScaleChangefield($field){
        $tmp = array('premier_scale'=>'premier_scale','refresh_scale'=>'refresh_scale','tuiguang_max_count'=>'tuiguang_max_count','refresh_max_count'=>'refresh_max_count');
        return array_merge($field,$tmp);
    }

    protected function formartData($dataList){
        foreach ($dataList as &$data) {
            if (!isset($data['data'])){
                continue;
            }

            $listData = &$data['data'];
            foreach ($listData as &$item) {
                if (isset($item['premier_scale'])) {
                    $item['premier_scale'] = ($item['premier_scale'] * 100).'%';
                }
                if (isset($item['refresh_scale'])) {
                    $item['refresh_scale'] = ($item['refresh_scale'] * 100).'%';
                }
            }
        }

        return $dataList;
    }
    /** 
     * 获取 放心房结果 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getAssureData($params){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_AccountReport');
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        $dataList = $dataService->getAccountAssureReportDetail($params['accountId']);
        $fields = $this->assureFields;

        if ($groupdataService->isShowScale($params)) {
            $fields = $this->getScaleChangefield($fields);
        } else {
            //删除前端显示 刷新率和推广率
            unset($this->productData[Service_Data_HouseReport_GroupData::ASSURE]['title_data']['premier_scale']);
            unset($this->productData[Service_Data_HouseReport_GroupData::ASSURE]['title_data']['refresh_scale']);
        }
        $dataList = $groupdataService->getScale($dataList,$this->scaleFields);
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);

        return $newList;
    }
    /*}}}*/

    //{{{ getPremierData data列表生成数据
    /** 
     * 获取 获取 精品结果 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getPremierData($params){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_AccountReport');
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        $dataService->setVal('countType',array(1));
        $dataList = $dataService->getAccountPremierReportDetail($params['accountId']);
        $fields = $this->premierFields;
        if ($groupdataService->isShowScale($params)) {
            $fields = $this->getScaleChangefield($fields);
        } else {
            //删除前端显示 刷新率和推广率
            unset($this->productData[Service_Data_HouseReport_GroupData::PREMIER]['title_data']['premier_scale']);
            unset($this->productData[Service_Data_HouseReport_GroupData::PREMIER]['title_data']['refresh_scale']);
        }
        $dataList = $groupdataService->getScale($dataList,$this->scaleFields);
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);

        return $newList;
    }
    /*}}}*/
    //{{{ getBidData data 经纪的生成列表
    /** 
     * 获取 获取 竞价结果 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getBidData($params,$countType=0,$fields=array()){
        $dataService = Gj_LayerProxy::getProxy("Service_Data_HouseReport_AccountReport");
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        $dataService->setVal('countType',array($countType));
        $dataList = $dataService->getAccountPremierReportDetail($params['accountId']);
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);
        return $newList;
    }
    /*}}}*/

    /** 
     * 获得组织数据 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getFrameData($params,$data){
        $newList = array();
        $managerSerivce = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
 
        if ($data[Service_Data_HouseReport_GroupData::PREMIER]) {
            $newList =  $data[Service_Data_HouseReport_GroupData::PREMIER];
        } else {
            //组织数据存储到 house_company_org_report中
            $newList =  $this->getPremierData($params);
        }
        return $newList;
    }
    /*}}}*/

   //{{{ getFrameAuditingData data 获得评价列表
    /** 
     * 获得评价列表 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getFrameAuditingData($params,$data){

        $plist = $data[Service_Data_HouseReport_GroupData::PREMIER];
        $alist = $data[Service_Data_HouseReport_GroupData::ASSURE];
        $blist = $data[Service_Data_HouseReport_GroupData::BID];

       if (!isset($plist)){
            //精品数据
            $plist = $this->getPremierData($params); 
        }
        //如果没有选择精品
        if (!is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::PREMIER],$params['product']))){
            unset($plist);
        }

        if (!isset($blist)){
            //竞价数据
            $blist = $this->getBidData($params);
        }

        //如果没有竞价精品
        if (!is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::BID],$params['product']))) {
            unset($blist);
        }

        if (!isset($alist)){
            //放心房数据
            $alist = $this->getAssureData($params);
        }
        //如果没有选择放心房
        if (!is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::ASSURE ],$params['product']))) {
            unset($alist);
        }

        //找到一个最大的长度
        $maxList = intval($plist['count']) >= intval($alist['count']) ?$plist:$alist;
        $maxList = intval($maxList['count']) >= intval($blist['count']) ?$maxList:$blist;
    
       if (isset($maxList['data'])) {
            foreach ($maxList['data'] as $reportDate => $value) {
                $result['data'][$reportDate][$this->indexFieldName] = $value[$this->indexFieldName];
                $result['data'][$reportDate]['similar_house_count'] = intval($plist['data'][$reportDate]['similar_house_count']) + intval($alist['data'][$reportDate]['similar_house_count'])  + intval($blist['data'][$reportDate]['similar_house_count']);
                $result['data'][$reportDate]['illegal_house_count'] = intval($plist['data'][$reportDate]['illegal_house_count']) + intval($alist['data'][$reportDate]['illegal_house_count']) + intval($blist['data'][$reportDate]['similar_house_count']); ;
                $result['data'][$reportDate]['comment_count'] = intval($plist['data'][$reportDate]['comment_count']) + intval($alist['data'][$reportDate]['comment_count']) + intval($blist['data'][$reportDate]['similar_house_count']);
            }
        }
               
        $result['count'] = $maxList['count'];
        return $result;
    }
    /*}}}*/
    
	/*{{{得到排序字段*/
	 /** 
     *@codeCoverageIgnore
     */
    protected function getSortCategory(){
        return array(
            Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
            Service_Data_HouseReport_GroupData::TOTAL,
            Service_Data_HouseReport_GroupData::PREMIER,
            Service_Data_HouseReport_GroupData::ASSURE,
            Service_Data_HouseReport_GroupData::BID,
            Service_Data_HouseReport_GroupData::STICK,
            Service_Data_HouseReport_GroupData::DXTG,
            Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
        );
    }
    /*}}}*/

    /*{{{groupAjaxData 合并Ajax数据*/
    /** 
     *
     * @param unknown $tags 
     * @param unknown $params    
     */
    public function groupAjaxData($tags,$params){
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        
        if($groupdataService->isShowTotal($tags,$params)) {
            //显示总数类别
            array_push($tags,Service_Data_HouseReport_GroupData::TOTAL);
        }
		$data[Service_Data_HouseReport_GroupData::PREMIER] = $this->getPremierData($params);
        if (is_array($tags) && !empty($tags)){
            foreach ($tags as $item) {
                if (!in_array($item,array(
                    Service_Data_HouseReport_GroupData::TOTAL,
                    Service_Data_HouseReport_GroupData::PREMIER,
                    Service_Data_HouseReport_GroupData::ASSURE,
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
                    case Service_Data_HouseReport_GroupData::TOTAL:
                        $data[Service_Data_HouseReport_GroupData::TOTAL] = $groupdataService->getTotalData($data,$params,$this->indexFieldName);
                        break;
                    case Service_Data_HouseReport_GroupData::FRAME_ORG_DATA:
                        $data[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA] = $this->getFrameData($params,$data);
                        break;
                    case Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA:
                        $data[Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA] = $this->getFrameAuditingData($params,$data);
                        break;
                    
                }
            }
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
