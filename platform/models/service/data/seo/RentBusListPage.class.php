<?php

/**
 * @package
 * @subpackage
 * @brief                $$
 * @author               $wanyang <wanyang@ganji.com>$
 * @file                 $RentBusListPage.class.php$
 * @lastChangeBy         $wanyang$
 * @lastmodified         $2014-09-18$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Service_Data_Seo_RentBusListPage implements Service_Data_Seo_InterfacePath
{
    protected $prefix = 'RentBus_';
    protected $Page;

    /**
     * @param $aParams
     */
    public function __construct($aParams)
    {
        $this->Page = Gj_LayerProxy::getProxy("Service_Data_Seo_Page", $aParams);
    }

    /**
     * @return array
     */
    public function getMetaInfo()
    {
        $meta = $this->getFinalTemplate();
        return $meta;
    }

    public function getFinalTemplate()
    {
        $aResult = array();
        $aMetas = array("title", "description", "keywords");
        foreach ($aMetas as $value) {
            $templateKey = $this->generateTemplateKey($value);
            $template = $this->Page->getTpl($templateKey);
            if (!$template) {
                 throw new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE) ;
            }
            $aResult[$value] = $this->Page->replaceTpl($template);
        }
        return $aResult;
    }

    /**
     * @param array $params
     * @return string
     */
    public function generateTemplateKey($value)
    {
        if ($value == 'title') {
            $key = $this->generateTemplateTitle($value);
        } else if ($value == 'description') {
            $key = $this->generateTemplateDescription($value);
        } else {
            $key = $this->generateTemplateKeyword($value);
        }
        return array($value, $this->prefix . $key);
    }

    protected function generateTemplateTitle()
    {
        $key = 1;
        return $key;
    }


    protected function generateTemplateDescription()
    {
        $key = 1;
        return $key;
    }

    protected function generateTemplateKeyword()
    {
        $key = 1;
        return $key;
    }

}
