<?php

/**
 * @package
 * @subpackage
 * @brief                $tdk模板获取类
 * @author               $Author:   wanyang <wanyang@ganji.com>$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Dao_Seo_Tdk_Template
{

    private function getTpl($templateKey){
        if($templateKey[0] == "title"){
            $config = Gj_Conf::getAppConf('tdktemplateconfig',"titleTplConfig");
        }elseif($templateKey[0]== "description"){
            $config = Gj_Conf::getAppConf('tdktemplateconfig',"descriptionTplConfig");
        }else{
            $config = Gj_Conf::getAppConf('tdktemplateconfig',"keywordTplConfig");
        }
        return $config[$templateKey[1]];
    }

    /**
     * @param $templateKey
     * @return mixed
     */
    public function getTdkTemplate($templateKey)
    {
        return $this->getTpl($templateKey);
    }
}
