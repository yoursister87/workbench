<?php
error_reporting(E_ALL^E_NOTICE);
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @author               $Author:   zhangrong <zhangrong3@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class Fake_Service_Data_SourceList_HouseList extends Service_Data_SourceList_HouseList
{
    public function setSearchMiddle($a){
        $this->searchMiddleArr = $a;
    }
    public function addMoreSearchResult($resultIndex, $group, $resultArr){
        return parent::addMoreSearchResult($resultIndex, $group, $resultArr);
    }
    public function getMoreCommonSearchResult($resultIndex, $currentResultArr){
        return parent::getMoreCommonSearchResult($resultIndex, $currentResultArr);
    }
    public function getPostAccountInfo($groupResultArr){
        return parent::getPostAccountInfo($groupResultArr);
    }
    public function getSubDataService($majorCategoryScriptIndex, $group, $queryFilterArr){
        return parent::getSubDataService($majorCategoryScriptIndex, $group, $queryFilterArr);
    }
    public function isGroupBy($resultIndex){
        return parent::isGroupBy($resultIndex);
    }
    public function isGetExactTotal($resultIndex, $group){
        return parent::isGetExactTotal($resultIndex, $group);
    }
    public function dealSubwayBusCollogeFilter($queryFilterArr){ 
        return parent::dealSubwayBusCollogeFilter($queryFilterArr);
    }
    /*public function getMoreMixSearchResult($resultIndex, $premierResultArr){
        return parent::getMoreMixSearchResult($resultIndex, $premierResultArr);
    }*/
}

class HouseListTest extends Testcase_PTest
{
    /* {{{ testPreSearch */
    /**
     * @brief 
     *
     * @returns   
     */
    public function testPreSearch(){
        $wrong = array(
            'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
        );
        $ok = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => 0,
        );
        //{{{ 各种错误的参数传入......
        $obj = new Service_Data_SourceList_HouseList();
        $ret = $obj->preSearch(1, 2);
        $this->assertEquals($wrong, $ret);
      
        $ret = $obj->preSearch(array(HousingVars::JINGJIA_LIST => array('count' => 1)), array('queryFilter' => array('major_category_script_index' => 'a')));
        $this->assertEquals($wrong, $ret);

        $ret = $obj->preSearch(array(HousingVars::JINGJIA_LIST => array('count' => 1)), array('queryFilter' => array('major_category_script_index' => '7', 'city_code' => '8888', 'page_no' => 1)));
        $this->assertEquals($wrong, $ret);

        $ret = $obj->preSearch(array(HousingVars::JINGJIA_LIST => array('count' => 1)), array('queryFilter' => array('major_category_script_index' => '7', 'city_domain' => '888', 'page_no' => 1)));
        $this->assertEquals($wrong, $ret);

        $ret = $obj->preSearch(array(HousingVars::JINGJIA_LIST => 1), array('queryFilter' => array('major_category_script_index' => '7', 'city_domain' => 'bj', 'page_no' => 1)));
        $this->assertEquals($wrong, $ret);

        $ret = $obj->preSearch(array('a' => array('count' => 1)), array('queryFilter' => array('major_category_script_index' => '7', 'city_domain' => 'bj', 'page_no' => 1)));
        $this->assertEquals($wrong, $ret);
        //}}}
        //{{{ 正确执行......
        $mockClass = array('Service_Data_SourceList_Part_CommercialCommon' => array('preSearch' => array('return' => 1234567)));
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Service_Data_SourceList_Part_CommercialCommon', $mockObj['Service_Data_SourceList_Part_CommercialCommon']);
        $ret = $obj->preSearch(array(HousingVars::STICKY_LIST => array('count' => 1)), array('queryFilter' => array('major_category_script_index' => '7', 'city_domain' => 'bj', 'page_no' => 1)));
        $this->assertEquals($ok, $ret);
        //}}}
    }//}}}
    /* {{{testGetSearchResult*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function testGetSearchResult(){
        //{{{ 传入的参数和中间变量矛盾...
        $wrong = array(
            'errorno' => ErrorConst::E_INNER_FAILED_CODE,
            'errormsg' => ErrorConst::E_INNER_FAILED_MSG, 
        );
        $searchMiddleArr = array();
        $searchMiddleArr[] = array('groupConfig' => 1, 'queryConfig' => 1, 'searchIds' => 1);
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $ret = $obj->getSearchResult(2);
        $this->assertEquals($wrong, $ret);
        //}}}
        //{{{ 中间变量遭到破坏而返回空......
        $ok = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(),
        );
        $searchMiddleArr = array();
        $searchMiddleArr[] = array('groupConfig' => array('9' => array('count' => 1)), 'queryConfig' => 1, 'searchIds' => 1);
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $ret = $obj->getSearchResult(0);
        $this->assertEquals($wrong, $ret);
        //}}}
        //{{{ 中间变量遭到破坏而返回空......
        $searchMiddleArr = array();
        $searchMiddleArr[] = array('groupConfig' => array(9 => array('count' => 6)), 'queryConfig' => array('queryFilter' => 1), 'searchIds' => array(9 => 123456));
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $ret = $obj->getSearchResult(0);
        $wrong = array('errormsg' => ErrorConst::E_PARAM_INVALID_MSG, 'errorno' => ErrorConst::E_PARAM_INVALID_CODE);
        $this->assertEquals($wrong, $ret);
        //}}}
        //{{{ 正确返回结果, 不用补贴........
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_CommercialCommon' => 
                array(
                    'getSearchResult' => array('return' => array(array(1), 1)),
                )
        );
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_CommercialCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $searchMiddleArr1[] = array(
            'groupConfig' => array(HousingVars::STICKY_LIST => array('count' => 1)), 
            'queryConfig' => array('queryFilter' => 1,'groupFilter'=>''), 
            'searchIds' => array(HousingVars::STICKY_LIST => 1234567)
        );
        $obj->setSearchMiddle($searchMiddleArr1);
        $ret = $obj->getSearchResult(0);
        $wannaRet = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(HousingVars::STICKY_LIST => array(array(1), 1))
        );
        $this->assertEquals($wannaRet, $ret);
        //}}}
        //{{{ 正确返回结果，需要补贴......
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_CommercialPremier' => 
                array(
                    'getSearchResult' => array('return' => array(array(1), 1)),
                )
        );
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_CommercialPremier']),
                    'addMoreSearchResult' => array('return' => array(array(1, 4, 5, 6), 4)),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $searchMiddleArr = array();
        $searchMiddleArr[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 1)), 
            'queryConfig' => array('queryFilter' => 1, 'groupFilter'=>''), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $obj->setSearchMiddle($searchMiddleArr);
        $ret = $obj->getSearchResult(0);
        $wannaRet = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => array(HousingVars::MAIN_BLOCK_LIST => array(array(1, 4, 5, 6), 4))
        );
        $this->assertEquals($wannaRet, $ret);
        //}}}
    }//}}}
    /* {{{testAddMoreSearchResult*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function testAddMoreSearchResult(){
        //{{{ 中间参数遭到破坏，传入的数组原封不动返回.....
        $searchMiddleArr = array();
        $searchMiddleArr[] = array(
            'groupConfig' => 1, 
            'queryConfig' => 1, 
            'searchIds' => 1, 
        );
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $resultArr = array(array(1, 2), 2);
        $ret = $obj->addMoreSearchResult(0, HousingVars::MAIN_BLOCK_LIST, $resultArr);
        $wannaRet = array(array(1, 2), 2);
        $this->assertEquals($wannaRet, $ret);
        //}}}
        //{{{ 正常返回结果，取到了帖子并补充.......
        $searchMiddleArr2[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 10)), 
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7)), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getMoreCommonSearchResult' => array('return' => array(array(3), 1)),
                )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        //$obj = new Fake_Service_Data_SourceList_HouseList();
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr2);
        $resultArr = array(array(1, 2), 2);
        $ret = $obj->addMoreSearchResult(0, HousingVars::MAIN_BLOCK_LIST, $resultArr);
        $wannaRet = array(array(1, 2, 3), 3);
        $this->assertEquals($wannaRet, $ret);
        //}}}
        //{{{ 正常返回结果，只取总数，不需要补贴.......
        $searchMiddleArr3[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 5)), 
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7)), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_CommercialCommon' => 
                array(
                    'getExactPostTotalCount' => array('return' => 687),
                )
        );
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_CommercialCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr3);
        $resultArr = array(array(1, 2, 3, 4, 5), 500);
        $ret = $obj->addMoreSearchResult(0, HousingVars::MAIN_BLOCK_LIST, $resultArr);
        $wannaRet = array(array(1, 2, 3, 4, 5), 1187);
        $this->assertEquals($wannaRet, $ret);
        //}}}
        //{{{ 执行发生异常，传入的数组原封不动返回......
        $searchMiddleArr[] = array();
        $searchMiddleArr[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 10)), 
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7)), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getMoreCommonSearchResult' => array('return' => false),
                )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        //$obj = new Fake_Service_Data_SourceList_HouseList();
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr2);
        $resultArr = array(array(1, 2), 2);
        $ret = $obj->addMoreSearchResult(0, HousingVars::MAIN_BLOCK_LIST, $resultArr);
        $wannaRet = array(array(1, 2), 2);
        $this->assertEquals($wannaRet, $ret);
        //}}}
    }//}}}
    /* {{{testGetMoreCommonSearchResult*/
    /**
     * @brief 
     *
     * @returns   
     */
    public function testGetMoreCommonSearchResult(){
        //{{{ 中间参数遭到破坏，返回空结果集.....
        $searchMiddleArr = array();
        $searchMiddleArr[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 5)), 
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7, 'city_domain' => 'bj')), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $resultArr = array(array(1, 2), 500);
        $ret = $obj->getMoreCommonSearchResult(0, $resultArr);
        $this->assertEquals(null, $ret);
        //}}}
        //{{{ 正确执行，返回补充的帖子集
        $searchMiddleArr = array();
        $searchMiddleArr[] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 5)), 
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7, 'city_domain' => 'bj', 'page_no' => 1)), 
            'searchIds' => array(HousingVars::MAIN_BLOCK_LIST => 1234567)
        );
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_CommercialCommon' => 
                array(
                    'preSearch' => array('return' => 689551),
                    'getSearchResult' => array('return' => array(array('q', 'w', 'e'), 3)),
                )
        );
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_CommercialCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr);
        $resultArr = array(array(1, 2), 500);
        $ret = $obj->getMoreCommonSearchResult(0, $resultArr);
        $wannaRet = array(array('q', 'w', 'e'), 3);
        $this->assertEquals($wannaRet, $ret);
        //}}}
    }//}}}
    
    public function testisGetExactTotal(){
        $searchMiddleArr = array();
        $resultIndex = 0;
        $searchMiddleArr[$resultIndex] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 1)), //取一条
            'queryConfig' => array('queryFilter' => array('major_category_script_index' => 7, 'city_domain' => 'bj', 'page_no' => 101)), //page_no 101
        );
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $return = $obj->isGetExactTotal($resultIndex, HousingVars::MAIN_BLOCK_LIST);
        $this->assertEquals(true, $return);

    }
    public function testisGroupBy(){
        $searchMiddleArr = array();
        $resultIndex = 0;
        $searchMiddleArr[$resultIndex] = array(
            //'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 1)), 
            'queryConfig' => array('groupFilter'=>'xiaoqu_id'), //
        );
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $obj->setSearchMiddle($searchMiddleArr);
        $return = $obj->isGroupBy($resultIndex, HousingVars::MAIN_BLOCK_LIST);
        $this->assertEquals(true, $return);

    }
    
    /*public function testgetMoreMixSearchResult(){
         
        for($i=0;$i<10;$i++){
            $premierResultArr[0][] = array('post_at'=>0);
        }
        $premierResultArr[1] =  10;
        $searchMiddleArr = array();
        $searchMiddleArr[0] = array(
            'groupConfig' => array(HousingVars::MAIN_BLOCK_LIST => array('count' => 5)), 
            'queryConfig' => array('queryFilter' => array(
                                        'major_category_script_index' => 3, 
                                        'city_domain' => 'bj', 
                                        'page_no' => 1,
                                        'premier_common_num' => array(5,0),)
                                    ),
        );

        //{{{测试agent=2的分支
        $searchMiddleArr[0]['queryConfig']['queryFilter']['agent'] = 2;
        $postList = array ( 'Ms' => array ( 0 => array ( ), 1 => 0, ), );
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_RentCommon' => 
                array(
                    'getMixPostList' => array('return' => $postList),
                )
        );
        $expected = $postList;
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_RentCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr);
        $return = $obj->getMoreMixSearchResult(0, $premierResultArr);
        $this->assertEquals($expected, $return);
        //}}}
        //{{{测试agent=3的分支 
        $searchMiddleArr[0]['queryConfig']['queryFilter']['agent'] = 3;
        $NDayMsPersonPostArr = array (  0 => array (array('post_at'=>0), ), 1 => 1 );
        //待补贴数分别是1
        $mixPostList = array ( 'MsAgent' => array ( 0 => array ( ), 1 => 1, ), 'NDayAgoMsPerson' => array ( 0 => array ( ), 1 => 1, ), );
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_RentCommon' => 
                array(
                    'getSearchResult'=> array('return' => $NDayMsPersonPostArr),
                    'getMixPostList' => array('return' => $mixPostList),
                )
        );
        //$expected = array ( 0 => array (array('post_at'=>0), ), 1 => 1, );
        $expected = array (
  'MsAgent' => 
  array (
    0 => 
    array (
    ),
    1 => 1,
  ),
  'NDayAgoMsPerson' => 
  array (
    0 => 
    array (
    ),
    1 => 1,
  ),
  'NDayMsPerson' => 
  array (
    0 => 
    array (
    ),
    1 => 1,
  ),
);
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_RentCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr);
        $return = $obj->getMoreMixSearchResult(0, $premierResultArr);
        $this->assertEquals($expected, $return);
        //}}} 

        //{{{测试agent=3的分支 FANG-700逻辑分支
        $searchMiddleArr[0]['queryConfig']['queryFilter']['agent'] = 3;
        $mixPostList = array ( 'NDayMsPerson' => array ( 0 => array (array('post_at'=>0), ), 1 => 1, ), );
        $mockSubDataserviceClass = array(
            'Service_Data_SourceList_Part_RentCommon' => 
                array(
                    'getSearchResult'=>array('return' => array(array(),9999)),//9999表示NDayMsPerson数据量多，走FANG-700逻辑分支
                    'getMixPostList' => array('return' => $mixPostList),
                )
        );
        $expected = $mixPostList;//array ( 0 => array (array('post_at'=>0), ), 1 => 1, );
        $mockSubDataserviceObj = $this->genAllObjectMock($mockSubDataserviceClass);
        $mockClass = array(
            'Fake_Service_Data_SourceList_HouseList' => 
                array(
                    'getSubDataService' => array('return' => $mockSubDataserviceObj['Service_Data_SourceList_Part_RentCommon']),
            )
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $obj->setSearchMiddle($searchMiddleArr);
        $return = $obj->getMoreMixSearchResult(0, $premierResultArr);
        $this->assertEquals($expected, $return);
        //}}} 
    }*/
    /*
    public function providergetMoreMixSearchResult(){
        
        return array(
            array(array('agent'=>2,),
        );
    }
  */  
    /* {{{testGetPostAccountInfo*/
    /**
     * @brief 
     *
     * @returns   
     */
    /*public function testGetPostAccountInfo(){
        //{{{
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getPostAccountInfo(null);
        $this->assertEquals(array(), $ret);
        //}}}
        //{{{
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $result = array(HousingVars::JINGJIA_LIST => array(array(1), 1));
        $ret = $obj->getPostAccountInfo($result);
        $wannaRet = $result;
        $this->assertEquals($result, $ret);
        //}}}
        //{{{
        $groupSearchResult = array(
            HousingVars::JINGJIA_LIST => 
                array(
                    array(
                        array('account_id' => 1), 
                        array('account_id' => 2)
                    ),
                    1
                )
        );
        $wannaRet = array(
            HousingVars::JINGJIA_LIST => 
                array(
                    array(
                        array('account_id' => 1, 'user_id' => 111), 
                        array('account_id' => 2, 'user_id' => 222),
                    ),
                    1
                )
        );

        $accountInfo = array(1 => array('user_id' => 111), 2 => array('user_id' => 222));
        $mockClass = array(
            'Service_Data_Broker_Info' => 
                array(
                    'getAccountInfo' => array('return' => $accountInfo),
                ),
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Service_Data_Broker_Info', $mockObj['Service_Data_Broker_Info']);
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getPostAccountInfo($groupSearchResult);
        $this->assertEquals($wannaRet, $ret);
        //}}}
        //{{{
        $groupSearchResult = array(
            HousingVars::JINGJIA_LIST => 
                array(
                    array(
                        array('account_id' => 1), 
                        array('account_id' => 2)
                    ),
                    1
                )
        );
        $wannaRet = $groupSearchResult;
        $accountInfo = array(1 => array('user_id' => 111), 2 => array('user_id' => 222));
        $mockClass = array(
            'Service_Data_Broker_Info' => 
                array(
                    'getAccountInfo' => array('return' => false),
                ),
        );
        $mockObj = $this->genAllObjectMock($mockClass);
        PlatformSingleton::setInstance('Service_Data_Broker_Info', $mockObj['Service_Data_Broker_Info']);
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getPostAccountInfo($groupSearchResult);
        $this->assertEquals($wannaRet, $ret);
        //}}}
    }*///}}}
    
    public function  providerGetSubDataService(){ 
        $queryFilterArr = array();
        $queryFilterArrAgent1 = array('agent'=>1);
        return array(
                array('1', true, $queryFilterArr, 'RentCommon'),
                array('1', HousingVars::TRUE_HOUSE_LIST, $queryFilterArr, 'RentPremier'),//放心房返回Premier的subService;
                array('1', HousingVars::STICKY_LIST, $queryFilterArr,'RentCommon'),
                array('1', HousingVars::MAIN_BLOCK_LIST, $queryFilterArr, 'RentPremier'),
                array('1', HousingVars::MAIN_BLOCK_LIST, $queryFilterArrAgent1, 'RentCommon'),//agent=1时返回Common的subService
                array('1', HousingVars::GONGYU_LIST, $queryFilterArr, 'RentPremier'),
            );    
    }
    
    /* {{{testGetSubDataService*/
    /**
     * @dataProvider providerGetSubDataService
     *
     * @brief 
     *
     * @returns   
     */
    public function testGetSubDataService($majorCategoryScriptIndex, $group, $queryFilterArr, $strSubDataService){
        //fang1,3
        $subDataServiceClassName = 'Service_Data_SourceList_Part_'. $strSubDataService;
        $mockClass = array($subDataServiceClassName => array());//mock 一个$subDataServiceClassName的空对象
        $mockObj = $this->genAllObjectMock($mockClass);
        $wannaObj = $mockObj[$subDataServiceClassName];
        PlatformSingleton::setInstance($subDataServiceClassName, $wannaObj);
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getSubDataService($majorCategoryScriptIndex, $group, $queryFilterArr);
        $this->assertEquals($wannaObj, $ret);
        //{{{
        $mockClass = array('Service_Data_SourceList_Part_CommercialCommon' => array('preSearch' => array('return' => 1234567)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $wannaObj = $mockObj['Service_Data_SourceList_Part_CommercialCommon'];
        PlatformSingleton::setInstance('Service_Data_SourceList_Part_CommercialCommon', $wannaObj);

        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getSubDataService(7, true, null);
        $this->assertEquals($wannaObj, $ret);
        //}}}
        //{{{
        $mockClass = array('Service_Data_SourceList_Part_CommercialCommon' => array('preSearch' => array('return' => 1234567)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $wannaObj = $mockObj['Service_Data_SourceList_Part_CommercialCommon'];
        PlatformSingleton::setInstance('Service_Data_SourceList_Part_CommercialCommon', $wannaObj);

        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getSubDataService(7, HousingVars::MAIN_BLOCK_LIST, array('agent' => 1));
        $this->assertEquals($wannaObj, $ret);
        //}}}
        //{{{
        $mockClass = array('Service_Data_SourceList_Part_CommercialPremier' => array('preSearch' => array('return' => 1234567)));
        $mockObj = $this->genAllObjectMock($mockClass);
        $wannaObj = $mockObj['Service_Data_SourceList_Part_CommercialPremier'];
        PlatformSingleton::setInstance('Service_Data_SourceList_Part_CommercialPremier', $wannaObj);

        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getSubDataService(7, HousingVars::MAIN_BLOCK_LIST, array());
        $this->assertEquals($wannaObj, $ret);
        //}}}
    }//}}}
    public function  providerdealSubwayBusCollogeFilter(){
        $queryFilterArr = array(
            'list_type'=> 'ditie',
        );

        $expect =  array (
            'list_type' => 'ditie',
            'textFilter' =>  array (
                0 => 
                array (
                    'subway_line' => 'all',
                ),
            ),
        );

        //测试只有ditie,同时getLatLng返回空时
        $queryFilterArr1 = $queryFilterArr;
        $getLatLng1 =  '';
        $expect1 = $expect;
        //测试有subway_line
        $queryFilterArr2 = array(
            'list_type' => 'ditie',
            'subway_line' => 11,
        );
        $getLatLng2 =  '';
        $expect2 = $expect1;
        //$expect2['subway_line'] = 11; // $expect2['textFilter'][0]['subway_line']和$expect2]['subway_line']不能共存
        $expect2['textFilter'][0]['subway_line'] = 11;
        //getlatlng 有数据时
        $queryFilterArr3 = $queryFilterArr;
        $queryFilterArr3['station'] =2;
        $getLatLng3 = '1,1';
        $expect3 = $expect;
        $expect3['latlng'] = $getLatLng3;
        $expect3['station'] = 2;
        unset($expect3['textFilter']);
        //测试有latlng时
        $queryFilterArr4 = $queryFilterArr;
        $queryFilterArr4['station'] =2;
        $queryFilterArr4['latlng'] = '1,1';//存在时，不会被覆盖及
        $getLatLng4 =  '2,2';
        $expect4 = $expect;
        $expect4['station'] = 2;
        $expect4['latlng'] = $queryFilterArr4['latlng'];
        unset($expect4['textFilter']);

        return array(
            array($queryFilterArr1,$getLatLng1,$expect1),
            array($queryFilterArr2,$getLatLng2,$expect2), 
            array($queryFilterArr3,$getLatLng3,$expect3),
            array($queryFilterArr4,$getLatLng4,$expect4), 
            //array($queryFilterArr1,$getLatLng1,$expect1),
        ); 
    }

    /** 
     * @dataProvider providerdealSubwayBusCollogeFilter
     */
    public function testdealSubwayBusCollogeFilter($queryFilterArr, $getLatLng, $expect){
        $mockClass = array(
                         'Fake_Service_Data_SourceList_HouseList' => array(
                            'getLatLng' => array(
                               'return' =>  $getLatLng,
                            ))
                       );
        $mockObj = $this->genAllObjectMock($mockClass);
        $obj = $mockObj['Fake_Service_Data_SourceList_HouseList'];
        $return = $obj->dealSubwayBusCollogeFilter($queryFilterArr);
        $this->assertEquals($return, $expect);
        
    }
     public function providerGetLatLng(){
        $queryFilterArr1 =  array(
            'list_type' => 'ditie',
            'subway_line' => '11',
            'station' => 4,
        );
        $search_type1 ='subway_line'; 
        $method1 = 'getSubwayStationInfoByLineIdAndStationNumber';
        $expect1 = '1,1';
        return array(
                array($queryFilterArr1, $search_type1, $method1, $expect1),
            );
    }
    
    /** 
     * @dataProvider providerGetLatLng
     */
    public function testGetLatLng($queryFilterArr, $search_type, $method, $expect){ 
        /*
        $staticLatLng = $this->genStaticMock('BusSubwayCollegeNamespce', array('getLatLngRange'));
        $staticLatLng->expects($this->any())
            ->method("getLatLngRange")
            ->will($this->returnValue($expect));  

        $objLatLng = $this->genObjectMock('BusSubwayCollegeNamespce', array($method));
        $objLatLng->expects($this->any())
                ->method($method)
                ->will($this->returnValue(array(
                        'id' => 4,
                        'name' => 'xxxx',
                        'lat' => 39,
                        'lng' => 116.2,
                )));
        
        //Gj_LayerProxy::registerProxy('Util_Source_BusSubwayCollegeNamespce', $mockObj['Util_Source_BusSubwayCollegeNamespce']);
        //Gj_LayerProxy::registerProxy('BusSubwayCollegeNamespce', $mockObj['BusSubwayCollegeNamespce']);
        //Gj_LayerProxy::registerProxy('BusSubwayCollegeNamespce', $objLatLng);


        $obj = new Util_HouseXapianMock();
        $obj->objBusSubwayCollegeNamespce = $objLatLng;
        $return = $obj->getLatLng($queryFilterArr,$search_type,$method);
        $this->assertEquals($expect, $return); */
    }
    
    
    /*public function testgetSearchResultSync(){
        //测试errorno不为0的分支
        $returnData = array('errorno'=>1,'errormsg'=>'error','data'=>'');
        $mockClass = array(
                         'Service_Data_SourceList_HouseList' => array(
                            'preSearch' => array(
                               'return' =>  $returnData,
                            ))
                       );
        $mockObj = $this->genAllObjectMock($mockClass);
        $HouseListObj = $mockObj['Service_Data_SourceList_HouseList'];
        $data = $HouseListObj->getSearchResultSync(array(),array());
        $this->assertEquals($returnData, $data);
        //测试errorno=0的分支
        $returnData = array('errorno'=>0,'errormsg'=>'','data'=>'preSearch');
        $returnData2 = array('errorno'=>0,'errormsg'=>'','data'=>'getSearchResult');
        $mockClass = array(
                         'Service_Data_SourceList_HouseList' => array(
                            'preSearch' => array(
                               'return' =>  $returnData,
                            ),
                            'getSearchResult' => array(
                                'return' => $returnData2,
                            )),
                       );
        $mockObj = $this->genAllObjectMock($mockClass);
        $HouseListObj = $mockObj['Service_Data_SourceList_HouseList'];
        $data = $HouseListObj->getSearchResultSync(array(),array());
        $this->assertEquals($returnData2, $data);

    }*/
/*
    public function testGetJingjiaList(){
        $wrong = array(
            'errorno' => ErrorConst::E_PARAM_INVALID_CODE,
            'errormsg' => ErrorConst::E_PARAM_INVALID_MSG
        );
        $ok = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data' => 0,
        );
        $queryConfigArr = null;
        $obj = new Fake_Service_Data_SourceList_HouseList();
        $ret = $obj->getJingjiaList($queryConfigArr);
    }
    */
}
