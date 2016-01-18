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
class UserPostList extends Testcase_PTest
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
    }
    public function testGetHouseListByWhere(){
        $whereConds = array(
            'account_id'=>100295,
            's_post_at'=>strtotime('today'),
            'e_post_at'=>time(),
        );
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $obj = $this->genObjectMock("Service_Data_Source_UserPostList",array("getWhere"));
        $obj->expects($this->any())
            ->method("getWhere")
            ->with($whereConds)
            ->will($this->returnValue($data));
        $res = $obj->getHouseListByWhere($whereConds);
        $this->assertEquals($data,$res);

        $arrConds = array(
            'account_id ='=>100295,
            'daynum >=' =>$whereConds['s_post_at'],
            'daynum <=' =>$whereConds['e_post_at']
        );
        $arrFields = array("id","puid","house_id","type","daynum","account_id");
        $returnData = array(array("id"=>1,"puid"=>456548456,"house_id"=>1563786345,"type"=>5,"daynum"=>1419862172,"account_id"=>100295));
        $obj1 = $this->genObjectMock("Dao_Housepremier_UserPostList",array("selectByPage"));
        $obj1->expects($this->any())
            ->method("selectByPage")
            ->with($arrFields, $arrConds, 1, 30, array())
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_UserPostList", $obj1);

        $obj = $this->genObjectMock("Service_Data_Source_UserPostList",array("getWhere"),array(),'',true);
        $obj->expects($this->any())
            ->method("getWhere")
            ->with($whereConds)
            ->will($this->returnValue($arrConds));
        $res = $obj->getHouseListByWhere($whereConds);
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>$returnData
        );
        $this->assertEquals($data,$res);
    }
    public function testGetHouseCountByWhere(){
        $whereConds = array(
            'account_id'=>100295,
            's_post_at'=>strtotime('today'),
            'e_post_at'=>time(),
        );
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $obj = $this->genObjectMock("Service_Data_Source_UserPostList",array("getWhere"));
        $obj->expects($this->any())
            ->method("getWhere")
            ->with($whereConds)
            ->will($this->returnValue($data));
        $res = $obj->getHouseCountByWhere($whereConds);
        $this->assertEquals($data,$res);

        $arrConds = array(
            'account_id ='=>100295,
            'daynum >=' =>$whereConds['s_post_at'],
            'daynum <=' =>$whereConds['e_post_at']
        );
        $returnData = 10;
        $obj1 = $this->genObjectMock("Dao_Housepremier_UserPostList",array("selectByCount"));
        $obj1->expects($this->any())
            ->method("selectByCount")
            ->with($arrConds)
            ->will($this->returnValue($returnData));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_UserPostList", $obj1);

        $obj = $this->genObjectMock("Service_Data_Source_UserPostList",array("getWhere"),array(),'',true);
        $obj->expects($this->any())
            ->method("getWhere")
            ->with($whereConds)
            ->will($this->returnValue($arrConds));
        $res = $obj->getHouseCountByWhere($whereConds);
        $data = array(
            'errorno'  => ErrorConst::SUCCESS_CODE,
            'errormsg' => ErrorConst::SUCCESS_MSG,
            'data'=>$returnData
        );
        $this->assertEquals($data,$res);
    }
    public function testGetWhere(){
        $whereConds = array(
            'account_id'=>100295,
            's_post_at'=>strtotime('today'),
            'e_post_at'=>time(),
        );
        $obj = new Service_Data_Source_UserPostList();
        $res = $obj->getWhere($whereConds);
        $arrConds = array(
            'account_id ='=>100295,
            'daynum >=' =>$whereConds['s_post_at'],
            'daynum <=' =>$whereConds['e_post_at']
        );
        $this->assertEquals($arrConds,$res);

        $whereConds = array();
        $obj = new Service_Data_Source_UserPostList();
        $res = $obj->getWhere($whereConds);
        $data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
        $data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
        $this->assertEquals($data,$res);
    }

    public function testInsertHouseRecord(){
        $arrFields = array('puid','house_id', 'type','account_id');
        $obj = $this->genObjectMock('Dao_Housepremier_UserPostList', array('insertHouseRecord'));
        $obj->expects($this->any())
            ->method('insertHouseRecord')
            ->with($arrFields)
            ->will($this->returnValue(true));
        Gj_LayerProxy::registerProxy("Dao_Housepremier_UserPostList", $obj);
        $obj1 = new Service_Data_Source_UserPostList();
        $res = $obj1->insertHouseRecord($arrFields);
        $this->assertEquals($this->data,$res);

        $arrFields = array();
        $obj = $this->genObjectMock('Dao_Housepremier_UserPostList', array('insertHouseRecord', 'getLastSQL'));
        $obj->expects($this->any())
            ->method('insertHouseRecord')
            ->with($arrFields)
            ->will($this->returnValue(false));
        $obj->expects($this->any())
            ->method('getLastSQL');
        Gj_LayerProxy::registerProxy("Dao_Housepremier_UserPostList", $obj);
        $obj1 = new Service_Data_Source_UserPostList();
        $res = $obj1->insertHouseRecord($arrFields);
        $data['data'] = array();
        $data['errorno'] = ErrorConst::E_SQL_FAILED_CODE;
        $data['errormsg'] = ErrorConst::E_SQL_FAILED_MSG;
        $this->assertEquals($data,$res);
    }
}
