<?php
class XiaoquPhotoMock extends Dao_Xiaoqu_XiaoquPhoto
{
    public function setDbHandler($obj){
        $this->dbHandler = $obj;
    }
}

class XiaoquPhotoTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    /**
     * @expectedException Exception
     */
    public function testGetXiaoquOutdoorPictureTotalCountException(){
        $obj = new Dao_Xiaoqu_XiaoquPhoto();
        $obj->getXiaoquOutdoorPictureTotalCount(0);
    }
    /**
     * @expectedException Exception
     */
    public function testGetXiaoquOutdoorPictureException(){
        $obj = new Dao_Xiaoqu_XiaoquPhoto();
        $obj->getXiaoquOutdoorPicture(0);
    }
    
    public function getXiaoquOutdoorPictureDataProvider(){
        //xiaoquId,limit,type
        return array(
            array(100, 0, 0),
            array(100, 0, 1),
            array(100, 10, 0),
            array(100, 10, 1),
        ); 
    }
    /**
     * @dataProvider getXiaoquOutdoorPictureDataProvider
     */
    public function testGetXiaoquOutdoorPicture($xiaoquId, $limit, $type){
        $expectValue = array(0=> array('id'=>1, 'type'=>1, 'title'=>'str', 'image'=>'url', 'thumb_image'=>'url'));

        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($expectValue));

        $obj = new XiaoquPhotoMock();
        $obj->setDbHandler($mockObj);
        
        $res = $obj->getXiaoquOutdoorPicture($xiaoquId, $limit, $type);
        $this->assertEquals($expectValue, $res);
    }
    
    public function getXiaoquOutdoorPictureTotalCountDataProvider(){
        return array(
            array(100, 0),
            array(100, 1),
        );
    }
    /**
     * @dataProvider getXiaoquOutdoorPictureTotalCountDataProvider
     */
    public function testGetXiaoquOutdoorPictureTotalCount($xiaoquId, $type){
        
        $expectValue[0]['total'] = 100;

        $mockObj = $this->genObjectMock('Gj_Db_DbFactory', array('select'));
        $mockObj->expects($this->at(0))
                ->method('select')
                ->will($this->returnValue($expectValue));
        
        $obj = new XiaoquPhotoMock();
        $obj->setDbHandler($mockObj);
        $res = $obj->getXiaoquOutdoorPictureTotalCount($xiaoquId, $type);
        
        $this->assertEquals($expectValue[0]['total'], $res);
    }
}
