<?php

/**
 * HouseImageRent model
 * @author               $Author:   lihongyun1 <lihongyun1@ganji.com>$
 * Date: 14-8-14
 * Time: 下午1:56
 * @codeCoverageIgnore
 */
class Dao_Ganjimisc_PostEditHistory extends Gj_Base_MysqlDao
{
    protected $dbName = 'ganji_misc';
    protected $dbNameAlias = 'ms';
    protected $dbHandler;
    protected $tableName = 'post_edit_history';
    protected $table_fields = array( 'id',  'province_id',  'city_id',  'city_unique_id',  'category',  'major_category',  'major_category_unique_id',  'post_id',  'data',  'puid',  'data_json',  'create_time');

}
    