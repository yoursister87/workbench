<?php
class Dao_Recommend_Xiaoqu
{
    /**
     * 获取看了又看的小区推荐
     * 
     * @codeCoverageIgnore
     */
    public function getXiaoquLookAndLookRecommend($xiaoquId, $number=10, $uuid=0){
		$xiaoquId = intval($xiaoquId);
		if (empty($xiaoquId)) {
			throw new Exception(ErrorConst::E_PARAM_INVALID_MSG, ErrorConst::E_PARAM_INVALID_CODE);
		}
		$rec = new Recommend();
		$result = $rec->getXiaoquLookAndLookRecommend($xiaoquId, $number, $uuid);
		return $result;
	}
}
