<?php
/** 
 * @package 
 * @subpackage 
 * @brief 
 * @author       $Author: liuzhen1 <liuzhen1@ganji.com>$ 
 * @author       $Author: zhenyangze <zhenyangze@ganji.com>$ 
 * @file         $HeadURL$ 
 * @version      $Rev$ 
 * @lastChangeBy $LastChangedBy$ 
 * @lastmodified $LastChangedDate$ 
 * @copyright Copyright (c) 2014, www.ganji.com 
 * @codeCoverageIgnore
 */ 

class Service_Data_Xiaoqu_Recommend
{
    protected $ids = array();
    /**_call{{{*/
    public function __call($func, $argv){
        if(empty($func) || !method_exists($this, $func)){
            return;
        }
        $arglen = count($argv); 
        switch ($arglen){
            case 0:
                return $this->$func();
                break;
            case 1:
                return $this->$func($argv[0]);
                break;
            case 2:
                return $this->$func($argv[0], $argv[1]);
                break;
            default:
                break;
        }

    }
    /*}}}*/
    /** getXiaoquLookAndLookRecommend 可能感兴趣的小区，看了又看推荐 {{{ 
     *
     * @codeCoverageIgnore
     */
    public function getXiaoquLookAndLookRecommend($xiaoquId, $number=10, $uuid=0)
    {
        try {
            $recModel = Gj_LayerProxy::getProxy('Dao_Recommend_Xiaoqu');
            $ids = $recModel->getXiaoquLookAndLookRecommend($xiaoquId, $number, $uuid);
            $fields = array('id','name','address','district_id','street_id','pinyin','city','avg_price','thumb_image');
            //从小区表中获取数据
            $xqModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');
            $xqInfoItems = $xqModel->getXiaoquInfoByIds($ids, $fields);
            //从小区stat表中获取数据
            $statModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
            $fields = array('xiaoqu_id', 'avg_price', 'rent_cnt', 'sell_cnt', 'share_cnt');
            $statItems = $statModel->getXiaoquStatInfoByXiaoquId($ids, $fields);
            $statInfo = array();
            foreach ($statItems as $item) {
                $xqid = $item['xiaoqu_id'];
                unset($item['xiaoqu_id']);
                $statInfo[$xqid] = $item;
            }
            $tmpXqInfoItems = array();
            foreach ($xqInfoItems as $item) {
                if (!empty($statInfo[$item['id']])) {
                    $tmpXqInfoItems[$item['id']] = array_merge($item, $statInfo[$item['id']]);
                } else {
                    $tmpXqInfoItems[$item['id']] = $item;
                }
            }
            $realXqInfoItems = array();
            foreach ($ids as $id) {
                if(!empty($tmpXqInfoItems[$id])) {
                    $realXqInfoItems[] = $tmpXqInfoItems[$id];
                }
            }
            $data = array(
                'errorno'  => ErrorConst::SUCCESS_CODE,
                'errormsg' => ErrorConst::SUCCESS_MSG,
                'data' => array('items'=>$realXqInfoItems),
            );
        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
    }//}}} 

    /** getXiaoquRelationshipByXiaoquId 获取小区云图/周边小区 {{{*/
    /** 
     * @param int $xiaoquId  小区id
     * @param int $type  1.代表基于小区属性进行推荐  2.代表调用看了还看了推荐接口
     * @return array()
     */
    public function getXiaoquRelationshipByXiaoquId($xiaoquId, $type = null) {
        try {
            $xiaoquRelationshipModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquRelationship');
            $xiaoquRelationship = $xiaoquRelationshipModel->getXiaoquRelationship($xiaoquId, $type);
            $this->ids = array();
            array_walk($xiaoquRelationship, array($this, 'getSimilarJsonDecode'));
            $fields = array('id', 'name', 'pinyin', 'avg_price', 'thumb_image');
            $statModel = Gj_layerProxy::getProxy('Dao_Xiaoqu_XiaoquStat');
            $xiaoquModel = Gj_LayerProxy::getProxy('Dao_Xiaoqu_XiaoquXiaoqu');

            $xiaoquInfoItems = $xiaoquModel->getXiaoquInfoByIds($this->ids, $fields);
            $statItems = $statModel->getXiaoquStatInfoByXiaoquId($this->ids);
            $xiaoquInfoItems = $this->updateKeyByXiaoquId($xiaoquInfoItems, 'id');
            $statItems = $this->updateKeyByXiaoquId($statItems, 'xiaoqu_id');
            foreach($xiaoquRelationship as $key => $item) {
                if (!empty($item['similar_xiaoqu']) && is_array($item['similar_xiaoqu'])) {
                    foreach ($item['similar_xiaoqu'] as $id => &$val){
                        if (isset($xiaoquInfoItems[$id])) {
                            $val = array_merge($val, $xiaoquInfoItems[$id]);
                        }
                        if (isset($statItems[$id])){
                            $val = array_merge($val, $statItems[$id]);
                        }
                    }
                }
                $data[$item['type']] = $item;
            }

        } catch(Exception $e) {
            $data = array(
                'errorno'  => $e->getCode(),
                'errormsg' => $e->getMessage(),
            );
        }
        return $data;
       } 
    /*}}}*/
    protected function getSimilarJsonDecode(&$item){
        $item['similar_xiaoqu'] = json_decode($item['similar_xiaoqu'], true);
        $keys = array_keys($item['similar_xiaoqu']);
        if (is_array($keys)){
            foreach($keys as $k){
                $this->ids[] = $k;
            }
        }
    }
    protected function updateKeyByXiaoquId($info, $key){
        $items = array();
        if (!is_array($info) || 0 == count($info) || empty($key)){
            return $items;
        }
        foreach($info as $v){
            if ($v[$key]){
                $items[$v[$key]] = $v;
            }
        }
        return $items;
    }
}
