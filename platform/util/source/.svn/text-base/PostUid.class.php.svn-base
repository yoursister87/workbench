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
class Util_Source_PostUid extends Gj_Base_OutLib2Util{

    /**
     * @var PostUID
     */
    protected $objInstance;
    protected function getObjMap(){
        return array('class_name' =>"PostUID",'file_path'=>CODE_BASE2 . "/util/post_uid/PostUIDNamespace.class.php");
    }
    public  function insertIndex($puid, $dbName, $tableName, $postId=0){
        $puidIndex = false;
        for( $i=0; $i<3; $i++ ) {
            $puidIndex = $this->objInstance->insertIndex($puid, $dbName, $tableName, $postId);
            if ($puidIndex == true) {
                break;
            }
        }
        return $puidIndex;
    }
    
    public  function updateIndex($intPuid,$intPostId){
        $bolRet = false;
        for( $i=0; $i<3; $i++ ) {
            $bolRet = $this->objInstance->updateIndex($intPuid,$intPostId);
            if ($bolRet == true) {
                break;
            }
        }
        return $bolRet;
    }

}