<?php

/**
 * HouseImageSell model
 * User: lihongyun1
 * Date: 14-8-14
 * Time: 下午1:56
 */
class Dao_Fang_HouseImageSell extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'fang';
    protected $dbHandler;
    protected $tableName = 'house_image_sell';
    protected $table_fields = array('id','post_id','image','middle_image','thumb_image','time','ind');
   /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = $dbName;
        parent::__construct();
    }
   /**
     * 通过图片顺序ind 来排序
     * @codeCoverageIgnore
     */
    public function selectOrderByInd($arrFields,$arrConds){
        $appends = ' ORDER BY ind asc, id asc';
        $ret = $this->dbHandler->select($this->tableName, $arrFields, $arrConds,null,$appends);
        return $ret;

    }
}
    