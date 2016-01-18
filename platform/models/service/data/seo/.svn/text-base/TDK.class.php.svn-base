<?php

/**
 * @package
 * @subpackage
 * @brief                $seo tdk对外唯一接口
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_SEO_TDK
{

    /**
     * @param array $params
     * @return array
     */
    public function getMetaInfo($params)
    {
        try {
            $params = $this->convertParams($params);
           //var_dump('处理后', $params);
            $page = $this->getTdkPage($params['page'], $params['file_path'], $params,$params["path"]);
            $meta = $page->getMetaInfo($params);
            $data = array(
                'errorno' => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'meta' => $meta
            );
        } catch (Exception $e) {
            $data = array(
                'errorno' => $e->getCode(),
                'errormsg' => $e->getMessage(),
                'meta' => Gj_Conf::getAppConf('tdktemplateconfig',"globalDefaultTpl") 
            );
        }
        return $data;
    }


    /**
     * @param string $pageName
     * @param string $pathName
     * @return Service_Data_Seo_Page
     */

    protected function getTdkPage($pageName, $pathFileName, $params , $pathName = "")
    {
        $instanceName = 'Service_Data_Seo_' . $pathFileName . $pageName;
        return Gj_LayerProxy::getProxy($instanceName,array($pageName,$pathName,$params));
    }

    /**
     * @param array $params
     * @return array
     */
    protected function convertParams($params)
    {
        $convertUrlParams = Gj_LayerProxy::getProxy("Service_Data_Seo_ConvertUrlParams");
        $params = $convertUrlParams->formatUrlParams($params);
        return $params;
    }
}
