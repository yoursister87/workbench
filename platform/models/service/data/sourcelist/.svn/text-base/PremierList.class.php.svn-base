<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Sourcelist_PremierList{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    protected $arrFields = array('house_id','puid','type','account_id','user_code','city','title','history_count','yesterday_count','district_id','district_name','street_id','street_name','xiaoqu_id','xiaoqu_name','post_at','premier_status','listing_status');
 /**
 * @brief 根据产品编号以及列表类型获得格式化好的帖子数据
 *
 * @param int $aid 房产用户ID
 * @param int $pg 产品组别
 * @param int $pi 产品组内编号
 * @param int $listType 列表也类型，参见getXiaoqu
 * @param int $type 可选，房源筛选类型
 * @param int $biotopeId 可选，小区筛选ID
 * @param int $page 可选，分页第几页
 * @param int $pageSize 可选，分页每页大小
 * @param int $format 可选，需要格式化的数据
 *
 * @return Array(..) || int errCode
 */
    public function getPostList($aid, $pg, $pi, $listType, $type = NULL, $biotopeId = NULL, $page = 1, $pageSize = 30, $format = 127){
        try {
            $tgApiUrl = 'http://tg.dns.ganji.com/api.php?c=SourceList&a=getPostList';
            $arrInput = func_get_args();
			#由于crm接口调整，导致这边经纪人店铺获取到的都是-1，页面显示不正常，所以需要调整为传递参数的数组必须是key=>val格式，暂时废弃$arrInput数组 FANG-10163
			$arrInputNew = array(
				'accountId'	=> $aid,
				'pg'		=> $pg,
				'pi'		=> $pi,
				'listType'	=> $listType,
				'type'		=> $type,
				'biotopeId'	=> $biotopeId,
				'page'		=> $page,
				'pageSize'	=> $pageSize,
				'format'	=> $format
			);
            $objCurl = new Gj_Util_Curl();
            $res = $objCurl->post($tgApiUrl,$arrInputNew);
             $this->arrRet['data'] = json_decode($res,true);
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }

    /**
     * @param $intPuid
     *
     * @return int
     *0 会员中心贴； house_source_list 中取不到 都返回0；
     *1 精品贴； biz_type 为1
     *2 放心房； premier_status 为100 或102
     *3 免费端口贴； biz_type 为3
     *4 真房源; premier_status 为 110 、 111、112
     */
    public function getAdType($intPuid){
	$intPuid = (int) $intPuid;
	if ($intPuid < 1) return 0;

        $objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");
        $arrFields = array('premier_status','biz_type');
        $arrConds = array('puid = '=>$intPuid);
        $arrRet = $objDaoSourceList->select($arrFields,$arrConds);
        if(empty($arrRet)){
            return 0;
        }
        if(in_array($arrRet[0]['premier_status'],array(110,111,112))){
            return 4;
        }
        return $arrRet[0]['biz_type'];
    }
	public function getHouseCountByUserId($whereConds){
		 $this->objDao = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSourceList');	
		 $ret = $this->objDao->selectByCount($whereConds,null);
		 return $ret;
	}
	public function getPostCountByAccountId($arrInput,$whereConds){
		if(empty($arrInput)){
			return $this->arrRet;
		}   
		$objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");    
		$fields = array('COUNT(1) AS house_count','account_id','type');
		try{
			$this->arrRet = $objDaoSourceList->selectGroupbyAccountId($fields,$whereConds);    
		}catch(Exception $e){
			$this->arrRet = array(
				'errorno' =>ErrorConst::E_DB_FAILED_CODE,
				'errormsg' => $e->getMessage(),
			);
		}
		return $this->arrRet;
	}
	public function getPostCountByAccountId1($arrInput,$whereConds){
		if(empty($arrInput)){
			return $this->arrRet;
		}
		$objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceList");    
		$fields = array('COUNT(1) AS count','account_id','type');
		try{
			$this->arrRet = $objDaoSourceList->selectGroupbyAccountId($fields,$whereConds);    
		}catch(Exception $e){
			$this->arrRet = array(
				'errorno' =>ErrorConst::E_DB_FAILED_CODE,
				'errormsg' => $e->getMessage(),
			);  
		}
		return $this->arrRet;
	} 
    
    /**
      * @brief 从Hibase获取端口贴，不限定城市
      * @param $queryArr
      *
      * @return     
      */
     public function search($queryConfigArr){
         
         $model = Gj_LayerProxy::getProxy('Dao_Hibase_Alltg');
         $ret = $model->search($queryConfigArr);
         return $ret;
     }
}
