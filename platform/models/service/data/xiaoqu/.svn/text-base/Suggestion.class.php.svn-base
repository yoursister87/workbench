<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   shenweihai$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Service_Data_Xiaoqu_Suggestion{
   /**{{{getXiaoquListByKeyCity
    * 根据城市domain和搜索key获取小区Suggest数据
    * 使用Memcache缓存
    */
   public function getXiaoquListByKeyCity($domain, $key) {
        if(empty($domain)|| empty($key)) {
            return false;
        }
        $key = addslashes($key);
        $key = str_replace(array('.', '(', ')'),'',$key);
        $key = strtolower($key);
        $cache = Gj_Cache_CacheClient::getInstance('Memcache');
        $memCacheKey = 'xiaoqu_name_autocomplete_'. $domain. '_'. $key. "_v3";
        $data = $cache->read($memCacheKey);
        if (false == $data) {
            $suggestion = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquSuggestion');
            $ret = $suggestion->getSuggestion($key, $domain);
            if (is_array($ret) && !empty($ret)) {
                $data = json_encode($ret);
                $cache->write($memCacheKey, $data);
                return $data;
            }else{
                return false;
            }
        } else {
            return $data;
        }
   } 
   /*}}}*/
}
