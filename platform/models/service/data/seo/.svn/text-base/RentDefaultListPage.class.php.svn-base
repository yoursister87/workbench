<?php
/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $wanyang <wanyang@ganji.com>$
 * @file                 $RentDefaultListPage.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-05$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Seo_RentDefaultListPage implements Service_Data_Seo_InterfacePath
{
    protected $prefix = 'RentDefault_';
    protected $Page;
    private $feifaParams = array(
            "category",
            "listPage",
            "agentTitle",
            "pageType",
            "tab_system",
            "path",
            "page",
            );
    /**
     * @param $sPageName
     * @param $sPathName
     * @param $aParams
     */
    public function __construct($aParams)
    {
        $this->Page = Gj_LayerProxy::getProxy("Service_Data_Seo_Page",$aParams);
    }

    /**
     * @return array
     */
    public function getMetaInfo()
    {
        $meta = $this->getFinalTemplate();
        return $meta;
    }

    public function getFinalTemplate(){
        $aResult = array();
        $aMetas = array("title","description","keywords");
        foreach($aMetas as $value) {
            $templateKey = $this->generateTemplateKey($value);
            $template = $this->Page->getTpl($templateKey);
            if(!$template){
                throw new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE) ;
            }
            $aResult[$value] = $this->Page->replaceTpl($template);
        }
        return $aResult;
    }

    /**
     * @param array $params
     * @return string
     */
    public function generateTemplateKey($value)
    {
        if($value == 'title'){
            $key = $this->generateTemplateTitle($value);
        }else if($value == 'description'){
            $key = $this->generateTemplateDescription($value);
        }else{
            $key = $this->generateTemplateKeyword($value);
        }
        return array($value,$this->prefix.$key);
    }

    protected function generateTemplateTitle(){
        if($this->hasGeo()) {
            $key = $this->genKeyWithGeo();
        }else{
            $key = $this->genKeyNoGeo();
        }
        if($sKey = $this->genKeySpecial()){
            $key = $sKey;
        }
        return $key;
    }

    protected function generateTemplateDescription(){
        if($this->hasGeo()) {
            $key = $this->genKeyWithGeo();
        }else{
            $key = $this->genKeyNoGeo();
        }
        if($sKey = $this->genKeySpecial()){
            $key = $sKey;
        }
        if($this->isOnlyHuxing()){
            $key = 17;
        }
        if(in_array($key ,array('5','11','18'))) {
            $key = '4';
        }
        return $key;
    }

    protected function generateTemplateKeyword(){
        if($this->hasGeo()) {
            $key = $this->genKeyWithGeo();
        }else{
            $key = $this->genKeyNoGeo();
        }
        if($sKey = $this->genKeySpecial()){
            if($sKey){
                $key = $sKey;                
            }
        }
        if($this->isOnlyHuxing()){
            $key = 17;
        }
        if(in_array($key ,array('5','11','18'))) {
            $key = '4';
        }
        return $key;
    }

    protected function genKeyWithGeoPurePersonFilter()
    {
        /* if ($this->Page->getStreetInfo()) { */
        /*     $key = '1'; */
        /* } else{  */
        /*     $key = '2'; */
        /* } */
        if($this->Page->getStreetInfo() || $this->Page->getDistrictInfo()){
            $key = "19";
        }
        return $key;
    }

    protected function genKeyWithGeoTrueHouse()
    {
        if ($this->Page->getFilterArray()) {
            $key = '4';
        } else {
            $key = '5';
        }
        return $key;
    }

    protected function genKeyWithGeo()
    {
        $key = '';
        if ($this->isPurePerson() && !$this->Page->getFilterArray()) {
            $key = $this->genKeyWithGeoPurePersonFilter();
        }
        if (!$key && $this->isPurePerson() && $this->Page->getFilterArray()) {
            $key = "19";
        }

        if (!$key && $this->isTrueHouse()) {
            $key = $this->genKeyWithGeoTrueHouse();
        }
        if (!$key && $this->isAgent()) {
            $key = '6';
        }
        if (!$key && $this->Page->getFilterArray()) {
            $key = '7';
        }
        if (!$key && $this->isPersonal()) {
            $key = '8';
        }
        if (!$key) {
            $key = '9';
        }
        return $key;
    }

    protected function genKeyNoGeoTrueHouse()
    {
        if ($this->Page->getFilterArray()) {
            $key = '11';
        } else {
            $key = '12';
        }
        return $key;
    }

    protected function genKeyNoGeo()
    {
        $key = '';
        if ($this->isPurePerson() && !$this->Page->getFilterArray()) {
            $key = '10';
        } 
        if ($this->isPurePerson() && $this->Page->getFilterArray()) {
            $key = '19';
        }

        if (!$key && $this->isTrueHouse()) {
            $key = $this->genKeyNoGeoTrueHouse();
        }
        if (!$key && $this->isAgent()) {
            $key = '13';
        }
        if (!$key && $this->isPersonal()) {
            $key = $this->genKeyNoGeoPersonal();
        }
        if (!$key && $this->Page->getFilterArray()) {
            $key = '14';
        }
        if (!$key) {
            $key = '16';
        }
        return $key;
    }
    
    protected function genKeyNoGeoPersonal(){
        if($this->Page->getFilterArray()){
            $key = 20;
        }else{
            $key = 15;
        }
        return $key;
    }

    protected function genKeySpecial(){
       /**
        if($this->isPurePerson()){
            return false;
        }
        */
        //strange
        if ($this->isOnlyHuxing() && !$this->hasGeo()) {
            $key = '17';
        }
        if ($this->isOnlyHuxingAndZufang()) {
            $key = '18';
        }
        return $key;
    }


    /**
     * @brief 是否含有区域或街道信息
     * @return bool
     */
    protected function hasGeo()
    {
        if ($this->Page->getDistrictInfo() || $this->Page->getStreetInfo()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @brief 是否100%个人
     * @return bool
     */
    protected function isPurePerson()
    {
        if ($this->Page->getParams('agent') == 1 && in_array($this->Page->getCityInfo('domain'), HousingVars::$rent100personalCity)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 是否放心房
     * @return bool
     */
    protected function isTrueHouse()
    {
        if ($this->Page->getParams('agent') == 4) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 是否经纪人
     * @return bool
     */
    protected function isAgent()
    {
        if ($this->Page->getParams('agent') == 2) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 是否个人
     * @return bool
     */
    protected function isPersonal()
    {
        if ($this->Page->getParams('agent') == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 只有huxing_shi参数
     * @return bool
     */
    public function isOnlyHuxing()
    {
        if (count($this->Page->getFilterArray()) == 1 && $this->Page->getFilterArray('huxing_shi')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @brief 只有huxing_shi和zufang2个参数
     * @return bool
     */
    public function isOnlyHuxingAndZufang()
    {
        if (count($this->Page->getFilterArray()) == 2 && $this->Page->getFilterArray('huxing_shi') && $this->Page->getFilterArray('zufang')) {
            return true;
        } else {
            return false;
        }

    }

    // {{{ just for test
    /**
     * @brief 
     *
     * @param $name
     * @param $value
     *
     * @returns   
     * @codeCoverageIgnore
     */
    public function __set($name, $value) {
        if (Gj_LayerProxy::$is_ut === true) {
            $this->$name = $value;
        }
    }
    /**
     * @brief 
     *
     * @param $func
     * @param $args
     *
     * @returns   
     * @codeCoverageIgnore
     */
    public function __call($func, $args) {
        if (Gj_LayerProxy::$is_ut === true) {
            return call_user_func_array(array($this,$func), $args);
        }
    }
    //}}}

}
