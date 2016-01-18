<?php
/*
 * File Name:OrgAjax.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Page_HouseReport_Report_OrgAjax
{
    //用来排序的索引
    protected $sortIndexName = 'orgIds';

    protected $levelToStr = array(
        '2'=>'大区',
        '3'=>'板块',
        '4'=>'门店',
    );

    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this,$name),$args);
        }
    }

    protected function substrTitle($title,$length = 9){
        if (empty($title)) 
            return '';
        //截取门店名称  存在两种清空的分隔
        if (strpos($title,'-')) {
            $titleArr = explode('-',$title);
        } else if (strpos($title,'－')) {
            $titleArr = explode('－',$title);
        }
        if (is_array($titleArr)) {
        	if (count($titleArr)==3) {
        		$shortTitle = trim($titleArr[1]).'-'.trim($titleArr[2]);
        	}else{
            	$shortTitle = trim(end($titleArr));
        	}
            return mb_substr($shortTitle,0,$length ,'UTF-8');
        } else {
            return mb_substr($title,0,$length ,'UTF-8');
        }
    }
    /*{{{groupOrgData 组织结构数据*/
    /** 
     * 
     * @param unknown $params    
     *  数据格式
     *  array(
     *       'data'=>array(
     *           'title'=>'大区',
     *          'title_list'=> array(
     *              orgId=>array('name'=>'name','href'=>'href')
     *              orgId=>array('name'=>'name','href'=>'href')
     *          ),
     *          'count'=>条数
     *       )
     *      'orgIds' => array()
     *       
     */
    protected function groupOrgData($params){
        $utilUrl = new Util_CommonUrl();
        $accountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        //获得当前页
        $page = intval($params['page']) <= 0?1:intval($params['page']);
        $res['data']['title'] = $this->levelToStr[$params['level'] + 1];
        $listParams['company_id'] = $params['companyId'];
        //否则使用这个用户这级的
        $pid = HttpNamespace::getGET('pid',$params['userId']);
        $level = $params['level'] + 1;
        $sparams['title'] =  $params['keyword'];
        $orgData = $accountService->getChildTreeByOrgId($pid,$level,$sparams,$page,Service_Data_HouseReport_GroupData::PAGE_SIZE);
        if ($orgData['errorno'] || empty($orgData['data']['list'])) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"此结构下面没有数据");
        }
        $res['data']['count'] = $orgData['data']['count'];
        $url['c'] = 'report';
        if (!$orgData['errorno']) {
            foreach ($orgData['data']['list'] as $item) {
                //如果是门店级别 获得门店id
                $url['level'] = $item['level'];
                $url['pid'] = $item['id'];
                $href = $utilUrl->createUrl($url, null);
                $title = $this->substrTitle($item['title']);
                $res['data']['title_list'][$item['id']] = array('name'=>$title,'href'=>$href,'title'=>$item['title']);
                //建立排序索引
                $res[$this->sortIndexName][] = $item['id'];
            }
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"组织结构数据有误");
        }
        return $res;
    }
    /*}}}*/
    
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    }


    /**
	 * {{{execute
	 */
    public function execute($arrInput){
        $groupDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $checkData = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
        $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOrgData');
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');

        $params  = array();
        try{
            $params = $pageDataSerivce->getCommonParams($arrInput);
            if (isset($arrInput['date'])){
                //设置时间
                $params['date'] = $checkData->setDate($arrInput['date']);
            }

            if (isset($arrInput['houseType'])) {
                //设置房源
                $params['houseType'] = $checkData->setHouseType($arrInput['houseType']);;
            }

            //精品或者竞价
            if (isset($arrInput['countType'])) {
                $params['countType']  = $checkData->setCountType($arrInput['countType']);;
            }

        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if (!$this->data['errorno']){
            $tags = array();
            //组合商品类型 和数据类型
            $productGroup = array_merge($params['product'],$params['dtype']);
            foreach ($productGroup as $product) {
                if (isset(Service_Data_HouseReport_GroupData::$productStr2Indx[$product])) {
                    $tags[] = Service_Data_HouseReport_GroupData::$productStr2Indx[$product];    
                }
            }
            try{
                $orgData = $this->groupOrgData($params);
                //得到结构下面的orgids
                $params['orgIds'] = $orgData[$this->sortIndexName];
                $res = $groupService->groupAjaxData($tags,$params);
                //数据匹配
                $res = $groupDataService->matchData($orgData,$res,$this->sortIndexName);
                
                $this->data['data']['dataList'] = $res;
                $this->data['data']['titleList'] = $orgData['data'];
                //分页页码
                $this->data['data']['page'] = $pageDataSerivce->getPageStr($params['page'], $orgData['data']['count'], $params['level']);
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;
    }	
    /*}}}*/
}
