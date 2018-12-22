<?php
/***************************************************************************
 *
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/


/**
 * 作业帮公共图片服务
 *
 * @since 2.1 2018-05-30 新增函数支持其他格式图片
 * @since 2.0 2018-04-24 整理代码，删除无用的代码调用
 *
 * @filesource hk/util/Image.php
 * @author  jiangyingjie(com@baidu.com)
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 2.1
 * @date    2018-05-30
 **/
class Hk_Util_Image {


    const ERR_IMG_UPLOAD_FAILED = 12000;
    const ERR_IMG_TYPE          = 12001;

    public static $imageType   = array(
        1  => "gif",
        2  => "jpg",
        3  => "png",
        4  => "swf",
        5  => "psd",
        6  => "bmp",
        7  => "tiff",
        8  => "tiff",
        9  => "jpc",
        10 => "jp2",
        11 => "jpx",
        12 => "jb2",
        13 => "swc",
        14 => "iff",
        15 => "wbmp",
        16 => "xbm",
    );

    /**
     * 通过解密图片ID获取hiphoto图片的URL
     *
     * @param int         $intPicId 图片ID
     * @param string      $strSpec  图片的修饰参数，示例请见：{@link http://man.baidu.com/mans/inf/odp/plugins/pic/interface/Bd_Pic/pid2Url.html}
     * @return mixed:string|boolean 成功返回图片的url，失败返回false
     */
    private static function getImgUrl($intPicId, $strSpec = "") {
        if ($intPicId <= 0) {
            Bd_Log::warning("Error:[param error, Detail:[intPicId: $intPicId]");
            return false;
        }

        $arrReq = array(
            array(
                "pic_id"       => $intPicId,
                "product_name" => 'zhidao',
            ),
        );
        if (!empty($strSpec)) {
            $arrReq[0]["pic_spec"] = $strSpec;
        }

        $ret = Bd_Pic::pid2Url($arrReq, false);
        if (!isset($ret['err_no']) || $ret['err_no'] != 0) {
            Bd_Log::warning('Error:[get image url failed], Detail:[error_msg: '.$ret['err_msg'].', error_no: '.$ret['err_no'].']');
            return false;
        }
        return $ret['resps'][0];
    }

    /**
     * 通过图片的加密串|pid获取图片的URL<br>
     * 支持hiphoto（老版本）和bos新版本
     *
     * @param mixed      $src   图片加密串
     * @param string     $spec  bos图片的修饰参数
     * @return mixed:string|boolean
     */
    public static function getImgUrlBySrc($src, $spec = "") {
        Bd_Log::addNotice("imgPid", $src);
        if (strlen($src) <= 0) {
            return false;
        } elseif (1 === preg_match('/^https?:\/\//', $src)) {              # url
            return $src;
        }

        # bos
        $pattern    = '/^(zyb|qa)([\d]*)_[0-9a-zA-Z]+(\.[0-9a-zA-Z]+)?$/'; # 兼容pid有后缀
        if (1 === preg_match($pattern, $src, $match)) {
            $bucket = isset($match[2]) ? 'image' . $match[2] : "image";    # imgBucketId
            $object = isset($match[3]) ? $src : "{$src}.jpg";              # 图片后缀兼容

            $objBos = new Hk_Service_Bos($bucket);
            $url    = $objBos->getObject($object);
            if (false !== strpos($object, ".jpg")) {            # 只有jpg的图可以进行图片修饰
                $pos  = empty($spec) ? "" : "@" . $spec;
                $url  = $url . $pos;
            }
            return $url;
        }

        # hiphoto
        $hPicId     = self::getPicIdBySrc($src);
        if (false === $hPicId) {
            return false;
        }
        return self::getImgUrl($hPicId, $spec);
    }

    /**
     * hiphoto域名获取图片pid<br>
     * 成功返回图片picId<br>
     * 失败返回false
     *
     * @param string      $strSrc 图片加密串src
     * @return  int
     */
    public static function getPicIdBySrc($strSrc) {
        $pregPattern = '/^zyb([\d]*)_[0-9a-zA-Z]+/';
        if (preg_match($pregPattern, $strSrc, $match) === 1 || strpos($strSrc, 'qa_') === 0) {
            return false;
        }
        $objUrlCrypt = new Bd_Pic_UrlCrypt();
        $intPicId    = $objUrlCrypt->decode_pic_url_crypt($strSrc);
        return $intPicId;
    }

    /**
     * 根据图片内容计算图片pid和图片基本信息<br>
     * 备注：imgBucketId默认为空，则前缀为zyb_<br>
     * imgBucketId支持取值为1. 则前缀为zyb1_
     *
     * @param stream      $content
     * @param string      $imgBucketId
     * @return array
     */
    public static function getPicIdByContent($content, $imgBucketId = "") {
        list($width, $height, $type) = @getimagesizefromstring($content);
        $pid = self::genImagePid($content, $imgBucketId);
        return array(
            'pid'    => $pid,
            'width'  => $width,
            'height' => $height,
        );
    }

    /**
     * 上传图片，请注意：此方法只支持jpg，pid不会带图片后缀（后缀固定）<br>
     * 上传成功，返回图片详情["pid", "width", "height"]<br>
     * 上传失败，返回false
     *
     * @since 2018-05-30 写死图片后缀，禁止其他后缀出现
     * @since 2016-05-27 图片的width, height等额外信息通过header存储，不在使用数据库存储
     *
     * @param stream        $content     图片数据
     * @param int           $width       图片width
     * @param int           $height      图片height
     * @param string        $imgBucketId 图片bucketId，支持取值为‘1’，默认值为""
     * @return mixed:array|boolean
     */
    public static function uploadImage($content, $width = 0, $height = 0, $imgBucketId = "") {
        list($width, $height, $type) = @getimagesizefromstring($content);    # 获取图像基本信息
        if (NULL === $type || !isset(self::$imageType[$type])) {
            Bd_Log::warning("image upload err, errType: {$type}", self::ERR_IMG_TYPE);
            return false;
        }

        $ext = "jpg";
        $pid = self::uploadBos($content, $width, $height, $imgBucketId, $ext);
        if (false === $pid) {
            return false;
        }
        return array(
            'pid'    => $pid,
            'width'  => $width,
            'height' => $height,
        );
    }

    /**
     * 新上传图片，支持多种图片类型，类型参见：$imageType<br>
     * 上传成功，返回图片详情["pid", "width", "height"]<br>
     * 上传失败，返回false<br>
     *
     * 请注意：此图片的pid会带图片后缀
     *
     * @since 2018-05-30 初始化
     *
     * @param stream        $content     图片数据
     * @param string        $imgBucketId 图片bucketId，支持取值为‘1’，默认值为""
     * @return mixed:array|boolean
     */
    public static function uploadExtImage($content, $imgBucketId = "") {
        list($width, $height, $type) = @getimagesizefromstring($content);    # 获取图像基本信息
        if (NULL === $type || !isset(self::$imageType[$type])) {
            Bd_Log::warning("image upload err, errType: {$type}", self::ERR_IMG_TYPE);
            return false;
        }

        $ext = self::$imageType[$type];
        $pid = self::uploadBos($content, $width, $height, $imgBucketId, $ext);
        if (false === $pid) {
            return false;
        }
        $imgPid = sprintf("%s.%s", $pid , $ext);
        return array(
            'pid'    => $imgPid,
            'width'  => $width,
            'height' => $height,
        );
    }

    /**
     * 检验图片是否存在，并获取图片元数据<br>
     * 如果图片存在，返回图片元数据信息。<br>
     * 如果图片不存在，或者未获取到图片元数据信息，返回false。
     *
     * @param string       $pid
     * @return mixed
     */
    public static function checkPic($pid) {
        if (strlen($pid) <= 0) {
            return false;
        }

        $bucket  = 'image';
        $object  = $pid;
        $pattern = '/^(zyb|qa)([\d]*)_[0-9a-zA-Z]+(\.[0-9a-zA-Z]+)?$/';
        $imageId = '';
        if (1 === preg_match($pattern, $pid, $match)) {
            $bucket     = isset($match[2]) ? 'image' . $match[2] : "image";
            if(isset($match[2])){
                $imageId = $match[2];
            }
            if (!isset($match[3])) {                        # pid无后缀，默认使用jpg
                $object = "{$pid}.jpg";
            }
        }

        if($imageId >= 10 && $imageId <= 19){
            //预留image10-image19是属于腾讯云
            //兼容腾讯云图片服务
            return Hk_Service_Tcos::getImageMeta($bucket, $pid);
        }else{
            $objBos  = new Hk_Service_Bos($bucket);
            $ret     = $objBos->getObjectMeta($object);
            if (false === $ret) {
                return false;
            }
            return array(
                    "pid"    => $pid,
                    "ext"    => isset($ret["ext"])    ? $ret["ext"] : "jpg",       # 图片扩展名
                    "width"  => isset($ret["width"])  ? intval($ret["width"])  : 0,
                    "height" => isset($ret["height"]) ? intval($ret["height"]) : 0,
                    'bucket' => $bucket,
                    );
        }
    }

    /**
     * 上传图片到bos，成功返回图片pid，失败返回false
     *
     * @since 2018-05-30 增加meta字段，ext：图片扩展名
     * @since 2016-05-26 meta支持图片扩展名
     *
     * @param string      $content
     * @param int         $width
     * @param int         $height
     * @param string      $imgBucketId 支持‘’ 或‘1’
     * @param string      $ext
     * @return mixed:string|boolean
     */
    private static function uploadBos($content, $width, $height, $imgBucketId = "", $ext = "jpg") {
        $pid  = self::genImagePid($content, $imgBucketId);
        $meta = array(
                "ext"    => $ext,
                "width"  => $width,
                "height" => $height,
                );

# 上传图片到BOS
        Hk_Util_Log::start("img_upload");
        $bucketName = 'image' . $imgBucketId;
        $objBos     = new Hk_Service_Bos($bucketName);
        $ret        = $objBos->putObjectByString($content, sprintf("%s.%s", $pid, $ext), $meta);      # bos存储带扩展，返回pid
            Hk_Util_Log::stop("img_upload");
        if (false === $ret) {
            Bd_Log::warning("upload image err, bos error", self::ERR_IMG_UPLOAD_FAILED, array(
                        "pid"  => $pid,
                        "pext" => $ext,
                        ));
            return false;
        }
        return $pid;
    }

    /**
     * 计算图片pid
     *
     * @param stream      $content
     * @param string      $imgBucketId
     * @return string
     */
    private static function genImagePid($content, $imgBucketId) {
        $prefix = ral_get_idc() == "test" ? "qa" : "zyb";
        return sprintf("%s%s_%s", $prefix, $imgBucketId, md5(md5($content) . strlen($content)));
    }

    /**
     * copy图片
     *
     * @param string      $pid1    源图片
     * @param string      $pid2    目标图片
     * @return mixed:array|boolean
     */
    public static function copyImage($pid1, $pid2) {
        if ($pid1 == $pid2) {
            return true;
        }
        $pregPatten = '/^zyb([\d]*)_[0-9a-zA-Z]+/';
        if (preg_match($pregPatten, $pid1, $match) === 1 || strpos($pid1, 'qa_') === 0) {
            if (empty($match)) {
                $srcBucketName = 'image';
            } else {
                $srcBucketName = 'image' . $match[1];
            }
        }
        if (preg_match($pregPatten, $pid2, $match) === 1 || strpos($pid2, 'qa_') === 0) {
            if (empty($match)) {
                $dstBucketName = 'image';
            } else {
                $dstBucketName = 'image' . $match[1];
            }
        }
        $objBos = new Hk_Service_Bos($dstBucketName);
        $ret    = $objBos->copyObject($pid1.".jpg", $pid2.".jpg", $srcBucketName);
        return $ret;
    }

    public static function getImgBucketId($vc, $vcname, $os) {
        if(ral_get_idc() == 'tx'){
            //腾讯云机房返回10，即image10
            //只有picsearch适用
            return '10';
        }
        if ($os == 'android' && $vc >= 291) {
            return '1';
        }
        if ($os == 'ios' && $vc >= 218) {
            return '1';
        }
        return "";
    }

    /**
     * 上传图片。<br>
     * 如果上传成功，会返回对应的图片信息["pid", "width", "height"]<br>
     * 如果上传失败，会返回false<br>
     *
     * added 2016-05-27 tangyang: 图片的width, height等额外信息通过header存储，不在使用数据库存储<br>
     *
     * @param stream        $picContent  图片数据
     * @param int           $width       图片width
     * @param int           $height      图片height
     * @param string        $imgBucketId      图片bucketId，支持取值为‘1’，默认值为""
     * @return mixed
     */
    public function uploadSafeImage($picContent, $width = 0, $height = 0) {
        list($width, $height, $type) = @getimagesizefromstring($picContent);    # 获取图像基本信息
        if (NULL === $type || !isset(self::$imageType[$type])) {                                  # 非图片格式，直接不上传
            Bd_Log::warning("Image, Upload picture failed, image type error", self::ERR_IMG_TYPE);
            return false;
        }

        $ret        = self::uploadSafeBOSImage($picContent, $width, $height);
        if (false === $ret) {           # 上传失败
            return false;
        }
        return $ret;                    # 结构["pid", "width", "height"]
    }

    private function uploadSafeBOSImage($picContent, $width, $height, $bucketName = "zyb-charge") {
        $pid    = sprintf("%s_%s", 'fudao_', md5(md5($picContent) . strlen($picContent)).'.jpg');
        $meta   = array(         # added 2016-05-26，提供meta存储
            "width"  => $width,
            "height" => $height,
        );

        // 上传图片到BOS
        $objBos     = new Hk_Service_Bos($bucketName);
        $ret        = $objBos->putObjectByString($picContent, $pid, $meta);      # TODO bos存储的名字有.jpg，返回给调用方只有pid，这是一颗地雷
        if (false === $ret) {
            $arg    = ["pid" => $pid];
            Bd_Log::warning("Image, Upload picture to bos failed", self::ERR_IMG_UPLOAD_FAILED, $arg);
            return false;
        }

        $arrRes = array(
            'pid'    => $pid,
            'width'  => $width,
            'height' => $height,
        );
        return $arrRes;
    }
}
