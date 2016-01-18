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
class Service_Page_RealHouse_GetCommentHouseInfo
{
    protected $data;

    public function __construct()
    {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    //{{{execute
    /**
     * 评论详细
     * @param $arrInput
     * @return mixed
     */
    public function execute($arrInput)
    {
        $whereConds = array(
            'user_id'=>$arrInput['user_id'],
            'puid'=>$arrInput['puid'],
        );
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_HouseRealComment');
            $res = $objService->getCommentInfoByWhere($whereConds);
            $this->data = $res;
        }catch(Exception $e) {
            $this->data['errorno'] = $e->getCode();
            $this->data['errormsg'] = $e->getMessage();
        }
        return $this->data;
    }
}
