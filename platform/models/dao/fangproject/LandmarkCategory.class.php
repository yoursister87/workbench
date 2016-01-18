<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   gaoguangyang <gaoguangyang@ganji.com>$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2015, www.ganji.com
 */
class Dao_Fangproject_LandmarkCategory extends Gj_Base_MysqlDao
{
    protected $dbNameAlias = 'fang';
    protected $dbName = 'fang_project';
    protected $tableName = 'landmark_category';
    protected $table_fields = array('id','parent_id','category_name');
}
