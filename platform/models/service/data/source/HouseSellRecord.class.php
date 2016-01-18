<?php
/**
 * /**
 * @package
 * @subpackage
 * @author               $Author:   zhuyaohui$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */


class Service_Data_Source_HouseSellRecord
{
    /**
     * @var Dao_Housepremier_HouseSellRecord
     */
    protected $objDaoHouseSellRecord;
    protected $data;
    protected $arrFields = array("puid", "type", "account_id", "sellername", "sellerphone", "price", "price_unit");


    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDaoHouseSellRecord = Gj_LayerProxy::getProxy('Dao_Housepremier_HouseSellRecord');
    }

    /**
     * @codeCoverageIgnore
     */
    public function __call($name, $args)
    {
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this, $name), $args);
        }
    }


    //{{{getSellInfoByPuid
    /**
     * 查询售出记录，通过puid
     * @param $intPuid, $arrFields
     * @return array
     */
    public function getSellInfoByPuid($intPuid, $arrFields)
    {
        if(!count($arrFields)) {
            $arrFields = $this->arrFields;
        }
        $arrConds = array('puid = ' => $intPuid);
        try{
            $res = $this->objDaoHouseSellRecord->selectSellInfoBypuid($arrFields,$arrConds);
            if($res ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoHouseSellRecord->getLastSQL());
            }
            $this->data['data'] = $res[0];
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;

    }//}}}

    //{{{insertSellInfo
    /**
     * 插入出售记录
     * @param $arrFields
     * @return array
     */
    public function insertSellInfo($arrFields)
    {
        try{
            $res = $this->objDaoHouseSellRecord->insertSellInfo($arrFields);
            if($res ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
            }
            $this->data['data'] = $res;
        }catch (Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;

    }//}}}



}
