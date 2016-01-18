<?php
/*
 * File Name:AccountReport.class.php
 * Author:zhangliming,zhangyulong1
 * mail:zhangliming@ganji.com
 */
class Service_Data_HouseReport_AccountReport
{

    protected  $pager = array('currentPage'=>1,'pageSize'=>20);
    protected  $sDate = null;
    protected  $eDate = null;
	protected  $dateStr = null;  
    protected  $houseType = array(0);
    protected  $order = array();

    protected  $fields = array();
    protected  $countType = array(1);

	protected  $startTime = null;
	protected $endTime = null;
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}   
	}  
    //{{{mergeDataByAccountId  data2中的值添加到data1中
    private function mergeDataByAccountId($data1,$data2){
        $tmpData = array();
        $result = array();

        if (empty($data1)) {
            return $data1;
        }
        
        foreach ($data2 as $item){
            $tmpData[$item['account_id']] = $item;
        }



        foreach ($data1 as $item) {
            if (isset($tmpData[$item['account_id']])) {
                $result[] = array_merge($item,$tmpData[$item['account_id']]);
            } else {
                $result[] = array_merge($item,array());
            }
        }

        return $result;
    }
    //}}}
    /**
     *{{{__construct 
     *@codeCoverageIgnore
     */
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->sDate = date('Y-m-d',strtotime('yesterday'));
        $this->eDate = date('Y-m-d',strtotime('yesterday'));
		$this->dateStr = date('Y-m-d'); 
        
    }
    /*}}}*/
     /*
     *{{{__set
     *@codeCoverageIgnore
     */

     public function setVal($property_name,$value){
            $this->{$property_name} = $value;
    }
    /*}}}*/
    /*
     * {{{ public getAccountPremierReportDetail
     * @params accountId int
     * @desc 得到某个账号的精品或者竞价按照时间分组的统计信息
     */
    public function getAccountPremierReportDetail($accountId){

        if (!is_numeric($accountId)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        if (empty(self::$order)) {
            $this->order['order'] = 'DESC';
            $this->order['orderField'] = 'ReportDate';
        }
        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);

        $houseType = implode(',', $this->houseType);
        $countType = implode(',', $this->countType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
        //条件拼接
        $arrConds = array("accountId IN ({$accountId})",'ReportDate >='=>$stime,'ReportDate <='=>$etime,"houseType IN ({$houseType})","countType IN ({$countType})");
        try {
            $reportData = $objDao->getAccountPremierReportDetail($fields,$this->eDate,null,$arrConds,$this->order['orderField'],$this->order['order']);
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    }
    /*}}}*/

    /**
     * {{{ public getAccountAssureReportDetail
     * @params accountId int
     * @desc 得到某个账号的放心房按照时间分组的统计信息
     */
    public function getAccountAssureReportDetail($accountId){
         if (!is_numeric($accountId)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);

        $houseType = implode(',', $this->houseType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountReportV2');
        //条件拼接
        $arrConds = array("account_id IN ({$accountId})",'report_date >='=>$stime,'report_date <='=>$etime,"house_type IN ({$houseType})","report_type = 4");
		$this->order['order'] = 'DESC';
		$this->order['orderField'] = 'report_date';
		try {
            $reportData = $objDao->getAccountAssureReportDetail($fields,null, $arrConds,$this->order['orderField'],$this->order['order']);
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;

    }
    /*}}}*/
	protected function getLoginList($accountIds){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccountLoginEvent');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}   
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}   
		$arrConds = array(" AccountId IN ({$accountIds})","LoginTime>= $this->startTime");
		try{
			$reportData['list']= $objDao->getLoginCountByAccountId($accountIds,$arrConds);
			$this->data['data'] = $reportData;
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}   
		return $this->data; 	
	}
	/** 
	 * 合并实时数据，登陆次数，房源总数，精品、放心推广，精品放心房刷新
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
	public function mergeRealDataTime($accountIds,$type){
		$houseTotalList = $this->getAccountPremierReportListRealDataTime($accountIds,$type);//取出房源总数	
		$housePremier = $this->getAccountPremierReportListPremierRealDataTime($accountIds,$type);//取出推广数量
		$houseDataList = $this->getSourceOperationRealDataTime($accountIds,$type);//获取刷新和新增数量
		$houseCountList = $this->getLoginList($accountIds);//登陆次数
		foreach($houseTotalList['data']['list'] as &$value){
			$value['login_count'] = 0;
			$value['house_total_count'] = $value['house_count'];
			$value['house_count'] = 0;
			$value['premier_count'] = 0;
			$value['refresh_count'] = 0;	
			foreach($housePremier['data']['list'] as $value1){
				if($value['account_id'] == $value1['account_id']){
					$value['premier_count'] = (int)$value1['house_count'];//组装推广数	
				}
			}
			foreach($houseCountList['data']['list'] as $value1){
				if($value['account_id'] == $value1['AccountId']){
					$value['login_count'] = $value1['count'];//组装登陆次数
				}
			}
			foreach($houseDataList['list']['user-add'] as $value1){
				if($value['account_id'] == $value1['account_id']){
					//account_id
					$value['house_count'] = $value1['count'];	//组装房源数
				}
			} 
			foreach($houseDataList['list']['user-add-refresh'] as $value1){
				if($value['account_id'] == $value1['CreatorId']){
					$value['refresh_count'] = $value1['count'];   //组装刷新数
				}
			} 
		}	

		return $houseTotalList;
	}
	protected function getUserAddHouse($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Sourcelist_PremierList');    
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}   
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}    
		$houseType = implode(',', $this->houseType);
		if(1 == $type){
			$premier_status = "premier_status IN ( 0, 2)";
		}else{
			$premier_status = "premier_status IN ( 100,102)";
		}  		
		$arrConds = array(" account_id IN ({$accountIds})","$premier_status","listing_status = 1","type IN ({$houseType})","post_at >=".strtotime("midnight"));
		try{
			$this->data = $objDao->getPostCountByAccountId1($accountIds,$arrConds);
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
	/** 
	 * 从house_source_operation表中实时取出精品和放心房的新增房源数量
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
/*	protected function getUserAddHouse($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Source_PremierSourceOperation');
		$objSourceListDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}			
		$houseType = implode(',', $this->houseType);
		$arrConds = array("CreatorId IN ({$accountIds})","OperationType = 'user-add'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'"); //缺少时间条件

		$arrConds1 = array("CreatorId IN ({$accountIds})","OperationType = 'user-delete'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'");
		try{
			$userAddHouse =   $objDao->getOPHouseList($accountIds,$arrConds);//实时新增 
			$userDelHouse =   $objDao->getOPHouseList($accountIds,$arrConds1);//实时删除 
			if(!empty($userAddHouse) && !empty($userDelHouse)){
				foreach($userAddHouse as $key =>$value){
					foreach($userDelHouse  as $value1){
						if($value['CreatorId'] == $value1['CreatorId'] && $value['Type'] == $value1['Type'] && $value['HouseId'] == $value1['HouseId']){
							unset($userAddHouse[$key]);		
						} 
					}		
				}	
			}
			$result = array();
			$addHouse = array();
			foreach( $userAddHouse as $value){
				$arrConds =  array("account_id = {$value['CreatorId']}","house_id = {$value['HouseId']}","type  IN ({$houseType})");				
				$fields = array("account_id","biz_type","type");
				$list = $objSourceListDao->selectAllInfo($fields, $arrConds);
				if(!empty( $list)){
					if($type == $list[0]['biz_type']){
						$result[$type][$list[0]['account_id']][] = $list;
					}
				}
			}
			foreach($result[$type]  as $key => $value){
				$addHouse[] = array('account_id' => $key,'count' =>count($value)); 
			} 		
			$this->data = $addHouse;
		}catch(Exception $e){
			$this->data['errorno'] = $e->getCode(); 
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}
*/
	/** 
	 * 从house_source_operation表中实时取出精品和放心房的刷新数量
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
	protected function getUserRefresh($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Source_PremierSourceOperation');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}		
		$houseType = implode(',', $this->houseType);
		$this->startTime =  strtotime($this->dateStr);
		$this->endTime   = time();	
		if(1 == $type){
			$arrConds = array("CreatorId IN ({$accountIds})","OperationType = 'user-add-refresh'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'");
			$arrConds1 = array("CreatorId IN ({$accountIds})","OperationType = 'user-del-refresh'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'");			
		}else{
			$arrConds = array("CreatorId IN ({$accountIds})","OperationType = 'user-add-refresh-assure'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'");
			$arrConds1 = array("CreatorId IN ({$accountIds})","OperationType = 'user-del-refresh-assure'","type IN ({$houseType})","CreatedTime >= '".$this->dateStr."'");			
		}
		try{
			$refreshData =   $objDao->getOPCountByAccountId($accountIds,$arrConds);//实时新增刷新 
			$delrefreshData =   $objDao->getOPCountByAccountId($accountIds,$arrConds1);//删除实时刷新
			if(!empty($refreshData) && !empty($delrefreshData)){
				foreach($refreshData as &$value){
					foreach($delrefreshData as $value1){
						if($value['CreatorId'] == $value1['CreatorId']){
							$value['count'] = (int)($value['count'] - $value1['count']);
						}
					}	
				}
			}
			$this->data = $refreshData;
		}catch(Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errorno'] = $e->getCode();
		}
		return  $this->data;
	}
	/** 
	 * 从house_source_operation表中实时取出精品和放心房的新增房源数量和刷新数量
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
	protected function getSourceOperationRealDataTime($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Source_PremierSourceOperation');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}	
		try{
			$reportData['list']['user-add-refresh'] = $this->getUserRefresh($accountIds,$type);//实时刷新
			//$reportData['list']['user-add'] = $this->getUserAddHouse($accountIds,$type);
			$reportData['list']['user-add'] =  $this->getUserAddHouse($accountIds,$type);
			$this->data = $reportData;
		}catch(Exception $e){
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();	
		}
		return $this->data;
	//	selectGroupbyCreatorId
	}
	/** 
	 * 获取精品和放心房的推广房源数量
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
	protected function getAccountPremierReportListPremierRealDataTime($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Sourcelist_PremierList');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}
		$houseType = implode(',', $this->houseType);
		$countType = implode(',', $this->countType);
		if(1 == $type){
			$premier_status = "premier_status = 2";
		}else{
			$premier_status = "premier_status = 102";
		}
		$arrConds = array(" account_id IN ({$accountIds})","$premier_status","listing_status = 1","type IN ({$houseType})");
		try{
			$reportData['list']= $objDao->getPostCountByAccountId($accountIds,$arrConds);
			$this->data['data'] = $reportData;
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;	
	}
	/** 
	 * 获取精品和放心房的房源数数量 为推广和已推广的和
	 * @param unknown $accountIds
	 * @param unknown $$type 1 表示精品 2表示放心房
	 * @param string $desc              功能描述
	 * @return 
	 */
	protected function getAccountPremierReportListRealDataTime($accountIds,$type){
		$objDao = Gj_LayerProxy::getProxy('Service_Data_Sourcelist_PremierList');
		if (!is_array($accountIds)) {
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
			return $this->data;
		}   
		if (is_array($accountIds)) {
			$accountIds = join(',', $accountIds);
		}   
		$houseType = implode(',', $this->houseType);
		$countType = implode(',', $this->countType);	
		if(1 == $type){
			$premier_status = "premier_status IN ( 0, 2)";
		}else{
			 $premier_status = "premier_status IN ( 100,102)";
		}
        $arrConds = array(" account_id IN ({$accountIds})","$premier_status","listing_status = 1","type IN ({$houseType})");
		try{
			$reportData['list']= $objDao->getPostCountByAccountId($accountIds,$arrConds);
			$this->data['data'] = $reportData;
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;
	}

     /**
     * {{{ public getAccountPremierReportList
     * @params accountIds array
     * @params houseType array
     * @params countType array
     * @params sDate string
     * @parmas eDate string
     * @params orderField string
     * @params order DESC | ASC 
     * @desc 得到账号列表精品或者竞价按照账号分组的统计信息
     */
    public function getAccountPremierReportList($accountIds){
         if (!is_array($accountIds)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        $reportDateStr = $this->getDataStr($stime,$etime);
        
        if (is_array($accountIds)) {
            $accountIds = join(',', $accountIds);
        }
        
        $houseType = implode(',', $this->houseType);
        $countType = implode(',', $this->countType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
        //条件拼接
        $arrConds = array("accountId IN ({$accountIds})","ReportDate IN ({$reportDateStr})","houseType IN ({$houseType})","countType IN ({$countType})");
        if (empty($this->order)) {
            $this->order['order'] = 'DESC';
            $this->order['orderField'] = 'ReportDate';
        }
        try {
            $reportData = $objDao->getAccountPremierReportList($arrConds,$this->eDate);
            //这几个字段求最后一天的数据
            if (count($this->countType)>1 || !in_array($countType, array(7,8,9))) {
            	unset($arrConds[1]);
	            $arrConds['ReportDate ='] = $etime;
	            $appends = 'GROUP BY AccountId ORDER BY NULL';
	            $ret = $objDao->selectByPage(array('AccountId AS account_id','SUM(HouseTotalCount) as house_total_count','LastLoginTime as last_login_time'),$arrConds,null,null,array(),$appends);
	            $reportData['list'] = $this->mergeDataByAccountId($reportData['list'],$ret);
            }
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
        
    }
    /*}}}*/
     /**
     * {{{ public getAccountAssureReportList
     * @params accountIds array
     * @params houseType array
     * @params sDate string
     * @parmas eDate string
     * @params orderField string
     * @params order DESC | ASC 
     * @desc 得到账号列表放心房按照账号分组的统计信息
     */
    public function getAccountAssureReportList($accountIds){
        if (!is_array($accountIds)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
            return $this->data;
        }

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        $reportDateStr = $this->getDataStr($stime,$etime);
        
        if (is_array($accountIds)) {
            $accountIds = join(',', $accountIds);
        }
        
        $houseType = implode(',', $this->houseType);
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountReportV2');
        //条件拼接
		$arrConds = array("account_id IN ({$accountIds})","report_date IN ({$reportDateStr})","house_type IN ({$houseType})","report_type = 4");
		$this->order['order'] = 'DESC';
		$this->order['orderField'] = 'report_date';
		try {
            $reportData = $objDao->getAccountAssureReportList($arrConds);
            unset($arrConds[1]);
            $arrConds['report_date ='] = $etime;
            $appends = 'GROUP BY account_id ORDER BY NULL';
            $ret = $objDao->selectByPage(array('account_id AS account_id','SUM(house_total_count) as house_total_count'),$arrConds,null,null,array(),$appends);
            $reportData['list'] = $this->mergeDataByAccountId($reportData['list'],$ret);
            $this->data['data'] = $reportData;
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
    //{{{获得新增并推广数  目前只有德佑有数据  2014年12月2日
    public function getTagAndAddPremierCount($accountIds){
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
        #add_premier_count  新增并推广房源数
        #system_tag_count  特色标签使用数    在2014年11月25日 止只有德佑这个公司才有数据
        $arrFields = array('account_id as account_id',
            'SUM(house_count) as add_premier_count,
			SUM(tags_count) as system_tag_count,
			SUM(premier_count) as online_housetotal'
        );

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        $reportDateStr = $this->getDataStr($stime,$etime);
        
        $countType = implode(',',$this->countType);
        $arrConds = array(
        	"city_id =-1",
            "report_date IN ({$reportDateStr})",
            "count_type IN ({$countType})",
            "opt_type =" => 1,// opt_type等于1表示刷新数据
        );

        if (isset($this->houseType) && is_array($this->houseType)) {
            $houseType = array_unique($this->houseType);
            $houseType = implode(',', $houseType);

            $arrConds[] = "house_type IN ({$houseType})";
        }


        if (!empty($accountIds) && is_array($accountIds)) {
            $accountIds = implode(',',$accountIds);
            $arrConds[] = "account_id in ({$accountIds})";
        }

        $appends = 'GROUP BY account_id  ORDER BY NULL';
        try{
            $this->data['data'] = $objDao->selectByPage($arrFields,$arrConds,null,null,array(),$appends);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
    //}}}
    /*}}}*/
    public function getAccountHoursReport($accountIds= array()){
        $objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
        #add_premier_count  新增并推广房源数
        #system_tag_count  特色标签使用数    在2014年11月25日 止只有德佑这个公司才有数据
        $arrFields = array('account_id',
            'SUM(h0) as h0,SUM(h1) as h1,SUM(h2) as h2,
        SUM(h3) as h3,SUM(h4) as h4,SUM(h5) as h5,SUM(h6) as h6,SUM(h7) as h7,
        SUM(h8) as h8,SUM(h9) as h9,SUM(h10) as h10,SUM(h11) as h11,
        SUM(h12) as h12,SUM(h13) as h13,SUM(h14) as h14,SUM(h15) as h15,
        SUM(h16) as h16,SUM(h17) as h17,SUM(h18) as h18,
        SUM(h19) as h19,SUM(h20) as h20,SUM(h21) as h21,SUM(h22) as h22,SUM(h23) as h23');

        $stime = strtotime($this->sDate);
        $etime = strtotime($this->eDate);
        
        $countType = implode(',',$this->countType);
        $arrConds = array(
            "report_date >="=>$stime,
            "report_date <="=>$etime,
            "count_type IN ({$countType})",
            "opt_type = " => 1, 
        );

        if (isset($this->houseType) && is_array($this->houseType)) {
            $houseType = array_unique($this->houseType);
            $houseType = implode(',', $houseType);

            $arrConds[] = "house_type IN ({$houseType})";
        }


        if (!empty($accountIds) && is_array($accountIds)) {
            $accountIds = implode(',',$accountIds);
            $arrConds[] = "account_id in ({$accountIds})";
        }
        
        $appends = 'GROUP BY account_id  ORDER BY NULL';

        try{
            $this->data['data'] = $objDao->selectByPage($arrFields,$arrConds,null,null,array(),$appends);
        } catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }

        return $this->data;
    } 
    //{{{getOverdueAccountList
    /**
     * 获取7天之内到期并且有推广房源的经纪人
     * @param unknown $whereConds
     * @param unknown $arrFields
     * @param number $page
     * @param number $pageSize
     * @param unknown $orderArr
     */
    public function getOverdueAccountList($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
    	$objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountGeneralstatReport');
    	if (!count($arrFields)) {
    		$arrFields = array('AccountId', 'CompanyId', 'CompanyName');
    	}
    	$arrConds = array(
    		'ReportDate ='=>strtotime('Yesterday'),
            'HouseBiddingMode ='=> 1,
            'CountType ='=> 1,
    	);
        if(isset($whereConds['PremierCount']) && intval($whereConds['PremierCount']) >= 0){
        	$arrConds['PremierCount >'] = $whereConds['PremierCount'];
        }
        if(isset($whereConds['CityId']) && intval($whereConds['CityId'])>=0){
        	$arrConds['AccountCityId ='] = $whereConds['CityId'];
        }
    	if (!empty($whereConds['s_premier_expire'])) {
            $arrConds['PremierExpireTime >='] = $whereConds['s_premier_expire'];
        }else {
        	$arrConds['PremierExpireTime >='] = time();
        }
        if(!empty($whereConds['e_premier_expire'])){
        	$arrConds['PremierExpireTime <='] = $whereConds['e_premier_expire'];
        }
    	try{
    		$res = $objDao->selectOverdueAccountByPage($arrFields, $arrConds, $page, $pageSize, $orderArr, NULL, $this->eDate);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	if ($res === false) {
    		Gj_Log::warning($objDao->getLastSQL());
    		$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    		$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	}else{
    		$this->data['data'] = $res;
    	}
    	return $this->data;
    }//}}}
    public function getOverdueAccountListByCache($whereConds, $arrFields=array(), $page=1, $pageSize=30, $orderArr=array()){
        $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
       $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
      $this->data['data'] = array();
      return $this->data;
        $obj = Gj_LayerProxy::getProxy("Service_Data_HouseReport_AccountReport");
         return $obj->getOverdueAccountList($whereConds, $arrFields=array(), $page, $pageSize, $orderArr);
    }
    /**
     * $func string 当前调用的函数名
     * $args mixed  调用这个函数，传递的参数
     */
    public function getKey($func, $args){
        if ($func === 'getOverdueAccountList') {
            return 'getOverdueAccountListByCache_' . $args[0]['CityId'];
        }
    }

    /*
     * 获取帐号精品或者竞价和放心房report信息
     * @params $accountId Int   帐号id
     * @params $sDate Int       开始时间
     * @params $eDate Int       结束时间
     */
    public function getAgentReportDetail($AccountId, $sDate, $eDate){
        $this->sDate = $sDate;
        $this->eDate = $eDate;
        $this->houseType = array(0);    //所有房源类型
        $this->countType = array(1, 6, 7, 8, 9);    //所有统计类型
        $premierReport = $this->getAccountPremierReportDetail($AccountId);
        $assureReport = $this->getAccountAssureReportDetail($AccountId);
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->data['data'] = array($premierReport, $assureReport);
        return $this->data;
    }
    public function getAccountAssureReportListBySum($accountIds){
    	if (!is_array($accountIds)) {
    		$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    		$this->data['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
    		return $this->data;
    	}
    
    	$stime = strtotime($this->sDate);
    	$etime = strtotime($this->eDate);
    	$reportDateStr = $this->getDataStr($stime,$etime);
    
    	$this->data['data'] = array();
    	$houseType = implode(',', $this->houseType);
    	$objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountReportV2');
    	if (is_array($accountIds)) {
    		$accountIdChunk = array_chunk($accountIds, 500);
    	}
    	$this->data['data']['list'] = array();
    	foreach ($accountIdChunk as $accountIdArr){
    		$accountIds = join(',', $accountIdArr);
    		//条件拼接
    		$arrConds = array("account_id IN ({$accountIds})","report_date IN ({$reportDateStr})","house_type IN ({$houseType})","report_type = 4");
    		try {
    			$reportData = $objDao->getAccountAssureReportListBySum($arrConds);
    			$this->data['data']['list'] = array_merge($this->data['data']['list'],$reportData['list']);
    			unset($reportData);
    		} catch(Exception $e) {
    			$this->data['errorno'] = $e->getCode();
    			$this->data['errormsg'] = $e->getMessage();
    		}
    	}
    	$this->data['data']['count'] = count($this->data['data']['list']);
    	return $this->data;
    }
    public function getAccountHoursReportBySum($accountIds= array()){
    	$objDao = Gj_LayerProxy::getProxy('Dao_Housereport_HouseAccountHours');
    	#add_premier_count  新增并推广房源数
    	#system_tag_count  特色标签使用数    在2014年11月25日 止只有德佑这个公司才有数据
    	$arrFields = array('account_id',
    	'h0 AS h0,h1 AS h1,h2 AS h2,
        h3 AS h3,h4 AS h4,h5 AS h5,h6 AS h6,h7 AS h7,
        h8 AS h8,h9 AS h9,h10 AS h10,h11 AS h11,
        h12 AS h12,h13 AS h13,h14 AS h14,h15 AS h15,
        h16 AS h16,h17 AS h17,h18 AS h18,
        h19 AS h19,h20 AS h20,h21 AS h21,h22 AS h22,h23 AS h23');
    
    	$stime = strtotime($this->sDate);
    	$etime = strtotime($this->eDate);
    	$reportDateStr = $this->getDataStr($stime,$etime);
    
    	$countType = implode(',',$this->countType);
    	$arrConds = array(
    			"report_date IN ({$reportDateStr})",
    			"count_type IN ({$countType})",
    			"opt_type = " => 1,
    			"city_id =" => -1,
    			//"city_id =" => $this->city_id,
    	);
    	if (isset($this->houseType) && is_array($this->houseType)) {
    		$houseType = array_unique($this->houseType);
    		$houseType = implode(',', $houseType);
    		$arrConds[] = "house_type IN ({$houseType})";
    	}
    	if (!empty($accountIds) && is_array($accountIds)) {
    		$accountIds = implode(',',$accountIds);
    		$arrConds[] = "account_id IN ({$accountIds})";
    	}
    	unset($accountIds);
    	$this->data['data'] = array();
    	try{
    		$reportData = $objDao->selectByPage($arrFields,$arrConds,null,null,array());
    		foreach ($reportData as $row){
    			if (in_array($row['account_id'], array_keys($this->data['data']))) {
    				foreach ($row as $key=>$v){
    					if(!in_array($key, array('account_id'))){
    						$this->data['data'][$row['account_id']][$key]+=trim($v);
    					}
    				}
    			}else{
    				$this->data['data'][$row['account_id']] = $row;
    			}
    		}
    		unset($reportData);
    	} catch(Exception $e) {
    		$this->data['errorno'] = $e->getCode();
    		$this->data['errormsg'] = $e->getMessage();
    	}
    	return $this->data;
    }

    protected function getDataStr($stime,$etime){
    	$reportDate = array();
    	for ($i = $stime;$i <= $etime;$i+=86400) {
    		$reportDate[date("Y-m-d",$i)] = $i;
    	}
    	$reportDateStr = implode(',',$reportDate);
    	return $reportDateStr;
    }
}
