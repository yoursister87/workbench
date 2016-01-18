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
 * @codeCoverageIgnore
 * 此文件保留不用
 * 时间mock工具请调用gj/util/TimeMock.class.php文件
 */
class TimeMock
{
    private $tm = "";
    public function getTime(){
        if ($this->tm == "")
            return time();
        else
            return $this->tm;
    }
    public function setTime($tm){
        $this->tm = $tm;
    }
}
