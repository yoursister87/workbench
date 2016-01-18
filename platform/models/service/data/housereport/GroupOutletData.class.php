<?php
/*
 * File Name:GroupOutletData.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 * description:门店级别账户数据获取
 */

class Service_Data_HouseReport_GroupOutletData
{
    //需要指定一个唯一的索引字段
	const REAL_DATA_TIME = 9;  //表示实时数据查询
    protected $indexFieldName = 'account_id';
    //数据库中的值=>换换为新的键
    protected  $premierFields = array('account_id'=>'account_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv','login_count'=>'login_count','mult_img_house_count'=>'mult_img_house_count',
        'last_login_time'=>'last_login_time', 'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count'
    );
    protected  $assureFields = array('account_id'=>'account_id','house_total_count'=>'house_total_count','house_count'=>'house_count',
        'premier_count'=>'premier_count','refresh_count'=>'refresh_count','account_pv'=>'account_pv','login_count'=>'login_count','mult_img_house_count'=>'mult_img_house_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count', "mult_img_house_total_count"=>"mult_img_house_total_count",#历史推广房源
    );
    /* protected  $bidFields = array('account_id'=>'account_id','account_pv'=>'account_pv','bid_count'=>'bid_count',
        'similar_house_count'=>'similar_house_count','illegal_house_count'=>'illegal_house_count','comment_count'=>'comment_count'
    ); */
    protected  $bidFields = array('account_id'=>'account_id','house_count'=>'bid_house_count','amount_count'=>'bid_amount_count',
    		'account_pv'=>'bid_account_pv',
    );
    protected  $stickFields = array('account_id'=>'account_id','house_count'=>'stick_house_count','amount_count'=>'stick_amount_count',
		'account_pv'=>'stick_account_pv',
	);
    protected  $dxtgFields = array('account_id'=>'account_id','house_count'=>'dxtg_house_count','amount_count'=>'dxtg_amount_count',
    		'account_pv'=>'dxtg_account_pv',
    );



    //组织结构数据返回结构
    protected $productData = array(
        Service_Data_HouseReport_GroupData::TOTAL => array(
            'title'=>'汇总数据',
            'title_data'=>array(
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广',
                //'premier_scale'=>'推广率', #这一期去掉
                'refresh_count'=>'刷新',
                //'refresh_scale'=>'刷新率',#这一期去掉
				  'account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ), 
        Service_Data_HouseReport_GroupData::PREMIER => array(
            'title'=>'精品',
            'title_data'=>array(
                'last_deposit_time'=>'开始时间',
                'premier_end_time'=>'到期时间',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'推广','premier_scale'=>'推广率',
				'refresh_count'=>'刷新','refresh_scale'=>'刷新率','account_pv'=>'点击量',
            ),
            'group'=>1  #组别
        ),    
        Service_Data_HouseReport_GroupData::ASSURE => array(
            'title'=>'放心房',
            'title_data'=>array(
                'last_deposit_time'=>'开始时间',
                'premier_end_time'=>'到期时间',
                'house_total_count'=>'房源总数',
                'house_count'=>'新增','premier_count'=>'展示','premier_scale'=>'展示率',
				'refresh_count'=>'刷新','refresh_scale'=>'刷新率','account_pv'=>'点击量',
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
         Service_Data_HouseReport_GroupData::STICK=> array(
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
            'group'=>2  #组别
        ),
        Service_Data_HouseReport_GroupData::FRAME_ORG_DATA => array(
            'title'=>'组织数据',
            'title_data'=> array(
                'login_count'=>'登录次数'
            ),
            'group'=>2
        ),
        Service_Data_HouseReport_GroupData::ACCOUNT_ID => array(
            'title'=>'经纪人ID',
            'title_data'=> array(
                'accountid_show'=>'ID'
            ),
            'group'=>3
        ),
        Service_Data_HouseReport_GroupData::BUSINESS_SCOPE => array(
            'title'=>'端口类别',
            'title_data'=> array(
                'business_scope_str'=>'端口类别'
            ),
            'group'=>3
        ),
        Service_Data_HouseReport_GroupData::ACCOUNT_EMAIL => array(
			'title'=>'经纪人邮箱',
			'title_data'=> array(
				'account_email'=>'经纪人邮箱'
			),
			'group'=>3
		),
		Service_Data_HouseReport_GroupData::CENTER_BALANCE => array(
			'title'=>'会员中心余额',
			'title_data'=>array(
				'userCenterBalance'=>'会员中心余额',
			),   
			'group'=>3  #组别		
		),
		Service_Data_HouseReport_GroupData::BID_BALANCE => array(
			'title'=>'竞价余额',
			'title_data'=>array(
				'userBidBalance'=>'竞价余额',
			),
			'group'=>3  #组别       
		),	
	);
    //不在统计表中需要添加的
    protected $otherFields = array('user_id'=>'user_id','last_deposit_time'=>'last_deposit_time','premier_end_time'=>'premier_end_time','biz_text'=>'biz_text');
    
	protected $deyouFields = array('system_tag_count'=>'system_tag_count','max_refresh_count'=>'max_refresh_count','add_premier_count'=>'add_premier_count','max_freerefresh_count'=>'max_freerefresh_count','online_premier' => 'online_premier','online_housetotal' => 'online_housetotal');
   //获得求钧值字段 新增，推广，刷新，点击求均值
   protected $avgFields = array('house_count','premier_count','refresh_count','account_pv','login_count','premier_scale','refresh_scale','mult_img_house_count','system_tag_count','max_refresh_count','max_freerefresh_count','mult_img_house_total_count');

   protected $sumFields  = array('house_count','premier_count','bid_count','refresh_count',
           'login_count','account_pv','tuiguang_max_count','refresh_max_count',
           'similar_house_count','illegal_house_count','comment_count','house_total_count',
           'system_tag_count','add_premier_count','mult_img_house_count'
       );
    //这两个字段没有从统计报表数据库中取，需要按照日期求和  例如从 bussiness_account_info表取出
    protected $noReportSumFields = array('max_refresh_count','max_freerefresh_count');
    //求比例  除数=>被除数
    /*
     *@codeCoverageIgnore
     */
    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }

    protected $scaleFields = array(
        'premier_scale'=> array('premier_count'=>'tuiguang_max_count'),
        'refresh_scale'=>array('refresh_count'=>'refresh_max_count')
    );
    //添加比例字段
    /*
     *@codeCoverageIgnore
     */
    private function getScaleChangefield($field){
        $tmp = array('premier_scale'=>'premier_scale','refresh_scale'=>'refresh_scale',
            'tuiguang_max_count'=>'tuiguang_max_count','refresh_max_count'=>'refresh_max_count');
        return array_merge($field,$tmp);
    }


    private function getBusinessScopeByOutlet($businessScopeList){
        $result = array();
        foreach ($businessScopeList as $scope=>$accountIdList) {

            foreach ($accountIdList as $accountId){
                if (!isset($result[$accountId])) {
                    $result[$accountId][$this->indexFieldName] = $accountId;
                    $result[$accountId]['biz_text'] = HousingVars::$bizTxt[$scope];
                } else {
                    $result[$accountId]['biz_text'] = $result[$accountId]['biz_text'].'/'.HousingVars::$bizTxt[$scope];
                }
            }
        }
        return $result;
    }
    //{{{mergeDataListInfo 合并数据 array['data']['list']  array['data']['list'] 这样的数据 按照accountId做键
    private function mergeDataListInfo($data1,$data2){
        $list1 = $data1['data']['list'];
        $list2 = $data2['data']['list'];

        $maxList = (count($list1) >= count($list2))?$list1:$list2;
        $minList = (count($list1) < count($list2))?$list1:$list2;
        $result = array();
        foreach ($maxList as $accountId=>$item) {
            $tmp = $minList[$accountId];
            if (!isset($tmp)) {
                $tmp = array();
            }
            $result[$accountId] = array_merge($item,$tmp);
        }
        $newList['data']['list'] = $result;
        return $newList;
    }

    //{{{ getTagAndaddPremier 得到优质的标签及新增并推广房源数  目前只有德佑可以用
    public  function getTagAndaddPremier($params,$groupAccountList){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_AccountReport');

        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }

        $dataService->setVal('countType',$params['countType']);
        foreach ($groupAccountList as $bizScope=>$businessInfo) {
            $houseType = HousingVars::$bizToTypes[$bizScope];
            if (empty($houseType)) {
                continue;
            }

            $accountIds = array_keys($businessInfo);

            if (isset($params['houseType'])) {
                $dataService->setVal('houseType',$houseType);
            }

            $bizScopeList[$bizScope] = $accountIds;

            $tmp = $dataService->getTagAndAddPremierCount($accountIds);
            $tmp2['data']['list'] = $tmp['data'];
            $totalData[$bizScope]  = $this->mergeReportAndOther($tmp2,$businessInfo);
        }
        $dataList = $this->mergeReportByField($totalData,$params);
        //$dataList = $this->mergeReportDataByField($totalData,$params);
        return $dataList;
    }
    //}}}

    public function groupAccountUseBusinessScope($params){
        $hma = Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
        //以精品的订单为准
        $whereConds = array();

        $whereConds['businessScope'] = $params['businessScope'];
        if ($params['islost'] === true) {
            $whereConds['effective'] = false;
            //一般端口的过期时间在 一天的 23:59:59秒
            $whereConds['maxTime']['<=maxEndTime'] = strtotime($params['date']['eDate']) + 3600 * 24 -1;
            $whereConds['maxTime']['>=maxEndTime'] = strtotime($params['date']['sDate']);
        } else {
            $whereConds['effective'] = true;
        }
        if (is_array($params['countType'])&&!empty($params['countType'])) {
            $whereConds['countType'] = reset($params['countType']);
        }
        //按照时间搜索框排除不满足条件的日期
        $whereConds['inTime']['<=minBeginTime'] = strtotime($params['date']['eDate']);
        $ret = $hma->getBusinessInfoByAccountIds($params['accountIds'],$whereConds);
        if ($ret['errorno']){
            throw new Gj_Exception($ret['errorno'],$ret['errormsg']);
        }

        $data = $ret['data'];
        $result = array();
        foreach ($data as $accountInfo) {
            $tmp = array(
                $this->indexFieldName=>$accountInfo['AccountId'],
            	'user_id' =>$accountInfo['UserId'],
                //端口开始时间
                'inDurationBeginTime'=>$accountInfo['InDurationBeginTime'],
                //端口结束时间
                'inDurationEndTime'=>$accountInfo['InDurationEndTime'],
                'max_freerefresh_count'=>$accountInfo['MaxFreeRefreshCount'],#精品目前的刷新是记录到免费中
                'max_refresh_count'=>$accountInfo['MaxChargeRefreshCount'],#放心房存在付费和免费刷新
				'online_premier' => $accountInfo['MaxPremierCount'],//最大推广数
            );
            //端口失效使用 最新开始时间字段
            if ($params['islost'] === true) {
                $tmp['last_deposit_time']=empty($accountInfo['MinBeginTime'])?'无记录':date('Y-m-d',$accountInfo['MinBeginTime']);//端口的最新开始时间
                $tmp['premier_end_time']= empty($accountInfo['MaxEndTime'])?'无记录':date('Y-m-d',$accountInfo['MaxEndTime']);//端口的最新开始时间
            } else {
                $tmp['last_deposit_time']=empty($accountInfo['InDurationBeginTime'])?'无记录':date('Y-m-d',$accountInfo['InDurationBeginTime']);//端口的最新开始时间
                $tmp['premier_end_time']=empty($accountInfo['InDurationEndTime'])?'无记录':date('Y-m-d',$accountInfo['InDurationEndTime']);//端口的最新开始时间
            }
            $result[$accountInfo['BussinessScope']][$accountInfo['AccountId']] = $tmp;
        }
        
        //$this->accountgroupList = $result;

        return $result;
    }

    private function sumNoReprtData($dataList,$params){
        //求和的字段
        $mergefield = $this->noReportSumFields;
        $spaceDay = strtotime($params['date']['eDate'])/3600/24 - strtotime($params['date']['sDate'])/3600/24;
        $spaceDay = ($spaceDay <= 0)?1:($spaceDay+1);
       ;
        foreach ($dataList as $index=>&$item) {
            foreach ($mergefield as $field) {
                if (isset($item[$field])) {
                    $item[$field] = $item[$field] * $spaceDay;
                }
            }
        }
       return $dataList;
    }

    private function sumReportData($oldData,$value){
        //求和的字段
        $mergefield = $this->sumFields;

        foreach ($mergefield as $field) {
            if (isset($oldData[$field]) || isset($value[$field]))
            $oldData[$field] = floatval($oldData[$field]) + floatval($value[$field]);
        }


        return $oldData;
    }
    //按照businessScope合并用户的数据
    public function mergeReportDataByField($dataList,$params){
        if (empty($dataList)) {
            return $dataList;
        }

        $result = array();
        $newList = array();
        foreach ($dataList as $bizScope => $listData) {
            $data = $listData['data'];
            $list = $data['list'];
            if (empty($list)) {
                continue;
            }
            foreach ($list as $item) {
                $accountId = $item[$this->indexFieldName];
                //有数据才能求和
                if (isset($newList[$accountId])){
                    //对字段进行求和
                    $newList[$accountId] =  $this->sumReportData($newList[$accountId],$item);
                } else {
                    $newList[$accountId] = $item;
                }
            }
        }
        $newList = $this->sumNoReprtData($newList,$params);
        $result['data']['list'] = $newList;
        return $result;
    }
    //合并数据
    protected function mergeReportByField($dataList,$params){
    	if (empty($dataList)) {
    		return $dataList;
    	}
    	
    	$result = array();
    	$newList = array();
    	foreach ($dataList as $bizScope => $listData) {
    		$data = $listData['data'];
    		$list = $data['list'];
    		if (empty($list)) {
    			continue;
    		}
    		foreach ($list as $item) {
    			$newKey = $item[$this->indexFieldName].'_'.$bizScope;
    			$item['biz_text'] = HousingVars::$bizTxt[$bizScope];
    			$newList[$newKey] = $item;
    		}
    	}
    	$result['data']['list'] = $newList;
    	return $result;
    }
    
    public function mergeReportAndOther($dataList,$otherInfo){
        $repotList = !empty($dataList['data']['list'])?$dataList['data']['list']:array();

        $status = (count($repotList) >= count($otherInfo));

        if (empty($listData) && empty($otherInfo)) {
            return $dataList;
        }

        foreach($repotList as &$infoList) {
            $index = $infoList[$this->indexFieldName];
            if (isset($otherInfo[$index])) {
                $result[$index] = array_merge($infoList,$otherInfo[$index]);
            }
        }

        //如果长度不一致 则补充other中的数据
        if ($status === false) {
            foreach ($otherInfo as &$other)  {
                $index = $other[$this->indexFieldName];
                if (!isset($result[$index])) {
                    $result[$index] = array_merge($other);
                }
            }
        }

        $dataList['data']['list'] = $result;
        return $dataList;
    }
    /*{{{getfatherOrgId 通过id取得pid为0的orgid*/
    /**
     *
     */
    private function getfatherOrgId($companyId){
        $ret = array();
        $hma = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $conds['company_id'] = $companyId;
        $conds['level'] = 1;
        $conds['pid'] = 0;
        $ret = $hma->getOrgInfoListByPid($conds);
        if ($ret['errorno']){
            throw new Gj_Exception($ret['errorno'],$ret['errormsg']);
        }
        $data = $ret['data'][0];
        return $data['id'];
    }
    /*}}}*/
    /*{{{getHouseMangerAccountByLevel orgid 和 level取得相应的等级的数据*/
    private function getHouseMangerAccountByLevel($orgId,$level){
        $hma = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $ret = $hma->getChildTreeByOrgId($orgId,$level,array(),1,null);
        $data = $ret['data']['list'];
        $result = array();
        foreach ($data as $item) {
            $result[$item['id']]['id'] = $item['id'];
            $result[$item['id']]['title'] = $item['title'];
            $result[$item['id']]['customerId'] = $item['customer_id'];
            $result[$item['id']]['pid'] = $item['pid'];
        }
        return $result;
    }
    /*}}}*/
    /*{{{houseMangerAccount2CustomerIdKey 返回一个customerid做键的数据*/
    private function houseMangerAccount2CustomerIdKey($list){
        $result = array();
        foreach ($list as $item) {
            $result[$item['customerId']]['customerId'] = $item['customerId'];
            $result[$item['customerId']]['origId'] = $item['id'];
        }
        return $result;
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
    protected function getReportDate($params, $data){
        $res = array();
        /* foreach ($params['accountIds'] as $key => $value) {
            $res['data'][$value][$this->indexFieldName] = $value;
            $res['data'][$value]['report_start_time'] = $params['date']['sDate'];
            $res['data'][$value]['report_end_time'] = $params['date']['eDate'];
        } */
        $tmpData = $data[Service_Data_HouseReport_GroupData::PREMIER]['data'];
        foreach ($tmpData as $key => $row) {
        	$res['data'][$key][$this->indexFieldName] = $row['account_id'];
        	$res['data'][$key]['report_start_time'] = $params['date']['sDate'];
        	$res['data'][$key]['report_end_time'] = $params['date']['eDate'];
        }
        return $res;
    }
    /*}}}*/
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
    /*{{{getAccountData 得到账号信息*/
    /**
     *
     */
    protected function getAccountData($params,$lineList,$data){
        $res = array();
        $newList = array();
        if ($data[Service_Data_HouseReport_GroupData::PREMIER]) {
            $newList =  $data[Service_Data_HouseReport_GroupData::PREMIER];
        } else {
            $newList =  $this->getPremierData($params);
        }
        //获得父pid为0的id
        $fid = $this->getfatherOrgId($params['companyId']);
        $areaList = $this->getHouseMangerAccountByLevel($fid,2);
        $plateList = $this->getHouseMangerAccountByLevel($fid,3);
        $outletList = $this->getHouseMangerAccountByLevel($fid,4);

        $customerList = $this->houseMangerAccount2CustomerIdKey($outletList);
        $res = array();
        /*
         * 传入的lineList 需要满足 index=>accountId的形式
         */
		foreach ($newList['data'] as $key => $row) {
			$customerId = $params['customerId'][$row['account_id']];
			$res['data'][$key][$this->indexFieldName] = $row['account_id'];
			//用户编号
			$res['data'][$key]['account_id'] = $params['accountIds'][$row['account_id']];
			//用户全称
			$res['data'][$key]['account_name'] = $params['accountName'][$row['account_id']];
			$outlet_name = $outletList[$customerList[$customerId]['origId']]['title'];
			//门店名称
			$res['data'][$key]['outlet_name'] = isset($outlet_name)?$outlet_name:'无';
			$plate_name = $plateList[$outletList[$customerList[$customerId]['origId']]['pid']]['title'];
			//板块名称
			$res['data'][$key]['plate_name'] = isset( $plate_name)? $plate_name:'无';
			//一级一级往上找 一次取得一次调用
			$area_name = $areaList[$plateList[$outletList[$customerList[$customerId]['origId']]['pid']]['pid']]['title'];
			$res['data'][$key]['area_name'] = isset($area_name)? $area_name:'无';
			$res['data'][$key]['phone'] = $params['phone'][$row['account_id']];
			$res['data'][$key]['owner_type'] = ($params['ownerType'][$row['account_id']]==1)?'自费':'公费';
			if ($params['islost'] === true) {
				$res['data'][$key]['status'] = '已过期';
			} else {
				$res['data'][$key]['status'] = Service_Data_HouseReport_GroupData::$accountStatus[$params['status'][$row['account_id']]];
			}
			$res['data'][$key]['login_count'] = isset($newList['data'][$key]['login_count'])?$newList['data'][$key]['login_count']:0;
			$res['data'][$key]['last_login_time'] = empty($newList['data'][$key]['last_login_time'])?'无记录':date('Y-m-d H:i:s',$newList['data'][$key]['last_login_time']);
		}
        /* foreach ($lineList as $index => $accountId) {
            $customerId = $params['customerId'][$accountId];
            $res['data'][$index][$this->indexFieldName] = $index;
            //用户编号
            $res['data'][$index]['account_id'] = $params['accountIds'][$accountId];
            //用户全称
            $res['data'][$index]['account_name'] = $params['accountName'][$accountId];
            $outlet_name = $outletList[$customerList[$customerId]['origId']]['title'];
            //门店名称
            $res['data'][$index]['outlet_name'] = isset($outlet_name)?$outlet_name:'无';
            $plate_name = $plateList[$outletList[$customerList[$customerId]['origId']]['pid']]['title'];
            //板块名称
            $res['data'][$index]['plate_name'] = isset( $plate_name)? $plate_name:'无';
            //一级一级往上找 一次取得一次调用
            $area_name = $areaList[$plateList[$outletList[$customerList[$customerId]['origId']]['pid']]['pid']]['title'];
            $res['data'][$index]['area_name'] = isset($area_name)? $area_name:'无';
            $res['data'][$index]['phone'] = $params['phone'][$accountId];
            $res['data'][$index]['owner_type'] = ($params['ownerType'][$accountId]==1)?'自费':'公费';
            if ($params['islost'] === true) {
                $res['data'][$index]['status'] = '已过期';
            } else {
                $res['data'][$index]['status'] = Service_Data_HouseReport_GroupData::$accountStatus[$params['status'][$accountId]];
            }
            $res['data'][$index]['login_count'] = isset($newList['data'][$index]['login_count'])?$newList['data'][$index]['login_count']:0;
            $res['data'][$index]['last_login_time'] = empty($newList['data'][$index]['last_login_time'])?'无记录':date('Y-m-d H:i:s',$newList['data'][$index]['last_login_time']);
        } */
        return $res;
    }
    /*}}}*/
   //{{{fieldAvg字段求平均值
   protected function fieldAvg($dataList,$params){
       $day = (strtotime($params['date']['eDate']) - strtotime($params['date']['sDate']))/3600/24 + 1;
       $day = ($day<=0)?1:$day;
       if (!isset($dataList['data'])) {
           return $dataList;
       }
       foreach ($dataList['data'] as $key => &$value) {
           foreach ($this->avgFields as $avgField) {
               if(isset($value[$avgField])) {
                   //保留2位有效数字
                   $value[$avgField] = round($value[$avgField]/$day,2);
               }
           }  
       }

       return $dataList;
   }
    //}}}
    //{{{ getAssureData data列表生成数据
    /** 
     * 获取 放心房结果 的数据 
     * @param unknown $params   
     * 
     *
     */
    protected function getAssureData($params){
        $dataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_AccountReport');
		$groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
		if(self::REAL_DATA_TIME == $params['level']){
			$params['date']['eDate'] =  date('Y-m-d H:m:i',time());
		}
        //设置为放心房  用来取用户订单详情
        $params['countType'] =array(2);
        $groupAccountList = $this->groupAccountUseBusinessScope($params);
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }
        //开通的订单
        $bizScopeList = array();
		$datatime =  HttpNamespace::getGET('level',-1);
        foreach ($groupAccountList as $bizScope=>$businessInfo) {
            $houseType = HousingVars::$bizToTypes[$bizScope];
            if (empty($houseType)) {
                continue;
            }

            $accountIds = array_keys($businessInfo);
            if (isset($params['houseType'])) {
                $dataService->setVal('houseType',$houseType);
			}
			$bizScopeList[$bizScope] = $accountIds;
			if($params['level']== self::REAL_DATA_TIME){
				$type = 2;//放心房 
				$tmp = $dataService->mergeRealDataTime($accountIds,$type); //获取精品的实时数据
			}else if(isset($params['downloadFilename'])) {	
				$tmp = $dataService->getAccountAssureReportListBySum($accountIds);
			}else{
				$tmp = $dataService->getAccountAssureReportList($accountIds);
			}
            $totalData[$bizScope]  = $this->mergeReportAndOther($tmp,$businessInfo);
        }
        $dataList = $this->mergeReportByField($totalData,$params);
        /* $dataList = $this->mergeReportDataByField($totalData,$params);
        $bizTextList = $this->getBusinessScopeByOutlet($bizScopeList);
        //合并数据
        $dataList  = $this->mergeReportAndOther($dataList,$bizTextList); */
        $fields = $this->assureFields;
        //对德佑这个公司做特殊处理 增加字段
        if ($params['companyId'] == 5800 && $params['level'] != 9) {
            $tagInfo = $this->getTagAndaddPremier($params,$groupAccountList);
            $dataList = $this->mergeDataListInfo($tagInfo,$dataList);
            $fields = array_merge($fields,$this->deyouFields);
        }
        //根据action 只有当全部经济人的时候才会走这里
        $bool = HttpNamespace::getGET('a') == 'ajax'?true:false;
        if ($groupdataService->isShowScale($params,$bool)) {
            $fields = $this->getScaleChangefield($fields);
        } else {
            //删除前端显示 刷新率和推广率
            unset($this->productData[Service_Data_HouseReport_GroupData::ASSURE]['title_data']['premier_scale']);
            unset($this->productData[Service_Data_HouseReport_GroupData::ASSURE]['title_data']['refresh_scale']);
        }
        $fields = array_merge($fields,$this->otherFields);
        $dataList = $groupdataService->getScale($dataList,$this->scaleFields);
        //拼接端口类型
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);
        //是否求平均值
        if($params['isavg']===true) {
           $newList = $this->fieldAvg($newList,$params);
        }
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
        //设置为精品 用来取用户订单详情
        $params['countType'] = array(1);
		if(self::REAL_DATA_TIME == $params['level']){
			$params['date']['eDate'] =  date('Y-m-d H:m:i',time());
		}
		$groupAccountList = $this->groupAccountUseBusinessScope($params);
		if (isset($params['date'])) {
			$dataService->setVal('sDate',$params['date']['sDate']);
			$dataService->setVal('eDate',$params['date']['eDate']);
		}
        $dataService->setVal('countType',array(1));
        //开通的订单
        $bizScopeList = array();
        foreach ($groupAccountList as $bizScope=>$businessInfo) {
            $houseType = HousingVars::$bizToTypes[$bizScope];
            if (empty($houseType)) {
                continue;
            }

            $accountIds = array_keys($businessInfo);

            if (isset($params['houseType'])) {
                $dataService->setVal('houseType',$houseType);
            }

            $bizScopeList[$bizScope] = $accountIds;
			if($params['level']== self::REAL_DATA_TIME){
				$type = 1;
				$tmp = $dataService->mergeRealDataTime($accountIds,$type); //获取精品的实时数据			
			}else{
				$tmp = $dataService->getAccountPremierReportList($accountIds); //获取精品的报表数据
			}
            //拼接端口的开始与过期时间
            $totalData[$bizScope]  = $this->mergeReportAndOther($tmp,$businessInfo);
        }
        $dataList = $this->mergeReportByField($totalData,$params);
        /* $dataList = $this->mergeReportDataByField($totalData,$params);
        //组成订单列表字符串
        $bizTextList = $this->getBusinessScopeByOutlet($bizScopeList);
        //拼接端口类型
        $dataList  = $this->mergeReportAndOther($dataList,$bizTextList); */

        $fields = $this->premierFields;
        //对德佑这个公司做特殊处理 增加字段
        if ($params['companyId'] == 5800 && $params['level'] != 9) {
            //设置为放心房
            $params['countType'] = array(1);
            $tagInfo = $this->getTagAndaddPremier($params,$groupAccountList);
            $dataList = $this->mergeDataListInfo($tagInfo,$dataList);
			
            $fields = array_merge($fields,$this->deyouFields);
        }
        //FANG-9822
        $bool = HttpNamespace::getGET('a') == 'ajax'?true:false;
        if ($groupdataService->isShowScale($params,$bool)) {
            $fields = $this->getScaleChangefield($fields);
        } else {
            //删除前端显示 刷新率和推广率
            unset($this->productData[Service_Data_HouseReport_GroupData::PREMIER]['title_data']['premier_scale']);
            unset($this->productData[Service_Data_HouseReport_GroupData::PREMIER]['title_data']['refresh_scale']);
        }
        $fields = array_merge($fields,$this->otherFields);

        $dataList = $groupdataService->getScale($dataList,$this->scaleFields);
        $newList = $groupdataService->changeData($dataList,$fields,$this->indexFieldName);

        //是否求平均值
        if($params['isavg']===true) {
           $newList = $this->fieldAvg($newList,$params);
        }
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
    protected function getBidData($params,$count_type=1,$field=array()){
        $dataService = Gj_LayerProxy::getProxy("Service_Data_HouseReport_AccountReport");
        $groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
        $groupAccountList = $this->groupAccountUseBusinessScope($params);
        if (isset($params['date'])) {
            $dataService->setVal('sDate',$params['date']['sDate']);
            $dataService->setVal('eDate',$params['date']['eDate']);
        }

        $dataService->setVal('countType',array($count_type));
        //这个用户付费的端口去做查询
        foreach ($groupAccountList as $bizScope=>$businessInfo) {
            $houseType = HousingVars::$bizToTypes[$bizScope];
            if (empty($houseType)) {
                continue;
            }

            $accountIds = array_keys($businessInfo);
            if (isset($params['houseType'])) {
                $dataService->setVal('houseType',$houseType);
            }

            $totalData[$bizScope]  = $dataService->getAccountPremierReportList($accountIds);
        }
        //$dataList = $this->mergeReportDataByField($totalData,$params);
        $dataList = $this->mergeReportByField($totalData,$params);
        $newList = $groupdataService->changeData($dataList,$field,$this->indexFieldName);

        //是否求平均值
        if($params['isavg']===true) {
            $newList = $this->fieldAvg($newList,$params);
        }
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
	protected function getUserCenterBalanceMoney($userIds){
		$objUserCenter  = Gj_LayerProxy::getProxy('Dao_Gcrm_Interface_UserInterface');
		$userIdsMoney = array();
		if(!empty($userIds)){
			foreach($userIds as $value){
				$money = $objUserCenter->getUserCenterBalanceMoney($value);		
				$userIdsMoney[$value] =   $money ;
			}	
		}	
		return $userIdsMoney;
	}
    /*}}}*/
   	protected function getUserCenterBalance($params,$data){
		$objDao = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccount');
		if (is_array($params['accountIds'])) {
			$accountIds = join(',', $params['accountIds']);
		}
		$arrConds = array(" AccountId IN ({$accountIds})");
		$fields = array("AccountId","UserId");
		try{
			$userIdList  = $objDao->selectAllInfo($fields,$arrConds);
			foreach($userIdList as $value){
				$userIdToAccount[$value['UserId']] = $value['AccountId'];
				$AccountTouserId[$value['AccountId']] = $value['UserId'];
			}
			$userListMoney = $this->getUserCenterBalanceMoney(array_keys($userIdToAccount));
			$tmpData = $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE]['data'];
			foreach ($tmpData as  $key=>$row) {
				$tmpList[$key][$this->indexFieldName] = $row['account_id'];
				$tmpList[$key]['userCenterBalance'] = $userListMoney[$AccountTouserId[$row['account_id']]];
			}
			$this->data['data'] = $tmpList;
			return $this->data;
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;	
	} 
	protected function getBalanceMoney($userIds,$params){
		$objUserCenter  = Gj_LayerProxy::getProxy('Dao_Gcrm_Interface_UserInterface');
		$userIdsMoney = array();
		if(!empty($userIds)){
			foreach($userIds as $value){
				$money = $objUserCenter->getBalance($value,$params['city_code']);
				$userIdsMoney[$value] =   $money ;
			}
		}
		return $userIdsMoney;			
	}
	protected function getBidBalance($params,$data){
		$objDao = Gj_LayerProxy::getProxy('Dao_Gcrm_CustomerAccount');
		if (is_array($params['accountIds'])) {
			$accountIds = join(',', $params['accountIds']);
		}
		$arrConds = array(" AccountId IN ({$accountIds})");
		$fields = array("AccountId","UserId");
		try{
			$userIdList  = $objDao->selectAllInfo($fields,$arrConds);
			foreach($userIdList as $value){
				$userIdToAccount[$value['UserId']] = $value['AccountId'];
				$AccountTouserId[$value['AccountId']] = $value['UserId'];
			}
			$userListMoney = $this->getBalanceMoney(array_keys($userIdToAccount),$params);
			$tmpData = $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE]['data'];
			foreach ($tmpData as  $key=>$row) {
				$tmpList[$key][$this->indexFieldName] = $row['account_id'];
				$tmpList[$key]['userBidBalance'] = $userListMoney[$AccountTouserId[$row['account_id']]];
			}
			$this->data['data'] = $tmpList;
			return $this->data;
		} catch(Exception $e) {
			$this->data['errorno'] = $e->getCode();
			$this->data['errormsg'] = $e->getMessage();
		}
		return $this->data;	
	}
    //得到accountId
    protected function getAccountId($params,$data){
        $newList = array();
        $tmpList = array();
        /* foreach ($params['accountIds'] as  $accountId) {
            $tmpList[$accountId][$this->indexFieldName] = $accountId;
            $tmpList[$accountId]['accountid_show'] = "<a target='_blank' href='http://{$params['domain']}.ganji.com/fang_{$accountId}/'>{$accountId}</a>";
        } */
        $tmpData = $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE]['data'];
        foreach ($tmpData as  $key=>$row) {
        	$tmpList[$key][$this->indexFieldName] = $row['account_id'];
        	$tmpList[$key]['accountid_show'] = "<a target='_blank' href='http://{$params['domain']}.ganji.com/fang_{$row['account_id']}/'>{$row['account_id']}</a>";
        }
        $newList['data'] = $tmpList;
        return $newList;
    }
    //得到经纪人邮箱
    protected function getCrmAccountEmail($params,$data){
    	$objServiceCustomerAccount = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
    	//$data = $data[Service_Data_HouseReport_GroupData::PREMIER]['data'];
    	$userIdArr = array();
    	if(function_exists(array_column) && !empty($data)){
    		$userIdArr = array_column($data,'user_id');
    	}else if(!empty($data)){
    		foreach ($data as $row){
    			$userIdArr[] = $row['user_id'];
    		}
    	}
    	//$userIdArr = array('500008706','8014');
    	$userIdChunk = array_chunk($userIdArr, 500);
    	$crmEmail = array();
    	foreach ($userIdChunk as $userIds){
    		$userEmail = array();
    		$userEmail = $objServiceCustomerAccount->batchGetUser($userIds);
    		if(!$userEmail['errorno'] && is_array($userEmail['data'])){
    			$crmEmail = count($crmEmail) ? array_merge($crmEmail,$userEmail['data']) : $userEmail['data'];
    		}
    	}
    	$tmpCrmEmail = array();
    	if(count($crmEmail)){
    		if(function_exists(array_column)){
    			$tmpCrmEmail = array_column($crmEmail, NULL, 'user_id');
    		}else{
    			foreach ($crmEmail as $row){
    				$tmpCrmEmail[$row['user_id']] = $row;
    			}
    		}
    	}
    	$newList = array();
    	$tmpList = array();
    	foreach ($data as  $key=>$row) {
    		if (!array_key_exists('downloadFilename', $params)) {
    			$tmpList[$key][$this->indexFieldName] = $row['account_id'];
    		}
    		$tmpList[$key]['account_email'] = isset($tmpCrmEmail[$row['user_id']]['email'])?$tmpCrmEmail[$row['user_id']]['email']:Service_Data_HouseReport_GroupData::NO_DATA_STR;//'zlm@ganji.com';
    	}
    	$newList['data'] = $tmpList;
    	return $newList;
    }

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
        if (isset($params['product']) && !is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::PREMIER],$params['product']))) {
            unset($plist);
        }

        if (!isset($blist)){
            //竞价数据
            $blist = $this->getBidData($params);
        }

        //如果没有竞价精品
        if ($params['product'] && !is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::BID],$params['product']))) {
            unset($blist);
        }

        if (!isset($alist)){
            //放心房数据
            $alist = $this->getAssureData($params);
        }

        //如果没有选择放心房
        if ($params['product'] && !is_numeric(array_search(Service_Data_HouseReport_GroupData::$productIndex2Text[Service_Data_HouseReport_GroupData::ASSURE],$params['product']))) {
            unset($alist);
        }

        //找到一个最大的长度
        $maxList = intval($plist['count']) >= intval($alist['count']) ?$plist:$alist;
        $maxList = intval($maxList['count']) >= intval($blist['count']) ?$maxList:$blist;

        if (isset($maxList['data'])) {
            foreach ($maxList['data'] as $accountId => $value) {
                $result['data'][$accountId][$this->indexFieldName] = $value[$this->indexFieldName];
                $result['data'][$accountId]['similar_house_count'] = intval($plist['data'][$accountId]['similar_house_count']) + intval($alist['data'][$accountId]['similar_house_count'])  + intval($blist['data'][$accountId]['similar_house_count']);
                $result['data'][$accountId]['illegal_house_count'] = intval($plist['data'][$accountId]['illegal_house_count']) + intval($alist['data'][$accountId]['illegal_house_count']) + intval($blist['data'][$accountId]['illegal_house_count']); ;
                $result['data'][$accountId]['comment_count'] = intval($plist['data'][$accountId]['comment_count']) + intval($alist['data'][$accountId]['comment_count']) + intval($blist['data'][$accountId]['comment_count']); ;
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
            Service_Data_HouseReport_GroupData::ACCOUNT_ID, 
            Service_Data_HouseReport_GroupData::ACCOUNT_EMAIL, 
            Service_Data_HouseReport_GroupData::BUSINESS_SCOPE, 
            Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
            Service_Data_HouseReport_GroupData::TOTAL,
            Service_Data_HouseReport_GroupData::PREMIER,
            Service_Data_HouseReport_GroupData::ASSURE,
            Service_Data_HouseReport_GroupData::BID,
            Service_Data_HouseReport_GroupData::STICK,
            Service_Data_HouseReport_GroupData::DXTG,
			Service_Data_HouseReport_GroupData::FRAME_AUDITING_DATA,
			Service_Data_HouseReport_GroupData::CENTER_BALANCE,
			Service_Data_HouseReport_GroupData::BID_BALANCE,
			
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
            //添加经纪人邮箱
            $data[Service_Data_HouseReport_GroupData::ACCOUNT_EMAIL] = $this->getCrmAccountEmail($params,$data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
            if (!in_array(Service_Data_HouseReport_GroupData::PREMIER, $tags)) {
            	unset($data[Service_Data_HouseReport_GroupData::PREMIER]);
            }
            //获得端口类别
            $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE] = $groupdataService->getBusinessScope($params,$this->indexFieldName);
            //添加accountId
            $data[Service_Data_HouseReport_GroupData::ACCOUNT_ID] = $this->getAccountId($params,$data);
            //添加经纪人邮箱
            //$data[Service_Data_HouseReport_GroupData::ACCOUNT_EMAIL] = $this->getCrmAccountEmail($params,$data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
			//添加会员中心余额
			if($params['level'] == self::REAL_DATA_TIME){
				$data[Service_Data_HouseReport_GroupData::CENTER_BALANCE ] = $this->getUserCenterBalance($params,$data);
				$data[Service_Data_HouseReport_GroupData::BID_BALANCE]  = $this->getBidBalance($params,$data);
				unset($this->productData[Service_Data_HouseReport_GroupData::TOTAL]['title_data']['account_pv']);
				unset($this->productData[Service_Data_HouseReport_GroupData::PREMIER]['title_data']['account_pv']);
				unset($this->productData[Service_Data_HouseReport_GroupData::ASSURE]['title_data']['account_pv']);
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
    /*{{{groupAccountData 组织结构数据*/
    /**
     *
     * @param unknown $params
     *  数据格式
     *  array(
     *       'data'=>array(
     *          'title'=>'日期',
     *          'title_list'=> array(
     *              orgId=>array('name'=>'name','href'=>'href')
     *              orgId=>array('name'=>'name','href'=>'href')
     *          ),
     *          'count'=>条数
     *       )
     *      'accountIds' => array()
     *
     */
    public function groupOutletData($params){
        $pageDataSerivce = Gj_LayerProxy::getProxy('Service_Data_HouseReport_ReportService');
        $dataServiceRet = array();
        //如果有搜索
        if (isset($params['stype']) && is_numeric($params['stype'])
                && !empty($params['keyword'])
           ){
            $searchObj = Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount_SearchCustomerAccount');
            $whereConds['search_keyword'] = $params['keyword'];
            $whereConds['search_type'] = $params['stype'];
            $whereConds['id'] = $params['userId'];
            //只搜索付费账号
            $whereConds['induration'] = true;
            $dataServiceRet = $searchObj->SearchAgent($whereConds);
        } else {
            $accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
            //当前存在的pid是门店id
            $id = HttpNamespace::getGET('pid',null);
            $orgId = !empty($id)?$id:$params['userId'];
            //查到所有的该公司的所以门店id
            $customerIds = $pageDataSerivce->getAllOutlet($orgId);
            //是否是过期的订单
            if ($params['islost']) {
                $whereConds['customerId'] = $customerIds;
                $whereConds['businessScope'] = $params['businessScope'];
                //取精品订单 后一段时间作为过期端口的开始时间  查找有效和无效的端口
                $whereConds['effective'] = false;

                //一般端口的过期时间在 一天的 23:59:59秒
                $whereConds['maxTime']['<=maxEndTime'] = strtotime($params['date']['eDate']) + 3600 * 24 -1;
                $whereConds['maxTime']['>=maxEndTime'] = strtotime($params['date']['sDate']);
                //以半年为一个时间范围
                // $whereConds['maxTime']['>=minBeginTime'] = strtotime($params['date']['eDate']) - 3600 * 24 * 178;
                $dataServiceRet = $accountObj->getAccountListByCompanyId($params['companyId'],$whereConds,1,null);
            } else {
                $whereConds = array();
                $whereConds['customerId'] = $customerIds;
                $whereConds['businessScope'] = $params['businessScope'];
                $whereConds['effective'] = true;
                $whereConds['inTime']['<=minBeginTime'] = strtotime($params['date']['eDate']);
                $dataServiceRet = $accountObj->getAccountListByCompanyId($params['companyId'],$whereConds,1,null);

                //$dataServiceRetCount = $accountObj->getAccountListByCompanyIdCount($params['companyId'],$whereConds);
            }
        }
        if (empty($dataServiceRet['data'])) {
            if ($params['islost']===true){
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'没有过期数据');
            } else {
                throw new Gj_Exception(Service_Data_HouseReport_CheckData::OUTLET_DATA_ERROR_NUM,'该端口类别下面没有数据');
            }
        }
        $accountList = $dataServiceRet['data'];
        $tmpList = array();
        foreach ($accountList as $item) {
            //得到所有的accountId
            $tmpList[$item['AccountId']] = $item['AccountId'];
        }
        $accountIdList = array_keys($tmpList);
        $accountObj =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccount');
        $accountIdList = $accountObj->getAccountInfoById($accountIdList,array('AccountId','UserId','AccountName','CityId','CellPhone','CustomerId','OwnerType','Status'));
        $tmpList1 = array();
        $cityList = array();
        //把名字查询其中
        foreach ($accountIdList['data'] as $item) {
            $tmpList1[$item['AccountId']] = array(
                    'user_id'=>$item['UserId'],
                    'accountId'=>$item['AccountId'],
                    'accountName'=>$item['AccountName'],
                    'cellPhone'=>$item['CellPhone'],
                    'customerId'=>$item['CustomerId'],
                    'ownerType'=>$item['OwnerType'],
                    'status'=>$item['Status'],
                    );
            $cityList[] = $item['CityId'];
        }
        $cityArr = array_count_values($cityList);
        $sum = -1;
        $city_id = 0;
        foreach ($cityArr as $key=>$val){
            if ($sum <= $val) {
                $city_id = $key;
                $sum = $val;
            }
        }
        $accountData['data']['list'] = $tmpList1;
        //得到总页数
        //$res['totalCount'] = $dataServiceRetCount['data'];
        if (!$accountData['errorno']) {
            foreach ($accountData['data']['list'] as $item) {
                //添加排序索引
                $res['accountIds'][$item['accountId']] = $item['accountId'];
                $res['user_id'][$item['accountId']] = $item['user_id'];
                $res['accountName'][$item['accountId']] = $item['accountName'];
                $res['cellPhone'][$item['accountId']] = $item['cellPhone'];
                $res['customerId'][$item['accountId']] = $item['customerId'];
                $res['ownerType'][$item['accountId']] = $item['ownerType'];
                $res['status'][$item['accountId']] = $item['status'];
            }
        } else {
            throw new Gj_Exception(Service_Data_HouseReport_CheckData::ORG_DATA_ERROR_NUM,"组织结构数据有误");
        }
        $res['city_id'] = $city_id;
        return $res;
    }
    /*}}}*/
}
