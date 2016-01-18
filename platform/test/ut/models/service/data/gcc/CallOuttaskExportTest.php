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
class CallOuttaskExport extends Testcase_PTest
{
    protected $data;

    protected function setUp()
    {
        //注册对象用于单元测试
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }
    public function testRemoteApi(){
        $objUtil = $this->genEasyObjectMock("Gj_Util_Curl",array("post"),array("post"	=>  '{"Ret":1,"Message":111}'));
        Gj_Util_Curl::setInstance($objUtil);
        $obj = new Service_Data_Gcc_CallOuttaskExport();
        $jsonStr = '{"calloutid":"45","result":[{"customername":"李瑶","phonenumber":"13488892391","items":[{"key":"测试k1","value":"测试v1","ishide":"1"},{"key":"测试k2","value":"测试v2","ishide":"1"},{"key":"测试k3","value":"测试v3","ishide":"1"}]},{"customername":"恒哥","phonenumber":"13855556666","items":[{"key":"col11","value":"value11","ishide":"1"},{"key":"col21","value":"value21","ishide":"1"},{"key":"col31","value":"value31","ishide":"1"}]}]}';
        $jsonStr = 'aaaaaa';
        $ret = $obj->remoteApi($jsonStr);
        $data = array(
            'data'		=> 111,
            'errorno'	=> 0,
            'errormsg'	=> 111
        );
        $this->assertEquals($data,$ret);
    }
    public function testCheckSpamKeyword(){
        $keyword = '测试评论标题';
        $objSta = $this->genStaticMock('SpamDefenceNamespace', array('checkSpamKeyword'));
        $objSta->expects($this->any())
            ->method('checkSpamKeyword')
            ->with($keyword, 'Content', 'HousePort')
            ->will($this->returnValue(true));
        $obj = new Service_Data_Gcc_CallOuttaskExport();
        $res = $obj->checkSpamKeyword($keyword);
        $this->assertEquals($this->data,$res);

        $objSta = $this->genStaticMock('SpamDefenceNamespace', array('checkSpamKeyword'));
        $objSta->expects($this->any())
            ->method('checkSpamKeyword')
            ->with($keyword, 'Content', 'HousePort')
            ->will($this->returnValue(false));
        $obj = new Service_Data_Gcc_CallOuttaskExport();
        $res = $obj->checkSpamKeyword($keyword);
        $this->data = ErrorCode::returnData(2123);
        $this->assertEquals($this->data,$res);
    }
}
