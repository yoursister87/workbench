<?php
/*
 * File Name:PhpExcel.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Util_PhpExcel
{

    protected $classMap ;
    protected $obj;
    /**
     *@codeCoverageIgnore
     *           
     */
    public function __construct(){
        if(!class_exists('PHPExcel')){
            $this->classMap = array('PHPExcel' =>  CODE_BASE2."/util/phpexcel/PHPExcel.php");
            Gj\Gj_Autoloader::addClassMap($this->classMap);
        }
        $this->obj = new PHPExcel();
    }

    public function getObj(){
        return $this->obj;
    }
}
