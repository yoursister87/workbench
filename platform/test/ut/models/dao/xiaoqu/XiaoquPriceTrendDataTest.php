<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   $
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class XiaoquPriceTrendDataMock extends Dao_Xiaoqu_XiaoquPriceTrendData
{
    public function setDbHandler($obj){
        $this->dbHandler = $obj;
    }
}
 
class XiaoquPriceTrendDataTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquOutdoorPictureException(){
        $obj = new Dao_Xiaoqu_XiaoquPriceTrendData();
        $obj->getXiaoquTrendInfo(0);
    }
    /* {{{providergetXiaoquTrendInfo*/
    /**  
     * @returns   
     */
    public function providergetXiaoquTrendInfo(){
        return array(
            array('11111',array(),array()),
        );    
    }//}}}
    /* {{{ testgetXiaoquTrendInfo*/
    /**  
     * @brief 
     * @returns   
     * @dataProvider providergetXiaoquTrendInfo
     */
    public function testgetXiaoquTrendInfo($xiaoquId,$data,$res){
        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array("select"));
        $mockObj->expects($this->at(0))
            ->method('select')
            ->will($this->returnValue($data));
        $obj = new XiaoquPriceTrendDataMock();
        $obj->setDbHandler($mockObj);
        $ret = $obj->getXiaoquTrendInfo($xiaoquId);
        $this->assertEquals($res, $ret);
    }//}}} 
}
