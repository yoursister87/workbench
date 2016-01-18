<?php
class Service_Data_SourceList_HaoZuList
{
    protected static $baseUrl = "http://www.haozu.com/";
    protected static $successCode = 0;

    public function getSearchResult($paramList){
        $url = self::$baseUrl;
        $city = $paramList['city_name'];
        $url .= $city.'/cooperation/ganji/?';
        //具体操作
        if(isset($paramList['action']) && !empty($paramList['action'])){
            $url .= "action=" . $paramList['action'];
        }else{
            $url .= "action=" . "list";
        }
        //城市
        if(isset($paramList['city_id']) && !empty($paramList['city_id'])){
            $url .= "&city_id=" . $paramList['city_id'];
        }
        //区域
        if(isset($paramList['district_id']) && !empty($paramList['district_id'])){
            $url .= "&district_id=" . $paramList['district_id']; 
        }
        //街道
        if(isset($paramList['street_id']) && !empty($paramList['street_id'])){
            $url .= "&street_id=" . $paramList['street_id'];
        }
        //面积
        if(isset($paramList['area_min']) && !empty($paramList['area_min'])){
            $url .= "&area_min=" . $paramList['area_min'];
        }
        if(isset($paramList['area_max']) && !empty($paramList['area_max'])){
            $url .= "&area_max=" . $paramList['area_max'];
        }
        //均价
        if(isset($paramList['price_unit_min']) && !empty($paramList['price_unit_min'])){
            $url .= "&price_unit_min=" . $paramList['price_unit_min'];
        }
        if(isset($paramList['price_unit_max']) && !empty($paramList['price_unit_max'])){
            $url .= "&price_unit_max=" . $paramList['price_unit_max'];
        }
        //总价
        if(isset($paramList['total_price_min']) && !empty($paramList['total_price_min'])){
            $url .= "&total_price_min=" . $paramList['total_price_min'];
        }
        if(isset($paramList['total_price_max']) && !empty($paramList['total_price_max'])){
            $url .= "&total_price_max=" . $paramList['total_price_max'];
        }
        //关键词
        if(isset($paramList['keyword']) && !empty($paramList['keyword'])){
            $url .= "&keyword=" . $paramList['keyword'];
        }
        //页数
        if(isset($paramList['page_no']) && !empty($paramList['page_no'])){
            $url .= "&page_no=" . $paramList['page_no'];
        }
        //请求数量
        if(isset($paramList['page_size']) && !empty($paramList['page_size'])){
            $url .= "&page_size=" . $paramList['page_size'];
        }
        
        $houseList = $this->getHouseList($url);
        $data= array(
                'errorno'   => ErrorConst::SUCCESS_CODE,
                'errormsg'  => ErrorConst::SUCCESS_MSG,
                'data'      => $houseList,
            );
        return $data;
    }

    private function getHouseList($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 200);
        $data = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($data, true);
        if($result['errorno'] == self::$successCode && !empty($result['data'])){
            return $result['data'];
        }else{
            return null;
        }
    }

}
