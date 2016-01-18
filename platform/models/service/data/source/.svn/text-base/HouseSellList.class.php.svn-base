<?php
/**
 * @package
 * @subpackage
 * @brief
 * @author               $Author:   wuyirui <wuyirui@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_HouseSellList{
    protected $arrRet = array(
        'errorno'  => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    protected $arrFields = array('house_id','puid','account_id','thumb_img','price','area','district_id','district_name','xiaoqu_id','xiaoqu','huxing_shi','huxing_ting');

    /*
     * 根据house_id获取二手房信息
     * */
    public function getHouseInfoByIds($houseIdArr){
        $objDaoSourceList = Gj_LayerProxy::getProxy("Dao_Housepremier_HouseSourceSellPremier");
        if(empty($houseIdArr)) {
            return $this->arrRet;
        }
        $strIds = implode(',',$houseIdArr);
        $arrConds = array('house_id in (' . $strIds . ')');
        try{
            $this->arrRet['data'] = $objDaoSourceList->select($this->arrFields, $arrConds);
        }catch(Exception $e){
            $this->arrRet = array(
                'errorno' =>ErrorConst::E_DB_FAILED_CODE,
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }
}
