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
class Service_Data_SourceList_Part_RentCommonMock extends Service_Data_SourceList_Part_RentCommon
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

class RentCommonTest extends Testcase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
      public function testisPremier(){
         $obj = new Service_Data_SourceList_Part_RentCommonMock();
         //测试10次避免碰巧return false的情况
         for($i=0; $i<10; $i++){
             $ret = $obj->isPremier();
             $this->assertEquals($ret, false);
         }
     }
 
     public function testpreSearch(){
          $mockClass = array('Dao_Xapian_Zufang' => 
                               array('preSearch' => array('return' => 'www')),
                             'Service_Data_SourceList_Part_RentCommon' =>
                                array('preSearch' => array('return' =>'www')),
                             'Service_Data_SourceList_Part_RentCommonMock' =>
                               array('formatQueryFilter' => array('return' => 'www')));
          $mockObj = $this->genAllObjectMock($mockClass);
          PlatformSingleton::setInstance('Dao_Xapian_Zufang', $mockObj['Dao_Xapian_Zufang']);
          $obj = $mockObj['Service_Data_SourceList_Part_RentCommon'];
          $ret = $obj->preSearch('', '', array('queryFilter' => array()));
          $this->assertEquals($ret, 'www');
          return $ret;
     }
     
     /**
      * @depends testpreSearch
      */
     public function testgetSearchResult($resultIndex){
          $mockClass = array('Dao_Xapian_Zufang' => array('getSearchResult' => array('return' => array(array(),0))));
          $mockObj = $this->genAllObjectMock($mockClass);
          Gj_LayerProxy::registerProxy('Dao_Xapian_Zufang', $mockObj['Dao_Xapian_Zufang']);
          $obj = new Service_Data_SourceList_Part_RentCommon();
          $ret = $obj->getSearchResult($resultIndex, array());
          $this->assertEquals($ret, array(array(),0));
         
     }

     /**
       * @dataProvider providerresultSpecialProcess
       */
     public function testresultSpecialProcess($result, $group, $returnValues){
         $obj = new Service_Data_SourceList_Part_RentCommonMock();
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
         $mockClass = array('Service_Data_SourceList_Part_RentCommon' => array(
                      'preSearch' => array('return' => 1),
                      'getSearchResult' => array('return' => array(8, 1))
                     )); 
         $mockObj = $this->genAllObjectMock($mockClass);
         $obj = new Service_Data_SourceList_Part_RentCommonMock();
         $obj = $mockObj['Service_Data_SourceList_Part_RentCommon']; 
         $ret = $obj->getExactPostTotalCount(-1, array());
         $this->assertEquals($ret, 1);
     }

     /**
      * @dataProvider providerformatQueryFilter
      */
     public function testformatQueryFilter($group, $count, $queryFilterArr, $returnValues){
       $obj = new Service_Data_SourceList_Part_RentCommonMock();
       $ret = $obj->formatQueryFilter($group, $count, $queryFilterArr);
       foreach($returnValues as $key => $value){
        if($queryFilterArr['major_category_script_index'] == 1 ){//==1时不应出现major_category 
            $this->assertEquals(true, !isset($ret['major_category']));
         } else {
            $this->assertEquals(true, isset($ret[$key]));
         }
         $this->assertEquals($value, $ret[$key]);
       }
     }

     public function providerformatQueryFilter(){
         //键名为字符时,array_merge()此时会覆盖掉前面相同键名的值
         $basicParams = array('major_category_script_index'=> 1,
                              'city_domain' => 'bj',
                              'get_total_count' => false,
                              'page_no' => 1000,
         );
         $queryFilter0 = array('get_total_count' => true); 
         $queryFilter1 = array('get_total_count' => false); 
         $queryFilter2 = array('get_total_count' => false); 
         $queryFilter3 = array(); 
         $queryFilter4 = array('page_no' => 10); 
         $queryFilter5 = array('major_category_script_index' => 3,  'city_domain' =>'bj'); 
        
         return array(
                   array(-1, -1, array_merge($basicParams, $queryFilter0), array('listing_status' => array(5, 50))),
                   array(HousingVars::STICKY_LIST, -1, array_merge($basicParams, $queryFilter1), 
                                                                   array('listing_status' => array(6, 50), 'post_type' => array(1, 10))),
                   array(HousingVars::MAIN_BLOCK_LIST, -1, array_merge($basicParams, $queryFilter2), array('listing_status' => array(5, 50), 'post_type' => array(0,1,10))),
                   array(HousingVars::STICKY_LIST, -1, array_merge($basicParams, $queryFilter3), array('offset_limit' => array(0, 150))),
                   array(-1, 5, array_merge($basicParams, $queryFilter4), array('offset_limit' =>array(45, 5))),
                   array(-1, -1, array_merge($basicParams, $queryFilter5), array('major_category'=>3)),
                );
     }
    
    public function testgetMixPostList(){
        for($i=0;$i<20;$i++){
            $_postList[$i] = array('post_at'=>0);//虚拟帖子
        }
        $queryFilterArr =  array(
             'major_category_script_index' => 1,
             'city_code' => 0,
             'page_no' => 1,
             'post_at' => array('1408960620', '1411552620'),
             'premier_common_num' => array(5,2),
        );
        $queryConfigArr = array(
             'queryFilter' => $queryFilterArr, 
             //'queryField' => $this->dbFields,
             //'groupFilter' => 'district_id',
         );
        $premierResultAll = array_slice($_postList,0,6); //所有的端口贴共6
        $premierSize = $queryFilterArr['premier_common_num'][0];
        
        $willMixPostList = array(
            'NDayMsPerson'=> array(array_slice($_postList,0,$premierSize),10),
            'MsAgent' => array(array_slice($_postList,0,$premierSize),10),
            'NDayAgoMsPerson'=>array(array_slice($_postList,0,$premierSize),10),
        );
        $expectedMixPostList = array(
            'NDayMsPerson'=> array(array(),10),
            'MsAgent' => array(array(),10),
            'NDayAgoMsPerson'=>array(array(),0),
        );
        
        $premierResultArr = array(0=>array_slice($_postList,0,$premierSize),1=>6); //只补总数
        $obj = new Service_Data_SourceList_Part_RentCommonMock();
        $postList = $obj->getMixPostList($queryConfigArr, $willMixPostList, $premierSize, $premierResultArr);
        $this->assertEquals($expectedMixPostList, $postList);
        $queryConfigArr['queryFilter']['page_no'] = 2;
        $queryConfigArr['queryFilter']['post_at'] = null;
        $currPage = $queryConfigArr['queryFilter']['page_no'];
        $premierResultArr = array(0=>array_slice($premierResultAll, ($currPage-1)*$premierSize, $premierSize), 1=>6); //共6条，每页5条，第二页需要补4个贴和总数
        $willMixPostList = array(
            'NDayMsPerson'=> array(array_slice($_postList,0,1),1),//只有1条
            'MsAgent' => array(array_slice($_postList,0,2),2),//只有2条
            'NDayAgoMsPerson'=>array(array_slice($_postList,0,10),10),
        );
        $expectedMixPostList = array(
            'NDayMsPerson'=> array(array_slice($_postList,0,1),1),//补1条
            'MsAgent' => array(array_slice($_postList,0,2),2),//补2条
            'NDayAgoMsPerson'=>array(array_slice($_postList,0,1),10),//补1条
        );
        $postList = $obj->getMixPostList($queryConfigArr, $willMixPostList, $premierSize, $premierResultArr);
        $this->assertEquals($expectedMixPostList, $postList);

    }
    public function testgetQuerySetting(){
        $queryConfigArr['queryFilter'] = array(
             'major_category_script_index' => 1,
             'city_code' => 0,
             'post_at' => array(1209760000, 1216748845),
         );
        $mockMixQueryConfigArr = array(
                  'post_at' =>
                      array (
                          0 => 1409760000,
                          1 => 1416748845,
                      ),
                  'agent' => 1,
              );
        $mockClass = array('Service_Data_SourceList_Part_RentCommon' => 
                               array('getMixQueryConfigArr' => array('return' => $mockMixQueryConfigArr)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = new Service_Data_SourceList_Part_RentCommonMock();
        $return  = $obj->getQuerySetting($queryConfigArr, 'NDayAgoMsPerson');
        $expected = array (
            'queryFilter' => 
            array (
                'major_category_script_index' => 1,
                'city_code' => 0,
                'post_at' => 
                array (
                    0 => 1209760000,
                    1 => 1216748845,
                ),
                'agent' => 1,
            ),
        );
        $this->assertEquals($expected, $return);
    }
    
    /**
     * @dataProvider providergetMixQueryConfigArr 
     */
    public function testgetMixQueryConfigArr($queryConfigArr,$type,$currtime,$expected){
        $timeObj = new Gj_Util_TimeMock();
        $timeObj->setTime($currtime);
        //Gj_LayerProxy::$is_ut = true;
        //Gj_LayerProxy::registerProxy('Gj_Util_TimeMock', $timeObj);
        $obj = new Service_Data_SourceList_Part_RentCommonMock();
        $obj->timeObj= $timeObj;
        $return = $obj->getMixQueryConfigArr($queryConfigArr, $type); 
        $this->assertEquals($expected, $return);
    } 
    public function providergetMixQueryConfigArr(){
        $queryConfigArr['queryFilter'] = array(
            'major_category_script_index' => 1,
            'city_code' => 0,
        );
        $currtime =  1417612845;
        $expected = array(
            'NDayMsPerson' => array(
                'post_at' => 
                    array (
                        0 => 1416672000,
                        1 => $currtime,
                    ),
                'agent' => 1,
            ),
            'MsAgent'=>array(
                'agent' =>2,
                'post_at'=>
                    array(
                        0=>strtotime(date('Y-m-d', $currtime)) - 86400 * 90,
                        1=>$currtime,
                    ),
            ),
            'NDayAgoMsPerson' => array(
                 'post_at' =>  
                     array (
                         0 => 1409760000,
                         1 => 1416748845,
                     ),  
                 'agent' => 1,
             )    
        );
        return array(
            array($queryConfigArr, 'NDayMsPerson', $currtime, $expected['NDayMsPerson']),
            array($queryConfigArr, 'MsAgent' , $currtime, $expected['MsAgent']),
            array($queryConfigArr, 'NDayAgoMsPerson', $currtime, $expected['NDayAgoMsPerson']),
        );
    }
}
