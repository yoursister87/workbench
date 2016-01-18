<?php
class XiaoquHuxingMock extends Dao_Xiaoqu_XiaoquHuxing
{
    public function setDbHandler($obj){
        $this->dbHandler = $obj; 
    }
}

class XiaoquHuxingTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    /**
     * @expectedException Exception
     */
    public function testExceptionHuxingPic(){
        $obj = new Dao_Xiaoqu_XiaoquHuxing();
        $obj->getXiaoquHuxingPicture(0, 0, 0);
    }
    /**
     * @expectedException Exception
     */
    public function testExceptionHuxingPicTotal(){
        $obj = new Dao_Xiaoqu_XiaoquHuxing();
        $obj->getXiaoquHuxingPictureTotalCount(0, 0, 0);
    }

    public function dataProviderHuxingPic(){
        //xiaoquId, huxingShi, limit
        return array(
            array(100, 0, 0),
            array(100, 0, 10),
            array(100, 2, 0),
            array(100, 2, 10),
        );
    }
    /**
     * @dataProvider dataProviderHuxingPic
     */
    public function testHuxingPic($xiaoquId, $huxingShi, $limit){
        $expectValue = array(0=>array('id'=>1, 'title'=>'str'));
        
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));

        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquHuxingMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getXiaoquHuxingPicture($xiaoquId, $huxingShi, $limit);

        $this->assertEquals($expectValue, $res);
    }

    public function dataProviderHuxingPicTotal(){
        //xiaoquId, huxingShi
        return array(
            array(100, 0),
            array(100, 2),
        );
    }
    /**
     * @dataProvider dataProviderHuxingPicTotal
     */
    public function testHuxingPicTotal($xiaoquId, $huxingShi){
        $expectValue = array(0=>array('total'=>100));
        
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($expectValue));
        
        $obj = new XiaoquHuxingMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getXiaoquHuxingPictureTotalCount($xiaoquId, $huxingShi);

        $this->assertEquals($expectValue[0]['total'], $res);
    }

}
