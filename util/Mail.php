<?php
/**
 * @file Mail.php
 * @author liuguozheng@zuoyebang.com
 * @date 2017年3月10日 下午7:51:09
 * @version $Revision$
 * @brief 发邮件
 *
 **/
class Hk_Util_Mail {
    /**
     * 发送邮件
     * @param string $tos 收件人，如单个收件人"liuguozheng@zuoyebang.com"，多个收件人"liuguozheng@zuoyebang.com,lgz@zuoyebang.com"
     * @param string $subject 主题，如"xxx例行统计"
     * @param string $content 正文内容，支持html格式，如<title>正文</title>
     * @param array $files 附件列表，如array('/tmp/a.txt', '/home/homework/c.csv', '/tmp/bb.xls')
     * @return boolean 成功true，失败false，错误信息在warning日志中
     */
    public static function sendMail($tos, $subject, $content, $files=array()) {
        if ($tos == '' || $subject == '' || $content == '' || !is_array($files)) {
            Bd_Log::warning("param error, tos[$tos], subject[$subject] content[$content] files[$files]");
            return false;
        }
        $datas = array(
            'tos'     => $tos,
            'subject' => $subject,
            'content' => $content,
            'format'  => 'html',
        );
        if (!empty($files)) {
            $attachs = array();
            $datas['attachNum'] = count($files);
            $i = 1;
            foreach ($files as $file) {
                $attachs['attach'.$i] = $file;
                $i++;
            }
            return self::sendAttachMail($datas, $attachs);
        } else {
            return self::sendNormalMail($datas);
        }
    }

    private static function sendNormalMail($datas) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://proxy.zuoyebang.com:1925/api/mail');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datas));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $str    = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        if ($errno) {
            Bd_Log::warning("send mail fail, errno[$errno] errmsg[$errmsg]");
            return false;
        }
        $str = trim($str);
        if ($str != '{"status":0,"msg":"ok"}') {
            Bd_Log::warning("send mail error, errmsg[$str]");
            return false;
        }
        return true;
    }

    private static function sendAttachMail($datas, $attachs) {
        $disallow = array("\0", "\"", "\r", "\n");
        foreach ($datas as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "Content-Type: text/plain; charset=utf-8",
                "",
                filter_var($v),
            ));
        }
        foreach ($attachs as $k => $v) {
            switch (true) {
                case false === $v = realpath(filter_var($v)):
                case !is_file($v):
                case !is_readable($v):
                    continue;
            }
            $data = file_get_contents($v);
            $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
            $k = str_replace($disallow, "_", $k);
            $v = str_replace($disallow, "_", $v);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
                "Content-Type: application/octet-stream",
                "",
                $data,
            ));
        }
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });
        $body[] = "--{$boundary}--";
        $body[] = "";
        $body   = implode("\r\n", $body);
        $header = array(
            "Expect: 100-continue",
            "Content-Type: multipart/form-data; boundary={$boundary}",
        );
        $ch = curl_init('http://proxy.zuoyebang.com:1925/api/attachmail');
        $options = array(
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_RETURNTRANSFER => true,
        );
        @curl_setopt_array($ch, $options);
        $str    = curl_exec($ch);
        $errno  = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        if ($errno) {
            Bd_Log::warning("send mail fail, errno[$errno] errmsg[$errmsg]");
            return false;
        }
        $str = trim($str);
        if ($str != '{"status":0,"msg":"ok"}') {
            Bd_Log::warning("send mail error, errmsg[$str]");
            return false;
        }
        return true;
    }
}
