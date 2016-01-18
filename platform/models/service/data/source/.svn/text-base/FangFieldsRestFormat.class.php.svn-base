<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Source_FangFieldsRestFormat{
    const FANG_CATEGORY_ID = 7;
    protected   $arrFieldsCategory = array(
        'public' => array('puid','user_id','username','password','city','district_id','district_name','street_id','street_name','title','ip','post_at','refresh_at','show_time','price','person','phone','image_count','thumb_img','deal_type','agent','listing_status','display_status','cookie_id','latlng','source_type','source_desc','major_category','minor_category','tag'),
        'audit' =>array('show_before_audit','show_before_audit_reason','editor_audit_status'),
        'description'=>array('description'),
        'business'=>array('top_info','ad_types','ad_status'));
    protected $objDaoFang;
    protected $objUtilGeo;
    protected $intCityCode;
    protected $intDistrictId;
    protected $intStreetId;
    public function __construct($objDaoFang){
        $this->objDaoFang = $objDaoFang;
        $this->objUtilGeo = new Gj_Util_Geo;
    }

    public function getFieldsByCategory($strCategory){
        if(empty($strCategory)){
            $strCategory = 'public';
        }
        $arrAllDaoFields = $this->objDaoFang->getValue('table_fields');
        $arrCategory = explode(',',$strCategory);

        if(in_array('extend',$arrCategory)){
            $this->arrFieldsCategory['extend'] = array_diff($arrAllDaoFields,$this->arrFieldsCategory['public'],
                $this->arrFieldsCategory['audit'],$this->arrFieldsCategory['description'],$this->arrFieldsCategory['business']);
        }
        $arrRetFields = array();
        foreach($arrCategory as $value){

            if(!isset($this->arrFieldsCategory[$value])){
                continue;
            }
            $arrRetFields = array_merge($arrRetFields,$this->arrFieldsCategory[$value]);
        }
        return $arrRetFields;
    }

    /**
     * @param $arrRet
     * @return array
     */
    public function formatRetFileds($arrRet){
        if(empty($arrRet)){
            return $arrRet;
        }
        $arrNewFields = array();
        foreach($arrRet as $strFieldsName =>$value){
            $strCheckFunc = "format".ucfirst(str_replace("_","",$strFieldsName));
            if(method_exists($this,$strCheckFunc)){
                $arrNewFields = array_merge($arrNewFields, $this->$strCheckFunc($value));

            }else{
                $arrNewFields[$strFieldsName] = $value;
            }
        }
        return $arrNewFields;
    }

    /**
     * 将原来的city改为city_id
     * @param $intField
     * @return array
     */
    protected function formatCity($intField){
        $this->intCityCode = $intField;
        $res = $this->objUtilGeo->getCityByCityCode($intField);
        $city_id = $res['id'];
        return array('city_id' =>$city_id );
    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatPostat($intField){
        return array('create_time'=>$intField);

    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatShowtime($intField){
        return array('display_time'=>$intField);

    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatRefreshat($intField){
        return array('update_time'=>$intField);

    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatListingStatus($intField){
        if($intField > 5){
            $intField = 5;
        }
        return array('listing_status' => $intField);
    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatDistrictid ($intField){
        $this->intDistrictId = $intField;
        $res = $this->objUtilGeo->districtScriptIndex2DistrictInfo($this->intCityCode,$intField);
        $district_id = $res['id'];
        return array('district_id' => $district_id);
    }

    /**
     * @param $intField
     * @return array
     */
    protected function formatStreetid ($intField){
        $res = $this->objUtilGeo->streetScriptIndex2StreetInfo($this->intCityCode,$this->intDistrictId,$intField);
        $street_id = $res['id'];
        return array('street_id' => $street_id);
    }

    protected function formatMajorcategory($intField){

        $objCategory = new Util_Source_Category();
        $res = $objCategory->getMajorCategoryByScriptIndex(2,$intField);
        return array('major_category_id' => $res['id'],'category_id' => $res['parent_id']);
    }


   
}