<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: liuzhen1 <liuzhen1@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 * @codeCoverageIgnore
*/ 

class Service_Data_Xiaoqu_Photo
{ 
    /* getXiaoquOutdoorPhoto 获取小区外景图片 {{{ */ 
    /**
     * 获取小区外景图片 
     * @param  int  $xiaoquId 小区id
     * @param  int  $limit    限制返回几条数据
     * @parma  int  $type     图片类型 0.全部 1.实景图 2.户型图 3.样板间 4.周边配套图 5.效果图
     * @return array
     *
     * @codeCoverageIgnore
     */
    public function getXiaoquOutdoorPhoto($xiaoquId, $limit=0, $type=1)
    {
        $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquPhoto');
        try {
            $items = $model->getXiaoquOutdoorPicture($xiaoquId, $limit, $type);
            $totalCount = $model->getXiaoquOutdoorPictureTotalCount($xiaoquId, $type);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$items, 'total'=>$totalCount),
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;        
    } //}}} 
    /* getXiaoquHuxingPhoto 获取小区户型图片 {{{ */
    /**
     * 获取小区户型图片
     * @param  int  $xiaoquId  小区id
     * @param  int  $huxingShi 几室 0.代表所有 1,2,3,4,5代表几室
     * @param  int  $limit     限制返回几条数据
     * @return array
     * 
     * @codeCoverageIgnore
     */
    public function getXiaoquHuxingPhoto($xiaoquId, $huxingShi=0, $limit=0)
    {
        $model = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquHuxing');
        try {
            $items = $model->getXiaoquHuxingPicture($xiaoquId, $huxingShi, $limit);
            $totalCount = $model->getXiaoquHuxingPictureTotalCount($xiaoquId, $huxingShi);
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$items, 'total'=>$totalCount),
            );
        } catch (Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    } //}}} 
}
