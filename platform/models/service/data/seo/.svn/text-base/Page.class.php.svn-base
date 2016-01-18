<?php

/**
 * @package
 * @subpackage
 * @brief                $Page基类$
 * @author               $wanyang <wanyang@ganji.com>$
 * @file                 $Page.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-05$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Seo_Page
{
    private $pageName = "";
    private $pathName = "";
    private $params = array();
    private $leftMark = "{";
    private $rightMark = "}";
    private $splitMark = "|";
    private $cityInfo;
    private $districtInfo;
    private $streetInfo;
    private $filterArray;
    private $lineInfo;
    private $stationInfo;
    private $busInfo;
    private $collegeInfo;
    private $filterConfig = array('page','path','majorPath','category','pageType','city','district','street','agent', 'district_street', 'tag_type', 'bus_line', 'subway_line', 'station', 'college',"postCount","share_mode",'agentTitle',"list_type","file_path");

    public function __construct($params)
    {
        list($sPageName, $sPathName, $aParams) = $params;
        $this->setPageName($sPageName);
        $this->setPathName($sPathName);
        $this->setParams($aParams);
        $this->setCityInfo($aParams['city']);
        $this->setFilterArray();
        if($this->getParams("list_type") == "ditie" || $this->getParams("majorPath") == "subway"){
            $this->setLineInfo();
        }
        if($this->getParams("list_type") == "bus" || $this->getParams("majorPath") == "bus"){
            $this->setBusInfo();            
        }
        if($this->getParams("list_type") == "daxue" || $this->getParams("majorPath") == "college"){
            $this->setCollegeInfo();
        }
        if(!$this->getParams("list_type") || !in_array($this->getParams("list_type"),array("ditie","daxue","bus"))){
            $this->setDistrictInfo();
        }
    }

    protected function setCityInfo($domain){
        $aResult = GeoNamespace::getCityByDomain($domain);
        if($aResult){
            $this->cityInfo = $aResult;
            return true;
        }
        else{
            $this->cityInfo = array();
            return false;
        }
        /* throw new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE) ; */
    }

    protected function setDistrictInfo(){
        $city_id = $this->getCityInfo("id");
        $district_street_url = $this->getParams("district_street");
        // quote from ganji_v5/app/common/PostListPage.class.php
        if (strpos($district_street_url, '-') !== false) {
            $urls = explode('-', $district_street_url);
            $multi_street_id = array();
            $streetList = array();
            foreach ($urls as $url) {
                $streetInfo = GeoNamespace::getDistrictOrStreetByUrl($city_id, $url);
                if (!empty($streetInfo) && $streetInfo['id']) {
                    $streetList[] = $streetInfo;
                    $multi_street_id[] = $streetInfo['id'];
                }
            }
            if (count($streetList) > 0) {
                $districtInfo = GeoNamespace::getDistrictById($streetList[0]['parent_id']);
                if (!empty($districtInfo) && !empty($districtInfo['id'])) {
                    $this->districtInfo = $districtInfo;
                }
            }
        } elseif ($district_street_url) {
            $areaInfo = GeoNamespace::getDistrictOrStreetByUrl($city_id, $district_street_url);
            if (!empty($areaInfo) && $areaInfo['id']) {
                if ($areaInfo['type'] == 3) {
                    $this->districtInfo = $areaInfo;
                } else if ($areaInfo['type'] == 4) {
                    $this->setStreetInfo($areaInfo);
                    $districtInfo = GeoNamespace::getDistrictById($areaInfo['parent_id']);
                    if (!empty($districtInfo) && !empty($districtInfo['id'])) {
                        $this->districtInfo = $districtInfo;
                    }
                }
            }
        }
        //quote finished
        if($this->districtInfo){
            return true;
        }
        return false;
    }

    protected function setStreetInfo($aStreetInfo){
        $this->streetInfo = $aStreetInfo;
        if($this->streetInfo){
            return true;
        }
        return false;
    }
    
    public function getCityInfo($key = ""){
        if($key){
            return isset($this->cityInfo[$key]) ? $this->cityInfo[$key] : NULL;
        }
        return $this->cityInfo;
    }

    public function getDistrictInfo($key = ""){
        if($key){
            return isset($this->districtInfo[$key]) ? $this->districtInfo[$key] : NULL;
        }
        return $this->districtInfo;
    }

    public function getStreetInfo($key = ""){
        if($key){
            return isset($this->streetInfo[$key]) ? $this->streetInfo[$key] : NULL;
        }
        return $this->streetInfo;
    }


    /**
     * @param string $pageName
     */
    protected function setPageName($pageName)
    {
        $this->pageName = $pageName;
    }

    /**
     * @param string $pathName
     */
    protected function setPathName($pathName)
    {
        $this->pathName = $pathName;
    }

    /**
     * @param array $params
     */
    protected function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getPageName()
    {
        return $this->pageName;
    }

    /**
     * @return string
     */
    public function getPathName()
    {
        return $this->pathName;
    }

    /**
     * @return array
     */
    public function getParams($key = "")
    {
        if ($key) {
            return isset($this->params[$key]) ? $this->params[$key] : NULL;
        }
        return $this->params;
    }

    public function getTpl($key = array()){
        $oTpl = Gj_LayerProxy::getProxy("Dao_Seo_Tdk_Template");
        return $oTpl->getTdkTemplate($key);
    }

    protected function getMark($key = ""){
        return $this->leftMark . $key . $this->rightMark;
    }

    protected function addLineStationForBusSubway(){
        if($busLine = $this->getBusInfo("name")){
            $this->params['busLine'] = $busLine;
            $this->params['line_station'] = $busLine;
        }
        if($subwayLineInfo = $this->getLineInfo()){
            if($subwayLineInfo[$this->getParams("subway_line")]){
                $this->params['subwayLine'] = $subwayLineInfo[$this->getParams("subway_line")];                
            }else{
                $this->params['subwayLine'] = $this->getParams("subway_line");                
            }
        }
        if($station = $this->getStationInfo("name")){
            $this->params['busStation'] = $station;
            $this->params['line_station'] = $station;
            $this->params['subwayStation']= $station;
        }
        if($this->getParams("college") && $collegeInfo = $this->getCollegeInfo()){
            $this->params['college'] = $collegeInfo['name'];
        }  
    }
    
    public function addCity(){
        if($this->getCityInfo("name")){
            $this->params["city"] = $this->getCityInfo("name");
        }
        if($this->getDistrictInfo("name")){
            $this->params["district"] = $this->getDistrictInfo("name");
            $this->params['district_street'] = $this->params["district"];
        }
        if($this->getStreetInfo("name")){
            $this->params["street"] = $this->getStreetInfo("name");
            $this->params['district_street'] = $this->params["street"];
        }
        $this->params['geoName'] = $this->params["city"].$this->params['district_street'];
        if($this->params['category'] == 'fang3'){
            $this->params['geoName'] = $this->params['district_street']?$this->params['district_street']:$this->params["city"];
        }
        if(in_array($this->getParams('majorPath'),array('college', 'subway', 'bus',"daxue"))){
            $this->addLineStationForBusSubway();
        }
      
    }

    public function replaceTpl($sTpl = ""){
        $this->addCity();
        foreach($this->getParams() as $key => $value) {
            $sMark = $this->getMark($key);
            $sTpl = str_replace($sMark,$value,$sTpl);
        }

        if(false === strpos($sTpl,$this->leftMark)){
            return $sTpl;
        }
        $preg = '/\\'.$this->leftMark .'.*?\\'.$this->rightMark."/";
        preg_match_all($preg,$sTpl,$aMatchs);

        if(!$aMatchs) return $sTpl;
        foreach($aMatchs[0] as $sMatch) {
            if($sMatch == $sTpl) continue;
            if(false === strpos($sMatch,$this->splitMark)) continue;
            $sValue = false;
            foreach(explode($this->splitMark,$sMatch) as $v){
                $v = str_replace(array($this->leftMark,$this->rightMark),"",$v);
                if($this->getParams($v)){
                    $sValue = $this->getParams($v);
                }
            }
            if($sValue){
                $sTpl = str_replace($sMatch,$sValue,$sTpl);
            }
        }
        if(false === strpos($sTpl,$this->leftMark)){
            return $sTpl;
        }
        $sTpl = preg_replace($preg,"",$sTpl);
        return $sTpl;
    }


    protected function setLineInfo(){
        //quote from ganji_v5/app/housing/rent/HousingRentSubwayListPage.class.php
        $client = Gj_LayerProxy::getProxy("Dao_Location_BusSubwayCollege");
        $this->lineInfo = $client->getSubwayByCityId($this->getCityInfo('city_code'));
        if(!$this->getParams("subway_line")){
            return $this->setStationInfo(array());
        }
        $lineInfo = $client->getSubwayStationByLineId($this->getCityInfo('city_code'), $this->getParams('subway_line'));
        if ($lineInfo != null) {
            $this->setStationInfo($lineInfo['stations']);
        }
        if ($this->getParams('station')) {
            $aStations = $this->getStationInfoFromClient("getSubwayStationInfoByLineIdAndStationNumber",$this->getParams("subway_line"));
            $this->setStationInfo($aStations);
        }
        //quote finished
        if($this->lineInfo){
            return true;
        }
        return false;
    }

    protected function getStationInfoFromClient($functionName,$paramValue){
        $aStations = array();
        $client = Gj_LayerProxy::getProxy("Dao_Location_BusSubwayCollege");
        $multi_subway_station = array();
        $multi_subway_station = explode('_',$this->getParams('station'));
        if (count($multi_subway_station) > 1) {
            foreach ($multi_subway_station as $v) {
                $aStations[] = $client->$functionName($this->getCityInfo('city_code'), $paramValue, $v);
            }
        } else {
            $aStations = $client->$functionName($this->getCityInfo('city_code'),$paramValue, $this->getParams('station'));
        }
        return $aStations;
    }

    protected function setStationInfo($aParams){
        $this->stationInfo = $aParams;
        if($this->stationInfo){
            return true;
        }
        return false;
    }

    public function getStationInfo($key = ""){
        if($key){
            return isset($this->stationInfo[$key]) ? $this->stationInfo[$key] : NULL;
        }
        return $this->stationInfo;

    }

    protected function setBusInfo(){
        //quote from ganji_v5/app/housing/rent/HousingRentBusListPage.class.phpinfo
        if(!$this->getParams("bus_line")){
            $this->busInfo = array();
            return false;
        }
        $client = Gj_LayerProxy::getProxy("Dao_Location_BusSubwayCollege");
        $busInfo = $client->getBusLineInfoByLineId($this->getCityInfo('city_code'), $this->getParams('bus_line'));
        if ($busInfo != null) {
            $this->busInfo = $busInfo['line'];
            $this->setStationInfo($busInfo['stations']);
        }

        if ($this->getParams('station')) {
            $aStations = $this->getStationInfoFromClient("getBusStationInfoByLineIdAndStationNumber",$this->getParams("bus_line"));
            $this->setStationInfo($aStations);
        }
        //quote finished
        if($this->busInfo){
            return true;
        }
        return false;

    }

    protected function setCollegeInfo(){
        //quote from ganji_v5/app/housing/rent/HousingRentCollegeListPage.class.php
        $client = Gj_LayerProxy::getProxy("Dao_Location_BusSubwayCollege");
        $this->collegeInfo = $client->getCollegeListByCityId($this->getCityInfo('city_code'));
        if ($this->getParams('college')) {
            $this->collegeInfo = $client->getCollegeInfoByCityIdAndCollegeId($this->getCityInfo('city_code'),$this->getParams('college'));
        }
        //quote finished
        if($this->collegeInfo){
            return true;
        }
        $this->collegeInfo = array();
        return  false;
    }

    public function getLineInfo($key = ""){
        if($key){
            return isset($this->lineInfo[$key]) ? $this->lineInfo[$key] : NULL;
        }
        return $this->lineInfo;
    }

    public function getBusInfo($key = ""){
        if($key){
            return isset($this->busInfo[$key]) ? $this->busInfo[$key] : NULL;
        }
        return $this->busInfo;
    }

    public function getCollegeInfo($key = ""){
        if($key){
            return isset($this->collegeInfo[$key]) ? $this->collegeInfo[$key] : NULL;
        }
        return $this->collegeInfo;
    }


    public function setFilterArray(){
        $aParams = $this->getParams();
        foreach($aParams as $key => $item){
            if(in_array($key ,$this->filterConfig)){
                continue;
            }
            if(strpos($key,"Title") !== false){
                continue;
            }
            $this->filterArray[$key] = $item;
        }
    }
    
    public function getFilterArray($key = ""){
        if($key){
            return $this->filterArray[$key];
        }
        return $this->filterArray;
    }

    /**
      *@brief 把agent算作参数，部分需求
      */
    public function getFilterArrayWithAgentTitle(){
        if($this->getFilterArray() || $this->getParams('agentTitle')){
            return true;
        }else{
            return false;
        }
    }

}
