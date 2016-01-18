<?php
//require_once CODE_BASE2 . '/util/se/SearchNamespace.class.php';
//require_once GANJI_CONF . '/SearchConfig.class.php';
//require_once CODE_BASE2 . '/app/geo/GeoNamespace.class.php';
require_once CODE_BASE2 . '/app/post/adapter/housing/include/HousingVars.class.php';

class BaseXapianModel
{

	protected $xapianObj = null; 
	
	public function __construct(){
        $this->xapianObj = PlatformSingleton::getInstance('Util_XapianSearchHandleUtil');
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
    //{{{ getPriceRange
    /**
     * 根据不同城市获取fang 1 3 5的价格区间
     * @param string $domain
     * @param int $major_category_id
     * @param int $rangeType
     */
    protected static function getPriceRange($domain, $majorCategoryId, $rangeType){
        switch ($majorCategoryId) {
            case HousingVars::RENT_ID:
                $priceRange = HousingVars::getRentPriceRange($domain, $rangeType);
                break;
            case HousingVars::SELL_ID:
                $priceRange = HousingVars::getSellPriceRange($domain, $rangeType);
                break;
            case HousingVars::SHARE_ID:
                $priceRange = HousingVars::getSharePriceRange($domain, $rangeType);
            default:
               break;
        }
       return $priceRange;
    }//}}}
}

