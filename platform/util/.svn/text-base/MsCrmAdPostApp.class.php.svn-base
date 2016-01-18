<?php
/**
 * Created by PhpStorm.
 * User: lihongyun1
 * Date: 14-9-12
 * Time: 下午3:02
 */

class Util_MsCrmAdPostApp{
    protected $classMap ;
    protected $obj;

    public function __construct(){
        if(!class_exists('MsCrmAdPostApp')){
            $this->classMap = array('MsCrmAdPostApp' =>  MSAPI."/core/app/post/MsCrmAdPostApp.class.php");
            Gj\Gj_Autoloader::addClassMap($this->classMap);
        }
        $this->obj = new MsCrmAdPostApp();
    }

    public function getObj(){
        return $this->obj;
    }
}
