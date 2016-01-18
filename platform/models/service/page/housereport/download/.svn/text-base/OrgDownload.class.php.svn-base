<?php
/*
 * File Name:OrgDownload.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Page_HouseReport_Download_OrgDownload
{
    //用来排序的索引
    protected $sortIndexName = 'orgIds';

    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this,$name),$args);
        }
    }
    //{{{得到汇总表格下载需要的数据
    protected function getSumData($downData){
        //第一标题
        $firstTitle = array();
        //第二标题
        $assistantTitle = array();
        $sheetName = '汇总数据报表';
        //下载的数据
        foreach ($downData as $dataList) {
            $firstTitle[0][] = array(
                'name'=>$dataList['title'],
                'width'=>count($dataList['title_data']),
            );
            $assistantTitle = array_merge($assistantTitle,$dataList['title_data']);
        }
        $data = array();
        $res = array();
        $tmpData = reset($downData);
        foreach ($tmpData['total_data'] as $index=>$item) {
            $data[] = $item;
        }
        //副标题放入数组的第一个值
        array_unshift($data,$assistantTitle);
        $res[$sheetName]['title'] = $firstTitle;
        $res[$sheetName]['data'] = $data;
        return $res;
    }
    //}}}   
    protected function createExcel($data){
        $houseExcel = new Util_HouseExcel();       
        
        $houseExcel->setData($data);
        $houseExcel->createExcel();
    }

    protected function createCsv($data,$filename = 'report'){
        $houseExcel = new Util_HouseExcel();       
        $data = reset($data);
        $houseExcel->createCsv($data,$filename);
    }
    /*{{{groupOrgData 组织结构数据*/
    /** 
     * 
     * @param unknown $params    
     *  数据格式
     *  array(
     *       'data'=>array(
     *              array(data1,data2,data3,data4)
     *              array(data1,data2,data3,data4)
     *          ),
     *          'count'=>条数
     *       )
     *      'orgIds' => array()
     *       
     */
    protected function groupOrgData($params){
        $accountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $level = $params['level'] + 1;
        $pid = HttpNamespace::getGET('pid',$params['userId']);
        $level = $params['level'] + 1;
        $sparams['title'] =  $params['keyword'];
        //下载表格没有分页  
        $orgData = $accountService->getChildTreeByOrgId($pid,$level,$sparams,null,null);
        if ($orgData['errorno'] || empty($orgData['data']['list'])) {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"此结构下面没有数据");
        }
        
        foreach ($orgData['data']['list'] as $item) {
            $res['data']['areaName'][$item['id']] = $item['title'];
            $res[$this->sortIndexName][] = $item['id'];
            $res['data']['account'][$item['id']] = $item['account'];
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

        $date = $arrInput['date']['sDate'].'至'.$arrInput['date']['eDate'];
        $params  = array();

        switch ($downloadType) {
            case '1':
                //汇总数据
                 $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOrgDownloadData');
                break;
            default:
                 $groupService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupOrgDownloadData');
                break;
        }

        $groupDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $checkData = Gj_LayerProxy::getProxy('Service_Data_HouseReport_CheckData');
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');

        try{
            $params = $pageDataSerivce->getCommonParams($arrInput);
            //目前只有汇总数据
            $params['downloadFilename'] = $date."汇总数据";
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
                //设置房源
                $params['countType']  = $checkData->setCountType($arrInput['countType']);;
            }



            $params['houseTypeText'] = $arrInput['houseTypeText'];
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
                $params['areaName'] = $orgData['data']['areaName'];
                $params['account'] = $orgData['data']['account'];

                $res = $groupService->groupDownLoadData($tags,$params);

                //数据匹配 和对 0 进行补充
                $downloadData = $groupDataService->matchData($orgData,$res,$this->sortIndexName);
                //获得求和数据
                $downData = $this->getSumData($downloadData);
                $this->createCsv($downData,$params['downloadFilename']);
            } catch (Exception $e) {
                $this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();
            }
        }
        return $this->data;
    }	
    /*}}}*/
}
