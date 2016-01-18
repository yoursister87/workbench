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
class Service_Page_Apartment_Apartment {
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
            $apartmentId = (int)$arrParams['apartmentId'];
            if(!isset($arrParams['apartmentId']) || !is_numeric($apartmentId)){
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG;
                return $this->arrRet;
            }
            $post = array();
            //获取公寓信息
            $post = $this->getApartmentInfo($apartmentId);
            if (empty($post)){
                $this->arrRet['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
                $this->arrRet['errormsg'] = ErrorConst::E_PARAM_INVALID_MSG; 
                return $this->arrRet;
            }
            //获取其他公寓信息
            $post['otherApartment'] = $this->getOtherApartment($post['company_id']);
            //获取样板间信息
            $post['modelroom'] = $this->getModelroomInfo($apartmentId);
            //获取配套信息
            $post['peitao'] = $this->getPeitao($apartmentId);
            //获取相册 
            $post['photo'] = $this->getPhoto($apartmentId);
            $this->arrRet['data'] = $post;
        } catch(Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->arrRet;
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
    /*{{{getOtherApartment
     *
     */
    protected function getOtherApartment($companyId, $isSame = true){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Info');
            $res = $objService->getApartmentListByCompanyId($companyId, $isSame);
        }  catch(Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($res);
    }//}}}
    /*{{{获取样板间信息
     * @param $apartmentId
     * @return array
     */
    protected function getModelroomInfo($apartmentId){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Modelroom');
            $ret = $objService->getModelroomByApartmentId($apartmentId, 100);
        } catch (Exception $e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($ret);
    }//}}}
    /*{{{获取配套信息
     * @param $apartmentId
     * @return 
     */
    protected function getPeitao($apartmentId){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Peitao');
            $ret = $objService->getApartmentPeitaoByApartmentId($apartmentId);
        } catch (Exception　$e){
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        } 
        return $this->formatData($ret);
    }//}}}
    /*{{{获取相册数据
     * @param $apartmentId
     * @return array
     */
    protected function getPhoto($apartmentId){
        try {
            $objService = Gj_LayerProxy::getProxy('Service_Data_Apartment_Photo'); 
            $ret = $objService->getApartmentPhotoByApartmentId($apartmentId);
        } catch (Exception $e) {
            $this->arrRet['errorno'] = $e->getCode();
            $this->arrRet['errormsg'] = $e->getMessage();
        }
        return $this->formatData($ret);
    }//}}}
    /*{{{formatData
     * @param @data
     * @return array
     */
    protected function formatData($data){
        if($data['errorno'] || empty($data['data'])) {
            return array();
        }
        return $data['data'];
    }//}}}
}
