<?php
/**
 * @author               $Author:   shenweihai <shenweihai@ganji.com>$
 * Date: 14-8-26
 * Time: 上午10:54
 */
class Service_Data_Source_HouseRecommend {
    /*{{{__construct*/
    public function __construct() {
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
        $this->POS_RIGTH= 1;
        $this->POS_BOTTOM= 0;
        /*非归档贴*/
        $this->COMMON_TYPE= 1; 
        /*归档贴*/
        $this->ARCHIVE_TYPE= 2;
    }
    /*}}}*/
    /* {{{ getHouseRecList */
    /**
     * @brief 主站详情页专用，获取推荐列表
     *
     * @param   $limit 限制条数
     * @param   $pos 0-详情页底部推荐调用；1-详情页右侧推荐调用 type 默认取相关房源列表 =2取归档贴相关列表
     *
     * 没有查询数据,未添加异常判断处理
     * @returns 推荐房源列表 
    */
    public function getHouseRecList($limit=5, $pos=0, $type=1, $params= array()){
        $obj = Gj_LayerProxy::getProxy('Dao_Recommend_House');
        $params= $this->handler_params($params);
        if($this->ARCHIVE_TYPE== $type) {
            $recList = $obj->getArchiveHouseRecommendList($limit, $params);
        } elseif($this->COMMON_TYPE== $type) {
            $recList = $obj->getRelatedPostsWithFields($limit, $params);
        }
        if (empty($recList) || !is_array($recList)) {
            $recList= array();
        }
        if($pos== $this->POS_BOTTOM) {
            $recList= $this->formatRecListBottom($recList, $params['city_code'], $params['major_index']);
        }elseif ($pos== $this->POS_RIGTH) {
            $recList= $this->formatRecListRight($recList, $params['city_code'], $params['major_index']);
        }
        return $recList;
    }
    /*}}}*/
    /* {{{ getRelatedPostsWithFields */
    /**
     * @brief 详情页专用，获取推荐列表
     *
     * @param   $limit 限制条数
     * @param   $pos 0-详情页底部推荐调用；1-详情页右侧推荐调用 type 默认取相关房源列表 =2取归档贴相关列表
     *
     * 没有查询数据,未添加异常判断处理
     * @returns 推荐房源列表 
    */
    public function getRelatedPostsWithFields($limit=5, $pos=0, $type=1, $params= array()){
        $obj = Gj_LayerProxy::getProxy('Dao_Recommend_House');
        $params= $this->handler_params($params);
        $recList = $obj->getRelatedPostsWithFields($limit, $params);
        if (empty($recList) || !is_array($recList)) {
            $recList= array();
        }
        $recList= $this->formatRecListRightV2($recList, $params['city_code'], $pos);
        return $recList;
    }/*}}}*/
    /**{{{formatRecListRightV2*/
    protected function formatRecListRightV2($recList, $cityCode, $pos){
      if (is_array($recList) && count($recList) > 0) {
            $cityInfo = GeoNamespace::getCityByCityCode($cityCode);
            $cate= new Gj_Util_Category();
            $puidNamespace= new Gj_Util_Puid();
            foreach ($recList as $key=> $post) {
                $majorInfo = $cate->getMajorCategoryByScriptIndex(2, $post['major_category']);
                //定向推广的帖子
                if (isset($post['direction']) && $post['direction'] == 1) {
                     $post['dingxiang_sign'] = SelfDirectionNamespace::getSign($cityInfo['id'], $majorInfo['id'], 'detail-haveseen-img', $post['puid']);          
                }   
                //$post['location_link']= $this->getLocation_link($this->POS_RIGTH, $post);
                $post['first_link'] = $this->getFirstLineDis($pos, $post);
                $post['detail_url'] = $puidNamespace->spellDetailUrlByPuid($post['puid'], 'fang' . $post['major_category']);
                $post['second_link'] = $this->getSecondLineDis($pos, $post);
                $post['third_link'] = $this->getThirdLineDis($pos, $post);
                $post['img_url']= empty($post['thumb_img'])? 'http://stacdn201.ganjistatic1.com/src/image/globle/ued/normal_65_45.png': 'http://scs.ganjistatic1.com/'.$post['thumb_img'];
                $recList[$key]= $post;
            }
                return $recList;
        }
        return array();
    }//}}}
    protected function getFirstLineDis($pos, $post){
        $locationLink = '';
        if (in_array($post['major_category'], array(6, 7))) {
            $locationLink = mb_substr($post['loupan_name'], 0, 9, 'utf-8');
        } else if (in_array($post['major_category'], array(8, 9, 10, 11))){
            $locationLink = mb_substr($post['title'], 0, 9, 'utf-8');
        } else if (12 == $post['major_category']){
            $locationLink = $this->getLocation_link($pos, $post);
        }
        return $locationLink;
    }
    protected function getSecondLineDis($pos, $post){
        $secondLink = '';
        if ($pos == $this->POS_RIGTH) {
            $dis = '<p class="fc-gray9"><i class="pr-5">%s</i>%s</p>';
        } else if ($pos == $this->POS_BOTTOM) {
            $dis = '<p class="fc-4b" >%s - %s </p>';
        }
        if (in_array($post['major_category'], array(6, 7, 8, 9, 11))) {
            $location = !empty($post['street_name']) ? $post['street_name'] : $post['district_name'];
            $secondLink = sprintf($dis, $location, $post['area'] . '㎡');
        } else if (10 == $post['major_category']) {
            //$secondLink = '<p class="fc-999"><i class="pr-5">' . $post['district_name'] . '</i>' . $post['street_name'] . '</p>';
            $secondLink = sprintf($dis, $post['district_name'], $post['street_name']);
        } else if (12 == $post['major_category']) {
            $huxingDis = '';
            if (!empty($post['huxing_shi'])) {
                $huxingDis = $post['huxing_shi'] . '室';
            }
            if (!empty($post['huxing_ting'])) {
                $huxingDis .= $post['huxing_ting'] . '厅';
            }
            $secondLink = sprintf($dis, $huxingDis, $post['area'] . '㎡');
        }
        return $secondLink;
    }
    protected function getThirdLineDis($pos, $post){
        if ($pos == $this->POS_RIGTH) {
            $dis = '<p class="fc-gray9"><i class="pr-5">%s</i><b class="fc-org">%s</b>%s</p>';
        } else if ($pos == $this->POS_BOTTOM) {
            if (12 != $post['major_category']) {
                $dis = '<p class="fc-4b" >%s-<i class="fc-org fb">%s</i>%s</p>';
            } else {
                $dis = '<p>%s%s%s</p>';
            }
        }
        if (in_array($post['major_category'], array(6, 8, 7, 9, 11))) {
            if (6 == $post['major_category'] || 8 == $post['major_category']) {
                $priceUnit = HousingVars::$STORE_PRICE_TYPE[$post['price_type']];
                $houseType = '出租';
            } else if (11 == $post['major_category']){
                $priceUnit = HousingVars::$PRICE_TYPE[$post['price_type']];
                $majorCategoryId = HousingVars::$scriptIndex2MajorCategoryId[$post['major_category']];
                $houseTypeArr = HousingVars::$BUILDING_TYPE[$majorCategoryId];
                $houseType = $houseTypeArr[$post['house_type']];
            } else {
                $houseType = '出售';
                $priceUnit = '万元';
            }
        } else if (10 == $post['major_category']) {
            $houseType = HousingVars::$SHORTRENT_FANG_XING_VALUES[$post['fang_xing']];
            $priceUnit = '元/天';
        } else if (12 == $post['major_category']) {
            $houseType = HousingVars::$CX_TYPE[$post['chaoxiang']];
            $priceUnit = '万元';
            $post['price'] = $post['price'] / 10000;
            if (!empty($houseType)) {
                $houseType = $houseType . '-';
            }
        }
        if ($post['price'] <= 0) {
            $thirdLine = sprintf($dis, $houseType, '面议', '');
        } else {
            $thirdLine = sprintf($dis, $houseType, $post['price'], $priceUnit);
        }
        return $thirdLine;
    }
    /*{{{handler_params*/
    protected function handler_params($params= array()) {
        $params['city_code']  = $this->getParam($params, 'city_code', 0);
        $params['major_index'] = $this->getParam($params, 'major_index', 0);
        $params['puid']   = $this->getParam($params, 'puid', 0);
        $params['agent'] = $this->getParam($params, 'agent', 0);
        $params['source']  = $this->getParam($params, 'source', 'pc');
        $params['show_pos'] = $this->getParam($params, 'show_pos', 'first_page_right');
        return $params;
    }
   /*}}}*/
    /*{{{getHuxingDisplay*/
    protected function getHuxingDisplay($pos, $post, $majorIndex) {
        $huxing_display= '';
        if($pos== $this->POS_BOTTOM) {
            $huxing_display = isset($post['huxing_shi'])&& $post['huxing_shi']> 0 ?$post['huxing_shi']. '室':'';
            if(3 == $majorIndex) {
                // 合租方式
                if (isset($post['house_type']) && $post['share_mode'] == 1) {
                    $huxing_display .=  HousingVars::$SHARE_HOUSE_TYPE[$post['house_type']];
                }else if (isset($post['share_mode'])){
                // 房间类型
                    $huxing_display .= HousingVars::$SHARE_MODE_LIST[$post['share_mode']];
                } else if(isset($post['rent_sex_request'])&& $post['rent_sex_request'] != 0) {
                // 性别要求
                    $huxing_display  .= HousingVars::$SHARE_SEX_REQUEST[$post['rent_sex_request']];
                }
                if(empty($huxing_display)) {
                    $huxing_display= '合租房';
                }
            }
        }
        return $huxing_display;
    }
    /*}}}*/
   /* {{{ getParam */
    /**
     * @brief 从传入的参数数组中获取特定值 
     *
     * @param $str 要获取内容的数组下标
     * @param $default  默认值
     *
     * @returns   传入的参数或 默认值
     */
    protected function getParam($params, $str, $default=''){
        if (isset($params[$str])) {
            return $params[$str];
        }
        return $default;
    }
    /*}}}*/
    /*{{{getPriceDisplay*/
    protected function getPriceDisplay($pos, $post, $majorIndex) {
        $price_display= '';
        if($pos == $this->POS_BOTTOM|| $pos== $this->POS_RIGTH) {
            $price_display= $post['price']>0? '<b class="fc-org">'.$post['price'].'</b>元/月': '<b class="fc-org">面议</b>';
            if(5 == $majorIndex) {
                if(is_numeric($post['price'])&& $post['price'] > 10000) {
                    $priceWan= ($post['price']%10000 < 1000)? intval($post['price']/10000): sprintf('%.1f',$post['price']/10000);
                    $price_display = sprintf('<b class="fc-org">%s</b>万',$priceWan);
                }else {
                    $price_display = '<b class="fc-org"></b>面议';
                }
             }
       }
        return $price_display;
    }
    /*}}}*/
    /*{{{formatRecListBottom*/
    protected function formatRecListBottom($recList, $cityCode, $majorIndex){
      if(is_array($recList)) {
        $cityInfo = GeoNamespace::getCityByCityCode($cityCode);
        $cate= new Gj_Util_Category();
        $majorInfo = $cate->getMajorCategoryByScriptIndex(2, $majorIndex);
        $puidNamespace= new Gj_Util_Puid();
        foreach($recList as $key=> $post) {
            //定向推广的帖子
            if ($post['direction'] == 1) {
                $post['dingxiang_sign'] = SelfDirectionNamespace::getSign($cityInfo['id'], $majorInfo['id'], 'detail-haveseen-img', $post['puid']);
            }
            $post['location_link']= $this->getLocation_link($this->POS_BOTTOM, $post);
            $post['detail_url']= (isset($post['house_id']) && $post['house_id'] > 0)? 
                                  '/'.'fang'.$majorIndex.'/tuiguang-'.$post['house_id'].'.htm':$puidNamespace->spellDetailUrlByPuid($post['puid'],'fang'.$majorIndex); 
            $post['huxing_display']= $this->getHuxingDisplay($this->POS_BOTTOM, $post, $majorIndex);
            $post['price_display']= $this->getPriceDisplay($this->POS_BOTTOM, $post, $majorIndex);
            $post['zhuangxiu_display']= isset($post['zhuangxiu'])?HousingVars::$ZX_TYPE[$post['zhuangxiu']]: '';
            $recList[$key]= $post;
         }
          return $recList;
      }
          return array();
    }
    /*}}}*/
    /*{{{getLocation_link*/
    protected function getLocation_link($pos, $post) {
        $location_link= '';
        if($pos == $this->POS_RIGTH) {
              $location_link= !empty($post['street_name'])?$post['street_name']: $post['district_name'];
              $len = mb_strlen($location_link, 'utf-8')+ 1;
              if ($len < 10) {
                  $len = 10 - $len;
                  if (mb_strlen($post['xiaoqu'], 'utf-8') > $len) {
                      $location_link .=  '-'.mb_substr($post['xiaoqu'], 0, $len, 'utf-8');
                  } else {
                      $location_link .=  '-'.$post['xiaoqu'];
                  }
              } else {
                  $location_link = mb_substr($location_link, 0, 4, 'utf-8');
                  if (mb_strlen($post['xiaoqu'], 'utf-8') > 4) {
                       $location_link .= '-'.mb_substr($post['xiaoqu'], 0, 4, 'utf-8');
                  } else {
                       $location_link .='-'.$post['xiaoqu'];
                  }   
             }
        } elseif($pos == $this->POS_BOTTOM) {
            $buf = array();
            $buf[]= !empty($post['street_name'])? $post['street_name']: $post['district_name'];
            $buf[] = $post['xiaoqu'];
            $location_link= join(' - ', $buf);
        }
        return $location_link;
    }
    /*}}}*/
    /*{{{getHouseDisplay*/
    protected function getHouseDisplay($pos, $post, $majorIndex) {
        $house_display = '';
        if($pos == $this->POS_RIGTH) {
            if (3 == $majorIndex) {
                 if ($post['house_type'] == 1) {
                     $house_display = '主卧';
                 } else if ($post['house_type'] == 2) {
                     $house_display = '次卧';
                 } else {
                        $house_display = '隔断间';
                 }
              } else if (1== $majorIndex|| 5==$majorIndex) {
                  if ($post['huxing_shi']) {
                        $house_display = $post['huxing_shi'].'室';
                  }
                  if ($post['huxing_ting']) {
                      $house_display .= $post['huxing_ting'].'厅';
                  }
              } 
        }
        return $house_display;
    }
    /*}}}*/
    /* {{{ formatRecListRight */
    /**
     * @brief 对推荐房源进行格式化, 内部调用函数 
     *
     * @param $recList 房源推荐列表
     * @param $cityCode 城市的city_code
     * @param $majorIndex 房源类的script_index
     * @codeCoverageIgnore
     *
     * @returns   
     */
    protected function formatRecListRight($recList, $cityCode, $majorIndex){
      if (is_array($recList)) {
            $cityInfo = GeoNamespace::getCityByCityCode($cityCode);
            $cate= new Gj_Util_Category();
            $majorInfo = $cate->getMajorCategoryByScriptIndex(2, $majorIndex);
            $puidNamespace= new Gj_Util_Puid();
            foreach ($recList as $key=> $post) {
                //定向推广的帖子
                if (isset($post['direction']) && $post['direction'] == 1) {
                     $post['dingxiang_sign'] = SelfDirectionNamespace::getSign($cityInfo['id'], $majorInfo['id'], 'detail-haveseen-img', $post['puid']);          
                }   
                $post['location_link']= $this->getLocation_link($this->POS_RIGTH, $post);
                $post['detail_url'] = $puidNamespace->spellDetailUrlByPuid($post['puid'], 'fang'.$majorIndex);
                $post['house_display']= $this->getHouseDisplay($this->POS_RIGTH, $post, $majorIndex);
                $post['price_display'] = $this->getPriceDisplay($pos, $post, $majorIndex);
                $post['zhuangxiu_display']= isset($post['zhuangxiu'])?HousingVars::$ZX_TYPE[$post['zhuangxiu']]: '';
                $post['img_url']= empty($post['thumb_img'])? 'http://stacdn201.ganjistatic1.com/src/image/globle/ued/normal_65_45.png': 'http://scs.ganjistatic1.com/'.$post['thumb_img'];
                $recList[$key]= $post;
            }
                return $recList;
        }
                return array();
    }
    /*}}}*/
}
