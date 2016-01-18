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
define ('PLATFORM_CODE' , 'web') ;
class Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon extends Service_Data_SourceList_Part_LoupanPremierAndCommon
{
    public function formatQueryFilter($group, $count, $queryFilterArr){
        return parent::formatQueryFilter($group, $count, $queryFilterArr); 
    }
    public function resultSpecialProcess($result, $group){
        return parent::resultSpecialProcess($result, $group); 
    }
}
class LoupanPremierAndCommonTest extends Testcase_PTest
{
    // {{{testisPremier
    public function testisPremier(){
        $obj = new Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon(); 
        for ($i=0; $i<10; $i++) {
            $ret = $obj->isPremier();
            $this->assertEquals($ret, false);
        }
    }//}}}
    // {{{testgetModelInstance 
    public function testgetModelInstance(){
        $obj = new Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon();
        $mockClass = array('Dao_Xapian_Loupan' => array());
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Dao_Xapian_Loupan', $mockObj['Dao_Xapian_Loupan']);
        $ret = $obj->getModelInstance();
        $this->assertEquals($ret instanceof Dao_Xapian_Loupan, true);
    }//}}}
    // {{{testpreSearch
    public function testpreSearch(){
        $mockClass = array(
            'Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon' => 
                array('formatQueryFilter' => array('return' => 'success')),
            'Dao_Xapian_Loupan' => array('preSearch' => array('return' => '12345')),
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Dao_Xapian_Loupan', $mockObj['Dao_Xapian_Loupan']);
        $obj = $mockObj['Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon'];
        $ret = $obj->preSearch(6, '', array('queryFilter' => array(), 'groupFilter' => array()));
        $this->assertEquals($ret, '12345');
    }//}}}
    // {{{testgetSearchResult
    public function testgetSearchResult(){
        $mockClass = array(
            'Service_Data_SourceList_Part_LoupanPremierAndCommon' => array('resultSpecialProcess' => array('return' => 'res1')), 
            'Dao_Xapian_Loupan' => array('getSearchResult' => array('return' => 'res2')),
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Dao_Xapian_Loupan', $mockObj['Dao_Xapian_Loupan']);
        $obj = $mockObj['Service_Data_SourceList_Part_LoupanPremierAndCommon']; 
        $ret = $obj->getSearchResult(111, array('group' => HousingVars::STICKY_LIST));
        $this->assertEquals($ret, 'res1');
        $ret = $obj->getSearchResult(111, array());
        $this->assertEquals($ret, 'res2');
    }//}}}
     /**
      * @dataProvider providerformatQueryFilter
      */
     public function testformatQueryFilter($group, $count, $queryFilterArr, $returnValues){
       $obj = new Fake_Service_Data_SourceList_Part_LoupanPremierAndCommon();
       $ret = $obj->formatQueryFilter($group, $count, $queryFilterArr);
       foreach ($returnValues as $key => $value) {
           $this->assertEquals($value, $ret[$key]);
       }
     }
     /* {{{providerformatQueryFilter*/
     /**
      * @brief 
      *
      * @returns   
      */
     public function providerformatQueryFilter(){
         $basicParams = array(
             'major_category_script_index'=> 1000,
             'get_total_count' => false,
             'city_domain' => 'bj',
             'page_no' => 1000,
         );
         return array(
             array(1, 1, array_merge($basicParams, array('get_total_count' => true)), array('listing_status' => array(5, 50))),
             array(HousingVars::STICKY_LIST, null, $basicParams, array('listing_status' => array(9, 50), 'post_type' => array(1, 10))),
             array(HousingVars::STICKY_LIST, -1, $basicParams, array('listing_status' => array(9, 50), 'post_type' => array(1, 10), 'offset_limit' => array(0, 150))),
             array(HousingVars::STICKY_LIST, null, array_merge($basicParams, array('price_b' => 1, 'price_e' => 2)), array('price_b' => 10000, 'price_e' => 20000)),
             array(HousingVars::STICKY_LIST, null, array_merge($basicParams, array('area' => 1)), array('area_b' => 0, 'area_e' => 50)),
             array(HousingVars::MAIN_BLOCK_LIST, null, $basicParams, array('listing_status' => array(5, 8))),
         );
     }//}}}
}
