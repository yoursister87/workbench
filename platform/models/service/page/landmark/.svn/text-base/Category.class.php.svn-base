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
class Service_Page_Landmark_Category
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
            $parentId = (int) $arrParams['parentId'];
            if (!isset($arrParams['parentId']) || !is_numeric($parentId))
            {
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->arrRet;
            }
            // 获取父级分类下的子分类
            $post = $this->getCategoryByParentId($parentId);
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
     * 获取父级分类下的子分类
     * @param int $parnetId
     * @return array
     */  
    protected function getCategoryByParentId($parentId)
    {
        try
        {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Landmark_Category');
            $res = $objService->getCategoryByParentId($parentId);
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
     * @param $data
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

