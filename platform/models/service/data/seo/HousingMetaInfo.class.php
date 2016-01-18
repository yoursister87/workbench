<?php
class Service_Data_Seo_HousingMetaInfo
{
    /*{{{fang2,fang4,fang10*/
    /*public function getWantrentWantbuyShortrentMeta(){
        $aParams = HousingListPage::$REQUEST_PARAMS;
        $aParams["pageType"] = "list";
        $aParams["city"] = HousingListPage::$CITY_INFO['domain'];
        HousingListPage::$REQUEST_LIST= (empty(HousingListPage::$REQUEST_LIST))?array(): HousingListPage::$REQUEST_LIST;
        foreach(HousingListPage::$REQUEST_LIST as $key => $v){
            if (in_array($key , array('agent', 'district_street'))){
                $aParams[$key.$aParams[$key]."Title"] = $v["title"];
                continue;
            }
            if (in_array($key , array('fang_xing'))){
                $aParams[$key."Text"] = $v["title"];
                continue;
            }
            if($v){
                $aParams[$key] = $v["title"];
            }
        }
        $aParams = array_filter($aParams);
        $meta = MsServiceApi::call('Service_Data_SEO_TDK.getMetaInfo',$aParams);
        return $meta["meta"];
    }*/
    /*}}}*/
    /*{{{fang6,fang7,fang8,fang9,fang11: (包括fang6,7,8,9楼盘)*/
    /*public function getStoreOfficePlantMeta($isLoupan= false){
        $aParams = HousingListPage::$REQUEST_PARAMS;
        HousingListPage::$REQUEST_LIST= (empty(HousingListPage::$REQUEST_LIST))?array(): HousingListPage::$REQUEST_LIST;
        $aList = HousingListPage::$REQUEST_LIST;
        $deal_type= HousingListPage::$REQUEST_PARAMS['deal_type'];
        $aList['deal_type']['title'] = $deal_type !== null?HousingVars::$DEAL_TYPE[HousingListPage::$MAJOR_CATEGORY_INFO['id']][$deal_type]:'';

        if($aParams['list_type'] == 'shangquan'){
            $aParams["majorPath"] = "shangquan";
        }elseif($isLoupan){
            $aParams["majorPath"] = "loupan";
        }
        $aParams["pageType"] = "list";
        $aParams["city"] = HousingListPage::$CITY_INFO['domain'];

        foreach($aList as $key => $v){
            if (in_array($key, array('agent', 'district_street', 'deal_type'))){
                $aParams[$key.$aParams[$key]."Title"] = $v["title"];
                continue;
            }
            if (in_array($key , array('house_type'))){
                $aParams[$key."Text"] = $v["title"];
                continue;
            }
            if($key == 'company_id'){
                $aParams["mingqi"] = $v["title"];
                continue;
            }
            if($v){
                $aParams[$key] = $v["title"];
            }
        }

        $aParams = array_filter($aParams, array(self, 'filterSinaList'));
        $meta = MsServiceApi::call('Service_Data_SEO_TDK.getMetaInfo',$aParams);
        return $meta["meta"];
    }*/
    /*}}}*/
    /*{{{filterSinaList*/
    public function filterSinaList($value){
        if (isset($value)){
            return 1;
        } else {
            return 0;
        }
    }
    /*}}}*/
    /*fang12{{{*/
    public  function getLoupanMeta($requestParams, $requestList, $domain, $postCount = null){
        if(!$postCount){
            $postCount = rand(1,10);
        }
        $aParams = $requestParams;
        $aParams["pageType"] = "list";
        $aParams["postCount"] = $postCount;
        $aParams["city"] = $domain;
        $requestList = (empty($requestList)) ? array() : $requestList;
        foreach($requestList as $key => $v){
          if (in_array($key , array('agent', 'district_street', 'tag_type'))){
                 $aParams[$key.$aParams[$key]."Title"] = $v["title"];
                 continue;
          }
          if($v){
              $aParams[$key] = $v["title"];
          }
        }
        $aParams = array_filter($aParams);
        $obj = Gj_LayerProxy::getProxy('Service_Data_SEO_TDK');
        $meta = $obj->getMetaInfo($aParams);

        return $meta["meta"];

    } 
    /*}}}*/
    /* {{{getRentMeta*/
    /**
     * @brief 
     *
     * @returns   
     */
    /*public  function getRentMeta($postCount){
        return self::getShareMeta($postCount);
    }*/
    //}}}
    /* {{{getShareMeta*/
    /**
     * @brief 
     *
     * @param $postCount
     *
     * @returns   
     */
    /*public  function getShareMeta($postCount = null){
        if(!$postCount){
            $postCount = rand(1,10);
        }
        $aParams = HousingListPage::$REQUEST_PARAMS;
        var_dump($aParams);
        $aParams['source'] = 'PC';
        $aParams["pageType"] = "list";
        $aParams["postCount"] = $postCount;
        $aParams["city"] = HousingListPage::$CITY_INFO['domain'];
        HousingListPage::$REQUEST_LIST= (empty(HousingListPage::$REQUEST_LIST))?array(): HousingListPage::$REQUEST_LIST;
        var_dump(HousingListPage::$REQUEST_LIST);
        foreach(HousingListPage::$REQUEST_LIST as $key => $v){
          if (in_array($key , array('agent', 'district_street', 'huxing_shi', 'tag_type', 'bus_line', 'subway_line', 'station', 'college',"share_mode"))){
                if($key == "agent"){
                    if($aParams[$key] == 4){
                        $aParams[$key.$aParams[$key]."Title"] = "_赶集网放心房";
                    }else{
                        $aParams[$key.$aParams[$key]."Title"] = $v["title"];
                    }
                }else{
                    $aParams[$key.$aParams[$key]."Title"] = $v["title"];
                }
                continue;
            }
            if($key == 'company_id'){
                $aParams["mingqi"] = $v["title"];
                continue;
            }
            if($v){
                $aParams[$key] = $v["title"];
            }
        }
        if(HousingListPage::$LIST_PAGE_TYPE == HousingVars::BUS_LIST_PAGE_TYPE){
            $aParams["majorPath"] = "bus";
        }elseif(HousingListPage::$LIST_PAGE_TYPE == HousingVars::SUBWAY_LIST_PAGE_TYPE){
            $aParams["majorPath"] = "subway";
        }elseif(HousingListPage::$LIST_PAGE_TYPE == HousingVars::COLLEGE_LIST_PAGE_TYPE){
            $aParams["majorPath"] = "college";
        }
        $aParams = array_filter($aParams);
        $meta = MsServiceApi::call('Service_Data_SEO_TDK.getMetaInfo',$aParams);

        return $meta["meta"];
   }*/
    /*}}}*/
}
