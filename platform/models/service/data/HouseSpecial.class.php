<?php
class Service_Data_HouseSpecial{
	protected $objDaoHousepremierHouseSpecial;
	public function __construct(){
		$this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;	
		$this->objDaoHousepremierHouseSpecial =  Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSpecial');
	}
	public function insertIntoHouseSpecial($arrRows){
		if (!is_array($arrRows)) {
            $this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
            $this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;;
        }else{
			try{
				$res = $this->objDaoHousepremierHouseSpecial->insert($arrRows);
			}catch(Exception $e){
				$this->data['errorno'] = $e->getCode();
                $this->data['errormsg'] = $e->getMessage();	
			}
			if(!$res){
				Gj_Log::warning($this->objDaoHousepremierHouseSpecial->getLastSQL());
                $this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
                $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;	
			}else{
				 $this->data['data'] = $res;
			}	
			return $this->data;
		}	
	}
	public function condition($arrRows){
		if(!empty($arrRows['city'])){
			$arrConds['company_city ='] = $arrRows['city'];	
		}
		if(!empty($arrRows['district'])){
			$arrConds['house_district ='] = $arrRows['district'];
		}
		if(!empty($arrRows['orginDate'])){
			$arrConds['create_time >='] = $arrRows['orginDate'];
		}
		return $arrConds;
	}
	public function selectData($arrRows){
		if(empty($arrRows)){
			$this->data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
			$this->data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;	
		}else{
			$fields = "user_name,house_xiaoqu,manager_name,manager_phone,house_price,house_link";			
			$arrConds = $this->condition($arrRows);
			try{
				$this->data['houseInfo'] = $this->objDaoHousepremierHouseSpecial->selectLimitData($fields,$arrConds);
				$this->data['houseCount'] = $this->objDaoHousepremierHouseSpecial->selectByCount($arrConds,null);
			}catch(Exception $e){
				 $this->data = array(
					'errorno' => $e->getCode(),
					'errormsg' => $e->getMessage(),
				 );
			}
		}
		return $this->data;
	}
}
