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
class Service_Data_Landmark_Category
{
    protected $successData = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    /**
     * 根据父级分裂id获取子集所有分类
     * @param int $parentId 父级分类id
     * @param array $arrFields 要获取的列
     * @return array
     */
    public function getCategoryByParentId($parentId, $arrFields = array())
    {
        try
        {
            $parentId = intval($parentId);
            if (empty($arrFields) || !is_array($arrFields))
            {
                $arrFields = array('*');
            }
            $arrConds = array('parent_id' => $parentId);
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_LankmarkCategory');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res;
        }
        catch (Exception $e)
        {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage()
                );    
        }
        return $data;
    }
}
