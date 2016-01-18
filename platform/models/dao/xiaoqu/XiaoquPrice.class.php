<?php
/**
 * @package              GanjiV5
 * @subpackage           
 * @author               $Author:   
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2012, www.ganji.com
 */
class Dao_Xiaoqu_XiaoquPrice extends Gj_Base_MysqlDao
{
     //数据库别名&库名
    protected $dbNameAlias  = 'fang';
    protected $dbName       = 'xiaoqu';
    protected $tableName = "xiaoqu_price";
    protected $table_fields = array(
        'id',
        'type',     //数据类型 0:city 1:district 2:street 3:xiaoqu
        'category',     //fang类别 1:fang1 3:fang3
        'city_code',
        'district_id',     //区域script_index
        'street_id',     //街道script_index
        'xiaoqu_id',     //小区id
        'huxing',     //fang1 1 2 3 4 fang3 30:合租不限 31:合租单间 32:合租床位
        'mean_price',    //均价
        'post_num',     //帖子数
        'valid_post_num',    //参与的帖子数
        'create_time',     //零点
        'accuracy',     //准确度
        'change_ratio'    //月均价环比
    );
    /*getPriceList{{{*/
    /**
     * @params $paramsArr = array(
                    'cityCode' => 0,
                    'districtId' => 0,
                    'streetId' => 52,
                    'xiaoquIds' => array(),
                    'category'  => 1 || 3
                  )
     * @param $dateBEArr = array('begin' => xxxxx, 'end' => 'xxxxxx'); 
     * @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
     * @return array() 如果没有数据返回array()
     */
    public function getPriceList($paramsArr, $dateBEArr, $type){
        $itemList = array();
        $fields = array('id', 'district_id', 'street_id', 'xiaoqu_id', 'huxing', 'change_ratio as avg_price_change', 'mean_price as avg_price', 'create_time as record_time');
        $conArrays = array('category = ' => $paramsArr['category'], 'city_code =' => $paramsArr['cityCode']);
        if (1 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
        } elseif (2 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
            $conArrays['street_id ='] = $paramsArr['streetId'];
        } elseif (3 === (int)$type) {
            $xiaoquIds = implode(',', $paramsArr['xiaoquIds']);
            $conArrays[] = 'xiaoqu_id in (' . $xiaoquIds . ')';
        }
        if (!empty($paramsArr['huxing'])) {
            $conArrays['huxing = '] = $paramsArr['huxing'];
        }
        $conArrays['type = '] = $type;
        $conArrays['create_time >='] = $dateBEArr['begin'];
        $conArrays['create_time <= '] = $dateBEArr['end'];
        $itemList = $this->select($fields, $conArrays);
        if (FALSE === $itemList) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "$sql", ErrorConst::E_SQL_FAILED_CODE);
        }
        return $itemList;
    }//}}}
    /*{{{getPriceListByMultiArea*/
    /**
     * @params $paramsArr = array(
                    'cityCode' => 0,
                    'districtIds' => array(),
                  )
     * @param $type 要查询的均价类型0:city 1:district 2:street 3:xiaoqu'
     * @return array() 如果没有数据返回array()
     */ 
    public function getPriceListByMultiArea($paramsArr, $type) {
        $itemList = array();
        $fields = array('id', 'district_id', 'street_id', 'xiaoqu_id', 'change_ratio as avg_price_change', 'mean_price as avg_price', 'create_time as record_time');
//        $conArrays = array('city =' => $paramsArr['cityCode']);
        $conArrays['type = '] = $type;

        if (1 === (int)$type) {
            $districtIds = implode(',', $paramsArr['districtId']);
            $conArrays[] = 'district_id in (' . $districtIds . ')';
        } elseif (2 === (int)$type) {
            $conArrays['district_id = '] = $paramsArr['districtId'];
            $streetIds = implode(',', $paramsArr['streetId']);
            $conArrays[] = 'street_id in (' .$streetIds . ')';
        } elseif (3 === (int)$type) {
            $xiaoquIds = implode(',', $paramsArr['xiaoquIds']);
            $conArrays[] = 'xiaoqu_id in (' . $xiaoquIds . ')';
        }
 
        $time = $this->getSearchTime();
        $conArrays['create_time ='] = $time;
        $itemList = $this->select($fields, $conArrays);
        if (FALSE === $itemList) {
                throw new Exception(ErrorConst::E_SQL_FAILED_MSG . "getPriceList : " . $paramsArr, ErrorConst::E_SQL_FAILED_CODE);
        }
        return $itemList;
 
    }
    /*}}}*/
    /**getSearchTime{{{*/
    protected function getSearchTime($searchTime = 0){
        if ($searchTime > 0) {
            //重新获取时间
            $day = date('d', $searchTime);
            if ($day >= 15) {
                $searchTime = strtotime(date('Y-m-01', $searchTime));
            } else {
                $searchTime = strtotime(date('Y-m-15', strtotime(date('Y-m-d', $searchTime) . ' -1 month')));
            }
        } else {
            //获取当前日期最近的1号或者15号的数据
            $currentTime = $this->getTime();
            $date15 = strtotime(date('Y-m-15', $currentTime));
            if ($currentTime >= $date15) {
                $searchTime = $date15;
            } else {
                $searchTime = strtotime(date('Y-m-01', $currentTime));
            }
        }
        return $searchTime;
    }//}}}
    /**getTime{{{*/
    /**
     * @codeCoverageIgnore
     */
    protected function getTime(){
        $time = Gj_LayerProxy::getProxy('Gj_Util_TimeMock');
        $now = $time->getTime();
        return $now;
    }//}}}


}

