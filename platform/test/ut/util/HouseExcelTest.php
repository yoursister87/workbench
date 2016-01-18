<?php
/*
 * File Name:HouseExcelTest.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class HouseExcelTest extends TestCase_PTest
{
    protected function setUp(){
        Gj_LayerProxy::$is_ut = true;
    }

    protected $data = array(
    '这是11月的数据'=>array(
            'title'=>array(
                //一般个标题头 需要是 顺序索引
                array(
                    array('name'=>'基础信息','width'=>'3'),array('name'=>'点击信息','width'=>'2')
                ),
            ),
            'data'=>array(
                array("编号","姓名","门店","版块","区域"),
                array(1,2,3,4,5),
                array(6,7,8,9,10),
                array('A','b','c','d','e')
            ),
        ),
    '这是12月的数据'=>array(
            'title'=>array(
                array(
                    array('name'=>'用户刷新量','width'=>'3'),array('name'=>'用户刷新数据','width'=>'2')
                ),
            ),
            'data'=>array(
                array("编号","姓名","门店","版块","区域"),
                array(3,2,3,4,5),
                array(4,7,8,9,10),
                array('ggg','5454g','4f','45','45')
            ),
        )
    );

    public function testconvert2col(){
        $obj = new Util_HouseExcel();
        $ret = $obj->convert2col('abc');
        $this->assertEquals($ret,false);
        
        $obj = new Util_HouseExcel();
        $ret = $obj->convert2col(122);
        $this->assertEquals($ret,'DS');
    }

}
