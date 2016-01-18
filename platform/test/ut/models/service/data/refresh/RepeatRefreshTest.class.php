<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   fuyongjie <fuyongjie@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */

class RepeatRefreshTest extends Testcase_PTest
{
   
    protected function setUp(){
         Gj_Layerproxy::$is_ut = true;
    }

    public function  providerSetRepeatRefresh(){
        return array(
            array(1, 'insert'),
            array(0, 'update'),
            //array(FALSE, 'error'),
            
        );
    }


   /*{{{ testSetRepeatRefresh */
   /**
    * @dataProvider providerSetRepeatRefresh
    */ 
    public function testSetRepeatRefresh($ret, $expect){
       $repeatInfo = array(
             'house_id' => 1,
             'type'=> 1,
             'puid' => -1,
             'account_id'=> 1,
             'is_repeat' => 1,
        ); 

        $mockClass = array(
                         'Dao_Housepremier_HouseSourceList'=> array(
                            'select' => array(
                               'return' =>  array(0=>array('puid'=>1)),
                            )),
                         'Dao_Housepremier_RepeatRefresh' => array(
                            'setRepeat' =>array(
                                    'return' => $ret, 
                                ),    
                            ),
                       );
        $mockObj = $this->genAllObjectMock($mockClass);
        Gj_LayerProxy::registerProxy('Dao_Housepremier_HouseSourceList', $mockObj['Dao_Housepremier_HouseSourceList']);
        Gj_LayerProxy::registerProxy('Dao_Housepremier_RepeatRefresh', $mockObj['Dao_Housepremier_RepeatRefresh']);
        $obj = new Service_Data_Refresh_RepeatRefresh();
        $return = $obj->setRepeatRefresh($repeatInfo);
        $this->assertContains($expect, $return['data']); 
    }
/*}}}*/

}
