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
 * @codeCoverageIgnore
 */

class Service_Data_Source_Sticky
{
    private $objDaoSticky;
    protected $data;

    /**
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->objDaoSticky = Gj_LayerProxy::getProxy("Dao_Housepremier_Sticky");
    }

    /**
     * @brief 通过tg_puid，获取ms_puid
     * @param $tg_puid
     */
    public function getMspuidByTgpuid($tg_puid)
    {
        try {
            $arrFields = array('ms_puid');
            $arrConds = array('tg_puid =' => $tg_puid);
            $ms_puid = $this->objDaoSticky->select($arrFields, $arrConds);
            if($ms_puid === false){
                throw new Gj_Exception(ErrorConst::E_DB_FAILED_CODE,ErrorConst::E_DB_FAILED_MSG."sql :".$this->objDaoSticky->getLastSQL());
            }
            $this->data['data'] = $ms_puid;
        } catch (Exception $e) {
            $this->data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $this->data;
    }
}