<?php

/**
* @file Tcos.php
* @brief 腾讯云对象存储服务
* @author guobaoshan
* @version 1.0
* @date 2017-11-11
*/
require_once LIB_PATH . "/ext/tcossdk/include.php";

use QCloud\Cos\Api;

class Hk_Service_Tcos {

    const COS_NAMESPACE_IMAGE_SRC   = 'picbj.myqcloud.com';  //cos原图域名
    const COS_NAMESPACE_IMAGE_SMALL = 'picbj.myqcloud.com'; //cos缩略图域名

    private static $CosConfig    = null;
    private static $CosConfigMap = array();

    /**
    * @brief 上传本地文件到Cos
    *
    * @param $cosName   cos服务名
    * @param $filePath  本地文件路径
    * @param $fileType  上传到cos的文件后缀，默认为jpg
    * @param $fileName  上传到cos的文件名，默认为空，自动生成文件名
    * @param $overwrite 同名文件是否强制覆盖，0-不强制覆盖，1-强制覆盖
    * @param $needsize  是否需要在pid中写宽高，0-不需要，1-需要
    *
    * @return boolean|string  正常返回可以访问的url
    */
    public static function uploadLocalFile($cosName, $filePath, $fileType = "jpg", $fileName = null, $overwrite = 0, $needsize = 0) {
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        if (!file_exists($filePath)) {
            Bd_Log::warning("local file $filePath not exist");
            return false;
        }
        $fileSize = filesize($filePath);
        if ($fileSize > intval($config['filesize_limit'])) {
            Bd_Log::warning("local file $filePath exceeds size limit ".$config['filesize_limit']);
            return false;
        }
        $dstFilePath = '';
        if (!empty($fileName)) {
            $dstFilePath = "/$fileName.$fileType";
        } else {
            $fileName = $config['file_prefix'] . md5_file($filePath);
            if ($needsize) {
                $imgInfo  = getimagesize($filePath);
                $fileName = $fileName . "_" . $imgInfo[0] . "_" . $imgInfo[1];
            }
            $dstFilePath = "/$fileName.$fileType";
        }
        if(!empty($config['directory'])){
            $dstFilePath = '/'.$config['directory'].$dstFilePath;
        }
        $cosApi = new Api($config);
        $insertOnly = $overwrite == 0 ? 1 : 0;
        $ret = $cosApi->upload($config['bucket'], $filePath, $dstFilePath, null, null, $insertOnly);
        if ($ret['code'] !== 0) {
            Bd_Log::warning("upload file to $dstFilePath failed, detail: " . $ret['message']);
            return false;
        }
        return $ret['data']['access_url'];
    }

    /**
    * @brief 上传文件内容到cos
    *
    * @param $cosName    cos服务名
    * @param $content    文件内容
    * @param $fileType   文件类型
    * @param $fileName   指定文件名
    * @param $overwrite  是否强制覆盖
    * @param $needsize   是否需要在pid中写宽高，0-不需要，1-需要
    *
    * @return boolean|string  正常返回可以访问的url
    */
    public static function uploadFileContent($cosName, $content, $fileType = "jpg", $fileName = null, $overwrite = 0, $needsize = 0) {
        if (empty($content)) {
            Bd_Log::warning("file content is empty!");
            return false;
        }
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        $fileSize = strlen($content);
        if ($fileSize > intval($config['filesize_limit'])) {
            Bd_Log::warning("file content length exceeds size limit ".$config['filesize_limit']);
            return false;
        }
        $dstFilePath = '';
        if (empty($fileName)) {
            $fileName = $config['file_prefix'] . md5($content);
            if ($needsize) {
                $imgInfo  = getimagesize($filePath);
                $fileName = $fileName . "_" . $imgInfo[0] . "_" . $imgInfo[1];
            }
        }
        if(empty($config['directory'])){
            $dstFilePath = "/$fileName.$fileType";
        }else{
            $dstFilePath = "/" . $config['directory'] . "/$fileName.$fileType";
        }
        $cosApi = new Api($config);
        $insertOnly = $overwrite == 0 ? 1 : 0;
        $ret = $cosApi->uploadBuffer($config['bucket'], $content, $dstFilePath, null, $insertOnly);
        if ($ret['code'] !== 0) {
            Bd_Log::warning("upload file content to $dstFilePath failed, detail: " . $ret['message']);
            return false;
        }
        return $ret['data']['access_url'];
    }

    /**
    * @brief 根据文件名和文件类型获取cos url地址
    *
    * @param $cosName
    * @param $fileName
    * @param $fileType
    *
    * @return
    */
    public static function getUrlByFileName($cosName, $fileName, $fileType) {
        if (empty($fileName)) {
            return '';
        }
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        $bucket     = strval($config['bucket']);
        $appId      = strval($config['app_id']);
        $directory  = strval($config['directory']);
        if(!empty($directory)){
            return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$directory/$fileName.$fileType";
        }else{
            return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$fileName.$fileType";
        }
    }

    /**
    * @brief 根据pid获取cos原图url地址
    *
    * @param $cosName   cos服务名
    * @param $pid       图片pid
    * @param $fileType  在cos的文件名后缀，默认jpg
    *
    * @return boolean|string 正常返回原图url
    */
    public static function getImageUrlByPid($cosName, $pid, $fileType = 'jpg') {
        if (empty($pid)) {
            return '';
        }
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        $bucket     = strval($config['bucket']);
        $appId      = strval($config['app_id']);
        $directory  = strval($config['directory']);
        $prefix     = strval($config['file_prefix']);
        if (preg_match("/${prefix}/", $pid, $out) == 1) {
            if(!empty($directory)){
                return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$directory/$pid.$fileType";
            }else{
                return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$pid.$fileType";
            }
        } else {
            // 解决测试环境为正式环境pid拼接url失败的问题
            $config = self::getCosConfig($cosName);
            if (false === $config) {
                return false;
            }
            $bucket     = strval($config['bucket']);
            $appId      = strval($config['app_id']);
            $directory  = strval($config['directory']);
            $prefix     = strval($config['file_prefix']);
            if (preg_match("/${prefix}/", $pid, $out) == 1) {
                if(!empty($directory)){
                    return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$directory/$pid.$fileType";
                }else{
                    return "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SRC . "/$pid.$fileType";
                }
            } else {
                echo $prefix."\n";
                return false;
            }
        }
    }

    //返回图片的meta信息（宽／高）
    public static function getImageMeta($bucket, $pid, $type = 'jpg'){
        $url = self::getImageUrlByPid($bucket, $pid, $type);
        $imageInfo = $url."?imageInfo";
        //请求curl
        $timeout = 1000;//ms
        $header = array();
        $method = "GET";
        $header[] = 'Method:'.$method;
        $header[] = 'User-Agent: cos-php-sdk-v4.3.7';
        $header[] = 'Connection: keep-alive';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_URL, $imageInfo);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);//连接超时，ms
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout); //执行超时间，ms
        # curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_PROXY, "proxy.zuoyebang.com:80");
       // curl_setopt($ch, CURLOPT_PROXY, "proxy.zuoyebang.com:80");

        $output     = curl_exec($ch);
        if (false === $output) {
            $error  = curl_error($ch);
            Bd_Log::warning("Tcos getImageMeta error[$error] url[$imageInfo]");
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        $arrOutput = json_decode($output,true);
        if(empty($arrOutput)){
            Bd_Log::warning('Tcos getImageMeta return error'. $output);
            return false;
        }
        $arrRes = array(
            'ext'   => $arrOutput['format'],# 图片扩展名
            'width' => $arrOutput['width'],
            'height' => $arrOutput['height'],
            'size'  => $arrOutput['size'],
            'bucket' => $bucket,
            'pid'    => $pid,
            'sourceUrl' => $url,
        );
        return  $arrRes;
    }

    /**
    * @brief 根据pid获取cos（实际是万象优图服务）缩略图url地址
    *
    * @param $cosName   cos服务名
    * @param $pid       图片pid
    * @param $thumbnail 缩略方案，可以指定限制最大宽度或最大高度或同时限制，格式为"w/number","h/number","w/num/h/num"
    * @param $fileType  在cos的文件名后缀，默认为jpg
    * @param $outType   指定缩略图的格式，默认和原图一致
    *
    * @return boolean|string 正常返回缩略图url
    */
    public static function getThumbnailUrlByPid($cosName, $pid, $thumbnail = "w/270", $fileType = 'jpg', $outType = '') {
        if (empty($pid)) {
            return '';
        }
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        // 先检查是否支持缩略图
        if (!isset($config['thumbnail']) || intval($config['thumbnail']) != 1) {
            Bd_Log::warning("CosServ $cosName not support thumbnail!");
            return false;
        }
        $bucket     = strval($config['bucket']);
        $appId      = strval($config['app_id']);
        $directory  = strval($config['directory']);
        $prefix     = strval($config['file_prefix']);
        if (preg_match("/${prefix}/", $pid, $out) == 1) {
            if(!empty($directory)){
                $url = "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SMALL . "/$directory/$pid.$fileType?imageView2/2/$thumbnail";
            }else{
                $url = "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SMALL . "/$pid.$fileType?imageView2/2/$thumbnail";
            }
        } else {
            // 解决测试环境为正式环境pid拼接url失败的问题
            $config = self::getCosConfig($cosName);
            if (false === $config) {
                return false;
            }
            $bucket     = strval($config['bucket']);
            $appId      = strval($config['app_id']);
            $directory  = strval($config['directory']);
            $prefix     = strval($config['file_prefix']);
            if (preg_match("/${prefix}/", $pid, $out) == 1) {
                if(!empty($directory)){
                    $url = "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SMALL . "/$directory/$pid.$fileType?imageView2/2/$thumbnail";
                }else{
                    $url = "http://$bucket-$appId." . self::COS_NAMESPACE_IMAGE_SMALL . "/$pid.$fileType?imageView2/2/$thumbnail";
                }
            } else {
                return false;
            }
        }
        if (!empty($outType)) {
            $url .= "/format/$outType";
        }
        return $url;
    }

    /**
    * @brief 简单检查下pid是否符合命名规范
    *
    * @param $pid
    *
    * @return
    */
    public static function checkPidValid($pid) {
        $pattern = '/^(zyb|qa)([\d]*)_[0-9a-zA-Z]+(\.[0-9a-zA-Z]+)?$/';
        if (preg_match($pattern, $pid, $out) === 1) {
            return true;
        }
        return false;
    }

    /**
    * @brief 删除腾讯云cos上面的文件
    *
    * @param $cosName
    * @param $pid
    * @param $fileType
    *
    * @return
    */
    public static function deleteYunFile($cosName, $pid, $fileType = 'jpg') {
        if (empty($pid)) {
            return true;
        }
        $config = self::getCosConfig($cosName);
        if (false === $config) {
            return false;
        }
        $bucket     = strval($config['bucket']);
        $directory  = strval($config['directory']);
        if(!empty($directory)){
            $dstFile    = "/$directory/$pid.$fileType";
        }else{
            $dstFile    = "/$pid.$fileType";
        }
        $cosApi     = new Api($config);
        $res        = $cosApi->delFile($bucket, $dstFile);
        if (!isset($res['code']) || $res['code'] !== 0) {
            Bd_Log::warning("deleteYunFile failed, Detail : ".$res['message']);
            return false;
        }
        return true;
    }

    /**
    * @brief 读取指定cos服务配置
    *
    * @param $cosName
    *
    * @return
    */
    private static function getCosConfig($cosName) {
        if (self::$CosConfig === null) {
            self::$CosConfig = Bd_Conf::getConf('hk/cos');
        }
        $cosConfig = self::$CosConfig;
        if (!isset($cosConfig[$cosName])) {
            Bd_Log::warning("CosConfig of $cosName is null");
            return false;
        }
        $config = $cosConfig[$cosName];
        if (empty($config['bucket'])) {
            Bd_Log::warning("CosConfig of $cosName need bucket");
            return false;
        }
        if (empty($config['app_id'])) {
            Bd_Log::warning("CosConfig of $cosName need app_id");
            return false;
        }
        if (empty($config['secret_id'])) {
            Bd_Log::warning("CosConfig of $cosName need secret_id");
            return false;
        }
        if (empty($config['secret_key'])) {
            Bd_Log::warning("CosConfig of $cosName need secret_key");
            return false;
        }
        if (empty($config['region'])) {
            Bd_Log::warning("CosConfig of $cosName need region");
            return false;
        }
        if (empty($config['file_prefix'])) {
            Bd_Log::warning("CosConfig of $cosName need file_prefix");
            return false;
        }
        /*
        if (empty($config['directory'])) {
            Bd_Log::warning("CosConfig of $cosName need directory");
            return false;
        }*/
        if (empty($config['filesize_limit'])) {
            Bd_Log::warning("CosConfig of $cosName need filesize_limit");
            return false;
        }
        return $config;
    }
}