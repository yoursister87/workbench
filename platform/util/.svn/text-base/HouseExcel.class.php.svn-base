<?php
/*
 * File Name:HouseExcel.class.php
 * Author:zhangliming
 * mail:zhangliming@ganji.com
 * 1.setData 创建数据
 * 2.createExcel 生成xls
 */
class Util_HouseExcel {
    //A-Z数组
    private $a2z = array();
    
    private $objPHPExcel = null;

    private $col2char = null;
    /*{{{__construct()*/   
    public function __construct(){
        //A-Z ascii码范围
        $arr = array(65,90);
        for ($i=$arr[0];$i<=$arr[1];$i++){
            $a2z[] = chr($i);
        }
        $this->a2z = $a2z;
        $obj = Gj_LayerProxy::getProxy('Util_PhpExcel');
        $this->objPHPExcel = $obj->getObj();
    }

    public function __call($name,$args){
        if (Gj_LayerProxy::$is_ut === true) {
            return  call_user_func_array(array($this,$name),$args);
        }
    }
    /*}}}*/
    private function getDownloadFilename($filename){
        $ua = $_SERVER["HTTP_USER_AGENT"];

        $encoded_filename = urlencode($filename);

        //如果是ie下载需要用 urlecnode文件名
        if (preg_match("/MSIE/", $ua)) {
            $filename = $encoded_filename;
        }
        return $filename;
    }
    /*{{{convert2col  数字转换为excel的列索引*/
    private function convert2col($num){
        if (!is_numeric($num)) {
            return false;
        }

        if (isset($this->col2char[$num])) {
            return $this->col2char[$num];
        }
        $numArr = array();
        $tmp = $num;
        do{
            $numArr[] = $tmp % 26;
            $tmp = floor($tmp/26);
            if ($tmp == 0) {
                if (count($numArr)>1){
                    //是从A开始所以需要减一
                    $numArr[count($numArr) - 1] = (end($numArr) -1 <=0)?0:end($numArr) - 1;
                }   
            }   
        } while($tmp);
        
        $ret = '';
        for ($i=count($numArr)-1;$i>=0;$i--){
            $ret .= $this->a2z[$numArr[$i]];
        }
        $this->col2char[$num] = $ret;
        return $ret;
    }
    /*}}}*/
    /*
    * array(
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
     */
    //{{{创建数据 setData
    public function setData($data){
        //设置第一个 sheet
        $sheetIndex = 0;
        foreach ($data as $sheetName=>$sheetData) {
            if ($sheetIndex >=1) {
                //创建sheet
                $this->objPHPExcel->createSheet();
            }
            $this->objPHPExcel->setactivesheetindex($sheetIndex);
            $this->objPHPExcel->getActiveSheet()->setTitle($sheetName); 
            
            //开始行的位置 xls是1行
            $startRow = 1;
            //是否存在标题头  设置标题单元格
            if (!empty($sheetData['title'])) {
                foreach ($sheetData['title'] as $row =>$titleData) {
                    $startTitleIndex = 0;
                    foreach ($titleData as $title) {
                        $colStr = $this->convert2col($startTitleIndex);
                        $rowStr = $startRow;
                        $this->objPHPExcel->getActiveSheet()->setCellValue($colStr.$rowStr,$title['name']);
                        //偏移title的起始位置
                        $startTitleIndex = $startTitleIndex + $title['width']-1;
                        //定位结束位置
                        $endColStr = $this->convert2col($startTitleIndex);
                        //合并单元格
                        $this->objPHPExcel->getActiveSheet()->mergeCells($colStr.$rowStr.":".$endColStr.$rowStr);
                        $startTitleIndex++; 
                    }
                }
                //行位置的偏移
                $startRow++;
            }
            //$sheetData['data'] = array_slice($sheetData['data'],1,20);
            //设置数据 设置数据单元格
            foreach ($sheetData['data'] as $dataList) {
                $this->objPHPExcel->getActiveSheet()->fromArray(
                    $dataList, // 赋值的数组
                    '', // 忽略的值,不会在excel中显示
                    //从A的位置开始赋值
                    "A{$startRow}" // 赋值的起始位置
                );
                /*
                foreach ($dataList as $col=>$value) {
                    $colStr = $this->convert2col($col);
                    $rowStr = $startRow;
                    $this->objPHPExcel->getActiveSheet()->setCellValue($colStr.$rowStr,$value); 
                }*/
                //行位置的偏移
                $startRow++;
            }
            //sheet偏移
            $sheetIndex++;
        }
    }
    //}}}
    public function getPhpExcel(){
        return $this->objPHPExcel;
    }
    /*
    * array(
   * '这是11月的数据'=>array(
    *        'title'=>array(
   *             //一般个标题头 需要是 顺序索引
   *             array(
    *                array('name'=>'基础信息','width'=>'3'),array('name'=>'点击信息','width'=>'2')
   *             ),
   *         ),
   *         'data'=>array(
   *             array("编号","姓名","门店","版块","区域"),
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
   *     )
   * );
     */
    public function createCsv($data,$filename = 'report'){

        $csv = '';
        if (!empty($data['title'])) {
            $titleList = $data['title'];
            foreach ($titleList as $title) {;
                foreach ($title as $item) {
                    $arr = array_fill(0,$item['width']+1,'');
                    $arr[0] = $item['name'];
                    $csv .= implode(',',$arr);
                }
                $csv .= "\r\n";
            }
        }

        if (!empty($data['data'])) {
           $list = $data['data'];
           foreach ($list as $key=>$line) {
           		if($key && $line[1]==='0'){
           			continue;
           		}
                $csv .= implode(',', $line);
                $csv .= "\r\n"; 
           }
           
           $filename = $this->getDownloadFilename($filename);
           header('Content-Type:application/text; charset=GBK');
           $filename = $filename.'.csv';
           header("Content-Disposition: attachment;filename={$filename}");
           ob_start();
           echo $csv;
           $out = ob_get_contents();
           $result = mb_convert_encoding($out, "GBK", "UTF-8");
           ob_clean();
           echo $result;
           exit;
        }

    }

    /*{{{createExcel 下载xls*/
    public function createExcel($filename = 'report.xls'){
        /*
        $dir = dirname(__FILE__);
        $obwrite = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $obwrite->save($dir."/$filename");
        */
        // Redirect output to a client’s web browser (Excel5) 
        header('Content-Type: application/vnd.ms-excel'); 
        header("Content-Type:application/download");
        header('Content-Disposition: attachment;filename="01simple.xls"'); 
        header('Cache-Control: max-age=0'); 
        // If you're serving to IE 9, then the following may be needed 
        header('Cache-Control: max-age=1'); 

        // If you're serving to IE over SSL, then the following may be needed 
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past 
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified 
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1 
        header ('Pragma: public'); // HTTP/1.0 

        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5'); 
        $objWriter->save('php://output'); 
        exit; 
        /*
        //输出游览器
        header("Content-Type:application/force-download");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        //设置文件名称
        header("Content-Disposition: attachment;filename={$filename}");
        header('Cache-Control: max-age=0');
        $objWriter->save("php://output");
         */
    }
    /*}}}*/
}
