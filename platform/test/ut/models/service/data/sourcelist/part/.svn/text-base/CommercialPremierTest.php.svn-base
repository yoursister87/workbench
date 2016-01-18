<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   yangyu$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Service_Data_SourceList_Part_CommercialPremierMock extends Service_Data_SourceList_Part_CommercialPremier{
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

class CommercialPremierTest extends Testcase_PTest{

    public function testisPremier(){
         $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
         //测试10次避免碰巧return true的情况
         for($i=0; $i<10; $i++){
             $ret = $obj->isPremier();
             $this->assertEquals($ret, true);
         }
     }

     public function testgetModelInstance(){
          $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
          $mockClass = array('Dao_Xapian_Shangputg' => array() );
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangputg', $mockObj['Dao_Xapian_Shangputg']);
          $ret = $obj->getModelInstance();
          $this->assertEquals($ret instanceof Dao_Xapian_Shangputg, true);
     }

     public function testpreSearch(){
          $mockClass = array('Dao_Xapian_Shangputg' => 
                               array('preSearch' => array('return' => 'www')),
                             'Service_Data_SourceList_Part_CommercialPremierMock' =>
                               array('formatQueryFilter' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangputg', $mockObj['Dao_Xapian_Shangputg']);
          $obj = $mockObj['Service_Data_SourceList_Part_CommercialPremierMock'];
          $ret = $obj->preSearch('', '', array('queryFilter' => array()));
          $this->assertEquals($ret, 'www');
     }

     public function testgetSearchResult(){
          $mockClass = array('Dao_Xapian_Shangputg' => 
                               array('getSearchResult' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangputg', $mockObj['Dao_Xapian_Shangputg']);
          $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
          $ret = $obj->getSearchResult(123, array('group' => HousingVars::MAIN_BLOCK_LIST));
          $this->assertEquals($ret, 'www');
         
          $ret = $obj->getSearchResult(123, array('group' => 66));
          $this->assertEquals($ret, 'www');
     }


   /**
      * @dataProvider providerformatQueryFilter
      */
     public function testformatQueryFilter($group, $count, $queryFilterArr, $returnValues){
       $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
       $ret = $obj->formatQueryFilter($group, $count, $queryFilterArr);
       foreach($returnValues as $key => $value){
         $this->assertEquals(true, isset($ret[$key]));
         $this->assertEquals($value, $ret[$key]);
       }
     }

     public function testResultSpecialProcess(){
         $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
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
         $obj = new Service_Data_SourceList_Part_CommercialPremierMock();
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
         $basicParams = array('major_category_script_index'=> 1000,
                              'city_domain' => 'bj',
                              'get_total_count' => false,
                              'page_no' => 1000,
         );
         $queryFilter0 = array('page_no' => 10); 
         $queryFilter1 = array(); 
         $queryFilter2 = array(); 
         $queryFilter3 = array('major_category_script_index'=>6, 'price'=>1, 'city_domain'=>'bj'); 
         $queryFilter4 = array('price_type'=>1, 'price_b'=>10, 'price_e'=>20); 
         $queryFilter5 = array('price_type'=>2, 'price_b'=>10, 'price_e'=>20); 
         $queryFilter6 = array('area'=>2, 'price'=>null, 'major_category_script_index'=>6, 'deal_type'=>1); 
         $queryFilter7 = array('area'=>2, 'major_category_script_index'=>9); 
         $queryFilter8 = array('major_category_script_index'=>11, 'deal_type'=>123); 
         $queryFilter9 = array('latlng'=>array(1, 3)); 
         $queryFilter10 = array('latlng'=>100); 
         $queryFilter11 = array('date' => 1);

         return array(
                   array(-1, 5, array_merge($basicParams, $queryFilter0), array('offset_limit' =>array(45, 5))),
                   array(HousingVars::TRUE_HOUSE_LIST, 5, array_merge($basicParams, $queryFilter1), array('premier_status' => array(102, 102))),
                   array(-1, -1, array_merge($basicParams, $queryFilter2), array('premier_status' => array(2, 2))),
                   array(-1, -1, array_merge($basicParams, $queryFilter3), array('price_month' => HousingVars::getPriceRange('bj', 6)[1])),
                   array(-1, -1, array_merge($basicParams, $queryFilter4), array('price_day' => array(10, 20))),
                   array(-1, -1, array_merge($basicParams, $queryFilter5), array('price_month' => array(10, 20))),
                   array(-1, -1, array_merge($basicParams, $queryFilter6), 
                           array('area_b' => HousingVars::$AREA_TYPE_STORE_VALUES[2][0], 'area_e' => HousingVars::$AREA_TYPE_STORE_VALUES[2][1])),
                   array(-1, -1, array_merge($basicParams, $queryFilter7), 
                           array('area_b' => HousingVars::$AREA_TYPE_VALUES[2][0], 'area_e' => HousingVars::$AREA_TYPE_VALUES[2][1])),
                   array(-1, -1, array_merge($basicParams, $queryFilter8), array('type' => 123)),
                   array(-1, -1, array_merge($basicParams, $queryFilter9), 
                           array('textFilter'=>array(0 =>array('latlng' =>1), 1=>array('latlng' =>3)) )), 
                   array(-1, -1, array_merge($basicParams, $queryFilter10), array('textFilter' => array(0 =>array('latlng' => 100)))),
                   array(-1, -1, array_merge($basicParams, $queryFilter11),
                        array('refresh_at'=>array(strtotime(date('Y-m-d').' 00:00:00 -'.HousingVars::$DATE_VALUES[1][1].' day'),$_SERVER['REQUEST_TIME'])))
                );
     }

}

