<?php
/**
 * @package              
 * @subpackage           
 * @author               $Author:   shenweihai$
 * @file                 $HeadURL$
 * @version              $Rev$
 * @lastChangeBy         $LastChangedBy$
 * @lastmodified         $LastChangedDate$
 * @copyright            Copyright (c) 2010, www.ganji.com
 */
class Dao_Recommend_House
{
   // memcache的key前缀
   public $memKey ='housing_get_related_house_';
   // memcache缓存时间
   public $cacheTime = 3600; 
    public $houseFields = array(
        'title',
        'xiaoqu',
        'xiaoqu_address',
        'district_id',
        'district_name',
        'street_id',
        'street_name',
        'pinyin',
        'price',
        'huxing_shi',
        'huxing_ting',
        'thumb_img',
        'area',
        'major_category',
        'chaoxiang',
        'zhuangxiu',
        'puid'
    );
    public $officeStoreFields = array(
        'title',
        'district_id',
        'street_id',
        'area',
        'price',
        'district_name',
        'street_name',
        'puid',
        'loupan_name',
        'house_type',
        'address',
        'price_type',
        'major_category',
        'thumb_img'
    );

   /* 获取归档贴推广贴推荐信息,使用memcached进行缓存*/
   public function getArchiveHouseRecommendList($limit = 10, $params= array()){
        $memcache = Gj_Cache_CacheClient::getInstance('Memcache');
        $key = $this->memKey. $this->puid;
        $recList = $memcache->read($key);
        if (empty($recList) || false == $recList) {
            $obj = new Recommend();
            $recList = $obj->getArchiveRecommend($params['city_code'],$params['major_index'],$params['puid'],$limit,$params['agent'],$params['source'],$params['show_pos']);
            $memcache->write($key, $recList, $this->cacheTime);
        }
        if (!is_array($recList) || empty($recList)) {
            return false;
        } else {
            return $recList;
        }
    }

   /* 获取非归档贴推广信息*/
   public function getRelatedHouseList($limit= 10, $params= array()) {
        $obj = new Recommend();
        $recList = $obj->getHousingRecommend($params['city_code'],$params['major_index'],$params['puid'],$limit,$params['agent'],$params['source'],$params['show_pos']);
        if (!is_array($recList) || empty($recList)) {
            return false;
        } else {
            return $recList;
        }
   }
   public function getListSimilarHousing($info, $num, $day, $agent){
       $obj = new Recommend();
       $recList = $obj->getListSimilarHousing($info, $num, $day, $agent);
       if (is_array($recList) && !empty($recList)) {
           return $recList;
       } else {
           return array();
       }
   }
   /* 获取非归档贴推广信息*/
   public function getRelatedPostsWithFields($limit= 10, $params= array()) {
        $obj = new Recommend();
        $fields = $this->getPostFieldsByMajorCategory($params['major_index']);
        $recList = $obj->getRelatedPostsWithFields($params['city_code'],$params['major_index'],$params['puid'],$limit,$params['agent'],$params['source'],$params['show_pos'], $fields);
        if (!is_array($recList) || empty($recList)) {
            return false;
        } else {
            return $recList;
        }
   }
   public function getPostFieldsByMajorCategory($majorCategoryIndex){
       $fieldsArr = array(
            1 => $this->houseFields,
            3 => $this->houseFields,
            5 => $this->houseFields,
            12 => $this->houseFields,
            6 => $this->officeStoreFields,
            7 => $this->officeStoreFields,
            8 => $this->officeStoreFields,
            9 => $this->officeStoreFields,
            11 => $this->officeStoreFields,
            10 => array(
                'district_id',
                'street_id',
                'district_name',
                'street_name',
                'price',
                'puid',
                'thumb_img',
                'fang_xing',
                'major_category',
                'title'
            ),
        );
       return isset($fieldsArr[$majorCategoryIndex]) ? $fieldsArr[$majorCategoryIndex] : array();
   }

   //测试数据
   public function test($limit = 10){
     return array (
            0 =>
            array (
              'agent' => '经纪人', //经纪人、个人
              'area' =>15, //面积
              'district_id' => '0',
              'district_name' => '海淀', //区域名字
              'house_id' => '5051298',
              'house_type' => '2',
              'huxing_shi' => 1,//室
              'huxing_ting' => 2,//厅
              'huxing_wei' => 2,//卫
              'image_count' => '5',
              'listing_status' => 1,
              'major_category' => '3',
              'person' => '陈远', //联系人
              'phone' => '13683319551',//电话号
              'pinyin' => 'huashengjiayuan',
              'post_at' => '08-13',//发帖时间
              'post_type' => 1,
              'price' =>1200, //价格
              'price_type' => '',
              'puid' => '207395',
              'refresh_at' => '2小时前',//刷新时间
              'share_mode' => '1',
              'street_id' => '46',
              'street_name' => '牡丹园',//街道
              'thumb_img' => 'gjfs04/M00/41/B0/wKhzK1IJqPGMaO49AAEsmHSNWNI043_120-100_9-0.jpg',
              'title' => '最新房源 华盛家园 精装明隔带空调 五家合住 可洗澡做饭',//标题
              'xiaoqu' => '华盛家园',//小区名称
              'zhuangxiu' => 2,
              'ceng' =>1,
              'chaoxiang' =>1,
             ),
             1 => array(
                  '__docid' => 101001414855465,
                  'account_id'  => 1234,
                  'address' => '[丰台  科技园区] 南四环西路188号',
                  'agent' => 1,
                  'area' => 200,
                  'district_id' => 6,
                  'district_name' => '丰台',
                  'house_name' => 'xxxxxx' ,
                  'house_type' => 7,
                  'image_count' => 4,
                  'listing_status' => 9,
                  'loupan_name' => '总部基地',
                  'major_category' => 6,
                  'post_at' => 1427255361,
                  'post_id' => 5773084,
                  'post_type' => 10,
                  'price' => 4.5,
                  'price_type' => 1,
                  'puid' => 1414855465,
                  'refresh_at' => 1427344275,
                  'store_rent_type' => 2,
                  'store_stat' => 1,
                  'street_id' => -1,
                  'street_name' => '不限',
                  'thumb_img' => 'gjfs12/M07/DB/C3/CgEHnVUBCN7w0IwZAAGMBVxvCUs105_90-75c_6-0.jpg',
                  'title' => '速来!丰台区总部基地!美食广场!火爆招商!',
                  'top_info' => '1 12 11 26 12',
                  'trade' => '1,2,4,5,6,7,8',
                  'user_id' => 452216655,
            ),
         );
    }   

}
