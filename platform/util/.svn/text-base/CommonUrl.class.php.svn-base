<?php
/*
 * File Name:CommonUrl.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Util_CommonUrl
{
    /**
     * {{{ Url参数拼接 
     *  params addParams array  需要添加的参数
     *  params delParams array or null  需要删除的参数   如果delParams为空 则清空出传来的所有参数
     *  params havehead boolean  是否显示 ? 开头
     */
    public static function createUrl($addParams = array(),$delParams = array(),$havehead = true){
        $uri = HttpNamespace::getQueryString();
        if (!is_array($addParams)) {
            return '';
        }

        parse_str($uri,$url);
        if ($delParams === NULL) {
            $url = array();
        }

        foreach ($addParams as $key=>$item){
            //url 在addprams中
            $url[$key] = $item;
        }
        if (is_array($delParams)) {
            foreach ($delParams as $item) {
                if (in_array($item,array_keys($url))) {
                    unset($url[$item]);
                }
            }
        }
        $strUrl = http_build_query($url);
        if ($havehead === true) {
            $strUrl = '?'.$strUrl;
        }

        return $strUrl;
    }
}
