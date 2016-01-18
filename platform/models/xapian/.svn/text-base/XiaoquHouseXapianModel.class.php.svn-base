<?php
require_once CODE_BASE2 . "/app/geo/GeoNamespace.class.php";

class XiaoquHouseXapianModel extends BaseXapianModel{

    protected $textFilter       = array(array(), array());
    protected $houseListParam = array(
        'major_category_id'  => 0,   //majorCategoryId:20=HousingVars::RENT_ID,22=HousingVars::SHARE_ID,21=HousingVars::SELL_ID.
        'city_code'     => 0,   //如北京是0
        'domain'        => '',  //如bj
        'xiaoqu_id'     => '',  //小区id
        'agent'         => 0,   //1=个人，2=经纪人，3=全部
        'huxing_shi'    => 0,   //户型室
        'price'         => 0,   /*格范围标示,注意：不同的type对应的price不同，参见HousingVars下
                                    出租房价格范围标示。
                                        HousingVars::$PRICE_RENT_TYPE_VALUES_FIRST_TIER_CITY\n
                                        HousingVars::$PRICE_RENT_TYPE_VALUES_SECOND_TIER_CITY\n
                                        HousingVars::$PRICE_RENT_TYPE_VALUES_OTHER_CITY\n
                                    合租房价格范围的代表数字\n
                                        HousingVars::$PRICE_SHARE_TYPE_VALUES_FIRST_TIER_CITY\n
                                        HousingVars::$PRICE_SHARE_TYPE_VALUES_SECOND_TIER_CITY\n
                                        HousingVars::$PRICE_SHARE_TYPE_VALUES_OTHER_CITY\n
                                    二手房价格范围的代表数字\n
                                        HousingVars::$PRICE_SELL_TYPE_VALUES_FIRST_TIER_CITY\n
                                        HousingVars::$PRICE_SELL_TYPE_VALUES_SECOND_TIER_CITY\n
                                        HousingVars::$PRICE_SELL_TYPE_VALUES_OTHER_CITY\n */
        'price_b'       => 0,   //价格范围的开始值
        'price_e'       => 0,   //价格范围结束值
        'share_mode'    => 0,   //合租模式：1=单间，2=床位
        'house_type'    => 0,   //合租房的房屋类型：1=主卧，2=次卧，3=隔断间
        'area'          => 0,   //房屋面积：HousingVars::$AREA_TYPE_VALUES
        'keyword'       => '',  //关键词
        'img_count'     => 0,   //图片数量
        'field'         => array(), // 所需字段
        'tag_type'      => 0,  //房源标签.各bit位代表意义：0=无 1=新 2=精 4=免税 8=小区宝',
        'premier_status'=> array(),
        );
        
    protected $defaultListingStatus = array(5,50);
    protected $dbFields = array (
                'id', 
                'type',
                'title', 
                'refresh_at', 
                'person',
                'post_at', 
                'district_id', 
                'street_id', 
                'district_name', 
                'street_name', 
                'listing_status', 
                'post_type', 
                'xiaoqu_address', 
                'image_count', 
                'latlng', 
                'huxing_shi', 
                'area', 
                'price', 
                'downpayment',
                'loan_require',
                'agent', 
                'xiaoqu', 
                'user_id',
                'phone',
                'pinyin', 
                'thumb_img', 
                'huxing_ting', 
                'huxing_wei',
                'fang_xing',
                'ceng', 
                'ceng_total', 
                'niandai', 
                'chaoxiang', 
                'zhuangxiu', 
                'top_info',
                'puid',
                'account_id',
                'bus_line', 
                'subway_line', 
                'college_line',
                'intval1',
                'tag_type',
                'tab_system',
                'premier_status',
                'major_category',
        );

    /*
     * 按照以下条件查询小区下房源
     */
    public function preGetXiaoquHouseList($listParam, $pagerParam){
        if($pagerParam['page'] < 1) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':page', ErrorConst::E_PARAM_INVALID_CODE);
        if($pagerParam['pageSize'] < 1) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':pageSize', ErrorConst::E_PARAM_INVALID_CODE);
        if(!isset($listParam['domain']) && !isset($listParam['city_code'])) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':domain or city_code', ErrorConst::E_PARAM_INVALID_CODE);
        if($listParam['xiaoqu_id'] < 1) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':xiaoqu_id 或者 pinyin', ErrorConst::E_PARAM_INVALID_CODE);
        $this->builder = new SearchQueryBuilder();
        $this->builder = $this->createCommonQueryBuilder($listParam, $pagerParam);
        return $xiaoquHouseIndexResultId = $this->xapianObj->query($this->builder->getQueryString());
        //return $this->xapianObj->getResultByQueryId(self::$searchHandle, $xiaoquHouseIndexResultId);
    }

    public function createCommonQueryBuilder($listParam, $pagerParam){
        //设置返回字段
        if(!empty($listParam['field'])){
            $this->dbFields = $listParam['field'];
        }      
        $this->builder->setFields($this->dbFields);
        
        // 3.过滤条件
        $xapianCategory = HousingVars::$majorCategoryId2xapianCategoryId[$listParam['major_category_id']];
        $this->builder->setEqualFilter('category', $xapianCategory);
        $this->builder->setEqualFilter('city', $listParam['city_code']);
        //小区拼音
        if($listParam['xiaoqu_id'] > 0){            
            $this->builder->setEqualFilter('xiaoqu_id', $listParam['xiaoqu_id']);
        }
        //价格
        if($listParam['price'] > 0){
            //临时解决啊~上面抛异常的地方判断 domain 不是必须的， 这里又直接使用了， --！  上面的city_code一样的问题 --！
            if(empty($listParam['domain'])) {
                $cityArr = GeoNamespace::getCityByCityCode($listParam['city_code']);
                $listParam['domain'] = $cityArr['domain'];
            }
            list($pA, $pB) = $this->getPriceRange($listParam['domain'], $listParam['major_category_id'], $listParam['price']);
        } elseif (!isset($listParam['price'])
            && isset($listParam['price_b']) && isset($listParam['price_e'])) {
            if ($listParam['major_category_id'] == HousingVars::SELL_ID) {//二手房价格是万
                $pA = $listParam['price_b'] <= 10000 ?  $listParam['price_b'] * 10000 : $listParam['price_b'];
                $pB = $listParam['price_e'] <= 10000 ?  $listParam['price_e'] * 10000 : $listParam['price_e'];
            }  else {
                $pA = $listParam['price_b'];
                $pB = $listParam['price_e'];
            }           
        }
        if(isset($pA) && isset($pB)){
            $this->builder->setBetweenFilter('price', array($pA, $pB));
        }
        
        //面积
        if (isset($listParam['area'])) {
            $value = HousingVars::$AREA_TYPE_VALUES[$listParam['area']];
            $this->builder->setBetweenFilter('area', array (                
                    $value [0], 
                    $value [1] 
            ));
        }
        //户型
        if (isset($listParam ['huxing_shi'])) {
            $this->builder->setEqualFilter('huxing_shi', $listParam ['huxing_shi']);
        }
        //agent
        if(isset($listParam['agent'])){
            $this->builder->setEqualFilter('agent', $listParam['agent']);
        }
        //是否有图
        if ($listParam['img_count'] > 0) {
            $this->builder->setBetweenFilter('image_count', array(1, 999));
        }
        
        //share_mode
        if($listParam['share_mode'] > 0) $this->builder->setEqualFilter('share_mode', $listParam['share_mode']);
        //house_type 
        if($listParam['house_type'] > 0) $this->builder->setEqualFilter('house_type', $listParam['house_type']);
        if(isset($listParam['tag_type'])) $this->builder->setEqualFilter('tag_type', $listParam['tag_type']);
        if(isset($listParam['premier_status'])) $this->builder->setBetweenFilter('premier_status', $listParam['premier_status']);
           
        $this->builder->setBetweenFilter('listing_status', $this->defaultListingStatus);
        $this->builder->setLimit(($pagerParam['page'] - 1) * $pagerParam['pageSize'], $pagerParam['pageSize']);
        
        
        // 4.设置排序条件
        $this->builder->setDescOrderBy('listing_status');
        $this->builder->setDescOrderBy('post_at');
        
               
        // 5.文本查询条件
        $this->builder->setTextFilter($listParam['keyword'], $this->textFilter);
        
        return $this->builder;
    }

    /* {{{getSearchResult*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getSearchResult($searchIndex){
         return $this->xapianObj->getResult($searchIndex);
    }//}}}


}
