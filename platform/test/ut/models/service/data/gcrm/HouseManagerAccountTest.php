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
class HouseManagerAccount extends Testcase_PTest{
	protected $data;
	protected $result;
	protected $arrFields;
    protected function setUp(){
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
    	$this->data['errorno'] = ErrorConst::SUCCESS_CODE;
    	$this->data['errormsg'] =  ErrorConst::SUCCESS_MSG;
    	$this->result = array(array('id'=>123,'company_id'=>45888));
    	$this->arrFields = array("id","pid","company_id","customer_id","level","create_time","status","account","password","title","name","phone");
    }
    public function testGetOrgInfoListByPid(){
    	$arrConds = array('company_id ='=>835,'status =' =>1);
    	$orderArr = array('DESC'=>'create_time');
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("selectByPage")); 
    	$obj->expects($this->any())
	    	->method('selectByPage')
	    	->with($this->arrFields, $arrConds, 1, 30, $orderArr)
	    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$whereConds = array('company_id'=>835);
    	$res = $orgObj->getOrgInfoListByPid($whereConds, $this->arrFields, 1, 30, $orderArr);

    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    	//传递参数
    	$arrFields = array('*');
    	$arrConds = array('company_id ='=>835,'status =' =>1, 'pid =' =>1, 'level =' =>1,);
    	$orderArr = array('DESC'=>'create_time');
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("selectByPage"));
    	$obj->expects($this->any())
    	->method('selectByPage')
    	->with($arrFields, $arrConds, 1, 30, $orderArr)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$whereConds = array('company_id'=>835,'pid'=>1,'level'=>1);
    	$res = $orgObj->getOrgInfoListByPid($whereConds, $arrFields, 1, 30, $orderArr);
    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    }
    public function testGetOrgCountByPid(){
    	$arrConds = array('company_id ='=>835,'status =' =>1);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("selectByCount"));
    	$obj->expects($this->any())
    	->method('selectByCount')
    	->with($arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$whereConds = array('company_id'=>835);
    	$res = $orgObj->getOrgCountByPid($whereConds);
    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    	//传递参数
    	$arrConds = array('company_id ='=>835,'status =' =>1, 'pid =' =>1, 'level =' =>1,);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("selectByCount"));
    	$obj->expects($this->any())
    	->method('selectByCount')
    	->with($arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$whereConds = array('company_id'=>835,'pid'=>1,'level'=>1);
    	$res = $orgObj->getOrgCountByPid($whereConds);
    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    }
    public function testGetOrgInfoByIdOrAccount(){
    	//根据ID获取信息

		$arrConds = array('id ='=>30100,'status =' =>1);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("select"));
    	$obj->expects($this->any())
    	->method('select')
    	->with($this->arrFields, $arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->getOrgInfoByIdOrAccount(array('id'=>30100), $this->arrFields);
    	$this->data['data'] = $this->result[0];
    	$this->assertEquals($this->data,$res);
    	//根据account获取信息
    	$arrConds = array('account ='=>'bjzlm@ganji.com','status =' =>1);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("select"));
    	$obj->expects($this->any())
    	->method('select')
    	->with($this->arrFields, $arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->getOrgInfoByIdOrAccount(array('account'=>'bjzlm@ganji.com'), $this->arrFields);
    	$this->data['data'] = $this->result[0];
    	$this->assertEquals($this->data,$res);

    }
    public function testAddOrg(){
    	//不传任何数据
    	$arrConds = '';
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("insert"));
    	$obj->expects($this->any())
    	->method('insert')
    	->with($arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->addOrg($arrConds);
    	$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$data['data'] = array();
    	$this->assertEquals($data,$res);
    	
    	$arrConds = array('account ='=>'bjzlm@ganji.com');
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("insert"));
    	$obj->expects($this->any())
    	->method('insert')
    	->with($arrConds)
    	->will($this->returnValue($this->result));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->addOrg($arrConds);
    	$this->data['data'] = $this->result;
    	$this->assertEquals($this->data,$res);
    }
    public function testModifyPassword(){
    	//旧密码错误
    	$this->data['data'] = array('id'=>835,'password'=>md5('112233'));
    	$id = '835';
    	$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoById"),array(),'',true);
    	$obj->expects($this->any())
    	->method('getOrgInfoById')
    	->with($id)
    	->will($this->returnValue($this->data));
    	$res = $obj->modifyPassword($id, '666666', '888888');
    	$data['errorno'] = ErrorConst::E_PARAM_INVALID_CODE;
    	$data['errormsg'] =  ErrorConst::E_PARAM_INVALID_MSG;
    	$data['data'] = array();
    	$this->assertEquals($data,$res);
    	
    	//旧密码正确
    	$arrConds = array('id =' => $id);
    	$new_pwd = "666666";
    	$arrRows = array('password' =>md5($new_pwd),'passwd' => $new_pwd);
    	$objUpdata = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("update"),array(),'',true);
    	$objUpdata->expects($this->any())
    	->method('update')
    	->with($arrRows, $arrConds)
    	->will($this->returnValue(1));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $objUpdata);
    	
    	$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount", array("getOrgInfoById"),array(),'',true);
    	$obj->expects($this->any())
    	->method('getOrgInfoById')
    	->with($id)
    	->will($this->returnValue($this->data));

    	$res = $obj->modifyPassword($id, '666666', '112233');
    	$this->data['data'] = 1;
    	$this->assertEquals($this->data,$res);
    	//不传旧密码
    	$id = 835;
    	$new_pwd = '888888';
    	$arrConds = array('id =' => $id);
    	$arrRows = array('password' =>md5($new_pwd),'passwd' => $new_pwd);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("update"));
    	$obj->expects($this->any())
    	->method('update')
    	->with($arrRows, $arrConds)
    	->will($this->returnValue(1));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->modifyPassword($id, $new_pwd);
    	$this->data['data'] = 1;
    	$this->assertEquals($this->data,$res);
    }
    public function testDeleteOrgById(){
    	$id = 83500000;
    	$arrConds = array('id ='=> $id);
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("deleteById"));
    	$obj->expects($this->any())
    	->method('deleteById')
    	->with($arrConds)
    	->will($this->returnValue(1));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	
    	$orgObj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"),array(),'',true);
    	$orgObj->expects($this->any())
    	->method("getOrgInfoByIdOrAccount")
    	->will($this->returnValue($this->data));
    	//$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->deleteOrgById(array($id));
    	$this->data['data'] = 1;
    	$this->assertEquals($this->data,$res);
    }
    public function testUpdateOrgById(){
    	$id = 835;
    	$arrConds = array('id =' => $id);
    	$arrChangeRow = array('account'=>'bjzlm@ganji.com');
    	$obj = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount", array("update"));
    	$obj->expects($this->any())
    	->method('update')
    	->with($arrChangeRow,$arrConds)
    	->will($this->returnValue(1));
    	Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj);
    	$orgObj = new Service_Data_Gcrm_HouseManagerAccount();
    	$res = $orgObj->updateOrgById($id, $arrChangeRow);
    	$this->data['data'] = 1;
    	$this->assertEquals($this->data,$res);
	}
	public function testgetOrgWhere(){
		$whereConds = array(
			'company_id'	=> 835,
			'pid'			=> 1,
			'level'			=> 1,
			'title'			=>'赶集'
		);
		$obj = new Service_Data_Gcrm_HouseManagerAccount();
		$ret = $obj->getOrgWhere($whereConds);
		$data = array(
			'company_id ='    => 835,
            'pid ='           => 1,
            'level ='         => 1,	
			'status =' => 1,
				0			  => "title like '%赶集%'"
		);
		$this->assertEquals($data,$ret);
	}
	public function testgetOrgCountListByPid(){
		$whereConds = array(
			'company_id'=>'835',
			'pid'	=> 123	
		);	
		$res = array('pid =' => 11);
		$res1 = true;
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgWhere"));
		$obj->expects($this->any())
			->method("getOrgWhere")
			->will($this->returnValue($res));
		$obj1 = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount",array("selectByCount"));
	    $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->returnValue($res1));
		Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj1);
		$objOrg = new Service_Data_Gcrm_HouseManagerAccount();
		$ret = $objOrg->getOrgCountByPid($whereConds); 	
		$data = array(
			'data'		=> true,
			'errorno'	=> 0,
			'errormsg'	=> '[数据返回成功]'
		);
		$this->assertEquals($data,$ret);
	}
	public function testgetOrgCountListByPidException(){
		$whereConds = array(
			'company_id'=>'835',
            'pid'   => 123
        );
        $res = array('pid =' => 11);
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgWhere"));
        $obj->expects($this->any())
            ->method("getOrgWhere")
            ->will($this->returnValue($res));
        $obj1 = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount",array("selectByCount"));
        $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->throwException(new Exception(ErrorConst::E_PARAM_INVALID_MSG,ErrorConst::E_PARAM_INVALID_CODE)));
        Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj1);
        $objOrg = new Service_Data_Gcrm_HouseManagerAccount();
        $ret = $objOrg->getOrgCountByPid($whereConds);
        $data = array(
            'data'      => null,
            'errorno'   => 1002,
            'errormsg'  => '[参数不合法]'
        );
        $this->assertEquals($data,$ret);			
	}
	public function testgetTreeByOrgId(){
		$id = "";
		$isfamily = true;
		$nextRes  = array(
			
		);	
		$minLevel = 5;
		$obj = new Service_Data_Gcrm_HouseManagerAccount();
		$ret = $obj->getTreeByOrgId($id,$isfamily, $nextRes,$minLevel);
		$data = array(
			'data'		=>array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);


		$id =133;
        $isfamily = true;
        $nextRes  = array(

        );
        $res = array(
            'errorno'   => 1002,
			'errormsg'	=>'[数据返回错误]'
        );
        $minLevel = 5;
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
		$obj->expects($this->any())
		->method("getOrgInfoByIdOrAccount")
		->will($this->returnValue($res));
        $ret = $obj->getTreeByOrgId($id,$isfamily, $nextRes,$minLevel);
        $data = array(
            'errorno'   => 1002,
            'errormsg'  => '[数据返回错误]'
        );
        $this->assertEquals($data,$ret);	

		$id =133;
        $isfamily = true;
        $nextRes  = array(

        );
        $res = array(
            'errorno'   => 0,
            'errormsg'  =>'[数据返回成功]',
			'data'		=> array()
        );
        $minLevel = 5;
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
        ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue($res));
        $ret = $obj->getTreeByOrgId($id,$isfamily, $nextRes,$minLevel);
        $data = array(
            'errorno'   => 1004,
            'errormsg'  => '[逻辑执行失败]'
        );
        $this->assertEquals($data,$ret);	

		
	    $id =133;
        $isfamily = false;
        $nextRes  = array(
			
        );
        $res = array(
            'errorno'   => 0,
            'errormsg'  =>'[数据返回成功]',
            'data'      => array(
				'pid'	=> 0,
				'level'	=> 2
			)
        );
        $minLevel = 5;
		$res1 =true; 
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
        $obj->expects($this->any())
        ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue($res));
		$ret = $obj->getTreeByOrgId($id,$isfamily, $nextRes,$minLevel);
		$data = array(
			'data'	=> array(
				2 =>array(
					'activeList'	=> array(
							'pid'	=> 0,
							'level'	=> 2	
					)
				)
			)
		);
        $this->assertEquals($data,$ret);	

	    $id =133;
        $isfamily = true;
        $nextRes  = array(
       
        );  
        $res = array(
            'errorno'   => 0,
            'errormsg'  =>'[数据返回成功]',
            'data'      => array(
                'pid'   		=> 0,
                'level' 		=> 2,
				'company_id'	=> 235
            )   
        );  
		$res1 = array(
			'data'	=> array(
				0 => array(
					'id'	=> 133,
				),
			)
		);
        $minLevel = 5;
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","getOrgInfoListByPid"));
        $obj->expects($this->any())
        ->method("getOrgInfoByIdOrAccount")
        ->will($this->returnValue($res));
		$obj->expects($this->any())
        ->method("getOrgInfoListByPid")
        ->will($this->returnValue($res1));
        $ret = $obj->getTreeByOrgId($id,$isfamily, $nextRes,$minLevel);
        $data = array(
            'data'  => array(
                2 =>array(
                    'activeList'    => array(
                            'id'   => 133,
                    )   
                )   
            )   
        );  
        $this->assertEquals($data,$ret);   

	}
	public function testgetChildTreeByOrgId(){
		$orgId = '';	
		$level = 2;
		$params = array(
			'title'	=> '赶集测试公司'	
		);
		$obj = new Service_Data_Gcrm_HouseManagerAccount();
		$ret = $obj->getChildTreeByOrgId($orgId,$level,$params);
		$data = array(
			'data'		=> array(),
			'errorno'	=> 1002,
			'errormsg'	=> '[参数不合法]'
		);
		$this->assertEquals($data,$ret);

		$orgId = 123;    
        $level = 2;
        $params = array(
            'title' => '赶集测试公司'   
        );  
		$res = array(
			'data'	=> array()
		);
		$obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount"));
		$obj->expects($this->any())
			->method("getOrgInfoByIdOrAccount")
			->will($this->returnValue($res));
        $ret = $obj->getChildTreeByOrgId($orgId,$level,$params);
        $data = array(
			'data'	=> array(
				'list'	=> array(),
				'count'	=> 0
			)
        );  
        $this->assertEquals($data,$ret);

		$orgId = 123;
        $level = 2;
        $params = array(
            'title' => '赶集测试公司'
        );
        $res = array(
            'data'  => array(
				'pid'			=> 0,
			)
        );
		$res1 = array(
			'data'	=> 123
		);
		$res2 = array(
			'data'	=> 345 
		);
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","getOrgInfoListByPid","getOrgCountByPid"));
        $obj->expects($this->any())
            ->method("getOrgInfoByIdOrAccount")
            ->will($this->returnValue($res));

	    $obj->expects($this->any())
        ->method("getOrgInfoListByPid")
        ->will($this->returnValue($res1));	

		$obj->expects($this->any())
        ->method("getOrgCountByPid")
        ->will($this->returnValue($res2)); 	
        $ret = $obj->getChildTreeByOrgId($orgId,$level,$params);
        $data = array(
            'data'  => array(
                'list'  => 123,
                'count' => 345
            )
        );
        $this->assertEquals($data,$ret);	


     	$orgId = 123;
        $level = 2;
        $params = array(
            'title' => '赶集测试公司'
        );  
        $res = array(
            'data'  => array(
                'pid'           => 1,
				'level'			=> 2
            )   
        );  
        $res1 = array(
            'data'  => 123 
        );  
        $res2 = array(
            'data'  => 345 
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","getOrgInfoListByPid","getOrgCountByPid"));
        $obj->expects($this->any())
            ->method("getOrgInfoByIdOrAccount")
            ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getOrgInfoListByPid")
        ->will($this->returnValue($res1));  

        $obj->expects($this->any())
        ->method("getOrgCountByPid")
        ->will($this->returnValue($res2));  
        $ret = $obj->getChildTreeByOrgId($orgId,$level,$params);
		$data = array(
            'data'  => array(
                'list'  => array(
					0	=> array(
						'pid'	=> 1,
						'level'	=> 2
					)	
				),
                'count' => 1
            )
        );
        $this->assertEquals($data,$ret);  

        $orgId = 123;
        $level = 2;
        $params = array(
            'title' => '赶集测试公司'
        );
        $res = array(
            'data'  => array(
                'pid'           => 1,
                'level'         => 3
            )   
        );  
        $res1 = array(
            'data'  => 123 
        );  
        $res2 = array(
            'data'  => 345 
        );  
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","getOrgInfoListByPid","getOrgCountByPid"));
        $obj->expects($this->any())
            ->method("getOrgInfoByIdOrAccount")
            ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getOrgInfoListByPid")
        ->will($this->returnValue($res1));  

        $obj->expects($this->any())
        ->method("getOrgCountByPid")
        ->will($this->returnValue($res2));  
        $ret = $obj->getChildTreeByOrgId($orgId,$level,$params);
		$data = array(
            'data'  => null
        );
        $this->assertEquals($data,$ret);
 
        $orgId = 123;
        $level = 3;
        $params = array(
            'title' => '赶集测试公司'
        );
        $res = array(
            'data'  => array(
                'pid'           => 1,
				'level'         => 2,
				'id'			=> 123,
				'company_id'	=> 235
            )
        );
        $res1 = array(
            'data'  => 123
        );
        $res2 = array(
            'data'  => 345
        );
		$res3 = array(
			0	=> array(
				'id'		=> 122,
				'level'		=> 3
			)
		);
		$res4 = 6;
        $obj = $this->genObjectMock("Service_Data_Gcrm_HouseManagerAccount",array("getOrgInfoByIdOrAccount","getOrgInfoListByPid","getOrgCountByPid"));
        $obj->expects($this->any())
            ->method("getOrgInfoByIdOrAccount")
            ->will($this->returnValue($res));

        $obj->expects($this->any())
        ->method("getOrgInfoListByPid")
        ->will($this->returnValue($res1));

        $obj->expects($this->any())
        ->method("getOrgCountByPid")
        ->will($this->returnValue($res2));

		$obj1 = $this->genObjectMock("Dao_Gcrm_HouseManagerAccount",array("selectByPage","selectByCount"));
        $obj1->expects($this->any())
        ->method("selectByPage")
        ->will($this->returnValue($res3));

	    $obj1->expects($this->any())
        ->method("selectByCount")
        ->will($this->returnValue($res4));	
		Gj_LayerProxy::registerProxy("Dao_Gcrm_HouseManagerAccount", $obj1);
        $ret = $obj->getChildTreeByOrgId($orgId,$level,$params);	
		$data = array(
			'data'	=> array(
				'list'	=> array(
					0	=> array(
						'id'	=> 122,
						'level'	=> 3
					)
				),
				'count'	=> 6
			)
		);
		$this->assertEquals($data,$ret);

	}
}
