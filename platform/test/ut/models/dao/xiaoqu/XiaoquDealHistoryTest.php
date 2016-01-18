<?php
class XiaoquDealHistoryMock extends Dao_Xiaoqu_XiaoquDealHistory
{
    public function setDbHandler($obj){
        $this->dbHandler = $obj;
    }
}
class XiaoquDealHistoryTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    
    /**
     * @expectedException Exception
     */
    public function testExceptionTransactionTotalCount(){
        $obj = new Dao_Xiaoqu_XiaoquDealHistory();
        $obj->getXiaoquDealHistoryTotalCount(0);
    }
    
    public function dataProviderTransaction(){
        return array(
            array(0, array(), 0, 0),
            array(0, array(), 0, 50),
            array(100, array(), 0, 0),
            array(100, FALSE, 0, 50),
        );
    }

    /**
     * @expectedException Exception
     * @dataProvider dataProviderTransaction
     */
    public function testExceptionTransaction($xiaoquId, $fields, $offset, $limit){
        $obj = new Dao_Xiaoqu_XiaoquDealHistory();
        $obj->getXiaoquDealHistoryData($xiaoquId, $fields, $offset, $limit);
    }

    public function testGetXiaoquDealHistoryTotalCount(){
        $xiaoquId = 100;
        $expectValue = array(0=>array('total'=>100));
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquDealHistoryMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getXiaoquDealHistoryTotalCount($xiaoquId);

        $this->assertEquals($expectValue[0]['total'], $res);
    }

    public function dataProviderGetXiaoquDealHistoryData(){
        return array(
            array(100, array(), 0, 50),
            array(100, array(), 50, 50),
            array(100, array('id', 'xiaoqu_id'), 0, 50),
            array(100, array('id', 'xiaoqu_id'), 50, 50),
        );
    }
    
    /**
     * @dataProvider dataProviderGetXiaoquDealHistoryData
     */
    public function testGetXiaoquDealHistoryData($xiaoquId, $fields, $offset, $limit){
        $expectValue = array(0=>array('id'=>1, 'xiaoqu_id'=>1));

        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquDealHistoryMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getXiaoquDealHistoryData($xiaoquId, $fields, $offset, $limit);
        
        $this->assertEquals($expectValue, $res);
    }
    
    public function dataPrioviderDealTotalException(){
        return array(
            array(0, 6),
            array(100, 0),
        );    
    }

    /**
     * @expectedException Exception
     * @dataProvider dataPrioviderDealTotalException
     */
    public function testOne($xiaoquId, $recentMonth){
        $obj = new Dao_Xiaoqu_XiaoquDealHistory();
        $obj->getDealHistoryAvgPriceByMonth($xiaoquId, $recentMonth); 
    }

    /**
     * @expectedException Exception
     * @dataProvider dataPrioviderDealTotalException
     */
    public function testTwo($xiaoquId, $recentMonth){
        $obj = new Dao_Xiaoqu_XiaoquDealHistory();
        $obj->getDealHistoryCountByMonth($xiaoquId, $recentMonth); 
    }
    
    /**
     * @expectedException Exception
     * @dataProvider dataPrioviderDealTotalException
     */
    public function testThree($xiaoquId, $recentMonth){
        $obj = new Dao_Xiaoqu_XiaoquDealHistory();
        $obj->getDealHistoryCountByHuxing($xiaoquId, $recentMonth); 
    }

    /**
     * 测试每个月的成交均价
     */
    public function testGetDealHistoryAvgPriceByMonth(){
        $expectValue = array(0=>array('mon'=>1, 'avg_price'=>9000));
        
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquDealHistoryMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getDealHistoryAvgPriceByMonth(100, 6);
        
        $this->assertEquals($expectValue, $res);
    }


    /**
     * 每个月的成交套数
     */
    public function testGetDealHistoryCountByMonth(){
        $expectValue = array(0=>array('mon'=>1, 'avg_price'=>9000));
        
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquDealHistoryMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getDealHistoryCountByMonth(100, 6);
        
        $this->assertEquals($expectValue, $res);
    }

    /**
     * 某段时间之内每个户型的成交数量
     */
    public function testGetDealHistoryCountByHuxing(){
        $expectValue = array(0=>array('mon'=>1, 'avg_price'=>9000));
        
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->any())
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquDealHistoryMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getDealHistoryCountByHuxing(100, 6);
        
        $this->assertEquals($expectValue, $res);
    }
}
