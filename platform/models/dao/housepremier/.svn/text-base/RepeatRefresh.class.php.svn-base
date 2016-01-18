<?php
 /**
   * 操作推广房源重复预约刷新
   *
   * @package              
   * @subpackage           
   * @author               fuyongjie <fuyongjie@ganji.com>
   * @file                 $HeadURL:  $
   * @version              $Rev:  $
   * @lastChangeBy         $LastChangedBy: fuyongjie $
   * @lastmodified         $LastChangedDate: 2015-03-17 11:13:24 +0800 (三, 2010-05-26) $
   * @copyright            Copyright (c) 2015, www.ganji.com
   */

class Dao_Housepremier_RepeatRefresh extends Gj_Base_MysqlDao
{
    protected $dbName = 'house_premier';
    protected $dbNameAlias = 'premier';
    protected $dbHandler;
    protected $tableName = 'house_premier_repeat_refresh';
    protected $table_fields = array('id','puid','house_id','type','account_id','biz_type','is_repeat','update_time', 'last_update_time');
    
    /**
     * @codeCoverageIgnore
     * @brief 设置重复预约刷新入库
     */ 
    public function setRepeat($repeatInfo){
        $repeatInfo['last_update_time'] = $repeatInfo['update_time'];
        $updateRows = array('biz_type'=>$repeatInfo['biz_type'], 'is_repeat' => $repeatInfo['is_repeat'], 'last_update_time=update_time', 'update_time'=>$repeatInfo['update_time']); 
        if($repeatInfo['is_repeat'] == 1){
            $onDup = $updateRows;
            //ON DUPLICATE KEY UPDATE时： insert 返回id，update 返回0,错误返回false
            $ret = $this->dbHandler->insert($this->tableName, $repeatInfo, 'into',  $onDup);
        } elseif ($repeatInfo['is_repeat'] == 0){
            $arrRows = $updateRows;
            $arrConds =  'house_id='.$repeatInfo['house_id'].' and `type`='.$repeatInfo['type']; //'puid='. $repeatInfo['puid'];
            $ret = $this->update($arrRows, $arrConds);
            if($ret == 1) {
                $ret = 0;//表示更新
            }
        }
        //echo  $this->getLastSQL();
        
        return $ret;
    }
    
    
}
    
