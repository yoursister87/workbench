<?php
/*
 * File Name:ReportService.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Service_Data_HouseReport_ReportService
{
    const COMPANY_LEVEL = 1;
    const AREA_LEVEL = 2;
    const PLATE_LEVEL = 3;
    const OUTLET_LEVEL = 4;
    const BROKER_LEVEL = 5;
    const BLOCK_LEVEL = 6;
    const BLOCKER_MANAGE_LEVEL = 6;

    public function setHouseType($params){
        $businessScope = $params['businessScope'];
        if (empty($businessScope)) {
            return array(0);
        }
        $result = array();
        foreach ($businessScope as $item) {
            if (isset(HousingVars::$bizToTypes[$item])) {
                $result = array_merge($result,HousingVars::$bizToTypes[$item]);
            }
        }
        $result = array_unique($result);
        return $result;
    }

    //获得相应这个等级下面的所有门店
    public function getAllOutlet($orgId){
        $houseAccount =  Gj_LayerProxy::getProxy('Service_Data_Gcrm_HouseManagerAccount');
        $customerIds = array();
        $tmpData = $houseAccount->getChildTreeByOrgId($orgId,4,array(),null,null);
        foreach($tmpData['data']['list'] as $item) {
            $customerIds[] = $item['customer_id'];
        }
        return $customerIds;
    }

    public function getCommonParams($arrInput){
        $params['product']  = $arrInput['product'];
        $params['companyId'] = $arrInput['companyId'];
        $params['pid'] = $arrInput['pid'];
        $params['level'] = $arrInput['level'];
        $params['customerId'] = $arrInput['cid'];
        //查询这个区域下面的所有
        $params['orgIds']  = $arrInput['orgIds'];
        //当前页
        $params['page'] = $arrInput['page'];
        $params['userId'] = $arrInput['userId'];
        $params['userLevel'] = $arrInput['userLevel'];

        //设置关键字搜索
        $params['keyword'] = addslashes(trim($arrInput['kword']));
        $params['stype'] = $arrInput['stype'];
        //数据类型
        $params['dtype'] = $arrInput['dtype'];
        $params['domain'] = $arrInput['domain'];
		$params['city_code'] = $arrInput['city_code'];
        return $params;
    }
    //得到分页的字符串
    public function getPageStr($page,$totalCount, $level){
        //每页15条
        $config['list_rows'] = Service_Data_HouseReport_GroupData::PAGE_SIZE;
        //总页数
        $config['total_rows'] = $totalCount;
        $config['now_page'] = $page;
        $config['method'] = 'ajax';
        $config['func_name']= 'pagination';
        $config['parameter']='pagination';
        $config['is_total'] = true;
        $total_str = '';
        switch ($level) {
            case 1:$total_str='<li><span class="all-broker">共<i>%</i>个区域</span></li>';break;
            case 2:$total_str='<li><span class="all-broker">共<i>%</i>个板块</span></li>';break;
            case 3:$total_str='<li><span class="all-broker">共<i>%</i>个门店</span></li>';break;
            case 4:$total_str='<li><span class="all-broker">共<i>%</i>位经纪人（付费）</span></li>';break;
			case 9:$total_str='<li><span class="all-broker">共<i>%</i>位经纪人（付费）</span></li>';break;
        }
        $config['total_str'] = $total_str;

        $pageObj = new Util_HouseReport_Page($config);
        return $pageObj->show();
    }

}
