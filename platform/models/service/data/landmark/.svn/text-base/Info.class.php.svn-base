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
class Service_Data_Landmark_Info
{
    protected $successData = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    
    /**
     * 根据地标拼音获取地标名称、经纬度等信息
     * @param string $pinyin 地标拼音
     * @param array $arrFields 要获取的列
     * @return array
     */
    public function getLandmarkInfoByPinyin($pinyin, $arrFields = array())
    {
        try
        {
            if (empty($arrFields) || !is_array($arrFields))
            {
                $arrFields = array('*');
            }
            $arrConds = array('pinyin = ' => $pinyin);
            $daoModel = Gj_LayerProxy::getProxy('Dao_Fangproject_LandmarkInfo');
            $res = $daoModel->select($arrFields, $arrConds);
            $data = $this->successData;
            $data['data'] = $res[0];
        }
        catch (Exception $e)
        {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );                
        }
        return $data;
    }
}
?>
