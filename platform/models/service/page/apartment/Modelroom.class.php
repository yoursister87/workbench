<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author: liuzhen1 <liuzhen1@ganji.com>$
 * @author               $Author: zhenyangze <zhenyangze@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Service_Page_Apartment_Modelroom {
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );
    /*{{{execute
     * @param $arrParams
     * @return array
     */
    public function execute($arrParams){
        try {
            $puid = (int)$arrParams['puid'];
            if(!isset($puid) || !is_numeric($puid)){
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;

                return $this->arrRet;
            }
            $post = array();
            //获取样板间信息
            $data = $this->getModelroomInfo($puid);
            if ($data['errorno'] !== ErrorConst::SUCCESS_CODE){
                return ;
            }
            $post = $data['data'];
            //获取相册信息
            $post['photo'] = $this->getPhoto($post['puid']);
            //获取配套信息
            //获取公寓信息
            $post['apartment'] = $this->getApartmentInfo($post['apartment_id']);
            $post['peitao'] = $this->getPeitao($post['apartment_id']);
            //获取不同公寓其他样板间信息
            $post['otherModelroom'] = $this->getOtherModelroom($puid, 5);
            //获取同公寓下样板间信息
            $post['lookAndLookModelroom'] = $this->getOtherModelroom($puid, 3);
            $this->arrRet['data'] = $post;
        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg']  = $e->getMessage();
        }
        return $this->arrRet;
    }//}}}
    /*{{{getModelroomInfo
     *
     */
    protected function getModelroomInfo($puid){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_FangQuery'); 
            $ret = $objService->getHouseSourceByPuidInfo($puid);
        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $ret;
    }//}}}
    /*{{{getPhoto
     *
     */
     protected function getPhoto($puid){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Source_Image'); 
            $ret = $objService->getImageListByPuid($puid);
        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($ret);
    }//}}}
     /*{{{获取公寓信息
     * @param $apartmentId
     * @return array
     */
    protected function getApartmentInfo($apartmentId){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Info'); 
            $res = $objService->getApartmentInfoById($apartmentId);
        } catch(Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($res);
    }//}}}

    /*{{{getPeitao
     *
     */
    protected function getPeitao($apartmentId){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Peitao');
            $ret = $objService->getApartmentPeitaoByApartmentId($apartmentId);

        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($ret);;
    }//}}}
    /*{{{getOtherModelroom
     *
     */
    protected function getOtherModelroom($puid, $num = 6){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Modelroom'); 
            $ret = $objService->getRecommendModelroom($puid, $num);
        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($ret);
    }//}}}

    /*{{{formatData
     *
     */
    protected function formatData($data){
        if ($data['errorno'] !== '0' || empty($data['data'])){
            return array();
        } 
        return $data['data'];
    }
}
