<?php
/*
 * File Name:HouseReportUtil.class.php
 * Author:lukang
 * mail:lukang@ganji.com
 */
class Util_HouseReportUtil
{
   public  function checkDate($date)
    {
        $arr = explode('-',$date);
        if(is_array($arr) && count($arr) == 3) {
            return true;
        } else {
            return false;
        }
    }

   public  function getTableName($date = null){
       if ($date === null) {
           $date = date('Y-m-d',strtotime('yesterday'));
       }
       $tableNameSuffix = '';
       $dar = explode('-', $date);
       if( isset($dar[0]) && isset($dar[1]) && $dar[0] && $dar[1] )
       {
           $tableNameSuffix = '_' . $dar[0] . '_' . $dar[1];   
       }
       return $tableNameSuffix;
   }

    public function assertIsValidDatePeriod($sdate, $edate)
    {   
        if ( $sdate === null || $edate === null )
        {   
            return true;
        }   
        $sar = explode('-', $sdate);
        $dar = explode('-', $edate);
        if( isset($sar[0]) && isset($dar[0]) && isset($sar[1]) && isset($dar[1])
         && $sar[0] == $dar[0] && $sar[1] == $dar[1] )
        {   
            return true;
        }
        //只能查一个月只能的数据
        return false;
    }   
}
