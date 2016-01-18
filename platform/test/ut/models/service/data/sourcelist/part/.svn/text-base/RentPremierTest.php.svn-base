<?php
 error_reporting(E_ALL^E_NOTICE);
/**
 * @package              
 * @subpackage           
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Service_Data_SourceList_Part_RentPremierMock extends Service_Data_SourceList_Part_RentPremier{
    /* {{{formatQueryFilter*/
    /*
     * @brief 
     *
     * @param $group
     * @param $count
     * @param $queryFilterArr
     *
     * @returns   
     */
    public  function formatQueryFilter($group, $count, $queryFilterArr)
    {
        return parent::formatQueryFilter($group, $count, $queryFilterArr); 
    }
    /*}}}*/
    /* {{{getModelInstance*/
    /*
     * @brief 
     *
     * @param $group
     * @param $extra
     *
     * @returns   
     */
     public function getModelInstance(){
          return parent::getModelInstance();
     }//}}}
    public function resultSpecialProcess($result){
        return parent::resultSpecialProcess($result);
    }
}

class RentPremierTest extends Testcase_PTest{

    public function testisPremier(){
         $obj = new Service_Data_SourceList_Part_RentPremierMock();
         //测试10次避免碰巧return true的情况
         for($i=0; $i<10; $i++){
             $ret = $obj->isPremier();
             $this->assertEquals($ret, true);
         }
     }
    
    /*
     public function testgetModelInstance(){
          $obj = new Service_Data_SourceList_Part_RentPremierMock();
          $mockClass = array('Dao_Xapian_Zufangtg' => array() );
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Zufangtg', $mockObj['Dao_Xapian_Zufangtg']);
          $ret = $obj->getModelInstance();
          $this->assertEquals($ret instanceof Dao_Xapian_Zufangtg, true);
     }
    */
     public function testpreSearch(){
          $mockClass = array(
                             'Service_Data_SourceList_Part_RentPremier' =>
                               array('preSearch' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          $obj = $mockObj['Service_Data_SourceList_Part_RentPremier'];
          $ret = $obj->preSearch('', '', array('queryFilter' => array()));
          $this->assertEquals($ret, 'www');
     }

     public function testgetSearchResult(){
          $mockClass = array('Dao_Xapian_Zufangtg' => 
                               array('getSearchResult' => array('return' => array(array(),0))));
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Dao_Xapian_Zufangtg', $mockObj['Dao_Xapian_Zufangtg']);
          $obj = new Service_Data_SourceList_Part_RentPremierMock();
          $ret = $obj->getSearchResult(123, array('group' => HousingVars::MAIN_BLOCK_LIST));
          $this->assertEquals($ret, array(array(),0));
         
          $ret = $obj->getSearchResult(123, array('group' => 66));
          $this->assertEquals($ret, array(array(),0));
     }


     /**
      * @dataProvider providerformatQueryFilter
      */
     public function testformatQueryFilter($group, $count, $queryFilterArr, $returnValues){
       $obj = new Service_Data_SourceList_Part_RentPremierMock();
       $ret = $obj->formatQueryFilter($group, $count, $queryFilterArr);
       foreach($returnValues as $key => $value){
         if($key == 'major_category' && $queryFilterArr['major_category_script_index'] == 1 ){//==1时不应出现major_category 
            $this->assertEquals(true, !isset($ret[$k]));
         } else {
            $this->assertEquals(true, isset($ret[$key]));
         }
         $this->assertEquals($value, $ret[$key]);
       }
     }

     public function testResultSpecialProcess(){
         $obj = new Service_Data_SourceList_Part_RentPremierMock();
         $ret = $obj->resultSpecialProcess(null);
         $this->assertEquals($ret, null);

         $now = time();
         $result = 
             array(
                 array(
                     array('puid' => 2, 'post_at' => $now-4*86400, 'refresh_at' => $now-86400-500),
                 ),
                 1,
             );
         $obj = new Service_Data_SourceList_Part_RentPremierMock();
         $ret = $obj->resultSpecialProcess($result);
         $wannaRet = 
             array(
                 array(
                     array('puid' => 2, 'post_at' => $now-4*86400, 'old_refresh_at' => $now-86400-500, 'refresh_at' => $now-3600-4*1800),
                 ), 
                 1
             );
         $this->assertEquals($wannaRet, $ret);
     }

     public function providerformatQueryFilter(){
         //键名为字符时,array_merge()此时会覆盖掉前面相同键名的值
         $basicParams = array('major_category_script_index'=> 1,
                              'city_domain' => 'bj',
                              'get_total_count' => false,
                              'page_no' => 1000,
         );
         $queryFilter0 = array('page_no' => 10); 
         $queryFilter1 = array(); 
         $queryFilter2 = array(); 
         $queryFilter3 = array('major_category_script_index'=>3, 'city_domain'=>'bj'); 
         return array(
                   array(-1, 5, array_merge($basicParams, $queryFilter0), array('offset_limit' =>array(45, 5))),
                   array(HousingVars::TRUE_HOUSE_LIST, 5, array_merge($basicParams, $queryFilter1), array('premier_status' => array(102, 102))),
                   array(-1, -1, array_merge($basicParams, $queryFilter2), array('premier_status' => array(2, 2))),
                   array(-1, -1, array_merge($basicParams, $queryFilter3), array('major_category'=>3)),
                   array(HousingVars::GONGYU_LIST, 2, array_merge($basicParams, array()), array('premier_status' => array(2, 2), 'tag_type' =>32)),
                );
     }

}

