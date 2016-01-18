<?php
/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $author: wanyang <wanyang@ganji.com>$
 * @file                 $RentDefaultListPage.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-05$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Seo_SellDefaultListPage 
{
    protected $prefix = 'SellDefault_';
    protected $Page;
    private $filterArray = array();
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

        if($aParams[2]['category'] =='Share' ||$aParams[2]['category'] == 'Sell'){
           $this->Config = Gj_Conf::getAppConf('level');
        }else if($aParams[2]['category'] =='Rent'){
           $this->Config = Gj_Conf::getAppConf('level.rent');
        }else {
           $this->Config = Gj_Conf::getAppConf('level.other');
        }
        $this->Level = $this->Config[$this->Page->getPathName()."Level"];
        if(!$this->Level){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE) ;
        }
        $this->Page->addCity();
        $this->initFilter();
        $this->Type = $this->Config["Type"];
    }

    private function initFilter(){
        $params = array();
        foreach($this->Level as $v) {
            $config = $this->Config[$v];
            if($config["is_params"] != 1){
                continue;
            }
            if($config["is_array"] == 1){
                foreach($config["values"] as $value){
                    $params[] = $value;
                }
            }else{
                $params[] = $config["name"];
            }
        }
        foreach($params as $v){
            if(null !== $this->Page->getParams($v)){
                $this->filterArray[] = $v;
            }
        }
    }

    /**
     * @return array
     */
    public function getMetaInfo()
    {
        $meta = array();
        foreach($this->Type as $type){
            $template = $this->getFinalTemplate($type);
            $meta[$type] = $this->Page->replaceTpl($template);
        }
        return $meta;
    }

    private function hasFilter($key,$configValues){
        if(!is_array($key)){
            $key = array($key);
        }
        if(isset($configValues["exclude"])){
            $key = array_merge($key,explode(",",$configValues["exclude"]));
        }
        if(isset($configValues["excludeValue"])){
            $excludeValues = explode(",",$configValues["excludeValue"]);
            foreach($excludeValues as $v){
                list($k,$v) = explode("#",$v);
                if($this->Page->getParams($k) == $v){
                    $key = array_merge($key,array($k));
                }
            }
        }
        $t = array_diff($this->filterArray,$key);
        return count($t) > 0 ? true : false;
    }

    private function getTemplateFromConfig($key,$type,$names,$configValues = array()){
        $hasFilter = $this->hasFilter($names,$configValues);
        if($hasFilter){
            $conditions = array($type);
        }else{
            $conditions = array($type."_withoutFilter",$type);
        }
        if($this->Page->getCityInfo("domain") == "bj"){
            $specilConditions = array();
            foreach($conditions as $v){
                $specilConditions[] = $v."_city#bj";
            }
            $conditions = array_merge($specilConditions,$conditions);
        }

        foreach($conditions as $v){
            if(isset($this->Config[$key][$v])){
                return $this->Config[$key][$v];
            }
        }
        return NULL;
    }

    public function getFinalTemplate($type){
        $leves = $this->Level;
        krsort($leves);
        foreach($leves as $l) {
            $v = $this->Config[$l];
            if($v["is_switch"]){
                if(in_array($v["name"],$this->filterArray)){
                    $key = $this->Page->getPathName()."_".$v["name"]."_".$this->Page->getParams($v["name"]);
                    $res = $this->getTemplateFromConfig($key,$type,$v["name"],$v);
                    if($res){
                        return $res;
                    }
                }
            }elseif($v["is_array"]){
                $matchValues = array_intersect($v["values"],$this->filterArray);
                sort($matchValues);
                $key = $this->Page->getPathName()."_".implode("+",$matchValues);

                $existValues = array();
                foreach($matchValues as $m){
                    $k = $this->Page->getPathName()."_".$m;
                    $r = $this->getTemplateFromConfig($k,$type,$m,$v); 
                    if($r){
                        $existValues[] = $r;
                    }
                }
                $res = (count($existValues) == 1)?$existValues[0]: false;

                if(!$res){
                    $res = $this->getTemplateFromConfig($key,$type,$matchValues,$v);
                }

                if($res){
                    return $res;
                }
            }elseif(!$v["is_params"]){
                $key = $this->Page->getPathName()."_".$v["name"];
                $res = $this->getTemplateFromConfig($key,$type,$v["name"],$v);
                if($res){
                    return $res;
                }
            }else{
                if(in_array($v["name"],$this->filterArray)){
                    $key = $this->Page->getPathName()."_".$v["name"];
                    $res = $this->getTemplateFromConfig($key,$type,$v["name"],$v);
                    if($res){
                        return $res;
                    }
                }
            }
        }
    }


}
