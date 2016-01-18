<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangrong <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Service_Data_SourceList_Part_CommercialCommonMock extends Service_Data_SourceList_Part_CommercialCommon
{
    /* {{{formatQueryFilter*/
    /**
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
    public function resultSpecialProcess($result, $group){
        return parent::resultSpecialProcess($result, $group); 
    }
}

class CommercialCommonTest extends Testcase_PTest
{
     public function testisPremier(){
         $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
         //测试10次避免碰巧return false的情况
         for($i=0; $i<10; $i++){
             $ret = $obj->isPremier();
             $this->assertEquals($ret, false);
         }
     }
     
     public function testgetModelInstance(){
          $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
          $mockClass = array('Dao_Xapian_Shangpu' => array() );
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangpu', $mockObj['Dao_Xapian_Shangpu']);
          $ret = $obj->getModelInstance();
          $this->assertEquals($ret instanceof Dao_Xapian_Shangpu, true);
     }

     public function testpreSearch(){
          $mockClass = array('Dao_Xapian_Shangpu' => 
                               array('preSearch' => array('return' => 'www')),
                             'Service_Data_SourceList_Part_CommercialCommonMock' =>
                               array('formatQueryFilter' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangpu', $mockObj['Dao_Xapian_Shangpu']);
          $obj = $mockObj['Service_Data_SourceList_Part_CommercialCommonMock'];
          $ret = $obj->preSearch('', '', array('queryFilter' => array()));
          $this->assertEquals($ret, 'www');
     }

     public function testgetSearchResult(){
          $mockClass = array('Dao_Xapian_Shangpu' => 
                               array('getSearchResult' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Shangpu', $mockObj['Dao_Xapian_Shangpu']);
          $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
          $ret = $obj->getSearchResult(123, array());
          $this->assertEquals($ret, 'www');
         
     }

     /**
       * @dataProvider providerresultSpecialProcess
       */
     public function testresultSpecialProcess($result, $group, $returnValues){
         $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
         $ret = $obj->resultSpecialProcess($result, $group);
         $this->assertEquals($ret, $returnValues);
     }
     
   public function  providerresultSpecialProcess(){
       $postList = array(
                      array('post_type' => 2),
                      array('post_type' => 3),
                      array('post_type' => 11),
                   );
       $params_pl0 = array(null, null, null); 
       $params_pl1 = array('123', null, '123'); 
       $params_pl2 = array(array($postList, 3), HousingVars::STICKY_LIST, array(array(), 0)); 
       $params_pl3 = array(array($postList, 3), 100, array($postList, 3)); 

       return array( 
                  $params_pl0,
                  $params_pl1,
                  $params_pl2,
                  $params_pl3,
               );
     }

    public function testgetExactPostTotalCount(){
         $mockClass = array('Service_Data_SourceList_Part_CommercialCommon' => array(
                      'preSearch' => array('return' => 1),
                      'getSearchResult' => array('return' => array(8, 1))
                     )); 
         $mockObj = $this->genAllObjectMock($mockClass);
         $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
         $obj = $mockObj['Service_Data_SourceList_Part_CommercialCommon']; 
         $ret = $obj->getExactPostTotalCount(-1, array());
         $this->assertEquals($ret, 1);
     }

     /**
      * @dataProvider providerformatQueryFilter
      */
     public function testformatQueryFilter($group, $count, $queryFilterArr, $returnValues){
       $obj = new Service_Data_SourceList_Part_CommercialCommonMock();
       $ret = $obj->formatQueryFilter($group, $count, $queryFilterArr);
       foreach($returnValues as $key => $value){
         $this->assertEquals(true, isset($ret[$key]));
         $this->assertEquals($value, $ret[$key]);
       }
     }

     public function providerformatQueryFilter(){
         //键名为字符时,array_merge()此时会覆盖掉前面相同键名的值
         $basicParams = array('major_category_script_index'=> 1000,
                              'city_domain' => 'bj',
                              'get_total_count' => false,
                              'page_no' => 1000,
         );
         $queryFilter0 = array('get_total_count' => true); 
         $queryFilter1 = array('get_total_count' => false); 
         $queryFilter2 = array('get_total_count' => false); 
         $queryFilter3 = array(); 
         $queryFilter4 = array('page_no' => 10); 
         $queryFilter5 = array('major_category_script_index' => 6, 'price' => 1, 'city_domain' =>'bj'); 
         $queryFilter6 = array('price_type' => 1, 'price_b' => 10, 'price_e' =>'100'); 
         $queryFilter7 = array('price_type' => 2, 'price_b' => 10, 'price_e' =>'100'); 
         $queryFilter8 = array('area' => 1,'price'=>null, 'major_category_script_index' => 6, 'deal_type' => 1); 
         $queryFilter9 = array('area' => 1, 'major_category_script_index' => 100); 
         $queryFilter10 = array('date' => 1); 
         $queryFilter11 = array('major_category_script_index' => 6, 'price_b' => 1, 'price_e' => 2); 
         $queryFilter12 = array('price_b' => 1, 'price_e' => 2); 

         return array(
                   array(-1, -1, array_merge($basicParams, $queryFilter0), array('listing_status' => array(5, 50))),
                   array(HousingVars::STICKY_LIST, -1, array_merge($basicParams, $queryFilter1), 
                                                                   array('listing_status' => array(6, 50), 'post_type' => array(1, 10))),
                   array(-1, -1, array_merge($basicParams, $queryFilter2), array('listing_status' => array(5, 50), 'post_type' => array(0,1,10))),
                   array(HousingVars::STICKY_LIST, -1, array_merge($basicParams, $queryFilter3), array('offset_limit' => array(0, 150))),
                   array(-1, 5, array_merge($basicParams, $queryFilter4), array('offset_limit' =>array(45, 5))),
                   array(-1, -1, array_merge($basicParams, $queryFilter5), array('price_month' => HousingVars::getPriceRange('bj', 6)[1])),
                   array(-1, -1, array_merge($basicParams, $queryFilter6), array('price_day' => array(10, 100))),
                   array(-1, -1, array_merge($basicParams, $queryFilter7), array('price_month' => array(10, 100))),
                   array(-1, -1, array_merge($basicParams, $queryFilter8),
                         array('area_b' =>HousingVars::$AREA_TYPE_STORE_VALUES[1][0], 'area_e' => HousingVars::$AREA_TYPE_STORE_VALUES[1][1])), 
                   array(-1, -1, array_merge($basicParams, $queryFilter9), 
                         array('area_b' => HousingVars::$AREA_TYPE_VALUES[1][0],'area_e' => HousingVars::$AREA_TYPE_VALUES[1][1])),
                   array(-1, -1, array_merge($basicParams, $queryFilter10), 
                         array('post_at'=>array(strtotime(date('Y-m-d').' 00:00:00 -'.HousingVars::$DATE_VALUES[1][1].' day'),$_SERVER['REQUEST_TIME']))),
                   array(-1, -1, array_merge($basicParams, $queryFilter11), array('price_month' => array(1, 2))),
                   array(-1, -1, array_merge($basicParams, $queryFilter12), array()),
                );
     }
}
