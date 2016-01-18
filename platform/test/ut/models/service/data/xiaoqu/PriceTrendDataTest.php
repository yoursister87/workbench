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

class PriceTrendDataTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_Layerproxy::$is_ut = true;
    }
    /* {{{providergetXiaoquRealImagePath*/
    /**  
     * @brief 
     *
     * @returns   
     */
    public function providergetXiaoquRealImagePath(){
        return array(
            array(
                '11111',
                array(
                    '0'=>array(
                        'url'=>'http://image.ganjistatic1.com/gjfs07/M0A/46/6D/wKhzVlNYiGiwqVbYAAAx-nCP,w098_600-450_9-0.jpeg',
                    ),
                ),
                'http://image.ganjistatic1.com/gjfs07/M0A/46/6D/wKhzVlNYiGiwqVbYAAAx-nCP,w098_600-450_9-0.jpeg',
            ),
            array(
                '',
                array(),
                '',
            ),
        );    
    }//}}}
    /* {{{ testgetXiaoquRealImagePath*/
    /**  
     * @brief 
     * @returns   
     * @dataProvider providergetXiaoquRealImagePath
     */
    public function testgetXiaoquRealImagePath($xiaoquId,$data,$res){
        $modelObj = $this->genObjectMock("Dao_Xiaoqu_XiaoquPriceTrendData",array("getXiaoquTrendInfo"));
        $modelObj->expects($this->any())
                 ->method("getXiaoquTrendInfo")
                 ->willReturn($data);
        Gj_LayerProxy::registerProxy("Dao_Xiaoqu_XiaoquPriceTrendData",$modelObj);
        
        $obj = new Service_Data_Xiaoqu_PriceTrendData();
        $ret = $obj->getXiaoquRealImagePath($xiaoquId);
        $this->assertEquals($res, $ret);
    }//}}}
}
