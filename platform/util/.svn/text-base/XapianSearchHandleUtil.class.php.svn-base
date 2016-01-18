<?php

class Util_XapianSearchHandleUtil
{
    public $xapianConnection = null;
    protected static $XapianHandlePool = array();
    /**
     * @codeCoverageIgnore
     */
    function __construct($majorCategoryId=null, $subCategory=null){
        if($majorCategoryId){
            if(!isset(self::$XapianHandlePool[$majorCategoryId]) ){
                self::$XapianHandlePool[$majorCategoryId] = SearchNamespace::createSearchHandle(SearchConstNamespace::TYPE_XAPIAN, SearchConfig::getServerByCategory(7, $majorCategoryId, $subCategory));
            }
            $this->xapianConnection = self::$XapianHandlePool[$majorCategoryId];
        } else {
            $this->xapianConnection = 
            SearchNamespace::createSearchHandle(SearchConstNamespace::TYPE_XAPIAN, SearchConfig::getServerByCategory(7, $majorCategoryId));
        }
    }
    /**
     * @codeCoverageIgnore
     */
    public function query($queryString){
        return SearchNamespace::query($this->xapianConnection, $queryString);
    }
    /**
     * @codeCoverageIgnore
     */
    public function getResult($searchId){
        return SearchNamespace::getResultByQueryId($this->xapianConnection, $searchId);
    }

}
