<?php
class Dao_Xiaoqu_XiaoquPhoto extends Gj_Base_MysqlDao
{
    protected $dbNameAlias = 'fang';
    protected $dbName = 'xiaoqu';
	protected $tableName = 'xiaoqu_photo';
    
    //查询的列
	protected $queryFields = array(
        'id',
        'type',
        'title',
		'image',
        'thumb_image',
	);
    
    //根据type获取相应的图片
	public function getXiaoquOutdoorPicture($xiaoquId, $limit=0, $type = 1)
    {
		$xiaoquId = intval($xiaoquId);
		$limit = intval($limit);
		if (empty($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
		}
        //fields
        $fields = $this->queryFields;
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);
        if (intval($type) !== 0) {
            $conds['type='] = $type;
        }
        //appends
        $appends = array('ORDER BY id ASC');
        if ($limit > 0) {
            $appends[] = 'LIMIT '.$limit;
        }
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
		return $result;
	}

    //根据type获取相应图片的数量
	public function getXiaoquOutdoorPictureTotalCount($xiaoquId, $type = 1)
    {
		$xiaoquId = intval($xiaoquId);
		if (empty($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
		}
        //fields
        $fields = array('COUNT(1) total');
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);
        if (intval($type) !== 0) {
            $conds['type='] = $type;
        }
        $result = $this->dbHandler->select($this->tableName, $fields, $conds);
		return $result[0]['total'];
	}
}
