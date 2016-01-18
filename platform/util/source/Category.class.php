<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 * @codeCoverageIgnore 没有逻辑不需要单测
 */
class Util_Source_Category extends Gj_Base_OutLib2Util{

    /**
     * @var PostUID
     */
    protected $objInstance;
    protected function getObjMap(){
        return array('class_name' =>"CategoryNamespace",'file_path'=>CODE_BASE2 . "/app/category/CategoryNamespace.class.php");
    }

}