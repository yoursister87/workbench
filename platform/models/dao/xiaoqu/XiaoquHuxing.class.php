<?php
class Dao_Xiaoqu_XiaoquHuxing extends Gj_Base_MysqlDao
{
    protected $dbNameAlias = 'fang';
    protected $dbName = 'xiaoqu';
	protected $tableName = 'xiaoqu_huxing';
    
    //要查询的列
	protected $queryFields = array(
		'id',
        'title',
		'image',
        'thumb_image',
		'huxing_shi',
		'huxing_ting',
		'huxing_wei',
		'huxing_chu',
		'area',
	);

    //根据户型获取相应的图片
	public function getXiaoquHuxingPicture($xiaoquId, $huxingShi, $limit)
    {
		$xiaoquId = intval($xiaoquId);
		$huxingShi = intval($huxingShi);
		$limit = intval($limit);
		if (empty($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
		}
        //fields
        $fields = $this->queryFields;
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);
        if ($huxingShi > 0) {
            $conds['huxing_shi='] = $huxingShi;
        } else {
            $conds['huxing_shi>'] = 0;
        }
        //appends
        $appends = array('ORDER BY id ASC');
        if ($limit > 0) {
            $appends[] = 'LIMIT '.$limit;
        }
        $result = $this->dbHandler->select($this->tableName, $fields, $conds, NULL, $appends);
		return $result;
	}
    
    //根据户型获取相应图片的数量
	public function getXiaoquHuxingPictureTotalCount($xiaoquId, $huxingShi)
    {
		$xiaoquId = intval($xiaoquId);
		$huxingShi = intval($huxingShi);
		if (empty($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
		}
        //fields
        $fields = array('COUNT(1) total');
        //conds
        $conds = array('xiaoqu_id=' => $xiaoquId);
        if ($huxingShi > 0) {
            $conds['huxing_shi='] = $huxingShi;
        } else {
            $conds['huxing_shi>'] = 0;
        }
        $result = $this->dbHandler->select($this->tableName, $fields, $conds);
		return $result[0]['total'];
	}
}
