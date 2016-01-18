 <?php
 /**
  * @package
  * @subpackage
  * @author               $Author:   zhuyaohui$
  * @file                 $HeadURL$
  * @version              $Rev$
  * @lastChangeBy         $LastChangedBy$
  * @lastmodified         $LastChangedDate$
  * @copyright            Copyright (c) 2010, www.ganji.com
  */

class Sticky extends Testcase_PTest{
    protected $data;
    
    protected function setUp()
    {
        Gj_LayerProxy::$is_ut = true;
        $this->data['data'] = array();
        $this->data['errorno'] = ErrorConst::SUCCESS_CODE;
        $this->data['errormsg'] = ErrorConst::SUCCESS_MSG;
    }

    public function testGetMspuidByTgpuid(){
        $arrConds = array('tg_puid =' => 1122334455);
        $arrFields = array('ms_puid');
        $obj = $this->genObjectMock('Dao_Housepremier_Sticky', array('select'));
        $obj->expects($this->any())
            ->method('select')
            ->with($arrFields, $arrConds)
            ->will($this->returnValue(5544332211));
        Gj_LayerProxy::registerProxy('Dao_Housepremier_Sticky', $obj);
        $obj1 = new Service_Data_Source_Sticky();
        $res = $obj1->getMspuidByTgpuid(1122334455);
        $this->data['data'] = 5544332211;
        $this->assertEquals($this->data, $res);
    }
}
