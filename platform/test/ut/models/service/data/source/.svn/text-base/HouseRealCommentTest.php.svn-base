<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   zhangliming$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class HouseRealComment extends Testcase_PTest
{
    protected $data;
    protected $result;
    protected $arrFields;

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
        $this->result = array(array('id'=>123,'company_id'=>45888));
        $this->arrFields = array("comment_id","puid","title","content","post_at","modified_at","ip","user_id","user_name","user_phone","owner_user_id");
    }
    public function testGetCommentListByWhere(){
        $whereConds = array();
        $obj = new Service_Data_Source_HouseRealComment();
        $res = $obj->getCommentListByWhere($whereConds, $this->arrFields, 1, 30);
        $returnData['data'] = array();
        $returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($returnData,$res);

        $arrConds = array('puid ='=>92686679,'stat ='=>1);
        $orderArr = array('post_at'=>'DESC');
        $obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("selectByPage"));
        $obj->expects($this->any())
            ->method('selectByPage')
            ->with($this->arrFields, $arrConds, 1, 30, $orderArr)
            ->will($this->returnValue($this->result));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('puid'=>92686679);
        $res = $commentObj->getCommentListByWhere($whereConds, $this->arrFields, 1, 30, $orderArr);
        $this->data['data'] = $this->result;
        $this->assertEquals($this->data,$res);
    }
    public function testGetCommentCountByWhere(){
        $whereConds = array();
        $obj = new Service_Data_Source_HouseRealComment();
        $res = $obj->getCommentCountByWhere($whereConds);
        $returnData['data'] = array();
        $returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($returnData,$res);
		
        $arrConds = array('puid ='=>92686679,'stat ='=>1);
        $num = 10;
        $obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("selectByCount"));
        $obj->expects($this->any())
        ->method('selectByCount')
        ->with($arrConds)
        ->will($this->returnValue($num));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('puid'=>92686679);
        $res = $commentObj->getCommentCountByWhere($whereConds);
        $this->data['data'] = $num;
        $this->assertEquals($this->data,$res);

    }
    public function testGetCommentWhere(){
        $arrConds = array('puid ='=>92686679,'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('puid'=>92686679);
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);

        $arrConds = array("puid in ( 92686679,422277 )",'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('puid'=>array(92686679,422277));
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);

        $arrConds = array('user_id ='=>92686679,'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('user_id'=>92686679);
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);

        $arrConds = array("user_id in ( 92686679,422277 )",'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('user_id'=>array(92686679,422277));
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);

        $arrConds = array('owner_user_id ='=>92686679,'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('owner_user_id'=>92686679);
        $res = $commentObj->getCommentWhere($whereConds);

        $arrConds = array('post_at >='=>92686679,'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('s_post_at'=>92686679);
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);

        $arrConds = array('post_at <='=>92686679,'stat ='=>1);
        $commentObj = new Service_Data_Source_HouseRealComment();
        $whereConds = array('e_post_at'=>92686679);
        $res = $commentObj->getCommentWhere($whereConds);
        $this->assertEquals($arrConds,$res);
    }
    public function testGetCommentInfoByWhere(){
    	$whereConds = array();
    	$obj = new Service_Data_Source_HouseRealComment();
    	$res = $obj->getCommentInfoByWhere($whereConds);
    	$returnData['data'] = array();
    	$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$this->assertEquals($returnData,$res);
    	
    	$arrConds = array('comment_id ='=>92686679,'stat ='=>1);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("select"));
    	$obj->expects($this->any())
    	->method('select')
    	->with($this->arrFields,$arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$whereConds = array('comment_id'=>92686679);
    	$res = $commentObj->getCommentInfoByWhere($whereConds,$this->arrFields);
    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    	
    	$arrConds = array('comment_id ='=>92686679,'stat ='=>1);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("select","getLastSQL"));
    	$obj->expects($this->any())
    	->method('select')
    	->with($this->arrFields,$arrConds)
    	->will($this->returnValue(false));
    	
    	$obj->expects($this->any())
    	->method('getLastSQL')
    	->will($this->returnValue(false));
    	
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$whereConds = array('comment_id'=>92686679);
    	$res = $commentObj->getCommentInfoByWhere($whereConds,$this->arrFields);
    	$this->data['data'] = array();
    	$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
        $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	$this->assertEquals($this->data,$res);
    }
    public function testInsertHouseComment(){
    	$whereConds = '';
    	$obj = new Service_Data_Source_HouseRealComment();
    	$res = $obj->insertHouseComment($whereConds);
    	$returnData['data'] = array();
    	$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$this->assertEquals($returnData,$res);
    	
    	$whereConds = array('puid'=>92686679,'stat'=>1);
    	$num = 10;
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("insert"));
    	$obj->expects($this->any())
    	->method('insert')
    	->with($whereConds)
    	->will($this->returnValue($num));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$res = $commentObj->insertHouseComment($whereConds);
    	$this->data['data'] = $num;
    	$this->assertEquals($this->data,$res);
    	
    	$whereConds = array('puid'=>92686679,'stat'=>1);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("insert","getLastSQL"));
    	$obj->expects($this->any())
    	->method('insert')
    	->with($whereConds)
    	->will($this->returnValue(false));
    	
    	$obj->expects($this->any())
    	->method('getLastSQL')
    	->will($this->returnValue(false));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$res = $commentObj->insertHouseComment($whereConds);
    	$this->data['data'] = array();
    	$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
    	$this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	$this->assertEquals($this->data,$res);
    }
    public function testUpdateHouseCommentByCommentId(){
    	$comment_id = 123456;
    	$whereConds = '';
    	$arrConds = array(
    			'comment_id =' => $comment_id,
    	);
    	$obj = new Service_Data_Source_HouseRealComment();
    	$res = $obj->updateHouseCommentByCommentId($comment_id,$whereConds);
    	$returnData['data'] = array();
    	$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$this->assertEquals($returnData,$res);

    	$whereConds = array('puid'=>92686679,'stat'=>1);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("update"));
    	$obj->expects($this->any())
    	->method('update')
    	->with($whereConds, $arrConds)
    	->will($this->returnValue(true));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$res = $commentObj->updateHouseCommentByCommentId($comment_id,$whereConds);
    	$this->data['data'] = true;
    	$this->assertEquals($this->data,$res);
    }
    public function testDelHouseCommentByCommentId(){
    	$comment_id = 123456;
    	$commentObj = new Service_Data_Source_HouseRealComment();
    	$res = $commentObj->delHouseCommentByCommentId($comment_id);
    	$returnData['data'] = array();
    	$returnData['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$returnData['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$this->assertEquals($returnData,$res);
    	
    	$arrConds = array(
    			'comment_id =' => $comment_id,
    	);
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("deleteById"));
    	$obj->expects($this->any())
    	->method('deleteById')
    	->with($arrConds)
    	->will($this->returnValue(1));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	 
    	$commentObj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentInfoByWhere"),array(),'',true);
    	$commentObj->expects($this->any())
    	->method("getCommentInfoByWhere")
    	->with(array('comment_id'=>$comment_id))
    	->will($this->returnValue($this->data));
    	$res = $commentObj->delHouseCommentByCommentId(array($comment_id));
    	$this->data['data'] = 1;
    	$this->assertEquals($this->data,$res);
    	
    	$obj = $this->genObjectMock("Dao_Housepremier_HouseRealComment", array("deleteById"));
    	$obj->expects($this->any())
    	->method('deleteById')
    	->with($arrConds)
    	->will($this->returnValue(false));
    	Gj_LayerProxy::registerProxy("Dao_Housepremier_HouseRealComment", $obj);
    	
    	$commentObj = $this->genObjectMock("Service_Data_Source_HouseRealComment",array("getCommentInfoByWhere"),array(),'',true);
    	$commentObj->expects($this->any())
    	->method("getCommentInfoByWhere")
    	->with(array('comment_id'=>$comment_id))
    	->will($this->returnValue($this->data));
    	$res = $commentObj->delHouseCommentByCommentId(array($comment_id));
    	$this->data['data'] = false;
    	$this->data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
        $this->data['errormsg'] =  ErrorConst::E_SQL_FAILED_MSG;
    	$this->assertEquals($this->data,$res);
    }
}