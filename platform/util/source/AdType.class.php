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
 */
class Util_Source_AdType extends Gj_Base_OutLib2Util{
    protected $objInstance;
    protected function getObjMap(){
        return array('class_name' =>"AdTypeNamespace",'file_path'=>CODE_BASE2 . "/app/adtype/AdTypeNamespace.class.php");
    }
}
