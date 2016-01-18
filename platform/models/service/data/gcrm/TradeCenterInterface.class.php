<?php
/**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 * Date: 2014/12/29
 * Time: 10:24
 */
Class Service_Data_Gcrm_TradeCenterInterface{

    protected $data;
    protected $objDaoTC;
    public function __construct(){
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDaoTC = Gj_LayerProxy::getProxy('Dao_Gcrm_TradeCenterInterface');
    }

    /**
     * @codeCoverageIgnore
     */
    public function __call( $name, $arguments)
    {
        if (Gj_LayerProxy::$is_ut == true)
        {
            return $this->$name($arguments[0]);
        }
    }

    /* {{{ getBussinessDurationInfo*/
    /**
     * @brief 
     *
     * @param $userId
     *
     * @returns   
     */
    public function getBussinessDurationInfo($userId, $referTime = null){
        if (!$referTime) {
            $referTime = time();
        }
        $params = array(
                'users' => array($userId),
                'categories' => array(7),
                );
        $res = $this->objDaoTC->getBalances($params);
        $data = $this->OutParamsProcess($res, '1');
        $retVal = array(
            'UserId' => $userId,
            'BussinessInfo' => array() 
        );
        if (!is_array($data) || empty($data))  {
            return $retVal;
        }
        // 库存按照开始时间排序 为库存合并逻辑做准备
        foreach ($data as $balance) {
            $sortKey[] = $balance['BeginAt'];
        }
        array_multisort($sortKey, SORT_NUMERIC, $data);
        foreach ($data as $balance) {
            $userId = $balance['UserId'];
            $bizType = $balance['Extension'];
            $productCode = $balance['ProductCode'];
            $amountLeft = $balance['AmountLeft'];
            $status = $balance['Status'];
            $beginAt = $balance['BeginAt'];
            $endAt = $balance['EndAt'];
            if ($status != 1 || !is_numeric($bizType)) {
                continue;
            }
            if (!isset($minTime[$productCode][$bizType])) {
                $minTime[$productCode][$bizType] = $beginAt;
                $maxTime[$productCode][$bizType] = $endAt;
                $amountInfo[$productCode][$bizType] = 0;
                $durationInfo[$productCode][$bizType][$beginAt] = $endAt;
            }
            $minTime[$productCode][$bizType] > $beginAt && $minTime[$productCode][$bizType] = $beginAt;
            $maxTime[$productCode][$bizType] < $endAt && $maxTime[$productCode][$bizType] = $endAt;
            if ($beginAt <= $referTime && $referTime <= $endAt) {
                if (($endAt - $beginAt) == (86400 - 1)
                    && in_array($productCode, array('pd_manual_refresh', 'pd_house_rest_refresh_free'))) {
                    // 特殊逻辑赠送刷新 结束时间为当天 23:59:59 开始时间为当天0点
                    $productCode = "gift_{$productCode}";
                }
                $amountInfo[$productCode][$bizType] = $this->getArrayVal($amountInfo[$productCode], $bizType, 0) + $amountLeft;
            }
            $this->addDuration($beginAt, $endAt, $durationInfo[$productCode][$bizType]);
        }
        $retKey = array(
            'pd_post_num' => array(
                'MaxPremierCount' => 'pd_post_num',
                'MaxFreeRefreshCount' => 'pd_manual_refresh',
                'MaxChargeRefreshCount' => 'not_exist_pd_code', // 精品暂时没有付费刷新,占位用
                'GiftRefresh' => 'gift_pd_manual_refresh', // 赠送刷新
            ),        
            'pd_house_rest' => array(
                'MaxPremierCount' => 'pd_house_rest',
                'MaxFreeRefreshCount' => 'pd_house_rest_refresh_free',
                'MaxChargeRefreshCount' => 'pd_house_rest_refresh', 
                'GiftRefresh' => 'gift_pd_house_rest_refresh_free', // 赠送刷新
            ),        
        );
        $bussinessInfo = array();
        foreach ($retKey as $tmpPdCode => $countKeyList){
            if (!isset($minTime[$tmpPdCode])) {
                continue;
            }
            foreach ($minTime[$tmpPdCode] as $tmpBiz => $tmpMinTime) {
                $countTypeArr = array(
                    'pd_post_num' => 1,
                    'pd_house_rest' => 2,        
                );
                $tmpInfo = array(
                    'CountType' => $countTypeArr[$tmpPdCode],
                    'BussinessScope' => $tmpBiz,
                );
                $tmpInfo['MinBeginTime'] = $tmpMinTime;
                $tmpInfo['MaxEndTime'] = $this->getArrayVal($maxTime[$tmpPdCode], $tmpBiz, 0);
                $tmpInfo['InDuration'] = 0;
                $tmpInfo['NextDurationBeginTime'] = $tmpInfo['MaxEndTime'];
                foreach ($durationInfo[$tmpPdCode][$tmpBiz] as $tmpB => $tmpE) {
                    if ($tmpB <= $referTime && $referTime <= $tmpE) {
                        $tmpInfo['InDurationBeginTime'] = $tmpB;
                        $tmpInfo['InDurationEndTime'] = $tmpE;
                        $tmpInfo['InDuration'] = 1;
                    } else if ($tmpB > $referTime) {
                        $tmpInfo['NextDurationBeginTime'] = min($tmpInfo['NextDurationBeginTime'], $tmpB);
                    }
                }
                if ($tmpInfo['NextDurationBeginTime'] == $tmpInfo['MaxEndTime']) {
                        $tmpInfo['NextDurationBeginTime'] = 0;
                }
                foreach ( $countKeyList as $key => $pdKey) {
                    $tmpInfo[$key] = $this->getArrayVal($amountInfo[$pdKey], $tmpBiz, 0);
                }
                $bussinessInfo[] = $tmpInfo;
            }
        }
        $retVal['BussinessInfo'] = $bussinessInfo;
        return $retVal;
    }
    /* }}}*/
    /* {{{ protected addDuration*/
    /**
     * @brief 计算库存汇总的付费期分段信息
     *
     * @param $beginAt 要计算的库存的开始时间
     * @param $endAt 要计算的库存的结束时间
     * @param $durationArr 当前库存对应的时段信息数组
     *
     * @returns  true 
     */
    protected function addDuration($beginAt, $endAt, &$durationArr){
        $processed = false;
        foreach ($durationArr as $b => $e){
            if ($b <= $beginAt && $beginAt <= ($e + 1)) {
                 $durationArr[$b] = max($e, $endAt);
                 $processed = true;
            } else if ($b <= ($endAt + 1) && $endAt <= $e) {
                $processed = true;
                unset($durationArr[$b]);
                $durationArr[$beginAt] = max($e, $endAt);
            } else if ($beginAt <= $b && $e <= $endAt) {
                $processed = true;
                unset($durationArr[$b]);
                $durationArr[$beginAt] = max($e, $endAt);
            }
        }
        if (! $processed) {
            $durationArr[$beginAt] = $endAt;
        }
        return true;
    }
    /* }}} */
    protected function getArrayVal($arr, $field, $default=0){
        $ret = is_array($arr) && isset($arr[$field]) ? $arr[$field] : $default;
        return $ret;
    }

    /* {{{ GetBalanceList*/
    /**
     * @brief   获取充值记录
     *
     * @param $option = array(
     *                      UserId 	 	    用户ID
     *                      ProductCode 	产品编号
     *                      CategoryType 	产品类别
     *                      CityId 	    	城市ID
     *                      Extension
     *                  )
     *
     * @returns
     */
    public function GetBalanceList($option)
    {
        $params = $this->InParamsProcess($option);
        if (empty($params['users'])) {
            return null;
        }
        $res = $this->getBalance($params);
        if($res !== false) {
            $this->data['data'] = $this->OutParamsProcess($res, '1');
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/

    /* {{{ GetBalanceListByUserList*/
    /**
     * @brief   获取用户充值记录
     *
     * @param $option = array(
     *                      UserIdList 	array   用户ID
     *                      OrderId	            订单编号
     *                      Status 	            库存状态
     *                      BeginAt	            开始时间
     *                      EndAt               结束时间
     *                  )
     * 这里添加了category=7  只取房产类的记录
     * @returns
     */
    public function GetBalanceListByUserList($option)
    {
        $params = array();
        if (isset($option['UserIdList'])) {
            $params['users'] = $option['UserIdList'];
        } else {
            return null;
        }
        //限制产品大类为房产category=7
        $params['categories'] = array(7);
        if (isset($option['OrderId'])) {
            $params['orders'][] = $option['OrderId'];
        }
        if (isset($option['Status'])) {
            $params['status'][] = $option['Status'];
        }
        if (isset($option['BeginAt'])) {
            $params['beginAtFrom'] = $option['BeginAt'];
        }
        if (isset($option['EndAt'])) {
            $params['endAtTo'] = $option['EndAt'];
        }
        $res = $this->getBalance($params);
        if($res !== false) {
            $this->data['data'] = $this->OutParamsProcess($res, '2');
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/

    /* {{{ GetPagedBalanceList*/
    /**
     * @brief   获取充值记录(分页)
     *
     * @param $option = array(
     *                      UserId 	        Int(11)  	用户ID
     *                      ProductCode 	String 	   	产品编号
     *                      CategoryType 	Int(11)  	产品类别
     *                      CityId 	        Int(11)  	城市ID
     *                      Extension 	    Json
     *                      BeginAt 	    int  	   	开始时间
     *                      EndAt 	        int 	   	过期时间
     *                      PageSize 	    int 	   	页大小
     *                      PageIndex 	    int 	   	第几页
     *                  )
     * 新接口没有分页功能，这里是自己实现的。这里的开始时间、过期时间用于按照充值时间搜索记录
     * @returns
     */
    public function GetPagedBalanceList($option)
    {
        $params = $this->InParamsProcess($option);
        if(empty($params['users'])) {
            return null;
        }
        $result = $this->getBalance($params);
        if($result !== false) {
            $out = $this->OutParamsProcess($result, '1');
            //获取指定日期内的记录
            if (!empty($option['EndAt'])) {
                $myarr = array();
                foreach ($out as $val) {
                    if ($val['CreatedAt'] >= $option['BeginAt'] && $val['CreatedAt'] <= $option['EndAt']) {
                        $myarr[] = $val;
                    }
                }
                if (!empty($myarr)) {
                    $res['TotalCount'] = ceil(count($myarr) / $option['PageSize']);
                    $res['BalanceList'] = array_slice($myarr, ($option['PageIndex'] - 1) * $option['PageSize'], $option['PageSize']);
                    $this->data['data'] = $res;
                } else {
                    $this->data['data'] = null;
                }
            } else {
                $res['TotalCount'] = ceil(count($out) / $option['PageSize']);
                $res['BalanceList'] = array_slice($out, ($option['PageIndex'] - 1) * $option['PageSize'], $option['PageSize']);
                $this->data['data'] = $res;
            }
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/

    /* {{{ AddUserBalance*/
    /**
     * @brief   自动补偿/充值接口
     *
     * @param $option = array(
     *                      UserId 	        Int(11) 	 	用户ID
     *                      Remark 	        varchar(500)  	备注内容
     *                      CityId 	        Int(11) 	 	城市ID
     *                      CategoryType 	Int(11) 	 	类别编号
     *                      ProductCode 	varchar(50) 	产品编号
     *                      Extension 	    varchar(5000)  	扩展信息
     *                      BeginAt 	    int(11) 		此充值有效期的开始时间
     *                      EndAt 	        int(11) 	 	此充值有效期的结束时间
     *                      Amount 	        int(11) 	    数量，正数为充值，负数为消耗
     *                  )
     * @returns
     */
    public function AddUserBalance($option){
        $balance = array();
        $remark = '';//not null
        $userId = null;
        $userName = "房产业务";
        if (isset($option['UserId'])) {
            $balance['userId'] = $option['UserId'];
            $userId = $option['UserId'];
        }else{
            return null;
        }
        if (isset($option['Remark'])) {
            $remark = $option['Remark'];
        }
        if (isset($option['CityId'])) {
            $balance['city'] = $option['CityId'];
        }
        if (isset($option['CategoryType'])) {
            $balance['category'] = $option['CategoryType'];
        }
        if (isset($option['ProductCode'])) {
            $balance['product'] = $option['ProductCode'];
        }
        if (isset($option['Extension'])) {
            $balance['extension'] = $option['Extension'];
        } else {
            $balance['extension'] = '此商品未区分端口类别';
        }
        if (isset($option['BeginAt'])) {
            $balance['beginAt'] = $option['BeginAt'];
        }
        if (isset($option['EndAt'])) {
            $balance['endAt'] = $option['EndAt'];
        }
        if (isset($option['Amount'])) {
            $balance['amount'] = $option['Amount'];
        }
        $balance['sourceType'] = 5;
        try {
            $res = $this->objDaoTC->addUserBalance($balance, $userId, $userName, $remark);
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if($res === false){
            Gj_Log::warning($this->objDaoTC->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
        } else {
            $this->data['data'] = $result;
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/

    /* {{{ ConsumeByProduct*/
    /**
     * @brief   消耗用户指定商品库存
     *
     * @param $option = array(
     *                      UserId 	        Int(11) 	 	用户ID
     *                      CityId 	        Int(11) 	 	城市ID
     *                      CategoryType 	Int(11)
     *                      ProductCode 	Int(11) 	 	产品ID
     *                      Extension 	    Json
     *                      ConsumeKey 	    String 	     	Puid、CickId、SMSId、OrderID等唯一标识，用于从消费记录中反向追踪
     *                      ConsumeAmount 	Decimal(10,2) 	消耗的数量或金额
     *                      ConsumeAt 	    int 	    	消耗时间点
     *                      Token 	        string 	    	MD5(Source,ConsumeKey,ProductCode,ConsumeAt) [注: 不是逗号连接,是直接拼接]
     *                  )
     * @returns
     */
    public function ConsumeByProduct($option){
        $params = $this->InParamsProcessTwo($option);
        if(empty($params['userId'])) {
            return null;
        }
        try {
            $result = $this->objDaoTC->consume($params);
        }catch (Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if($result === false){
            Gj_Log::warning($this->objDaoTC->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
        } else {
            $this->data['data'] = $result;
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/

    /* {{{ GetBalanceDuration*/
    /**
     * @brief      获取用户的指定的产品库存
     *
     * @param $option = array(
     *                      UserId 	        Int(11)  	用户ID
     *                      ProductCode 	String 	 	产品编号
     *                      CategoryType 	Int(11) 	产品类别
     *                      CityId 	        Int(11) 	城市ID
     *                      Extension 	    Json
     *                  )
     * @returns
     */
    public function GetBalanceDuration($option){
        $params = $this->InParamsProcess($option);
        if(empty($params['users'])) {
            return null;
        }
        $result = $this->getBalance($params);
        if($result !== false) {
            $res = array();
            foreach ($result as $val) {
                if (is_object($val))
                    $res[] = get_object_vars($val);
            }
            $this->data['data'] = $this->DataProcess($res);
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }
    /* }}}*/


    public function GetBalanceDurationList($option){
        $params = $this->InParamsProcess($option);
        if(empty($params['users'])) {
            return null;
        }
        $result = $this->getBalance($params);
        if($result !== false) {
            //这里根据四个字段分类，ProductCode、CityId、CategoryType、Extension;
            //虽然新接口入参users可以包含多个userId，但由于旧接口入参UserId唯一，所以不作为分类标准。
            $cup = array();
            foreach ($result as $val) {
                if (is_object($val))
                    $cup[] = get_object_vars($val);
            }
            $tmp = $this->Group($cup);
            $res = array();
            foreach ($tmp as $val) {
                $res[] = $this->DataProcess($val);
            }
            $this->data['data'] = $res;
            $this->data['succeed'] = 1;
        }
        return $this->data;
    }

    //入参处理
    protected function InParamsProcess($option){
        if(!empty($option)){
            $params = array();
            if(isset($option['UserId'])){
                $params['users'][] = $option['UserId'];
            }
            if(isset($option['ProductCode'])){
                $params['products'][] = $option['ProductCode'];
            }
            if(isset($option['ProductCodeList'])){
                $params['products'] = explode(",",$option['ProductCodeList']);
            }
            if(isset($option['CategoryType'])){
                $params['categories'][] = $option['CategoryType'];
            }
            if(isset($option['CityId'])){
                $params['cities'][] = $option['CityId'];
            }
            if(isset($option['Extension'])){
                $params['extension'] = $option['Extension'];
            }
            if(isset($option['PageSize'])){
                $params['pageSize'] = $option['PageSize'];
            }
            if(isset($option['PageIndex'])){
                $params['pageIndex'] = $option['PageIndex'];
            }
            return $params;
        }
        return null;
    }
    /**
     *@codeCoverageIgnore
     */
    protected function InParamsProcessTwo($option){
        if (!empty($option)) {
            if (isset($option['UserId'])) {
                $params['userId'] = $option['UserId'];
            }
            if (isset($option['ProductCode'])) {
                $params['product'] = $option['ProductCode'];
            }
            if (isset($option['CategoryType'])) {
                $params['category'] = $option['CategoryType'];
            }
            if (isset($option['CityId'])) {
                $params['city'] = $option['CityId'];
            }
            if (isset($option['Extension'])) {
                $params['extension'] = $option['Extension'];
            }
            if (isset($option['ConsumeKey'])) {
                $params['consumeKey'] = $option['ConsumeKey'];
            }
            if (isset($option['ConsumeAmount'])) {
                $params['amount'] = $option['ConsumeAmount'];
            }
            if (isset($option['ConsumeAt'])) {
                $params['consumeTime'] = $option['ConsumeAt'];
            }
            if (isset($option['Token'])) {
                $params['token'] = $option['Token'];
            }
            if (isset($option['consumeOnlyOnce'])) {
                $params['consumeOnlyOnce'] = $option['consumeOnlyOnce'];
            }
            if (isset($option['resultType'])) {
                $params['resultType'] = $option['resultType'];
            }
            return $params;
        }
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    //出参处理  $flag：为‘1’时，amount表示总数量；为‘2’时，表示剩余数量。
    protected function OutParamsProcess($params, $flag){
        if(empty($params)){
            return null;
        }
        $result = array();
        foreach($params as $val) {
            if(is_object($val))
                $result[] = get_object_vars($val);
        }
        if(!empty($result)){
            $out =array();
            foreach($result as $key => $val){
                if(isset($val['id'])){
                    $out[$key]['Id'] = $val['id'];
                }
                if(isset($val['orderId'])){
                    $out[$key]['OrderId'] = $val['orderId'];
                }
                if(isset($val['userId'])){
                    $out[$key]['UserId'] = $val['userId'];
                }
                if(isset($val['city'])){
                    $out[$key]['CityId'] = $val['city'];
                }
                if(isset($val['category'])){
                    $out[$key]['CategoryType'] = $val['category'];
                }
                if(isset($val['product'])){
                    $out[$key]['ProductCode'] = $val['product'];
                }
                if($flag === '1') {
                    if (isset($val['amount'])) {
                        $out[$key]['Amount'] = $val['amount'];
                    }
                    if (isset($val['amountLeft'])) {
                        $out[$key]['AmountLeft'] = $val['amountLeft'];
                    }
                } elseif($flag === '2') {
                    if(isset($val['amount'])){
                        $out[$key]['TotalAmount'] = $val['amount'];
                    }
                    if(isset($val['amountLeft'])){
                        $out[$key]['Amount'] = $val['amountLeft'];
                    }
                }
                if(isset($val['beginAt'])){
                    $out[$key]['BeginAt'] = $val['beginAt'];
                }
                if(isset($val['endAt'])){
                    $out[$key]['EndAt'] = $val['endAt'];
                }
                if(isset($val['status'])){
                    $out[$key]['Status'] = $val['status'];
                }
                if(isset($val['sourceType'])){
                    $out[$key]['SourceType'] = $val['sourceType'];
                }
                if(isset($val['createdAt'])){
                    $out[$key]['CreatedAt'] = $val['createdAt'];
                }
                if(isset($val['extension'])){
                    $out[$key]['Extension'] = $val['extension'];
                }
                if(isset($val['refundAt'])){
                    $out[$key]['refund_at'] = $val['refundAt'];
                }
                if(isset($val['usage'])){
                    $out[$key]['Usage'] = $val['usage'];
                }
                if(isset($val['usage'])){
                    $out[$key]['UsageJson'] = json_encode($val['usage']);
                }
            }
            return $out;
        }
        return null;
    }

//为了适配GetBalanceDuration、GetBalanceDurationList而添加的方法，根据product、city、category、extension对库存记录分类
    protected  function Group($option){
        if(empty($option))
            return null;
        $params = array();
        $keywords = array();
        foreach($option as $val){
            if(empty($keywords)){
                $keywords[] = $val['product'].$val['city'].$val['category'].$val['extension'];
            }
            if(empty($params)){
                $params[reset($keywords)][] = $val;
            }else{
                $tmp = $val['product'].$val['city'].$val['category'].$val['extension'];
                if(in_array($tmp,$keywords)){
                    $params[$tmp][] = $val;
                }else{
                    $keywords[] = $tmp;
                    $params[$tmp][] = $val;
                }
            }
        }
        return $params;
    }

    //对分类后的库存数据进行处理
    /**
     * @codeCoverageIgnore
     */
    protected function DataProcess($result){
        if(!empty($result)) {
            $out = array();
            $timeNow = time();
            foreach ($result as $key => $val) {
                if($val['status'] === 1) {
                    if ($key == 0) {
                        $this->paramsDP($out, 'UserId', $val['userId']);
                        $this->paramsDP($out['Balance'], 'ProductCode', $val['product']);
                        $this->paramsDP($out['Balance'], 'CityId', $val['city']);
                        $this->paramsDP($out['Balance'], 'CategoryType', $val['category']);
                        $this->paramsDP($out['Balance'], 'Extension', $val['extension']);
                        $this->paramsDP($out, 'MaxEndTime', $val['endAt']);
                        $this->paramsDP($out, 'MinBeginTime', $val['beginAt']);
                        $this->paramsDP($out, 'MinInDurationBeginTime', 0);
                        $this->paramsDP($out, 'MaxInDurationEndTime', 0);
                        $this->paramsDP($out, 'InDuration', false);
                        $this->paramsDP($out, 'Amount', 0.0);
                        $this->paramsDP($out, 'TotalAmount', 0.0);
                        $this->paramsDP($out, 'IsFreezed', false);
                        $this->paramsDP($out, 'InDurationBeginAt', 0);
                        $this->paramsDP($out, 'InDurationEndAt', 0);
                        $this->paramsDP($out, 'OutDurationBeginAt', 0);
                        $this->paramsDP($out, 'OutDurationEndAt', 0);
                    }
                    if ($out['MaxEndTime'] < $val['endAt']) {
                        $out['MaxEndTime'] = $val['endAt'];
                    }
                    if ($out['MinBeginTime'] > $val['beginAt']) {
                        $out['MinBeginTime'] = $val['beginAt'];
                    }
                    $out['TotalAmount'] += $val['amount'];
                    if ($timeNow > $val['beginAt'] && $timeNow < $val['endAt']) {
                        if ($out['MinInDurationBeginTime'] == 0) {
                            $out['MinInDurationBeginTime'] = $val['beginAt'];
                        } elseif ($out['MinInDurationBeginTime'] > $val['beginAt']) {
                            $out['MinInDurationBeginTime'] = $val['beginAt'];
                        }
                        if ($out['MaxInDurationEndTime'] == 0) {
                            $out['MaxInDurationEndTime'] = $val['endAt'];
                        } elseif ($out['MaxInDurationEndTime'] < $val['endAt']) {
                            $out['MaxInDurationEndTime'] = $val['endAt'];
                        }
                        $out['InDuration'] = true;
                        $out['Amount'] += $val['amountLeft'];
                        if ($out['InDurationEndAt'] < $val['endAt']) {
                            $out['InDurationBeginAt'] = $val['beginAt'];
                            $out['InDurationEndAt'] = $val['endAt'];
                        }
                    } elseif ($val['endAt'] < $timeNow && $out['OutDurationEndAt'] < $val['endAt']) {
                        $out['OutDurationBeginAt'] = $val['beginAt'];
                        $out['OutDurationEndAt'] = $val['endAt'];
                    }
                }
            }
            return $out;
        }
        return null;
    }
    /**
     * @codeCoverageIgnore
     */
    protected function paramsDP(&$ayou,$byou,$cyou){
        if(!isset($ayou[$byou]))
            $ayou[$byou] = $cyou;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function getBalance($params){
        try {
            $res = $this->objDaoTC->getBalances($params);
        }catch(Exception $e){
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        if ($res === false) {
            Gj_Log::warning($this->objDaoTC->getLastSQL());
            $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
            $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
        }
        return $res;
    }
}

