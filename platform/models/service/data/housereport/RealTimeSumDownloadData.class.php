<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Service_Data_HouseReport_RealTimeSumDownloadData extends Service_Data_HouseReport_GroupOutletData
{
	protected $productData = array(
			Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT => array(
					'title'=>'用户信息',
					'title_data' => array(
							'account_id'=>'编号', #登录账号
							'account_name'=>'姓名',
							'account_email'=>'邮箱',
							'outlet_name'=>'门店',
							'plate_name'=>'板块',
							'area_name'=>'区域',
							'phone'=>'手机',
							'owner_type'=>'账号类型',
							'status'=>'端口状态',
							'login_count'=>'登录次数',
					),
			),
			Service_Data_HouseReport_GroupData::PREMIER => array(
					'title'=>'精品',
					'title_data'=>array(
							'last_deposit_time'=>'开户或续费时间',
							'premier_end_time'=>'精品到期时间',
							'biz_text'=>'套餐类型',
							'house_total_count'=>'房源总数',
							'house_count'=>'新增',
							'premier_count'=>'推广','premier_scale'=>'推广率',
							'refresh_count'=>'刷新','refresh_scale'=>'刷新率'
					),
			),
			Service_Data_HouseReport_GroupData::ASSURE => array(
					'title'=>'放心房',
					'title_data'=>array(
							'last_deposit_time'=>'开户或续费时间',
							'premier_end_time'=>'精品到期时间',
							'biz_text'=>'放心房类型',
							'house_total_count'=>'房源总数',
							'house_count'=>'新增',
							'premier_count'=>'展示','premier_scale'=>'展示率',
							'refresh_count'=>'刷新','refresh_scale'=>'刷新率'
					),
			),
			Service_Data_HouseReport_GroupData::CENTER_BALANCE => array(
				'title'=>'会员中心余额',
				'title_data'=>array(
                    'userCenterAwardBalance' => '会员中心赠送余额',
                    'userCenterCashBalance' => '会员中心付费余额'
				),
				//'group'=>2  #组别
			),
			Service_Data_HouseReport_GroupData::BID_BALANCE => array(
				'title'=>'竞价余额',
				'title_data'=>array(
					'userBidBalance'=>'竞价余额',
				),
				//'group'=>3  #组别
			),
	);
	public function __construct(){
		$this->data['data'] = array();
		$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
		$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
	}
	/*
	 *@codeCoverageIgnore
	*/
	public function __call($name,$args){
		if (Gj_LayerProxy::$is_ut === true) {
			return  call_user_func_array(array($this,$name),$args);
		}
	}
	/*{{{得到排序字段*/
	/**
	 *@codeCoverageIgnore
	 */
	protected function getSortCategory(){
		return array(
				Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT,
				Service_Data_HouseReport_GroupData::PREMIER,
				Service_Data_HouseReport_GroupData::ASSURE,
				Service_Data_HouseReport_GroupData::CENTER_BALANCE,
				Service_Data_HouseReport_GroupData::BID_BALANCE,
		);
	}
	/*{{{groupDownLoadData 合并下载数据*/
	/**
	 *
	 * @param unknown $tags
	 * @param unknown $params
	 */
	public function groupDownLoadData($tags,$params){
		$groupdataService = Gj_LayerProxy::getProxy('Service_Data_HouseReport_GroupData');
		$data[Service_Data_HouseReport_GroupData::PREMIER] = $this->getPremierData($params);
		if (is_array($tags) && !empty($tags)){
			foreach ($tags as $item) {
				if (!in_array($item,array(
						Service_Data_HouseReport_GroupData::TOTAL,
						Service_Data_HouseReport_GroupData::PREMIER,
						Service_Data_HouseReport_GroupData::ASSURE,
						Service_Data_HouseReport_GroupData::FRAME_ORG_DATA,
						Service_Data_HouseReport_GroupData::FRAME_USE_DATA,
				))) {
					throw new Gj_Exception(Service_Data_HouseReport_CheckData::PRODUCT_TAGS_ERROR_NUM,"没有相应的tag配置");
				}
				switch ($item) {
					case Service_Data_HouseReport_GroupData::ASSURE:
						$data[Service_Data_HouseReport_GroupData::ASSURE] = $this->getAssureData($params);
						break;
					
				}
			}
			//添加账号信息
			$accountInfoList = $this->getAccountData($params,$params['accountIds'],$data);
			//添加经纪人邮箱
			$accountEmailList = $this->getCrmAccountEmail($params,$data[Service_Data_HouseReport_GroupData::PREMIER]['data']);
			$data[Service_Data_HouseReport_GroupData::ORGDOWNLOAD_ACCOUNT] = array_merge_recursive($accountInfoList,$accountEmailList);
			if (!in_array(Service_Data_HouseReport_GroupData::PREMIER, $tags)) {
				unset($data[Service_Data_HouseReport_GroupData::PREMIER]);
			}
            //获得端口类别
            $data[Service_Data_HouseReport_GroupData::BUSINESS_SCOPE] = $groupdataService->getBusinessScope($params,$this->indexFieldName);
            //添加accountId
            $data[Service_Data_HouseReport_GroupData::ACCOUNT_ID] = $this->getAccountId($params,$data);
			//添加会员中心余额
			$data[Service_Data_HouseReport_GroupData::CENTER_BALANCE ] = $this->getUserCenterBalance($params,$data);
			$data[Service_Data_HouseReport_GroupData::BID_BALANCE]  = $this->getBidBalance($params,$data);
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
