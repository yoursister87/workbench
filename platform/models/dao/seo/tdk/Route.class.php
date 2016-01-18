<?php

/**
 * @package
 * @subpackage
 * @brief                $tdk路由类
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Seo_Tdk_Route
{
    /**
     * @param string $page
     * @return string
     */
    public function getTdkPage($page)
    {
        $config = Gj_Conf::getAppConf('tdkrouter',"tdkConfig");
        return $config["tdkPageConfig"][$page];
    }

    /**
     * @param string $page
     * @return string
     */
    public function convertCategory($category = "")
    {
        $config = Gj_Conf::getAppConf('tdkrouter',"tdkConfig");
        if(isset($config["tdkCategoryConfig"][$category])){
            return $config["tdkCategoryConfig"][$category];            
        }
        return $category;
    }


    /**
     * @param string $path
     * @return string
     */
    public function getTdkPath($path)
    {
        $config = Gj_Conf::getAppConf('tdkrouter',"tdkConfig");
        return $config["tdkPathConfig"][$path];
    }
}
