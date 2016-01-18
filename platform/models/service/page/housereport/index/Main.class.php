<?php
/******************************
 * @Author      : wangjun5@ganji.com
 * @Date        : 2014-10-17
 * @Filename    : Index.class.php
 * @Description : housing app
 *****************************/
class Service_Page_HouseReport_Index_Main{
    protected $customerArr = array();
    public function __construct(){
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->GcrmHouseManagerAccountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $this->OrgDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_OrgReport');
        $this->GroupDataService =  Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');  
        $this->CustomerDataService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_Customer');
        $this->BizCompanyDataService =  Gj_LayerProxy::getProxy('Service_Data_CompanyShop_BizCompanyInfo');
        $this->CustomerLoginDataService =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_CustomerAccountLoginEvent');
        $this->AccountBusinessInfoService =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_AccountBusinessInfo');
    }
	 public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
          return  call_user_func_array(array($this,$name),$args);
        }   
     }  
    /*
     * array arrayInput {
     * id,pid,company_id,customer_id,level,create_time,status,account,title,name,phone,allowChange
     * }
     */
    public function execute($arrInput){
        //var_dump($arrInput);
		$this->GcrmHouseManagerAccountService = Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $customerList = $this->GcrmHouseManagerAccountService->getChildTreeByOrgId($arrInput['id'],4,null,1,null);
        if ($arrInput['level'] == 4 && $arrInput['customer_id'] >0 ) {
          $this->customerArr[] = $arrInput['customer_id'];
        }elseif($arrInput['level'] == 2 || $arrInput['level'] == 3 ){//如果不是公司级别的,需要门店ID
        //php5.5 支持
          if(function_exists(array_column)){
              $this->customerArr = array_column($customerList['data']['list'], 'customer_id');//获取customer_ids
          }else{
              foreach($customerList['data']['list']    as $item){
                 $this->customerArr[] = $item['customer_id'];
              }
          }
        } 
        //帮帮状态
        $this->data['data']['bangbang'] = $this->accountBusinessInfo($arrInput);
        //账户信息
        $this->data['data']['orginfo'] = $this->orgInfo($arrInput);
        //端口使用量
        $this->data['data']['usage'] = $this->bizUsage($arrInput);
        //点击趋势
        $this->data['data']['click']  = $this->clickTrend($arrInput);
        //分时段刷新
        $this->data['data']['hours']  = $this->hoursInfo($arrInput);
        
        return $this->data;
    } 
    
    protected function accountBusinessInfo($arrInput){
        $data['premier_week_count'] = $data['premier_2week_count'] = $data['premier_month_count'] = $data['assure_week_count'] = $data['assure_2week_count'] = $data['assure_month_count'] = 0;
        foreach(HousingVars::$bizTxt as $biz_code =>$biz_txt){
            if($biz_code == 10){
                continue;
            }
            $this->AccountBusinessInfoService->setVal('defaultFields',array('id','InDurationEndTime','CountType'));
            try{
            $businessList = $this->AccountBusinessInfoService->getBusinessList($arrInput['company_id'],$this->customerArr,$biz_code,true,null,null);//有效订单列表
            //$now = PlatformSingleton::getInstance('TimeMock')->getTime();      
            $biz_status['assure_month_count'] = $biz_status['premier_month_count'] = $biz_status['assure_2week_count'] = $biz_status['premier_2week_count'] = $biz_status['assure_week_count']  = $biz_status['premier_week_count'] = $effect_count = 0;//七天到期数量，30天到期数量,总共的有效端口数量
            if(isset($businessList['data']) && is_array($businessList['data'])){
                foreach($businessList['data'] as $item){     
                    if( $item['InDurationEndTime'] <= strtotime("+7 days midnight") ){//7天到期数量   
                        if($item['CountType'] == 1){//精品
                            $biz_status['premier_week_count']++;
                            $data['premier_week_count']++;
                        }elseif($item['CountType'] == 2){//放心房
                            $biz_status['assure_week_count']++;
                            $data['assure_week_count']++;
                        } 
                    }elseif( $item['InDurationEndTime'] <= strtotime("+15 days midnight") ){//15天到期数量   
                        if($item['CountType'] == 1){//精品
                            $biz_status['premier_2week_count']++;
                            $data['premier_2week_count']++;
                        }elseif($item['CountType'] == 2){//放心房
                            $biz_status['assure_2week_count']++;
                            $data['assure_2week_count']++;
                        } 
                    }elseif( $item['InDurationEndTime'] <= strtotime("+30 days midnight") ){//30天到期数量
                         if($item['CountType'] == 1){
                            $biz_status['premier_month_count']++;
                            $data['premier_month_count']++;
                        }elseif($item['CountType'] == 2){
                            $biz_status['assure_month_count']++;
                            $data['assure_month_count']++;
                        }  
                    }
                    $effect_count++;
                }                           
            }

            $staleCount = $this->AccountBusinessInfoService->getBusinessStatusCount($arrInput['company_id'],$this->customerArr,$biz_code,0);//已过期订单数
            $nextCount = $this->AccountBusinessInfoService->getBusinessStatusCount($arrInput['company_id'],$this->customerArr,$biz_code,1);//即将生效的订单数
            $biz_status['premier_week_count'] = $biz_status['premier_week_count'] > 0 ? $biz_status['premier_week_count'] : '0.00001';//为了JS展示不显示空白所用
            $data['businessInfo'][$biz_code]['title'] = $biz_txt;
            $data['businessInfo'][$biz_code]['data']['biz_status'] = implode(',',$biz_status);
            $data['businessInfo'][$biz_code]['data']['effect_count'] = $effect_count;
            $data['businessInfo'][$biz_code]['data']['next_count'] = intval($nextCount['data']);//即将生效
            $data['businessInfo'][$biz_code]['data']['stale_count'] = intval($staleCount['data']);//         
            }catch(Exception $e){
                $data['errorno'] = $e->getCode();
                $data['errormsg'] = $e->getMessage();
            }
        }
        return $data;
    }


    protected function orgInfo($arrInput){
        $data['level'] = $arrInput['level'];
        $data['manager'] = $arrInput['name'];
        $data['phone'] = $arrInput['phone'];
        try{
            $customerLog = $this->CustomerLoginDataService->getLastedCustomerLoginLog($arrInput['account'],array('loging_time','city'));
            $data['last_login'] = !empty($customerLog['data']['loging_time']) ? date('Y-m-d H:i:s',$customerLog['data']['loging_time']) : '';
            $data['login_city'] = $customerLog['data']['city'];
           //获取未分配的门店数量
            $outLetTotal = $this->CustomerDataService->getOutlet($arrInput['company_id']);
            $data['outlet_count'] = intval(count($outLetTotal['data'])) > 99 ? '99+' : intval(count($outLetTotal['data'])) ; 
            $this->OrgDataService->setVal('order',array('order'=>'asc','orderField'=>'null'));
            $this->OrgDataService->setVal('fields',array('customer_count,account_count'));
            $premierData = $this->OrgDataService->getOrgPremierReportById($arrInput['id'],false);
            $data['customer_count'] = intval($premierData['data']['list'][0]['customer_count']);
            $data['account_count'] = intval($premierData['data']['list'][0]['account_count']);
         } catch (Exception $e) {
            $data['errorno'] = $e->getCode();
            $data['errormsg'] = $e->getMessage(); 
        }
        return $data;
    }


    protected function bizUsage($arrInput){
           //端口使用量
        try{
           // var_dump(HousingVars::$bizToTypes);
            $this->OrgDataService->setVal('countType',array(1,2,3));
            $this->OrgDataService->setVal('order',array('order'=>'asc','orderField'=>'null'));
            $data = array();
            foreach(HousingVars::$bizTxt as $biz_code =>$biz_txt){
                $premier = $assure = $total = array();
                if($biz_code == 10){
                    continue;
                }
                $this->OrgDataService->setVal('houseType',HousingVars::$bizToTypes[$biz_code]);
                //精品,不包含体验账户
                $this->OrgDataService->setVal('fields',array('IFNULL(SUM(house_count),0) as house_count','IFNULL(SUM(premier_count),0) as premier_count',
                'IFNULL(SUM(refresh_count),0) as refresh_count'));
                $premier = $this->OrgDataService->getOrgPremierReportById($arrInput['id'],false);
                $data['premier'][$biz_code]['title'] = $biz_txt;
                $data['premier'][$biz_code]['data'] = $premier['data']['list'][0];
                
                //放心房
                if(in_array($biz_code,array(1,3,4))){
                    $this->OrgDataService->setVal('fields',array('IFNULL(SUM(house_count),0) as house_count','IFNULL(SUM(promote_count),0) as premier_count',
                    'IFNULL(SUM(refresh_count),0) as refresh_count'));
                    $assure = $this->OrgDataService->getOrgAssureReportById($arrInput['id'],false);
                    $data['assure'][$biz_code]['title'] = $biz_txt;
                    $data['assure'][$biz_code]['data'] = $assure['data']['list'][0];
                }
                //汇总数据又TMD不需要了
                if(isset($premier['data']['list'][0]) && is_array($premier['data']['list'][0])){
                    foreach($premier['data']['list'][0] as $key=>$value){
                        //$total[$key] = isset($assure['data']['list'][0][$key]) ? intval($assure['data']['list'][0][$key]+$value) : intval($value);
                        $total[$key] = intval($value).'/'.intval($assure['data']['list'][0][$key]);
                    }
                }
                $data['collect'][$biz_code]['title'] = $biz_txt;
                $data['collect'][$biz_code]['data'] = $total;
            }
        } catch (Exception $e) {
            $data['errorno'] = $e->getCode();
            $data['errormsg'] = $e->getMessage(); 
        }
        return $data;
    }
    
    protected function clickTrend($arrInput){
        $params['orgid'] = $arrInput['id'];
        $this->GroupDataService =  Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');          
        $categorys = array('PREMIER'=>array(1),'BID'=>array(2,3),'ASSURE'=>array(4));
        $this->GroupDataService->setVal('sDate',date('Y-m-d',strtotime("-7 day midnight")));
        $this->GroupDataService->setVal('eDate',date('Y-m-d',strtotime("-1 day midnight")));
        $data = $click_consume = $click_trend = array();
        try{
            //7天内的点击量
            foreach($categorys as $category=>$val){
                $params['countType'] = $val;
                $click_consume[$category] = $this->GroupDataService->getOrgClickByDay($params);    
            }
            //总点击趋势
            if(isset($click_consume['PREMIER']) && is_array($click_consume['PREMIER'])){
                foreach($click_consume['PREMIER'] as $key => $val){
                   $click_trend[$key] = isset($click_consume['ASSURE'][$key]) ? intval($val + $click_consume['ASSURE'][$key]) : intval($val);
                }
            }
            $data['trend'] = $this->formatDisplay($click_trend,'m-d');;
            $data['bid'] = $this->formatDisplay($click_consume['BID'],'d');   
        }catch(Exception $e){
            $data['errorno'] = $e->getCode();
            $data['errormsg'] = $e->getMessage(); 
        }
        return $data;
    }
    
    /*
     * 分时段刷新
     * array $arrInput['id']组织结构ID
     * array $arrInput['level'] 级别 
     * array $arrInput['company_id']公司id
     * array $arrInput['customer_id'] 门店 
     */
    protected function hoursInfo($arrInput){
      
        try{
        $this->OrgDataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_OrgReport');
        $this->OrgDataService->setVal('countType',array(1,2));
        $hoursInfo = $this->OrgDataService->getOrgHoursReport($arrInput['company_id'],$this->customerArr);
        $hoursArr = array();
        if(!empty($hoursInfo['data'][0]) && is_array($hoursInfo['data'][0])){   
            $total = array_pop($hoursInfo['data'][0]);
            foreach($hoursInfo['data'][0] as $k=>$v){
                if($total>0){
                    $hoursArr[] = sprintf("%.1f%%",$v/$total*100);
                }else{
                    $hoursArr[] = 0;
                }
            } 
        }
         $data['refresh'] = implode('/', $hoursArr);
        }catch(Exception $e){
            $data['errorno'] = $e->getCode();
            $data['errormsg'] = $e->getMessage(); 
        }
        return $data;
    }

    protected function formatDisplay($data,$d_format = 'm-d'){
        $str = '';
        if(isset($data) && is_array($data)){
            foreach($data as $day => $click){
            $str .= $click.'/'.date($d_format,$day).',';
            }
            $str = isset($str) ? substr($str,0,strlen($str)-1) : $str ;    
        }
        return $str;
    }
    
    
    

}
