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

class Service_Data_Source_UserRefreshNum
{
    /**
     * @var Dao_Housepremier_UserRefreshNum
     */
    private $objDaoUserRefreshNum;
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->objDaoUserRefreshNum = Gj_LayerProxy::getProxy("Dao_Housepremier_UserRefreshNum");
    }

    /**
     * @brief 通过account_id，获取该账号本月累计上架的真实房源数量
     * @param $intAccountId
     * @param null $arrHouseType
     */
    public function getRefreshInfoByAccount($arrInput)
    {
        try {
            $arrFields = array('account_id', 'daynum', 'num', 'bussiness_scope');
            //上架的真实房源数据每月只存一条，每次新上架房源只更新此数据；表中的时间为每月第一天0时
            $arrConds = array('account_id=' => $arrInput['account_id'], 'bussiness_scope=' => $arrInput['bussiness_scope'], 'daynum=' => $arrInput['daynum']);
            $RealHouseCount = $this->objDaoUserRefreshNum->getRealHouseCountByAccount($arrFields, $arrConds);
            if($RealHouseCount === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoUserRefreshNum->getLastSQL());
            }
            $this->arrRet['data'] = $RealHouseCount;
        } catch (Exception $e) {
            $this->arrRet = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }

    /**
     * @brief 通过account_id，更新该账号本月累计上架的真实房源数量
     * @param $intAccountId
     * @param null $arrHouseType
     */
    public function updateNumByAccount($arrChangeRow,$intAccountId,$bussiness_scope)
    {
        try {
            $arrConds = array('account_id =' => $intAccountId, 'daynum = ' => mktime(0, 0, 0, date('m'), 1, date('Y')), 'bussiness_scope' => $bussiness_scope);
            $arrRes = $this->objDaoUserRefreshNum->update($arrChangeRow,$arrConds);
            if($arrRes ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'update failed');
            }
        }  catch(Exception $e) {
            $this->arrRet = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->arrRet;
    }


    //{{{insertSellInfo
    /**
     * 插入本月累计上架真房源数量
     * @param $arrFields
     * @return array
     */
    public function insertNumCountInfo($arrFields)
    {
        try{
            $res = $this->objDaoUserRefreshNum->insertCountInfo($arrFields);
            if($res ===false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,'insert failed');
            }
        }catch (Exception $e) {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->arrRet;

    }//}}}




}
