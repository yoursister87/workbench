<?php

class Dao_Xapian_Xiaoqu
{
    const XAPIAN_ID = 230;
    protected static $xapianFieldArr = array(
        'f' => array(
            'id' => array(0, 0),
            'name' => array(1, 1),
            'city' => array(1, 0),
            'pinyin' => array(1, 0),
            'latlng' => array(1, 1),
            'thumb_image' => array(0, 0),
            'district_id' => array(1, 0),
            'street_id' => array(1, 0),
            'company_id' => array(1, 0),
            'address' => array(1, 1),
            'description' => array(0, 0),
            'bus' => array(0, 0),
            'subway' => array(0, 0),
            'jungong_time' => array(0, 0),
            'pinyin_prefix' => array(1, 2),
            'str_val1' => array(1, 0),
            'str_val2' => array(1, 0),
            'bus_line' => array(1, 1),
            'subway_line' => array(1, 1),
            'college_line' => array(1, 1)
        ),
        'n' => array(
            'rent_num' => array(1, 0),
            'sell_num' => array(1, 0),
            'share_num' => array(1, 0),
            'avg_price' => array(1, 0),
            'avg_price_change' => array(1, 0),
            'popularity' => array(1, 0),
            'words_icon' => array(1, 0),
            'int_val1' => array(1, 0),
            'int_val2' => array(1, 0),
            'xiaoqu_bao_cnt' => array(1, 0),
            'zufang_num' => array(1, 0)
        )
    );
    protected static $defaultQueryFieldArr = array(
        'default' => array(
            'id',
            'name',
            'city',
            'pinyin',
            'latlng',
            'thumb_image',
            'rent_num',
            'sell_num',
            'share_num',
            'zufang_num',
            'avg_price',
            'district_id',
            'street_id',
            'company_id',
            'address',
            'description',
            'bus',
            'subway',
            'jungong_time',
            'subway_line',
            'avg_price_change'
        ),
        'hot' => array(
            'name',
            'pinyin',
            'avg_price',
            'avg_price_change'
        ),
    );
    //protected $fieldsHotXiaoqu  = array ();
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
    public function getXiaoquList($queryConfig){
        $result = array();
        if (true === $this->validatorQueryConfig($queryConfig)) {
            if ('HOT' == $queryConfig['queryFilter']['type']) {
                $defaultFields = self::$defaultQueryFieldArr['hot'];
            } else {
                $defaultFields = self::$defaultQueryFieldArr['default'];
            }
            unset($queryConfig['queryFilter']['type']);
            $searchUtil = Gj_LayerProxy::getProxy('Util_HouseXapian');
            $builder = $searchUtil->createQueryBuilder(self::XAPIAN_ID, $queryConfig, self::$xapianFieldArr, $defaultFields);
            // sendQueryAndGetResult
            $searchHandle = Gj_LayerProxy::getProxy('Util_XapianSearchHandleUtil');
            $searchId = $searchHandle->query($builder->getQueryString());
            if (is_numeric($searchId) && $searchId != 0) {
                $result = $searchHandle->getResult($searchId);
            } else {
                throw new Exception("检索searchId返回错误 :" . $searchId, ErrorConst::E_SQL_FAILED_CODE);
            }
        }
        return $result;
    }
    private function validatorQueryConfig($queryConfig){
        if (empty($queryConfig) || !is_array($queryConfig)) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . ':queryConfig is empty or not array', ErrorConst::E_PARAM_INVALID_CODE);
        } elseif (empty($queryConfig['queryFilter']) || !is_array($queryConfig['queryFilter'])) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG . ':queryFilter is empty or not array', ErrorConst::E_PARAM_INVALID_CODE);
        } elseif (null === $queryConfig['queryFilter']['city_code'] || null === $queryConfig['queryFilter']['domain']) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':city_code or domain', ErrorConst::E_PARAM_INVALID_CODE);
        } elseif (empty($queryConfig['queryFilter']['major_category_id'])) {
            throw new Exception(ErrorConst::E_PARAM_INVALID_MSG.':major_category_id', ErrorConst::E_PARAM_INVALID_CODE);
        }
        return true;
    }
}
