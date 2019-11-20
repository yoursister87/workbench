<?php


/**
 * 图片二维码服务
 *
 * @filesource hk/service/QRCode.php
 * @author  tangyang<tangyang@zuoyebang.com>
 * @version 1.0
 * @date    2018-04-23
 */
class Hk_Service_QRCode {


    private static $isInit = false;

    private static function init() {
        @require_once LIB_PATH . '/ext/phpqrcode/phpqrcode.php';       # 导入phpqrcode类库
        self::$isInit = true;
    }

    /**
     * 生成二维码，并上传二维码到bos，返回二维码图片url
     *
     * @param string        $content
     * @return string
     */
    public static function QRCode($content) {
        if (false === self::$isInit) {
            self::init();
        }

        $errorCorrectionLevel = 'L';            # 容错级别
        $matrixPointSize      = 6;              # 生成图片大小

        $name = md5($content) . '_' . time() . '.png';
        $path = '/tmp/' . $name;

        QRcode::png($content, $path, $errorCorrectionLevel, $matrixPointSize, 2);       # 生成二维码图片
        $content = @file_get_contents($path);           # 上传图片文件到BOS
        @unlink($path);

        $ret     = Hk_Util_Image::uploadImage($content);
        if (false === $ret) {
            return false;
        }

        # 获取图片URL
        $pid     = $ret['pid'];
        $host    = 'img.zuoyebang.cc';
        $bosConf = Bd_Conf::getConf('/hk/bos/bucket');
        if (isset($bosConf['image']['host'])) {
            $host = $bosConf['image']['host'];
        }

        $url = "http://{$host}/{$pid}.jpg";
        return $url;
    }


    /**
     * 生成图片二维码，返回图片原始数据
     *
     * @param string      $content
     * @param int         $imageSize 图片大小，1 - 6等级
     * @param string      $logoContent 需要合并logo的图片stream
     * @return mixed:string|boolean
     */
    public static function genQRcodeImage($content, $imageSize = 6, $logoContent = "") {
        if (false === self::$isInit) {
            self::init();
        }
        $elevel  = 'M';        # 容错级别
        $name    = md5($content) . '_' . time() . '.png';
        $tmpfile = '/tmp/' . $name;

        QRcode::png($content, $tmpfile, $elevel, $imageSize, 2);       # 生成二维码图片
        $qrcode  = @file_get_contents($tmpfile);
        @unlink($tmpfile);
        if (empty($logoContent)) {
            return $qrcode;
        }

        # 将logo合并进入二维码
        $qrImg   = imagecreatefromstring($qrcode);
        $logoImg = imagecreatefromstring($logoContent);

        $qrWidth    = imagesx($qrImg);   // 二维码图片宽度
        $qrHeight   = imagesy($qrImg);   // 二维码图片高度
        $logoWidth  = imagesx($logoImg); // logo图片宽度
        $logoHeight = imagesy($logoImg); // logo图片高度
        $dstW = $qrWidth / 5;
        $dstH = $logoHeight / ($logoWidth / $dstW);
        $dstX = ($qrWidth - $dstW) / 2;

        imagecopyresampled($qrImg, $logoImg, $dstX, $dstX, 0, 0, $dstW, $dstW, $logoWidth, $logoHeight);         # 重新组合图片并调整大小
        imagepng($qrImg, $tmpfile);
        imagedestroy($qrImg);
        imagedestroy($logoImg);

        $ret = @file_get_contents($tmpfile);
        @unlink($tmpfile);
        return $ret;
    }
}
