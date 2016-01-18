<?php
/*
 * File Name:GroupOrgData.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */

class Service_Data_HouseReport_GroupOrgData
{
    //需要指定一个唯一的索引字段
    protected $indexFieldName = 'org_id';
    //数据库中的值=>换换为新的键
    protected  $premierFields = array('org_id'=>'org_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv',
        'customer_count'=>'customer_count','account_count'=>'account_count','login_count'=>'login_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count');
    protected  $assureFields = array('org_id'=>'org_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count');
    /* protected  $bidFields = array('org_id'=>'org_id','bid_house'=>'bid_house','account_pv'=>'bid_account_pv',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count',
    ); */
    protected  $bidFields = array('org_id'=>'org_id','house_count'=>'bid_house_count','click_price'=>'bid_click_price',
    		'account_pv'=>'bid_account_pv',
    );
    protected  $stickFields = array('org_id'=>'org_id','house_count'=>'stick_house_count','click_price'=>'stick_click_price',
    		'account_pv'=>'stick_account_pv',
    );
    protected  $dxtgFields = array('org_id'=>'org_id','house_count'=>'dxtg_house_count','click_price'=>'dxtg_click_price',
    		'account_pv'=>'dxtg_account_pv',
    );

    //组织结构数据返回结构
    protected $productData = array(
        Service_Data_HouseReport_GroupData::TOTAL => array(
            'title'=>'汇总数据',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                'refresh_count'=>'刷新','account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ), 
        Service_Data_HouseReport_GroupData::PREMIER => array(
            'title'=>'精品',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                'refresh_count'=>'刷新','account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ),    
        Service_Data_HouseReport_GroupData::ASSURE => array(
            'title'=>'放心房',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                'refresh_count'=>'刷新','account_pv'=>'点击量',
            ),
            'group'=>1  #组别
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
        Service_Data_HouseReport_GroupData::FRAME_ORG_DATA => array(
            'title'=>'组织数据',
            'title_data' => array(
                'plate_count'=>'板块数',
                'customer_count'=>'门店数',
                'login_count'=>'登录次数',
                'account_count'=>'经纪人数', 
            ),
            'group'=>2  #组别
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

   public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        } 
    }

    //{{{ getAssureData data列表生成数据
    /** 
     * 获取 放心房结果 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getAssureData($params){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_OrgReport');
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        
        $dataList = $dataService->getOrgAssureReportList($params['orgIds']);
        $newList = $groupdataService->changeData($dataList,$this->assureFields,$this->indexFieldName);
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
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_OrgReport');
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        $dataService->setVal('countType',array(1));
        $dataList = $dataService->getOrgPremierReportList($params['orgIds']);
        $newList = $groupdataService->changeData($dataList,$this->premierFields,$this->indexFieldName);
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
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $dataService = Gj_LayerProxy::getProxy("Service_Data_HouseReport_OrgReport");
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        
        if (isset($params['houseType'])) {
            $dataService->setVal('houseType',$params['houseType']);
        }
        $dataService->setVal('countType',array($countType));
        $dataList = $dataService->getOrgPremierReportList($params['orgIds']);
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);
        return $newList;
    }
    /*}}}*/
    protected function processQueryDays($params){
    	$newParams = array();
    	$newParams['orgId'] = implode(',', $params['orgIds']);
    	$days = array();
    	$stime = strtotime($params['date']['sDate']);
    	$etime = strtotime($params['date']['eDate']);
    	for ($i = $stime;$i <= $etime;$i+=86400) {
    		$days[date("Y-m-d",$i)] = $i;
    	}
    	$newParams['houseType'] = $params['houseType'];
    	$newParams['days'] = $days;
    	$newParams['c'] = 'report';
    	return $newParams;
    }

    /** 
     * 获得组织数据 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getFrameData($params,$data){
        $newList = array();
        $orgCount = null;
        $managerSerivce = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
 
        if ($data[Service_Data_HouseReport_GroupData::PREMIER]) {
            $newList =  $data[Service_Data_HouseReport_GroupData::PREMIER];
        } else {
            //组织数据存储到 house_company_org_report中
            $newList =  $this->getPremierData($params);
        }
        if (isset($newList['data'])) {
            foreach ($params['orgIds'] as $key => $orgId) {
                //添加区域名称
                if (isset($params['areaName'][$orgId])) {
                    $newList['data'][$orgId]['area_name'] = $params['areaName'][$orgId];
                }

                //搜索区域下面的的板块数量
                if ($params['userLevel'] <= $params['level'] && $params['level'] == 1) {
                    $where['pid'] = $orgId;
                    $where['level'] = 3;
                    $where['company_id'] = $params['companyId'];
                    $cdata = $managerSerivce->getOrgCountByPid($where);
                    $orgCount = $cdata['data'];
                    $newList['data'][$orgId]['plate_count'] = !empty($orgCount) ? $orgCount : 0;
                }
                $newList['data'][$orgId][$this->indexFieldName] = $orgId;
            }

        }
        //去掉板块数
        if (!isset($orgCount)){
            unset($this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['plate_count']);
        }
        //去掉门店数
        if ($params['level'] == 3) {
            unset($this->productData[Service_Data_HouseReport_GroupData::FRAME_ORG_DATA]['title_data']['customer_count']);
        }
        return $newList;
        //return $newList;
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
        if (!is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::PREMIER],$params['product']))) {
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
        if (!is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::ASSURE],$params['product']))) {
            unset($alist);
        }

        //找到一个最大的长度
        $maxList = intval($plist['count']) >= intval($alist['count']) ?$plist:$alist;
        $maxList = intval($maxList['count']) >= intval($blist['count']) ?$maxList:$blist;
    
        if (isset($maxList['data'])) {
            foreach ($maxList['data'] as $orgId => $value) {
                $result['data'][$orgId]['org_id'] = $value['org_id'];
                $result['data'][$orgId]['similar_house_count'] = intval($plist['data'][$orgId]['similar_house_count']) + intval($alist['data'][$orgId]['similar_house_count'])  + intval($blist['data'][$orgId]['similar_house_count']);
                $result['data'][$orgId]['illegal_house_count'] = intval($plist['data'][$orgId]['illegal_house_count']) + intval($alist['data'][$orgId]['illegal_house_count']) + intval($blist['data'][$orgId]['similar_house_count']); ;
                $result['data'][$orgId]['comment_count'] = intval($plist['data'][$orgId]['comment_count']) + intval($alist['data'][$orgId]['comment_count']) + intval($blist['data'][$orgId]['similar_house_count']); ;
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

        if (is_array($tags) && !empty($tags)){
            foreach ($tags as $item) {
                if (!in_array($item,array(Service_Data_HouseReport_GroupData::TOTAL,
                    Service_Data_HouseReport_GroupData::PREMIER,Service_Data_HouseReport_GroupData::ASSURE,
                    Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
                    Service_Data_HouseReport_GroupData::FRAME_USE_DATA,
                    Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
                    Service_Data_HouseReport_GroupData::BID,
                	Service_Data_HouseReport_GroupData::STICK,
                    Service_Data_HouseReport_GroupData::DXTG))) {
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
            $getSortCategory = $this->getSortCategory();
            $res = $groupdataService->mergeData($data,$getSortCategory,$this->productData,$this->indexFieldName);
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_TAGS_ERROR_NUM,"没有响应的tag配置");
        }

        return $res;
    }
    /*}}}*/
}
