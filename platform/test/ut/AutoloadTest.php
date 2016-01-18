<?php
/**
 * @package              
 * @subpackage           
 * @brief                
 * @author               $Author:   yangyu <yangyu@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2014, www.ganji.com
 */
class AutoloadTest extends Testcase_PTest
{
    /* {{{ testAutoloader */
    /**
        * @brief 
        *
        * @returns   
     */
    public function testAutoloader(){
        $a = new Dao_FangDao();
        $this->assertTrue(($a instanceof Dao_FangDao));

        $a = new XiaoquDataService();
        $this->assertTrue(($a instanceof XiaoquDataService));

        $a = new XiaoquXiaoquXiaoquModel();
        $this->assertTrue(($a instanceof XiaoquXiaoquXiaoquModel));
    }//}}}
}
