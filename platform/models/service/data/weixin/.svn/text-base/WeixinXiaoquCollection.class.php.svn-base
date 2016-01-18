<?php
/**
 * @package
 * @subpackage
 * @brief      			 $微信小区收藏data处理层$
 * @file                 WeixinCollectionFormatInfo.class.php$
 * @author               $Author:   zhangshengli <zhangshengli@ganji.com>$
 * @lastChangeBy         2015-05-29
 * @lastmodified         上午17:00
 * @copyright            Copyright (c) 2014,fangweixin.3g.ganji.com
*/
class Service_Data_Weixin_WeixinXiaoquCollection
{
    protected $uuid;
    protected $xiaoquInfoObj;
    protected $xiaoquCollectionObj;
    protected $BigImageWidth = 360;
    protected $heightOrSmallWidth = 200;
    protected static $Ganji_Wap_Default_Image = 'http://sta.ganjistatic1.com/src/image/mobile/touch/weixin/default_ganji_logo.png';
    protected $arrRet = array(
        'errorno' => ErrorConst::SUCCESS_CODE,
        'errormsg' => ErrorConst::SUCCESS_MSG,
        'data' => array(),
    );

    public function __construct()
    {
        //验证openid
        $this->xiaoquCollectionObj  = Gj_LayerProxy::getProxy('Dao_Weixin_WeixinXiaoquCollection');
        $this->xiaoquInfoObj        = Gj_LayerProxy::getProxy('Service_Data_Xiaoqu_Info');
    }
	 /**
     * @brief 单测注入用
     * @param $obj
     */
    public function setInnerObj($obj, $protype)
    {
        $this->$protype = $obj;
    }
    /**
     * @brief 根据xiaoquId取出格式化的小区信息
     * @param null $xiaoquId
     * @return array
     */
    public function getXiaoquInfoById($xiaoquId= null, $openid = null)
    {
        try {

            $arrRet = $this->arrRet;
            $this->checkParams(array($xiaoquId, $openid));

            $fields = array("id", "thumb_image", "name", "pinyin", "city", "district_id", "street_id", "finish_at");

            $ret = $this->xiaoquInfoObj->getXiaoquInfoById($xiaoquId, $fields, true);
			
            $xiaoquInfo = $ret['data'];
            
            $arrRet['data'] = $this->formatXiaoquInfo($xiaoquInfo);

            //保存时用的postInfo是未格式化前的postInfo,而推出时的postInfo是格式化过的
            $this->saveXiaoquCollection($arrRet['data'],['openid'=> $openid, 'xiaoqu_id'=>$xiaoquId,'pinyin'=>$xiaoquInfo['city']]);

        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 获取收藏列表接口
     * @param null $openid
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getXiaoquCollectionByOpenId($openid = null, $limit = 10, $page = 1)
    {
        try {

            $arrRet = $this->arrRet;

            $this->checkParams(array($openid));

            $collectionList = $this->xiaoquCollectionObj->selectCollections($openid, array('openid', 'xiaoqu_id', 'name', 'pinyin', 'thumb_img', 'url', 'city_name', 'district_name', 'street_name', 'avg_price', 'finish_at'),$limit,$page);
			
            $arrRet['data'] = $collectionList;

        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
		
        return $arrRet;
    }

    /**
     * @brief 删除该用户的所有收藏数据
     * @param null $openid
     * @return array
     */
    public function clearXiaoquCollectionByOpenId($openid = null)
    {
        try {

            $arrRet = $this->arrRet;
            $this->checkParams(array($openid));

            if (!$this->xiaoquCollectionObj->deleteAllCollectionsByOpenid($openid)) {
                $arrRet['errorno'] = ErrorConst::E_INNER_FAILED_CODE;
                $arrRet['errormsg'] = ErrorConst::E_INNER_FAILED_MSG;
            }
        } catch (Exception $e) {
            $arrRet['errorno'] = $e->getCode();
            $arrRet['errormsg'] = $e->getMessage();
        }
        return $arrRet;
    }

    /**
     * @brief 必须参数为空检查
     * @param $paramsReal
     * @return bool
     */
    protected function checkParams($paramsReal = array())
    {
        foreach($paramsReal as $key => $value){
            if ($value === null) {
                throw new Gj_Exception(ErrorConst::E_PARAM_INVALID_CODE, ErrorConst::E_PARAM_INVALID_MSG);
            }
        }
        return true;
    }

    /**
     * @brief 格式化小区信息
     */
    protected function formatXiaoquInfo($xiaoquInfo){

        $ret = array();

        $cityInfo = GeoNamespace::getCityByDomain($xiaoquInfo['city']);
        list($p_index, $c_index) = GeoNamespace::cityCodeDecode($cityInfo['city_code']);
        $districtInfo = GeoNamespace::getDistrictByScriptIndex($p_index, $c_index, $xiaoquInfo['district_id']);
        $streetInfo = GeoNamespace::getStreetByScriptIndex($p_index, $c_index, $xiaoquInfo['district_id'], $xiaoquInfo['street_id']);
		
		$url = 'http://fangweixin.3g.ganji.com/'.$xiaoquInfo['city'].'_xiaoqu/'.$xiaoquInfo['pinyin'].'/?vvcc=3g&ifid=gjwx_gj_sc&ca_s=other_weixin&ca_n=scan';
        //$url = 'http://'.$xiaoquInfo['city'].'.ganji.com/xiaoqu/'.$xiaoquInfo['pinyin'].'/';

        $ret['thumb_img'] = $this->getImageUrlBySize($xiaoquInfo['thumb_image'], $this->heightOrSmallWidth, $this->heightOrSmallWidth);
        $ret['big_img'] = $this->getImageUrlBySize($xiaoquInfo['thumb_image'], $this->BigImageWidth, $this->heightOrSmallWidth);
        $ret['name'] = $xiaoquInfo['name'];
        $ret['finish_at'] = $xiaoquInfo['finish_at'];
        $ret['city'] = $cityInfo['name'];
        $ret['pinyin'] = $xiaoquInfo['city'];
		$ret['xiaoqu_pinyin'] = $xiaoquInfo['pinyin'];
        $ret['district_name'] = $districtInfo['name'];
        $ret['street_name'] = $streetInfo['name'];
        $ret['avg_price'] = $xiaoquInfo['avg'];
		//设置区域的ID 
		$ret['district_id'] = $xiaoquInfo['district_id'];//区域
		$ret['street_id'] = $xiaoquInfo['street_id'];//街道
		
        $ret['url'] = $url;
        
        return $ret;
    }

    /**
     * @brief 图片大小矫正
     * @param $url
     * @param $width
     * @param $height
     * @param null $type c/f
     * @param int $quality [0-9]
     * @param int $version [0..]
     * @return string
     */
    public function getImageUrlBySize($url, $width, $height, $type = 'c', $quality = 6, $version = 0)
    {
        $pattern = '/_(\d+)-(\d+)(.*)_[0-9]-[0-9]/i';
        $replaceStr = '_' . $width . '-' . $height . $type . '_' . $quality . '-' . $version;
        if(strpos($url, 'tuiguang')){
            $imageUrl =  'http://image.ganjistatic1.com/'. $url;
        }else if( $url == null || $url == self::$Ganji_Wap_Default_Image || strpos($url, 'list_default_pic') || strpos($url, 'default_weixin_logo') ){
            $imageUrl = self::$Ganji_Wap_Default_Image;
        }else{
            $imageUrl = 'http://image.ganjistatic1.com/'. preg_replace($pattern, $replaceStr, $url);
        }
        return $imageUrl;
    }

    /**
     * @brief 小区收藏条件入库 
     */
    protected function saveXiaoquCollection($xiaoquInfo, $annotationData){

        $data = array(
            'openid'    => $annotationData['openid'],
            'xiaoqu_id' => $annotationData['xiaoqu_id'],
            'name'      => (!empty($xiaoquInfo['street_name']) ? $xiaoquInfo['street_name'].'|' : $xiaoquInfo['district_name'].'|').$xiaoquInfo['name'].'|'.( $xiaoquInfo['avg_price'] > 0 ? round($xiaoquInfo['avg_price']).' 元/㎡' : '暂无均价'),
            'thumb_img' => $xiaoquInfo['thumb_img'] ? $xiaoquInfo['thumb_img'] : self::$Ganji_Wap_Default_Image,
            'url'       => $xiaoquInfo['url'],
            'city_name' => $xiaoquInfo['city'],
            'pinyin'    => $annotationData['pinyin'],
            'district_name' => $xiaoquInfo['district_name'],
            'street_name'   => $xiaoquInfo['street_name'],
            'avg_price'     => $xiaoquInfo['avg_price'],
            'finish_at'     => $xiaoquInfo['finish_at'],
            'create_time'   => time()
        );
		//判断是否已经达到收藏上限10条
		$result = $this->getXiaoquCollectionByOpenId($annotationData['openid']);
		if(count($result['data']) >= 10 )
		{
			//先删除创建时间最晚一条
			$result_data = end($result['data']);
			$this->xiaoquCollectionObj->deleteOneCollectionsByOpenidAndXiaoquID($result_data['openid'],$result_data['xiaoqu_id']);
		}
        $this->xiaoquCollectionObj->insertOneCollection($data);
    }
}
