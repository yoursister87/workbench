<?php
 /**
  * @package              
  * @subpackage           
  * @brief                
  * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
  * @file                 $HeadURL$
  * @version              $Rev$
  * @lastChangeBy         $LastChangedBy$
  * @lastmodified         $LastChangedDate$
  * @copyright            Copyright (c) 2014, www.ganji.com
  */
 
 class Dao_Hibase_Alltg
 {
 
     const HIBASE_ID = 'adfang';
         
     private $searchServiceName = 'se.hibase.adfang.http';

     public function search($queryConfigArr){
         $queryFilterArr = $queryConfigArr['queryFilter'];
         $objHouseHibase = Gj_LayerProxy::getProxy('Util_HouseHibase');
         $hibaseQueryArr = $objHouseHibase->createQueryParam(self::HIBASE_ID, $queryConfigArr);
         $data = $objHouseHibase->search($this->searchServiceName, self::HIBASE_ID, $hibaseQueryArr);
         return $data;
     }
     /*}}}*/

    
}
