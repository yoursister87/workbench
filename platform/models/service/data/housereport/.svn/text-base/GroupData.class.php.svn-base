<?php
/*
 * File Name:GroupData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.coma
 * description:数据的组合
 */
ini_set('memory_limit','1024M');
class Service_Data_HouseReport_GroupData
{
    //使用数据类别
    //总数据
    CONST TOTAL = 0;
    //精品
    CONST PREMIER = 1;
    //放心房
    CONST ASSURE = 2;
    //置顶
    CONST STICK = 3;
    //竞价
    CONST BID = 4;
    //小区宝
    CONST XQB = 5;
    //定向推广
    CONST DXTG = 6;

    //产品类型类别
    //组织数据
    CONST FRAME_ORG_DATA = 10;
    //使用数据
    CONST FRAME_USE_DATA = 11;
    //审核数据 
    CONST FRAME_AUDITING_DATA = 12;

    //其他类别
    CONST ACCOUNT_ID = 20;
    //显示端口类别
    CONST BUSINESS_SCOPE = 21; 
    //分时段刷新
    CONST REFRESH_HOUSE = 22;
    //其他类别
    CONST ACCOUNT_EMAIL = 23;
	CONST CENTER_BALANCE = 9;
	CONST BID_BALANCE = 16;
    
    //下载相关
    CONST ORGDOWNLOAD_TIME = 30;
    //账号信息
    CONST ORGDOWNLOAD_ACCOUNT = 31;
    
    //默认每页取15条
    CONST PAGE_SIZE = 15;

    //搜索类型
    CONST SEARCH_OUTLET = 4;
    CONST SEARCH_BROKER_NAME = 5; //accountName
    CONST SEARCH_BROKER_ID = 6; //accountId
    CONST SEARCH_BROKER_PHONE = 7;//cellphone

    CONST NO_DATA_STR = '0';
    CONST NOT_DATA_STR = '--';
    //最大列数
    protected  $maxCol = 0;

    protected $sDate;
    protected $eDate;


    public static  $productStr2Indx = array(
        'premier'=>self::PREMIER,
        'assure'=>self::ASSURE,
        'stick'=>self::STICK,
        'bid'=>self::BID,
        'xqb'=>self::XQB,
        'dxtg'=>self::DXTG,

        'org'=>self::FRAME_ORG_DATA,
        'use'=>self::FRAME_USE_DATA,
        'verify'=>self::FRAME_AUDITING_DATA
    );

    public static $productIndex2Text = array(
       self::PREMIER=> 'premier',
       self::ASSURE=> 'assure',
        self::STICK=>'stick',
        self::BID=>'bid',

        self::FRAME_ORG_DATA=>'org',
        self::FRAME_USE_DATA=>'use',
        self::FRAME_AUDITING_DATA=>'verify'
    );

    public static $allowBusinessScope = array(
      HousingVars::BIZ_RENT_SHARE_SELL,  
      HousingVars::BIZ_RENT_SHARE,
      HousingVars::BIZ_SELL,
      HousingVars::BIZ_STORE_OFFICE,
      HousingVars::BIZ_STORE,
      HousingVars::BIZ_OFFICE,
      HousingVars::BIZ_STORE,
      HousingVars::BIZ_PLANT,
      HousingVars::BIZ_SHORT_RENT,
      HousingVars::BIZ_LOUPAN,
    ); 

    public static $accountStatus = array('1'=>'已激活','2'=>'被冻结','3'=>'已删除','6'=>'待审核','9'=>'待完善','10'=>'被冻结');

    /*
     *@codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    /*{{{isShowScale 是否显示比例信息*/
    public  function isShowScale($params,$bool = false){
        //这期不展示推广刷新率
        //FANG-9822
        if(!$bool){
            return false;
        }
        //如果选择的全部的房源类别或者全部的端口类型 则不显示刷新率 或者推广率
        if (isset($params['businessScope'])) {
            return true;
        } else {
            return false;
        }
    }
    /*}}}*/

    private function setNoExistsData($data,$indexList,$productList,$define = '--'){
        $result = array();
        foreach ($indexList as $index){
            foreach ($productList as $product=>$fields) {
                if (!isset($data[$index][$product])) {
                	if (in_array($product, array(3,4,6))) {
                		$tmp = array_fill(0,count($fields),self::NOT_DATA_STR);
                	}else{
                    	$tmp = array_fill(0,count($fields),$define);
                	}
                } else {
                    $tmp = $data[$index][$product];
                }
                if (!isset($result[$index])) {
                    $result[$index] = array();
                }
                $result[$index] = array_merge($result[$index],$tmp);
            }
        }

        return $result;
    }
    /**
     *@codeCoverageIgnore
     */
    public function setVal($property_name,$value){
            $this->{$property_name} = $value;
    }
    /**
     *@codeCoverageIgnore
     */
    public function getVal($property_name){
        if(isset($this->{$property_name})){
            return $this->{$property_name};
        }else{
            return NULL;
        }
    }

    public function getScale($dataList,$scaleField){
        if (empty($dataList['data'])){
            return $dataList;
        }

        foreach ($dataList['data']['list'] as &$item) {
            foreach($scaleField as $scaleKey=>$scale) {
                //除数
                $divisor = $item[current($scale)];
                //被除数
                $dividend = $item[key($scale)];
                if (intval($divisor) !==0) {
                    //比例不能大于100%
                    $num = round($dividend / $divisor,2);
                    $item[$scaleKey] = ($num >= 1)?1:$num;

                } else {
                    $item[$scaleKey] = null;
                }
            }
        }
        return $dataList;
    }
	public function getOrgData($data,$params,$indexFieldName){
		//精品的统计
		$pcount = count($data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
		//放心房的统计
		$acount = count($data[Service_Data_HouseReport_GroupData::ASSURE]['data']);
		$maxKey = $pcount >= $acount ?Service_Data_HouseReport_GroupData::PREMIER:Service_Data_HouseReport_GroupData::ASSURE;

		if (empty($data[$maxKey]['data'])) {
			return  array();
		} 	
	}

    public  function getTotalData($data,$params,$indexFieldName){
        //精品的统计
        $pcount = count($data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
        //放心房的统计
        $acount = count($data[Service_Data_HouseReport_GroupData::ASSURE]['data']);
        $maxKey = $pcount >= $acount ?Service_Data_HouseReport_GroupData::PREMIER:Service_Data_HouseReport_GroupData::ASSURE;

        if (empty($data[$maxKey]['data'])) {
            return  array();
        }

        $isShowScale = $this->isShowScale($params);
        if (!$isShowScale) {
            unset($this->productData[Service_Data_HouseReport_GroupData::TOTAL]['title_data']['premier_scale']);
            unset($this->productData[Service_Data_HouseReport_GroupData::TOTAL]['title_data']['refresh_scale']);
        }
        //遍历多的数据
        foreach ($data[$maxKey]['data'] as $index => $value) {
            $result['data'][$index][$indexFieldName] = $value[$indexFieldName];
            $result['data'][$index]['house_total_count'] = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['house_total_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['house_total_count'];
            $result['data'][$index]['house_count'] = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['house_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['house_count'];
            $result['data'][$index]['refresh_count'] = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['refresh_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['refresh_count'];
            $result['data'][$index]['account_pv'] = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['account_pv'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['account_pv'];
            $result['data'][$index]['premier_count'] = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['premier_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['premier_count'];
	//		var_dump($data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]);
	//		var_dump($data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]);
	//		var_dump($result['data'][$index]);exit;
            if ($isShowScale === true){
                $tuiguangMaxCount = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['tuiguang_max_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['tuiguang_max_count'];
                $refreshMaxCount = (int)$data[Service_Data_HouseReport_GroupData::PREMIER]['data'][$index]['refresh_max_count'] + (int)$data[Service_Data_HouseReport_GroupData::ASSURE]['data'][$index]['refresh_max_count'];
                $prmierCount = $result['data'][$index]['premier_count'];
                $refresCount = $result['data'][$index]['refresh_count'];
                //保留两位有效数字  比例不能大于100
                if($tuiguangMaxCount!=0)
                $pscale = (round($prmierCount/ $tuiguangMaxCount,2) >=1)?1:round($prmierCount/ $tuiguangMaxCount,2);
                if($refreshMaxCount!=0)
                $rscale = (round($refresCount/ $refreshMaxCount,2)>=1)?1:round($refresCount/ $refreshMaxCount,2);
                $result['data'][$index]['premier_scale'] = (intval($tuiguangMaxCount) === 0)?0:$pscale;
                $result['data'][$index]['refresh_scale'] = (intval($refreshMaxCount) === 0)?0:$rscale;
            }
        }
        return $result;
    }

    //counttype = 1为精品 counttype = 2 为放心房
    public function getBusinessScope($params,$indexFieldName,$countType = 1){
        $result = array();
        $businessObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
        $businessScope = !isset($params['businessScope'])?array():$params['businessScope'];
        //只查询付费的订单
        $whereConds = array();
        $whereConds['effective'] = true;
        $whereConds['countType'] = $countType;
        $tmpList = $businessObj->getBusinessInfoByAccountIds($params['accountIds'],$whereConds);
        //循环订单
        $tmp = array();
        foreach ($tmpList['data'] as $list) {
            //目前值统计精品端口
            if (in_array($list['AccountId'],$params['accountIds']) && $list['CountType'] == $countType
            && in_array($list['BussinessScope'],$params['businessScope'])
            ){
            	$tmp[$list['AccountId'].'_'.$list['BussinessScope']] = array(
            			'account_id'=>$list['AccountId'],
            			'BussinessScope'=>$list['BussinessScope'],
            			'business_scope_str'=>HousingVars::$bizTxt[$list['BussinessScope']],
            	);
                //$tmp[$list['AccountId'].'_'.$list['BussinessScope']]['business_scope_str'] = HousingVars::$bizTxt[$list['BussinessScope']];
            }
        }
        /* $ret = array();
        foreach ($tmp as $accountId => $value) {
        	var_dump($tmp);exit;
            $ret[$accountId][$indexFieldName] = $accountId;
            $ret[$accountId]['business_scope_str'] = implode('/', $value);
        }

        $result['data'] = $ret; */
        $result['data'] = $tmp;
        
        return $result;
    }

    //{{{ isShowTotal 是否显示汇总类别
    /** 
     *
     */
    public function isShowTotal($tags,$params){
        if (in_array(self::PREMIER,$tags) && in_array(self::ASSURE,$tags)) {
            $houseType = $params['houseType'];
            if (is_array($houseType) && !empty($houseType)) {
                foreach ($houseType as $key => $value) {
                    //有这些房屋类别展示放心房
                    if (in_array($value, array(0,1,3,5))) {
                        return true;
                    } 
                }
                return false;
            } else {
                return false;
            }
        }  else {
            return false;
        }
    }
    /*}}}*/
    //{{{ setDefaultValue 设置默认值
    protected function setDefaultValue($orgIds,$fields,$indexFieldName,$default = 0){
        $newList = array();
        foreach ($orgIds as $id) {
             $newList[$id][$indexFieldName] = $id;
            foreach ($fields as $key=>$item) {
                $newList[$id][$key] = $default;
            } 
        }
        return $newList;
    }
    //}}}
    //{{{ changeData data列表生成数据
    /** 
     * 获取区域，板块，门店列表
     * @param unknown $dataList   
     * @param unknown $changeFields               $premierOrgFields  或 $assureOrgFields
     *
     */
    public function changeData($dataList,$changeFields,$indexFieldName){
    	//var_dump($dataList['data']['list'],$changeFields,$indexFieldName);exit;
        $newList = array();
        if (empty($dataList['data']['list'])) {
            return $newList;
        }

        if (!$dataList['errorno']) {
            foreach ($dataList['data']['list'] as $key=>$data) {
                if (!in_array($indexFieldName,array_keys($data))){
                    throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_DATA_ERROR_NUM,"需要{$indexFieldName}字段");
                }
                if($indexFieldName=='org_id'){
	                $index = $data[$indexFieldName];
	                foreach ($data as $field=>$post) {
	                    if (in_array($field,array_keys($changeFields))) {
	                        $newList['data'][$index][$changeFields[$field]] = $post;
	                    }
	                }
                }else{
	                foreach ($data as $field=>$post) {
	                	if (in_array($field,array_keys($changeFields))) {
	                		$newList['data'][$key][$changeFields[$field]] = $post;
	                	}
	                }
                }
            }
        } else {
            //错误处理
            throw new Gj_Exception($dataList['errorno'],$dataList['errormsg']);
        }
        $newList['count'] = count($dataList['data']['list']);
       
        return $newList;
    }
    /*}}}*/
     /*{{{ sortAjaxData 对ajax返回的数据进行排序 */
    protected function sortAjaxData($dataList,$sortCategory){
        $newProductList = array();
        //排序列表
        foreach ($sortCategory as $key => $value) {
            if (isset($dataList[$value])) {
                $newProductList[$value] = $dataList[$value];
            }
        }
        return $newProductList;
    }
    /*}}}*/
    /*{{{ mergeData 合并数据
     *  array(
     *       array(
     *       'title'=>'精品',
     *       'title_data'=>array('房源总数','新增','推广','推广率','刷新','刷新率','点击量'),
     *       'total_data'=>array(
     *           //所有数据的综合
     *           orgId=>array(data1,data2,data3,data4,data5,data6,data7,'assure_data1',assure_data2,assure_data3,assure_data4,assure_data5),
     *           orgId=>array(data1,data2,data3,data4,data5,data6,data7,'assure_data1',assure_data2,assure_data3,assure_data4,assure_data5),
     *        ),
     *        //最大行数
     *        'maxCol'=>数字
     *
     *      )
     *      array(
     *        'title'=>放心房
     *        'date_list'=>array('房源总数','新增','推广','刷新','点击量')
     *      )
     *     需要接受的格式是  array(data=>array(),count=>int) 数据需要放到data中
     */
	 /*
     **/ 
    public function mergeData($dataList,$sortCategory,$productData,$indexFieldName){
        $titleData = array();
        $dataTotal = array();
        $indexList = array();
        $productList = array();
        //排序产品类别
        $dataList = $this->sortAjaxData($dataList,$sortCategory);
    	//var_dump($dataList,$sortCategory,$productData,$indexFieldName);exit;
        foreach ($dataList as $product=>$data) {
            $titleData[$product] = $productData[$product];
            $this->maxCol += count( $titleData[$product]['title_data']);
            $productList[$product] = array_keys($titleData[$product]['title_data']);
            //生成当前表格需要的字段
            $allowField = array_keys($titleData[$product]['title_data']);
            //如果没有数据则初始化值
            if (!isset($dataList[$product]['data'])) {
                $fields = $productData[$product]['title_data'];
                if (in_array($product, array(3,4,6))) {
                	$data['data'] = $this->setDefaultValue($indexList,$fields,$indexFieldName,self::NOT_DATA_STR);
                }else{
                	$data['data'] = $this->setDefaultValue($indexList,$fields,$indexFieldName,self::NO_DATA_STR);
                }
            }
            if($indexFieldName=='report_date'){
               foreach ($data['data'] as $key=>$report) {
	                if(!isset($report[$indexFieldName])) {
	                    throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_DATA_ERROR_NUM,"需要{$indexFieldName}字段");
	                }
	                $indexList[] = $report[$indexFieldName];
	                //当前类别允许的字段填充
	                foreach ($allowField as $field){
	                    //设置默认值
	                	if (in_array($product, array(3,4,6))) {
	                		$tmp[$report[$indexFieldName]][] = isset($report[$field])?$report[$field]:self::NOT_DATA_STR;
	            		}else{
	            			$tmp[$report[$indexFieldName]][] = isset($report[$field])?$report[$field]:self::NO_DATA_STR;
	            		}
	                }
	                
	                if (empty($dataTotal[$report[$indexFieldName]])){
	                     $dataTotal[$report[$indexFieldName]] = array();
	                     //$dataTotal[$report[$indexFieldName]] = array();
	                }
	                //$dataTotal[$report[$indexFieldName]] =  array_merge($dataTotal[$report[$indexFieldName]],$tmp[$report[$indexFieldName]]);
	                $dataTotal[$report[$indexFieldName]][$product] = $tmp[$report[$indexFieldName]];
	                $tmp = array();
            	}
            }else{
	            foreach ($data['data'] as $key=>$report) {
	            	if(!isset($report[$indexFieldName])) {
	            		throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_DATA_ERROR_NUM,"需要{$indexFieldName}字段");
	            	}
	            	$indexList[] = $key;
	            	//当前类别允许的字段填充
	            	foreach ($allowField as $field){
	            		//设置默认值
	            		if (in_array($product, array(3,4,6))) {
	            			$tmp[$key][] = isset($report[$field])?$report[$field]:self::NOT_DATA_STR;
	            		}else{
	            			$tmp[$key][] = isset($report[$field])?$report[$field]:self::NO_DATA_STR;
	            		}
	            	}
	            	if (empty($dataTotal[$key])){
	            		$dataTotal[$key] = array();
	            		//$dataTotal[$report[$indexFieldName]] = array();
	            	}
	            	//$dataTotal[$report[$indexFieldName]] =  array_merge($dataTotal[$report[$indexFieldName]],$tmp[$report[$indexFieldName]]);
	            	$dataTotal[$key][$product] = $tmp[$key];
	            	$tmp = array();
	            }
            }
            //index去重
            $indexList = array_unique($indexList);
        }
        $i = 0;
        $newRes = array();
        //设置未定义的字段的默认值
        $dataTotal = $this->setNoExistsData($dataTotal,$indexList,$productList,self::NO_DATA_STR);
        foreach ($titleData as $data){
            $i++;
            if ($i==1) {
                $data['total_data'] = $dataTotal;
                $data['maxCol'] = $this->maxCol;
            }
            $data['title_data'] = array_values($data['title_data']);
            //建立一个新数组  这么做的原因是 js 变量是数组的索引顺序遍历的
            $newRes[$i] = $data;
        }
        return $newRes;
    }
    /**}}}*/
    
    /*{{{按照键值进行数据匹配 matchData*/
    /*
    *  @param titleData 左表格中的title数据
    *  @param tabledata 右表格中的data数据
    *  @param sortIndex 匹配字段键名
    */
    public function matchTableData($titleData,$tableData,$sortIndex){
    	//var_dump($titleData,$tableData,$sortIndex);exit;
    	reset($tableData);
    	$firstData = current($tableData);
    	$sortData = array();
    	foreach ($firstData['total_data'] as $key=>$row) {
    		$colCount = count($row);
    		if ($colCount == $firstData['maxCol']) {
    			$sortData[$key] = $row;
    		} else {
    			//初始化数据
    			$sortData[$key] = $row;
    			for ($i = $colCount;$i<$firstData['maxCol'];$i++) {
    				
    				$sortData[$key] =  array_merge($sortData[$key],array(self::NO_DATA_STR));
    			}
    		}
    		$tmp = array();
    	}
    	$firstKey = current(array_keys($tableData));
    	$tableData[$firstKey]['total_data'] = $sortData;
    	return $tableData;
    }
    public function matchData($titleData,$tableData,$sortIndex){
    	//var_dump($titleData,$tableData,$sortIndex);exit;
        reset($tableData);
        $firstData = current($tableData);
        $sortData = array();
        foreach ($titleData[$sortIndex] as $index) {
            if(isset($firstData['total_data'][$index])) {
                $colCount = count($firstData['total_data'][$index]);
                if ($colCount == $firstData['maxCol']) {
                    $sortData[$index] = $firstData['total_data'][$index];
                } else {
                    //初始化数据
                    $sortData[$index] = $firstData['total_data'][$index];
                    for ($i = $colCount;$i<$firstData['maxCol'];$i++) {
                        $sortData[$index] =  array_merge($sortData[$index],array(self::NO_DATA_STR));
                    }
                }
			} else {
                //初始化数据
                for($i=0;$i<$firstData['maxCol'];$i++) {
                    $tmp[] = self::NO_DATA_STR;
                }
                $sortData[$index] = $tmp;
            }
            $tmp = array();
        }
        $firstKey = current(array_keys($tableData));
        $tableData[$firstKey]['total_data'] = $sortData;
        return $tableData;
    }
    /*}}}*/
    //{{{matchTitleData
    /**
     * 重置key并添加accoun_id
     * @param unknown $titleData
     * @param unknown $tableData
     * @return multitype:NULL
     */
    public function matchTitleData($titleData,$tableData){
    	$firstData = current($tableData);
    	$accountKeys = array_keys($firstData['total_data']);
    	$accountIds = array_keys($titleData['data']['title_list']);
    	$titleList = array();
    	foreach ($accountKeys as $row){
    		$accountArr = explode('_',$row);
    		if (in_array($accountArr[0], $accountIds) && isset($titleData['data']['title_list'][$accountArr[0]])) {
    			$titleList[$row] = $titleData['data']['title_list'][$accountArr[0]];
    			//$titleList[$row]['account_id'] = $accountArr[0];
    		}
    	}
    	$titleData['data']['title_list'] = $titleList;
    	return $titleData;
    }//}}}
    /*
      * 获取时间段内组织结构统计数据总和
      * $params array('ordid','countType')
      * $type string 'PREMIER' 'ASSURE' 'STICK' 'BID'  'DXTG' 'XQB' 'PREMIER_ALL'
      *
      * */
      public function getOrgClickByDay($params){
         $OrgDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_OrgReport');
         $start_date = strtotime($this->sDate);
         $end_date = strtotime($this->eDate);
         $OrgDataService->setVal('countType', $params['countType']);
         $OrgDataService->setVal('order',array('order'=>'asc','orderField'=>'null'));
         $total_click = $data = array();
         for($day = $start_date;$day <= $end_date;$day += 86400 ){
             $OrgDataService->setVal('sDate', date('Y-m-d',$day));
             $OrgDataService->setVal('eDate', date('Y-m-d',$day));
             if($params['countType'] == array(4)){
                $OrgDataService->setVal('fields', array('sum(click_count) as click_count'));
                $data = $OrgDataService->getOrgAssureReportById($params['orgid'],false);
             }else{
                 $OrgDataService->setVal('fields' ,array('sum(account_pv) as click_count'));
                 $data = $OrgDataService->getOrgPremierReportById($params['orgid'],false);
             }
             $total_click[$day] = intval($data['data']['list'][0]['click_count']);
         }
 
         return $total_click ;
      }
      /**
       * {{{getMillisecond 计算运行时间
       * 计算运行时间
       * @codeCoverageIgnore
       * @return number
       */
      public function getMillisecond() {
      	list($t1, $t2) = explode(' ', microtime());
      	return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
      }//}}}
      /* {{{ log */
      /**
       * @codeCoverageIgnore
       * @param $msg
       * @returns
       */
      public function log($msg) {
      	if(empty($msg)) return false;
      	$date = date("Y-m-d H:i:s");
      	$filedate = date("Y_m_d");
      	$msg = "[".$date."]:".$msg."\n";
      	//echo $msg;
      	$logfile = "/ganji/ganji_log/tuiguang/house_report_data_download_".$filedate.".log";
      	if (false === file_exists($logfile)) {
      		touch($logfile);
      		chmod($logfile, 0666);//rw-rw-rw-
      	}
      	error_log($msg,3, $logfile);
      }//}}}
}
