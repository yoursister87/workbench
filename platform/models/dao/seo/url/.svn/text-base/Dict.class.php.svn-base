<?php
/**
 * @package
 * @subpackage
 * @brief                $tdk url参数处理类
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Dao_Seo_Url_Dict
{

    /**
     * @param string $page
     * @param string $path
     * @param string $params
     * @return string
     */
    public function getTdkUrlParam($params){
        //通用参数处理
        $params= $this->processParamsCommon($params);

        //特殊参数处理
        switch($params['category']){
          //fang1 
          case 'Rent': $params= $this->processParamsRent($params); break;
          //fang6
          case 'Storerent': $params= $this->processParamsStorerent($params); break;
          //fang7
          case 'Storetrade': $params= $this->processParamsStoretrade($params); break;
          //fang8
          case 'Officerent': $params= $this->processParamsOfficerent($params); break;
          //fang9
          case 'Officetrade': $params= $this->processParamsOfficetrade($params); break;
          //fang10
          case 'Shortrent': $params= $this->processParamsShortrent($params); break;
          //fang11
          case 'Plant': $params= $this->processParamsPlant($params); break;
          //fang12
          case 'Loupan': $params= $this->processParamsLoupan($params); break;
        }
        return $params;
    }

    //fang1
    protected function processParamsRent($params) {
        if(isset($params['huxing_shi'])){
            $config = Gj_Conf::getAppConf('urldictconfig',"huxing_shi_config");
            $params['huxing_shi']= ($params['huxing_shi']>6) ?6: $params['huxing_shi'];
            if(isset($config[$params['huxing_shi']])){
                $params['huxing_shi'] = $config[$params['huxing_shi']];
            }
        }
        return $params;
    }

    //fang9 
    protected function processParamsOfficetrade($params){
         if(isset($params['price'])){
             $params['price'] = '售价'.$params['price'].'万元';
         }
         if(isset($params['house_typeText'] )&& $params['house_typeText']== '其他'){
             $params['house_typeText'] = '其他办公楼';
         }
         if(empty($params['house_typeText'])){
             $params['house_typeText'] = '写字楼';
         }
         return $params;
    }

    //fang7
    protected function processParamsStoretrade($params){
         if(isset($params['price'])){
             $params['price'] .= '万元';
         } 
         return $params;
   }

   //fang10
   protected function processParamsShortrent($params){
         if(isset($params['tag_type'])){
             $params['tag_type'] = '在线预订';
         }
         if(isset($params['fang_xingText'])&& $params['fang_xingText'] == '其他'){
             $params['fang_xingText'] = '集体宿舍|独栋别墅等';
         }
         if(isset($params['rent_type']) && $params['rent_type'] == 1){
             $params['rent_type'] = '求租房屋';
         }
       return $params;
   }

   //fang6
   protected function processParamsStorerent($params){
        if(isset($params['price'])){
           if(strpos($params['price'], '元') !== false||strpos($params['price'], '万') !== false ){
                $params['price'] = str_replace('元', '元/月', $params['price']);
                $params['price'] = str_replace('万', '万元/月', $params['price']);
           }else {
                $params['price'] .= '元/月';
           }
        }
        if(isset($params['store_rent_type'])){
            $params['store_rent_type'] = '|'. $params['store_rent_type'];
         }
        return $params;
   }
   
   //fang11
   protected function processParamsPlant($params){
        if(isset($params['house_typeText'] )&& $params['house_typeText']== '其他'){
            $params['house_typeText'] = '工业园区等';
        }    
        if(!empty($params['price_type'])){
            $unit = HousingVars::$PRICE_TYPE[$params['price_type']];
            $pre = ($params['deal_type'] == '3'|| $params['deal_type'] == '4')? '售价': '租金';
            $params['price'] =$pre. $params['price']. $unit;
        }   
        return $params;
   }

   //fang8
   protected function processParamsOfficerent($params){
        if(isset($params['house_typeText'])){
           $tx = ($params['house_typeText'] == '写字楼')?"": $params['house_typeText'];
           $params['house_typeText']= $tx;  
        }  
        if(empty($params['house_typeText'])){
           $params['house_typeText'] = '写字楼';
        }
        if(isset($params['price_type']) &&isset($params['price'])){
           $unit = HousingVars::$PRICE_TYPE[$params['price_type']];
           $params['price'] .= $unit;
        }   
        return $params;
   }

   //fang12   
   protected function processParamsLoupan($params) {
        if($params['agent'] == '2'){
            $params['agent2Title']="房产中介";
        }  
        return $params;
   } 

   //common
   protected function processParamsCommon($params){
        if (isset($params['huxing_shi'])) {
            $params['huxing_shi']=str_replace('室', '居室', $params['huxing_shi']);
        }
        foreach(array("huxing_shi2","huxing_ting","huxing_wei") as $v) {
            if(!isset($params[$v])) continue;
            $params['huxing_shi'] .= $params[$v];                
        }
        if($params["area"]){
            $params["area"] = str_replace('㎡', '平方米', $params["area"]);
        }
        if(isset($params["price"])){
            $params["price"] = str_replace('元/㎡·天','元/平方米每天', $params['price']);
        }
        if(isset($params["chaoxiang"])){
            $config = Gj_Conf::getAppConf('urldictconfig',"chaoxiang_config");
            if(isset($config[$params['chaoxiang']])){
                $params['chaoxiang'] = $config[$params['chaoxiang']];
            }
        }
        if(isset($params["image_count"]) && $params["image_count"] == '1'){
             $params['image_count'] = "[图]";
        }
       return $params;
   }
}
