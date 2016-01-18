<?php

require_once CODE_BASE2 . '/app/bus_subway_college/BusSubwayCollegeNamespace.php';
class XiaoquXapianModel extends BaseXapianModel{

	const XAPIAN_XIAOQU_CATEGORY = 230;
    public static $LINE 		= null;	//公交或地铁线路信息
    public static $STATIONS 	= null;	//公交或地铁线路站点列表信息
    public static $STATION 		= null;	//公交或地铁站点信息    
    public static $LATLNG_RANGE = null;	//公交、地铁、大学检索的经纬度区间
	protected $STREET_LIST 		= array();	//选择多个街道时 todo...
	protected $textFilter       = array(array(), array());
	protected $builder 			= null;
    protected $xapianObj        = null;
    protected $orderKey         = null;//排序key
    protected $fieldsAll        =  array (
                                        'id','name','city','pinyin','latlng','thumb_image','rent_num','sell_num','share_num','zufang_num',
                                        'avg_price','district_id','street_id','address','description','bus','subway','jungong_time', 'subway_line', 'avg_price_change',
                                    );
    protected $fieldsHotXiaoqu  = array ('name','pinyin','avg_price','avg_price_change',);

    protected $listParam = array(
            'type'         => '', //AREA=区域查询；SUBWAY=地铁查询；NEARBY=周边查询
            'domain'       => '', //当前城市domain，如bj
            'city_code'    => '', //bj的话是0
            'district_id'  => '',
            'street_id'    => '', //
            'pinyin_id'    => '', //小区拼音首字母标示
            'price'        => '', //价格区间标示
            'price_b'      => '', //价格区间开始
            'price_e'      => '', //价格区间结束
            'keyword'      => '', //关键词
            'fields'       => array(), //指定要取的字段
            'subway_line'  => '', //地铁线
            'station'      => '', //车站
            'walk'         => '', //分钟，步行时间
            'lat'          => '', //经度
            'lng'          => '', //经度
            'radius'        => '', //半径单位m
            'order'        => 0, //排序 排序 int. 1=XIAOQUBAO_SELL_DESC;2= SELL_NUM_DESC;3=RENT_NUM_DESC;4=AVG_PRICE_SELL_DESC;5=AVG_PRICE_SELL_ASC;

        );


    //注意和HousingVars里XIAOQU_ORDER_IN_XIPIAN保持统一
    protected $searchOrder = array(
        'XIAOQUBAO_SELL_DESC'   => 1,
        'SELL_NUM_DESC'         => 2,
        'RENT_NUM_DESC'         => 3,
        'AVG_PRICE_SELL_DESC'   => 4,
        'AVG_PRICE_SELL_ASC'    => 5,
        'SELL_NUM_ASC'          => 6,
        'RENT_NUM_ASC'          => 7,
        'ZUFANG_NUM_DESC'         =>8,
        'ZUFANG_NUM_ASC'          =>9,
        'AVG_PRICE_CHANGE_DESC' =>10,
        'AVG_PRICE_CHANGE_ASC' =>11,
    );    

	public function preGetXiaoquList($listParam, $pagerParam){
        if($pagerParam['page'] < 1) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':page', ErrorConst::E_PARAM_INVALID_CODE);
        if($pagerParam['pageSize'] < 1) throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':pageSize', ErrorConst::E_PARAM_INVALID_CODE);
        
        if($listParam['city_code'] === null || $listParam['domain'] === null){
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':city_code or domain', ErrorConst::E_PARAM_INVALID_CODE);            
        }
        if(empty($listParam['major_category_id'])){
            $listParam['major_category_id'] = HousingVars::SELL_ID;
        }
		$this->builder = $this->createBuilder($listParam, $pagerParam);

        if('AREA' == $listParam['type']){       
        	$this->builder = $this->preSearchArea($listParam, $pagerParam);
        } else if('SUBWAY' == $listParam['type']){
        	$this->builder = $this->preSearchSubway($listParam, $pagerParam);
        } else if('NEARBY' == $listParam['type']){
            if($listParam['lat'] === null && $listParam['lng'] === null){
               throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':lat or lng', ErrorConst::E_PARAM_INVALID_CODE);             
            } 
        	$this->builder = $this->preNearby($listParam, $pagerParam);
        } else {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);            
        }
        $this->builder = $this->setEndQueryBuilder();
        return $xiaoquIndexResultId = $this->xapianObj->query($this->builder->getQueryString());
        //return $this->xapianObj->getResult($xiaoquIndexResultId);
	}

    /* {{{ preNearBy */
    /**
     * @brief 查询前执行
     *
     * @returns
     */
    protected function preNearby($listParam, $pagerParam){
        
        $this->builder = $this->createNearbyQueryBuilder($listParam, $pagerParam);
        return $this->builder;
    }
    //}}}

    /* {{{ createNearbyQueryBuilder */
    /**
     * @brief 查询前执行
     *
     * @returns
     */
    protected function createNearbyQueryBuilder($listParam, $pagerParam){
        //坐标查询处理
        if (!empty($listParam['lat']) && !empty($listParam['lng'])) {
            $radius = $listParam['radius'] > 0 ? $listParam['radius'] : 3000;
            $client = new BusSubwayCollegeNamespce();
            self::$LATLNG_RANGE = $client->getLatLngRange2($listParam['lat'], $listParam['lng'], $radius);
            $this->textFilter[0]['latlng'] = self::$LATLNG_RANGE;
        }   
        return $this->builder;

    }
    //}}}

	/* {{{ preSearchSubway */
    /**
     * @brief 查询前执行
     *
     * @returns
     */
    protected function preSearchSubway($listParam, $pagerParam){
        if (!in_array($listParam['domain'], HousingVars::$DOMAIN_IN_SUBWAY_CITY)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.": no subway in this city", ErrorConst::E_PARAM_INVALID_CODE);            
        }
        $client = new BusSubwayCollegeNamespce();
        self::$LINE = $client->getSubwayByCityId($listParam['city_code']);
        if (!is_null($listParam['subway_line'])) {
            $lineInfo = $client->getSubwayStationByLineId($listParam['city_code'], $listParam['subway_line']);
            if ($lineInfo != null) {
                self::$STATIONS = $lineInfo['stations'];
            }
            if (!is_null($listParam['station'])) {
                self::$STATION = $client->getSubwayStationInfoByLineIdAndStationNumber($listParam['city_code'], $listParam['subway_line'], $listParam['station']);
                if (self::$STATION) {
                    //由于是画方算法，乘以一个系数
                    $distance = !is_null($listParam['walk']) ? HousingVars::$WALK_TIME_VALUES [$listParam['walk']] * 0.8 : 3000 * 0.8;
                    self::$LATLNG_RANGE = $client->getLatLngRange2(self::$STATION ['lat'], self::$STATION ['lng'], $distance);
                }
            }
        }       
        $this->builder = $this->createSubwayQueryBuilder($listParam, $pagerParam);
        return $this->builder;
    }//}}}

	/* {{{ createSubwayQueryBuilder */
    /**
     * @brief 创建小区检索对象
     *
     * @returns
     */
    public function createSubwayQueryBuilder($listParam, $pagerParam){
        //地铁线路
        if ($listParam['subway_line'] === null) {
            $this->textFilter[0]['subway_line'] = 'all';
        } else {
            $this->textFilter[0]['subway_line'] = $listParam['subway_line'];
        }
        //地铁站点
        if (isset(self::$LATLNG_RANGE)) {
            unset($this->textFilter[0]['subway_line']);
            $this->textFilter[0]['latlng'] = self::$LATLNG_RANGE;
        } else {
            if (!is_null($listParam['walk'])) {
                if (!is_null($listParam['subway_line'])) {
                    $filterName = 'subwaydistance_' . $listParam['subway_line'];
                } else {
                    $filterName = 'subwaydistance';
                }
                $this->textFilter[0][$filterName] = HousingVars::$WALK_TIME_VALUES [$listParam['walk']];
            }
        }
        return $this->builder;
    }//}}}

    /* {{{ preSearchArea */
	/**
     * @brief 查询前执行
     *
     * @returns
     */
    protected function preSearchArea($listParam, $pagerParam){
        $this->builder = $this->createAreaQueryBuilder($listParam, $pagerParam);
        return $this->builder;
       
    }//}}}

	/* {{{ createAreaQueryBuilder */
    /**
     * @brief 创建小区检索对象
     *
     * @returns
     */
    public function createAreaQueryBuilder($listParam, $pagerParam){        
        //区域地标处理
        $this->builder->setEqualFilter('district_id', $listParam['district_id']);
        //todo......self::$STREET_LIST 参见PostListPage下createGeoData
        if (!empty(self::$STREET_LIST)) {
            $multiStreetId = array();
            foreach (self::$STREET_LIST as $street) {
                $multiStreetId[] = $street['script_index'];
            }
            $this->builder->setInFilter('street_id', $multiStreetId);
        } else {
            $this->builder->setEqualFilter('street_id', $listParam['street_id']);
        }
        
        return $this->builder;
    }//}}}

    /* {{{ createBuilder */
    /**
     * @brief
     * @param $listParam 
     * @param $pagerParam 
     * @returns
     */
    protected function createBuilder($listParam, $pagerParam, $type=0){
        $this->builder = new SearchQueryBuilder();


	    // 2.设置查询字段
        if(!empty($listParam['fields'])){
        	$dbFields = $listParam['fields'];
        } else {
	        if (0 == $type) {
	            $dbFields = $this->fieldsAll;
	        } else {
	            $dbFields = $this->fieldsHotXiaoqu;
	        }
	    }
	    $this->builder->setFields($dbFields);
        // 3.过滤条件
        $this->builder->setEqualFilter('category',self::XAPIAN_XIAOQU_CATEGORY);
        $this->builder->setEqualFilter('city', $listParam['city_code']);
        $this->builder = $this->setBuilderOtherWhere($listParam);
        $this->builder->setLimit(($pagerParam['page'] - 1) * $pagerParam['pageSize'], $pagerParam['pageSize']);
        if($listParam['major_category_id'] == HousingVars::SELL_ID){
            $this->orderKey = $listParam['order'] ?  $listParam['order'] : $this->searchOrder['XIAOQUBAO_SELL_DESC'];
        } elseif($listParam['major_category_id'] == HousingVars::RENT_ID){
            $this->orderKey = $listParam['order'] ?  $listParam['order'] : $this->searchOrder['ZUFANG_NUM_DESC'];
        }
        if(in_array($this->orderKey, array($this->searchOrder['ZUFANG_NUM_DESC'], $this->searchOrder['ZUFANG_NUM_ASC']))){
            $this->builder->setBetweenFilter('zufang_num', array(0, 99999999));//zufang_num asc 时，必须这样才起作用
        }
        if (0 == $type && isset(HousingVars::$XIAOQU_ORDER_IN_XIPIAN[ $this->orderKey ])) {
            $order_info = HousingVars::$XIAOQU_ORDER_IN_XIPIAN[ $this->orderKey ];
            if(!is_array($order_info[0])){
                $order_info = array($order_info);                  
            }
             foreach ((array)$order_info as $key => $val) {
                $method = "set{$val[1]}OrderBy";
                $this->builder->$method($val[0]);
            }            
        } else {
            $this->builder->setDescOrderBy('popularity');
        }
        
        return $this->builder;
    }//}}}


     /* {{{ setBuilderOtherWhere */
    /**
     * @brief 设定检索其他条件
     *
     * @param $builder
     *
     * @returns
     */
    protected function setBuilderOtherWhere($listParam){

        if ( is_numeric($listParam['pinyin_id'])) {
            $pinyin = XiaoquPageConfig::$pinyinType4Xipian[ $listParam['pinyin_id'] ];
            $this->textFilter[0]['pinyin_prefix'] = $pinyin;
        }

        //{{{价格区间处理
        if (is_null($listParam['price']) && !is_null($listParam['price_b']) && !is_null($listParam['price_e'])) {
            $p1 = $listParam['price_b'] * 10000;
            $p2 = $listParam['price_e'] * 10000;
        } else if (!is_null($listParam['price'])) {
            list( $p1, $p2 ) = HousingVars::getSellAveragePriceRange($listParam['domain'], $listParam['price']);
        } else {
            //当排序规则为按均价由低到高
            //为了去掉小区均价为0的
            if ( isset(HousingVars::$XIAOQU_ORDER_PRICE_RANGE[ $this->orderKey ] )) {
                list( $p1, $p2 ) = HousingVars::$XIAOQU_ORDER_PRICE_RANGE[ $this->orderKey ];
            }
        }
        if ( isset($p1) && isset($p2) ) {
            $this->builder->setBetweenFilter('avg_price', array($p1, $p2));
        }
        //}}}
        
        //{{{ 小区名
        if ( null != $listParam['keyword'] ) {
            $this->keyword = $listParam['keyword'];
        }
        //}}}
        return $this->builder;
    }//}}}
	

    /* {{{ setEndQueryBuilder */
    /**
     * @brief 设定检索其他条件
     *
     * @returns
     */
    protected function setEndQueryBuilder(){
        //setTextFilter统一在此处理
        $this->builder->setTextFilter($this->keyword, $this->textFilter);

        //对有坐标查询时，默认由近及远排序
        if(isset(self::$LATLNG_RANGE)){
            // $this->builder->setAscOrderBy('distance_sort');
        }
        
        return $this->builder;
    }
    //}}}

    /* {{{preGetHotXiaoquList*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function preGetHotXiaoquList($listParam){
        //只查询端口帖索引
        $this->builder = $this->createHotXiaoquQueryBuilder($listParam);
        return $this->hotXiaoquIndexResultId = $this->xapianObj->query($this->builder->getQueryString());
    }//}}}

    /* {{{getSearchResult*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function getSearchResult($searchIndex){
         return $this->xapianObj->getResult($searchIndex);
    }//}}}

    /* {{{createHotXiaoquQueryBuilder*/
    /**
     * @brief 
     *
     * @returns   
     */
    private function createHotXiaoquQueryBuilder($listParam){
        $builder = $this->createBuilder($listParam,array(),1);
        $builder->setBetweenFilter('sell_num', array(10, 99999));
        $builder->setDescOrderBy('avg_price_change');
        //区域地标处理
        $builder->setEqualFilter('district_id', $listParam['district_id']);
        if (in_array($listParam['domain'], array('bj', 'sh', 'gz', 'sz'))/*北上广深*/) {
            $builder->setEqualFilter('street_id', $listParam['street_id']);
            // 筛选街道时取5条，否则取10条
            if (!isset($listParam['street_id']) || empty($listParam['street_id'])) {
                $builder->setLimit(0, 10);
            } else {
                $builder->setLimit(0, 5);
            }
        } else {
            // 小城市，不筛选街道
            $builder->setLimit(0, 10);
        }
        $builder->setTextFilter($this->keyword, $this->textFilter);
        return $builder;
    }//}}}
}
