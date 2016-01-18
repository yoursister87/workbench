<?php

/**
 * @package
 * @subpackage
 * @brief                $url地址解析成数组$
 * @author               $wanyang <wanyang@ganji.com>$
 * @file                 $covertUrlParams.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-10$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Seo_ConvertUrlParams
{

    /**
     * @param $params
     * @extra $params = ('pageType' ,'category' ,'majorPath' ,'agent','price','trueHouse','huxing_shi',
     *                   'zufang','district_street','city','chaoxiang','')
     * @return mixed
     */
    public function formatUrlParams($params)
    {
        $tdkRoute = Gj_LayerProxy::getProxy("Dao_Seo_Tdk_Route");
        
        //listPage detailPage xiaoquDetailPage etc
        $params['page'] = $tdkRoute->getTdkPage($params['pageType']);

        $params["category"] = $tdkRoute->convertCategory($params['category']);

        
        //subway default bus colllege
        if (!empty($params['majorPath'])) {
            $path= ucfirst($params['category']) . ucfirst($params['majorPath']);
        } else {
            $path = ucfirst($params['category']).'Default';
        }
        
        $params["path"] = $path;
        $params['file_path'] = $tdkRoute->getTdkPath($path);
        $urlDict = Gj_LayerProxy::getProxy("Dao_Seo_Url_Dict");
        $params = $urlDict->getTdkUrlParam($params);
        return $params;
    }
}
