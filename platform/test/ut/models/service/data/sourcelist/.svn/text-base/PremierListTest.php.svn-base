<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class Service_Data_Sourcelist_PremierListTest extends Testcase_PTest
{
    public function testGetPostList(){
        $arrMockRet = array (0 =>array (
            'type' => '1',
            'house_id' => '1404872445',));
        $objMock = $this->genObjectMock("Curl",array("post"));
        $objMock->expects($this->any())
            ->method('post')
            ->with($this->IsType("string"),array(121617,2,1,2))
            ->will($this->returnValue(json_encode($arrMockRet)));
        Gj_Util_Curl::setInstance($objMock);
        $obj = new Service_Data_Sourcelist_PremierList();
        $res = $obj->getPostList(121617,2,1,2);
        $this->assertEquals($res,array('errorno' => ErrorConst::SUCCESS_CODE,'errormsg' => ErrorConst::SUCCESS_MSG,'data'=>$arrMockRet));
    }
	public function testgetPostCountByAccountId1(){
		$arrInput = array();
		$whereConds = array();
		$obj = new Service_Data_Sourcelist_PremierList();
		$ret = $obj->getPostCountByAccountId1($arrInput,$whereConds);
		$result = array(
			'data' => array(),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);  
		$this->assertEquals($ret,$result);
	}
	public function testgetPostCountByAccountId(){
		$arrInput = array();
		$whereConds = array();
		$obj = new Service_Data_Sourcelist_PremierList();
		$ret = $obj->getPostCountByAccountId($arrInput,$whereConds);
		$result = array(
			'data' => array(),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);
		$this->assertEquals($ret,$result);
/*
		$arrInput = array(1);
		$whereConds = array(2);
		$res = array('data' => 1);
        $obj= $this->genObjectMock("Dao_Housepremier_HouseSourceList",array("selectGroupbyAccountId"));
        $obj->expects($this->any())
            ->method('selectGroupbyAccountId')
			->will($this->returnValue($res));
		Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseSourceList",$obj);		
		$obj1 = new Service_Data_Sourcelist_PremierList();
		$ret = $obj1->getPostCountByAccountId($arrInput,$whereConds);
		$result = array(
			'data' => array(),
			'errorno' => 0,
			'errormsg' => '[数据返回成功]'
		);  
		$this->assertEquals($ret,$result);
*/
 	}
}
