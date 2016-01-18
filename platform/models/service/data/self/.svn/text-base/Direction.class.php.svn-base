<?php
class Service_Data_Self_Direction
{
    /* {{{getList*/
    /**
     * @brief 
     *
     * @param $postList
     * @param $queryConfigArr
     * @param $builder
     *
     * @returns   
     */
    public function getList($postList, $queryConfigArr, $builder){
        if (!is_array($postList) || empty($postList)) {
            $postList = array();
        }
        if (defined('PLATFORM_CODE')) {
            switch (PLATFORM_CODE) {
                case 'wap':
                case 'mob':
                    return $this->getWapList($postList, $queryConfigArr, $builder);
            }
        }
        return $this->getPcList($postList, $queryConfigArr, $builder);
    }//}}}
    /* {{{getPcList*/
    /**
     * @brief 
     *
     * @param $postList
     * @param $queryConfigArr
     * @param $builder
     * @codeCoverageIgnore
     *
     * @returns   
     */
    protected function getPcList($postList, $queryConfigArr, $builder){
        PostViewAuthorNamespace::filterList($queryConfigArr['queryFilter']['city_code'], '7', $builder, $postList, $queryConfigArr['queryFilter']['major_category_script_index']);
        SelfDirectionNamespace::setDirectionToSticky($builder, $postList, $queryConfigArr['queryFilter']['page_no']);
        return $postList;
    }//}}}
    /* {{{getWapList*/
    /**
     * @brief 
     *
     * @param $postList
     * @param $queryConfigArr
     * @param $builder
     * @codeCoverageIgnore
     *
     * @returns   
     */
    protected function getWapList($postList, $queryConfigArr, $builder){
        $directionPostListQueryParams = MsapiSelfDirectionPage::_setDirectionPostListQueryParams($queryConfigArr['queryFilter'], $builder);
        $builder = $directionPostListQueryParams['builder'];
        $currentPage = $directionPostListQueryParams['currentPage'];
        $uuid = $directionPostListQueryParams['uuid'];
        $source = $directionPostListQueryParams['source'];
        return MsapiSelfDirectionPage::getDirectionPostList($currentPage, $builder, $postList, $uuid, $source);
    }//}}}
}
