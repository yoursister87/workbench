<?php
/**
 * Created by PhpStorm.
 * User: kongxiangshuai
 * Date: 2015/6/10
 * Time: 10:23
 */


class Dao_Fang_HouseImageOuter extends Gj_Base_MysqlDao
{
    protected $dbName;
    protected $dbNameAlias = 'fang';
    protected $dbHandler;
    protected $tableName = 'house_image_outer_premier';
    protected $table_fields = array(
        'id',
        'house_id',
        'type',
        'is_cover',
        'category',
        'image',
        'middle_image',
        'thumb_image',
        'upload_time',
        'size',
        'direction',
        'description',
        'auditor_id',
        'auditor_name',
        'audited_time',
        'status',
        'delete_time'
    );
    /**
     * @param $dbName 数据库名
     * @codeCoverageIgnore
     */
    public function __construct($dbName)
    {
        $this->dbName = "house_premier";
        parent::__construct();
    }

}