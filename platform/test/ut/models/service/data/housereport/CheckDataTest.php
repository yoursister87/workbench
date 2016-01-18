<?php
class	CheckDataTest extends Testcase_PTest{
	protected function setUp(){
		Gj_LayerProxy::$is_ut = true;
	}
	public function testsetPage(){
		$arrInput = array(
			'currentPage'	=> 1,
			'pageSize'		=> 50	
		);
		 $obj =  new Service_Data_HouseReport_CheckData();
		 $ret = $obj->setPage($arrInput);
		 $data = array(
		 	 'currentPage'   => 1,   
			'pageSize'      => 50    
		 );
		  $this->assertEquals($data,$ret);  
	}
	/**   
	 *@expectedException Exception
	 */
	public function testsetPageException(){
		$arrInput = array(
            'currentPage1'   => 1,
            'pageSize1'      => 50
        );
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setPage($arrInput);			
	}
	public function testsetHouseType(){
		$arrInput = array(
			'data'	=> array(1,2,3)
		);	
		$data = array(
			 'data'  => array(1,2,3)   
		);
		 $obj =  new Service_Data_HouseReport_CheckData();
		 $ret = $obj->setHouseType($arrInput);
		 $this->assertEquals($data,$ret);
	}
	 /**   
     *@expectedException Exception
     */
	 public function testsetHouseTypeException(){
         $arrInput = array();
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setHouseType($arrInput);
    }

	 public function testsetCountType(){
        $arrInput = array(
            'data'  => array(1,2,3)
        );  
        $data = array(
             'data'  => array(1,2,3)   
        );  
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setCountType($arrInput);
         $this->assertEquals($data,$ret);
    }
     /**   
     *@expectedException Exception
     */
     public function testsetCountTypeException(){
         $arrInput = array();
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setCountType($arrInput);
    }

	public function testsetFields(){
        $arrInput = array(
            'data'  => array(1,2,3)
        );
        $data = array(
             'data'  => array(1,2,3)
        );
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setFields($arrInput);
         $this->assertEquals($data,$ret);
    }
     /**   
     *@expectedException Exception
     */
     public function testsetFieldsException(){
         $arrInput = array();
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setFields($arrInput);
    }
	public function testsetOrder(){
		$arrInput = array(
			'orderField'	=> '123',
			'order'			=> '234'
		);	
		$data = array(
			'orderField'    => '123',
            'order'         => '234'	
		);
		$obj =  new Service_Data_HouseReport_CheckData();
		$ret = $obj->setOrder($arrInput);
		$this->assertEquals($data,$ret);
	}
	/**   
     *@expectedException Exception
     */
	 public function testsetOrderException(){
         $arrInput = array();
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setOrder($arrInput);
    }

    public function testsetProduct(){
        $arrInput = array(
            'data'  => array(1,2,3)
        );
        $data = array(
             'data'  => array(1,2,3)
        ); 
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setProduct($arrInput);
         $this->assertEquals($data,$ret);
    }
     /**   
     *@expectedException Exception
     */
     public function testsetProductException(){
         $arrInput = array();
         $obj =  new Service_Data_HouseReport_CheckData();
         $ret = $obj->setProduct($arrInput);
    }

}
