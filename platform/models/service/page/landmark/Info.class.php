<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   gaoguangyang <gaoguangyang@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Page_Landmark_Info
{
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * execute
     * @param $arrParams
     * @return array
     */ 
    public function execute($arrParams)
    {
        try
        {
            $pinyin = $arrParams['pinyin'];
            if (!isset($arrParams['pinyin']))
            {
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->arrRet;
            }
            // 获取地标信息
            $post = $this->getLandmarkInfo($pinyin);
            if (empty($post))
            {
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->arrRet;
            }
            $this->arrRet['data'] = $post;
        }
        catch (Exception $e)
        {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }   
        return $this->arrRet;
    }    

    /**
     * 获取地标信息
     * @param string $yinpin
     * @return array
     */
    protected function getLandmarkInfo($pinyin)
    {
        try
        {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Landmark_Info');
            $res = $objService->getLandmarkInfoByPinyin($pinyin);
        }
        catch (Exception $e)
        {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($res);
    }

    /**
     * formatData
     * @param @data
     * @return array
     */ 
    protected function formatData($data)
    {
        if ($data['errorno'] || empty($data['data']))
        {
            return array();        
        }
        return $data['data'];
    }
}
